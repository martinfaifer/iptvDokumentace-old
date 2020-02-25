<?php

namespace App\Http\Controllers;

use App\Channels;
use App\H264;
use App\H265;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    // získání vše kanálů včetně nastavení tabulky
    public function get()
    {
        return Channels::all();
    }


    /**
     * fn pro editaci
     *
     * @param [string] $column
     * @param [string] $deviceId
     * @param [string] $channelId
     * @return void
     */
    public static function updateChannel($column, $columnData, $channelId)
    {
        $update = Channels::find($channelId);

        $update->$column = $columnData;

        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Editace proběhla úspěšně"
        ];
    }

    /**
     * fn pro editaci názvu kanálu
     *
     * @param Request $request
     * @return void
     */
    public function editChannelName(Request $request)
    {
        $this->updateChannel("nazev", $request->channelName, $request->channelId);
        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Kanál byl úspěšně z editován"
        ];
    }

    // Uložení nového nebo editovaného multiplexeru
    public function setMultiplexer(Request $request)
    {
        $update = Channels::find($request->channelId);

        $update->multiplexer_id = $request->multiplexerId;

        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Multiplexer byl úspěšně přidán"
        ];
    }

    public function deleteMultiplexer(Request $request)
    {
        $update = Channels::find($request->channelId);

        $update->multiplexer_id = "false";

        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Multiplexer byl úspěšně smazán"
        ];
    }

    /**
     * fn pro výpis multicastových informací
     *
     * @param Request $request
     * @return void
     */
    public function getChannelMulticastInfo(Request $request)
    {
        $rf = "false";
        $img = "false";
        $historyName = "false";
        $history = "false";
        // $outup = "false";

        // hledání konkrétního kanálu dle "id"
        $channel = Channels::where('id', $request->id)->first();

        // získání náhledu z h264 url pres API

        $h264Data = H264Controller::getH264($request->id);
        if (empty($h264Data)) {
            $img = "false";
        } else {
            if ($h264Data->K1080 != null) {
                $img = ApiSystemUrlController::getNahledFromDohled($h264Data->K1080);
                // $historyName = ApiSystemUrlController::getHistoryName($h264Data->K1080);
                // $history = ApiSystemUrlController::getHistyByName($historyName);
            } elseif ($h264Data->K576 != null) {
                $img = ApiSystemUrlController::getNahledFromDohled($h264Data->K576);
                // $historyName = ApiSystemUrlController::getHistoryName($h264Data->K576);
                // $history = ApiSystemUrlController::getHistyByName($historyName);
            } elseif ($h264Data->K404 != null) {
                $img = ApiSystemUrlController::getNahledFromDohled($h264Data->K404);
                // $historyName = ApiSystemUrlController::getHistoryName($h264Data->K404);
                // $history = ApiSystemUrlController::getHistyByName($historyName);
            }
        }

        if ($img == null) {
            $img = "false";
        }

        // konec zíslání náhledu
        $multiplexer = DeviceController::DeviceInfo($channel->multiplexer_id);
        if (!$multiplexer) {
            $multiplexer = [
                [
                    'status' => "KO",
                    'id' => "1"
                ]
            ];
        } else {
            $multiplexer = [
                [
                    'id' => $multiplexer->id,
                    'status' => "OK",
                    'nazev' => $multiplexer->name,
                    'ip' => $multiplexer->ip,
                    'login' => $multiplexer->login,
                    'poznamka' => $multiplexer->poznamka
                ]
            ];
        }
        $prijimac = DeviceController::sortDevice($channel->device_id);

        $rfUnSort = BlankomPortController::getChannelOnBlankom($channel->blankom_rf);
        if (!empty($rfUnSort)) {
            $rf = $rfUnSort->rf;
        }

        $multicastIPsWithoutIsp = IspChannelController::ispChannels($channel->id);
        foreach ($multicastIPsWithoutIsp as $multicast) {
            $isp = IspController::getIsp($multicast->id_isp);
            $outup[] = array(
                'id' => $multicast->id,
                'ispId' => $isp->id,
                'ip' => $multicast->ip,
                'isp' => $isp->isp
            );
        }
        return [
            'nazev' => $channel->nazev,
            'ipKstb' => $channel->ipKstb,
            'multicast' => $outup,
            'multiplexer' => $multiplexer,
            'prijem' => $prijimac,
            'rf' => $rf,
            'img' => $img,
            'history' => $history,

        ];
    }


    /**
     * fn pro vypis informací pro h264
     */
    public function getChannelh264Info(Request $request)
    {
        // hledání konkrétního kanálu dle "id"
        $channel = Channels::where('id', $request->id)->first();

        $h264Info = H264::where('id_channel', $channel->id)->first();
        if (!$h264Info) {

            $transcoder = [
                [
                    'tag' => "NoTranscoder"
                ]
            ];

            return [
                'nazev' => $channel->nazev,
                'transcoder' => $transcoder,
            ];
        } else {

            $transcoder = DeviceController::sortDevice($h264Info->id_device);
            return [
                'nazev' => $channel->nazev,
                'chundStoreId' => $h264Info->chunk_Store_id,
                'K1080' => $h264Info->K1080,
                'K720' => $h264Info->K720,
                'K576' => $h264Info->K576,
                'K270' => $h264Info->K270,
                'K404' => $h264Info->K404,
                'm3u8_stb' => $h264Info->m3u8_stb,
                'm3u8_HLS_kdekoliv' => $h264Info->m3u8_HLS_kdekoliv,
                'm3u8_mobile' => $h264Info->m3u8_mobile,
                'transcoder' => $transcoder,
            ];
        }
    }


    public function getChannelH265info(Request $request)
    {
        // hledání konkrétního kanálu dle "id"
        $channel = Channels::where('id', $request->id)->first();

        $h265Info = H265::where('id_channel', $channel->id)->first();
        if (!$h265Info) {

            $transcoder = [
                [
                    'tag' => "NoTranscoder"
                ]
            ];

            return [
                'nazev' => $channel->nazev,
                'transcoder' => $transcoder,
            ];
        } else {

            $transcoder = DeviceController::sortDevice($h265Info->id_device);
            return [
                'nazev' => $channel->nazev,
                'K1080' => $h265Info->K1080,
                'K720' => $h265Info->K720,
                'transcoder' => $transcoder,
            ];
        }
    }


    public static function showChannelsByDevice($deviceId)
    {
        return Channels::where('device_id', $deviceId)->get();
    }

    public static function showChannelsByMultiplexer($deviceId)
    {
        return Channels::where('multiplexer_id', $deviceId)->get();
    }


    /**
     * fn pro pocet kanálů
     *
     * @param [string] $column
     * @param [string] $deviceId
     * @return void
     */
    public static function countChannels($column, $data)
    {
        return Channels::where($column, $data)->count();
    }


    /**
     * obecna fn pro vyhledání kanálu dle parametrů
     *
     * @param [type] $column
     * @param [type] $data
     * @return void
     */
    public function find($column, $data)
    {
        return Channels::where($column, $data)->first();
    }

    /**
     * fn pro vytvoreni nového kanálu
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        Channels::create([
            'nazev' => $request->channelName,
            'ipKstb' => $request->channelMulticastKStb,
        ]);

        $channel = $this->find('ipKstb', $request->channelMulticastKStb);
        IspChannelController::create($request->channelIsp, $channel->id, $request->channelMulticast);

        $overeni = $this->find("ipKstb", $request->channelMulticastKStb);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Kanál byl založen",
            'id' => $overeni->id
        ];
    }


    /**
     * fn pro smazání kanálu
     * $request->channelId
     * @return void
     */
    public function delete(Request $request)
    {

        if (H264Controller::countChannels("id_channel", $request->channelId) == 0 && H265Controller::countChannels("id_channel", $request->channelId) == 0) {
            Channels::find($request->channelId)->delete();
            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Kanál byl odebrán"
            ];
        } else {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nejdříve odeberte H264 a H265 kvality!"
            ];
        }
    }
}
