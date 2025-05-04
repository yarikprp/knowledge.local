<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::with(['test', 'questionType'])->get();

        return Inertia::render('question/QuestionList', [
            'questions' => $questions,
            'tests' => Test::select('id', 'name')->get(),
            'questionTypes' => QuestionType::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'test_id' => 'required|exists:tests,id',
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'order' => 'nullable|integer',
        ]);

        Question::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'test_id' => 'required|exists:tests,id',
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'order' => 'nullable|integer',
        ]);

        $question->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
    }
}
