<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreGameRoomRequest;
use App\Models\GameCardSet;
use App\Models\GameRoom;
use App\Services\GameRoomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameRoomController extends Controller
{
    public function __construct(private readonly GameRoomService $gameRoomService)
    {
    }

    public function index(): View
    {
        return view('user.game.index');
    }

    public function create(): View
    {
        return view('user.game.create-room', [
            'cardSets' => GameCardSet::where('status', 'active')->orderBy('title')->get(),
        ]);
    }

    public function store(StoreGameRoomRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $room = GameRoom::create([
            'code' => $this->gameRoomService->generateUniqueCode(),
            'host_user_id' => $request->user()->id,
            'game_card_set_id' => $data['game_card_set_id'],
            'title' => $data['title'],
            'card_flow_type' => 'manual',
            'auto_next_seconds' => null,
            'allow_guest' => (bool) ($data['allow_guest'] ?? false),
            'host_is_player' => (bool) ($data['host_is_player'] ?? true),
            'status' => 'waiting',
        ]);

        $this->gameRoomService->joinRegisteredUser(
            room: $room,
            user: $request->user(),
            anonymous: (bool) ($data['is_anonymous'] ?? false),
            anonymousName: $data['anonymous_name'] ?? null,
            isHost: true,
        );

        return redirect()->route('game.rooms.show', $room->code)
            ->with('success', 'Room berhasil dibuat.');
    }
}
