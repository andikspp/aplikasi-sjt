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
        ]);

        Question::create($request->all());

        return redirect()->route('admin.questions.index')->with('success', 'Question created successfully.');
    }
}
