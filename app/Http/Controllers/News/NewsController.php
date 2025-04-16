<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $news = News::with(['user', 'type'])->get();

        return Inertia::render('news/News', [
            'news' => $news
        ]);
    }

    public function list(): Response
    {
        $news = News::with(['user', 'type'])->get();

        return Inertia::render('news/NewsList', [
            'news' => $news
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateNewsRequest $request)
    {
        $validated = $request->validated();
        News::create($validated);

        return redirect()->route('news.index')
                         ->with('success', 'Новость успешно добавлена.');
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news): Response
    {
        $news->load(['user', 'type']);

        return Inertia::render('news/Show', [
            'news' => $news
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'string|max:255',
            'content' => 'string',
            'user_id' => 'exists:users,id',
            'news_type_id' => 'exists:news_types,id',
        ]);

        $news->update($request->all());

        return redirect()->route('news.index')
                         ->with('success', 'Новость успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('news.index')
                         ->with('success', 'Новость удалена.');
    }
}
