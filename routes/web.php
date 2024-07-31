<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionSetController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('store.register');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/exam', [ExamController::class, 'index'])->name('exam')->middleware('auth');;
Route::get('/start-exam', [ExamController::class, 'examPage'])->name('examPage')->middleware('auth');;
Route::post('/submit-exam', [ExamController::class, 'submitExam'])->name('submitExam')->middleware('auth');;


Route::get('/admin/login', [AdminController::class, 'loginAdmin'])->name('login.admin');
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/soal', [AdminController::class, 'soalPage'])->name('admin.soal');
Route::get('/admin/soal/create', [AdminController::class, 'soal'])->name('admin.soal.create');
Route::post('/admin/questions', [AdminController::class, 'storeQuestion'])->name('admin.storeQuestion');
Route::get('/admin/soal/{question_set_id}', [AdminController::class, 'showQuestions'])->name('admin.detail-soal');

Route::get('/admin/add-paket-soal', [QuestionSetController::class, 'create'])->name('create.QuestionSet');
Route::post('/admin/paket-soal', [QuestionSetController::class, 'store'])->name('store.QuestionSet');
Route::get('/admin/paket-soal', [QuestionSetController::class, 'index'])->name('index.QuestionSet');
Route::get('/admin/hasil', [AdminController::class, 'resultPage'])->name('hasil');

Route::get('/verify-email/{token}', [EmailVerificationController::class, 'verify'])->name('verifyEmail');
