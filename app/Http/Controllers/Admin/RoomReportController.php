<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameCard;
use App\Models\GameRoom;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class RoomReportController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $rooms = GameRoom::withCount(['participants', 'messages'])
            ->with(['host', 'cardSet'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($request->query('search'), function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.room-reports.index', [
            'rooms' => $rooms,
            'filters' => [
                'status' => $status,
                'search' => $request->query('search'),
            ],
            'summary' => [
                'total' => GameRoom::count(),
                'playing' => GameRoom::where('status', 'playing')->count(),
                'finished' => GameRoom::where('status', 'finished')->count(),
                'waiting' => GameRoom::where('status', 'waiting')->count(),
            ],
        ]);
    }

    public function show(GameRoom $gameRoom): View
    {
        return view('admin.room-reports.show', $this->buildReportData($gameRoom));
    }

    public function pdf(GameRoom $gameRoom): Response
    {
        $data = $this->buildReportData($gameRoom);

        $pdf = Pdf::loadView('admin.room-reports.pdf', $data)->setPaper('a4', 'portrait');

        $fileName = 'laporan-room-'.$gameRoom->code.'.pdf';

        return $pdf->download($fileName);
    }

    private function buildReportData(GameRoom $gameRoom): array
    {
        $gameRoom->load([
            'host',
            'cardSet.cards' => fn ($query) => $query->orderBy('order_number'),
            'participants' => fn ($query) => $query->orderByDesc('is_host')->orderBy('joined_at'),
            'participants.user',
            'messages' => fn ($query) => $query->orderBy('created_at'),
            'messages.participant',
            'messages.card',
            'feedbacks' => fn ($query) => $query->orderBy('created_at'),
        ]);

        $openedCardIds = collect($gameRoom->opened_card_ids ?? [])->map(fn ($id) => (int) $id)->filter()->values();
        $cardsById = GameCard::whereIn('id', $openedCardIds)->get()->keyBy('id');

        $openedCards = $openedCardIds
            ->map(fn ($id, $index) => $cardsById->get($id) ? [
                'order' => $index + 1,
                'title' => $cardsById->get($id)->title,
                'question' => $cardsById->get($id)->question,
            ] : null)
            ->filter()
            ->values();

        $durationSeconds = null;

        if ($gameRoom->started_at) {
            $durationSeconds = $gameRoom->started_at->diffInSeconds($gameRoom->ended_at ?? now());
        }

        return [
            'room' => $gameRoom,
            'openedCards' => $openedCards,
            'totalActiveCards' => $gameRoom->cardSet->cards->where('status', 'active')->count(),
            'messages' => $gameRoom->messages,
            'chatMessageCount' => $gameRoom->messages->where('message_type', 'chat')->count(),
            'feedbacks' => $gameRoom->feedbacks,
            'durationSeconds' => $durationSeconds,
            'generatedAt' => now(),
        ];
    }
}
