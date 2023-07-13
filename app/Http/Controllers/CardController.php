<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::get();

        return view('card.index', compact('cards'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'rfid' => 'required|string|unique:cards',
            'nopol' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $card = Card::create([
                'rfid' => $request->rfid,
                'nopol' => $request->nopol,
            ]);

            DB::commit();

            return back()->with('success', "Card rfid berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Card $card)
    {
        return response()->json([
            'card' => $card
        ]);
    }

    public function edit(Card $card)
    {
        //
    }

    public function update(Request $request, Card $card)
    {
        $request->validate([
            'rfid' => 'required|string|unique:cards,rfid,' . $card->id,
            'nopol' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $card->update([
                'rfid' => $request->rfid,
                'nopol' => $request->nopol,
            ]);

            DB::commit();

            return back()->with('success', "Card rfid berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Card $card)
    {
        try {
            DB::beginTransaction();

            $card->delete();

            DB::commit();

            return back()->with('success', "Card rfid berhasil didelete");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function change(Request $request, Card $card)
    {
        try {
            DB::beginTransaction();

            $card->update(['status' => $request->status]);

            DB::commit();

            return response()->json([
                "status" => "success",
                "message" => "Status card rfid berhasil diupdate"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                "status" => "error",
                "message" => $th->getMessage()
            ]);
        }
    }
}
