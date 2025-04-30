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
        Log::info('Start Controller:AuthController.login', $request->all());

        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (!$user || ! Hash::check($data['password'], $user->password)) {

            $result = ['message' => 'The provided credentials are incorrect.', 'status' => 401];

            Log::info('End Controller:AuthController.login', $result);

            return response()->json($result, 401);
        }

        $result = ['token' => $user->createToken($data['device_name'])->plainTextToken];
        
        Log::info('End Controller:AuthController.login', $result);

        return $result;
    }

    public function logout(Request $request)
    {
        Log::info('Start Controller:AuthController.logout', ['user_id' => auth()->id()]);

        $request->user()->currentAccessToken()->delete();

        Log::info('End Controller:AuthController.logout', ['user_id' => auth()->id()]);

        return response()->json(['message' => 'Logged out']);
    }

    public function refresh(Request $request)
    {
        Log::info('Start Controller:AuthController.refresh', ['user_id' => auth()->id()]);

        $user = $request->user();
        
        $request->user()->currentAccessToken()->delete();

        $token = $user->createToken($request->header('User-Agent'))->plainTextToken;

        Log::info('End Controller:AuthController.refresh', ['user_id' => auth()->id()]);

        return response()
            ->json([
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]);
    }
}
