<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Test;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $results = Result::with(['user', 'test'])->latest()->get();
        $users = User::all();
        $tests = Test::all();

        return Inertia::render('tests/ResultList', [
            'results' => $results,
            'users'       => $users,
            'tests'       => $tests,
        ]);
    }

    public function show(Result $result): Response
    {
        $result->load(['user', 'test']);
        $users = User::all();
        $tests = Test::all();

        return Inertia::render('tests/ResultShow', [
            'result' => $result,
            'users'       => $users,
            'tests'       => $tests,
        ]);
    }
}
