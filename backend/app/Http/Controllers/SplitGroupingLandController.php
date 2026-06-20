<?php

namespace App\Http\Controllers;

use App\Models\SplitGroupingLand;
use App\Models\LandBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SplitGroupingLandController extends Controller
{
    /** GET /api/v1/split-grouping-lands */
    public function index(Request $request)
    {
        $query = SplitGroupingLand::query()
            ->with('landBankSumber:id,code,name');

        if ($request->filled('status_doc') && $request->status_doc !== 'All') {
            $query->where('status_doc', $request->status_doc);
        }

        if ($request->filled('tipe')) {
            $query->where('tipe', $request->tipe);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_transaksi', 'like', "%{$s}%")
                  ->orWhere('nama_notaris', 'like', "%{$s}%")
                  ->orWhereHas('landBankSumber', fn($lb) => $lb->where('name', 'like', "%{$s}%")
                      ->orWhere('code', 'like', "%{$s}%"));
            });
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(10)
        );
    }

    /** POST /api/v1/split-grouping-lands */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tipe'                => 'required|in:Split,Grouping',
            'tgl_transaksi'       => 'required|date',
            'land_bank_id_sumber' => 'required|exists:m_landbank,id',
            'lampiran'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $count = SplitGroupingLand::count() + 1;
            $kode  = 'SPG-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $path = $request->file('lampiran')->store('public/split-grouping');
                $lampiranPath = Storage::url($path);
            }

            $landBankIdsHasil = null;
            if ($request->filled('land_bank_ids_hasil')) {
                $landBankIdsHasil = is_string($request->land_bank_ids_hasil)
                    ? json_decode($request->land_bank_ids_hasil, true)
                    : $request->land_bank_ids_hasil;
            }

            $record = SplitGroupingLand::create([
                'kode_transaksi'      => $kode,
                'tipe'                => $request->tipe,
                'tgl_transaksi'       => $request->tgl_transaksi,
                'land_bank_id_sumber' => $request->land_bank_id_sumber,
                'land_bank_ids_hasil' => $landBankIdsHasil,
                'keterangan'          => $request->keterangan,
                'nama_notaris'        => $request->nama_notaris,
                'no_akta'             => $request->no_akta,
                'lampiran_path'       => $lampiranPath,
                'status_doc'          => $request->input('status_doc', 'Draft'),
                'status'              => $request->input('status', 'Aktif'),
                'created_by'          => 'admin',
            ]);

            return response()->json([
                'message' => 'Transaksi ' . $request->tipe . ' lahan berhasil dibuat',
                'data'    => $record->load('landBankSumber:id,code,name'),
            ], 201);
        });
    }

    /** GET /api/v1/split-grouping-lands/{id} */
    public function show($id)
    {
        $record = SplitGroupingLand::with('landBankSumber')->find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($record);
    }

    /** PUT /api/v1/split-grouping-lands/{id} */
    public function update(Request $request, $id)
    {
        $record = SplitGroupingLand::find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'tipe'                => 'required|in:Split,Grouping',
            'tgl_transaksi'       => 'required|date',
            'land_bank_id_sumber' => 'required|exists:m_landbank,id',
            'lampiran'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $record) {
            if ($request->hasFile('lampiran')) {
                if ($record->lampiran_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $record->lampiran_path));
                }
                $path = $request->file('lampiran')->store('public/split-grouping');
                $record->lampiran_path = Storage::url($path);
            }

            $landBankIdsHasil = $record->land_bank_ids_hasil;
            if ($request->filled('land_bank_ids_hasil')) {
                $landBankIdsHasil = is_string($request->land_bank_ids_hasil)
                    ? json_decode($request->land_bank_ids_hasil, true)
                    : $request->land_bank_ids_hasil;
            }

            $record->fill($request->except(['lampiran', 'kode_transaksi', 'land_bank_ids_hasil']));
            $record->land_bank_ids_hasil = $landBankIdsHasil;
            $record->updated_by = 'admin';
            $record->save();

            return response()->json([
                'message' => 'Data berhasil diupdate',
                'data'    => $record->load('landBankSumber:id,code,name'),
            ]);
        });
    }

    /** DELETE /api/v1/split-grouping-lands/{id} */
    public function destroy($id)
    {
        $record = SplitGroupingLand::find($id);
        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($record->lampiran_path) {
            Storage::delete(str_replace('/storage/', 'public/', $record->lampiran_path));
        }

        $record->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }

    /** GET /api/v1/split-grouping-lands/land-bank-options */
    public function landBankOptions()
    {
        $options = LandBank::select('id', 'code', 'name', 'area_cert', 'cert_type')
            ->where('status', 'Aktif')
            ->orderBy('code')
            ->get();
        return response()->json($options);
    }
}
