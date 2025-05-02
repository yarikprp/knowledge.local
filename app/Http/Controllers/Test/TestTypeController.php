<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\TestType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class TestTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testType = TestType::all();

        return Inertia::render('tests/TestTypeList', [
            'testType' => $testType,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TestType::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TestType $testType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $testType->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TestType $testType)
    {
        $testType->delete();
    }
}
