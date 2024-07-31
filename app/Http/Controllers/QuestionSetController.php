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
            'name' => 'required|string|max:255',
            'time_limit' => 'required|integer',
        ]);

        QuestionSet::create($request->all());

        return redirect()->route('index.QuestionSet')->with('success', 'Question set created successfully.');
    }

    public function index()
    {
        $questionSets = QuestionSet::all();
        return view('admin.paket_soal.index', compact('questionSets'));
    }
}
