<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    public function filterSoalByKompetensi(Request $request)
    {
        try {
            $questionSetId = $request->query('questionSetId');
            $kompetensiId = $request->query('kompetensiId');
            $perPage = $request->query('perPage');

            $query = \App\Models\Question::with(['answers', 'kompetensi', 'indikator'])
                ->where('question_set_id', $questionSetId);

            if ($kompetensiId) {
                $query->where('kompetensi_id', $kompetensiId);
            }

            if ($perPage && $perPage !== 'all') {
                $questions = $query->paginate((int)$perPage);
                // Agar data JSON hanya berisi data yang dibutuhkan
                return response()->json([
                    'data' => $questions->items(),
                    'current_page' => $questions->currentPage(),
                    'last_page' => $questions->lastPage(),
                    'total' => $questions->total(),
                    'per_page' => $questions->perPage(),
                ]);
            } else {
                $questions = $query->get();
                return response()->json([
                    'data' => $questions,
                    'current_page' => 1,
                    'last_page' => 1,
                    'total' => $questions->count(),
                    'per_page' => $questions->count(),
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
