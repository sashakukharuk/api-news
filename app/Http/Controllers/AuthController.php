<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        return ['token' => $user->createToken($data['device_name'])->plainTextToken];
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function refresh(Request $request)
    {
        $user = $request->user();
        
        $request->user()->currentAccessToken()->delete();

        $token = $user->createToken($request->header('User-Agent'))->plainTextToken;

        return response()
            ->json([
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]);
    }
}
