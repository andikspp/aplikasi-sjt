<?php

namespace App\Http\Controllers;

use App\Exports\ResultsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\AnswersExport;
use App\Exports\GuruAnswersExport;

class ExportController extends Controller
{
    public function exportResultsKepsek()
    {
        // Ambil semua soal dari database
        $questions = DB::table('questions')
            ->select('id', 'question_text')
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
            ->get();

        // Ambil data soal dan jawaban dari tabel user_answers
        $resultsWithAnswers = $results->map(function ($result) use ($questions) {
            // Ambil data soal dan jawaban dari tabel user_answers
            $userAnswers = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->where('user_answers.user_id', $result->id)
                ->select('questions.id as question_id', 'answers.answer_text')
                ->get();

            // Membuat array kosong untuk menyimpan jawaban berdasarkan soal
            $answersByQuestion = [];

            // Mengisi jawaban berdasarkan soal
            foreach ($questions as $question) {
                // Temukan jawaban untuk soal yang sesuai
                $answer = $userAnswers->firstWhere('question_id', $question->id);
                $answersByQuestion[$question->question_text] = $answer ? $answer->answer_text : 'Belum Dijawab';
            }

            // Menambahkan jawaban berdasarkan soal ke dalam hasil
            $result->answers_by_question = $answersByQuestion;
            return $result;
        });

        // Ekspor hasil ujian guru ke Excel dengan nama file berdasarkan nama peserta
        return Excel::download(new GuruAnswersExport($resultsWithAnswers, $questions), 'hasil_ujian_guru.xlsx');
    }

    public function exportGuruResults()
    {
        // Ambil semua soal dari database
        $questions = DB::table('questions')
            ->select('id', 'question_text')
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
            ->where('users.role', 'guru')
            ->get();

        // Ambil data soal dan jawaban dari tabel user_answers
        $resultsWithAnswers = $results->map(function ($result) use ($questions) {
            // Ambil data soal dan jawaban dari tabel user_answers
            $userAnswers = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->where('user_answers.user_id', $result->id)
                ->select('questions.id as question_id', 'answers.answer_text')
                ->get();

            // Membuat array kosong untuk menyimpan jawaban berdasarkan soal
            $answersByQuestion = [];

            // Mengisi jawaban berdasarkan soal
            foreach ($questions as $question) {
                // Temukan jawaban untuk soal yang sesuai
                $answer = $userAnswers->firstWhere('question_id', $question->id);
                $answersByQuestion[$question->question_text] = $answer ? $answer->answer_text : 'Belum Dijawab';
            }

            // Menambahkan jawaban berdasarkan soal ke dalam hasil
            $result->answers_by_question = $answersByQuestion;
            return $result;
        });

        // Ekspor hasil ujian guru ke Excel dengan nama file berdasarkan nama peserta
        return Excel::download(new GuruAnswersExport($resultsWithAnswers, $questions), 'hasil_ujian_guru.xlsx');
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
