<?php

namespace App\Http\Controllers;

use App\Models\MasterInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MasterInterfaceController extends Controller
{
    public function index()
    {
        return response()->json(MasterInterface::with('details')->get());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'var_id' => 'required|integer',
            'note' => 'nullable|string',
            'details' => 'nullable|array',
            'details.*.m_coa_id' => 'required|integer',
            'details.*.note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $item = MasterInterface::create($request->only(['type', 'var_id', 'note']));

            if ($request->has('details') && is_array($request->details)) {
                foreach ($request->details as $detail) {
                    $item->details()->create($detail);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Created successfully', 'data' => $item->load('details')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $item = MasterInterface::with('details')->find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = MasterInterface::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'var_id' => 'required|integer',
            'note' => 'nullable|string',
            'details' => 'nullable|array',
            'details.*.m_coa_id' => 'required|integer',
            'details.*.note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $item->update($request->only(['type', 'var_id', 'note']));

            if ($request->has('details') && is_array($request->details)) {
                $item->details()->delete();
                foreach ($request->details as $detail) {
                    $item->details()->create($detail);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Updated successfully', 'data' => $item->load('details')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $item = MasterInterface::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        $item->delete(); // This will not delete details unless cascade is on. Let's explicitly delete if needed, though foreign keys should cascade.
        // Explicitly delete details just in case:
        $item->details()->delete();
        $item->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
