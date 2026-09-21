<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Frontend and backend live on different domains (e.g. vercel.app vs
     * fly.dev) in production, so JS on the frontend's origin can never read
     * the XSRF-TOKEN cookie the backend sets — cross-site cookies aren't
     * visible via document.cookie on the other domain. Returning the same
     * encrypted token in the response body lets the frontend send it back
     * as X-XSRF-TOKEN without depending on cookie readability.
     */
    public function csrfCookie(Request $request)
    {
        return response()->json([
            'message' => 'ok',
            // false = don't serialize before encrypting: VerifyCsrfToken
            // decrypts the X-XSRF-TOKEN header the same way (unserialized),
            // matching how Laravel's own XSRF-TOKEN cookie is encrypted.
            'csrf_token' => encrypt($request->session()->token(), false),
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::guard('web')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'user' => Auth::guard('web')->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'ok']);
    }

    public function user(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json(['user' => $user]);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::guard('web')->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $data = $request->validate([
            'password_lama' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if (! Hash::check($data['password_lama'], $user->password)) {
            return response()->json(['message' => 'Password lama salah.'], 422);
        }

        $user->password = bcrypt($data['password']);
        $user->save();

        return response()->json(['message' => 'Berhasil mengubah password.']);
    }
}
