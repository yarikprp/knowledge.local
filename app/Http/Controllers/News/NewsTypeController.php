<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsTypeRequest;
use App\Models\NewsType;
use Illuminate\Http\Request;

class NewsTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return NewsType::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewsTypeRequest $request)
    {
        $validated = $request->validated();
        $newsType = NewsType::create($validated);

        return response()->json($newsType, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsType $newsType)
    {
        $request->validate([
            'name' => 'string|max:255',
        ]);

        $newsType->update($request->all());

        return response()->json($newsType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsType $newsType)
    {
        $newsType->delete();

        return response()->noContent();
    }
}
