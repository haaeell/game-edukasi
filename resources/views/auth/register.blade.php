@extends('layouts.base')

@section('title', 'Daftar — SoluShare')

@section('body')
    <div class="flex min-h-screen items-center justify-center bg-[#eef4fb] px-0 py-0 sm:px-4 sm:py-10">
        <section class="panel min-h-screen w-full max-w-xl overflow-hidden rounded-none border-0 p-6 sm:min-h-0 sm:rounded-[1.5rem] sm:border sm:p-8">
            <div class="flex items-center justify-center gap-3">
                <div class="flex h-30 w-30 items-center justify-center ">
                    <img src="{{ asset('logo-header.png') }}" alt="SoluShare" class="h-full w-full object-contain">
                </div>
            </div>

            <div class="mt-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Buat Akun Baru</h1>
                <p class="mt-2 text-sm text-slate-500">Lengkapi data berikut untuk mulai bergabung.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" class="mt-8 grid gap-5 md:grid-cols-2">
                @csrf
                <div>
                    <label class="label" for="name">Nama Lengkap</label>
                    <input id="name" name="name" value="{{ old('name') }}" class="field" placeholder="Masukkan nama lengkap" required>
                    @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="field" placeholder="email@contoh.com" required>
                    @error('email')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="phone">Nomor Telepon</label>
                    <input id="phone" name="phone" value="{{ old('phone') }}" class="field" placeholder="08xxxxxxxxxx" required>
                    @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="label">Foto Profil</label>
                    <div class="flex items-center gap-5">
                        <div class="relative shrink-0">
                            <div id="photoPreviewWrapper" class="flex h-24 w-24 items-center justify-center overflow-hidden rounded-full border-2 border-dashed border-slate-200 bg-slate-50 text-3xl text-slate-300">
                                <i class="fa-solid fa-user" id="photoPlaceholderIcon"></i>
                                <img id="photoPreviewImg" src="" alt="Preview foto profil" class="hidden h-full w-full object-cover">
                            </div>
                            <label for="photo" class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-blue-600 text-white shadow-md ring-4 ring-white transition hover:bg-blue-700">
                                <i class="fa-solid fa-camera text-sm"></i>
                            </label>
                        </div>
                        <div>
                            <label for="photo" class="btn-secondary cursor-pointer">
                                <i class="fa-solid fa-upload mr-2"></i> Unggah Foto
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
                    <textarea id="address" name="address" rows="3" class="field" placeholder="Alamat lengkap" required>{{ old('address') }}</textarea>
                    @error('address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password">Password</label>
                    <input id="password" name="password" type="password" class="field" placeholder="Minimal 8 karakter" required>
                    @error('password')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="label" for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="field" placeholder="Ulangi password" required>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary w-full">Daftar</button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-blue-600">Masuk di sini</a>
            </p>

            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-500 hover:text-blue-600">Kembali ke landing page</a>
            </div>
        </section>
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
