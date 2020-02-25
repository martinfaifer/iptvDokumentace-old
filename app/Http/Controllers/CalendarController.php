<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function currDate()
    {
        return date("Y-m-d");
    }
}
