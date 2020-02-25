<?php

namespace App\Http\Controllers;

use App\NotificationAlertNew;
use Illuminate\Http\Request;

class NotificationAlertNewController extends Controller
{

    public static function getInfo($userId)
    {
        return NotificationAlertNew::where('user_id', $userId)->first();
    }

    public function submitNotification(Request $request)
    {
        NotificationAlertNew::create([
            'user_id' => $request->userId,
        ]);
    }
}
