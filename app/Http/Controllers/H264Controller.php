<?php

namespace App\Http\Controllers;

use App\H264;
use App\Channels;
use Illuminate\Http\Request;

class H264Controller extends Controller
{

    // definice proměnných
    var $chunkStoreId = NULL;
    var $kvalita1080p = NULL;
    var $kvalita720p = NULL;
    var $kvalita576p = NULL;
    var $kvalita404p = NULL;
    var $hlsKdekoliv = NULL;
    var $hlsLocal = NULL;
    var $hlsMobile = NULL;
    var $device = NULL;
    var $kodek = "false";


    public static function getH264($channelId)
    {
        return H264::where('id_channel', $channelId)->first();
    }

    public static function updateH264($column, $columnData, $channelId)
    {
        $getId = H264::where('id_channel', $channelId)->first();

        $update = H264::find($getId->id);

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
        $validation = ValidationController::validateIfIsNull($request->multiplexerId);
        if ($validation != "true") {
            return $validation;
        }
        return $this->updateH264("id_device", $request->device, $request->channelId);
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
            if (!empty($request->chunkStoreId)) {
                $this->updateH264("chunk_Store_id", $request->chunkStoreId, $request->channelId);
            } else {
                $this->updateH264("chunk_Store_id", NULL, $request->channelId);
            }
            if (!empty($request->kvalita1080p)) {
                $this->updateH264("K1080", $request->kvalita1080p, $request->channelId);
            } else {
                $this->updateH264("K1080", NULL, $request->channelId);
            }
            if (!empty($request->kvalita720p)) {
                $this->updateH264("K720", $request->kvalita720p, $request->channelId);
            } else {
                $this->updateH264("K720", NULL, $request->channelId);
            }
            if (!empty($request->kvalita576p)) {
                $this->updateH264("K576", $request->kvalita576p, $request->channelId);
            } else {
                $this->updateH264("K576", NULL, $request->channelId);
            }
            if (!empty($request->kvalita404p)) {
                $this->updateH264("K404", $request->kvalita404p, $request->channelId);
            } else {
                $this->updateH264("K404", NULL, $request->channelId);
            }
            if (!empty($request->hlsKdekoliv)) {
                $this->updateH264("m3u8_HLS_kdekoliv", $request->hlsKdekoliv, $request->channelId);
            } else {
                $this->updateH264("m3u8_HLS_kdekoliv", NULL, $request->channelId);
            }
            if (!empty($request->hlsLocal)) {
                $this->updateH264("m3u8_stb", $request->hlsLocal, $request->channelId);
            } else {
                $this->updateH264("m3u8_stb", NULL, $request->channelId);
            }
            if (!empty($request->hlsMobile)) {
                $this->updateH264("m3u8_mobile", $request->hlsMobile, $request->channelId);
            } else {
                $this->updateH264("m3u8_mobile", NULL, $request->channelId);
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
     * fn pro uložení nových dat ke kanálu na h264
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
            if (!empty($request->newChunkStoreId)) {
                $this->chunkStoreId = $request->newChunkStoreId;
            }

            if (!empty($request->newKvalita1080p)) {
                $this->kvalita1080p = $request->newKvalita1080p;
            }

            if (!empty($request->newKvalita720p)) {
                $this->kvalita720p = $request->newKvalita720p;
            }

            if (!empty($request->newKvalita576p)) {
                $this->kvalita576p = $request->newKvalita576p;
            }

            if (!empty($request->newKvalita404p)) {
                $this->kvalita404p = $request->newKvalita404p;
            }

            if (!empty($request->newHlsKdekoliv)) {
                $this->hlsKdekoliv = $request->newHlsKdekoliv;
            }

            if (!empty($request->newHlsLocal)) {
                $this->hlsLocal = $request->newHlsLocal;
            }

            if (!empty($request->newHlsMobile)) {
                $this->hlsMobile = $request->newHlsMobile;
            }

            if (!empty($request->newDevice)) {
                $this->device = $request->newDevice;
            }

            /**
             * validace vstupních dat
             */
            $validation = ValidationController::validateIfIsNull($this->hlsKdekoliv);
            if ($validation != "true") {
                return $validation;
            }
            $validation = ValidationController::validateIfIsNull($this->hlsLocal);
            if ($validation != "true") {
                return $validation;
            }
            $validation = ValidationController::validateIfIsNull($this->hlsMobile);
            if ($validation != "true") {
                return $validation;
            }
            $validation = ValidationController::validateIfIsNull($this->device);
            if ($validation != "true") {
                return $validation;
            }
            /**
             * konec validace vstupu
             */
            H264::create([
                'id_channel' => $request->newChannelId,
                'kodek' => $this->kodek,
                'chunk_Store_id' => $this->chunkStoreId,
                'id_device' => $this->device,
                'K1080' => $this->kvalita1080p,
                'K720' => $this->kvalita720p,
                'K576' => $this->kvalita576p,
                'K404' => $this->kvalita404p,
                'm3u8_stb' => $this->hlsLocal,
                'm3u8_HLS_kdekoliv' => $this->hlsKdekoliv,
                'm3u8_mobile' => $this->hlsMobile,
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

    /**
     * fn pro smazaní hls u kanálu
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        $getId = H264::where('id_channel', $request->channelId)->first();

        H264::find($getId->id)->delete();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "HLS bylo odebráno"
        ];
    }


    /**
     * fn pro spocitni kanalu dle par
     *
     * @param [string] $column
     * @param [string] $data
     * @return void
     */
    public static function countChannels($column, $data)
    {
        return H264::where($column, $data)->count();
    }


    /**
     * funkce na vyhledání záznamů v tabulce channels a update záznamu v tabulce h264 do pole udpxy
     *
     * @return void
     */
    public static function get_dohledUrl_from_channels_add_to_udpxy_in_this(): void
    {
        foreach (Channels::get() as $channel) {
            if (H264::where('id_channel', $channel->id)->first()) {
                // update záznamu
                H264::where('id_channel', $channel->id)->update(['udpxy' => $channel->dohledUrl]);
            }
        }
    }
}
