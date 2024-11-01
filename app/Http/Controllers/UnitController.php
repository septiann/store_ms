<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Requests\StoreUnitRequest;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $units = Unit::all();

        if (count($units) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $units,
            'errors' => []
        ], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        Unit::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Unit created successfully!',
            'data' => '',
            'errors' => []
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unit = Unit::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $unit,
            'errors' => []
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'slug' => 'required',
            'short_code' => 'nullable'
        ]);

        // Action
        $post = Unit::findOrFail($id);

        $post->name = $request->name;
        $post->slug = $request->slug;
        $post->short_code = $request->short_code;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Unit updated successfully!',
            'data' => $post,
            'errors' => []
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteUnit = Unit::findOrFail($id);

        $deleteUnit->delete();

        return response()->json([
            'success' => true,
            'message' => 'Unit deleted successfully!',
            'data' => '',
            'errors' => []
        ], 200);
    }
}
