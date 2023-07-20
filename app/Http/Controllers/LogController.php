<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $logs = [];
        if ($request->from && $request->to) {
            $to = Carbon::parse($request->to)->addDay(1)->format('Y-m-d H:i:s');
            $logs = Log::with('gate')->whereBetween('waktu', [$request->from, $to])->latest()->get();
        }
        return view('log.index', compact('logs'));
    }

    function get(Request $request)
    {
        if ($request->ajax()) {
            $now = Carbon::now('Asia/Jakarta')->format('Y-m-d');
            $logs = Log::with('gate')->whereDate('waktu', $now)->latest()->get();

            return response()->json([
                'logs' => $logs
            ]);
        }
    }

    function gate(Request $request)
    {
        $gate = Gate::find($request->gate);
        $logs = Log::where('gate_id', $gate->id)->latest()->get();

        return response()->json([
            'logs' => $logs
        ]);
    }
}
