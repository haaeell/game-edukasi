<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        return view('user.articles.index', [
            'articles' => Article::where('status', 'published')->latest()->paginate(9),
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('user.articles.show', compact('article'));
    }
}
