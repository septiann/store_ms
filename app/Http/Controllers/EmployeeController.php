<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $employees = Employee::all();

        if (count($employees) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $employees,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        Employee::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $employee,
            'errors' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'name' => 'required',
            'role' => 'required',
            'salary' => 'required',
            'hired_at' => 'required',
            'status' => 'required'
        ]);

        // Action
        $post = Employee::findOrFail($id);

        $post->name = $request->name;
        $post->role = $request->role;
        $post->salary = $request->salary;
        $post->hired_at = $request->hired_at;
        $post->status = $request->status;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully!',
            'data' => $post,
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteEmployee = Employee::findOrFail($id);

        $deleteEmployee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully!',
            'data' => '',
            'errors' => []
        ]);
    }
}
