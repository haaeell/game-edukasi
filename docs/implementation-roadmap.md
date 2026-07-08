# Roadmap Implementasi

## Urutan Pengerjaan

1. Migration semua tabel
2. Model dan relationship
3. Seeder awal
4. Middleware role admin dan user
5. Auth register dan login
6. Layout admin dan user
7. CRUD artikel
8. CRUD video
9. CRUD game card set
10. CRUD game cards dengan ordering
11. Create room
12. Join room dengan kode
13. Join anonymous untuk user registered
14. Join guest jika `allow_guest = true`
15. Invite email dengan token
16. Waiting room
17. Gameplay room
18. Chat group dengan AJAX polling
19. Manual card flow
20. Automatic card flow dengan countdown
21. Admin game report
22. Validasi dan SweetAlert untuk action penting

## Saran Fase Implementasi

### Fase 1 - Fondasi Sistem

- Setup auth, role, middleware, layout admin, dan layout user
- Buat migration, model, relationship, request validation, dan seeder
- Siapkan helper umum seperti slug generator, room code generator, dan YouTube embed parser

### Fase 2 - Konten Edukasi

- Implementasi modul artikel
- Implementasi modul video
- Lengkapi halaman dashboard user agar artikel dan video dapat diakses dengan mudah

### Fase 3 - Fondasi Game

- Implementasi game card set
- Implementasi game cards dan ordering
- Implementasi create room, join room, participant management, dan waiting room

### Fase 4 - Interaksi Room

- Implementasi invitation email
- Implementasi gameplay room
- Implementasi chat AJAX polling
- Implementasi anonymous mode

### Fase 5 - Kontrol dan Laporan

- Implementasi manual dan automatic card flow
- Implementasi countdown
- Implementasi admin monitoring room dan game report
- Finalisasi validasi, policy, dan perapihan UX

## Catatan Teknis yang Disarankan

- Pisahkan logic bisnis game ke service class agar controller tetap ringan.
- Gunakan Form Request untuk semua validasi CRUD dan aksi game.
- Gunakan policy atau gate untuk otorisasi room host dan participant.
- Simpan semua endpoint polling di area yang mudah diganti ke broadcast realtime nanti.
- Gunakan partial Blade untuk komponen yang sering dipakai seperti card, table, modal, dan alert.

## Deliverable per Modul

Setiap modul idealnya selesai dengan paket berikut:

- Migration
- Model dan relationship
- Seeder jika perlu
- Controller
- Form Request
- Blade views
- Routes
- Test minimal untuk flow utama

## Langkah Berikutnya

Dokumen ini bisa langsung dipakai sebagai acuan implementasi bertahap di repo Laravel ini. Jika dilanjutkan ke coding, fase terbaik untuk mulai adalah fondasi sistem dan struktur database terlebih dahulu.
