<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Test;
use App\Models\User;
use App\Models\Status;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attempts = Attempt::with(['user', 'test', 'status'])->latest()->get();
        $users = User::all();
        $tests = Test::all();
        $statuses = Status::all();

        return Inertia::render('tests/AttemptsList', [
            'attempts' => $attempts,
            'users'       => $users,
            'tests'       => $tests,
            'statusList'  => $statuses,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'test_id' => 'required|exists:tests,id',
            'total_questions' => 'required|integer',
            'passing_score' => 'required|integer',
            'started_at' => 'nullable|date',
            'finish_at' => 'nullable|date',
            'score' => 'nullable|integer',
            'is_passed' => 'required|boolean',
            'status_id' => 'nullable|exists:statuses,id',
        ]);
        $validated['started_at'] = date('Y-m-d H:i:s', strtotime($validated['started_at']));
        $validated['finish_at'] = date('Y-m-d H:i:s', strtotime($validated['finish_at']));

        Attempt::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attempt $attempt)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'test_id' => 'required|exists:tests,id',
            'total_questions' => 'required|integer',
            'passing_score' => 'required|integer',
            'started_at' => 'nullable|date',
            'finish_at' => 'nullable|date',
            'score' => 'nullable|integer',
            'is_passed' => 'required|boolean',
            'status_id' => 'nullable|exists:statuses,id',
        ]);
        $validated['started_at'] = date('Y-m-d H:i:s', strtotime($validated['started_at']));
        $validated['finish_at'] = date('Y-m-d H:i:s', strtotime($validated['finish_at']));

        $attempt->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attempt $attempt)
    {
        $attempt->delete();
    }
}
