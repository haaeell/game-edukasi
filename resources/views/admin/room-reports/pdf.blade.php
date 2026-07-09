<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Room - {{ $room->title }}</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Helvetica", "DejaVu Sans", sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header .brand {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #2563eb;
        }

        .header h1 {
            margin: 6px 0 2px;
            font-size: 20px;
            color: #0f172a;
        }

        .header .meta {
            font-size: 10px;
            color: #64748b;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
            color: #ffffff;
            background-color: #0891b2;
        }

        .status-finished { background-color: #059669; }
        .status-waiting { background-color: #d97706; }

        h2.section-title {
            font-size: 13px;
            color: #0f172a;
            margin: 20px 0 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.info-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.info-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10.5px;
            vertical-align: top;
        }

        table.info-table td.label {
            width: 28%;
            background-color: #f8fafc;
            font-weight: bold;
            color: #475569;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4px;
        }

        table.data-table th {
            background-color: #eff6ff;
            color: #1d4ed8;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #dbeafe;
        }

        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: top;
        }

        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .host-tag {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 6px;
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 8.5px;
            font-weight: bold;
        }

        .system-row td {
            background-color: #fffbeb !important;
            color: #92400e;
            text-align: center;
            font-style: italic;
        }

        .empty-note {
            font-size: 10px;
            color: #94a3b8;
            font-style: italic;
            padding: 8px 0;
        }

        .footer-note {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">SoluShare &bull; Laporan Room</div>
        <h1>{{ $room->title }}</h1>
        <div class="meta">
            Kode Room: <strong>{{ $room->code }}</strong> &bull;
            Status:
            <span class="status-badge {{ $room->status === 'finished' ? 'status-finished' : ($room->status === 'waiting' ? 'status-waiting' : '') }}">
                {{ $room->status === 'playing' ? 'BERLANGSUNG' : ($room->status === 'finished' ? 'SELESAI' : 'MENUNGGU') }}
            </span>
        </div>
    </div>

    <h2 class="section-title">Informasi Room</h2>
    <table class="info-table">
        <tr>
            <td class="label">Host</td>
            <td>{{ $room->host->name ?? '-' }}</td>
            <td class="label">Card Set</td>
            <td>{{ $room->cardSet->title ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dibuat</td>
            <td>{{ $room->created_at->format('d M Y H:i') }}</td>
            <td class="label">Mulai Sesi</td>
            <td>{{ $room->started_at?->format('d M Y H:i') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Selesai Sesi</td>
            <td>{{ $room->ended_at?->format('d M Y H:i') ?? '-' }}</td>
            <td class="label">Durasi</td>
            <td>{{ $durationSeconds !== null ? gmdate('H:i:s', $durationSeconds) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Akses Guest</td>
            <td>{{ $room->allow_guest ? 'Diizinkan' : 'Tidak diizinkan' }}</td>
            <td class="label">Host Ikut Main</td>
            <td>{{ $room->host_is_player ? 'Ya' : 'Tidak, hanya memandu' }}</td>
        </tr>
    </table>

    <h2 class="section-title">Peserta ({{ $room->participants->count() }})</h2>
    @if ($room->participants->isEmpty())
        <div class="empty-note">Belum ada peserta yang bergabung.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 30%;">Nama</th>
                    <th style="width: 16%;">Tipe</th>
                    <th style="width: 16%;">Peran</th>
                    <th style="width: 17%;">Bergabung</th>
                    <th style="width: 17%;">Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($room->participants as $index => $participant)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $participant->public_name }}</td>
                        <td>{{ $participant->participant_type === 'registered' ? 'Terdaftar' : 'Tamu' }}</td>
                        <td>@if ($participant->is_host)<span class="host-tag">HOST</span>@else Peserta @endif</td>
                        <td>{{ $participant->joined_at?->format('d M H:i') ?? '-' }}</td>
                        <td>{{ $participant->left_at?->format('d M H:i') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="section-title">Kartu yang Dimainkan ({{ $openedCards->count() }}/{{ $totalActiveCards }})</h2>
    @if ($openedCards->isEmpty())
        <div class="empty-note">Belum ada kartu yang dibuka pada sesi ini.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 8%;">Urutan</th>
                    <th style="width: 25%;">Judul Kartu</th>
                    <th style="width: 67%;">Pertanyaan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($openedCards as $card)
                    <tr>
                        <td>{{ $card['order'] }}</td>
                        <td>{{ $card['title'] }}</td>
                        <td>{{ $card['question'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="section-title">Riwayat Chat ({{ $chatMessageCount }} pesan)</h2>
    @if ($messages->isEmpty())
        <div class="empty-note">Belum ada pesan pada sesi ini.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 14%;">Waktu</th>
                    <th style="width: 20%;">Pengirim</th>
                    <th style="width: 66%;">Pesan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                    @if ($message->message_type === 'system')
                        <tr class="system-row">
                            <td colspan="3">{{ $message->created_at->format('d M H:i') }} &bull; {{ $message->message }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ $message->created_at->format('d M H:i') }}</td>
                            <td>{{ $message->participant->public_name ?? 'Sistem' }}</td>
                            <td>{{ $message->message }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif

    <h2 class="section-title">Kritik & Saran ({{ $feedbacks->count() }})</h2>
    @if ($feedbacks->isEmpty())
        <div class="empty-note">Belum ada kritik atau saran dari peserta pada sesi ini.</div>
    @else
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 14%;">Waktu</th>
                    <th style="width: 20%;">Peserta</th>
                    <th style="width: 66%;">Masukan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr>
                        <td>{{ $feedback->created_at->format('d M H:i') }}</td>
                        <td>{{ $feedback->participant_name }}</td>
                        <td>{{ $feedback->message }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer-note">
        Laporan ini dibuat otomatis oleh sistem SoluShare pada {{ $generatedAt->format('d M Y H:i:s') }}.
    </div>
</body>
</html>
