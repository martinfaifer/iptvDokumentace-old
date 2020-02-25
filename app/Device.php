<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'name',
        'vendor_id',
        'id_kategory',
        'ip',
        'login',
        'ssh_User',
        'poznamka',
        'CI_1',
        'RF1_dvb',
        'RF1_satelit',
        'RF1_freq',
        'RF1_polarizace',
        'RF1_Symbol',
        'RF1_fec',
        'CI_2',
        'RF2_dvb',
        'RF2_satelit',
        'RF2_freq',
        'RF2_polarizace',
        'RF2_Symbol',
        'RF2_fec',
        'CI_3',
        'RF3_dvb',
        'RF3_satelit',
        'RF3_freq',
        'RF3_polarizace',
        'RF3_Symbol',
        'RF3_fec',
        'CI_4',
        'RF4_dvb',
        'RF4_satelit',
        'RF4_freq',
        'RF4_polarizace',
        'RF4_Symbol',
        'RF4_fec',
        'FTE_dvb',
        'FTE_satelit',
        'FTE_freq',
        'FTE_polarizace',
        'FTE_Symbol',
        'FEC_fec',
        'CI_A',
        'CI_B'

    ];
}
