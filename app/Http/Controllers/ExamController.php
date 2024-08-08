<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        return view('user.ujian');
    }

    public function examPage()
    {
        $user = Auth::user();

        session(['exam_started_at' => now()]);

        if (!$user->question_set_id) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditetapkan paket soal.');
        }

        // Cek apakah pengguna sudah pernah menyelesaikan ujian
        $attempt = QuizAttempt::where('user_id', $user->id)->first();

        if ($attempt) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah pernah menyelesaikan ujian.');
        }

        // Ambil paket soal berdasarkan question_set_id dan load relasi questions dan answers
        $questionSet = QuestionSet::with('questions.answers')->find($user->question_set_id);
        $questions = $questionSet->questions->toArray(); // Ubah ke array untuk pengacakan

        if (!session()->has('question_order')) {
            // Acak urutan soal
            shuffle($questions);

            // Simpan urutan soal yang sudah diacak di sesi
            session(['question_order' => $questions]);
        } else {
            // Ambil urutan soal dari sesi
            $questions = session('question_order');
        }

        // Ambil jawaban pengguna dari tabel user_answers
        $userAnswers = UserAnswer::where('user_id', $user->id)->get()->keyBy('question_id');

        // Acak urutan jawaban di setiap soal
        foreach ($questions as &$question) {
            if (isset($question['answers'])) {
                $answers = $question['answers']; // Asumsikan ini adalah array jawaban
                shuffle($answers);
                $question['answers'] = $answers;
            }
        }

        // Menyimpan urutan soal yang sudah diacak di sesi
        session(['questions' => $questions]);

        return view('user.mulai-ujian', compact('questions', 'questionSet', 'userAnswers'));
    }

    public function submitExam(Request $request)
    {
        $user = auth()->user();
        $score = 0;

        $answers = $request->input('answers', []);

        $startedAt = session('exam_started_at');

        if (!$startedAt) {
            return redirect()->route('dashboard')->with('error', 'Waktu mulai ujian tidak ditemukan.');
        }

        // Jika tidak ada jawaban, set score ke 0 dan simpan
        if (empty($answers)) {
            $quizAttempt = QuizAttempt::create([
                'user_id' => $user->id,
                'started_at' => $startedAt, // Asumsikan Anda menyimpan ini di session saat ujian dimulai
                'ended_at' => now(),
                'score' => 0,
            ]);

            return redirect()->route('dashboard')->with('success', 'Ujian telah selesai');
        }

        // Calculate score based on the user's answers
        foreach ($request->answers as $question_id => $answer_id) {
            $answer = Answer::find($answer_id);
            if ($answer) {
                $score += $answer->score; // Sum the score of the selected answers
            }
        }

        // Store the quiz attempt
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'started_at' => $startedAt, // Assuming you store this in session when the exam starts
            'ended_at' => now(),
            'score' => $score,
        ]);

        session()->forget('exam_started_at');

        return redirect()->route('dashboard')->with('success', 'Ujian telah selesai.');
    }

    public function saveAnswer(Request $request)
    {
        // Validasi data request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:answers,question_id',
            'answer_id' => 'required|exists:answers,id',
        ]);

        // Simpan jawaban ke database
        UserAnswer::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'question_id' => $request->question_id,
            ],
            ['answer_id' => $request->answer_id]
        );

        return response()->json(['message' => 'Answer saved successfully.']);
    }

    public function getQuestionId($answerId)
    {
        $answer = Answer::find($answerId);

        if (!$answer) {
            return response()->json(['message' => 'Answer not found'], 404);
        }

        return response()->json(['question_id' => $answer->question_id]);
    }

    public function getUserAnswers()
    {
        $userId = auth()->user()->id;
        $answers = DB::table('user_answers')
            ->where('user_id', $userId)
            ->get(['question_id', 'answer_id']);
        return response()->json($answers);
    }
}
