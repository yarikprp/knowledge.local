<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\NewsType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NewsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::denies('role-access', 1)) {
            abort(403, 'Доступ запрещен');
        }

        $newsType = NewsType::all();

        return Inertia::render('news/NewsTypeList', [
            'newsType' => $newsType,
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

        NewsType::create($validated);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsType $newsType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $newsType->update($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsType $newsType)
    {
        $newsType->delete();
    }
}
