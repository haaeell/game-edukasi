@extends('layouts.user')

@section('content')
    <div class="panel mx-auto max-w-4xl p-8">
        <h1 class="text-3xl font-bold text-slate-900">Buat Room Game</h1>
        <p class="mt-2 text-sm text-slate-500">Host memilih card set, mode kartu, dan aturan guest sebelum memulai permainan.</p>

        <form action="{{ route('user.game.store') }}" method="POST" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf

            <div class="md:col-span-2">
                <label class="label" for="title">Nama Room</label>
                <input id="title" name="title" value="{{ old('title') }}" class="field" required>
                @error('title')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="game_card_set_id">Card Set</label>
                <select id="game_card_set_id" name="game_card_set_id" class="field" required>
                    <option value="">Pilih card set</option>
                    @foreach ($cardSets as $set)
                        <option value="{{ $set->id }}" @selected((string) old('game_card_set_id') === (string) $set->id)>{{ $set->title }}</option>
                    @endforeach
                </select>
                @error('game_card_set_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="card_flow_type">Mode Perpindahan Kartu</label>
                <select id="card_flow_type" name="card_flow_type" class="field" required>
                    <option value="manual" @selected(old('card_flow_type') === 'manual')>Manual</option>
                    <option value="automatic" @selected(old('card_flow_type') === 'automatic')>Automatic</option>
                </select>
            </div>

            <div id="auto-next-wrapper">
                <label class="label" for="auto_next_seconds">Durasi Default per Kartu</label>
                <input id="auto_next_seconds" name="auto_next_seconds" type="number" min="5" value="{{ old('auto_next_seconds', 60) }}" class="field">
                @error('auto_next_seconds')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-3 pt-8">
                <input id="allow_guest" name="allow_guest" type="checkbox" value="1" @checked(old('allow_guest')) class="h-4 w-4 rounded border-slate-300">
                <label for="allow_guest" class="text-sm text-slate-700">Izinkan guest join room</label>
            </div>

            <div class="rounded-[1.6rem] border border-slate-200 bg-slate-50/80 p-5 md:col-span-2">
                <div class="text-sm font-semibold text-slate-900">Host ikut bermain?</div>
                <p class="mt-1 text-sm text-slate-500">Tentukan apakah host ikut menerima kartu atau hanya memandu sesi.</p>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4">
                        <input type="radio" name="host_is_player" value="1" class="mt-1 h-4 w-4 border-slate-300" {{ old('host_is_player', '1') === '1' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Ya, host ikut bermain</span>
                            <span class="mt-1 block text-sm text-slate-500">Host bisa ikut menjadi target kartu saat sesi berlangsung.</span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-4">
                        <input type="radio" name="host_is_player" value="0" class="mt-1 h-4 w-4 border-slate-300" {{ old('host_is_player') === '0' ? 'checked' : '' }}>
                        <span>
                            <span class="block text-sm font-semibold text-slate-900">Tidak, host hanya memandu</span>
                            <span class="mt-1 block text-sm text-slate-500">Host tetap ada di room, tetapi tidak akan dipilih untuk menjawab kartu.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <input id="is_anonymous" name="is_anonymous" type="checkbox" value="1" @checked(old('is_anonymous')) class="h-4 w-4 rounded border-slate-300">
                <label for="is_anonymous" class="text-sm text-slate-700">Tampil sebagai anonymous saat menjadi host</label>
            </div>

            <div class="md:col-span-2">
                <label class="label" for="anonymous_name">Nama Anonymous</label>
                <input id="anonymous_name" name="anonymous_name" value="{{ old('anonymous_name') }}" class="field" placeholder="Opsional">
            </div>

            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="btn-primary">Buat Room</button>
                <a href="{{ route('user.game.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleAutoNextField() {
            const isAutomatic = $('#card_flow_type').val() === 'automatic';
            $('#auto-next-wrapper').toggle(isAutomatic);
        }

        $('#card_flow_type').on('change', toggleAutoNextField);
        toggleAutoNextField();
    </script>
@endpush
