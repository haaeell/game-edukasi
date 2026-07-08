<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreVideoRequest;
use App\Http\Requests\Admin\UpdateVideoRequest;
use App\Models\Video;
use App\Services\YouTubeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function __construct(private readonly YouTubeService $youTubeService)
    {
    }

    public function index(): View
    {
        return view('admin.videos.index', [
            'videos' => Video::with('creator')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.videos.create');
    }

    public function store(StoreVideoRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['youtube_embed_url'] = $this->youTubeService->toEmbedUrl($data['youtube_url']) ?? $data['youtube_url'];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        $data['created_by'] = $request->user()->id;

        Video::create($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil ditambahkan.');
    }

    public function edit(Video $video): View
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(UpdateVideoRequest $request, Video $video): RedirectResponse
    {
        $data = $request->validated();
        $data['youtube_embed_url'] = $this->youTubeService->toEmbedUrl($data['youtube_url']) ?? $data['youtube_url'];

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')->store('videos', 'public');
        }

        $video->update($data);

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil diperbarui.');
    }

    public function destroy(Video $video): RedirectResponse
    {
        if ($video->thumbnail) {
            Storage::disk('public')->delete($video->thumbnail);
        }

        $video->delete();

        return redirect()->route('admin.videos.index')->with('success', 'Video berhasil dihapus.');
    }
}
