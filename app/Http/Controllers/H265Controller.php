<?php

namespace App\Http\Controllers;

use App\H265;
use Illuminate\Http\Request;

class H265Controller extends Controller
{

    var $kvalita1080p = NULL;
    var $kvalita720p = NULL;
    var $device = NULL;

    public static function getH265($channelId)
    {
        return H265::where('id_channel', $channelId)->first();
    }


    /**
     * fn pro spocutani kanalu na h265 dle par
     *
     * @param [string] $column
     * @param [string] $data
     * @return void
     */
    public static function countChannels($column, $data)
    {
        return H265::where($column, $data)->count();
    }

    public static function updateH265($column, $columnData, $channelId)
    {
        $getId = H265::where('id_channel', $channelId)->first();

        $update = H265::find($getId->id);

        $update->$column = $columnData;

        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Editace proběhla úspěšně"
        ];
    }


    /**
     * fn pro editaci již přiřazeného transcodéru u kanálu
     *
     * @param Request $request
     * @return void
     */
    public function editTranscoder(Request $request)
    {
        return $this->updateH265("id_device", $request->device, $request->channelId);
    }


    /**
     * fn pro editaci H624 výstupů
     * @param Request $request
     * @return void
     */
    public function editOutput(Request $request)
    {

        // validace
        if ($request->channelId) {
            if (!empty($request->kvalita1080p)) {
                $this->updateH265("K1080", $request->kvalita1080p, $request->channelId);
            } else {
                $this->updateH265("K1080", NULL, $request->channelId);
            }
            if (!empty($request->kvalita720p)) {
                $this->updateH265("K720", $request->kvalita720p, $request->channelId);
            } else {
                $this->updateH265("K720", NULL, $request->channelId);
            }

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Editace proběhla úspěšně"
            ];
        }

        return [
            'isAlert' => "isAlert",
            'stat' => "error",
            'msg' => "Něco se nepodařilo během editace"
        ];
    }

    /**
     * fn pro smazaní hls u kanálu
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        $getId = H265::where('id_channel', $request->channelId)->first();

        H265::find($getId->id)->delete();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "HLS bylo odebráno"
        ];
    }



    /**
     * fn pro uložení nových dat ke kanálu na h265
     *
     * newChannelId: this.$route.params.id,
          newDevice: this.newDevice
     *
     * @param Request $request
     * @return void
     */
    public function addOutput(Request $request)
    {

        if ($request->newChannelId) {
            if (!empty($request->newKvalita1080p)) {
                $this->kvalita1080p = $request->newKvalita1080p;
            }

            if (!empty($request->newKvalita720p)) {
                $this->kvalita720p = $request->newKvalita720p;
            }


            if (!empty($request->newDevice)) {
                $this->device = $request->newDevice;
            }

            H265::create([
                'id_channel' => $request->newChannelId,
                'id_device' => $this->device,
                'K1080' => $this->kvalita1080p,
                'K720' => $this->kvalita720p,
            ]);

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Založení proběhlo úspěšně"
            ];
        }

        return [
            'isAlert' => "isAlert",
            'stat' => "error",
            'msg' => "Něco se nepodařilo během založení "
        ];
    }
}
