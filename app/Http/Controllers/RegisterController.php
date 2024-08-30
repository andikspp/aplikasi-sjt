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

    public function registerKepsek(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordKs = 'sjtks123#';

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($passwordKs),
            'telepon' => $request->telepon,
            'instansi' => $request->instansi,
            'role' => $request->role,
        ]);

        $questionSets = QuestionSet::where('role', $request->role)->get();

        $randomQuestionSet = $questionSets->random();

        $user->question_set_id = $randomQuestionSet->id;

        $user->save();

        return redirect()->route('data.kepala_sekolah')->with('success', 'Pengguna berhasil ditambahkan');
    }

    public function registerGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordGuru = 'sjtguru123#';

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($passwordGuru),
            'telepon' => $request->telepon,
            'instansi' => $request->instansi,
            'role' => $request->role,
        ]);

        $questionSets = QuestionSet::where('role', $request->role)->get();

        $randomQuestionSet = $questionSets->random();

        $user->question_set_id = $randomQuestionSet->id;

        $user->save();

        return redirect()->route('data.guru')->with('success', 'Data berhasil ditambahkan');
    }
}
