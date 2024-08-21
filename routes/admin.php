<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuestionSetController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/register', [AdminController::class, 'registerAdmin'])->name('register.admin');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register');
Route::get('/admin/login', [AdminController::class, 'loginAdmin'])->name('login.admin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/soal', [AdminController::class, 'soalPage'])->name('admin.soal');
    Route::get('/admin/soal/create', [AdminController::class, 'soal'])->name('admin.soal.create');
    Route::post('/admin/questions', [AdminController::class, 'storeQuestion'])->name('admin.storeQuestion');
    Route::get('/admin/soal/{question_set_id}', [AdminController::class, 'showQuestions'])->name('admin.detail-soal');
    Route::get('/admin/add-paket-soal', [QuestionSetController::class, 'create'])->name('create.QuestionSet');
    Route::post('/admin/paket-soal', [QuestionSetController::class, 'store'])->name('store.QuestionSet');
    Route::get('/admin/paket-soal', [QuestionSetController::class, 'index'])->name('index.QuestionSet');
    Route::get('/admin/hasil', [AdminController::class, 'resultPage'])->name('hasil');
    Route::get('/admin/data-peserta', [AdminController::class, 'dataPeserta'])->name('data.peserta');
    Route::get('/jawaban-peserta/{userId}', [AdminController::class, 'jawabanPeserta'])->name('jawaban.peserta');
    Route::get('/admin/data-guru', [AdminController::class, 'dataGuru'])->name('data.guru');
    Route::get('/admin/data-kepsek', [AdminController::class, 'dataKepsek'])->name('data.kepala_sekolah');
    Route::get('/admin/hasil-guru', [AdminController::class, 'resultGuru'])->name('hasil.guru');
    Route::get('/admin/hasil-kepsek', [AdminController::class, 'resultKepsek'])->name('hasil.kepala_sekolah');
    Route::get('/admin/edit-soal/{question_set_id}', [AdminController::class, 'editSoal'])->name('admin.edit-soal');
});
