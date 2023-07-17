<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        return view('log.index');
    }

    function get(Request $request)
    {
        if ($request->ajax()) {
            $logs = Log::with('gate')->latest()->get();

            return response()->json([
                'logs' => $logs
            ]);
        }
    }

    function gate(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $limit = Carbon::now('Asia/Jakarta')->subSecond(30)->format('Y-m-d H:i:s');

        $logs = Log::where('gate_id', $request->gate)->whereBetween('waktu', [$limit, $now])->latest()->get();

        return response()->json([
            'logs' => $logs
        ]);
    }
}
