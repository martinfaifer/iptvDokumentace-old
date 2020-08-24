<?php

namespace App\Http\Controllers;

use App\Channels;
use App\Device;
use Illuminate\Http\Request;

class SearchController extends Controller
{


    /**
     * fn pro vyhledávíní v dokumenatci
     *
     * @return void
     */
    public function search()
    {
        foreach (Channels::get(['id', 'nazev', 'ipKstb']) as $channel) {
            $searchData[] = array(
                'description' => $channel->nazev,
                'value' => "/channel/" . $channel->id
            );

            $searchData[] = array(
                'description' => $channel->ipKstb,
                'value' => "/channel/" . $channel->id
            );
        }

        foreach (Device::get(['id', 'name', 'ip']) as $device) {

            $searchData[] = array(
                'description' => $device->name,
                'value' => "/device/" . $device->id
            );

            $searchData[] = array(
                'description' => $device->ip,
                'value' => "/device/" . $device->id
            );
        }

        return response()->json($searchData);
    }
}
