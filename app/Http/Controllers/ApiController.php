<?php

namespace App\Http\Controllers;

use App\Channels;
use App\H264;
use App\H265;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * fn pro získání souhrnných informací o kanálu, informace se zasílají dle id kanálu
     *
     * output dat bude -> název, IPkStb, výstupní kvality ( url ), m3u8 (h264 , h265), device na kterém je kanál evidovaný
     *
     * @param Request $request
     * @return void
     */
    public function getChannelDataForExternalSystem(Request $request)
    {
        return [
            'multicast' => Channels::where('id', $request->id)->get(['id', 'nazev', 'ipKstb']),
            'h264' => H264::where('id_channel', $request->id)->get(['K1080', 'K720', 'K576', 'K404', 'm3u8_stb', 'm3u8_HLS_kdekoliv', 'm3u8_mobile']),
            'h265' => H265::where('id_channel', $request->id)->get(['K1080', 'K720'])
        ];
    }


    /**
     * fn pro zaslání id, názvu , url pro dohled kanálu do dohledovacího systému
     *
     * @return void
     */
    public function getChannelDataForDohled()
    {

        return Channels::get(['id', 'nazev', 'dohledUrl']);
    }
}
