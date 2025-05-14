<?php

namespace App\Http\Controllers\Test;;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestType;
use App\Models\Question;
use App\Models\QuestionType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tests = Test::with('testType')->get();
        $testTypes = TestType::all();

        return Inertia::render('tests/TestList', [
            'tests' => $tests,
            'testTypes' => $testTypes,
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $tests = Test::with('testType')->get();
        $testTypes = TestType::all();

        return Inertia::render('Test', [
            'tests' => $tests,
            'testTypes' => $testTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:tests',
            'description' => 'nullable|string',
            'test_types_id' => 'required|exists:test_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attempt_limit' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);
        $validated['start_date'] = date('Y-m-d H:i:s', strtotime($validated['start_date']));
        $validated['end_date'] = date('Y-m-d H:i:s', strtotime($validated['end_date']));

        Test::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $test = Test::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:tests,slug,' . $test->id,
            'description' => 'nullable|string',
            'test_types_id' => 'required|exists:test_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'attempt_limit' => 'required|integer|min:1',
            'time_limit' => 'nullable|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['start_date'] = date('Y-m-d H:i:s', strtotime($validated['start_date']));
        $validated['end_date'] = date('Y-m-d H:i:s', strtotime($validated['end_date']));

        $test->update($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): Response
    {
        try {
        $test = Test::findOrFail($id);

        $test->load(['questions', 'testType', 'questions.questionType',]);
        $testTypes = TestType::all();

        return Inertia::render('tests/Show', [
            'test' => $test,
            'questions' => $test->questions,
            'testTypes' => $testTypes,
            'questionTypes' => QuestionType::select('id', 'name')->get(),
        ]);
        } catch (ModelNotFoundException $e) {
            return Inertia::render('errors/NotFound', [
                'message' => 'Новость не найдена'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $test = Test::findOrFail($id);
        $test->delete();
        return redirect()->route('test.index')->with('success', 'Тест успешно удален');
    }
}
