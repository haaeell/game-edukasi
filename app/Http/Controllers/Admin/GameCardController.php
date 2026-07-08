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
    public function create(GameCardSet $gameCardSet): View
    {
        return view('admin.game-cards.create', [
            'set' => $gameCardSet,
        ]);
    }

    public function store(StoreGameCardRequest $request, GameCardSet $gameCardSet): RedirectResponse
    {
        $nextOrder = ((int) $gameCardSet->cards()->max('order_number')) + 1;

        $gameCardSet->cards()->create([
            ...$request->validated(),
            'order_number' => $nextOrder,
        ]);

        return redirect()->route('admin.game-card-sets.show', $gameCardSet)->with('success', 'Kartu berhasil ditambahkan.');
    }

    public function edit(GameCardSet $gameCardSet, GameCard $card): View
    {
        $this->guardSetCardRelation($gameCardSet, $card);

        return view('admin.game-cards.edit', [
            'set' => $gameCardSet,
            'card' => $card,
        ]);
    }

    public function update(UpdateGameCardRequest $request, GameCardSet $gameCardSet, GameCard $card): RedirectResponse
    {
        $this->guardSetCardRelation($gameCardSet, $card);

        $card->update($request->validated());

        return redirect()->route('admin.game-card-sets.show', $gameCardSet)->with('success', 'Kartu berhasil diperbarui.');
    }

    public function destroy(GameCardSet $gameCardSet, GameCard $card): RedirectResponse
    {
        $this->guardSetCardRelation($gameCardSet, $card);

        $deletedOrder = $card->order_number;
        $card->delete();

        $gameCardSet->cards()
            ->where('order_number', '>', $deletedOrder)
            ->decrement('order_number');

        return redirect()->route('admin.game-card-sets.show', $gameCardSet)->with('success', 'Kartu berhasil dihapus.');
    }

    public function moveUp(GameCardSet $gameCardSet, GameCard $card): RedirectResponse
    {
        $this->guardSetCardRelation($gameCardSet, $card);

        $previous = $gameCardSet->cards()
            ->where('order_number', '<', $card->order_number)
            ->orderByDesc('order_number')
            ->first();

        if ($previous) {
            $previousOrder = $previous->order_number;
            $previous->update(['order_number' => $card->order_number]);
            $card->update(['order_number' => $previousOrder]);
        }

        return back()->with('success', 'Urutan kartu diperbarui.');
    }

    public function moveDown(GameCardSet $gameCardSet, GameCard $card): RedirectResponse
    {
        $this->guardSetCardRelation($gameCardSet, $card);

        $next = $gameCardSet->cards()
            ->where('order_number', '>', $card->order_number)
            ->orderBy('order_number')
            ->first();

        if ($next) {
            $nextOrder = $next->order_number;
            $next->update(['order_number' => $card->order_number]);
            $card->update(['order_number' => $nextOrder]);
        }

        return back()->with('success', 'Urutan kartu diperbarui.');
    }

    private function guardSetCardRelation(GameCardSet $gameCardSet, GameCard $card): void
    {
        abort_unless($card->game_card_set_id === $gameCardSet->id, 404);
    }
}
