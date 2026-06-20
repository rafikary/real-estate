<?php

namespace App\Http\Controllers;

use App\Models\UpdateSertifikat;
use App\Models\LandBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UpdateSertifikatController extends Controller
{
    /** GET /api/v1/update-sertifikats */
    public function index(Request $request)
    {
        $query = UpdateSertifikat::query()
            ->with('landBank:id,code,name');

        if ($request->filled('status_doc') && $request->status_doc !== 'All') {
            $query->where('status_doc', $request->status_doc);
        }

        if ($request->filled('jenis_perubahan')) {
            $query->where('jenis_perubahan', $request->jenis_perubahan);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_update', 'like', "%{$s}%")
                  ->orWhere('no_perubahan', 'like', "%{$s}%")
                  ->orWhere('pemilik_baru', 'like', "%{$s}%")
                  ->orWhereHas('landBank', fn($lb) => $lb->where('name', 'like', "%{$s}%")
                      ->orWhere('code', 'like', "%{$s}%"));
            });
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(10)
        );
    }

    /** POST /api/v1/update-sertifikats */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'land_bank_id'    => 'required|exists:m_landbank,id',
            'jenis_perubahan' => 'required|string|max:100',
            'tgl_perubahan'   => 'nullable|date',
            'lampiran'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $count = UpdateSertifikat::count() + 1;
            $kode  = 'UPS-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $path = $request->file('lampiran')->store('public/update-sertifikats');
                $lampiranPath = Storage::url($path);
            }

            $record = UpdateSertifikat::create(array_merge(
                $request->except(['lampiran']),
                [
                    'kode_update'  => $kode,
                    'lampiran_path'=> $lampiranPath,
                    'created_by'   => 'admin',
                    'status_doc'   => $request->input('status_doc', 'Draft'),
                    'status'       => $request->input('status', 'Aktif'),
                ]
            ));

            // Update land bank data dengan sertifikat baru jika Approved
            if ($request->input('status_doc') === 'Approved') {
                $this->applyToLandBank($request, $record, $record->land_bank_id);
            }

            return response()->json([
                'message' => 'Update sertifikat berhasil dibuat',
                'data'    => $record->load('landBank:id,code,name'),
            ], 201);
        });
    }

    /** GET /api/v1/update-sertifikats/{id} */
    public function show($id)
    {
        $record = UpdateSertifikat::with('landBank:id,code,name,cert_type,cert_no,owner,area_cert')->find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($record);
    }

    /** PUT /api/v1/update-sertifikats/{id} */
    public function update(Request $request, $id)
    {
        $record = UpdateSertifikat::find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'land_bank_id'    => 'required|exists:m_landbank,id',
            'jenis_perubahan' => 'required|string|max:100',
            'tgl_perubahan'   => 'nullable|date',
            'lampiran'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $record) {
            if ($request->hasFile('lampiran')) {
                if ($record->lampiran_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $record->lampiran_path));
                }
                $path = $request->file('lampiran')->store('public/update-sertifikats');
                $record->lampiran_path = Storage::url($path);
            }

            $record->fill($request->except(['lampiran', 'kode_update']));
            $record->updated_by = 'admin';
            $record->save();

            if ($request->input('status_doc') === 'Approved') {
                $this->applyToLandBank($request, $record, $record->land_bank_id);
            }

            return response()->json([
                'message' => 'Update sertifikat berhasil diupdate',
                'data'    => $record->load('landBank:id,code,name'),
            ]);
        });
    }

    /** DELETE /api/v1/update-sertifikats/{id} */
    public function destroy($id)
    {
        $record = UpdateSertifikat::find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($record->lampiran_path) {
            Storage::delete(str_replace('/storage/', 'public/', $record->lampiran_path));
        }

        $record->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    /** GET /api/v1/update-sertifikats/land-bank-options */
    public function landBankOptions()
    {
        $options = LandBank::select('id', 'code', 'name', 'cert_type', 'cert_no', 'owner', 'area_cert')
            ->where('status', 'Aktif')
            ->orderBy('code')
            ->get();
        return response()->json($options);
    }

    /** Apply sertifikat baru to LandBank when Approved */
    private function applyToLandBank(Request $request, $record, $landBankId)
    {
        $lb = LandBank::find($landBankId);
        if (!$lb) return;

        // Save history first
        $seq = $lb->histories()->count() + 1;
        $lb->histories()->create([
            'seq' => $seq,
            'type' => $request->jenis_perubahan,
            'cert_type' => $request->jenis_sertifikat_baru ?: $lb->cert_type,
            'cert_no' => $request->no_sertifikat_baru ?: $lb->cert_no,
            'nib' => $request->nib_baru ?: $lb->nib,
            'owner' => $request->pemilik_baru ?: $lb->owner,
            'area_cert' => $request->luas_sertifikat_baru ?: $lb->area_cert,
            'area_real' => $lb->area_real,
            'cert_date' => $request->tanggal_terbit_baru ?: $lb->cert_date,
            'cert_date_to' => $request->masa_berlaku_baru ?: $lb->cert_date_to,
            'reference' => $record->kode_update,
            'image' => $record->lampiran_path,
        ]);

        // Then apply changes to main land bank info
        if ($request->filled('jenis_sertifikat_baru'))  $lb->cert_type   = $request->jenis_sertifikat_baru;
        if ($request->filled('no_sertifikat_baru'))     $lb->cert_no      = $request->no_sertifikat_baru;
        if ($request->filled('nib_baru'))               $lb->nib          = $request->nib_baru;
        if ($request->filled('pemilik_baru'))           $lb->owner        = $request->pemilik_baru;
        if ($request->filled('luas_sertifikat_baru'))   $lb->area_cert    = $request->luas_sertifikat_baru;
        if ($request->filled('tanggal_terbit_baru'))    $lb->cert_date    = $request->tanggal_terbit_baru;
        if ($request->filled('masa_berlaku_baru'))      $lb->cert_date_to = $request->masa_berlaku_baru;
        $lb->updated_by = 'admin';
        $lb->save();
    }
}
