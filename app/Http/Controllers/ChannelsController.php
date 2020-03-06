<?php

namespace App\Http\Controllers;

use App\Channels;
use App\H264;
use App\H265;
use App\IPTVpackage;
use Illuminate\Broadcasting\Channel;
// use Barryvdh\DomPDF\PDF;
use PDF;
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

        $validation = ValidationController::validateIfIsNull($request->channelName);
        if ($validation != "true") {
            return $validation;
        }

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

        $validation = ValidationController::validateIfIsNull($request->multiplexerId);
        if ($validation != "true") {
            return $validation;
        }

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
        $backupPrijimac = array();
        $balicek = array();
        $poznamka = "false";
        $mainBalicek = array();
        $subBalicek = array();
        // $outup = "false";


        // hledání konkrétního kanálu dle "id"
        $channel = Channels::where('id', $request->id)->first();

        // vyhledání balíčku do ktereho je kanal prirazen
        if ($channel->iptvPackage == null && $channel->iptvSubPackage == null) {
            $balicek = "false";

            // Hlavni balicky (MINI, Basic, MAX)
        }
        if ($channel->iptvPackage != null) {

            $balicky = explode(",", $channel->iptvPackage);
            if (is_array($balicky)) {
                foreach ($balicky as $programovyBalicek) {
                    $findBalicek = IPTVpackageController::findPackageById($programovyBalicek, "main");
                    $mainBalicek[] = $findBalicek->nazevBalicku;
                }
            } else {
                $findBalicek = IPTVpackageController::findPackageById($balicky, "main");
                $mainBalicek = $findBalicek->nazevBalicku;
            }

            // Příplatkové balíčky
        }
        if ($channel->iptvSubPackage != null) {

            $balicky = explode(",", $channel->iptvSubPackage);
            if (is_array($balicky)) {
                foreach ($balicky as $programovySubBalicek) {

                    $findBalicek = IPTVpackageController::findPackageById($programovySubBalicek, "sub");
                    $subBalicek[] = $findBalicek->subBalicek;
                }
            } else {
                $findBalicek = IPTVpackageController::findPackageById($balicky, "sub");
                $subBalicek = $findBalicek->subBalicek;
            }
        }

        // overeni, zda je mainBalicek a $subBalicek array
        if (is_array($mainBalicek) && is_array($subBalicek)) {
            $balicek = array_merge($mainBalicek, $subBalicek);
        } else {
            $balicek = array($mainBalicek, $subBalicek);
        }

        // else {

        //     // Main balicky
        //     $mainBalicky = explode(",", $channel->iptvPackage);
        //     if (is_array($mainBalicky)) {
        //         foreach ($mainBalicky as $programovySubBalicek) {
        //             $findBalicek = IPTVpackageController::findPackageById($programovySubBalicek, "main");
        //             $mainBalicek[] = $findBalicek->nazevBalicku;
        //         }
        //     } else {
        //         $findBalicek = IPTVpackageController::findPackageById($mainBalicky, "main");
        //         $mainBalicek = $findBalicek->nazevBalicku;
        //     }

        //     // sub balicky
        //     $subBalicky = explode(",", $channel->iptvSubPackage);
        //     if (is_array($subBalicky)) {
        //         foreach ($subBalicky as $programovyBalicek) {
        //             $findBalicek = IPTVpackageController::findPackageById($programovyBalicek, "sub");
        //             $subBalicek[] = $findBalicek->nazevBalicku;
        //         }
        //     } else {
        //         $findBalicek = IPTVpackageController::findPackageById($subBalicky, "sub");
        //         $subBalicek = $findBalicek->nazevBalicku;
        //     }

        //     // overeni, zda je mainBalicek a $subBalicek array
        //     if (is_array($mainBalicek) && is_array($subBalicek)) {
        //         $balicek = array_merge($mainBalicek, $subBalicek);
        //     } else {
        //         $balicek = array($mainBalicek, $subBalicek);
        //     }
        // }

        // overeni, zda je mainBalicek a $subBalicek array
        // if (is_array($mainBalicek) && is_array($subBalicek)) {
        //     $balicek = array_merge($mainBalicek, $subBalicek);
        // } else {
        //     $balicek = array($mainBalicek, $subBalicek);
        // }

        // získání náhledu
        $img = ApiSystemUrlController::getNahledFromDohled($channel->nazev);
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

        $backupPrijimac = DeviceController::sortDevice($channel->device_backup_id);

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
            'backup' => $backupPrijimac,
            'balicek' => $balicek,
            'poznamka' => $channel->poznamka

        ];
    }

    /**
     * fn pro získání náhledu z API
     *
     * @return void
     */
    public function getNahled(Request $request)
    {

        $img = "false";

        $channel = Channels::where('id', $request->id)->first();
        $img = ApiSystemUrlController::getNahledFromDohled($channel->nazev);
        if ($img == null) {
            $img = "false";
        }
        return $img;
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

        $validation = ValidationController::validateIfIsNull($request->channelName);
        if ($validation != "true") {
            return $validation;
        }
        $validation = ValidationController::validateIfIsNull($request->channelIsp);
        if ($validation != "true") {
            return $validation;
        }
        $validation = ValidationController::validateIPv4($request->channelMulticastKStb);
        if ($validation != "true") {
            return $validation;
        }
        $validation = ValidationController::validateIPv4($request->channelMulticast);
        if ($validation != "true") {
            return $validation;
        }

        $channelName = str_replace("/", "", $request->channelName);

        Channels::create([
            'nazev' => $channelName,
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


    /**
     *
     * fn pro získání PDF vsechn kanálů s IP k stb
     *
     * @return void
     */
    public function channelsPDF()
    {
        $channels = Channels::all();
        $pdf = PDF::loadView('channelPDF', compact('channels'));
        $pdf->save(storage_path() . '_filename.pdf');
        return $pdf->download('channelPDF.pdf');
    }


    /**
     * fn pro přidání k již existujímu záznamu nový záznam oddělený ","
     *
     * @return void
     */
    public static function addToExistRecordaNewOne($column, $columnData, $channelId)
    {
        $takeCurrentRecord = Channels::where('id', $channelId)->first();
        $currnetRecords = $takeCurrentRecord->$column;

        if ($takeCurrentRecord->$column != null) {
            $update = Channels::find($channelId);

            $update->$column = $currnetRecords . "," . $columnData;

            $update->save();

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Editace proběhla úspěšně"
            ];
        } else {
            $update = Channels::find($channelId);

            $update->$column =  $columnData;

            $update->save();

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Editace proběhla úspěšně"
            ];
        }
    }

    /**
     * fn pro ulození balíčku ke kanálu
     *
     * addIPTVPackage => false, pokud true, doplňuje se balíček
     *
     * @return void
     */
    public function savePackage(Request $request)
    {
        // zjisteni zda kanál jiz ma na sobe vazany nejaký balicek
        $channel = Channels::where('id', $request->channelId)->first();
        if ($channel->iptvPackage != null && $request->addIPTVPackage == false || $channel->iptvSubPackage != null && $request->addIPTVPackage == false) {
            $this->updateChannel("iptvPackage", null, $request->channelId);
            $this->updateChannel("iptvSubPackage", null, $request->channelId);
        }

        // vyhledání názvu balíčku zda existuje v hlavní nabídce,
        $infoOBalicku = IPTVpackageController::findPackageByName($request->iptvPackageName);
        if ($infoOBalicku["tag"] == "error") {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Balíček nebyl nalezen"
            ];
        } else if ($infoOBalicku["tag"] == "main" && $request->addIPTVPackage == false) {
            return $this->updateChannel("iptvPackage", $infoOBalicku["id"], $request->channelId);
        } else if ($infoOBalicku["tag"] == "main" && $request->addIPTVPackage == true) {
            return $this->addToExistRecordaNewOne("iptvPackage", $infoOBalicku["id"], $request->channelId);
        } else if ($infoOBalicku["tag"] != "main" && $request->addIPTVPackage == true) {
            return $this->addToExistRecordaNewOne("iptvSubPackage", $infoOBalicku["id"], $request->channelId);
        } else {
            return $this->updateChannel("iptvSubPackage", $infoOBalicku["id"], $request->channelId);
        }
    }


    /**
     * fn pro smazani programoveho balicku
     *
     * @param Request $request-channelId
     * @return void
     */
    public function removePackage(Request $request)
    {
        $channel = Channels::where('id', $request->channelId)->first();
        if ($channel->iptvPackage != null || $channel->iptvSubPackage != null) {
            $this->updateChannel("iptvPackage", null, $request->channelId);
            $this->updateChannel("iptvSubPackage", null, $request->channelId);

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Balíček byl z editován u kanálu"
            ];
        }
    }


    public function editNote(Request $request)
    {
        $this->updateChannel("poznamka", $request->channelNote, $request->channelId);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Poznámka byla změněna"
        ];
    }
}
