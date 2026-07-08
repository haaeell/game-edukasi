@extends('layouts.user')

@section('content')
    <div class="panel mx-auto max-w-3xl p-8">
        <h1 class="text-2xl font-bold text-slate-900">Profil</h1>
        <p class="mt-2 text-sm text-slate-500">Perbarui data akun user Anda.</p>

        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-5 md:grid-cols-2">
            @csrf
            <div>
                <label class="label" for="name">Nama</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" class="field" required>
                @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="field" required>
                @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="phone">Nomor Telepon</label>
                <input id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="field" required>
                @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="label" for="photo">Foto Profil</label>
                <input id="photo" name="photo" type="file" class="field">
                @error('photo')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <label class="label" for="address">Alamat</label>
                <textarea id="address" name="address" rows="4" class="field" required>{{ old('address', $user->address) }}</textarea>
                @error('address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
