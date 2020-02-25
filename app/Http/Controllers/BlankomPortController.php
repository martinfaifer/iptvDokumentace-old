<?php

namespace App\Http\Controllers;

use App\Blankom_port;
use Illuminate\Http\Request;

class BlankomPortController extends Controller
{
    /**
     * $table->string('rf1')->nullable();
            $table->string('rf2')->nullable();
            $table->string('rf3')->nullable();
            $table->string('rf4')->nullable();
     *
     * @param [type] $channelId
     * @return void
     */
    public static function getChannelOnBlankom($rfId)
    {
        return Blankom_port::where('id', $rfId)->first();
    }

    public function getPorts(Request $request)
    {
        $device = DeviceController::sortDevice($request->deviceId);
        foreach ($device as $d) {
            if ($d["tag"] == 'Blankom') {
                return Blankom_port::all();
            } else {
                return "submit";
            }
        }
    }
}
