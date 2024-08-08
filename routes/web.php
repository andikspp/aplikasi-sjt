<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuestionSetController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

require base_path('routes/admin.php');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('store.register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/exam', [ExamController::class, 'index'])->name('exam');
    Route::get('/start-exam', [ExamController::class, 'examPage'])->name('examPage');
    Route::post('/submit-exam', [ExamController::class, 'submitExam'])->name('submitExam');
    Route::get('/get-question-id/{answerId}', [ExamController::class, 'getQuestionId']);
    Route::post('/save-answer', [ExamController::class, 'saveAnswer'])->name('saveAnswer');
    Route::get('/get-user-answers', [ExamController::class, 'getUserAnswers']);
    Route::get('/get-question-order', function () {
        $questionOrder = session('question_order', []);
        return response()->json($questionOrder);
    });
    Route::get('/exam/page/{questionIndex}', [ExamController::class, 'showPage'])->name('showPage');
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
});

Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify'])->name('verifyEmail');
