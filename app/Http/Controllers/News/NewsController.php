<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\User;
use App\Models\NewsType;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a carousel of the news.
     */
    public function index(): Response
    {
        $news = News::with(['user', 'type'])->get();

        return Inertia::render('news/News', [
            'news' => $news,
        ]);
    }

    /**
     * Display a list of the news.
     */
    public function list(): Response
    {
        $news = News::with(['user', 'type'])->get();
        $users = User::select('id', 'name')->get();
        $newsTypes = NewsType::select('id', 'name')->get();

        return Inertia::render('news/NewsList', [
            'news' => $news,
            'users' => $users,
            'newsTypes' => $newsTypes,
        ]);
    }

    /**
     * Display a list of the news view.
     */
    public function listView(): Response
    {
        $news = News::with(['user', 'type'])->get();
        $users = User::select('id', 'name')->get();
        $newsTypes = NewsType::select('id', 'name')->get();

        return Inertia::render('news/NewsListView', [
            'news' => $news,
            'users' => $users,
            'newsTypes' => $newsTypes,
        ]);
    }

    /**
     * Store a newly created news in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'news_type_id' => 'required|exists:news_types,id',
        ]);

        News::create($validated);
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'news_type_id' => 'required|exists:news_types,id',
        ]);

        $news->update($validated);
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(News $news)
    {
        $news->delete();
    }

    /**
     * Show the specified news.
     */
    public function show(News $news): Response
    {
        $news->load(['user', 'type']);

        return Inertia::render('news/Show', [
            'news' => $news
        ]);
    }
}
