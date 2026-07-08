<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\GameCardSet;
use App\Models\GameRoom;
use App\Models\User;
use App\Models\Video;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'totalUsers' => User::count(),
                'totalArticles' => Article::count(),
                'totalVideos' => Video::count(),
                'totalCardSets' => GameCardSet::count(),
                'totalRooms' => GameRoom::count(),
                'activeRooms' => GameRoom::where('status', 'playing')->count(),
                'finishedGames' => GameRoom::where('status', 'finished')->count(),
            ],
        ]);
    }
}
