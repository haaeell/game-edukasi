@extends('layouts.admin')

@section('page-title', $video ? 'Edit Video' : 'Tambah Video')
@section('page-description', 'Masukkan link YouTube dan data video.')

@section('content')
    <div class="panel max-w-4xl p-6">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label class="label" for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title', $video->title ?? '') }}" class="field" required>
            </div>

            <div>
                <label class="label" for="slug">Slug</label>
                <input id="slug" name="slug" value="{{ old('slug', $video->slug ?? '') }}" class="field">
            </div>

            <div>
                <label class="label" for="youtube_url">YouTube URL</label>
                <input id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url ?? '') }}" class="field" required>
                @error('youtube_url')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="thumbnail">Thumbnail</label>
                <input id="thumbnail" name="thumbnail" type="file" class="field">
            </div>

            <div>
                <label class="label" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="6" class="field">{{ old('description', $video->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="field">
                    @foreach (['draft', 'published'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $video->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan</button>
                <a href="{{ route('admin.videos.index') }}" class="btn-secondary w-full sm:w-auto">Kembali</a>
            </div>
        </form>
    </div>
@endsection
