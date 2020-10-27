<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class H264 extends Model
{
    protected $fillable = [
        'id_channel',
        'kodek',
        'chunk_Store_id',
        'id_device',
        'K1080',
        'K720',
        'K576',
        'K404',
        'm3u8_stb',
        'm3u8_HLS_kdekoliv',
        'm3u8_mobile',
        'udpxy'
    ];
}
