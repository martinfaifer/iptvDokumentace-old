<?php

namespace App\Http\Controllers;

use App\Channels;
use App\Device;
use App\H264;
use App\H265;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiController extends Controller
{
    // hello request
    public $hello = "d4c3ed93-3768-48c0-98b6-1717108157e9";

    public $hello_dohled = "873134d5-6324-4555-aa6d-fcdb1f7a9f4f";
    public $iptvdohledUriApiConnectionTest = "http://10.255.255.51/api/connectionTest";
    public $iptvdohledUriApiAlerts = "http://10.255.255.51/api/streamAlerts";
    public $iptvdohledUriApiNewEventCreate = "http://10.255.255.51/api/new_event";
    public $iptvdohledUriApiEventDelete = "http://10.255.255.51/api/delete_event";
    public $iptvdohledUriApiStreamInformation = "http://10.255.255.51/api/getInformationAboutStream";

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


    /**
     * funkce vrácí success / error
     * pro overení pripojení
     *
     * @return void
     */
    public function connectionTest_dohled(Request $request): string
    {
        // overení pripojení
        if ($request->hello == $this->hello) {
            return "success";
        } else {
            return "error";
        }
    }


    /**
     * funkce, která vrátí informace o kanálu, pokud existují
     *
     * @param Request $request->stream_url , hello
     * @return array
     */
    public function get_information_about_channel(Request $request): array
    {
        if ($this->connectionTest_dohled($request) == "error") {
            return [
                'status' => "error"
            ];
        }

        // if (!Channels::where('dohledUrl', $request->stream_url)->first()) {
        //     return [
        //         'status' => "not_found",
        //     ];
        // }

        // pokud nic neexistuje vrátí not_found
        if (!H264::where('udpxy', $request->stream_url)->first()) {
            if (!H265::where('udpxy', $request->stream_url)->first()) {
                return [
                    'status' => "not_found",
                ];
            }
        }

        if ($channelDataFound = H264::where('udpxy', $request->stream_url)->first()) {
        } else {
            $channelDataFound = H265::where('udpxy', $request->stream_url)->first();
        }

        $channelData = Channels::where('id', $channelDataFound->id_channel)->first();
        $prijem = Device::where('id', $channelData->device_id)->first();
        $multiplexer = Device::where('id', $channelData->multiplexer_id)->first();
        $h264 = H264::where('id_channel', $channelData->id)->first();
        if (!is_null($h264)) {
            $h264Device = Device::where('id', $h264->id_device)->first()->name;
            $h264DeviceId = Device::where('id', $h264->id_device)->first()->id;
        }
        $h265 = H265::where('id_channel', $channelData->id)->first();
        if (!is_null($h265)) {
            $h265Device = Device::where('id', $h265->id_device)->first()->name;
            $h265DeviceId = Device::where('id', $h265->id_device)->first()->id;
        }

        return [
            'status' => "success",

            'channnelData' => $channelData,
            'prijem' => $prijem,
            'multiplexer' => $multiplexer,
            'h264' => $h264,
            'h264Device' => $h264Device ?? null,
            'h264DeviceId' => $h264DeviceId ?? null,
            'h265' => $h265,
            'h265Device' => $h265Device ?? null,
            'h265DeviceId' => $h265DeviceId ?? null

        ];
    }


    /**
     * funkce na vrácení pole kanálů, které se mohou přidat do dohledu.
     *
     * obsahuje H264, H265
     *
     * @param Request $request
     * @return array
     */
    public function return_channels_for_adding_to_dohled(Request $request): array
    {
        // vyhledá všechny kanály a posle pole ve formátu název , url
        // url se vyhledává v h264->udpxy a h265->udpxy dle toho zda existují id_channel z tabulky channel

        if ($this->connectionTest_dohled($request) == "error") {
            return [
                'status' => "error"
            ];
        }

        foreach (Channels::get() as $channel) {
            // $channel->nazev
            // vyhledání h264
            if (!is_null($channel->dohledUrl)) {
                $output[] = array(
                    'nazev' => $channel->nazev . " Multicast",
                    'url' => $channel->dohledUrl
                );
            }

            if ($h264ChannelData = H264::where('id_channel', $channel->id)->first()) {
                if (!is_null($h264ChannelData->udpxy)) {
                    // overeno, ze hodnota udpxy není null
                    $output[] = array(
                        'nazev' => $channel->nazev . " H264",
                        'url' => $h264ChannelData->udpxy
                    );
                }
            }


            // vyhledání h265
            if ($h265ChannelData = H265::where('id_channel', $channel->id)->first()) {
                if (!is_null($h265ChannelData->udpxy)) {
                    // overeno, ze hodnota udpxy není null
                    $output[] = array(
                        'nazev' => $channel->nazev . " H265",
                        'url' => $h265ChannelData->udpxy
                    );
                }
            }
        }

        return $output;
    }



    /**
     *
     *
     * NAPOJENÍ NA DOHLED
     *
     * API TOKEN $hello_dohled
     *
     */

    /**
     * funkce na otestování připojení do dohledu
     *
     * @return void
     */
    public function test_connection_to_dohled()
    {
        try {
            $client = new Client;

            $response = $client->post($this->iptvdohledUriApiConnectionTest, [
                'form_params' => [
                    'hello' => $this->hello_dohled,
                ]
            ]);
            if ($body = $response->getBody()->getContents()) {
                return $body;
            }
        } catch (\Throwable $th) {
            return "error";
        }
    }


    /**
     * funkce na získání alertů z dohledu
     *
     * @return array
     */
    public function get_alerts_from_fohled()
    {
        if ($this->test_connection_to_dohled() != "success") {
            return [
                'status' => "not_connect"
            ];
        }

        $client = new Client;

        $response = $client->post($this->iptvdohledUriApiAlerts, [
            'form_params' => [
                'hello' => $this->hello_dohled,
            ]
        ]);
        // echo $response->getStatusCode();
        if ($body = $response->getBody()->getContents()) {
            return json_decode($body, true);
        }
    }


    /**
     * funkce na zalození nove udalosti z doku do dohledu, bez návratové hodnoty
     *
     * @return void
     */
    public static function new_event(
        $start_time,
        $end_time,
        $start_day,
        $end_day,
        $repeatEveryDay,
        $h264Uri,
        $h265Uri,
        $multicastUri
    ) {
        if ((new self())->test_connection_to_dohled() != "success") {
            return [
                'status' => "not_connect"
            ];
        }

        $client = new Client;

        $response = $client->post((new self())->iptvdohledUriApiNewEventCreate, [

            'form_params' => [
                'hello' => (new self())->hello_dohled,
                'start_day' => $start_day,
                'start_time' => $start_time,
                'end_day' => $end_day,
                'end_time' => $end_time,
                'every_day' => $repeatEveryDay,
                'multicastUri' => $multicastUri,
                'h264Uri' => $h264Uri,
                'h265Uri' => $h265Uri
            ]
        ]);

        if ($body = $response->getBody()->getContents()) {
            return json_decode($body, true);
        }
    }

    /**
     * funknce na odebrání události z dohledu
     *
     * @param string $h264Uri
     * @param string $h265Uri
     * @param string $multicastUri
     * @return void
     */
    public static function delete_event(
        $h264Uri,
        $h265Uri,
        $multicastUri,
        $start_time,
        $end_time
    ) {

        if ((new self())->test_connection_to_dohled() != "success") {
            return [
                'status' => "not_connect"
            ];
        }


        $client = new Client;

        $response = $client->post((new self())->iptvdohledUriApiEventDelete, [

            'form_params' => [
                'hello' => (new self())->hello_dohled,
                'multicastUri' => $multicastUri,
                'h264Uri' => $h264Uri,
                'h265Uri' => $h265Uri,
                'start_time' => $start_time,
                'end_time' => $end_time
            ]
        ]);

        if ($body = $response->getBody()->getContents()) {
            return json_decode($body, true);
        }
    }


    /**
     * funkce na získání celkových informací o streamu
     *
     * vrací pole o klících status, multicast, H264, H265
     *
     * @param Request $request->channelId
     * @return void
     */
    public static function getInformation_about_stream_multicast_and_unicasts(Request $request)
    {
        if ((new self())->test_connection_to_dohled() != "success") {
            return [
                'status' => "not_connect"
            ];
        }

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

        $client = new Client;

        $response = $client->post((new self())->iptvdohledUriApiStreamInformation, [

            'form_params' => [
                'hello' => (new self())->hello_dohled,
                'multicastUri' => $multicastUri,
                'h264Uri' => $h264Uri,
                'h265Uri' => $h265Uri,
            ]
        ]);

        if ($body = $response->getBody()->getContents()) {
            return json_decode($body, true);
        }
    }
}
