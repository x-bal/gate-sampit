<?php

namespace App\Http\Controllers;

use App\Models\Gate;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $table = '';
            $no = 1;

            foreach ($logs as $data) {
                $table .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $data->waktu . '</td>
                    <td>' . $data->rfid . '</td>
                    <td>' . $data->gate->name . '</td>
                    <td>' . $data->nopol . '</td>
                    <td>' . $data->statu . '</td>
                </tr>';
            }
            return response()->json([
                'logs' => $logs,
                'table' => $table
            ]);
        }
    }

    function gate(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $limit = Carbon::now('Asia/Jakarta')->subSecond(30)->format('Y-m-d H:i:s');
        $gate = Gate::find($request->gate);

        $latestLogs = Log::select('logs.*')->with('gate')
            ->join(
                DB::raw('(SELECT rfid, MAX(waktu) AS max_waktu FROM logs GROUP BY rfid) as latest_logs'),
                function ($join) {
                    $join->on('logs.rfid', '=', 'latest_logs.rfid')
                        ->on('logs.waktu', '=', 'latest_logs.max_waktu');
                }
            )
            ->where('gate_id', $gate->id)
            ->whereBetween('waktu', [$limit, $now])
            ->latest()
            ->get();

        $new = $latestLogs->groupBy('rfid')->toArray();

        $table = '';
        $no = 1;


        foreach ($new as $rfid => $logArray) {
            foreach ($logArray as $data) {
                $table .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $data['waktu'] . '</td>
                    <td>' . $data['rfid'] . '</td>
                    <td>' . $data['gate']['name'] . '</td>
                    <td>' . $data['nopol'] . '</td>
                    <td>' . $data['status'] . '</td>
                </tr>';
            }
        }

        return response()->json([
            'logs' => $new,
            'table' => $table
        ]);
    }
}
