<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
    protected $fillable = [
        'nazev', 'ipKstb', 'multiplexer_id', 'device_id', 'blankom_rf', 'poznamka', 'isActive', 'device_backup_id', 'iptvPackage', 'iptvSubPackage', 'ca_modul', 'max_ca_module_channels', 'ca_modul_number', 'ci', 'dohledUrl', 'tags', 'pathToReboot'
    ];
}
