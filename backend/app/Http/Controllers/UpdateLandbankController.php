<?php

namespace App\Http\Controllers;

use App\Models\UpdateLandbank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateLandbankController extends Controller
{
    public function index()
    {
        return response()->json(UpdateLandbank::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // TODO: Validation rules
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = UpdateLandbank::create($request->all());
        return response()->json(['message' => 'Created successfully', 'data' => $item], 201);
    }

    public function show($id)
    {
        $item = UpdateLandbank::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = UpdateLandbank::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            // TODO: Validation rules
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = UpdateLandbank::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        $item->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
