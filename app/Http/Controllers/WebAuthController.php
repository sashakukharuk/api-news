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


class WebAuthController extends Controller
{
    public function login(WebLoginResource $request)
    {
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

        return $response;
    }

    public function refreshSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User is not authorized'], 401);
        }

        Session::invalidate();
        Session::regenerateToken();
        Session::regenerate();

        return response()->json([
            'message' => 'Session updated successfully',
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $response = response()->json(['message' => 'Logout successful']);

        $response->withCookie(Cookie::forget('laravel_session'));

        return $response;
    }
}
