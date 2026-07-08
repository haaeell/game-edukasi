@extends('layouts.admin')

@section('page-title', $set ? 'Edit Card Set' : 'Tambah Card Set')
@section('page-description', 'Kelola kategori pertanyaan game.')

@section('content')
    <div class="panel max-w-4xl p-6">
        <form action="{{ $action }}" method="POST" class="space-y-5">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label class="label" for="title">Judul</label>
                <input id="title" name="title" value="{{ old('title', $set->title ?? '') }}" class="field" required>
            </div>

            <div>
                <label class="label" for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="6" class="field">{{ old('description', $set->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="field">
                    @foreach (['active', 'inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $set->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="btn-primary">Simpan</button>
                <a href="{{ route('admin.game-card-sets.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection
