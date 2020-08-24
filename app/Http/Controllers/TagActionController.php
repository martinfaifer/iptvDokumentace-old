<?php

namespace App\Http\Controllers;

use App\TagAction;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use phpseclib\Net\SSH2;

class TagActionController extends Controller
{
    /**
     * fn pro vyvolání akce na základe stítku a akce u nej ulozene
     *
     * @return void
     */
    public static function actionCheck($tagActionId, $channelId)
    {
        // overeni zda existuje dana akce
        if (TagAction::where('id', $tagActionId)->first()) {
            // tag existuje , nyni bude probihat vyvolaní akce

            switch (TagAction::where('id', $tagActionId)->first()->action) {
                case "channelAutomaticReboot":

                    // automaticke restartování kanálu
                    self::channelAutomaticReboot($tagActionId, $channelId);

                    break;

                default:
                    echo "defaultni akce , pokud se nepodari vyhledat akci";
            }
        }
    }

    /**
     * fn pro automatické restartování kanálu
     *
     * fn sama detekuje zda kanál má možnost automatického restartu za zaklade zařízení
     *
     * @return void
     */
    public static function channelAutomaticReboot($tagActionId, $channelId)
    {
        // vyhledání, kde je možné kanál restartovat => vyhledání zařízení, které toto umožňuje a kanál na něj má vazbu
        $deviceDataArray = DeviceController::checkIfDeviceHaveTagForActions($channelId); // pokud vrátí false, neco selhalo a kanál se nerestartuje

        foreach ($deviceDataArray as $device) {
            // bud "false" nebo array
            if (is_array($device)) {
                // overeni zda je transcoder = "false" nebo "true"
                if ($device['transcoder'] == "false") {
                    try {

                        // pripojení pomocí SSH
                        $ssh = new SSH2($device['ip']);

                        if (!$ssh->login($device['user'], $device['password'])) {

                            return [
                                'status' => "fail",
                                'msg' => "connection fail"
                            ];
                        }

                        $ssh->exec($device['path']);
                    } catch (\Throwable $th) {
                        //throw $th;
                        return "nepodařill se komunikovat se serverem";
                    }
                } else {

                    // pripojení pomocí API
                    // odeslání requestu do aplikace pro ovládání transcodérů , vyhledává se dle src adresy na transcoderu ve formátu channelUrl:1234 , kde se vyhledá částečná shoda
                    $client = new Client();
                    $res = $client->get($device['ip'] . "/api/findStreamAndTryToReboot?src=" . $device['channelUrl']);
                    // echo $res->getStatusCode();
                    // echo $res->getBody();
                }
            }
        }
    }

    /**
     * fn pro vytvorení akce pro stitek
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        // some code here ...
    }


    /**
     * fn pro aktualizaci akce
     *
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        // some code here ...
    }


    /**
     * odebrání akce => editace stitku a odebrání akce u nej Tags_table
     *
     * @param Request $request
     * @return void
     */
    public function delete(Request $request)
    {
        // some code here ...
        // update tags
    }
}
