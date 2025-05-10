<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\QuestionCorrectAnswer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Gate;

class QuestionCorrectAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('question/QuestionCorrectAnswerList', [
            'correctAnswers' => QuestionCorrectAnswer::with('question')->get(),
            'questions' => Question::select('id', 'name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'text' => 'required|string',
        ]);

        QuestionCorrectAnswer::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionCorrectAnswer $questionCorrectAnswer)
    {
        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'text' => 'required|string',
        ]);

        $questionCorrectAnswer->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionCorrectAnswer $questionCorrectAnswer)
    {
        $questionCorrectAnswer->delete();
    }
}
