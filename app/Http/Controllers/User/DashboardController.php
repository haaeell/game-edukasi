<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\GameCardSet;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('user.dashboard', [
            'articles' => Article::where('status', 'published')->latest()->take(3)->get(),
            'videos' => Video::where('status', 'published')->latest()->take(3)->get(),
            'cardSets' => GameCardSet::where('status', 'active')->latest()->take(4)->get(),
        ]);
    }
}
