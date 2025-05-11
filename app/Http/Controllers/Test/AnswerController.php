<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Attempt;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $answers = Answer::with(['question', 'attempt'])->latest()->get();
        $questions = Question::all();
        $attempts = Attempt::all();

        return Inertia::render('tests/AnswerList', [
            'answers'  => $answers,
            'questions' => $questions,
            'attempts'  => $attempts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
            'time_spent' => 'nullable|integer',
            'question_id' => 'required|exists:questions,id',
            'attempt_id' => 'required|exists:attempts,id',
        ]);

        $answer = Answer::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_correct' => 'required|boolean',
            'time_spent' => 'nullable|integer',
            'question_id' => 'required|exists:questions,id',
            'attempt_id' => 'required|exists:attempts,id',
        ]);

        $answer->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Answer $answer)
    {
        $answer->delete();
    }
}
