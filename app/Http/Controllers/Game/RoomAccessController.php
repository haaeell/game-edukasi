<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\JoinRoomRequest;
use App\Http\Requests\Game\ToggleAnonymousRequest;
use App\Models\GameRoom;
use App\Models\GameRoomInvitation;
use App\Services\GameRoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomAccessController extends Controller
{
    public function __construct(private readonly GameRoomService $gameRoomService)
    {
    }

    public function showJoinForm(): View
    {
        return view('game.join', [
            'invitation' => session('active_invitation'),
        ]);
    }

    public function join(JoinRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $room = GameRoom::with('cardSet')->where('code', $data['code'])->firstOrFail();
        $invitation = null;

        if (! empty($data['invitation_token'])) {
            $invitation = GameRoomInvitation::where('token', $data['invitation_token'])
                ->where('game_room_id', $room->id)
                ->where('status', 'pending')
                ->where('expired_at', '>', now())
                ->first();

            if (! $invitation) {
                return back()->withErrors(['code' => 'Invitation token tidak valid atau sudah expired.'])->withInput();
            }
        }

        if (! in_array($room->status, ['waiting', 'playing'], true)) {
            return back()->withErrors(['code' => 'Room sudah selesai dan tidak bisa diikuti.'])->withInput();
        }

        if ($request->user()) {
            $this->gameRoomService->joinRegisteredUser(
                room: $room,
                user: $request->user(),
                anonymous: (bool) ($data['is_anonymous'] ?? false),
                anonymousName: $data['anonymous_name'] ?? null,
            );
        } else {
            if (! $room->allow_guest) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Room ini tidak mengizinkan guest. Silakan login terlebih dahulu.',
                ]);
            }

            if (empty($data['guest_name'])) {
                return back()->withErrors(['guest_name' => 'Nama guest wajib diisi.'])->withInput();
            }

            $this->gameRoomService->joinGuest(
                room: $room,
                request: $request,
                guestName: $data['guest_name'],
                anonymous: (bool) ($data['is_anonymous'] ?? false),
                anonymousName: $data['anonymous_name'] ?? null,
                email: $data['email'] ?? null,
            );
        }

        if ($invitation) {
            $this->gameRoomService->markInvitationAccepted($invitation);
            $request->session()->forget('active_invitation');
        }

        return redirect()->route('game.rooms.show', $room->code)
            ->with('success', 'Berhasil bergabung ke room.');
    }

    public function handleInvitation(Request $request, string $token): RedirectResponse
    {
        $invitation = GameRoomInvitation::with('room')->where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending' || $invitation->expired_at->isPast()) {
            if ($invitation->status === 'pending' && $invitation->expired_at->isPast()) {
                $invitation->update(['status' => 'expired']);
            }

            return redirect()->route('game.join')->withErrors([
                'code' => 'Invitation sudah tidak valid atau telah expired.',
            ]);
        }

        $request->session()->put('active_invitation', [
            'token' => $invitation->token,
            'room_code' => $invitation->room->code,
            'email' => $invitation->email,
            'room_title' => $invitation->room->title,
        ]);

        if ($request->user()) {
            return redirect()->route('game.join')->withInput([
                'code' => $invitation->room->code,
                'invitation_token' => $invitation->token,
            ]);
        }

        $request->session()->put('post_auth_redirect', route('game.join'));

        return redirect()->route('login')->with('success', 'Silakan login atau register untuk menerima invitation room.');
    }

    public function showRoom(Request $request, string $code): View|RedirectResponse
    {
        $room = GameRoom::with([
            'host',
            'cardSet.cards',
            'currentCard',
            'currentTargetParticipant',
            'participants' => fn ($query) => $query->orderByDesc('is_host')->orderBy('display_name'),
        ])->where('code', strtoupper($code))->firstOrFail();

        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        if (! $participant) {
            return redirect()->route('game.join')->withErrors([
                'code' => 'Anda belum tergabung pada room tersebut.',
            ]);
        }

        return view('game.room', [
            'room' => $room,
            'participant' => $participant,
        ]);
    }

    public function start(Request $request, string $code): RedirectResponse|JsonResponse
    {
        $room = GameRoom::with(['cardSet', 'participants'])->where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant?->is_host, 403);

        $room = $this->gameRoomService->startRoom($room);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Game dimulai.',
                'status' => $this->gameRoomService->buildStatusPayload($room, $participant),
            ]);
        }

        return back()->with('success', 'Game dimulai.');
    }

    public function toggleAnonymous(ToggleAnonymousRequest $request, string $code): RedirectResponse
    {
        $room = GameRoom::where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant, 403);

        $isAnonymous = (bool) $request->validated()['is_anonymous'];
        $anonymousName = $request->validated()['anonymous_name'] ?? null;

        if ($participant->participant_type === 'registered' && $participant->user) {
            $participant->update([
                'is_anonymous' => $isAnonymous,
                'anonymous_name' => $isAnonymous ? ($anonymousName ?: $participant->anonymous_name ?: 'Anonymous') : null,
                'display_name' => $isAnonymous
                    ? ($anonymousName ?: $participant->anonymous_name ?: 'Anonymous')
                    : $participant->user->name,
            ]);
        } else {
            $participant->update([
                'is_anonymous' => $isAnonymous,
                'anonymous_name' => $isAnonymous ? ($anonymousName ?: $participant->anonymous_name ?: 'Anonymous') : null,
                'display_name' => $isAnonymous
                    ? ($anonymousName ?: $participant->anonymous_name ?: 'Anonymous')
                    : ($participant->guest_name ?: $participant->display_name),
            ]);
        }

        return back()->with('success', 'Mode anonymous diperbarui.');
    }
}
