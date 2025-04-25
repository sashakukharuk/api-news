<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class WebAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Дані не відповідають нашим записам.'],
            ]);
        }

        Auth::login($user);

        $response = response()->json(['message' => 'Логін успішний']);

        $response->withCookie(cookie()->make('XSRF-TOKEN', csrf_token(), 60));

        return $response;
    }

    public function refreshSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Користувач не авторизований'], 401);
        }

        Session::invalidate();
        Session::regenerateToken();
        Session::regenerate();

        return response()->json([
            'message' => 'Сесію оновлено успішно',
            'user' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $response = response()->json(['message' => 'Вихід успішний']);

        $response->withCookie(Cookie::forget('laravel_session'));

        return $response;
    }
}
