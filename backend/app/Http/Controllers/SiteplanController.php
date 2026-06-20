<?php

namespace App\Http\Controllers;

use App\Models\Siteplan;
use App\Models\MasterProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiteplanController extends Controller
{
    /** GET /api/v1/siteplans */
    public function index(Request $request)
    {
        $query = Siteplan::query()
            ->with('masterProject:id,kode_proyek,nama_proyek');

        if ($request->filled('status_doc') && $request->status_doc !== 'All') {
            $query->where('status_doc', $request->status_doc);
        }

        if ($request->filled('tipe_unit')) {
            $query->where('tipe_unit', $request->tipe_unit);
        }

        if ($request->filled('master_project_id')) {
            $query->where('master_project_id', $request->master_project_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('kode_siteplan', 'like', "%{$s}%")
                  ->orWhere('nama_siteplan', 'like', "%{$s}%")
                  ->orWhere('tipe_unit', 'like', "%{$s}%")
                  ->orWhere('cluster', 'like', "%{$s}%")
                  ->orWhereHas('masterProject', fn($p) => $p->where('nama_proyek', 'like', "%{$s}%"));
            });
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(10)
        );
    }

    /** POST /api/v1/siteplans */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'master_project_id' => 'required|exists:master_projects,id',
            'nama_siteplan'     => 'required|string|max:255',
            'tipe_unit'         => 'required|string|max:100',
            'luas_tanah'        => 'required|numeric|min:0',
            'jumlah_unit'       => 'required|integer|min:0',
            'harga_dasar'       => 'nullable|numeric|min:0',
            'lampiran'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            $count = Siteplan::count() + 1;
            $kode  = 'SPL-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $path = $request->file('lampiran')->store('public/siteplans');
                $lampiranPath = Storage::url($path);
            }

            $siteplan = Siteplan::create(array_merge(
                $request->except(['lampiran']),
                [
                    'kode_siteplan' => $kode,
                    'lampiran_path' => $lampiranPath,
                    'created_by'    => 'admin',
                    'status_doc'    => $request->input('status_doc', 'Draft'),
                    'status'        => $request->input('status', 'Aktif'),
                ]
            ));

            return response()->json([
                'message' => 'Siteplan berhasil dibuat',
                'data'    => $siteplan->load('masterProject:id,kode_proyek,nama_proyek'),
            ], 201);
        });
    }

    /** GET /api/v1/siteplans/{id} */
    public function show($id)
    {
        $siteplan = Siteplan::with('masterProject:id,kode_proyek,nama_proyek')->find($id);
        if (!$siteplan) {
            return response()->json(['message' => 'Siteplan tidak ditemukan'], 404);
        }
        return response()->json($siteplan);
    }

    /** PUT /api/v1/siteplans/{id} */
    public function update(Request $request, $id)
    {
        $siteplan = Siteplan::find($id);
        if (!$siteplan) {
            return response()->json(['message' => 'Siteplan tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'master_project_id' => 'required|exists:master_projects,id',
            'nama_siteplan'     => 'required|string|max:255',
            'tipe_unit'         => 'required|string|max:100',
            'luas_tanah'        => 'required|numeric|min:0',
            'jumlah_unit'       => 'required|integer|min:0',
            'harga_dasar'       => 'nullable|numeric|min:0',
            'lampiran'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $siteplan) {
            if ($request->hasFile('lampiran')) {
                if ($siteplan->lampiran_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $siteplan->lampiran_path));
                }
                $path = $request->file('lampiran')->store('public/siteplans');
                $siteplan->lampiran_path = Storage::url($path);
            }

            $siteplan->fill($request->except(['lampiran', 'kode_siteplan']));
            $siteplan->updated_by = 'admin';
            $siteplan->save();

            return response()->json([
                'message' => 'Siteplan berhasil diupdate',
                'data'    => $siteplan->load('masterProject:id,kode_proyek,nama_proyek'),
            ]);
        });
    }

    /** DELETE /api/v1/siteplans/{id} */
    public function destroy($id)
    {
        $siteplan = Siteplan::find($id);
        if (!$siteplan) {
            return response()->json(['message' => 'Siteplan tidak ditemukan'], 404);
        }

        if ($siteplan->lampiran_path) {
            Storage::delete(str_replace('/storage/', 'public/', $siteplan->lampiran_path));
        }

        $siteplan->delete();
        return response()->json(['message' => 'Siteplan berhasil dihapus']);
    }

    /** GET /api/v1/siteplans/project-options */
    public function projectOptions()
    {
        $options = MasterProject::select('id', 'kode_proyek', 'nama_proyek')
            ->where('status', 'Aktif')
            ->orderBy('kode_proyek')
            ->get();
        return response()->json($options);
    }
}
