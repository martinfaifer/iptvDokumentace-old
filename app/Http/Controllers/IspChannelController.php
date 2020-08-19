<?php

namespace App\Http\Controllers;

use App\Isp_channel;
use Illuminate\Http\Request;

class IspChannelController extends Controller
{
    public static function ispChannels($channelId)
    {
        return Isp_channel::where('id_channel', $channelId)->get();
    }


    /**
     * fn pro editaci jen zalozených isp u kanálu
     *
     * @param Request $request
     * @return void
     */
    public function editIspData(Request $request)
    {
        $validation = ValidationController::validateIfIsNull($request->isp);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->stbIp);
        if ($validation != "true") {
            return $validation;
        }

        // $validation = ValidationController::validateIPv4($request->multicastIp);
        // if ($validation != "true") {
        //     return $validation;
        // }


        $dohledUrl = $request->dohledUrl ?? null;

        // aktualizace záznamu v Isp_channels table
        $update = Isp_channel::find($request->channelMulticastId);

        $update->id_isp = $request->isp;
        $update->ip = $request->multicastIp;

        $update->save();

        ChannelsController::updateChannel("ipKstb", $request->stbIp, $request->channelId);
        ChannelsController::updateChannel("dohledUrl", $dohledUrl, $request->channelId);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Editace proběhla úspěšně"
        ];
    }

    /**
     * fn pro odebrání multicástových dat, ldy pokud existuje více jak 1 input tak se odebere pouze input, ale záznam jako takový se nechává
     * pokud neexistuje další inout tak bude kanál označen za neaktivní a následně bude umožněno jej smazat
     *
     * @param Request $request
     * @return void
     */
    public function deleteIspData(Request $request)
    {
        $pocetVystupu = Isp_channel::where('id_channel', $request->channelId)->count();
        if ($pocetVystupu > 1) {
            Isp_channel::find($request->channelMulticastId)->delete();
            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Smazání proběhlo úspěšně"
            ];
        } else {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nelze oedebrat multicast, pouze odebrat kanál"
            ];
        }
    }


    /**
     * fn pro přidání nového multicastu od nějakého ISP
     * @param Request $request
     * @return void
     */
    public function addIspData(Request $request)
    {
        if ($request->priority = false) {
            $priority = "S";
        } else {
            $priority = "P";
        }
        Isp_channel::create([
            'id_isp' => $request->isp,
            'id_channel' => $request->channelId,
            'ip' => $request->newMulticastIp,
            'priority' => $priority,
        ]);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Přidána nová multicástová adresa"
        ];
    }

    /**
     * obecna fn pro zalozeni multicastu k isp a kanalu
     *
     * @return void
     */
    public static function create($isp, $channelId, $ip)
    {
        Isp_channel::create([
            'id_isp' => $isp,
            'id_channel' => $channelId,
            'ip' => $ip
        ]);
    }
}
