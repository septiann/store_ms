<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $categories = Category::all();

        if (count($categories) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $categories,
            'errors' => []
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        Category::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully!',
            'data' => [],
            'errors' => []
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $category,
            'errors' => []
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $validatedDate = $request->validate([
            'name' => 'required|unique:categories,name,'.$id,
            'slug' => 'required|unique:categories,slug,'.$id,
            'description' => 'nullable',
            'code' => 'required|string'
        ]);

        // Action
        $post = Category::findOrFail($id);

        $post->name = $request->name;
        $post->slug = $request->slug;
        $post->description = $request->description;
        $post->code = $request->code;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'data' => $post,
            'errors' => []
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteCategory = Category::findOrFail($id);

        $deleteCategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!',
            'data' => '',
            'errors' => []
        ], 200);
    }
}
