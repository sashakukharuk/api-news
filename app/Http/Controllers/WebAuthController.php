<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Auth\WebLoginResource;    
use Illuminate\Support\Facades\Log; 

class WebAuthController extends Controller
{
    public function login(WebLoginResource $request)
    {
        Log::info('Start Controller:WebAuthController.login', ['email' => $request->email]);
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);     
        }

        Auth::login($user);

        $response = response()->json(['message' => 'Login successful']);

        $response->withCookie(cookie()->make('XSRF-TOKEN', csrf_token(), 60));

        Log::info('End Controller:WebAuthController.login', ['email' => $request->email]);

        return $response;
    }

    public function refreshSession(Request $request)
    {
        Log::info('Start Controller:WebAuthController.refreshSession', ['user_id' => auth()->id()]);

        if (!Auth::check()) {
            Log::info('End Controller:WebAuthController.refreshSession', ['message' => 'User is not authorized', 'status' => 401]);

            return response()->json(['message' => 'User is not authorized'], 401);
        }

        Session::invalidate();
        Session::regenerateToken();
        Session::regenerate();

        Log::info('End Controller:WebAuthController.refreshSession', ['user_id' => auth()->id()]);

        return response()->json([
            'message' => 'Session updated successfully',
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('Start Controller:WebAuthController.logout', ['user_id' => auth()->id()]);

        Auth::logout();

        $response = response()->json(['message' => 'Logout successful']);

        $response->withCookie(Cookie::forget('laravel_session'));

        Log::info('End Controller:WebAuthController.logout', ['user_id' => auth()->id()]);

        return $response;
    }
}
