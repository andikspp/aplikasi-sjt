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
use App\Models\UserAnswer;

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
        return view('admin.hasil.index');
    }

    public function resultGuru()
    {
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.telepon',
                'users.instansi as instansi',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'guru')
            ->get();

        return view('admin.hasil.guru', ['results' => $results]);
    }

    public function resultKepsek()
    {
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.telepon',
                'users.instansi as instansi',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'kepala sekolah')
            ->get();

        return view('admin.hasil.kepsek', ['results' => $results]);
    }

    public function showQuestions($question_set_id)
    {
        $questionSet = QuestionSet::findOrFail($question_set_id);
        $questions = $questionSet->questions()->with('answers')->get();
        return view('admin.soal.detail-soal', compact('questionSet', 'questions'));
    }

    public function showEditForm($id)
    {
        $question = Question::with('answers')->findOrFail($id);

        return view('admin.soal.edit-soal', compact('question'));
    }


    public function editQuestions(Request $request, $id)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:255',
            'question_set_id' => 'required|exists:question_sets,id',
            'answers.*.answer_text' => 'required|string|max:255',
            'answers.*.score' => 'required|integer|in:1,2,3,4',
        ]);

        $question = Question::findOrFail($id);

        // Update the question text and question_set_id
        $question->update([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
        ]);

        // Update the answers
        foreach ($validatedData['answers'] as $index => $answerData) {
            $answer = Answer::find($request->input("answer_ids.$index"));
            if ($answer) {
                $answer->update([
                    'answer_text' => $answerData['answer_text'],
                    'score' => $answerData['score'],
                ]);
            }
        }

        return redirect()->route('admin.soal.edit', $question->id)->with('success', 'Soal berhasil diperbarui!');
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
            ->select('name', 'email', 'telepon', 'instansi', 'role', 'status')
            ->orderBy('name')
            ->paginate(50);

        return view('admin.data_peserta.user', compact('results'));
    }

    public function dataGuru()
    {
        $results = DB::table('users')
            ->leftJoin('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.telepon',
                'users.instansi',
                'users.role',
                'users.status',
                'users.question_set_id',
                'question_sets.name as question_set_name'
            )
            ->where('users.role', 'guru')
            ->orderBy('users.name')
            ->paginate(50);

        return view('admin.data_peserta.guru', compact('results'));
    }

    public function dataKepsek()
    {
        $results = DB::table('users')
            ->leftJoin('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.telepon',
                'users.instansi',
                'users.role',
                'users.status',
                'users.question_set_id',
                'question_sets.name as question_set_name'
            )
            ->where('users.role', 'Kepala Sekolah')
            ->orderBy('users.name')
            ->paginate(50);

        return view('admin.data_peserta.kepsek', compact('results'));
    }

    public function jawabanPeserta($userId)
    {
        $userAnswers = UserAnswer::where('user_id', $userId)
            ->with(['question', 'answer'])
            ->get();

        $answers = DB::table('user_answers')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                'questions.question_text',
                'answers.answer_text',
                'answers.score'
            )
            ->where('user_answers.user_id', $userId)
            ->get();

        $userName = $answers->first()->user_name ?? 'Unknown';

        return view('admin.hasil.detail-jawaban', compact('answers', 'userName'));
    }

    public function editPaketSoal($question_set_id)
    {
        $questionSet = QuestionSet::findOrFail($question_set_id);
        $question = $questionSet->questions;

        return view('admin.paket_soal.edit', compact('questionSet', 'question'));
    }

    public function editGuru($id)
    {
        $guru = User::where('role', 'guru')->findOrFail($id);

        $questionSets = DB::table('question_sets')
            ->where('role', 'Guru')
            ->pluck('name', 'id');

        return view('admin.data_peserta.edit-guru', compact('guru', 'questionSets'));
    }


    public function updateGuru(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'role' => [
                'required',
                'string',
                'max:50',
                'in:Guru,Kepala Sekolah'
            ],
            'status' => [
                'required',
                'string',
                'max:50',
                'in:not_started,on_going,submitted'
            ],
            'question_set_id' => 'nullable|exists:question_sets,id',
        ]);

        $guru = User::where('role', 'guru')->findOrFail($id);

        $guru->update($validatedData);

        return redirect()->route('data.guru')->with('success', 'Data guru berhasil diperbarui!');
    }

    public function editKepsek($id)
    {
        $kepsek = User::where('role', 'Kepala Sekolah')->findOrFail($id);

        $questionSets = DB::table('question_sets')
            ->where('role', 'Kepala Sekolah')
            ->pluck('name', 'id');

        return view('admin.data_peserta.edit-kepsek', compact('kepsek', 'questionSets'));
    }

    public function updateKepsek(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'role' => [
                'required',
                'string',
                'max:50',
                'in:Guru,Kepala Sekolah'
            ],
            'status' => [
                'required',
                'string',
                'max:50',
                'in:not_started,on_going,submitted'
            ],
            'question_set_id' => 'nullable|exists:question_sets,id',
        ]);

        $kepsek = User::where('role', 'Kepala Sekolah')->findOrFail($id);

        $kepsek->update($validatedData);

        return redirect()->route('data.kepala_sekolah')->with('success', 'Data Kepala Sekolah berhasil diperbarui!');
    }

    public function destroyGuru($id)
    {
        $guru = User::find($id);

        if (!$guru) {
            return redirect()->route('admin.data.guru')->with('error', 'Data guru tidak ditemukan.');
        }

        $guru->delete();

        return redirect()->route('data.guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function destroyKepsek($id)
    {
        $kepsek = User::find($id);

        if (!$kepsek) {
            return redirect()->route('admin.data.guru')->with('error', 'Data guru tidak ditemukan.');
        }

        $kepsek->delete();

        return redirect()->route('data.kepala_sekolah')->with('success', 'Data Kepala Sekolah berhasil dihapus.');
    }
}
