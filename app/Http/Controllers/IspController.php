<?php

namespace App\Http\Controllers;

use App\Isp;
use Illuminate\Http\Request;

class IspController extends Controller
{

    public function get()
    {
        return Isp::all();
    }

    public static function getIsp($ispId)
    {
        return Isp::where('id', $ispId)->first();
    }
}
