<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $message = "";
        $users = User::all();

        if (count($users) < 1) {
            $message = "Data is empty";
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $users,
            'errors' => []
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(StoreUserRequest $request)
    {
        // Validation
        $validatedData = $request->validated();

        // Action
        User::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully!',
            'data' => '',
            'errors' => []
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $user,
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
            'name' => 'required|max:50|string',
            'email' => 'required|email:rfc,dns|max:30|unique:users,email,'.$id,
        ]);

        // Action
        $post = User::findOrFail($id);

        $post->name = $request->name;
        $post->email = $request->email;
        $post->role = $request->role;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'data' => $post,
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteUser = User::findOrFail($id);

        $deleteUser->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!',
            'data' => '',
            'errors' => []
        ]);
    }

    public function login(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $validatedData['email'])->first();
        if (!$user || Hash::check($validatedData['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized - Invalid credentials!',
                'data' => '',
                'errors' => []
            ], 401);
        }

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login success.',
            'data' => [
                'access_token' => $token
            ],
            'errors' => []
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout success.',
            'data' => [],
            'errors' => []
        ]);
    }
}
