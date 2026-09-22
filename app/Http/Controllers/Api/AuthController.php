<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

    public function forgotPassword(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        $user = User::where('email', $data['email'])->first();

        // Same response whether or not the email exists, so this endpoint
        // can't be used to check which addresses have an account.
        if ($user) {
            $token = Str::random(64);

            DB::table('password_resets')->where('email', $user->email)->delete();
            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => Hash::make($token),
                'created_at' => now(),
            ]);

            $resetUrl = rtrim(config('app.frontend_url'), '/')
                . '/reset-password?token=' . $token . '&email=' . urlencode($user->email);

            Mail::to($user->email)->send(new ResetPasswordMail($user, $resetUrl));
        }

        return response()->json(['message' => 'Jika email terdaftar, link reset password sudah dikirim.']);
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_resets')->where('email', $data['email'])->first();

        if (! $record || ! Hash::check($data['token'], $record->token)) {
            return response()->json(['message' => 'Link reset password tidak valid.'], 422);
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $data['email'])->delete();

            return response()->json(['message' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.'], 422);
        }

        $user = User::where('email', $data['email'])->first();
        $user->password = bcrypt($data['password']);
        $user->save();

        DB::table('password_resets')->where('email', $data['email'])->delete();

        return response()->json(['message' => 'Password berhasil diubah. Silakan login.']);
    }
}
