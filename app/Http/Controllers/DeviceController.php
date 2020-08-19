<?php

namespace App\Http\Controllers;

use App\Blankom_port;
use App\CAModulChannel;
use App\Channels;
use App\Device;
use App\H264;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    /**
     * sort dat dle specifikací prijimaců
     *
     * kategorie -> 2 => satelit
     * kategorie -> 3 => transcoder
     * kategorie -> 4 => Po IP
     * kategorie -> 5 => Linuxový přijímač
     *
     * @param [type] $deviceId
     * @return void
     */

    public static function sortDevice($deviceId)
    {
        $prijimac = DeviceController::DeviceInfo($deviceId);

        if (!$prijimac) {
            // nebylo nalezeno zarozeni, ktere odbavuje kanaly
            return [
                [
                    'status' => "KO",
                    'tag' => "KO"
                ]
            ];
        }

        switch ($prijimac->id_kategory) {

                // multiplexery
            case 1:
                foreach (DeviceCategorieController::findCategorie($prijimac->id_kategory) as $kategorie) {
                    $channels = ChannelsController::showChannelsByMultiplexer($prijimac->id);
                    return [
                        [
                            'id' => $prijimac->id,
                            'nazev' => $prijimac->name,
                            'ip' => $prijimac->ip,
                            'login' => $prijimac->login,
                            'poznamka' => $prijimac->poznamka,
                            'kategorie' => $kategorie->kategorie,
                            'tag' => "Multiplexer",
                            'disabled' => $prijimac->isActiv,
                            'channels' => $channels
                        ]
                    ];
                }
                break;

                //  satelitni prijmace
            case 2:
                switch ($prijimac->vendor_id) {

                        // Blankom
                    case 1:
                        $channelsData = array();
                        $channels = ChannelsController::showChannelsByDevice($prijimac->id);
                        foreach ($channels as $channel) {
                            $rfData = BlankomPortController::getChannelOnBlankom($channel->blankom_rf);
                            $channelsData[] = array(
                                'channelId' => $channel->id,
                                'nazev' => $channel->nazev,
                                'rf' => $rfData->rf
                            );
                        }
                        return [
                            [
                                'id' => $prijimac->id,
                                'tag' => "Blankom",
                                'status' => "OK_Blankom",
                                'nazev' => $prijimac->name,
                                'ip' => $prijimac->ip,
                                'login' => $prijimac->login,
                                'poznamka' => $prijimac->poznamka,
                                'CI_1' => $prijimac->CI_1,
                                'RF1_channels' => $prijimac->RF1_channels,
                                'RF1_dvb' => $prijimac->RF1_dvb,
                                'RF1_satelit' => $prijimac->RF1_satelit,
                                'RF1_freq' => $prijimac->RF1_freq,
                                'RF1_polarizace' => $prijimac->RF1_polarizace,
                                'RF1_Symbol' => $prijimac->RF1_Symbol,
                                'RF1_fec' => $prijimac->RF1_fec,
                                'CI_2' => $prijimac->CI_2,
                                'RF2_channels' => $prijimac->RF2_channels,
                                'RF2_dvb' => $prijimac->RF2_dvb,
                                'RF2_satelit' => $prijimac->RF2_satelit,
                                'RF2_freq' => $prijimac->RF2_freq,
                                'RF2_polarizace' => $prijimac->RF2_polarizace,
                                'RF2_Symbol' => $prijimac->RF2_Symbol,
                                'RF2_fec' => $prijimac->RF2_fec,
                                'CI_3' => $prijimac->CI_3,
                                'RF3_channels' => $prijimac->RF3_channels,
                                'RF3_dvb' => $prijimac->RF3_dvb,
                                'RF3_satelit' => $prijimac->RF3_satelit,
                                'RF3_freq' => $prijimac->RF3_freq,
                                'RF3_polarizace' => $prijimac->RF3_polarizace,
                                'RF3_Symbol' => $prijimac->RF3_Symbol,
                                'RF3_fec' => $prijimac->RF3_fec,
                                'CI_4' => $prijimac->CI_4,
                                'RF4_channels' => $prijimac->RF4_channels,
                                'RF4_dvb' => $prijimac->RF4_dvb,
                                'RF4_satelit' => $prijimac->RF4_satelit,
                                'RF4_freq' => $prijimac->RF4_freq,
                                'RF4_polarizace' => $prijimac->RF4_polarizace,
                                'RF4_symbol' => $prijimac->RF4_symbol,
                                'RF4_fec' => $prijimac->RF4_fec,
                                'channels' => $channelsData,
                                'disabled' => $prijimac->isActiv,
                            ]
                        ];
                        break;

                        // FTE
                    case 2:
                        $channels = ChannelsController::showChannelsByDevice($prijimac->id);

                        return [
                            [
                                'id' => $prijimac->id,
                                'tag' => "FTE",
                                'status' => "OK_FTE",
                                'nazev' => $prijimac->name,
                                'ip' => $prijimac->ip,
                                'poznamka' => $prijimac->poznamka,
                                'FTE_dvb' => $prijimac->FTE_dvb,
                                'FTE_freq' => $prijimac->FTE_freq,
                                'FTE_polarizace' => $prijimac->FTE_polarizace,
                                'FTE_satelit' => $prijimac->FTE_satelit,
                                'FTE_Symbol' => $prijimac->FTE_Symbol,
                                'FEC_fec' => $prijimac->FEC_fec,
                                'CI_A' => $prijimac->CI_A,
                                'CI_B' => $prijimac->CI_B,
                                'channels' => $channels,
                                'disabled' => $prijimac->isActiv,
                            ]
                        ];
                        break;

                        // cisco
                    case 6:
                        $channels = ChannelsController::showChannelsByDevice($prijimac->id);
                        return [
                            [
                                'id' => $prijimac->id,
                                'tag' => "PowerVu",
                                'nazev' => $prijimac->name,
                                'ip' => $prijimac->ip,
                                'poznamka' => $prijimac->poznamka,
                                'login' => $prijimac->login,
                                'channels' => $channels,
                                'disabled' => $prijimac->isActiv,
                            ]
                        ];
                        break;
                    default:
                        return [
                            [
                                'status' => "KO"
                            ]
                        ];
                }
                break;

                // transcodery
            case 3:
                switch ($prijimac->vendor_id) {

                        // NSP
                    case 3:
                        $channelsId = H264::where('id_device', $prijimac->id)->get();
                        foreach ($channelsId as $channelId) {
                            $thisChannel = Channels::where('id', $channelId->id_channel)->first();
                            $channel[] = array(
                                'channelId' => $thisChannel->id,
                                'nazev' => $thisChannel->nazev
                            );
                        }
                        return [
                            [
                                'id' => $prijimac->id,
                                'tag' => "Transcoder_NSP",
                                'nazev' => $prijimac->name,
                                'ip' => $prijimac->ip,
                                'login' => $prijimac->login,
                                'poznamka' => $prijimac->poznamka,
                                'channels' => $channel,
                                'disabled' => $prijimac->isActiv,
                            ]
                        ];
                        break;

                        // NVidia
                    case 4:
                        $channel = [];
                        $channelsId = H264::where('id_device', $prijimac->id)->get();
                        foreach ($channelsId as $channelId) {
                            $thisChannel = Channels::where('id', $channelId->id_channel)->first();
                            $channel[] = array(
                                'channelId' => $thisChannel->id,
                                'nazev' => $thisChannel->nazev
                            );
                        }
                        return [
                            [
                                'id' => $prijimac->id,
                                'tag' => "Transcoder_Grape",
                                'nazev' => $prijimac->name,
                                'ip' => $prijimac->ip,
                                'login' => $prijimac->login,
                                'poznamka' => $prijimac->poznamka,
                                'channels' => $channel,
                                'disabled' => $prijimac->isActiv,
                            ]
                        ];
                        break;
                    default:
                        return [
                            [
                                'tag' => "NoTranscoder"
                            ]
                        ];
                }

                break;

                // Kanály po IP
            case 4:
                $channels = ChannelsController::showChannelsByDevice($prijimac->id);
                return [
                    [
                        'tag' => "PoIP",
                        'prijem' => $prijimac->name,
                        'poznamka' => $prijimac->poznamka,
                        'channels' => $channels,
                        'disabled' => $prijimac->isActiv,
                    ]
                ];
                break;

                // Linux
            case 5:
                $channels = ChannelsController::showChannelsByDevice($prijimac->id);

                return [
                    [
                        'id' => $prijimac->id,
                        'tag' => "Linux",
                        'nazev' => $prijimac->name,
                        'ip' => $prijimac->ip,
                        'login' => $prijimac->login,
                        'ssh_user' => $prijimac->ssh_User,
                        'poznamka' => $prijimac->poznamka,
                        'channels' => $channels,
                        'disabled' => $prijimac->isActiv,
                    ]
                ];
                break;
            default:
                return [
                    [
                        'tag' => "KO"
                    ]
                ];
        }
    }


    public function get()
    {
        return Device::all();
    }

    public function getDeviceRoute(Request $request)
    {
        return $this->sortDevice($request->id);
    }

    public static function getDevice($deviceId)
    {
        return Device::find($deviceId);
    }


    public static function DeviceInfo($deviceId)
    {
        return Device::where('id', $deviceId)->first();
    }


    /**
     * funkce pro získání zařízení dle kategorie
     *
     * @param [type] $categoryId
     * @return void
     */
    public static function getByCategory($categoryId)
    {
        return Device::where('id_kategory', $categoryId)->get(['id', 'name']);
    }

    public function getDevices(Request $request)
    {
        return $this->getByCategory($request->kategorieId);
    }


    /**
     * fn pro ulození prijmu multicastu
     *
     *  $request->kategorieId => 2 (satelit), 3(Po Ip), 5(Linux)
     *  $request->deviceId => Pokud deviceId = 2 => Vše až na Blankom (4 - 10) posílá deviceId, pokud zařízení je Blankom je nutné poslat rfId
     *  $request->deviceId => Pokud deviceId = 4 => Zasílá se jen deviceId
     *  $request->deviceId => Pokud deviceId = 5 => zasílá se jen deviceId
     *  $request->camodulNumber -> zasílá se číslo modulu, není nutné aby existovalo, ne vždy jsou evidodavné, takže primárně se jedná o nové kanály
     *  $request->camodulId -> zasílá se ID výrobce CA modulu
     *  $request->camodulChannelId -> maximální počet dekryptovaných kanálů v modulu
     *
     * Při založení se zjištuje zda je jiz maodul použit, pokud je, zjištuje se maximální počet kanálů a porovnává se s aktuálním stavem, kdy je do toho již započítám tento nový kanál
     *
     *  $request->channelId (ID kanálu)
     *
     * @param Request $request
     * @return void
     */
    public function savePrijem(Request $request)
    {
        $column = "device_id";



        switch ($request->kategorieId) {
            case 4:
                ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                break;
            case 5:
                ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                break;
            case 2:
                $vendor = Device::where('id', $request->deviceId)->first();
                switch ($vendor->vendor_id) {
                        // Blankom
                    case 1:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        ChannelsController::updateChannel("blankom_rf", $request->rfId, $request->channelId);
                        ChannelsController::updateChannel("ci", $request->ci, $request->channelId);
                        if ($request->camodulId != '') {
                            if (Channels::where('ca_modul_number', $request->camodulNumber)->first()) {
                                // získání kolik kanálů je možné dekryptovat na modulu
                                $informaceOpoctuKanaluVmodulu = CAModulChannel::where('id', $request->camodulChannelId)->first();
                                if (Channels::where('ca_modul_number', $request->camodulNumber)->count() < $informaceOpoctuKanaluVmodulu->pocet_podporovanych_kanalu) {
                                    ChannelsController::updateChannel("ca_modul", $request->camodulId, $request->channelId);
                                    ChannelsController::updateChannel("ca_modul_number", $request->camodulNumber, $request->channelId);
                                    ChannelsController::updateChannel("max_ca_module_channels", $request->camodulChannelId, $request->channelId);
                                } else {
                                    return [
                                        'isAlert' => "isAlert",
                                        'stat' => "error",
                                        'msg' => "Maximální počet kanálů byl pessažen"
                                    ];
                                }
                            }
                        }
                        break;
                        // FTE
                    case 2:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        ChannelsController::updateChannel("ca_modul", $request->camodulId, $request->channelId);
                        ChannelsController::updateChannel("ca_modul_number", $request->camodulNumber, $request->channelId);
                        ChannelsController::updateChannel("max_ca_module_channels", $request->camodulChannelId, $request->channelId);
                        break;
                        // Nvidia
                    case 4:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                        // Neznámý výrobce
                    case 5:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                        // Cisco
                    case 6:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                    default:
                        return [
                            'isAlert' => "isAlert",
                            'stat' => "error",
                            'msg' => "Nepodařilo se z editovat zařízení, obraťte se na MF"
                        ];
                }
                break;
            default:
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Nepodařilo se z editovat zařízení, obraťte se na MF"
                ];
        }
    }


    /**
     * fn pro ulození založního prijimace
     *
     * @param Request $request
     * @return void
     */
    public function saveBackupPrijem(Request $request)
    {
        $column = "device_backup_id";



        switch ($request->kategorieId) {
            case 4:
                ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                break;
            case 5:
                ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                break;
            case 2:
                $vendor = Device::where('id', $request->deviceId)->first();
                switch ($vendor->vendor_id) {
                        // Blankom
                    case 1:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        ChannelsController::updateChannel("blankom_rf", $request->rfId, $request->channelId);
                        ChannelsController::updateChannel("ci", $request->ci, $request->channelId);
                        break;
                        // FTE
                    case 2:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                        // Nvidia
                    case 4:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                        // Neznámý výrobce
                    case 5:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                        // Cisco
                    case 6:
                        ChannelsController::updateChannel($column, $request->deviceId, $request->channelId);
                        break;
                    default:
                        return [
                            'isAlert' => "isAlert",
                            'stat' => "error",
                            'msg' => "Nepodařilo se z editovat zařízení, obraťte se na MF"
                        ];
                }
                break;
            default:
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Nepodařilo se z editovat zařízení, obraťte se na MF"
                ];
        }
    }

    public function deletePrijem(Request $request)
    {
        ChannelsController::updateChannel("device_id", "false", $request->channelId);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Přijímč byl odebrán"
        ];
    }

    public function deleteBackupPrijem(Request $request)
    {
        ChannelsController::updateChannel("device_backup_id", "false", $request->channelId);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Přijímč byl odebrán"
        ];
    }


    /**
     * obecna fn pro editaci zarizeni
     *
     * @return void
     */
    public static function editDevice($deviceId, $arrayData)
    {
        // ziskani stavajiich informaci o devicu
        $thisDevice = Device::where('id', $deviceId)->first();

        $deviceCategory = DeviceCategorieController::findCategorie($thisDevice->id_kategory);
        switch ($deviceCategory[0]->kategorie) {
            case "multiplexer":
                $update = Device::find($deviceId);


                $update->name = $arrayData["name"];
                $update->ip = $arrayData["ip"];
                $update->login = $arrayData["login"];
                $update->poznamka = $arrayData["poznamka"];


                $update->save();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo aktualizováno"
                ];
                break;
            case "satelitní přijímač":
                $vendor = DeviceVendorController::getVendor($thisDevice->vendor_id);
                switch ($vendor->vendor) {
                    case "Blankom":
                        switch ($arrayData["tag"]) {
                            case "basicEdit":
                                $update = Device::find($deviceId);

                                $update->name = $arrayData["name"];
                                $update->ip = $arrayData["ip"];
                                $update->login = $arrayData["login"];
                                $update->poznamka = $arrayData["poznamka"];

                                $update->save();

                                return [
                                    'isAlert' => "isAlert",
                                    'stat' => "success",
                                    'msg' => "Zařízení bylo aktualizováno"
                                ];

                                break;

                            case "RF":

                                $number = substr($arrayData["rf"], -1);

                                $dvb = "RF" . $number . "_dvb";
                                $satelit = "RF" . $number . "_satelit";
                                $freq = "RF" . $number . "_freq";
                                $polarizace = "RF" . $number . "_polarizace";
                                $symbol = "RF" . $number . "_Symbol";
                                $fec = "RF" . $number . "_fec";
                                $ci = "CI_" . $number;

                                $updateRF = Device::find($deviceId);

                                $updateRF->$dvb = $arrayData["dvb"];
                                $updateRF->$satelit = $arrayData["satelit"];
                                $updateRF->$freq = $arrayData["freq"];
                                $updateRF->$polarizace = $arrayData["polarizace"];
                                $updateRF->$symbol = $arrayData["symbol"];
                                $updateRF->$fec = $arrayData["fec"];
                                $updateRF->$ci = $arrayData["ci"];

                                $updateRF->save();

                                return [
                                    'isAlert' => "isAlert",
                                    'stat' => "success",
                                    'msg' => "Zařízení bylo aktualizováno"
                                ];

                                break;
                        }
                        break;
                        // fte
                    case "FTE":
                        switch ($arrayData["tag"]) {
                            case "basicEdit":

                                $update = Device::find($deviceId);

                                $update->name = $arrayData["name"];
                                $update->ip = $arrayData["ip"];
                                $update->poznamka = $arrayData["poznamka"];

                                $update->save();

                                return [
                                    'isAlert' => "isAlert",
                                    'stat' => "success",
                                    'msg' => "Zařízení bylo aktualizováno"
                                ];

                                break;

                            case "sat":
                                $update = Device::find($deviceId);

                                $update->FTE_dvb = $arrayData["dvb"];
                                $update->FTE_satelit = $arrayData["satelit"];
                                $update->FTE_freq = $arrayData["freq"];
                                $update->FTE_polarizace = $arrayData["polarizace"];
                                $update->FTE_Symbol = $arrayData["symbol"];
                                $update->FEC_fec = $arrayData["fec"];
                                $update->CI_A = $arrayData["ci_a"];
                                $update->CI_B = $arrayData["ci_b"];


                                $update->save();

                                return [
                                    'isAlert' => "isAlert",
                                    'stat' => "success",
                                    'msg' => "Zařízení bylo aktualizováno"
                                ];

                                break;
                        }
                        break;
                    case "cisco":
                        $update = Device::find($deviceId);

                        $update->name = $arrayData["name"];
                        $update->ip = $arrayData["ip"];
                        $update->login = $arrayData["login"];
                        $update->poznamka = $arrayData["poznamka"];

                        $update->save();

                        return [
                            'isAlert' => "isAlert",
                            'stat' => "success",
                            'msg' => "Zařízení bylo aktualizováno"
                        ];

                        break;
                }

                break;
                // transcoder
            case "transcoder":
                $vendor = DeviceVendorController::getVendor($thisDevice->vendor_id);
                // NSP
                switch ($vendor->vendor) {
                    case "nsp":
                        $update = Device::find($deviceId);

                        $update->name = $arrayData["name"];
                        $update->login = $arrayData["login"];
                        $update->ip = $arrayData["ip"];
                        $update->poznamka = $arrayData["poznamka"];

                        $update->save();

                        return [
                            'isAlert' => "isAlert",
                            'stat' => "success",
                            'msg' => "Zařízení bylo aktualizováno"
                        ];

                        break;

                    case "nvidia":
                        $update = Device::find($deviceId);

                        $update->name = $arrayData["name"];
                        $update->ip = $arrayData["ip"];
                        $update->poznamka = $arrayData["poznamka"];

                        $update->save();

                        return [
                            'isAlert' => "isAlert",
                            'stat' => "success",
                            'msg' => "Zařízení bylo aktualizováno"
                        ];

                        break;
                }
            case "Linux":
                $update = Device::find($deviceId);

                $update->name = $arrayData["name"];
                $update->login = $arrayData["login"];
                $update->ssh_User = $arrayData["ssh"];
                $update->ip = $arrayData["ip"];
                $update->poznamka = $arrayData["poznamka"];

                $update->save();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo aktualizováno"
                ];

                break;
            case "IP":
                $update = Device::find($deviceId);

                $update->name = $arrayData["name"];
                $update->poznamka = $arrayData["poznamka"];

                $update->save();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo aktualizováno"
                ];
                break;

                break;
        }
    }

    /**
     * fn pro editaci multiplexeru
     * $request->deviceId
     * $request->deviceName
     * $request->deviceIp
     * $request->deviceLogin
     * $request->deviceNote
     *
     * @param Request $request
     * @return void
     */
    public function editMultiplexer(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceLogin);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'login' => $request->deviceLogin,
            'poznamka' => $request->deviceNote
        );

        return $this->editDevice($request->deviceId,  $data);
    }


    /**
     * fn pro zakladni editaci blankomu
     * $request->deviceId
     * $request->deviceName
     * $request->deviceIp
     * $request->deviceLogin
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function basicEditBlankom(Request $request)
    {
        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceLogin);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'login' => $request->deviceLogin,
            'poznamka' => $request->deviceNote,
            'tag' => "basicEdit"

        );

        return $this->editDevice($request->deviceId,  $data);
    }


    /**
     * fn pro editace jednotlivych RF na blankomu
     * $request->deviceId
     * $request->rf
     * $request->rfDVB
     * $request->rfSatelit
     * $request->rfFreq
     * $request->rfSymbol
     * $request->rfFec
     * $request->rfCI
     * $request->rfPolarizace
     *
     * @param Request $request
     * @return void
     */
    public function editBlankomRf(Request $request)
    {

        $data = array(
            'dvb' => $request->rfDVB,
            'satelit' => $request->rfSatelit,
            'freq' => $request->rfFreq,
            'polarizace' => $request->rfPolarizace,
            'symbol' => $request->rfSymbol,
            'fec' => $request->rfFec,
            'ci' => $request->rfCI,
            'tag' => "RF",
            'rf' => $request->rf
        );

        return $this->editDevice($request->deviceId,  $data);
    }

    /**
     * fn pro zakladni editaci fte
     * $request->deviceId
     * $request->deviceName
     * $request->deviceIp
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function basicEditFte(Request $request)
    {
        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }


        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'poznamka' => $request->deviceNote,
            'tag' => "basicEdit"

        );

        return $this->editDevice($request->deviceId,  $data);
    }


    /**
     * fn pro editace jednotlivych RF na blankomu
     * $request->deviceId
     * $request->FteDVB
     * $request->FteSatelit
     * $request->FteFreq
     * $request->FteSymbol
     * $request->FteFec
     * $request->FteCI_A
     * $request->FteCI_AB
     * $request->FtePolarizace
     *
     * @param Request $request
     * @return void
     */
    public function editFteSat(Request $request)
    {
        $data = array(
            'dvb' => $request->FteDVB,
            'satelit' => $request->FteSatelit,
            'freq' => $request->FteFreq,
            'polarizace' => $request->FtePolarizace,
            'symbol' => $request->FteSymbol,
            'fec' => $request->FteFec,
            'ci_a' => $request->FteCI_A,
            'ci_b' => $request->FteCI_B,
            'tag' => "sat",
        );

        return $this->editDevice($request->deviceId,  $data);
    }

    /**
     * fn pro editace NSP
     * $request->deviceId
     * $request->deviceName
     * $request->deviceIp
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function editNsp(Request $request)
    {
        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceLogin);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'login' => $request->deviceLogin,
            'poznamka' => $request->deviceNote,
            'tag' => "basicEdit"

        );

        return $this->editDevice($request->deviceId,  $data);
    }

    /**
     * fn pro editace Grape Transcoderi
     * $request->deviceId
     * $request->deviceName
     * $request->deviceIp
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function editTranscoder(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'poznamka' => $request->deviceNote,
            'tag' => "basicEdit"

        );

        return $this->editDevice($request->deviceId,  $data);
    }


    /**
     * fn pro editace linuxu
     * $request->deviceId
     * $request->deviceName
     * $request->deviceLogin
     * $request->deviceIp
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function editLinux(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceLogin);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $login = explode(" / ", $request->deviceLogin);

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'login' => $request->deviceLogin,
            'ssh' => $login[0],
            'poznamka' => $request->deviceNote,
            'tag' => "basicEdit"

        );

        return $this->editDevice($request->deviceId,  $data);
    }


    /**
     * fn pro editace kanalu po Ip
     * $request->deviceId
     * $request->deviceName
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function editPoIp(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'poznamka' => $request->deviceNote,

        );

        return $this->editDevice($request->deviceId,  $data);
    }

    /**
     * fn pro editace kanalu po Ip
     * $request->deviceId
     * $request->deviceIp
     * $request->deviceLogin
     * $request->deviceName
     * $request->deviceNote
     * @param Request $request
     * @return void
     */
    public function editPowerVu(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceLogin);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        $data = array(
            'name' => $request->deviceName,
            'ip' => $request->deviceIp,
            'login' => $request->deviceLogin,
            'poznamka' => $request->deviceNote,
            'tag' => "powerVu"

        );

        return $this->editDevice($request->deviceId,  $data);
    }

    /**
     * fn pro smazání zařízení, ale jen pokud na něj nejsou vázané žádné kanály
     *
     * @param Request $request
     * @return void
     */
    public function removeDevice(Request $request)
    {

        // nalezeni kanalu, ktere maji vazbu na zarizeni -> vrati jedno pole  -> kategorie 1 = multiplexer , 2 satelitni prijimac
        $device = Device::where('id', $request->deviceId)->first();
        // multiplexer
        if ($device->id_kategory == "1") {
            if (ChannelsController::countChannels("multiplexer_id", $request->deviceId) > 0) {
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Zařízení má vazbu na kanály"
                ];
            } else {
                Device::find($request->deviceId)->delete();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo smazáno"
                ];
            }
            // satelitni prijimace
        } elseif ($device->id_kategory == "2") {
            if (ChannelsController::countChannels("device_id", $request->deviceId) > 0) {
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Zařízení má vazbu na kanály"
                ];
            } else {
                Device::find($request->deviceId)->delete();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo smazáno"
                ];
            }
            // Po IP
        } elseif ($device->id_kategory == "4") {
            if (ChannelsController::countChannels("device_id", $request->deviceId) > 0) {
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Zařízení má vazbu na kanály"
                ];
            } else {
                Device::find($request->deviceId)->delete();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo smazáno"
                ];
            }
            // Linux
        } elseif ($device->id_kategory == "5") {
            if (ChannelsController::countChannels("device_id", $request->deviceId) > 0) {
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Zařízení má vazbu na kanály"
                ];
            } else {
                Device::find($request->deviceId)->delete();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo smazáno"
                ];
            }
            // transcodery
        } else {
            // Hledání zda není na H264
            $h264Channels = H264Controller::countChannels("id_device", $request->deviceId);
            // hledani h265
            $h265Channels = H265Controller::countChannels("id_device", $request->deviceId);
            if ($h264Channels == 0 && $h265Channels == 0) {
                Device::find($request->deviceId)->delete();

                return [
                    'isAlert' => "isAlert",
                    'stat' => "success",
                    'msg' => "Zařízení bylo smazáno"
                ];
            } else {
                return [
                    'isAlert' => "isAlert",
                    'stat' => "error",
                    'msg' => "Zařízení má vazbu na kanály"
                ];
            }
        }
    }


    /**
     * fn pro zalozeni noveho zarizeni
     *
     * @param Request $request
     * $request->deviceName
     * $request->deviceVendor
     * $request->deviceKategorie
     * @return array
     */
    public function create(Request $request)
    {

        $validation = ValidationController::validateIfIsNull($request->deviceName);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceVendor);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIfIsNull($request->deviceKategorie);
        if ($validation != "true") {
            return $validation;
        }

        $validation = ValidationController::validateIPv4($request->deviceIp);
        if ($validation != "true") {
            return $validation;
        }

        // overeni zda jiz zarizeni s danou IP neexistuje
        $checkIfExist = Device::where('ip', $request->deviceIp)->first();
        if ($checkIfExist == "false") {
            Device::create([
                'name' => $request->deviceName,
                'vendor_id' => $request->deviceVendor,
                'id_kategory' => $request->deviceKategorie,
                'ip' => $request->deviceIp
            ]);

            $overeni = Device::where('ip', $request->deviceIp)->first();

            return [
                'isAlert' => "isAlert",
                'stat' => "success",
                'msg' => "Zařízení bylo založeno",
                'id' => $overeni->id
            ];
        } else {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Zařízení s touto IP je již založeno",
            ];
        }
    }
}
