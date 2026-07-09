@extends('layouts.admin')

@section('page-title', $card ? 'Edit Kartu' : 'Tambah Kartu')
@section('page-description', 'Atur pertanyaan untuk card set '.$set->title.'.')

@section('content')
    <div class="panel max-w-4xl p-6">
        <form action="{{ $action }}" method="POST" class="space-y-5">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif

            <div>
                <label class="label" for="title">Judul Kartu</label>
                <input id="title" name="title" value="{{ old('title', $card->title ?? '') }}" class="field" required>
            </div>

            <div>
                <label class="label" for="question">Pertanyaan</label>
                <textarea id="question" name="question" rows="6" class="field" required>{{ old('question', $card->question ?? '') }}</textarea>
            </div>

            <div>
                <label class="label" for="status">Status</label>
                <select id="status" name="status" class="field">
                    @foreach (['active', 'inactive'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $card->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" class="btn-primary w-full sm:w-auto">Simpan</button>
                <a href="{{ route('admin.game-card-sets.show', $set) }}" class="btn-secondary w-full sm:w-auto">Kembali</a>
            </div>
        </form>
    </div>
@endsection
