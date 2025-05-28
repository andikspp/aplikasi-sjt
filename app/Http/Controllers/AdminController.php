<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Indikator;
use App\Models\Kompetensi;
use App\Models\UserAnswer;
use App\Models\QuestionSet;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
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
        $jumlahPaudMitra = \App\Models\User::where('jenis_paud', 'mitra')->count();
        $jumlahPaudPembelajar = \App\Models\User::where('jenis_paud', 'pembelajar')->count();

        return view('admin.dashboard', compact('admin', 'jumlahUser', 'jumlahInstansi', 'jumlahUjianSelesai', 'jumlahGuru', 'jumlahKepalaSekolah', 'jumlahPaudMitra', 'jumlahPaudPembelajar'));
    }

    public function soalPage(Request $request)
    {
        try {
            $questionSets = QuestionSet::with('questions')->get();

            // Ambil nama pembuat dari LogAdmin berdasarkan question_set_id
            foreach ($questionSets as $set) {
                $log = \App\Models\LogAdmin::where('question_set_id', $set->id)
                    ->where('action', 'like', 'Menambah paket soal%')
                    ->orderBy('created_at', 'asc')
                    ->first();
                $set->creator_name = $log ? $log->admin_name : '-';
            }

            // Jika request AJAX, return JSON
            if ($request->ajax()) {
                return response()->json($questionSets);
            }

            // Jika request biasa, return view
            return view('admin.soal.index', compact('questionSets'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
            }
            return view('admin.soal.index', ['questionSets' => collect([]), 'error' => $e->getMessage()]);
        }
    }

    public function soalKs($questionSetId)
    {
        $selectedSet = \App\Models\QuestionSet::findOrFail($questionSetId);
        $kompetensi = Kompetensi::where('role', 'Kepala Sekolah')->get();

        return view('admin.soal.kepala_sekolah.create', compact('selectedSet', 'kompetensi'));
    }

    public function soalGuru($questionSetId)
    {
        $selectedSet = \App\Models\QuestionSet::findOrFail($questionSetId);
        $kompetensi = Kompetensi::where('role', 'Guru')->get();
        return view('admin.soal.guru.create', compact('selectedSet', 'kompetensi'));
    }

    public function getIndikatorByKompetensi($kompetensi_id)
    {
        $indikator = Indikator::where('kompetensi_id', $kompetensi_id)->get(['id', 'nama']);
        return response()->json($indikator);
    }

    public function storeQuestion(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'indikator_id' => 'required|exists:indikator,id',
            'kompetensi_id' => 'required|exists:kompetensi,id',
            'question_text' => 'required|string|max:500',
            'question_set_id' => 'required|exists:question_sets,id',
            'option_a' => 'required|string|max:500',
            'score_a' => 'required|integer|in:1,2,3,4',
            'option_b' => 'required|string|max:500',
            'score_b' => 'required|integer|in:1,2,3,4',
            'option_c' => 'required|string|max:500',
            'score_c' => 'required|integer|in:1,2,3,4',
            'option_d' => 'required|string|max:500',
            'score_d' => 'required|integer|in:1,2,3,4',
        ]);

        // Menyimpan soal
        $question = Question::create([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
            'kompetensi_id' => $validatedData['kompetensi_id'],
            'indikator_id' => $validatedData['indikator_id'],
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

        $questionSet = QuestionSet::find($validatedData['question_set_id']);
        $ownerPaketSoal = $questionSet->role;

        if ($ownerPaketSoal === 'Kepala Sekolah') {
            $route = 'admin.ks.detail-soal';
        } else {
            $route = 'admin.guru.detail-soal';
        }

        return redirect()->route($route, ['question_set_id' => $validatedData['question_set_id']])
            ->with('success', 'Soal berhasil disimpan!');
    }

    public function resultPage()
    {
        return view('admin.hasil.index');
    }

    public function resultGuru(Request $request)
    {
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi as instansi',
                'users.jenis_paud',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score',
                'quiz_attempts.id as quiz_attempt_id'
            )
            ->where('users.role', 'guru')
            ->paginate(10);

        return view('admin.hasil.guru', ['results' => $results]);
    }

    public function searchGuru(Request $request)
    {
        // Ambil input dari form
        $search = $request->input('search');
        $jenisPaudFilter = $request->input('jenis_paud');

        // Query dasar untuk data guru
        $query = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi as instansi',
                'users.jenis_paud',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'guru'); // Khusus untuk guru

        // Filter berdasarkan pencarian jika input tersedia
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.instansi', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan jenis PAUD jika input tersedia
        if ($jenisPaudFilter) {
            $query->where('users.jenis_paud', $jenisPaudFilter);
        }

        // Dapatkan hasil dengan pagination
        $results = $query->paginate(10)->appends([
            'search' => $search,
            'jenis_paud' => $jenisPaudFilter,
        ]);

        // Return ke view dengan hasil pencarian
        return view('admin.hasil.guru', [
            'results' => $results,
            'search' => $search,
            'jenis_paud' => $jenisPaudFilter,
        ]);
    }

    public function searchKs(Request $request)
    {
        // Ambil input dari form
        $search = $request->input('search');
        $jenisPaudFilter = $request->input('jenis_paud');

        // Query dasar untuk data guru
        $query = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi as instansi',
                'users.jenis_paud',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'kepala sekolah'); // Khusus untuk guru

        // Filter berdasarkan pencarian jika input tersedia
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', '%' . $search . '%')
                    ->orWhere('users.username', 'like', '%' . $search . '%')
                    ->orWhere('users.instansi', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan jenis PAUD jika input tersedia
        if ($jenisPaudFilter) {
            $query->where('users.jenis_paud', $jenisPaudFilter);
        }

        // Dapatkan hasil dengan pagination
        $results = $query->paginate(10)->appends([
            'search' => $search,
            'jenis_paud' => $jenisPaudFilter,
        ]);

        // Return ke view dengan hasil pencarian
        return view('admin.hasil.kepsek', [
            'results' => $results,
            'search' => $search,
            'jenis_paud' => $jenisPaudFilter,
        ]);
    }


    public function resultKepsek()
    {
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi as instansi',
                'users.jenis_paud',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score',
                'quiz_attempts.id as quiz_attempt_id'
            )
            ->where('users.role', 'kepala sekolah')
            ->paginate(10);

        return view('admin.hasil.kepsek', ['results' => $results]);
    }

    public function showQuestionsKs($question_set_id)
    {
        $questionSet = QuestionSet::findOrFail($question_set_id);
        $kompetensi = Kompetensi::where('role', 'Kepala Sekolah')->get();
        $questions = $questionSet->questions()->with('answers', 'kompetensi', 'indikator')->get();
        return view('admin.soal.kepala_sekolah.detail-soal', compact('questionSet', 'questions', 'kompetensi'));
    }

    public function showQuestionsGuru($question_set_id)
    {
        $questionSet = QuestionSet::findOrFail($question_set_id);
        $kompetensi = Kompetensi::where('role', 'Guru')->get();
        $questions = $questionSet->questions()->with('answers', 'kompetensi')->get();
        return view('admin.soal.guru.detail-soal', compact('questionSet', 'questions', 'kompetensi'));
    }

    public function showEditFormKs($id)
    {
        $question = Question::with('answers')->findOrFail($id);

        return view('admin.soal.kepala_sekolah.edit-soal', compact('question'));
    }

    public function showEditFormGuru($id)
    {
        $question = Question::with('answers')->findOrFail($id);

        return view('admin.soal.guru.edit-soal', compact('question'));
    }


    public function editQuestions(Request $request, $id)
    {
        $validatedData = $request->validate([
            'indikator_id' => 'required|exists:indikator,id',
            'kompetensi_id' => 'required|exists:kompetensi,id',
            'question_text' => 'required|string|max:500',
            'question_set_id' => 'required|exists:question_sets,id',
            'answers.*.answer_text' => 'required|string|max:500',
            'answers.*.score' => 'required|integer|in:1,2,3,4',
        ]);

        $question = Question::findOrFail($id);

        // Update the question text and question_set_id
        $question->update([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
            'kompetensi_id' => $validatedData['kompetensi_id'],
            'indikator_id' => $validatedData['indikator_id'],
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

        $questionSet = QuestionSet::find($validatedData['question_set_id']);

        $ownerPaketSoal = $questionSet->role;

        if ($ownerPaketSoal === 'Kepala Sekolah') {
            $route = 'admin.ks.detail-soal';
        } else {
            $route = 'admin.guru.detail-soal';
        }

        return redirect()->route($route, ['question_set_id' => $validatedData['question_set_id']])
            ->with('success', 'Soal berhasil diperbarui!');
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
            ->select('name', 'username', 'telepon', 'instansi', 'role', 'status')
            ->orderBy('name')
            ->paginate(50);

        return view('admin.data_peserta.user', compact('results'));
    }

    public function dataGuru(Request $request)
    {
        $search = $request->input('search');

        $statusFilter = $request->input('status');

        $jenisPaudFilter = $request->input('jenis_paud');

        $query = DB::table('users')
            ->leftJoin('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi',
                'users.jenis_paud',
                'users.role',
                'users.status',
                'users.question_set_id',
                'question_sets.name as question_set_name'
            )
            ->where('users.role', 'guru');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.username', 'LIKE', "%{$search}%")
                    ->orWhere('users.instansi', 'LIKE', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('users.status', $statusFilter);
        }

        if ($jenisPaudFilter) {
            $query->where('users.jenis_paud', $jenisPaudFilter);
        }

        $results = $query->orderBy('users.name')->paginate(10)->appends([
            'search' => $search,
            'status' => $statusFilter,
            'jenis_paud' => $jenisPaudFilter,
        ]);

        return view('admin.data_peserta.guru', compact('results', 'search', 'statusFilter', 'jenisPaudFilter'));
    }

    public function dataKepsek(Request $request)
    {
        $search = $request->input('search');

        $statusFilter = $request->input('status');

        $jenisPaudFilter = $request->input('jenis_paud');

        $query = DB::table('users')
            ->leftJoin('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi',
                'users.jenis_paud',
                'users.role',
                'users.status',
                'users.question_set_id',
                'question_sets.name as question_set_name'
            )
            ->where('users.role', 'Kepala Sekolah');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'LIKE', "%{$search}%")
                    ->orWhere('users.username', 'LIKE', "%{$search}%")
                    ->orWhere('users.instansi', 'LIKE', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('users.status', $statusFilter);
        }

        if ($jenisPaudFilter) {
            $query->where('users.jenis_paud', $jenisPaudFilter);
        }


        $results = $query->orderBy('users.name')->paginate(10)->appends([
            'search' => $search,
            'status' => $statusFilter,
            'jenis_paud' => $jenisPaudFilter,
        ]);

        return view('admin.data_peserta.kepsek', compact('results', 'search', 'statusFilter', 'jenisPaudFilter'));
    }

    public function jawabanPeserta($userId)
    {
        $userAnswers = UserAnswer::where('user_id', $userId)
            ->with(['question.kompetensi', 'answer'])
            ->get();

        // Jika Anda menggunakan query builder:
        $answers = DB::table('user_answers')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('kompetensi', 'user_answers.kompetensi_id', '=', 'kompetensi.id')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->leftJoin('indikator', 'questions.indikator_id', '=', 'indikator.id')
            ->select(
                'users.name as user_name',
                'questions.question_text',
                'answers.answer_text',
                'answers.score',
                'kompetensi.nama as nama',
                'indikator.nama as indikator_nama'
            )
            ->where('user_answers.user_id', $userId)
            ->paginate(10);

        $userName = $answers->first()->user_name ?? 'Unknown';

        return view('admin.hasil.detail-jawaban', compact('answers', 'userName', 'userId'));
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
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'jenis_paud' => [
                'required',
                'string',
                'in:mitra,pembelajar',
            ],
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
            'telepon' => 'required|string|max:15',
            'instansi' => 'required|string|max:255',
            'jenis_paud' => [
                'required',
                'string',
                'in:mitra,pembelajar',
            ],
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

    public function tambahKepsek()
    {
        $questionSets = QuestionSet::where('role', 'Kepala Sekolah')->pluck('name', 'id');

        return view('admin.data_peserta.tambah-kepsek', compact('questionSets'));
    }

    public function tambahGuru()
    {
        $questionSets = QuestionSet::where('role', 'Guru')->pluck('name', 'id');

        return view('admin.data_peserta.tambah-guru', compact('questionSets'));
    }

    public function hapusSoal($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return redirect()->back()->withErrors('Soal tidak ditemukan.');
        }

        $questionSet = $question->questionSet;

        $question->delete();

        if ($questionSet && $questionSet->role === 'Kepala Sekolah') {
            return redirect()->route('admin.ks.detail-soal', $questionSet->id)
                ->with('success', 'Soal berhasil dihapus.');
        } else {
            return redirect()->route('admin.guru.detail-soal', $questionSet->id)
                ->with('success', 'Soal berhasil dihapus.');
        }
    }

    public function hapusHasilKepsek($userId)
    {
        DB::transaction(function () use ($userId) {
            DB::table('quiz_attempts')->where('user_id', $userId)->delete();

            DB::table('users')->where('id', $userId)->update(['status' => 'not_started']);

            DB::table('user_answers')->where('user_id', $userId)->delete();
        });


        return redirect()->route('hasil.kepala_sekolah')->with('success', 'Data berhasil dihapus.');
    }

    public function hapusHasilGuru($userId)
    {
        DB::transaction(function () use ($userId) {
            DB::table('quiz_attempts')->where('user_id', $userId)->delete();

            DB::table('users')->where('id', $userId)->update(['status' => 'not_started']);

            DB::table('user_answers')->where('user_id', $userId)->delete();
        });


        return redirect()->route('hasil.guru')->with('success', 'Data berhasil dihapus.');
    }

    public function grafikIndividu($userId)
    {
        // Ambil data jawaban pengguna dengan kompetensi
        $answers = DB::table('user_answers')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                'kompetensi.nama as kompetensi_name',
                'answers.score'
            )
            ->where('user_answers.user_id', $userId)
            ->get();

        // Menghitung total skor per kompetensi
        $scoreByCompetency = $answers->groupBy('kompetensi_name')->map(function ($items) {
            return $items->sum('score');
        });

        // Data untuk grafik pie
        $scoreData = [
            '4' => $answers->where('score', 4)->count(),
            '3' => $answers->where('score', 3)->count(),
            '2' => $answers->where('score', 2)->count(),
            '1' => $answers->where('score', 1)->count(),
        ];

        return view('admin.hasil.grafik-individu-guru', [
            'userName' => $answers->first()->user_name ?? 'Unknown',
            'scoreData' => $scoreData,
            'scoreByCompetency' => $scoreByCompetency
        ]);
    }


    public function grafikKepsek()
    {
        // Data for existing chart (Total scores for kepala sekolah)
        $scores = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->where('users.role', 'kepala sekolah')
            ->select(DB::raw('answers.score, COUNT(*) as count'))
            ->groupBy('answers.score')
            ->pluck('count', 'answers.score');

        $scoreData = [
            '4' => $scores->get(4, 0),
            '3' => $scores->get(3, 0),
            '2' => $scores->get(2, 0),
            '1' => $scores->get(1, 0),
        ];

        // Data for new chart (Scores grouped by kompetensi for kepala sekolah)
        $scoreByCompetency = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->where('users.role', 'kepala sekolah')
            ->select(DB::raw('kompetensi.nama as kompetensi, SUM(answers.score) as total_score'))
            ->groupBy('kompetensi.nama')
            ->pluck('total_score', 'kompetensi');

        // Pass both datasets to the view
        return view('admin.hasil.grafik-kepsek', compact('scoreData', 'scoreByCompetency'));
    }


    public function grafikGuru()
    {
        $scores = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->where('users.role', 'guru')
            ->select(DB::raw('answers.score, COUNT(*) as count'))
            ->groupBy('answers.score')
            ->pluck('count', 'answers.score');

        $scoreData = [
            '4' => $scores->get(4, 0),
            '3' => $scores->get(3, 0),
            '2' => $scores->get(2, 0),
            '1' => $scores->get(1, 0),
        ];

        // Data for new chart (Scores grouped by kompetensi for kepala sekolah)
        $scoreByCompetency = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->where('users.role', 'Guru')
            ->select(DB::raw('kompetensi.nama as kompetensi, SUM(answers.score) as total_score'))
            ->groupBy('kompetensi.nama')
            ->pluck('total_score', 'kompetensi');

        return view('admin.hasil.grafik-guru', compact('scoreData', 'scoreByCompetency'));
    }

    public function logs(Request $request)
    {
        try {
            // Ambil semua admin untuk dropdown
            $admins = DB::table('admins')->select('id', 'username')->get();

            // Query dasar logs
            $query = DB::table('logs_admin')->orderBy('created_at', 'desc');

            $logs = $query->get();

            // Untuk view utama
            return view('admin.logs.index', compact('admins', 'logs'));
        } catch (\Exception $e) {
            // Jika terjadi error, redirect atau tampilkan pesan error
            return back()->with('error', 'Terjadi kesalahan saat mengambil data log: ' . $e->getMessage());
        }
    }

    public function filterLogsByAdmin(Request $request)
    {
        try {
            $adminId = $request->input('admin_id');
            $timeRange = $request->input('time_range');
            $waktuOrder = $request->input('waktu_order', 'desc');

            $query = DB::table('logs_admin')->orderBy('created_at', $waktuOrder);

            if ($adminId) {
                $query->where('admin_id', $adminId);
            }

            if ($timeRange) {
                $now = \Carbon\Carbon::now();
                switch ($timeRange) {
                    case 'today':
                        $query->whereDate('created_at', $now->toDateString());
                        break;
                    case '3days':
                        $query->where('created_at', '>=', $now->subDays(3));
                        break;
                    case '1week':
                        $query->where('created_at', '>=', $now->subWeek());
                        break;
                    case '2weeks':
                        $query->where('created_at', '>=', $now->subWeeks(2));
                        break;
                    case '3weeks':
                        $query->where('created_at', '>=', $now->subWeeks(3));
                        break;
                    case '1month':
                        $query->where('created_at', '>=', $now->subMonth());
                        break;
                }
            }

            $logs = $query->get()->map(function ($log, $index) {
                return [
                    'no' => $index + 1,
                    'admin_name' => $log->admin_name ?? '-',
                    'action' => $log->action,
                    'waktu' => \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') . ' WIB',
                ];
            });

            return response()->json([
                'success' => true,
                'logs' => $logs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function permintaanPage()
    {
        $allowances = \App\Models\Allowance::with(['peserta', 'approvals'])->where('requested_by', auth('admin')->id())->orderBy('created_at', 'desc')->get();

        return view('admin.permintaan.index', compact('allowances'));
    }

    public function storePermintaan(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
                'quiz_attempt_id' => 'nullable|integer|exists:quiz_attempts,id',
                'reason' => 'required|string|max:255',
            ]);

            $adminId = auth('admin')->id();

            $allowance = \App\Models\Allowance::create([
                'user_id' => $request->user_id,
                'quiz_attempt_id' => $request->quiz_attempt_id ?? null,
                'requested_by' => $adminId,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan penghapusan berhasil diajukan.',
                'data' => $allowance,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancelPermintaan($id)
    {
        try {
            $adminId = auth('admin')->id();
            $allowance = \App\Models\Allowance::where('id', $id)
                ->where('requested_by', $adminId)
                ->where('status', 'pending')
                ->first();

            if (!$allowance) {
                return redirect()->back()->with('error', 'Permintaan tidak ditemukan atau sudah diproses.');
            }

            $allowance->delete();

            return redirect()->back()->with('success', 'Permintaan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function persetujuanPage()
    {
        $adminId = auth('admin')->id();

        $allowances = \App\Models\Allowance::with(['peserta', 'adminrequested', 'approvals'])
            ->where('requested_by', '!=', $adminId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.persetujuan.index', compact('allowances'));
    }

    public function approveAllowance($id)
    {
        try {
            $adminId = auth('admin')->id();

            // Cek apakah sudah pernah approve/reject
            $existing = \App\Models\AllowanceApproval::where('allowance_id', $id)
                ->where('admin_id', $adminId)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Anda sudah memproses permintaan ini.');
            }

            // Simpan approval
            \App\Models\AllowanceApproval::create([
                'allowance_id' => $id,
                'admin_id' => $adminId,
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            // Cek apakah semua admin (selain pengaju) sudah approve
            $allowance = \App\Models\Allowance::with('approvals')->findOrFail($id);
            $totalAdmin = \App\Models\Admin::where('id', '!=', $allowance->requested_by)->count();
            $totalApproved = $allowance->approvals()->where('status', 'approved')->count();

            if ($totalApproved >= $totalAdmin) {
                // Update status allowance
                $allowance->status = 'approved';
                $allowance->save();

                if ($allowance->quiz_attempt_id) {
                    // Penghapusan hasil tes saja
                    DB::transaction(function () use ($allowance) {
                        DB::table('quiz_attempts')->where('id', $allowance->quiz_attempt_id)->delete();
                        DB::table('user_answers')->where('user_id', $allowance->user_id)->delete();
                        DB::table('users')->where('id', $allowance->user_id)->update(['status' => 'not_started']);
                    });

                    // Catat log aktivitas admin (setelah hapus hasil tes, peserta masih ada)
                    $adminPengaju = \App\Models\Admin::find($allowance->requested_by);
                    $peserta = \App\Models\User::find($allowance->user_id);
                    $namaPeserta = $peserta ? $peserta->name : 'Unknown';

                    \App\Models\LogAdmin::create([
                        'admin_id'   => $adminPengaju->id ?? null,
                        'admin_name' => $adminPengaju->username ?? 'Unknown',
                        'action' => 'Menghapus hasil tes peserta: ' . $namaPeserta . ' (ID: ' . $allowance->user_id . '). ' . $allowance->reason,
                        'ip_address' => request()->ip(),
                        'question_set_id' => null,
                    ]);
                } else {
                    // Penghapusan data peserta (hapus semua data peserta)
                    // Ambil nama peserta sebelum dihapus
                    $adminPengaju = \App\Models\Admin::find($allowance->requested_by);
                    $peserta = \App\Models\User::find($allowance->user_id);
                    $namaPeserta = $peserta ? $peserta->name : 'Unknown';

                    // Catat log SEBELUM hapus data peserta
                    \App\Models\LogAdmin::create([
                        'admin_id'   => $adminPengaju->id ?? null,
                        'admin_name' => $adminPengaju->username ?? 'Unknown',
                        'action' => 'Menghapus data peserta: ' . $namaPeserta . ' (ID: ' . $allowance->user_id . '). ' . $allowance->reason,
                        'ip_address' => request()->ip(),
                        'question_set_id' => null,
                    ]);

                    // Baru hapus data peserta dan relasi
                    DB::transaction(function () use ($allowance) {
                        DB::table('quiz_attempts')->where('user_id', $allowance->user_id)->delete();
                        DB::table('user_answers')->where('user_id', $allowance->user_id)->delete();
                        DB::table('users')->where('id', $allowance->user_id)->delete();

                        DB::table('allowance_approval')
                            ->whereIn('allowance_id', function ($query) use ($allowance) {
                                $query->select('id')->from('allowance')->where('user_id', $allowance->user_id);
                            })->delete();

                        DB::table('allowance')->where('user_id', $allowance->user_id)->delete();
                    });
                }
            }

            return redirect()->back()->with('success', 'Permintaan berhasil disetujui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function rejectAllowance($id)
    {
        try {
            $adminId = auth('admin')->id();

            // Cek apakah sudah pernah approve/reject
            $existing = \App\Models\AllowanceApproval::where('allowance_id', $id)
                ->where('admin_id', $adminId)
                ->first();

            if ($existing) {
                return redirect()->back()->with('error', 'Anda sudah memproses permintaan ini.');
            }

            // Simpan reject
            \App\Models\AllowanceApproval::create([
                'allowance_id' => $id,
                'admin_id' => $adminId,
                'status' => 'rejected',
                'approved_at' => now(),
            ]);

            // Jika ada satu saja yang reject, update status allowance jadi rejected
            $allowance = \App\Models\Allowance::findOrFail($id);
            $allowance->status = 'rejected';
            $allowance->save();

            return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
