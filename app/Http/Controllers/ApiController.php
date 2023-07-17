<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    function create(Request $request)
    {
        try {
            DB::beginTransaction();

            if ($request->rfid) {
                foreach ($request->rfid as $rfid) {
                    $card = Card::where('rfid', $rfid)->first();

                    if ($card) {
                        Log::create([
                            'rfid' => $card->rfid,
                            'nopol' => $card->nopol,
                            'waktu' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                            'status' => $card->status == 1 ? 'Active' : 'Nonactive',
                            'gate_id' => $request->gate
                        ]);
                    } else {
                        Log::create([
                            'rfid' => $rfid,
                            'nopol' => '-',
                            'waktu' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                            'status' => 'Tidak Terdaftar',
                            'gate_id' => $request->gate
                        ]);
                    }
                }
            }

            $totalRegistered = Card::whereIn('rfid', $request->rfid)->where('status', 1)->count();

            if ($totalRegistered > 0) {
                $status = 'Open';
            } else {
                $status = 'Closed';
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $status,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => "error",
                'message' => $th->getMessage()
            ]);
        }
    }
}
