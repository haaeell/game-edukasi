@extends('layouts.user')

@section('content')
    <article class="panel mx-auto max-w-4xl overflow-hidden">
        <div class="h-56 bg-gradient-to-r from-sky-200 to-slate-200"></div>
        <div class="p-8">
            <p class="text-sm text-slate-500">{{ $article->created_at->format('d M Y') }}</p>
            <h1 class="mt-3 text-4xl font-bold text-slate-900">{{ $article->title }}</h1>
            <div class="prose prose-slate mt-6 max-w-none">
                {!! nl2br(e($article->content)) !!}
            </div>
        </div>
    </article>
@endsection
