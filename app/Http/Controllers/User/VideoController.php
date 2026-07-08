<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(): View
    {
        return view('user.videos.index', [
            'videos' => Video::where('status', 'published')->latest()->paginate(9),
        ]);
    }

    public function show(string $slug): View
    {
        $video = Video::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('user.videos.show', compact('video'));
    }
}
