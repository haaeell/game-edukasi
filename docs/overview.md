# Ringkasan Proyek

## Gambaran Umum

Game Edukasi adalah aplikasi web berbasis Laravel untuk pembelajaran interaktif. Sistem memiliki dua role utama:

- Admin untuk mengelola konten, kartu permainan, room, dan laporan.
- User untuk membaca materi, menonton video, membuat room game, join room, dan berdiskusi melalui chat.

Fitur inti aplikasi ada pada modul game room, di mana host memilih satu card set lalu peserta menjawab pertanyaan kartu secara bergiliran melalui chat group.

## Tujuan Produk

- Menyediakan platform edukasi yang ringan dan interaktif.
- Menggabungkan materi pasif seperti artikel dan video dengan aktivitas partisipatif berbasis game.
- Mendukung sesi belajar, ice breaking, deep talk, dan diskusi kelompok.

## Stack Utama

- Backend: Laravel
- Frontend: Blade, Tailwind CSS, jQuery
- UI helper: SweetAlert2, DataTables
- Database: MySQL
- Storage: Laravel Storage
- Email: Laravel Mail
- Typography: Poppins

## Prinsip Implementasi

- Tampilan modern, clean, responsive, dan mobile friendly.
- Layout admin dan user dipisah.
- Upload file disimpan via Laravel Storage.
- Fitur realtime awal menggunakan AJAX polling agar sederhana dan stabil.
- Struktur kode disiapkan untuk upgrade ke broadcast realtime di fase berikutnya.

## Modul Utama

- Authentication dan role management
- Dashboard admin dan dashboard user
- Modul artikel
- Modul video
- Modul game card set
- Modul game cards
- Modul room game
- Modul invitation email
- Modul chat dan gameplay
- Modul laporan game

## Dokumen Lanjutan

- [Spesifikasi Sistem](./system-specification.md)
- [Roadmap Implementasi](./implementation-roadmap.md)
