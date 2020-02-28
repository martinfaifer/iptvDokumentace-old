<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * fn pro validaci IPv4
     *
     * @param [type] $dataToValidate
     * @return void
     */
    public static function validateIPv4($dataToValidate)
    {
        if (filter_var($dataToValidate, FILTER_VALIDATE_IP)) {
            return "true";
        } else {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Neplatný formát IPv4",
            ];
            die;
        }
    }

    public static function validateIfIsNull($dataToValidate)
    {
        if ($dataToValidate == null) {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Pole nemůže být prázdné",
            ];
            die;
        } else {
            return "true";
        }
    }
}
