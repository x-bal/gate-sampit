<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GateController extends Controller
{
    public function index()
    {
        $gates = Gate::get();

        return view('gate.index', compact('gates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gate' => 'required|numeric|unique:gates',
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $gate = Gate::create([
                'id_gate' => $request->id_gate,
                'name' => $request->name,
            ]);

            DB::commit();

            return back()->with('success', "Gate berhasil ditambahkan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function show(Gate $gate)
    {
        return response()->json([
            'gate' => $gate
        ]);
    }

    public function update(Request $request, Gate $gate)
    {
        $request->validate([
            'id_gate' => 'required|numerice|unique:gates,id_gate,' . $gate->id,
            'name' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $gate->update([
                'id_gate' => $request->id_gate,
                'name' => $request->name,
            ]);

            DB::commit();

            return back()->with('success', "Gate berhasil diupdate");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    public function destroy(Gate $gate)
    {
        try {
            DB::beginTransaction();

            $gate->delete();

            DB::commit();

            return back()->with('success', "Gate berhasil didelete");
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    function stream(Gate $gate)
    {
        return view('gate.stream', compact('gate'));
    }
}
