<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChannelScheduler extends Model
{
    protected $fillable = [
        'channelId',
        'start_day',
        'start_time',
        'end_day',
        'end_time',
        'every_day',
        'event'
    ];
}
