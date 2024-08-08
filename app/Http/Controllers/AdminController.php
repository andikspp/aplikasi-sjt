<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionSet;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function loginAdmin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Cek kredensial admin
        $credentials = $request->only('username', 'password');

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);


        if (Auth::guard('admin')->attempt($credentials)) {
            // Jika berhasil, alihkan ke halaman dashboard admin
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function dashboard()
    {
        $admin = Auth::guard('admin')->user();
        $jumlahUser = User::count();
        $jumlahInstansi = User::distinct('instansi')->count('instansi');
        $jumlahUjianSelesai = QuizAttempt::count();
        $jumlahGuru = User::where('role', 'guru')->count();
        $jumlahKepalaSekolah = User::where('role', 'kepala sekolah')->count();

        return view('admin.dashboard', compact('admin', 'jumlahUser', 'jumlahInstansi', 'jumlahUjianSelesai', 'jumlahGuru', 'jumlahKepalaSekolah'));
    }

    public function soalPage()
    {
        $questionSets = QuestionSet::all();
        return view('admin.soal.index', compact('questionSets'));
    }

    public function soal()
    {
        return view('admin.soal.create');
    }

    public function storeQuestion(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:255',
            'question_set_id' => 'required|exists:question_sets,id',
            'option_a' => 'required|string|max:255',
            'score_a' => 'required|integer|in:1,2,3,4',
            'option_b' => 'required|string|max:255',
            'score_b' => 'required|integer|in:1,2,3,4',
            'option_c' => 'required|string|max:255',
            'score_c' => 'required|integer|in:1,2,3,4',
            'option_d' => 'required|string|max:255',
            'score_d' => 'required|integer|in:1,2,3,4',
        ]);

        // Menyimpan soal
        $question = Question::create([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
        ]);

        // Menyimpan jawaban
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => $validatedData['option_a'],
            'score' => $validatedData['score_a'],
        ]);
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => $validatedData['option_b'],
            'score' => $validatedData['score_b'],
        ]);
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => $validatedData['option_c'],
            'score' => $validatedData['score_c'],
        ]);
        Answer::create([
            'question_id' => $question->id,
            'answer_text' => $validatedData['option_d'],
            'score' => $validatedData['score_d'],
        ]);

        return redirect()->route('admin.soal.create')->with('success', 'Soal berhasil disimpan!');
    }

    public function resultPage()
    {
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.name',
                'users.email',
                'users.telepon',
                'users.instansi as instansi',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.started_at',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->get();

        return view('admin.hasil', ['results' => $results]);
    }

    public function showQuestions($question_set_id)
    {
        $questionSet = QuestionSet::findOrFail($question_set_id);
        $questions = $questionSet->questions()->with('answers')->get();
        return view('admin.soal.detail-soal', compact('questionSet', 'questions'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function registerAdmin()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate(
            [
                'username' => 'required|unique:admins|alpha_num|max:255',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'username.unique' => 'Username sudah dipakai.',
                'password.confirmed' => 'Password Konfirmasi tidak sesuai',
            ]
        );

        $admin = new Admin();
        $admin->username = $request->input('username');
        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return redirect()->route('admin.login')->with('success', 'Admin berhasil register');
    }

    public function dataPeserta()
{
    $results = DB::table('users')
        ->select('name', 'email', 'telepon', 'instansi', 'role')
        ->orderBy('name')
        ->paginate(50);

    return view('admin.user', compact('results'));
}
}

