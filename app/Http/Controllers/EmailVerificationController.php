<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function verify($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Token verifikasi tidak valid.');
        }

        $user->email_verified_at = now();
        $user->email_verification_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi.');
    }

    public function verifyEmail(Request $request)
    {
        $request->user()->markEmailAsVerified();

        return redirect('/home')->with('success', 'Email verified successfully.');
    }
}
