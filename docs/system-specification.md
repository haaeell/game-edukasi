# Spesifikasi Sistem

## 1. Role Sistem

### Admin

Admin dapat:

- Login ke dashboard admin
- Melihat statistik dashboard
- Mengelola user
- Mengelola artikel
- Mengelola video
- Mengelola game card set
- Mengelola kartu pertanyaan
- Melihat room game
- Melihat peserta room
- Melihat chat atau jawaban peserta
- Melihat laporan riwayat game

### User

User dapat:

- Register
- Login
- Melengkapi profil
- Membaca artikel
- Melihat video
- Membuat room game
- Join room menggunakan kode
- Invite teman ke room melalui email
- Join sebagai peserta biasa
- Join sebagai anonymous
- Chat di room game untuk menjawab pertanyaan kartu

## 2. Stack dan Konvensi UI

- Laravel
- Blade
- Tailwind CSS
- jQuery
- SweetAlert2
- DataTables untuk tabel admin, kecuali game cards
- Font Poppins
- MySQL
- Laravel Storage untuk upload file
- Laravel Mail untuk email invitation

Gaya antarmuka:

- Modern
- Clean
- Responsive
- Mobile friendly
- Background soft
- Card putih
- Button rounded
- Form dan table rapi

## 3. Authentication dan Routing

### Role dan Middleware

- `admin` hanya bisa mengakses area `/admin`
- `user` hanya bisa mengakses area `/user`
- `guest` boleh mengakses halaman invitation dan join room publik sesuai aturan room

### Prefix Route

- Admin: `/admin`
- User: `/user`
- Public game: `/game`

## 4. Register User

Field register:

- `name`
- `email`
- `password`
- `phone`
- `address`
- `photo` opsional

Aturan:

- Email harus unique
- Password wajib di-hash
- Validasi menggunakan Form Request Laravel
- Setelah register berhasil, user diarahkan ke dashboard user

### Struktur Tabel `users`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| name | string | wajib |
| email | string | unique |
| password | string | hashed |
| phone | string | nullable sesuai kebutuhan |
| address | text | nullable |
| photo | string | nullable |
| role | enum | `admin`, `user` |
| status | enum | `active`, `inactive` |
| timestamps | timestamps | default Laravel |

## 5. Menu User

Menu utama user:

- Dashboard
- Artikel
- Video
- Game
- Profil
- Logout

## 6. Modul Artikel

### Fitur Admin

- List artikel dengan DataTables
- Tambah artikel
- Edit artikel
- Hapus artikel dengan konfirmasi SweetAlert
- Upload thumbnail
- Ubah status `draft` atau `published`

### Fitur User

- List artikel berstatus `published`
- Detail artikel

### Struktur Tabel `articles`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| title | string | wajib |
| slug | string | unique |
| thumbnail | string | nullable |
| content | longText | wajib |
| status | enum | `draft`, `published` |
| created_by | foreignId | relasi ke users |
| timestamps | timestamps | default Laravel |

## 7. Modul Video

### Fitur Admin

- CRUD video
- Input URL YouTube
- Konversi URL biasa menjadi embed URL
- Publish atau draft video

### Fitur User

- List video berstatus `published`
- Detail video dengan embed YouTube

### Struktur Tabel `videos`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| title | string | wajib |
| slug | string | unique |
| youtube_url | string | wajib |
| youtube_embed_url | string | hasil parsing |
| thumbnail | string | nullable |
| description | text | nullable |
| status | enum | `draft`, `published` |
| created_by | foreignId | relasi ke users |
| timestamps | timestamps | default Laravel |

Catatan teknis:

- Buat helper atau service untuk mengubah URL YouTube standar menjadi URL embed.

## 8. Modul Game

### Konsep Inti

Admin membuat card set berisi daftar kartu pertanyaan. User membuat room, memilih card set, lalu peserta menjawab pertanyaan kartu melalui chat group.

### Mode Perpindahan Kartu

1. Manual
   Host mengontrol `next`, `previous`, dan `end game`.
2. Automatic
   Sistem berpindah kartu berdasarkan durasi default room atau durasi khusus per kartu.

