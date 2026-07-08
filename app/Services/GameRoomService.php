<?php

namespace App\Services;

use App\Models\GameCard;
use App\Models\GameRoom;
use App\Models\GameRoomInvitation;
use App\Models\GameRoomMessage;
use App\Models\GameRoomParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class GameRoomService
{
    public function generateUniqueCode(): string
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (GameRoom::where('code', $code)->exists());

        return $code;
    }

    public function getGuestSessionKey(GameRoom $room): string
    {
        return 'guest_room_participant_'.$room->id;
    }

    public function joinRegisteredUser(
        GameRoom $room,
        User $user,
        bool $anonymous = false,
        ?string $anonymousName = null,
        bool $isHost = false,
    ): GameRoomParticipant {
        $participant = GameRoomParticipant::firstOrNew([
            'game_room_id' => $room->id,
            'user_id' => $user->id,
        ]);

        $participant->fill([
            'guest_name' => null,
            'email' => $user->email,
            'participant_type' => 'registered',
            'is_anonymous' => $anonymous,
            'anonymous_name' => $anonymous ? $this->resolveAnonymousName($room, $anonymousName) : null,
            'display_name' => $anonymous
                ? $this->resolveAnonymousName($room, $anonymousName)
                : $user->name,
            'is_host' => $isHost || $participant->is_host,
            'status' => 'active',
            'joined_at' => $participant->joined_at ?? now(),
            'left_at' => null,
        ]);

        $participant->save();

        return $participant->fresh();
    }

    public function joinGuest(
        GameRoom $room,
        Request $request,
        string $guestName,
        bool $anonymous = false,
        ?string $anonymousName = null,
        ?string $email = null,
    ): GameRoomParticipant {
        $displayName = $anonymous
            ? $this->resolveAnonymousName($room, $anonymousName)
            : $guestName;

        $participant = GameRoomParticipant::create([
            'game_room_id' => $room->id,
            'user_id' => null,
            'guest_name' => $guestName,
            'email' => $email,
            'display_name' => $displayName,
            'participant_type' => 'guest',
            'is_anonymous' => $anonymous,
            'anonymous_name' => $anonymous ? $displayName : null,
            'is_host' => false,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $request->session()->put($this->getGuestSessionKey($room), $participant->id);

        return $participant;
    }

    public function resolveParticipant(GameRoom $room, Request $request): ?GameRoomParticipant
    {
        if ($request->user()) {
            return $room->participants()
                ->where('user_id', $request->user()->id)
                ->where('status', 'active')
                ->first();
        }

        $participantId = $request->session()->get($this->getGuestSessionKey($room));

        if (! $participantId) {
            return null;
        }

        return $room->participants()
            ->whereKey($participantId)
            ->where('status', 'active')
            ->first();
    }

    public function startRoom(GameRoom $room): GameRoom
    {
        $firstCard = $room->cardSet->cards()
            ->where('status', 'active')
            ->orderBy('order_number')
            ->first();
        $targetParticipant = $firstCard ? $this->pickRandomTargetParticipant($room) : null;

        $room->update([
            'status' => 'playing',
            'current_game_card_id' => $firstCard?->id,
            'current_target_participant_id' => $targetParticipant?->id,
            'current_card_order' => $firstCard?->order_number,
            'opened_card_ids' => $firstCard ? [$firstCard->id] : [],
            'current_card_started_at' => $firstCard ? Carbon::now() : null,
            'started_at' => $room->started_at ?? Carbon::now(),
            'ended_at' => null,
        ]);

        if ($firstCard) {
            $this->createSystemMessage($room, 'Game dimulai. Kartu pertama telah dibuka.');
        }

        return $room->fresh(['currentCard', 'cardSet', 'currentTargetParticipant']);
    }

    public function moveToNextCard(GameRoom $room): GameRoom
    {
        if ($room->status !== 'playing') {
            return $room;
        }

        $nextCard = $room->cardSet->cards()
            ->where('status', 'active')
            ->where('order_number', '>', (int) $room->current_card_order)
            ->orderBy('order_number')
            ->first();

        if (! $nextCard) {
            return $this->endRoom($room);
        }

        $room->update([
            'current_game_card_id' => $nextCard->id,
            'current_card_order' => $nextCard->order_number,
            'current_card_started_at' => now(),
        ]);

        $this->createSystemMessage($room, 'Berpindah ke kartu '.$nextCard->order_number.'.');

        return $room->fresh(['currentCard', 'cardSet.cards']);
    }

    public function shuffleToRandomCard(GameRoom $room): GameRoom
    {
        if ($room->status !== 'playing') {
            return $room;
        }

        $cards = $room->cardSet->cards()
            ->where('status', 'active')
            ->orderBy('order_number')
            ->get(['id', 'order_number']);

        if ($cards->isEmpty()) {
            return $room;
        }

        $openedCardIds = collect($room->opened_card_ids ?? [])->map(fn ($id) => (int) $id)->filter()->values();
        $pool = $cards->reject(fn ($card) => $openedCardIds->contains((int) $card->id))->values();

        if ($pool->isEmpty()) {
            return $room->fresh(['currentCard', 'cardSet.cards', 'currentTargetParticipant']);
        }

        $nextCard = $pool->random();

        $targetParticipant = $this->pickRandomTargetParticipant($room, $room->current_target_participant_id);
        $openedCardIds = $openedCardIds->push((int) $nextCard->id)->unique()->values();

        $room->update([
            'current_game_card_id' => $nextCard->id,
            'current_target_participant_id' => $targetParticipant?->id,
            'current_card_order' => $nextCard->order_number,
            'opened_card_ids' => $openedCardIds->all(),
            'current_card_started_at' => now(),
        ]);

        $targetName = $targetParticipant?->public_name ?: 'peserta terpilih';
        $this->createSystemMessage($room, 'Kartu diacak. Kartu '.$nextCard->order_number.' telah dibuka untuk '.$targetName.'.');

        return $room->fresh(['currentCard', 'cardSet.cards', 'currentTargetParticipant']);
    }

    public function resetRoomDeck(GameRoom $room): GameRoom
    {
        $room->update([
            'current_game_card_id' => null,
            'current_target_participant_id' => null,
            'current_card_order' => null,
            'opened_card_ids' => [],
            'current_card_started_at' => null,
        ]);

        $this->createSystemMessage($room, 'Deck kartu direset. Host dapat mengacak kartu lagi dari awal.');

        return $room->fresh(['currentCard', 'cardSet.cards', 'currentTargetParticipant']);
    }

    public function moveToPreviousCard(GameRoom $room): GameRoom
    {
        if ($room->status !== 'playing') {
            return $room;
        }

        $previousCard = $room->cardSet->cards()
            ->where('status', 'active')
            ->where('order_number', '<', (int) $room->current_card_order)
            ->orderByDesc('order_number')
            ->first();

        if (! $previousCard) {
            return $room;
        }

        $room->update([
            'current_game_card_id' => $previousCard->id,
            'current_card_order' => $previousCard->order_number,
            'current_card_started_at' => now(),
        ]);

        $this->createSystemMessage($room, 'Kembali ke kartu '.$previousCard->order_number.'.');

        return $room->fresh(['currentCard', 'cardSet.cards']);
    }

    public function endRoom(GameRoom $room): GameRoom
    {
        $room->update([
            'status' => 'finished',
            'current_target_participant_id' => null,
            'ended_at' => now(),
        ]);

        $room->participants()
            ->where('status', 'active')
            ->update([
                'status' => 'left',
                'left_at' => now(),
            ]);

        $this->createSystemMessage($room, 'Game telah selesai.');

        return $room->fresh(['currentCard', 'cardSet.cards', 'currentTargetParticipant']);
    }

    public function advanceAutomaticRoomIfNeeded(GameRoom $room): GameRoom
    {
        if ($room->status !== 'playing' || $room->card_flow_type !== 'automatic' || ! $room->current_card_started_at) {
            return $room;
        }

        $room = $room->fresh(['currentCard', 'cardSet.cards']);
        $seconds = $room->currentCard?->duration_seconds ?: $room->auto_next_seconds;

        if (! $seconds) {
            return $room;
        }

        $elapsed = $room->current_card_started_at->diffInSeconds(now());

        if ($elapsed >= $seconds) {
            return $this->moveToNextCard($room);
        }

        return $room;
    }

    public function buildStatusPayload(GameRoom $room, ?GameRoomParticipant $viewer = null): array
    {
        $remainingSeconds = null;
        $totalActiveCards = $room->cardSet->cards()->where('status', 'active')->count();
        $openedCardIds = collect($room->opened_card_ids ?? [])->map(fn ($id) => (int) $id)->filter()->values();
        $cardsRemaining = max($totalActiveCards - $openedCardIds->count(), 0);

        if ($room->status === 'playing' && $room->card_flow_type === 'automatic' && $room->current_card_started_at) {
            $seconds = $room->currentCard?->duration_seconds ?: $room->auto_next_seconds;
            $elapsed = $room->current_card_started_at->diffInSeconds(now());
            $remainingSeconds = max(0, (int) $seconds - $elapsed);
        }

        return [
            'status' => $room->status,
            'title' => $room->title,
            'code' => $room->code,
            'card_flow_type' => $room->card_flow_type,
            'current_card_order' => $room->current_card_order,
            'remaining_seconds' => $remainingSeconds,
            'host_is_player' => $room->host_is_player,
            'opened_card_count' => $openedCardIds->count(),
            'cards_remaining' => $cardsRemaining,
            'cards_exhausted' => $cardsRemaining === 0 && $totalActiveCards > 0,
            'current_card' => $room->currentCard ? [
                'id' => $room->currentCard->id,
                'title' => $room->currentCard->title,
                'question' => $room->currentCard->question,
            ] : null,
            'target_participant' => $room->currentTargetParticipant ? [
                'id' => $room->currentTargetParticipant->id,
                'name' => $room->currentTargetParticipant->public_name,
                'is_me' => $viewer ? $room->currentTargetParticipant->id === $viewer->id : false,
            ] : null,
        ];
    }

    public function markInvitationAccepted(GameRoomInvitation $invitation): void
    {
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    public function createSystemMessage(GameRoom $room, string $message): GameRoomMessage
    {
        $systemParticipant = $room->participants()
            ->where('is_host', true)
            ->orderBy('id')
            ->first();

        return $room->messages()->create([
            'game_room_participant_id' => $systemParticipant?->id,
            'game_card_id' => $room->current_game_card_id,
            'message' => $message,
            'message_type' => 'system',
        ]);
    }

    private function resolveAnonymousName(GameRoom $room, ?string $preferredName = null): string
    {
        if ($preferredName) {
            return $preferredName;
        }

        $count = $room->participants()
            ->where('is_anonymous', true)
            ->count() + 1;

        return 'Anonymous '.$count;
    }

    private function pickRandomTargetParticipant(GameRoom $room, ?int $excludeParticipantId = null): ?GameRoomParticipant
    {
        $participants = $room->participants()
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        if ($participants->isEmpty()) {
            return null;
        }

        $preferred = $room->host_is_player
            ? $participants->values()
            : $participants->where('is_host', false)->values();

        if ($preferred->isEmpty()) {
            $preferred = $participants->values();
        }

        if ($excludeParticipantId && $preferred->count() > 1) {
            $preferred = $preferred->reject(fn (GameRoomParticipant $participant) => $participant->id === $excludeParticipantId)->values();
        }

        if ($preferred->isEmpty()) {
            $preferred = $participants->values();
        }

        return $preferred->random();
    }
}
