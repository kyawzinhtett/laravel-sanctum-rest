<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
        ]);

        $token = $user->createToken('my-secret-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token,
            'status' => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        // Check email & password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong credentials!',
                'status' => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('my-secret-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token,
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully!',
            'status' => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
