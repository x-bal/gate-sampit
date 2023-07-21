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
        $id = $gate->id;

        $logs = DB::select("SELECT logs.*, gates.name as name FROM logs JOIN gates ON logs.gate_id = gates.id WHERE logs.gate_id = '$id' AND logs.waktu >= '$limit' AND logs.waktu <= '$now' AND logs.id IN (SELECT MAX(id) AS max_id FROM logs WHERE logs.gate_id = '$id' AND logs.waktu >= '$limit' AND logs.waktu <= '$now' GROUP BY logs.rfid) ORDER BY logs.id DESC");

        $table = '';
        $no = 1;

        foreach ($logs as $data) {
            $table .= '<tr>
                    <td>' . $no++ . '</td>
                    <td>' . $data->waktu . '</td>
                    <td>' . $data->rfid . '</td>
                    <td>' . $data->name . '</td>
                    <td>' . $data->nopol . '</td>
                    <td>' . $data->status . '</td>
                </tr>';
        }

        return response()->json([
            'logs' => $logs,
            'table' => $table
        ]);
    }
}
