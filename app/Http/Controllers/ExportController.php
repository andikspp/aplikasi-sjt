<?php

namespace App\Http\Controllers;

use App\Exports\ResultsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\AnswersExport;
use App\Exports\GuruAnswersExport;
use App\Exports\KepsekAnswersExport;
use Symfony\Component\HttpFoundation\Request;

class ExportController extends Controller
{
    public function exportResultsKepsek(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil semua soal dan set soal dari database, dengan memfilter berdasarkan role 'guru' pada question_sets
        $questions = DB::table('questions')
            ->join('question_sets', 'questions.question_set_id', '=', 'question_sets.id')
            ->select('questions.id', 'questions.question_text', 'question_sets.role')
            ->where('question_sets.role', 'kepala sekolah') // Memfilter berdasarkan role di question_sets
            ->get();

        // Ambil data pengguna dengan role 'guru'
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'kepala sekolah')
            ->whereBetween('quiz_attempts.ended_at', [$startDate, $endDate])
            ->get();

        // Ambil data soal dan jawaban dari tabel user_answers
        $resultsWithAnswers = $results->map(function ($result) use ($questions) {
            // Ambil data soal dan jawaban dari tabel user_answers
            $userAnswers = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->where('user_answers.user_id', $result->id)
                ->select('questions.id as question_id', 'answers.answer_text', 'answers.score') // Ambil score dari tabel answers
                ->get();

            // Membuat array kosong untuk menyimpan jawaban berdasarkan soal
            $answersByQuestion = [];

            // Mengisi jawaban berdasarkan soal
            foreach ($questions as $question) {
                // Temukan jawaban untuk soal yang sesuai, termasuk skor
                $answer = $userAnswers->firstWhere('question_id', $question->id);
                $answersByQuestion[$question->question_text] = [
                    'answer_text' => $answer->answer_text ?? 'Belum Dijawab',
                    'score' => $answer->score ?? 'Tidak Ada Skor'
                ];
            }

            // Menambahkan jawaban berdasarkan soal ke dalam hasil
            $result->answers_by_question = $answersByQuestion;
            return $result;
        });

        $fileName = 'hasil_ks_' . $startDate . '_to_' . $endDate . '.xlsx';
        return Excel::download(new GuruAnswersExport($resultsWithAnswers, $questions), $fileName);
    }

    public function exportGuruResults(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil semua soal dan set soal dari database, dengan memfilter berdasarkan role 'guru' pada question_sets
        $questions = DB::table('questions')
            ->join('question_sets', 'questions.question_set_id', '=', 'question_sets.id')
            ->select('questions.id', 'questions.question_text', 'question_sets.role')
            ->where('question_sets.role', 'guru') // Memfilter berdasarkan role di question_sets
            ->get();

        // Ambil data pengguna dengan role 'guru'
        $results = DB::table('users')
            ->join('question_sets', 'users.question_set_id', '=', 'question_sets.id')
            ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
            ->select(
                'users.id',
                'users.name',
                'users.username',
                'users.telepon',
                'users.instansi',
                'users.role',
                'question_sets.name as question_set_name',
                'quiz_attempts.ended_at',
                'quiz_attempts.score'
            )
            ->where('users.role', 'guru') // Hanya pengguna dengan role 'guru'
            ->whereBetween('quiz_attempts.ended_at', [$startDate, $endDate])
            ->get();

        // Ambil data soal dan jawaban dari tabel user_answers
        $resultsWithAnswers = $results->map(function ($result) use ($questions) {
            // Ambil data soal dan jawaban dari tabel user_answers
            $userAnswers = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->where('user_answers.user_id', $result->id)
                ->select('questions.id as question_id', 'answers.answer_text', 'answers.score') // Ambil score dari tabel answers
                ->get();

            // Membuat array kosong untuk menyimpan jawaban berdasarkan soal
            $answersByQuestion = [];

            // Mengisi jawaban berdasarkan soal
            foreach ($questions as $question) {
                // Temukan jawaban untuk soal yang sesuai, termasuk skor
                $answer = $userAnswers->firstWhere('question_id', $question->id);
                $answersByQuestion[$question->question_text] = [
                    'answer_text' => $answer->answer_text ?? 'Belum Dijawab',
                    'score' => $answer->score ?? 'Tidak Ada Skor'
                ];
            }

            // Menambahkan jawaban berdasarkan soal ke dalam hasil
            $result->answers_by_question = $answersByQuestion;
            return $result;
        });

        $fileName = 'hasil_guru_' . $startDate . '_to_' . $endDate . '.xlsx';
        return Excel::download(new GuruAnswersExport($resultsWithAnswers, $questions), $fileName);
    }




    public function exportAnswersUser($userId)
    {
        // Ambil data jawaban berdasarkan user_id
        $answers = DB::table('user_answers')
            ->join('questions', 'user_answers.question_id', '=', 'questions.id')
            ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
            ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
            ->where('user_answers.user_id', $userId) // Filter berdasarkan user_id
            ->select(
                'user_answers.id as user_answer_id', // ID jawaban pengguna
                'questions.question_text as question_text', // Teks pertanyaan
                'answers.answer_text as answer_text', // Teks jawaban
                'kompetensi.nama as kompetensi_name', // Nama kompetensi
                'answers.score' // Skor jawaban
            )
            ->get(); // Ambil semua data yang sesuai

        // Ambil nama pengguna berdasarkan userId
        $userName = DB::table('users')->where('id', $userId)->value('name');


        // Ekspor jawaban ke Excel menggunakan AnswersExport
        return Excel::download(new AnswersExport($answers), "{$userName}.xlsx");
    }
}
