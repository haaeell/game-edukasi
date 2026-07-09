<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesertaController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $peserta = User::where('role', 'user')
            ->withCount(['hostedRooms', 'roomParticipants'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.peserta.index', [
            'peserta' => $peserta,
            'filters' => [
                'status' => $status,
                'search' => $search,
            ],
            'summary' => [
                'total' => User::where('role', 'user')->count(),
                'active' => User::where('role', 'user')->where('status', 'active')->count(),
                'inactive' => User::where('role', 'user')->where('status', 'inactive')->count(),
                'newThisMonth' => User::where('role', 'user')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
        ]);
    }

    public function show(User $peserta): View
    {
        abort_unless($peserta->role === 'user', 404);

        $peserta->load([
            'hostedRooms' => fn ($query) => $query->with('cardSet')->latest()->limit(5),
            'roomParticipants' => fn ($query) => $query->with('room')->latest()->limit(5),
        ]);

        return view('admin.peserta.show', [
            'peserta' => $peserta,
            'stats' => [
                'hostedRooms' => $peserta->hostedRooms()->count(),
                'roomParticipants' => $peserta->roomParticipants()->count(),
            ],
        ]);
    }

    public function toggleStatus(User $peserta): RedirectResponse
    {
        abort_unless($peserta->role === 'user', 404);

        $peserta->update([
            'status' => $peserta->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', $peserta->status === 'active'
            ? 'Akun peserta berhasil diaktifkan.'
            : 'Akun peserta berhasil dinonaktifkan.');
    }
}