## 9. Game Card Set

### Fitur Admin

- Tambah card set
- Edit card set
- Hapus card set
- Aktif atau nonaktifkan card set

### Struktur Tabel `game_card_sets`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| title | string | wajib |
| description | text | nullable |
| status | enum | `active`, `inactive` |
| created_by | foreignId | relasi ke users |
| timestamps | timestamps | default Laravel |

## 10. Game Cards

### Fitur Admin

- Tambah kartu
- Edit kartu
- Hapus kartu
- Atur urutan kartu
- Aktif atau nonaktifkan kartu

Catatan UI:

- Jangan gunakan DataTables.
- Gunakan tampilan list atau card biasa.
- Sediakan urutan dengan tombol naik turun atau drag and drop sederhana.

### Struktur Tabel `game_cards`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| game_card_set_id | foreignId | relasi ke card set |
| title | string | judul singkat |
| question | text | isi pertanyaan |
| order_number | integer | urutan permainan |
| duration_seconds | integer | nullable |
| status | enum | `active`, `inactive` |
| timestamps | timestamps | default Laravel |

## 11. Game Room

### Create Room

Saat membuat room, host memilih:

- Card set
- Nama room
- Mode perpindahan kartu: `manual` atau `automatic`
- Durasi default per kartu jika automatic
- Apakah room mengizinkan guest atau tidak

Kode room:

- Format uppercase 6 karakter
- Harus unique
- Contoh: `ABC123`, `X9K2LM`

### Struktur Tabel `game_rooms`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| code | string | unique, 6 uppercase |
| host_user_id | foreignId | user pembuat room |
| game_card_set_id | foreignId | card set terpilih |
| title | string | nama room |
| card_flow_type | enum | `manual`, `automatic` |
| auto_next_seconds | integer | nullable |
| allow_guest | boolean | default `true` |
| status | enum | `waiting`, `playing`, `finished` |
| current_game_card_id | foreignId | nullable |
| current_card_order | integer | nullable |
| current_card_started_at | timestamp | nullable |
| started_at | timestamp | nullable |
| ended_at | timestamp | nullable |
| timestamps | timestamps | default Laravel |

## 12. Peserta Room

Peserta room harus disimpan di tabel terpisah karena bisa berasal dari user login maupun guest.

### Struktur Tabel `game_room_participants`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| game_room_id | foreignId | relasi ke room |
| user_id | foreignId | nullable |
| guest_name | string | nullable |
| email | string | nullable |
| display_name | string | nama tampil publik |
| participant_type | enum | `registered`, `guest` |
| is_anonymous | boolean | default `false` |
| anonymous_name | string | nullable |
| is_host | boolean | default `false` |
| status | enum | `active`, `left` |
| joined_at | timestamp | waktu join |
| left_at | timestamp | nullable |
| timestamps | timestamps | default Laravel |

### Aturan Anonymous

1. User terdaftar tetap boleh tampil anonymous di room.
2. Jika user login memilih anonymous, `user_id` tetap disimpan.
3. Di tampilan room dan chat hanya tampil `display_name`.
4. Admin boleh melihat mapping user asli di dashboard admin.
5. Guest anonymous tidak memiliki `user_id`.
6. Anonymous berlaku per room, bukan global.

Logika nama tampil:

- Registered + non-anonymous: tampilkan nama asli user
- Registered + anonymous: tampilkan `anonymous_name`
- Guest: tampilkan `guest_name` atau `anonymous_name`

## 13. Join Room

### Join dengan Kode Room

Flow:

1. User buka menu game.
2. User input kode room.
3. Sistem validasi room.
4. Jika status room `waiting` atau `playing`, user boleh masuk.
5. User memilih tampil dengan nama asli atau anonymous.

### Join sebagai Guest

Flow:

1. Pengunjung input kode room.
2. Sistem cek `allow_guest`.
3. Jika diizinkan, sistem simpan participant tanpa `user_id`.
4. Guest masuk ke room dengan `display_name` yang dipilih.

### Aturan Penting

- Jangan buat participant duplicate untuk room yang sama.
- Jika room tidak mengizinkan guest, arahkan ke login atau register.

