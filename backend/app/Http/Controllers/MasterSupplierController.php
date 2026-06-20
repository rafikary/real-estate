<?php

namespace App\Http\Controllers;

use App\Models\MasterSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterSupplierController extends Controller
{
    public function index()
    {
        return response()->json(MasterSupplier::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'code' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|string',
            'prov_id' => 'required|integer',
            'city_id' => 'required|integer',
            'district_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item = MasterSupplier::create($request->all());
        return response()->json(['message' => 'Created successfully', 'data' => $item], 201);
    }

    public function show($id)
    {
        $item = MasterSupplier::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        return response()->json($item);
    }

    public function update(Request $request, $id)
    {
        $item = MasterSupplier::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);

        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'code' => 'required|string',
            'name' => 'required|string',
            'address' => 'required|string',
            'prov_id' => 'required|integer',
            'city_id' => 'required|integer',
            'district_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $item->update($request->all());
        return response()->json(['message' => 'Updated successfully', 'data' => $item]);
    }

    public function destroy($id)
    {
        $item = MasterSupplier::find($id);
        if (!$item) return response()->json(['message' => 'Not found'], 404);
        $item->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
