<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Room Game</title>
</head>
<body style="margin:0;padding:24px;background:#f8fafc;font-family:Poppins,Arial,sans-serif;color:#0f172a;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e2e8f0;border-radius:24px;overflow:hidden;">
        <div style="padding:32px;background:linear-gradient(135deg,#082f49 0%,#0ea5e9 100%);color:#ffffff;">
            <div style="font-size:12px;font-weight:700;letter-spacing:0.3em;text-transform:uppercase;color:#bae6fd;">Game Edukasi</div>
            <h1 style="margin:16px 0 0;font-size:30px;line-height:1.2;">Kamu diundang ke room game</h1>
        </div>

        <div style="padding:32px;">
            <p style="margin:0 0 16px;">Halo,</p>
            <p style="margin:0 0 16px;line-height:1.8;">
                Kamu diundang untuk bergabung ke room game interaktif.
            </p>

            <div style="margin:24px 0;padding:20px;border-radius:20px;background:#f8fafc;border:1px solid #e2e8f0;">
                <div style="font-size:14px;color:#475569;">Nama Room</div>
                <div style="margin-top:6px;font-size:24px;font-weight:700;">{{ $invitation->room->title }}</div>
                <div style="margin-top:18px;font-size:14px;color:#475569;">Kode Room</div>
                <div style="margin-top:6px;font-size:20px;font-weight:700;letter-spacing:0.15em;">{{ $invitation->room->code }}</div>
            </div>

            <p style="margin:0 0 24px;line-height:1.8;">
                Klik tombol di bawah untuk join room. Jika belum punya akun, sistem akan mengarahkanmu ke halaman register terlebih dahulu.
            </p>

            <a href="{{ route('game.invitation', $invitation->token) }}" style="display:inline-block;padding:14px 22px;background:#0284c7;color:#ffffff;text-decoration:none;border-radius:16px;font-weight:700;">
                Join Room
            </a>

            <p style="margin:24px 0 0;font-size:13px;color:#64748b;line-height:1.8;">
                Link invitation ini berlaku sampai {{ $invitation->expired_at->format('d M Y H:i') }}.
            </p>
        </div>
    </div>
</body>
</html>
