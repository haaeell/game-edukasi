@extends('layouts.user')

@section('content')
    <div class="panel mx-auto max-w-5xl overflow-hidden p-8">
        <p class="text-sm text-slate-500">{{ $video->created_at->format('d M Y') }}</p>
        <h1 class="mt-3 text-4xl font-bold text-slate-900">{{ $video->title }}</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600">{{ $video->description }}</p>

        <div class="mt-8 aspect-video overflow-hidden rounded-3xl">
            <iframe class="h-full w-full" src="{{ $video->youtube_embed_url }}" title="{{ $video->title }}" allowfullscreen></iframe>
        </div>
    </div>
@endsection
