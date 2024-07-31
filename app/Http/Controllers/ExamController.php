<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Answer;
use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;

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

        // Acak urutan soal
        shuffle($questions);

        // Acak urutan jawaban di setiap soal
        foreach ($questions as &$question) {
            if (isset($question['answers'])) {
                $answers = $question['answers']; // Asumsikan ini adalah array jawaban
                shuffle($answers);
                $question['answers'] = $answers;
            }
        }

        return view('user.mulai-ujian', compact('questions', 'questionSet'));
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
}
