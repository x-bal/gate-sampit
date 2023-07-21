<?php

namespace App\Exports;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LogExport implements FromView
{
    public function view(): View
    {
        $to = Carbon::parse(request('to'))->addDay(1)->format('Y-m-d H:i:s');
        $logs = Log::with('gate')->whereBetween('waktu', [request('from'), $to])->latest()->get();

        return view('log.export', [
            'logs' => $logs
        ]);
    }
}