## 14. Invitation Email

Host dapat mengundang teman melalui email.

### Struktur Tabel `game_room_invitations`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| game_room_id | foreignId | relasi ke room |
| email | string | email penerima |
| token | string | unique |
| invited_by | foreignId | user pengundang |
| status | enum | `pending`, `accepted`, `expired` |
| expired_at | timestamp | default 3 hari |
| accepted_at | timestamp | nullable |
| timestamps | timestamps | default Laravel |

### Flow Invitation

1. Host input email teman.
2. Sistem membuat token acak dan unique.
3. Sistem kirim email berisi nama room, kode room, tombol join, dan link invitation.
4. Saat token dibuka:
   - Jika user sudah login, arahkan ke halaman join room.
   - Jika email sudah terdaftar tapi belum login, arahkan ke login lalu redirect kembali.
   - Jika email belum terdaftar, arahkan ke register lalu redirect kembali.
5. Setelah berhasil join, status invitation menjadi `accepted`.

## 15. Waiting Room

Sebelum game dimulai, peserta masuk ke waiting room.

Elemen halaman:

- Nama room
- Kode room
- Daftar peserta
- Status peserta
- Tombol invite friend untuk host
- Tombol start game untuk host
- Opsi anonymous sebelum game dimulai

Hak khusus host:

- Start game
- Ubah mode kartu selama status masih `waiting`
- Invite peserta
- Mengeluarkan peserta

## 16. Gameplay Room

Saat game dimulai:

- Status room berubah ke `playing`
- Kartu pertama tampil
- Peserta melihat pertanyaan aktif
- Chat group aktif
- Jawaban peserta disimpan per kartu
- Flow kartu berjalan manual atau automatic

Komponen tampilan:

- Header room
- Area kartu aktif
- Indikator urutan kartu
- Countdown untuk mode automatic
- Area chat group
- Daftar peserta
- Tombol kontrol host

## 17. Chat Group

### Struktur Tabel `game_room_messages`

| Field | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| game_room_id | foreignId | relasi ke room |
| game_room_participant_id | foreignId | relasi ke participant |
| game_card_id | foreignId | nullable |
| message | text | isi chat |
| message_type | enum | `chat`, `system` |
| timestamps | timestamps | default Laravel |

### Aturan Chat

- Hanya peserta aktif yang boleh mengirim chat.
- Pesan harus terkait dengan kartu aktif.
- Room berstatus `finished` tidak menerima chat baru.
- Frontend menggunakan jQuery AJAX tanpa reload.
- Polling digunakan untuk mengambil status room dan pesan terbaru.

## 18. Perpindahan Kartu

Endpoint yang dibutuhkan:

- Start game
- Next card
- Previous card
- End game
- Get current card status
- Get participants
- Get messages
- Send message

### Manual Flow

- Host klik next atau previous.
- Sistem mencari kartu berdasarkan `order_number`.
- Sistem update `current_game_card_id`, `current_card_order`, dan `current_card_started_at`.

### Automatic Flow

- Frontend melakukan polling status room.
- Jika waktu habis, sistem trigger auto next dengan validasi anti double submit.
- Gunakan `current_card_started_at` untuk hitung sisa waktu.
- Jika kartu terakhir selesai, room dapat selesai otomatis atau melalui host.

## 19. Laporan Game

Admin dapat melihat:

- Nama room
- Kode room
- Host
- Card set
- Jumlah peserta
- Status
- Waktu mulai
- Waktu selesai

Detail laporan:

- Daftar peserta
- Daftar kartu yang dimainkan
- Chat atau jawaban per kartu
- Nama anonymous tetap anonim di laporan umum
- Admin tetap bisa melihat mapping user asli jika dibutuhkan

## 20. Dashboard Admin

Card statistik:

- Total user
- Total artikel
- Total video
- Total card set
- Total room
- Room aktif
- Game selesai

Menu admin:

- Dashboard
- Users
- Articles
- Videos
- Game Card Sets
- Game Cards
- Game Rooms
- Game Reports
- Settings
- Logout

DataTables dipakai untuk:

