<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\QuestionSetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\RegisterController;

Route::get('/admin', function () {
    return view('admin.login');
});

Route::get('/admin/register', [AdminController::class, 'registerAdmin'])->name('register.admin');
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register');
Route::get('/admin/login', [AdminController::class, 'loginAdmin'])->name('login.admin');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::middleware(['auth.admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // manajemen soal
    Route::get('/admin/soal', [AdminController::class, 'soalPage'])->name('admin.soal');
    Route::get('/admin/soal/ks/create/{questionSetId}', [AdminController::class, 'soalKs'])->name('admin.soal.ks.create');
    Route::get('/admin/soal/guru/create/{questionSetId}', [AdminController::class, 'soalGuru'])->name('admin.soal.guru.create');
    Route::post('/admin/soal/guru/store', [AdminController::class, 'storeQuestionGuru'])->name('admin.storeQuestionGuru');
    Route::post('/admin/soal/kepsek/store', [AdminController::class, 'storeQuestionKepsek'])->name('admin.storeQuestionKepsek');
    Route::get('/admin/soal/ks/{question_set_id}', [AdminController::class, 'showQuestionsKs'])->name('admin.ks.detail-soal');
    Route::get('/admin/soal/guru/{question_set_id}', [AdminController::class, 'showQuestionsGuru'])->name('admin.guru.detail-soal');
    Route::delete('/admin/soal/{id}', [AdminController::class, 'hapusSoal'])->name('hapus.soal');
    Route::get('/admin/indikator/by-kompetensi/{kompetensi_id}', [AdminController::class, 'getIndikatorByKompetensi'])->name('admin.indikator.by-kompetensi');
    Route::get('/admin/soal/filter', [QuestionSetController::class, 'filterByRole'])->name('admin.soal.filter');
    Route::get('/admin/soal/guru/detail/filter', [QuestionController::class, 'filterSoalByKompetensi'])->name('admin.soal.filter');
    Route::get('/admin/soal/edit/ks/{id}', [AdminController::class, 'showEditFormKs'])->name('admin.soal.edit.ks');
    Route::get('/admin/soal/edit/guru/{id}', [AdminController::class, 'showEditFormGuru'])->name('admin.soal.edit.guru');
    Route::put('/admin/soal/guru/update/{id}', [AdminController::class, 'editQuestionGuru'])->name('admin.soal.update.guru');
    Route::put('/admin/soal/ks/update/{id}', [AdminController::class, 'editQuestionKepsek'])->name('admin.soal.update.ks');

    // manajemen paket soal
    Route::get('/admin/add-paket-soal', [QuestionSetController::class, 'create'])->name('create.QuestionSet');
    Route::post('/admin/paket-soal', [QuestionSetController::class, 'store'])->name('store.QuestionSet');
    Route::get('/admin/paket-soal', [QuestionSetController::class, 'index'])->name('index.QuestionSet');
    Route::get('/admin/edit/{question_set_id}', [AdminController::class, 'editPaketSoal'])->name('admin.editPaketSoal');
    Route::put('/admin/soal/{id}/edit', [QuestionSetController::class, 'update'])->name('admin.putPaketSoal');
    Route::delete('/admin/delete/{id}', [QuestionSetController::class, 'destroy'])->name('admin.deletePaketSoal');

    // manajemen hasil tes peserta
    Route::get('/admin/hasil', [AdminController::class, 'resultPage'])->name('hasil');
    Route::get('/admin/data-peserta', [AdminController::class, 'dataPeserta'])->name('data.peserta');
    Route::get('/jawaban-peserta/{userId}', [AdminController::class, 'jawabanPeserta'])->name('jawaban.peserta');
    Route::get('/admin/data-guru', [AdminController::class, 'dataGuru'])->name('data.guru');
    Route::get('/admin/data-kepsek', [AdminController::class, 'dataKepsek'])->name('data.kepala_sekolah');
    Route::get('/admin/hasil-guru', [AdminController::class, 'resultGuru'])->name('hasil.guru');
    Route::get('/admin/hasil-kepsek', [AdminController::class, 'resultKepsek'])->name('hasil.kepala_sekolah');
    Route::delete('/admin/hasil/kepsek/delete/{id}', [AdminController::class, 'hapusHasilKepsek'])->name('hapus.hasil.kepsek');
    Route::delete('/admin/hasil/guru/delete/{id}', [AdminController::class, 'hapusHasilGuru'])->name('hapus.hasil.guru');
    Route::get('/admin/grafik-individu/{userId}', [AdminController::class, 'grafikIndividu'])->name('grafik.individu');
    Route::get('/admin/grafik-kepsek', [AdminController::class, 'grafikKepsek'])->name('grafik.kepsek');
    Route::get('/admin/grafik-guru', [AdminController::class, 'grafikGuru'])->name('grafik.guru');
    Route::get('/admin/hasil/kepsek/export', [ExportController::class, 'exportResultsKepsek'])->name('admin.results.kepsek');
    Route::get('/admin/hasil/guru/export', [ExportController::class, 'exportGuruResults'])->name('admin.results.guru');
    Route::get('/export-answers/{userId}', [ExportController::class, 'exportAnswersUser'])->name('export.answers');

    // manajemen pengguna
    Route::post('/admin/register/ks', [RegisterController::class, 'registerKepsek'])->name('store.register.kepsek');
    Route::post('/admin/register/guru', [RegisterController::class, 'registerGuru'])->name('store.register.guru');
    Route::get('/admin/guru/edit/{id}', [AdminController::class, 'editGuru'])->name('admin.edit.guru');
    Route::put('/admin/guru/update/{id}', [AdminController::class, 'updateGuru'])->name('admin.update.guru');
    Route::get('/admin/kepsek/edit/{id}', [AdminController::class, 'editKepsek'])->name('admin.edit.kepsek');
    Route::put('/admin/kepsek/update/{id}', [AdminController::class, 'updateKepsek'])->name('admin.update.kepsek');
    Route::delete('/admin/guru/delete/{id}', [AdminController::class, 'destroyGuru'])->name('admin.delete.guru');
    Route::delete('/admin/kepsek/delete/{id}', [AdminController::class, 'destroyKepsek'])->name('admin.delete.kepsek');
    Route::get('/admin/kepsek/create', [AdminController::class, 'tambahKepsek'])->name('admin.tambah.kepsek');
    Route::get('/admin/guru/create', [AdminController::class, 'tambahGuru'])->name('admin.tambah.guru');
    Route::get('admin/search/guru', [AdminController::class, 'searchGuru'])->name('search.guru');
    Route::get('admin/search/ks', [AdminController::class, 'searchKs'])->name('search.ks');

    // log aktivitas
    Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.log');
    Route::get('/admin/logs/filter', [AdminController::class, 'filterLogsByAdmin'])->name('admin.logs.filter');

    // permintaan penghapusan
    Route::get('admin/permintaan', [AdminController::class, 'permintaanPage'])->name('admin.permintaan');
    Route::post('admin/permintaan/store', [AdminController::class, 'storePermintaan'])->name('admin.permintaan.store');
    Route::delete('/allowance/{id}/cancel', [AdminController::class, 'cancelPermintaan'])->name('admin.permintaan.cancel');

    // persetujuan penghapusan
    Route::get('admin/persetujuan', [AdminController::class, 'persetujuanPage'])->name('admin.persetujuan');
    Route::post('/admin/persetujuan/approve/{id}', [AdminController::class, 'approveAllowance'])->name('admin.persetujuan.approve');
    Route::post('/admin/persetujuan/reject/{id}', [AdminController::class, 'rejectAllowance'])->name('admin.persetujuan.reject');
});
