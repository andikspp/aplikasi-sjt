<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Str;
use App\Models\QuestionSet;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verification_token' => Str::random(32),
            'telepon' => $request->telepon,
            'instansi' => $request->instansi,
            'role' => $request->role,
        ]);

        $questionSets = QuestionSet::all();

        $randomQuestionSet = $questionSets->random();

        $user->question_set_id = $randomQuestionSet->id;

        $user->save();

        Mail::to($user->email)->send(new EmailVerificationMail($user));

        return redirect()->route('login')->with('success', 'Registrasi berhasil. Silakan cek email Anda untuk verifikasi.');
    }
}
