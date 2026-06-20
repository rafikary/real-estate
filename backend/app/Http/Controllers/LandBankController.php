<?php

namespace App\Http\Controllers;

use App\Models\LandBank;
use App\Models\LandBankLegalityHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LandBankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LandBank::query()->with('histories');

        // Filter by document status
        if ($request->has('status_doc') && $request->status_doc !== '' && $request->status_doc !== 'All') {
            $query->where('status_doc', $request->status_doc);
        }

        // Search in name, code, certificate number, or owner
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('cert_no', 'like', "%{$search}%")
                  ->orWhere('owner', 'like', "%{$search}%")
                  ->orWhere('loc', 'like', "%{$search}%");
            });
        }

        $landBanks = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($landBanks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'loc' => 'required|string',
            'prov_id' => 'required|integer',
            'city_id' => 'required|integer',
            'district_id' => 'required|integer',
            'cert_type' => 'required|string|max:255',
            'cert_no' => 'required|string|max:255',
            'area_cert' => 'required|numeric|min:0',
            'area_real' => 'required|numeric|min:0',
            'histories' => 'nullable|array',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            return DB::transaction(function () use ($request) {
                // Auto generate code: extract number from last code to prevent duplicates
                $latest = LandBank::where('code', 'like', 'LAND-%')->orderBy('code', 'desc')->first();
                if ($latest && preg_match('/LAND-(\d+)/', $latest->code, $matches)) {
                    $nextId = (int)$matches[1] + 1;
                } else {
                    $nextId = 1;
                }
                $code = 'LAND-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

                // Handle file upload
                $imagePath = null;
                if ($request->hasFile('lampiran')) {
                    $file = $request->file('lampiran');
                    $path = $file->store('public/attachments');
                    $imagePath = Storage::url($path);
                }

                // Create LandBank
                $landBank = LandBank::create(array_merge(
                    $request->except(['histories', 'lampiran']),
                    [
                        'code' => $code,
                        'image' => $imagePath,
                        'created_by' => 'admin', // mocked
                        'status_doc' => $request->input('status_doc', 'Draft'),
                        'status' => $request->input('status', 'Aktif'),
                    ]
                ));

                // Save legality history records if present
                if ($request->has('histories')) {
                    $histories = $request->input('histories');
                    if (is_string($histories)) {
                        $histories = json_decode($histories, true);
                    }
                    if (is_array($histories)) {
                        foreach ($histories as $history) {
                            $landBank->histories()->create($history);
                        }
                    }
                }

                return response()->json([
                    'message' => 'Land Bank created successfully',
                    'data' => $landBank->load('histories')
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create Land Bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $landBank = LandBank::with('histories')->find($id);

        if (!$landBank) {
            return response()->json(['message' => 'Land Bank not found'], 404);
        }

        return response()->json($landBank);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $landBank = LandBank::find($id);

        if (!$landBank) {
            return response()->json(['message' => 'Land Bank not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'loc' => 'required|string',
            'prov_id' => 'required|integer',
            'city_id' => 'required|integer',
            'district_id' => 'required|integer',
            'cert_type' => 'required|string|max:255',
            'cert_no' => 'required|string|max:255',
            'area_cert' => 'required|numeric|min:0',
            'area_real' => 'required|numeric|min:0',
            'histories' => 'nullable|array',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            return DB::transaction(function () use ($request, $landBank) {
                // Handle file upload
                if ($request->hasFile('lampiran')) {
                    // Delete old file if exists
                    if ($landBank->image) {
                        $oldPath = str_replace('/storage/', 'public/', $landBank->image);
                        Storage::delete($oldPath);
                    }
                    $file = $request->file('lampiran');
                    $path = $file->store('public/attachments');
                    $landBank->image = Storage::url($path);
                }

                // Update fields
                $landBank->fill($request->except(['histories', 'lampiran', 'code']));
                $landBank->updated_by = 'admin'; // mocked
                $landBank->save();

                // Synchronize legality history records (delete existing and insert new)
                if ($request->has('histories')) {
                    $histories = $request->input('histories');
                    if (is_string($histories)) {
                        $histories = json_decode($histories, true);
                    }
                    if (is_array($histories)) {
                        $landBank->histories()->delete();
                        foreach ($histories as $history) {
                            $landBank->histories()->create($history);
                        }
                    }
                }

                return response()->json([
                    'message' => 'Land Bank updated successfully',
                    'data' => $landBank->load('histories')
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Land Bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $landBank = LandBank::find($id);

        if (!$landBank) {
            return response()->json(['message' => 'Land Bank not found'], 404);
        }

        try {
            // Delete attachment if exists
            if ($landBank->image) {
                $oldPath = str_replace('/storage/', 'public/', $landBank->image);
                Storage::delete($oldPath);
            }

            $landBank->delete(); // Cascades to histories due to foreign key constraints

            return response()->json(['message' => 'Land Bank deleted successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Land Bank',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
