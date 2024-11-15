<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Answer;
use App\Models\QuestionSet;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;
use App\Models\UserAnswer;
use App\Models\Question;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            $role = $user->role;

            $questionSet = $user->questionSet;

            if ($questionSet) {
                $timeLimit = $questionSet->time_limit;
                $startExam = $questionSet->start_exam;
                $endExam = $questionSet->end_exam;

                $now = now();
                $statusMessage = null;

                if ($user->status === 'not_started' && request()->query('start_exam')) {
                    $user->status = 'on_going';
                    $user->save();
                }

                if ($user->status === 'on_going' && request()->query('submit_exam')) {
                    $user->status = 'submitted';
                    $user->save();
                }

                if ($now < $startExam) {
                    $statusMessage = 'Waktu pengerjaan belum dimulai';
                } elseif ($now > $endExam) {
                    $statusMessage = 'Waktu pengerjaan telah berakhir';
                }

                return view('user.ujian', [
                    'role' => $role,
                    'time_limit' => $timeLimit,
                    'start_exam' => $startExam,
                    'end_exam' => $endExam,
                    'status' => $user->status,
                    'statusMessage' => $statusMessage
                ]);
            } else {
                return redirect()->back()->withErrors(['QuestionSet not found.']);
            }
        } else {
            return redirect()->back()->withErrors(['User not found.']);
        }
    }

    public function examPage()
    {
        $user = Auth::user();

        // Cek apakah user memiliki paket soal yang ditetapkan
        if (!$user->question_set_id) {
            return redirect()->route('dashboard')->with('error', 'Anda belum ditetapkan paket soal.');
        }

        // Cek status user
        if ($user->status === 'not_started') {
            $questionSet = QuestionSet::find($user->question_set_id);
            $currentTime = now();
            $examEnd = $questionSet->end_exam;

            if ($currentTime->lt($questionSet->start_exam)) {
                return redirect()->route('dashboard')->with('error', 'Maaf, pengerjaan belum dimulai.');
            }

            if ($currentTime->gt($examEnd)) {
                return redirect()->route('dashboard')->with('error', 'Maaf, pengerjaan telah berakhir.');
            }

            if (!$questionSet) {
                return redirect()->route('dashboard')->with('error', 'Paket soal tidak ditemukan.');
            }

            // Jika statusnya 'not_started', atur status menjadi 'on_going' dan simpan waktu mulai ujian di sesi
            $user->status = 'on_going';
            $user->save();
        } elseif ($user->status === 'submitted') {
            return redirect()->route('dashboard')->with('error', 'Anda sudah melakukan pengerjaan.');
        } elseif ($user->status !== 'on_going') {
            return redirect()->route('dashboard')->with('error', 'Status pengerjaan tidak valid.');
        }

        // Ambil paket soal berdasarkan question_set_id dan load relasi questions dan answers
        $questionSet = QuestionSet::with('questions.answers')->find($user->question_set_id);

        $currentTime = now();
        $examStart = $questionSet->start_exam;
        $examEnd = $questionSet->end_exam;

        if (!$questionSet) {
            return redirect()->route('dashboard')->with('error', 'Paket soal tidak ditemukan.');
        }



        $questions = $questionSet->questions->toArray(); // Ubah ke array untuk pengacakan

        // Acak urutan soal jika belum diatur di sesi
        if (!session()->has('question_order')) {
            shuffle($questions);
            session(['question_order' => $questions]);
        } else {
            $questions = session('question_order');
        }

        // Ambil jawaban pengguna dari tabel user_answers
        // $userAnswers = UserAnswer::where('user_id', $user->id)->get()->keyBy('question_id');

        // Acak urutan jawaban di setiap soal
        foreach ($questions as &$question) {
            if (isset($question['answers'])) {
                $answers = $question['answers']; // Asumsikan ini adalah array jawaban
                shuffle($answers);
                $question['answers'] = $answers;
            }
        }

        // Ambil jawaban yang sudah disimpan oleh user
        $savedAnswers = DB::table('user_answers')
            ->where('user_id', auth()->id())
            ->pluck('answer_id', 'question_id')
            ->toArray();

        // Menyimpan urutan soal yang sudah diacak di sesi
        session(['questions' => $questions]);

        return view('user.mulai-ujian', compact('questions', 'questionSet', 'savedAnswers', 'examEnd'));
    }



    public function submitExam(Request $request)
    {
        $user = auth()->user();

        $score = 0;

        $questionSet = QuestionSet::find($user->question_set_id);

        if (!$questionSet) {
            return redirect()->route('dashboard')->with('error', 'Set soal tidak ditemukan.');
        }

        $answers = $request->input('answers', []);

        // Jika tidak ada jawaban, set score ke 0 dan simpan
        if (empty($answers)) {
            $user->status = 'submitted';
            $user->save();
            $quizAttempt = QuizAttempt::create([
                'user_id' => $user->id, // Asumsikan Anda menyimpan ini di session saat ujian dimulai
                'ended_at' => now(),
                'score' => 0,
            ]);

            return redirect()->route('dashboard')->with('success', 'Pengerjaan telah selesai, terima kasih atas partisipasi anda.');
        }

        // Calculate score based on the user's answers
        foreach ($request->answers as $question_id => $answer_id) {
            $answer = Answer::find($answer_id);
            if ($answer) {
                $score += $answer->score; // Sum the score of the selected answers
            }
        }

        $user->status = 'submitted';
        $user->save();

        // Store the quiz attempt
        $quizAttempt = QuizAttempt::create([
            'user_id' => $user->id,
            'ended_at' => now(),
            'score' => $score,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengerjaan telah selesai. Terima kasih atas partisipasi anda.');
    }

    public function saveAnswer(Request $request)
    {
        // Validasi data request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required|exists:answers,question_id',
            'answer_id' => 'required|exists:answers,id',
        ]);

        $kompetensiId = Question::where('id', $request->question_id)->value('kompetensi_id');

        if (is_null($kompetensiId)) {
            return response()->json(['message' => 'Kompetensi ID not found for the selected question.'], 404);
        }

        // Simpan jawaban ke database
        UserAnswer::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'question_id' => $request->question_id,
            ],
            [
                'answer_id' => $request->answer_id,
                'kompetensi_id' => $kompetensiId,
            ]
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
