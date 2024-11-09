<?php

namespace App\Http\Controllers;

use App\Exports\ResultsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Exports\AnswersExport;

class ExportController extends Controller
{
    public function exportResultsKepsek()
    {
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

        $resultsWithCompetencyScores = $results->map(function ($result) {
            $competencyScores = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
                ->where('user_answers.user_id', $result->id)
                ->select('kompetensi.nama as kompetensi_name', DB::raw('SUM(answers.score) as total_score'))
                ->groupBy('kompetensi_name')
                ->pluck('total_score', 'kompetensi_name')
                ->toArray();

            // Add competency scores as an additional attribute to the result
            $result->competency_scores = $competencyScores;
            return $result;
        });

        return Excel::download(new ResultsExport($results), 'hasil_ujian_ks.xlsx');
    }

    public function exportGuruResults()
    {
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

        $resultsWithCompetencyScores = $results->map(function ($result) {
            $competencyScores = DB::table('user_answers')
                ->join('questions', 'user_answers.question_id', '=', 'questions.id')
                ->join('answers', 'user_answers.answer_id', '=', 'answers.id')
                ->join('kompetensi', 'questions.kompetensi_id', '=', 'kompetensi.id')
                ->where('user_answers.user_id', $result->id)
                ->select('kompetensi.nama as kompetensi_name', DB::raw('SUM(answers.score) as total_score'))
                ->groupBy('kompetensi_name')
                ->pluck('total_score', 'kompetensi_name')
                ->toArray();

            // Add competency scores as an additional attribute to the result
            $result->competency_scores = $competencyScores;
            return $result;
        });

        return Excel::download(new ResultsExport($results), 'hasil_ujian_guru.xlsx');
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
