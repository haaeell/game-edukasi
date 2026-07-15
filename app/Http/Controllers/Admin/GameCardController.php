<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGameCardRequest;
use App\Http\Requests\Admin\UpdateGameCardRequest;
use App\Models\GameCard;
use App\Models\GameCardSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameCardController extends Controller
{
    public function create(int $gameCardSet): View
    {
        $set = GameCardSet::findOrFail($gameCardSet);

        return view('admin.game-cards.create', [
            'set' => $set,
        ]);
    }

    public function store(StoreGameCardRequest $request, int $gameCardSet): RedirectResponse
    {
        $set = GameCardSet::findOrFail($gameCardSet);

        $nextOrder = ((int) $set->cards()->max('order_number')) + 1;

        $set->cards()->create([
            ...$request->validated(),
            'order_number' => $nextOrder,
        ]);

        return redirect()->route('admin.game-card-sets.show', $set)->with('success', 'Kartu berhasil ditambahkan.');
    }

    public function edit(int $gameCardSet, int $card): View
    {
        [$set, $gameCard] = $this->findSetAndCard($gameCardSet, $card);

        return view('admin.game-cards.edit', [
            'set' => $set,
            'card' => $gameCard,
        ]);
    }

    public function update(UpdateGameCardRequest $request, int $gameCardSet, int $card): RedirectResponse
    {
        [$set, $gameCard] = $this->findSetAndCard($gameCardSet, $card);

        $gameCard->update($request->validated());

        return redirect()->route('admin.game-card-sets.show', $set)->with('success', 'Kartu berhasil diperbarui.');
    }

    public function destroy(int $gameCardSet, int $card): RedirectResponse
    {
        [$set, $gameCard] = $this->findSetAndCard($gameCardSet, $card);

        $deletedOrder = $gameCard->order_number;
        $gameCard->delete();

        $set->cards()
            ->where('order_number', '>', $deletedOrder)
            ->decrement('order_number');

        return redirect()->route('admin.game-card-sets.show', $set)->with('success', 'Kartu berhasil dihapus.');
    }

    public function moveUp(int $gameCardSet, int $card): RedirectResponse
    {
        [$set, $gameCard] = $this->findSetAndCard($gameCardSet, $card);

        $previous = $set->cards()
            ->where('order_number', '<', $gameCard->order_number)
            ->orderByDesc('order_number')
            ->first();

        if ($previous) {
            $previousOrder = $previous->order_number;
            $previous->update(['order_number' => $gameCard->order_number]);
            $gameCard->update(['order_number' => $previousOrder]);
        }

        return back()->with('success', 'Urutan kartu diperbarui.');
    }

    public function moveDown(int $gameCardSet, int $card): RedirectResponse
    {
        [$set, $gameCard] = $this->findSetAndCard($gameCardSet, $card);

        $next = $set->cards()
            ->where('order_number', '>', $gameCard->order_number)
            ->orderBy('order_number')
            ->first();

        if ($next) {
            $nextOrder = $next->order_number;
            $next->update(['order_number' => $gameCard->order_number]);
            $gameCard->update(['order_number' => $nextOrder]);
        }

        return back()->with('success', 'Urutan kartu diperbarui.');
    }

    /**
     * @return array{0: GameCardSet, 1: GameCard}
     */
    private function findSetAndCard(int $gameCardSetId, int $cardId): array
    {
        $set = GameCardSet::findOrFail($gameCardSetId);
        $card = GameCard::findOrFail($cardId);

        abort_unless($card->game_card_set_id === $set->id, 404);

        return [$set, $card];
    }
}
