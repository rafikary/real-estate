<?php

namespace App\Http\Controllers;

use App\Models\MasterProject;
use App\Models\LandBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MasterProjectController extends Controller
{
    /** GET /api/v1/projects — list + search + filter */
    public function index(Request $request)
    {
        $query = MasterProject::query()->with('landBank:id,code,name');

        // Status filter
        if ($request->filled('status_doc') && $request->status_doc !== 'All') {
            $query->where('status_doc', $request->status_doc);
        }

        // Tipe proyek filter
        if ($request->filled('tipe_proyek')) {
            $query->where('tipe_proyek', $request->tipe_proyek);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('nama_proyek', 'like', "%{$s}%")
                  ->orWhere('kode_proyek', 'like', "%{$s}%")
                  ->orWhere('kota_kabupaten', 'like', "%{$s}%")
                  ->orWhere('nama_developer', 'like', "%{$s}%")
                  ->orWhere('tipe_proyek', 'like', "%{$s}%");
            });
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(10)
        );
    }

    /** POST /api/v1/projects */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_proyek'              => 'required|string|max:255',
            'provinsi'                 => 'required|string|max:255',
            'kota_kabupaten'           => 'required|string|max:255',
            'kecamatan'                => 'required|string|max:255',
            'kelurahan'                => 'required|string|max:255',
            'tipe_proyek'              => 'required|string|max:100',
            'luas_total'               => 'required|numeric|min:0',
            'nilai_investasi'          => 'nullable|numeric|min:0',
            'tanggal_mulai'            => 'nullable|date',
            'tanggal_selesai_estimasi' => 'nullable|date',
            'land_bank_id'             => 'nullable|exists:m_landbank,id',
            'lampiran'                 => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request) {
            // Auto-generate kode
            $count = MasterProject::count() + 1;
            $kode  = 'PRJ-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            $lampiranPath = null;
            if ($request->hasFile('lampiran')) {
                $path = $request->file('lampiran')->store('public/projects');
                $lampiranPath = Storage::url($path);
            }

            $project = MasterProject::create(array_merge(
                $request->except(['lampiran']),
                [
                    'kode_proyek'  => $kode,
                    'lampiran_path'=> $lampiranPath,
                    'created_by'   => 'admin',
                    'status_doc'   => $request->input('status_doc', 'Draft'),
                    'status'       => $request->input('status', 'Aktif'),
                ]
            ));

            return response()->json([
                'message' => 'Proyek berhasil dibuat',
                'data'    => $project->load('landBank:id,code,name'),
            ], 201);
        });
    }

    /** GET /api/v1/projects/{id} */
    public function show($id)
    {
        $project = MasterProject::with('landBank:id,code,name')->find($id);

        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        return response()->json($project);
    }

    /** PUT /api/v1/projects/{id} */
    public function update(Request $request, $id)
    {
        $project = MasterProject::find($id);
        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_proyek'              => 'required|string|max:255',
            'provinsi'                 => 'required|string|max:255',
            'kota_kabupaten'           => 'required|string|max:255',
            'kecamatan'                => 'required|string|max:255',
            'kelurahan'                => 'required|string|max:255',
            'tipe_proyek'              => 'required|string|max:100',
            'luas_total'               => 'required|numeric|min:0',
            'nilai_investasi'          => 'nullable|numeric|min:0',
            'tanggal_mulai'            => 'nullable|date',
            'tanggal_selesai_estimasi' => 'nullable|date',
            'land_bank_id'             => 'nullable|exists:m_landbank,id',
            'lampiran'                 => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        return DB::transaction(function () use ($request, $project) {
            if ($request->hasFile('lampiran')) {
                if ($project->lampiran_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $project->lampiran_path));
                }
                $path = $request->file('lampiran')->store('public/projects');
                $project->lampiran_path = Storage::url($path);
            }

            $project->fill($request->except(['lampiran', 'kode_proyek']));
            $project->updated_by = 'admin';
            $project->save();

            return response()->json([
                'message' => 'Proyek berhasil diupdate',
                'data'    => $project->load('landBank:id,code,name'),
            ]);
        });
    }

    /** DELETE /api/v1/projects/{id} */
    public function destroy($id)
    {
        $project = MasterProject::find($id);
        if (!$project) {
            return response()->json(['message' => 'Proyek tidak ditemukan'], 404);
        }

        if ($project->lampiran_path) {
            Storage::delete(str_replace('/storage/', 'public/', $project->lampiran_path));
        }

        $project->delete();

        return response()->json(['message' => 'Proyek berhasil dihapus']);
    }

    /** GET /api/v1/projects/land-banks-options — untuk dropdown di form */
    public function landBankOptions()
    {
        $options = LandBank::select('id', 'code', 'name')
            ->where('status', 'Aktif')
            ->orderBy('code')
            ->get();

        return response()->json($options);
    }
}
