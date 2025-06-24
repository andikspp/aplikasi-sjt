<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAdmin;
use App\Models\QuestionSet;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            'jenis_paud' => 'required|in:mitra,pembelajar',
            'role' => 'required|in:Kepala Sekolah',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordKepsek = 'sjtguru123#';

        try {
            $questionSets = QuestionSet::where('role', $request->role)->get();

            if ($questionSets->isEmpty()) {
                throw new \Exception('Tidak ada paket soal untuk role ini. Silakan tambahkan paket soal terlebih dahulu.');
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($passwordKepsek),
                'telepon' => $request->telepon,
                'instansi' => $request->instansi,
                'jenis_paud' => $request->jenis_paud,
                'role' => $request->role,
            ]);

            $randomQuestionSet = $questionSets->random();
            $user->question_set_id = $randomQuestionSet->id;
            $user->save();

            // Catat log admin
            LogAdmin::create([
                'admin_id'        => auth('admin')->id(),
                'admin_name'      => auth('admin')->user()->username ?? '-',
                'action'          => 'Menambahkan kepala sekolah: ' . $user->name . ' (username: ' . $user->username . ')',
                'ip_address'      => $request->ip(),
                'question_set_id' => $randomQuestionSet->id,
            ]);

            return redirect()->route('data.kepala_sekolah')->with('success', 'Akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['question_set' => $e->getMessage()])->withInput();
        }
    }

    public function registerGuru(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'jenis_paud' => 'required|in:mitra,pembelajar',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $passwordGuru = 'sjtguru123#';

        try {
            $questionSets = QuestionSet::where('role', $request->role)->get();

            if ($questionSets->isEmpty()) {
                throw new \Exception('Tidak ada paket soal untuk role ini. Silakan tambahkan paket soal terlebih dahulu.');
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($passwordGuru),
                'telepon' => $request->telepon,
                'instansi' => $request->instansi,
                'jenis_paud' => $request->jenis_paud,
                'role' => $request->role,
            ]);

            $randomQuestionSet = $questionSets->random();
            $user->question_set_id = $randomQuestionSet->id;
            $user->save();

            // Catat log admin
            LogAdmin::create([
                'admin_id'        => auth('admin')->id(),
                'admin_name'      => auth('admin')->user()->username ?? '-',
                'action'          => 'Menambahkan ' . strtolower($request->role) . ': ' . $user->name . ' (username: ' . $user->username . ')',
                'ip_address'      => $request->ip(),
                'question_set_id' => $randomQuestionSet->id,
            ]);

            return redirect()->route('data.guru')->with('success', 'Akun berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['question_set' => $e->getMessage()])->withInput();
        }
    }
}
