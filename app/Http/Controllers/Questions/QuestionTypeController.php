<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use App\Models\QuestionType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questionType = QuestionType::all();

        return Inertia::render('question/QuestionTypeList', [
            'questionType' => $questionType,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        QuestionType::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestionType $questionType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);

        $questionType->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestionType $questionType)
    {
        $questionType->delete();
    }
}
