<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuestionSet;
use App\Models\Question;
use App\Models\Answer;

class QuestionController extends Controller
{
    public function create()
    {
        $questionSets = QuestionSet::all();
        return view('admin.questions.create', compact('questionSets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
            'question_set_id' => 'required|exists:question_sets,id',
            'kompetensi_id' => 'required|exists:kompetensi,id',
        ]);

        Question::create($request->all());

        return redirect()->route('admin.detail-soal')->with('success', 'Soal Berhasil Ditambah');
    }
}
