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
use Illuminate\Support\Facades\Mail;

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

    public function storeQuestionGuru(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'kompetensi_guru' => 'required|string|max:255',
                'indikator_guru' => 'required|string|max:255',
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

            // Kompetensi Guru
            if (is_numeric($validatedData['kompetensi_guru'])) {
                $kompetensi = Kompetensi::where('id', $validatedData['kompetensi_guru'])->where('role', 'Guru')->first();
                if (!$kompetensi) return back()->with('error', 'Kompetensi tidak ditemukan.');
            } else {
                $kompetensi = Kompetensi::firstOrCreate(
                    ['nama' => $validatedData['kompetensi_guru'], 'role' => 'Guru']
                );
            }

            // Indikator Guru
            if (is_numeric($validatedData['indikator_guru'])) {
                $indikator = Indikator::where('id', $validatedData['indikator_guru'])->where('kompetensi_id', $kompetensi->id)->first();
                if (!$indikator) return back()->with('error', 'Indikator tidak ditemukan.');
            } else {
                $indikator = Indikator::firstOrCreate(
                    ['nama' => $validatedData['indikator_guru'], 'kompetensi_id' => $kompetensi->id]
                );
            }

            // Simpan soal dan jawaban (sama seperti sebelumnya)
            $question = Question::create([
                'question_text' => $validatedData['question_text'],
                'question_set_id' => $validatedData['question_set_id'],
                'kompetensi_id' => $kompetensi->id,
                'indikator_id' => $indikator->id,
            ]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_a'], 'score' => $validatedData['score_a']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_b'], 'score' => $validatedData['score_b']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_c'], 'score' => $validatedData['score_c']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_d'], 'score' => $validatedData['score_d']]);

            // Catat aktivitas ke LogAdmin
            $questionSet = \App\Models\QuestionSet::find($validatedData['question_set_id']);
            $questionSetName = $questionSet ? $questionSet->name : 'Unknown';

            \App\Models\LogAdmin::create([
                'admin_id' => auth('admin')->id() ?? null,
                'admin_name' => auth('admin')->user()->username ?? 'Unknown',
                'action' => 'Menambah soal guru: "' . $validatedData['question_text'] . '" pada paket soal: ' . $questionSetName,
                'ip_address' => $request->ip(),
                'question_set_id' => $validatedData['question_set_id'],
            ]);

            return redirect()->route('admin.guru.detail-soal', ['question_set_id' => $validatedData['question_set_id']])
                ->with('success', 'Soal berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function storeQuestionKepsek(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'kompetensi_kepsek' => 'required|string|max:255',
                'indikator_kepsek' => 'required|string|max:255',
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

            // Kompetensi Kepala Sekolah
            if (is_numeric($validatedData['kompetensi_kepsek'])) {
                $kompetensi = Kompetensi::where('id', $validatedData['kompetensi_kepsek'])->where('role', 'Kepala Sekolah')->first();
                if (!$kompetensi) return back()->with('error', 'Kompetensi tidak ditemukan.');
            } else {
                $kompetensi = Kompetensi::firstOrCreate(
                    ['nama' => $validatedData['kompetensi_kepsek'], 'role' => 'Kepala Sekolah']
                );
            }

            // Indikator Kepala Sekolah
            if (is_numeric($validatedData['indikator_kepsek'])) {
                $indikator = Indikator::where('id', $validatedData['indikator_kepsek'])->where('kompetensi_id', $kompetensi->id)->first();
                if (!$indikator) return back()->with('error', 'Indikator tidak ditemukan.');
            } else {
                $indikator = Indikator::firstOrCreate(
                    ['nama' => $validatedData['indikator_kepsek'], 'kompetensi_id' => $kompetensi->id]
                );
            }

            // Simpan soal dan jawaban (sama seperti sebelumnya)
            $question = Question::create([
                'question_text' => $validatedData['question_text'],
                'question_set_id' => $validatedData['question_set_id'],
                'kompetensi_id' => $kompetensi->id,
                'indikator_id' => $indikator->id,
            ]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_a'], 'score' => $validatedData['score_a']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_b'], 'score' => $validatedData['score_b']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_c'], 'score' => $validatedData['score_c']]);
            Answer::create(['question_id' => $question->id, 'answer_text' => $validatedData['option_d'], 'score' => $validatedData['score_d']]);

            // Catat aktivitas ke LogAdmin
            $questionSet = \App\Models\QuestionSet::find($validatedData['question_set_id']);
            $questionSetName = $questionSet ? $questionSet->name : 'Unknown';

            \App\Models\LogAdmin::create([
                'admin_id' => auth('admin')->id() ?? null,
                'admin_name' => auth('admin')->user()->username ?? 'Unknown',
                'action' => 'Menambah soal guru: "' . $validatedData['question_text'] . '" pada paket soal: ' . $questionSetName,
                'ip_address' => $request->ip(),
                'question_set_id' => $validatedData['question_set_id'],
            ]);

            return redirect()->route('admin.ks.detail-soal', ['question_set_id' => $validatedData['question_set_id']])
                ->with('success', 'Soal berhasil disimpan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function resultPage()
    {
        return view('admin.hasil.index');
    }

    public function resultGuru(Request $request)
    {
        $sort = request('sort', 'score');
        $direction = request('direction', 'desc');

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
            ->orderBy($sort, $direction)
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
                'quiz_attempts.score',
                'quiz_attempts.id as quiz_attempt_id'
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
                'quiz_attempts.score',
                'quiz_attempts.id as quiz_attempt_id'
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
        $sort = request('sort', 'score');
        $direction = request('direction', 'desc');

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
            ->orderBy($sort, $direction)
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
        $kompetensi = Kompetensi::where('role', 'Kepala Sekolah')->get();

        return view('admin.soal.kepala_sekolah.edit-soal', compact('question', 'kompetensi'));
    }

    public function showEditFormGuru($id)
    {
        $question = Question::with('answers')->findOrFail($id);
        $kompetensi = Kompetensi::where('role', 'Guru')->get();

        return view('admin.soal.guru.edit-soal', compact('question', 'kompetensi'));
    }


    public function editQuestionGuru(Request $request, $id)
    {
        $validatedData = $request->validate([
            'indikator_guru' => 'required|string|max:255',
            'kompetensi_guru' => 'required|string|max:255',
            'question_text' => 'required|string|max:500',
            'question_set_id' => 'required|exists:question_sets,id',
            'answers.*.answer_text' => 'required|string|max:500',
            'answers.*.score' => 'required|integer|in:1,2,3,4',
        ]);

        $question = Question::with(['answers', 'kompetensi', 'indikator'])->findOrFail($id);
        $oldData = [
            'kompetensi' => $question->kompetensi ? $question->kompetensi->nama : '',
            'indikator' => $question->indikator ? $question->indikator->nama : '',
            'question_text' => $question->question_text,
            'answers' => $question->answers->pluck('answer_text', 'id')->toArray(),
            'scores' => $question->answers->pluck('score', 'id')->toArray(),
        ];

        // Kompetensi Guru
        if (is_numeric($validatedData['kompetensi_guru'])) {
            $kompetensi = Kompetensi::where('id', $validatedData['kompetensi_guru'])->where('role', 'Guru')->first();
            if (!$kompetensi) return back()->with('error', 'Kompetensi tidak ditemukan.');
        } else {
            $kompetensi = Kompetensi::firstOrCreate(
                ['nama' => $validatedData['kompetensi_guru'], 'role' => 'Guru']
            );
        }

        // Indikator Guru
        if (is_numeric($validatedData['indikator_guru'])) {
            $indikator = Indikator::where('id', $validatedData['indikator_guru'])->where('kompetensi_id', $kompetensi->id)->first();
            if (!$indikator) return back()->with('error', 'Indikator tidak ditemukan.');
        } else {
            $indikator = Indikator::firstOrCreate(
                ['nama' => $validatedData['indikator_guru'], 'kompetensi_id' => $kompetensi->id]
            );
        }

        // Update the question
        $question->update([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
            'kompetensi_id' => $kompetensi->id,
            'indikator_id' => $indikator->id,
        ]);

        // Update the answers
        $logAnswerChanges = [];
        foreach ($validatedData['answers'] as $index => $answerData) {
            $answer = Answer::find($request->input("answer_ids.$index"));
            if ($answer) {
                $oldAnswer = $answer->answer_text;
                $oldScore = $answer->score;
                $answer->update([
                    'answer_text' => $answerData['answer_text'],
                    'score' => $answerData['score'],
                ]);
                if ($oldAnswer !== $answerData['answer_text'] || $oldScore != $answerData['score']) {
                    $logAnswerChanges[] = "Jawaban " . chr(65 + $index) . " diubah dari [\"$oldAnswer\" ($oldScore)] menjadi [\"{$answerData['answer_text']}\" ({$answerData['score']})]";
                }
            }
        }

        $questionSet = QuestionSet::find($validatedData['question_set_id']);
        $questionSetName = $questionSet ? $questionSet->name : 'Unknown';

        // Detail perubahan
        $changes = [];
        if ($oldData['kompetensi'] !== $kompetensi->nama) {
            $changes[] = "Kompetensi diubah dari \"{$oldData['kompetensi']}\" menjadi \"{$kompetensi->nama}\"";
        }
        if ($oldData['indikator'] !== $indikator->nama) {
            $changes[] = "Indikator diubah dari \"{$oldData['indikator']}\" menjadi \"{$indikator->nama}\"";
        }
        if ($oldData['question_text'] !== $validatedData['question_text']) {
            $changes[] = "Soal diubah dari \"{$oldData['question_text']}\" menjadi \"{$validatedData['question_text']}\"";
        }
        if (!empty($logAnswerChanges)) {
            $changes = array_merge($changes, $logAnswerChanges);
        }
        $actionDetail = implode('; ', $changes);

        // Catat aktivitas ke LogAdmin
        \App\Models\LogAdmin::create([
            'admin_id' => auth('admin')->id() ?? null,
            'admin_name' => auth('admin')->user()->username ?? 'Unknown',
            'action' => 'Mengedit soal guru pada paket soal: ' . $questionSetName . '. ' . ($actionDetail ?: 'Tidak ada perubahan.'),
            'ip_address' => $request->ip(),
            'question_set_id' => $validatedData['question_set_id'],
        ]);

        $route = 'admin.guru.detail-soal';

        return redirect()->route($route, ['question_set_id' => $validatedData['question_set_id']])
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function editQuestionKepsek(Request $request, $id)
    {
        $validatedData = $request->validate([
            'indikator_ks' => 'required|string|max:255',
            'kompetensi_ks' => 'required|string|max:255',
            'question_text' => 'required|string|max:500',
            'question_set_id' => 'required|exists:question_sets,id',
            'answers.*.answer_text' => 'required|string|max:500',
            'answers.*.score' => 'required|integer|in:1,2,3,4',
        ]);

        $question = Question::with(['answers', 'kompetensi', 'indikator'])->findOrFail($id);
        $oldData = [
            'kompetensi' => $question->kompetensi ? $question->kompetensi->nama : '',
            'indikator' => $question->indikator ? $question->indikator->nama : '',
            'question_text' => $question->question_text,
            'answers' => $question->answers->pluck('answer_text', 'id')->toArray(),
            'scores' => $question->answers->pluck('score', 'id')->toArray(),
        ];

        // Kompetensi Kepsek
        if (is_numeric($validatedData['kompetensi_ks'])) {
            $kompetensi = Kompetensi::where('id', $validatedData['kompetensi_ks'])->where('role', 'Kepala Sekolah')->first();
            if (!$kompetensi) return back()->with('error', 'Kompetensi tidak ditemukan.');
        } else {
            $kompetensi = Kompetensi::firstOrCreate(
                ['nama' => $validatedData['kompetensi_ks'], 'role' => 'Kepala Sekolah']
            );
        }

        // Indikator Kepsek
        if (is_numeric($validatedData['indikator_ks'])) {
            $indikator = Indikator::where('id', $validatedData['indikator_ks'])->where('kompetensi_id', $kompetensi->id)->first();
            if (!$indikator) return back()->with('error', 'Indikator tidak ditemukan.');
        } else {
            $indikator = Indikator::firstOrCreate(
                ['nama' => $validatedData['indikator_ks'], 'kompetensi_id' => $kompetensi->id]
            );
        }

        // Update the question text and question_set_id
        $question->update([
            'question_text' => $validatedData['question_text'],
            'question_set_id' => $validatedData['question_set_id'],
            'kompetensi_id' => $kompetensi->id,
            'indikator_id' => $indikator->id,
        ]);

        // Update the answers
        foreach ($validatedData['answers'] as $index => $answerData) {
            $answer = Answer::find($request->input("answer_ids.$index"));
            if ($answer) {
                $oldAnswer = $answer->answer_text;
                $oldScore = $answer->score;
                $answer->update([
                    'answer_text' => $answerData['answer_text'],
                    'score' => $answerData['score'],
                ]);
                if ($oldAnswer !== $answerData['answer_text'] || $oldScore != $answerData['score']) {
                    $logAnswerChanges[] = "Jawaban " . chr(65 + $index) . " diubah dari [\"$oldAnswer\" ($oldScore)] menjadi [\"{$answerData['answer_text']}\" ({$answerData['score']})]";
                }
            }
        }

        $questionSet = QuestionSet::find($validatedData['question_set_id']);
        $questionSetName = $questionSet ? $questionSet->name : 'Unknown';

        // Detail perubahan
        $changes = [];
        if ($oldData['kompetensi'] !== $kompetensi->nama) {
            $changes[] = "Kompetensi diubah dari \"{$oldData['kompetensi']}\" menjadi \"{$kompetensi->nama}\"";
        }
        if ($oldData['indikator'] !== $indikator->nama) {
            $changes[] = "Indikator diubah dari \"{$oldData['indikator']}\" menjadi \"{$indikator->nama}\"";
        }
        if ($oldData['question_text'] !== $validatedData['question_text']) {
            $changes[] = "Soal diubah dari \"{$oldData['question_text']}\" menjadi \"{$validatedData['question_text']}\"";
        }
        if (!empty($logAnswerChanges)) {
            $changes = array_merge($changes, $logAnswerChanges);
        }
        $actionDetail = implode('; ', $changes);

        // Catat aktivitas ke LogAdmin
        \App\Models\LogAdmin::create([
            'admin_id' => auth('admin')->id() ?? null,
            'admin_name' => auth('admin')->user()->username ?? 'Unknown',
            'action' => 'Mengedit soal guru pada paket soal: ' . $questionSetName . '. ' . ($actionDetail ?: 'Tidak ada perubahan.'),
            'ip_address' => $request->ip(),
            'question_set_id' => $validatedData['question_set_id'],
        ]);

        $route = 'admin.ks.detail-soal';

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

        $userRole = User::find($userId)->role;

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
            ->get();

        $userName = $answers->first()->user_name ?? 'Unknown';

        $quizAttempt = QuizAttempt::where('user_id', $userId)->latest()->first();

        return view('admin.hasil.detail-jawaban', compact('answers', 'userName', 'userId', 'userRole', 'quizAttempt'));
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

        $paketGuru = DB::table('question_sets')->where('role', 'Guru')->pluck('name', 'id');
        $paketKepsek = DB::table('question_sets')->where('role', 'Kepala Sekolah')->pluck('name', 'id');

        return view('admin.data_peserta.edit-guru', compact('guru', 'paketGuru', 'paketKepsek'));
    }


    public function updateGuru(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:15',
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
            'question_set_id' => 'nullable|exists:question_sets,id',
        ]);

        $guru = User::where('role', 'guru')->findOrFail($id);

        // Simpan data lama sebelum update
        $oldData = $guru->toArray();

        $guru->update($validatedData);

        // Ambil data baru setelah update
        $newData = $guru->fresh()->toArray();

        // Cek perubahan
        $changes = [];
        foreach ($validatedData as $field => $value) {
            // Untuk field relasi question_set_id, tampilkan nama paket soal
            if ($field === 'question_set_id') {
                $oldName = $oldData['question_set_id'] ? (\App\Models\QuestionSet::find($oldData['question_set_id'])->name ?? '-') : '-';
                $newName = $newData['question_set_id'] ? (\App\Models\QuestionSet::find($newData['question_set_id'])->name ?? '-') : '-';
                if ($oldData['question_set_id'] != $newData['question_set_id']) {
                    $changes[] = "Paket soal diubah dari \"$oldName\" menjadi \"$newName\"";
                }
            } else {
                if ($oldData[$field] != $newData[$field]) {
                    $changes[] = ucfirst(str_replace('_', ' ', $field)) . " diubah dari \"{$oldData[$field]}\" menjadi \"{$newData[$field]}\"";
                }
            }
        }

        // Catat log jika ada perubahan
        if (!empty($changes)) {
            \App\Models\LogAdmin::create([
                'admin_id' => auth('admin')->id() ?? null,
                'admin_name' => auth('admin')->user()->username ?? 'Unknown',
                'action' => 'Mengedit data guru: ' . $guru->name . '. ' . implode('; ', $changes),
                'ip_address' => $request->ip(),
                'question_set_id' => $newData['question_set_id'] ?? null,
            ]);
        }

        return redirect()->route('data.guru')->with('success', 'Data guru berhasil diperbarui!');
    }
    public function editKepsek($id)
    {
        $kepsek = User::where('role', 'Kepala Sekolah')->findOrFail($id);

        $paketGuru = DB::table('question_sets')->where('role', 'Guru')->pluck('name', 'id');
        $paketKepsek = DB::table('question_sets')->where('role', 'Kepala Sekolah')->pluck('name', 'id');

        return view('admin.data_peserta.edit-kepsek', compact('kepsek', 'paketGuru', 'paketKepsek'));
    }

    public function updateKepsek(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:15',
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

        // Simpan data lama sebelum update
        $oldData = $kepsek->toArray();

        $kepsek->update($validatedData);

        // Ambil data baru setelah update
        $newData = $kepsek->fresh()->toArray();

        // Cek perubahan
        $changes = [];
        foreach ($validatedData as $field => $value) {
            // Untuk field relasi question_set_id, tampilkan nama paket soal
            if ($field === 'question_set_id') {
                $oldName = $oldData['question_set_id'] ? (\App\Models\QuestionSet::find($oldData['question_set_id'])->name ?? '-') : '-';
                $newName = $newData['question_set_id'] ? (\App\Models\QuestionSet::find($newData['question_set_id'])->name ?? '-') : '-';
                if ($oldData['question_set_id'] != $newData['question_set_id']) {
                    $changes[] = "Paket soal diubah dari \"$oldName\" menjadi \"$newName\"";
                }
            } else {
                if ($oldData[$field] != $newData[$field]) {
                    $changes[] = ucfirst(str_replace('_', ' ', $field)) . " diubah dari \"{$oldData[$field]}\" menjadi \"{$newData[$field]}\"";
                }
            }
        }

        // Catat log jika ada perubahan
        if (!empty($changes)) {
            \App\Models\LogAdmin::create([
                'admin_id' => auth('admin')->id() ?? null,
                'admin_name' => auth('admin')->user()->username ?? 'Unknown',
                'action' => 'Mengedit data guru: ' . $kepsek->name . '. ' . implode('; ', $changes),
                'ip_address' => $request->ip(),
                'question_set_id' => $newData['question_set_id'] ?? null,
            ]);
        }

        return redirect()->route('data.kepala_sekolah')->with('success', 'Data Kepala Sekolah berhasil diperbarui!');
    }

    public function destroyGuru($id)
    {
        $guru = User::find($id);

        if (!$guru) {
            return redirect()->route('admin.data.guru')->with('error', 'Data guru tidak ditemukan.');
        }

        // Catat log sebelum hapus
        \App\Models\LogAdmin::create([
            'admin_id'   => auth('admin')->id() ?? null,
            'admin_name' => auth('admin')->user()->username ?? 'Unknown',
            'action'     => 'Menghapus data guru: ' . $guru->name . ' (ID: ' . $guru->id . ')',
            'ip_address' => request()->ip(),
            'question_set_id' => $guru->question_set_id ?? null,
        ]);

        $guru->delete();

        return redirect()->route('data.guru')->with('success', 'Data guru berhasil dihapus.');
    }

    public function destroyKepsek($id)
    {
        $kepsek = User::find($id);

        if (!$kepsek) {
            return redirect()->route('admin.data.guru')->with('error', 'Data guru tidak ditemukan.');
        }

        // Catat log sebelum hapus
        \App\Models\LogAdmin::create([
            'admin_id'   => auth('admin')->id() ?? null,
            'admin_name' => auth('admin')->user()->username ?? 'Unknown',
            'action'     => 'Menghapus data kepala sekolah: ' . $kepsek->name . ' (ID: ' . $kepsek->id . ')',
            'ip_address' => request()->ip(),
            'question_set_id' => $kepsek->question_set_id ?? null,
        ]);

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
        $questionSetName = $questionSet ? $questionSet->name : 'Unknown';

        // Catat log sebelum hapus
        \App\Models\LogAdmin::create([
            'admin_id' => auth('admin')->id() ?? null,
            'admin_name' => auth('admin')->user()->username ?? 'Unknown',
            'action' => 'Menghapus soal: "' . $question->question_text . '" pada paket soal: ' . $questionSetName,
            'ip_address' => request()->ip(),
            'question_set_id' => $questionSet ? $questionSet->id : null,
        ]);

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
        $admin = auth('admin')->user();
        $user = \App\Models\User::find($userId);

        DB::transaction(function () use ($userId) {
            DB::table('quiz_attempts')->where('user_id', $userId)->delete();
            DB::table('users')->where('id', $userId)->update(['status' => 'not_started']);
            DB::table('user_answers')->where('user_id', $userId)->delete();
        });

        // Catat log penghapusan hasil tes kepala sekolah
        \App\Models\LogAdmin::create([
            'admin_id'   => $admin->id ?? null,
            'admin_name' => $admin->username ?? 'Unknown',
            'action'     => 'Menghapus hasil tes kepala sekolah: ' . ($user->name ?? 'Unknown') . ' (ID: ' . $userId . ')',
            'ip_address' => request()->ip(),
            'question_set_id' => $user->question_set_id ?? null,
        ]);

        return redirect()->route('hasil.kepala_sekolah')->with('success', 'Data berhasil dihapus.');
    }

    public function hapusHasilGuru($userId)
    {
        $admin = auth('admin')->user();
        $user = \App\Models\User::find($userId);

        DB::transaction(function () use ($userId) {
            DB::table('quiz_attempts')->where('user_id', $userId)->delete();
            DB::table('users')->where('id', $userId)->update(['status' => 'not_started']);
            DB::table('user_answers')->where('user_id', $userId)->delete();
        });

        // Catat log penghapusan hasil tes
        \App\Models\LogAdmin::create([
            'admin_id'   => $admin->id ?? null,
            'admin_name' => $admin->username ?? 'Unknown',
            'action'     => 'Menghapus hasil tes guru: ' . ($user->name ?? 'Unknown') . ' (ID: ' . $userId . ')',
            'ip_address' => request()->ip(),
            'question_set_id' => $user->question_set_id ?? null,
        ]);

        return redirect()->route('hasil.guru')->with('success', 'Data berhasil dihapus.');
    }

    public function grafikIndividu($userId)
    {
        $userId = User::where('id', $userId)->value('id');

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

        // Menghitung RATA-RATA skor per kompetensi (bukan total)
        $scoreByCompetency = $answers->groupBy('kompetensi_name')->map(function ($items) {
            return round($items->avg('score'), 2); // Rata-rata skor per kompetensi
        });

        // Alternatif: Menghitung persentase capaian (jika skor maksimal adalah 4)
        $percentageByCompetency = $answers->groupBy('kompetensi_name')->map(function ($items) {
            $avgScore = $items->avg('score');
            return round(($avgScore / 4) * 100, 1); // Persentase dari skor maksimal 4
        });

        // Data untuk grafik pie (tetap sama)
        $scoreData = [
            '4' => $answers->where('score', 4)->count(),
            '3' => $answers->where('score', 3)->count(),
            '2' => $answers->where('score', 2)->count(),
            '1' => $answers->where('score', 1)->count(),
        ];

        // Hitung jumlah soal per kompetensi
        $questionCountByCompetency = $answers->groupBy('kompetensi_name')->map(function ($items) {
            return $items->count();
        });

        return view('admin.hasil.grafik-individu-guru', [
            'userName' => $answers->first()->user_name ?? 'Unknown',
            'scoreData' => $scoreData,
            'scoreByCompetency' => $scoreByCompetency, // Rata-rata skor
            'percentageByCompetency' => $percentageByCompetency, // Persentase capaian
            'questionCountByCompetency' => $questionCountByCompetency,
            'userId' => $userId,
        ]);
    }

    public function grafikKepsek()
    {
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

        // Data rata-rata skor per kompetensi (kecuali Pedagogik)
        $competencyScores = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->where('users.role', 'kepala sekolah')
            ->where('kompetensi.nama', '!=', 'Pedagogik') // EXCLUDE PEDAGOGIK
            ->whereNotIn('kompetensi.nama', ['Pedagogik', 'pedagogik', 'PEDAGOGIK'])
            ->select(
                'kompetensi.nama as kompetensi',
                DB::raw('AVG(answers.score) as avg_score'),
                DB::raw('COUNT(answers.score) as question_count')
            )
            ->groupBy('kompetensi.nama')
            ->get();

        $scoreByCompetency = $competencyScores->pluck('avg_score', 'kompetensi')->map(function ($score) {
            return round($score, 2);
        });

        $percentageByCompetency = $competencyScores->pluck('avg_score', 'kompetensi')->map(function ($score) {
            return round(($score / 4) * 100, 1);
        });

        $questionCountByCompetency = $competencyScores->pluck('question_count', 'kompetensi');

        $jumlahKs = DB::table('users')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->where('users.role', 'kepala sekolah')
            ->count();

        return view('admin.hasil.grafik-kepsek', compact(
            'scoreData',
            'scoreByCompetency',
            'percentageByCompetency',
            'questionCountByCompetency',
            'jumlahKs'
        ));
    }
    public function grafikGuru()
    {
        // Data untuk pie chart (tetap sama - distribusi skor jawaban)
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

        // Data untuk bar chart - RATA-RATA skor per kompetensi (bukan total)
        $competencyScores = DB::table('user_answers')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('users', 'user_answers.user_id', '=', 'users.id')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->where('users.role', 'guru')
            ->select(
                'kompetensi.nama as kompetensi',
                DB::raw('AVG(answers.score) as avg_score'),
                DB::raw('COUNT(answers.score) as question_count')
            )
            ->groupBy('kompetensi.nama')
            ->get();

        // Konversi ke format yang sesuai untuk chart
        $scoreByCompetency = $competencyScores->pluck('avg_score', 'kompetensi')->map(function ($score) {
            return round($score, 2);
        });

        // Persentase capaian (dari skor maksimal 4)
        $percentageByCompetency = $competencyScores->pluck('avg_score', 'kompetensi')->map(function ($score) {
            return round(($score / 4) * 100, 1);
        });

        // Jumlah soal per kompetensi
        $questionCountByCompetency = $competencyScores->pluck('question_count', 'kompetensi');

        $jumlahGuru = DB::table('users')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->where('users.role', 'guru')
            ->count();

        return view('admin.hasil.grafik-guru', compact(
            'scoreData',
            'scoreByCompetency',
            'percentageByCompetency',
            'questionCountByCompetency',
            'jumlahGuru'
        ));
    }

    public function logs(Request $request)
    {
        try {
            // Ambil semua admin untuk dropdown
            $admins = DB::table('admins')->select('id', 'username')->get();

            // Query dasar logs
            $query = DB::table('logs_admin')->orderBy('created_at', 'desc');

            $logs = $query->paginate(20);

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

            $total = $query->count();

            if ($total > 20) {
                $logsPaginated = $query->paginate(20);
                $logs = $logsPaginated->getCollection()->map(function ($log, $index) use ($logsPaginated) {
                    return [
                        'no' => ($logsPaginated->currentPage() - 1) * $logsPaginated->perPage() + $index + 1,
                        'admin_name' => $log->admin_name ?? '-',
                        'action' => $log->action,
                        'waktu' => \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') . ' WIB',
                    ];
                });
                $pagination = (string) $logsPaginated->links('pagination.pagination');
            } else {
                $logs = $query->get()->map(function ($log, $index) {
                    return [
                        'no' => $index + 1,
                        'admin_name' => $log->admin_name ?? '-',
                        'action' => $log->action,
                        'waktu' => \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') . ' WIB',
                    ];
                });
                $pagination = '';
            }

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'pagination' => $pagination,
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

            // Cek apakah sudah ada permintaan pending untuk user_id dan quiz_attempt_id yang sama
            $existing = \App\Models\Allowance::where('user_id', $request->user_id)
                ->where(function ($q) use ($request) {
                    if ($request->quiz_attempt_id) {
                        $q->where('quiz_attempt_id', $request->quiz_attempt_id);
                    } else {
                        $q->whereNull('quiz_attempt_id');
                    }
                })
                ->where('status', 'pending')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permintaan penghapusan untuk data ini sudah pernah diajukan dan masih menunggu persetujuan.',
                ], 422);
            }

            $allowance = \App\Models\Allowance::create([
                'user_id' => $request->user_id,
                'name' => \App\Models\User::find($request->user_id)->name ?? 'Unknown',
                'quiz_attempt_id' => $request->quiz_attempt_id ?? null,
                'requested_by' => $adminId,
                'reason' => $request->reason,
                'status' => 'pending',
            ]);

            // Kirim notifikasi email ke semua admin lain
            $adminPengaju = \App\Models\Admin::find($adminId);
            $otherAdmins = \App\Models\Admin::where('id', '!=', $adminId)
                ->whereNotNull('email')
                ->pluck('email');

            foreach ($otherAdmins as $email) {
                Mail::to($email)->send(new \App\Mail\PermintaanPenghapusanMail($adminPengaju, $allowance));
            }

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

            // Simpan data untuk email sebelum allowance dihapus
            $adminPengaju = \App\Models\Admin::find($adminId);
            $otherAdmins = \App\Models\Admin::where('id', '!=', $adminId)
                ->whereNotNull('email')
                ->pluck('email');

            $allowance->delete();

            // Kirim email notifikasi pembatalan ke admin lain
            foreach ($otherAdmins as $email) {
                Mail::to($email)->send(new \App\Mail\PembatalanPermintaan($adminPengaju, $allowance));
            }

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

                    // Kirim email notifikasi ke admin pengaju
                    if ($adminPengaju && $adminPengaju->email) {
                        Log::info('Akan mengirim email ke: ' . $adminPengaju->email);
                        Mail::to($adminPengaju->email)->send(new \App\Mail\PermintaanDisetujuiMail($adminPengaju, $allowance));
                        Log::info('Email sudah dipanggil ke: ' . $adminPengaju->email);
                    }
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
                    });

                    // Kirim email notifikasi ke admin pengaju
                    if ($adminPengaju && $adminPengaju->email) {
                        Log::info('Akan mengirim email ke: ' . $adminPengaju->email);
                        Mail::to($adminPengaju->email)->send(new \App\Mail\PermintaanDisetujuiMail($adminPengaju, $allowance));
                        Log::info('Email sudah dipanggil ke: ' . $adminPengaju->email);
                    }
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

            // Kirim email notifikasi ke admin pengaju
            $adminPengaju = \App\Models\Admin::find($allowance->requested_by);
            if ($adminPengaju && $adminPengaju->email) {
                Mail::to($adminPengaju->email)->send(new \App\Mail\PermintaanDitolakMail($adminPengaju, $allowance));
            }

            return redirect()->back()->with('success', 'Permintaan berhasil ditolak.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroyAllowance($id)
    {
        $allowance = \App\Models\Allowance::findOrFail($id);
        $allowance->delete();
        return redirect()->back()->with('success', 'Permintaan berhasil dihapus.');
    }

    public function reapplyAllowance($id)
    {
        $allowance = \App\Models\Allowance::findOrFail($id);
        $allowance->status = 'pending';
        $allowance->save();

        // (Opsional) Hapus approval sebelumnya
        \App\Models\AllowanceApproval::where('allowance_id', $id)->delete();

        $adminId = auth('admin')->id();

        // Kirim notifikasi email ke semua admin lain
        $adminPengaju = \App\Models\Admin::find($adminId);
        $otherAdmins = \App\Models\Admin::where('id', '!=', $adminId)
            ->whereNotNull('email')
            ->pluck('email');

        foreach ($otherAdmins as $email) {
            Mail::to($email)->send(new \App\Mail\PermintaanPenghapusanMail($adminPengaju, $allowance));
        }

        return redirect()->back()->with('success', 'Permintaan berhasil diajukan kembali.');
    }

    public function checkAdminCount()
    {
        $count = \App\Models\Admin::count();
        return response()->json(['count' => $count]);
    }
}
