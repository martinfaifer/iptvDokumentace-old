<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelperController extends Controller
{
    public static function checkIfNull($data)
    {
        if ($data != NULL) {
            return $data;
        } else {
            return "false";
        }
    }
}
