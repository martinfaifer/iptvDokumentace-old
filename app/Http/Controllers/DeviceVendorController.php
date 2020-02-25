<?php

namespace App\Http\Controllers;

use App\DeviceVendor;
use Illuminate\Http\Request;

class DeviceVendorController extends Controller
{
    public static function getVendor($vendorId)
    {
        return DeviceVendor::where('id', $vendorId)->first('vendor');
    }

    public function all()
    {
        return DeviceVendor::all();
    }
}
