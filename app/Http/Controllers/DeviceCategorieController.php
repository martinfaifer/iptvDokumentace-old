<?php

namespace App\Http\Controllers;

use App\Device_categorie;
use Illuminate\Http\Request;

class DeviceCategorieController extends Controller
{
    public static function findCategorie($id)
    {
        return Device_categorie::where('id', $id)->get('kategorie');
    }


    public function getMultiplexer()
    {
        return DeviceController::getByCategory("1");
    }

    public function getPrijem()
    {
        return Device_categorie::where('id', "2")->orWhere('id', "4")->orWhere('id', "5")->get();
    }

    public function getTranscoder()
    {
        return DeviceController::getByCategory("3");
    }

    public function all()
    {
        return Device_categorie::all();
    }
}
