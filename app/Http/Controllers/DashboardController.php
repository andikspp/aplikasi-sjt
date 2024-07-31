<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\QuizAttempt;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil riwayat ujian untuk pengguna saat ini
        $quizAttempt = QuizAttempt::where('user_id', $user->id)->first();

        // Konversi string ke Carbon instance jika ada
        $startedAt = $quizAttempt ? Carbon::parse($quizAttempt->started_at)->setTimezone('Asia/Jakarta') : null;
        $endedAt = $quizAttempt ? Carbon::parse($quizAttempt->ended_at)->setTimezone('Asia/Jakarta') : null;

        // Kirimkan variabel ke view
        return view('user.dashboard', compact('user', 'quizAttempt', 'startedAt', 'endedAt'));
    }
}
