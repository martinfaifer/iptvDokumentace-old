<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $fillable = [
        'nazev', 'ipKstb', 'multiplexer_id', 'device_id', 'blankom_rf', 'poznamka', 'isActive', 'device_backup_id', 'iptvPackage', 'iptvSubPackage'
    ];
}
