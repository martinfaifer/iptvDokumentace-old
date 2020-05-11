<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CAModul;

class CAModulController extends Controller
{
    public function get()
    {
        return CAModul::get();
    }
}
