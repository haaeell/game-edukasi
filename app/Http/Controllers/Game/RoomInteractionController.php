<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Http\Requests\Game\InviteRoomRequest;
use App\Http\Requests\Game\SendMessageRequest;
use App\Mail\GameRoomInvitationMail;
use App\Models\GameRoom;
use App\Models\GameRoomInvitation;
use App\Services\GameRoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RoomInteractionController extends Controller
{
    public function __construct(private readonly GameRoomService $gameRoomService)
    {
    }

    public function invite(InviteRoomRequest $request, string $code): RedirectResponse
    {
        $room = GameRoom::with('host')->where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant?->is_host, 403);

        $invitation = GameRoomInvitation::create([
            'game_room_id' => $room->id,
            'email' => $request->validated()['email'],
            'token' => Str::random(64),
            'invited_by' => $request->user()->id,
            'status' => 'pending',
            'expired_at' => now()->addDays(3),
        ]);

        Mail::to($invitation->email)->send(new GameRoomInvitationMail($invitation->load(['room', 'inviter'])));

        return back()->with('success', 'Invitation berhasil dikirim.');
    }

    public function nextCard(Request $request, string $code): RedirectResponse
    {
        $room = $this->loadRoomForControl($code, $request);
        $this->gameRoomService->moveToNextCard($room);

        return back()->with('success', 'Berpindah ke kartu berikutnya.');
    }

    public function shuffleCard(Request $request, string $code): JsonResponse
    {
        $room = $this->loadRoomForControl($code, $request);
        $room = $this->gameRoomService->shuffleToRandomCard($room);
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        return response()->json([
            'message' => 'Kartu berhasil diacak.',
            'status' => $this->gameRoomService->buildStatusPayload($room, $participant),
        ]);
    }

    public function resetDeck(Request $request, string $code): JsonResponse
    {
        $room = $this->loadRoomForControl($code, $request);
        $room = $this->gameRoomService->resetRoomDeck($room);
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        return response()->json([
            'message' => 'Deck kartu berhasil direset.',
            'status' => $this->gameRoomService->buildStatusPayload($room, $participant),
        ]);
    }

    public function previousCard(Request $request, string $code): RedirectResponse
    {
        $room = $this->loadRoomForControl($code, $request);
        $this->gameRoomService->moveToPreviousCard($room);

        return back()->with('success', 'Kembali ke kartu sebelumnya.');
    }

    public function end(Request $request, string $code): RedirectResponse|JsonResponse
    {
        $room = $this->loadRoomForControl($code, $request);
        $room = $this->gameRoomService->endRoom($room);
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'message' => 'Game diakhiri.',
                'status' => $this->gameRoomService->buildStatusPayload($room, $participant),
                'redirect_url' => route('home'),
            ]);
        }

        return back()->with('success', 'Game diakhiri.');
    }

    public function status(Request $request, string $code): JsonResponse
    {
        $room = GameRoom::with(['currentCard', 'cardSet.cards', 'currentTargetParticipant'])->where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant, 403);

        if ($room->status === 'playing' && $room->card_flow_type === 'automatic') {
            $room = $this->gameRoomService->advanceAutomaticRoomIfNeeded($room);
        }

        return response()->json($this->gameRoomService->buildStatusPayload($room->fresh(['currentCard', 'cardSet.cards', 'currentTargetParticipant']), $participant));
    }

    public function participants(Request $request, string $code): JsonResponse
    {
        $room = GameRoom::with(['participants' => fn ($query) => $query->where('status', 'active')->orderByDesc('is_host')->orderBy('display_name')])
            ->where('code', strtoupper($code))
            ->firstOrFail();

        $participant = $this->gameRoomService->resolveParticipant($room, $request);
        abort_unless($participant, 403);

        return response()->json([
            'participants' => $room->participants->map(fn ($item) => [
                'name' => $item->public_name,
                'type' => $item->participant_type,
                'is_host' => $item->is_host,
                'status' => $item->status,
            ])->values(),
        ]);
    }

    public function messages(Request $request, string $code): JsonResponse
    {
        $room = GameRoom::where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);
        abort_unless($participant, 403);

        $messages = $room->messages()
            ->with('participant')
            ->latest('id')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return response()->json([
            'messages' => $messages->map(fn ($message) => [
                'id' => $message->id,
                'name' => $message->participant?->public_name ?? 'System',
                'message' => $message->message,
                'type' => $message->message_type,
                'created_at' => $message->created_at?->format('H:i'),
                'is_mine' => $message->game_room_participant_id === $participant->id,
            ]),
        ]);
    }

    public function sendMessage(SendMessageRequest $request, string $code): JsonResponse
    {
        $room = GameRoom::with('currentCard')->where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant, 403);

        if ($room->status === 'finished') {
            return response()->json(['message' => 'Room sudah selesai.'], 422);
        }

        if (! in_array($room->status, ['waiting', 'playing'], true)) {
            return response()->json(['message' => 'Room belum siap menerima chat.'], 422);
        }

        $message = $room->messages()->create([
            'game_room_participant_id' => $participant->id,
            'game_card_id' => $room->currentCard?->id,
            'message' => $request->validated()['message'],
            'message_type' => 'chat',
        ])->load('participant');

        return response()->json([
            'message' => [
                'id' => $message->id,
                'name' => $message->participant?->public_name ?? 'System',
                'message' => $message->message,
                'type' => $message->message_type,
                'created_at' => $message->created_at?->format('H:i'),
                'is_mine' => true,
            ],
        ]);
    }

    private function loadRoomForControl(string $code, Request $request): GameRoom
    {
        $room = GameRoom::with(['cardSet.cards', 'participants', 'currentCard', 'currentTargetParticipant'])->where('code', strtoupper($code))->firstOrFail();
        $participant = $this->gameRoomService->resolveParticipant($room, $request);

        abort_unless($participant?->is_host, 403);

        return $room;
    }
}
