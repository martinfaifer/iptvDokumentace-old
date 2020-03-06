<?php

namespace App\Http\Controllers;

use App\Channels;
use App\IPTVpackage;
use App\IPTVsubPackage;
use Illuminate\Http\Request;


/**
 * Class pro IPTV balíčky, mini, basic, max ...
 * příplatkové balíčky jako hbo, filmbox ...
 * možnost přidání nových balíčků
 * přidání kanálu do balíčku, editace a jeho odebrání
 *
 */
class IPTVpackageController extends Controller
{

    /**
     * $request->packageName
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        $validation = ValidationController::validateIfIsNull($request->packageName);
        if ($validation != "true") {
            return $validation;
        }

        // Overení zda se jedná o příplatkový balicek
        if ($request->subPackage == "true") {
            $this->createSub($request->packageName);
        }

        IPTVpackage::create([
            'nazevBalicku' => $request->packageName,
        ]);
        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Založen nový programový balíček"
        ];
    }

    /**
     * fn pro získání všech balíčků včetně tech příplatkových
     *
     * @return void
     */
    public function getAll()
    {
        $dataHlavni = array();
        $dataPriplatkove = array();
        $hlavniBalicky = IPTVpackage::all();
        $priplatkoveBalicky = IPTVsubPackage::all();

        foreach ($hlavniBalicky as $hlavni) {
            $dataHlavni[] = array(

                'nazev' => $hlavni->nazevBalicku,
                'tag' => "hlavni"
            );
        }

        foreach ($priplatkoveBalicky as $priplatek) {
            $dataPriplatkove[] = array(

                'nazev' => $priplatek->subBalicek,
                'tag' => "true"
            );
        }

        return   array_merge($dataHlavni, $dataPriplatkove);
    }


    /**
     * fn pro založení příplatkového balícku
     *
     * @param [string] $nazevBalicku
     * @return void
     */
    public static function createSub($nazevBalicku)
    {
        IPTVsubPackage::created([
            'subBalicek' => $nazevBalicku,
        ]);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Založen nový programový balíček"
        ];
    }

    /**
     * statická fn pro vyhledání balícku dle názvu
     *
     * @param [string] $packageName
     * @return array
     */
    public static function findPackageByName($packageName)
    {
        $findMain = IPTVpackage::where('nazevBalicku', $packageName)->first();
        if ($findMain) {
            return [
                'id' => $findMain->id,
                'tag' => "main"
            ];
        } else {
            $findSub = IPTVsubPackage::where('subBalicek', $packageName)->first();
            if ($findSub) {
                return [
                    'id' => $findSub->id,
                    'tag' => "sub"
                ];
            } else {
                return [
                    'tag' => "error"
                ];
            }
        }
    }

    /**
     * staticka fn pro vyhledání názvu balícku, dle ID a toho zda je to hlavni nebo priplatkovy
     *
     * @param [string] $packageId
     * @param [string] $packageKategory
     * @return void
     */
    public static function findPackageById($packageId, $packageKategory)
    {
        if ($packageKategory == "main") {
            return IPTVpackage::where('id', $packageId)->first();
        } else {
            return  IPTVsubPackage::where('id', $packageId)->first();
        }
    }

    /**
     * fn na získání bšech kanálů patřící do daného balíčku
     *
     * @param Request $request->packageName
     * @return void
     */
    public function getChannelByPackage(Request $request)
    {
        // definice proměnné
        $data = array();
        $findfIfIsMain = IPTVpackage::where('nazevBalicku', $request->packageName)->first();
        if ($findfIfIsMain) {
            // hledání v základních programových balíčcích
            $channels = Channels::all();
            foreach ($channels as $channel) {
                if (strpos($channel->iptvPackage, strval($findfIfIsMain->id)) !== false) {
                    $data[] = array(
                        'id' => $channel->id,
                        'nazev' => $channel->nazev,
                        'ipKstb' => $channel->ipKstb
                    );
                }
            }
            // odeslání dat do frontendu
            return $data;
        } else {
            // prvni podminka nebyla splnena, hleda se tedy v priplatkovych baliccich
            $subPackage = IPTVsubPackage::where('subBalicek', $request->packageName)->first();

            $channels = Channels::all();
            foreach ($channels as $channel) {
                if (strpos($channel->iptvSubPackage, strval($subPackage->id)) !== false) {
                    $data[] = array(
                        'id' => $channel->id,
                        'nazev' => $channel->nazev,
                        'ipKstb' => $channel->ipKstb
                    );
                }
            }
        }
        // odeslání dat do frontendu
        return $data;
    }
}
