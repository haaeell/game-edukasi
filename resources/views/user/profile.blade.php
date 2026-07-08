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

            <div class="md:col-span-2">
                <label class="label">Foto Profil</label>
                <div class="flex items-center gap-5">
                    <div class="relative shrink-0">
                        <div id="photoPreviewWrapper" class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 border-dashed border-slate-200 bg-slate-50 text-3xl text-slate-300">
                            <i class="fa-solid fa-user {{ $user->photo_url ? 'hidden' : '' }}" id="photoPlaceholderIcon"></i>
                            <img id="photoPreviewImg" src="{{ $user->photo_url }}" alt="Preview foto profil" class="{{ $user->photo_url ? '' : 'hidden' }} h-full w-full object-cover">
                        </div>
                        <label for="photo" class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-blue-600 text-white shadow-md ring-4 ring-white transition hover:bg-blue-700">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </label>
                    </div>
                    <div>
                        <label for="photo" class="btn-secondary cursor-pointer">
                            <i class="fa-solid fa-upload mr-2"></i> {{ $user->photo_url ? 'Ganti Foto' : 'Unggah Foto' }}
                        </label>
                        <p class="mt-2 text-xs text-slate-500">Format JPG atau PNG, maks. 2MB.</p>
                        <p id="photoFileName" class="mt-1 truncate text-xs font-semibold text-blue-600"></p>
                    </div>
                </div>
                <input id="photo" name="photo" type="file" accept="image/*" class="hidden">
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

@push('scripts')
    <script>
        const photoInput = document.getElementById('photo');
        const photoPreviewImg = document.getElementById('photoPreviewImg');
        const photoPlaceholderIcon = document.getElementById('photoPlaceholderIcon');
        const photoFileName = document.getElementById('photoFileName');

        if (photoInput) {
            photoInput.addEventListener('change', () => {
                const file = photoInput.files[0];

                if (!file) {
                    return;
                }

                photoFileName.textContent = file.name;

                const reader = new FileReader();
                reader.onload = (event) => {
                    photoPreviewImg.src = event.target.result;
                    photoPreviewImg.classList.remove('hidden');
                    photoPlaceholderIcon.classList.add('hidden');
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endpush
