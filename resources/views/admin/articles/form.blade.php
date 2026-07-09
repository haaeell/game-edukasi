@extends('layouts.admin')

@section('page-title', $article ? 'Edit Artikel' : 'Tambah Artikel')
@section('page-description', 'Isi data artikel dengan rapi dan valid.')

@section('content')
    <div class="panel max-w-4xl p-6">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label class="label" for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title', $article->title ?? '') }}" class="field" required>
                @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="slug">Slug</label>
                <input id="slug" name="slug" value="{{ old('slug', $article->slug ?? '') }}" class="field">
                @error('slug')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="thumbnail">Thumbnail</label>
                <input id="thumbnail" name="thumbnail" type="file" class="field">
                @error('thumbnail')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="content">Konten</label>
                <textarea id="content" name="content" rows="10" class="field" required>{{ old('content', $article->content ?? '') }}</textarea>
                @error('content')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="field">
                    @foreach (['draft', 'published'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $article->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan</button>
                <a href="{{ route('admin.articles.index') }}" class="btn-secondary w-full sm:w-auto">Kembali</a>
            </div>
        </form>
    </div>
@endsection
