<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGameCardSetRequest;
use App\Http\Requests\Admin\UpdateGameCardSetRequest;
use App\Models\GameCardSet;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GameCardSetController extends Controller
{
    public function index(): View
    {
        return view('admin.game-card-sets.index', [
            'sets' => GameCardSet::withCount('cards')->with('creator')->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.game-card-sets.create');
    }

    public function store(StoreGameCardSetRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $set = GameCardSet::create($data);

        return redirect()->route('admin.game-card-sets.show', $set)->with('success', 'Card set berhasil dibuat.');
    }

    public function show(GameCardSet $gameCardSet): View
    {
        return view('admin.game-card-sets.show', [
            'set' => $gameCardSet->load('cards'),
        ]);
    }

    public function edit(GameCardSet $gameCardSet): View
    {
        return view('admin.game-card-sets.edit', [
            'set' => $gameCardSet,
        ]);
    }

    public function update(UpdateGameCardSetRequest $request, GameCardSet $gameCardSet): RedirectResponse
    {
        $gameCardSet->update($request->validated());

        return redirect()->route('admin.game-card-sets.index')->with('success', 'Card set berhasil diperbarui.');
    }

    public function destroy(GameCardSet $gameCardSet): RedirectResponse
    {
        $gameCardSet->delete();

        return redirect()->route('admin.game-card-sets.index')->with('success', 'Card set berhasil dihapus.');
    }
}
