<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Isp_channel extends Model
{
    protected $fillable = [
        'id_isp', 'id_channel', 'ip'
    ];
}
