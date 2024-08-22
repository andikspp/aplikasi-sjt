<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionSet;

class QuestionSetController extends Controller
{
    public function create()
    {
        return view('admin.paket_soal.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:question_sets',
            'time_limit' => 'required|integer',
            'start_exam' => 'required|date_format:Y-m-d\TH:i',
            'end_exam' => 'required|date_format:Y-m-d\TH:i|after:start_exam',
            'role' => 'required|in:Guru,Kepala Sekolah',
        ], [
            'name.unique' => 'Nama paket soal sudah terpakai.',
        ]);

        QuestionSet::create([
            'name' => $request->name,
            'time_limit' => $request->time_limit,
            'start_exam' => $request->start_exam,
            'end_exam' => $request->end_exam,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.soal')->with('success', 'Paket Soal berhasil disimpan');
    }

    public function index()
    {
        $questionSets = QuestionSet::all();
        return view('admin.paket_soal.index', compact('questionSets'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input dari form
        $request->validate([
            'name' => 'required|string|max:255|unique:question_sets,name,' . $id,
            'time_limit' => 'required|integer',
            'start_exam' => 'required|date_format:Y-m-d\TH:i',
            'end_exam' => 'required|date_format:Y-m-d\TH:i|after:start_exam',
            'role' => 'required|in:guru,Kepala Sekolah',
        ], [
            'name.unique' => 'Nama paket soal sudah terpakai.',
        ]);

        // Cari QuestionSet berdasarkan ID
        $questionSet = QuestionSet::findOrFail($id);

        // Update data QuestionSet
        $questionSet->update([
            'name' => $request->name,
            'time_limit' => $request->time_limit,
            'start_exam' => $request->start_exam,
            'end_exam' => $request->end_exam,
            'role' => $request->role,
        ]);

        // Redirect kembali ke halaman list soal dengan pesan sukses
        return redirect()->route('admin.soal')->with('success', 'Paket Soal berhasil diperbarui');
    }
}
