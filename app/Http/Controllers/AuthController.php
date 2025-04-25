<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Дані не відповідають нашим записам.'],
            ]);
        }

        // Створення токена з можливими правами доступу
        return ['token' => $user->createToken($request->device_name)->plainTextToken];
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
