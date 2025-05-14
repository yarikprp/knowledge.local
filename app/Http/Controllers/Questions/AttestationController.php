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


        return redirect()->route('dashboard')->with('success', 'Результаты отправлены!');
    }
}