- Users
- Articles
- Videos
- Game Rooms
- Game Reports

## 21. Validasi dan Keamanan

Wajib diterapkan:

- CSRF protection
- Validasi request
- Authorization per action
- Hanya host yang boleh kontrol game
- User non-peserta tidak boleh masuk room
- Guest mengakses room berdasarkan session
- Upload hanya image
- Batasi ukuran upload
- Slug artikel dan video harus unique
- Room code harus unique
- Invitation token harus unique dan memiliki masa aktif
- Identitas asli anonymous tidak boleh tampil di frontend

## 22. Model dan Relationship

Model utama:

- `User`
- `Article`
- `Video`
- `GameCardSet`
- `GameCard`
- `GameRoom`
- `GameRoomParticipant`
- `GameRoomInvitation`
- `GameRoomMessage`

Relationship inti:

- `User` hasMany `Article`, `Video`, `GameRoom`, `GameRoomParticipant`, `GameRoomInvitation`
- `GameCardSet` hasMany `GameCard`, `GameRoom`
- `GameRoom` belongsTo `User`, `GameCardSet`, `GameCard`; hasMany `GameRoomParticipant`, `GameRoomInvitation`, `GameRoomMessage`
- `GameRoomParticipant` belongsTo `GameRoom`, nullable belongsTo `User`, hasMany `GameRoomMessage`
- `GameRoomMessage` belongsTo `GameRoom`, `GameRoomParticipant`, nullable `GameCard`

## 23. Seeder Awal

Seeder minimal:

- Admin default
- User default
- Artikel dummy
- Video dummy
- Card set dummy
- Kartu pertanyaan dummy

Akun default:

- Admin: `admin@example.com` / `password`
- User: `user@example.com` / `password`

## 24. Email Invitation

Isi email:

- Salam pembuka
- Informasi undangan ke room game
- Nama room
- Kode room
- Tombol join room
- Informasi bahwa user akan diarahkan ke register jika belum punya akun

## 25. Route yang Dibutuhkan

### Admin

- `GET /admin/dashboard`
- `RESOURCE /admin/users`
- `RESOURCE /admin/articles`
- `RESOURCE /admin/videos`
- `RESOURCE /admin/game-card-sets`
- `RESOURCE /admin/game-card-sets/{set}/cards`
- `GET /admin/game-rooms`
- `GET /admin/game-rooms/{room}`
- `GET /admin/game-reports`
- `GET /admin/game-reports/{room}`

### User

- `GET /user/dashboard`
- `GET /user/profile`
- `POST /user/profile`
- `GET /user/articles`
- `GET /user/articles/{slug}`
- `GET /user/videos`
- `GET /user/videos/{slug}`
- `GET /user/game`
- `GET /user/game/create-room`
- `POST /user/game/create-room`
- `GET /user/game/join`
- `POST /user/game/join`
- `GET /user/game/room/{code}`

### Game Actions

- `POST /game/room/{room}/invite`
- `POST /game/room/{room}/start`
- `POST /game/room/{room}/next-card`
- `POST /game/room/{room}/previous-card`
- `POST /game/room/{room}/end`
- `GET /game/room/{room}/status`
- `GET /game/room/{room}/participants`
- `GET /game/room/{room}/messages`
- `POST /game/room/{room}/messages`
- `POST /game/room/{room}/anonymous-toggle`

### Public

- `GET /game/join`
- `POST /game/join`
- `GET /game/invitation/{token}`

## 26. Business Rules Penting

1. Anonymous hanya berlaku dalam konteks room.
2. User bisa anonymous di satu room dan tetap tampil asli di room lain.
3. Mapping user asli untuk registered anonymous tetap disimpan.
4. Host juga bisa tampil anonymous tanpa kehilangan hak kontrol.
5. Peserta hanya boleh chat jika sudah join room.
6. Chat harus terhubung ke kartu aktif.
7. Room `finished` tidak menerima chat baru.
8. Invitation expired tidak bisa dipakai.
9. Setelah register dari invitation, user harus diarahkan kembali ke room terkait.
10. Sistem harus mencegah duplicate participant dalam room yang sama.
