<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CAModulChannel;

class CAModulChannelController extends Controller
{
    public function get()
    {
        return CAModulChannel::get();
    }
}
