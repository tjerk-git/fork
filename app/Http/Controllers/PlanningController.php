<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::with(['cards' => function($query) {
            $query->orderBy('order');
        }])->get();
        
        return view('planning.index', compact('boards'));
    }

    /**
     * Store a newly created board in storage.
     */
    public function storeBoard(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Board::create($validated);

        return response()->json(['message' => 'Board created successfully']);
    }

    /**
     * Update the specified board in storage.
     */
    public function updateBoard(Request $request, Board $board)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $board->update($validated);

        return response()->json(['message' => 'Board updated successfully']);
    }

    /**
     * Remove the specified board from storage.
     */
    public function destroyBoard(Board $board)
    {
        $board->delete();
        return response()->json(['message' => 'Board deleted successfully']);
    }

    /**
     * Store a newly created card in storage.
     */
    public function storeCard(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'board_id' => 'required|exists:boards,id',
            'status' => 'required|in:todo,backlog,done',
            'priority' => 'required|in:low,medium,high'
        ]);

        $maxOrder = Card::where('board_id', $request->board_id)
            ->where('status', $request->status)
            ->max('order') ?? 0;

        $validated['order'] = $maxOrder + 1;

        Card::create($validated);

        return response()->json(['message' => 'Card created successfully']);
    }

    /**
     * Update the specified card in storage.
     */
    public function updateCard(Request $request, Card $card)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,backlog,done',
            'priority' => 'required|in:low,medium,high'
        ]);

        $card->update($validated);

        return response()->json(['message' => 'Card updated successfully']);
    }

    /**
     * Update the position of the specified card in storage.
     */
    public function updateCardPosition(Request $request, Card $card)
    {
        $validated = $request->validate([
            'board_id' => 'required|exists:boards,id',
            'order' => 'required|integer|min:0',
            'status' => 'required|in:todo,backlog,done'
        ]);

        // Update the status and board_id
        $card->status = $validated['status'];
        $card->board_id = $validated['board_id'];

        // If the card is moved to a different status or board, update the order of all cards
        if ($card->isDirty('status') || $card->isDirty('board_id')) {
            // Shift up the order of cards in the old status/board
            Card::where('board_id', $card->getOriginal('board_id'))
                ->where('status', $card->getOriginal('status'))
                ->where('order', '>', $card->order)
                ->decrement('order');

            // Shift down the order of cards in the new status/board
            Card::where('board_id', $validated['board_id'])
                ->where('status', $validated['status'])
                ->where('order', '>=', $validated['order'])
                ->increment('order');
        }

        // Update the card's order
        $card->order = $validated['order'];
        $card->save();

        return response()->json(['message' => 'Card position updated successfully']);
    }

    /**
     * Remove the specified card from storage.
     */
    public function destroyCard(Card $card)
    {
        $card->delete();
        return response()->json(['message' => 'Card deleted successfully']);
    }
}
