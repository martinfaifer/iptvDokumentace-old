<?php

namespace App\Http\Controllers;

use App\ApiSystemUrl;
use App\Channels;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApiSystemUrlController extends Controller
{
    public static function getNahledFromDohled($channel)
    {
        // ziskáme pole s url
        $dohled = ApiSystemUrl::where('type', "dohled")->first();

        $channelName = str_replace(" ", "%20", $channel);

        if ($dohled == false) {
            return "false";
        } else {

            // získání skrz API url, kde se nachází obrázek (náhled)
            $imgUrl = file_get_contents('http://' . $dohled->url . '/api/channel/nahled/' . $channelName);
            if ($dohled->url . "false" == $dohled->url . $imgUrl) {
                // nenačetl se obrazek => false
                return "false";
            } else {
                return $dohled->url . $imgUrl;
            }
        }
    }

    /**
     * fn pro získání informací o kanálech, které momentálně nefungují
     *
     * @return void
     */
    public function getIPTVAlert()
    {
        // ziskáme pole s url
        $dohled = ApiSystemUrl::where('type', "dohled")->first();

        if ($dohled == false) {
            return "false";
        } else {

            $json = json_decode(file_get_contents('http://' . $dohled->url . '/api/getCrashedChannels'), true);

            return $json;
        }
    }

    /**
     * fn pro lzískání historie dle kanálu dle jeho url -> stále v todo
     *
     * @param [type] $channelUrl
     * @return void
     */
    public function getHistoryName(Request $request)
    {
        // získáme ID kanálu -> nutno dohledat název kanálu

        $channel = Channels::where('id', $request->id)->first();
        $channelName = str_replace(" ", "%20", $channel->nazev);
        // ziskáme pole s url
        $dohled = ApiSystemUrl::where('type', "dohled")->first();

        if ($dohled == false) {
            return "false";
        } else {

            $json = json_decode(file_get_contents('http://' . $dohled->url . '/api/channel/crashed/' . $channelName), true);

            return $json;
        }
    }

    /**
     *
     * @param [type] $name
     * @return void
     */
    public static function getHistyByName($name)
    {

        // ziskáme pole s url
        $dohled = ApiSystemUrl::where('type', "dohled")->first();

        if ($dohled == false) {
            return "false";
        } else {
            $client = new Client;

            $response = $client->post('http://' . $dohled->url . '/api/channel/historyData', [

                'form_params' => [
                    'channel_id' => $name,
                ]
            ]);


            if ($body = $response->getBody()->getContents()) {
                $bodyParse = explode(",", $body);
                return $body;
                die;
                // $nameParse = $bodyParse[1];
                // $nazev = str_replace("\"nazev\":", "", $nameParse);
                // return $nazev;
            }
        }
    }

    /**
     * fn pro získání kvalit a bitratu, pro h264 a H265
     *
     * @return void
     */
    public function getKvalityFromTranscoder()
    {
        $apiUrl = ApiSystemUrl::where('type', "transcoder")->first();
        if ($apiUrl == false) {
            return "false";
        } else {

            $json = json_decode(file_get_contents('http://' . $apiUrl->url . '/api/getKvalities'), true);

            return $json;
        }
    }
}
