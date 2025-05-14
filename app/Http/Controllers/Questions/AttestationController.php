<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AttestationController extends Controller
{
    public function show(Test $test)
    {
        $test->load(['questions.options']);

        return Inertia::render('attestation/TakeTest', [
            'test' => $test->only(['id', 'name', 'time_limit']) + [
                'questions' => $test->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'options' => $question->options->map(fn ($option) => [
                            'id' => $option->id,
                            'text' => $option->text,
                        ]),
                    ];
                }),
            ],
        ]);
    }


   public function submit(Request $request, Test $test)
    {
        $data = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_option_id' => 'nullable|exists:question_options,id',
            'answers.*.text' => 'nullable|string',
        ]);

        $correctAnswers = 0;
        $totalQuestions = $test->questions()->count();

        foreach ($data['answers'] as $answer) {
            $question = $test->questions()->find($answer['question_id']);

            if ($question && $answer['selected_option_id']) {
                $correctOption = $question->options()->where('is_correct', true)->first();
                if ($correctOption && $correctOption->id == $answer['selected_option_id']) {
                    $correctAnswers++;
                }
            }
        }

        $percentage = $totalQuestions ? round(($correctAnswers / $totalQuestions) * 100) : 0;
        $isPassed = $percentage >= 60;

        $result = \App\Models\Result::create([
            'user_id' => auth()->id(),
            'test_id' => $test->id,
            'score' => $correctAnswers,
            'percentage' => $percentage,
            'is_passed' => $isPassed,
            'attempted_at' => now(),
        ]);

        return redirect()->route('results.show', $result->id);
    }
}
