<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class H265 extends Model
{
    protected $fillable = [
        'id_channel',
        'id_device',
        'K1080',
        'K720',
        'udpxy'
    ];
}
