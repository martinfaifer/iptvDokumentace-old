<?php

namespace App\Http\Controllers;

use App\Channels;
use App\ChannelScheduler;
use App\H264;
use App\H265;
use Illuminate\Http\Request;

class ChannelSchedulerController extends Controller
{
    /**
     * funkce na overení, zda existuje udalost / udalosti u kanálu
     *
     * @param Request $request->channelId
     * @return void
     */
    public static function check_if_is_shedule(Request $request)
    {
        if (!ChannelScheduler::where('channelId', $request->channelId)->first()) {
            return [
                'status' => "empty"
            ];
        }
        return [
            'status' => "exist"
        ];
    }



    /**
     * funkcne na vrácení událostí u kanálu
     *
     * @param Request $request->channelId
     * @return array
     */
    public static function return_events_for_channel(Request $request)
    {
        // zjistení zda existuje událost
        $overeniPodminky = self::check_if_is_shedule($request);
        if ($overeniPodminky['status'] != "exist") {
            return [
                "status" => "empty"
            ];
        }

        return ChannelScheduler::where('channelId', $request->channelId)->get();
    }

    /**
     * funkce na vytvoření nové události do scheduleru  kalendare
     *
     * @param Request $request->channelId
     * @return array
     */
    public static function create_new_event(Request $request): array
    {
        // zobrazení dnešního data
        $today = date("Y-m-d", time());
        $currentTime = date("H:m", time());
        if (empty($request->start_time) || empty($request->end_time)) {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nebyl vyplněn čas"
            ];
        }

        if ((empty($request->start_day) || empty($request->end_day)) && $request->repeatEveryDay == false) {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nebyl vyplněn termín"
            ];
        }

        if ($request->repeatEveryDay == true) {
            $repeatEveryDay = "everyDay";
            $start_day = null;
            $end_day = null;
        } else {
            $repeatEveryDay = null;
            $start_day = $request->start_day;
            $end_day = $request->end_day;
        }

        // uložení
        try {
            ChannelScheduler::create([
                'channelId' => $request->channelId,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'start_day' => $start_day,
                'end_day' => $end_day,
                'every_day' => $repeatEveryDay,
                'event' => $request->eventPopis
            ]);

            // vyhedání informací o H264, H265, multicastu, pro odeslání informací do dohledu
            $multicastUri = Channels::where('id', $request->channelId)->first()->dohledUrl;
            if ($h264 = H264::where('id_channel', $request->channelId)->first()) {
                $h264Uri = $h264->udpxy;
            } else {
                $h264Uri = null;
            }

            if ($h265 = H265::where('id_channel', $request->channelId)->first()) {
                $h265Uri = $h265->udpxy;
            } else {
                $h265Uri = null;
            }


            ApiController::new_event(
                $request->start_time,
                $request->end_time,
                $start_day,
                $end_day,
                $repeatEveryDay,
                $h264Uri,
                $h265Uri,
                $multicastUri
            );

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Založena nová událost a poslána do Dohledu"
            ];
        } catch (\Throwable $th) {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nepodařilo se založit událost"
            ];
        }
    }

    /**
     * funkce na odebrání události
     *
     * @param Request $request->eventId , channelId, start_time, end_time
     * @return array
     */
    public static function delete_event(Request $request): array
    {
        try {
            ChannelScheduler::where('id', $request->eventId)->delete();


            // vyhedání informací o H264, H265, multicastu, pro odeslání informací do dohledu
            if (Channels::where('id', $request->channelId)->first()) {
                $multicastUri = Channels::where('id', $request->channelId)->first()->dohledUrl;
            } else {
                $multicastUri = null;
            }

            if ($h264 = H264::where('id_channel', $request->channelId)->first()) {
                $h264Uri = $h264->udpxy;
            } else {
                $h264Uri = null;
            }

            if ($h265 = H265::where('id_channel', $request->channelId)->first()) {
                $h265Uri = $h265->udpxy;
            } else {
                $h265Uri = null;
            }

            ApiController::delete_event($h264Uri, $h265Uri, $multicastUri, $request->startTime, $request->endTime);

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Odebrána událost"
            ];
        } catch (\Throwable $th) {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Nepodařilo se odebrat událost"
            ];
        }
    }


    public static function edit_event(): array
    {
        return [];
    }
}
