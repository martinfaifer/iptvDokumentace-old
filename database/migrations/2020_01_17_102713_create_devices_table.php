<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('id_kategory')->nullable();
            $table->string('id_channel');
            $table->string('ip');
            $table->string('login');
            $table->string('remoteAccess')->index();
            $table->string('vendor_id')->index()->nullable();
            $table->string('ssh_User')->nullable();
            $table->string('poznamka')->nullable();
            $table->string('CI_1')->nullable();
            $table->string('RF1_dvb')->nullable();
            $table->string('RF1_satelit')->nullable();
            $table->string('RF1_freq')->nullable();
            $table->string('RF1_polarizace')->nullable();
            $table->string('RF1_Symbol')->nullable();
            $table->string('RF1_fec')->nullable();
            $table->string('CI_2')->nullable();
            $table->string('RF2_dvb')->nullable();
            $table->string('RF2_satelit')->nullable();
            $table->string('RF2_freq')->nullable();
            $table->string('RF2_polarizace')->nullable();
            $table->string('RF2_Symbol')->nullable();
            $table->string('RF2_fec')->nullable();
            $table->string('CI_3')->nullable();
            $table->string('RF3_dvb')->nullable();
            $table->string('RF3_satelit')->nullable();
            $table->string('RF3_freq')->nullable();
            $table->string('RF3_polarizace')->nullable();
            $table->string('RF3_Symbol')->nullable();
            $table->string('RF3_fec')->nullable();
            $table->string('CI_4')->nullable();
            $table->string('RF4_dvb')->nullable();
            $table->string('RF4_satelit')->nullable();
            $table->string('RF4_freq')->nullable();
            $table->string('RF4_polarizace')->nullable();
            $table->string('RF4_Symbol')->nullable();
            $table->string('RF4_fec')->nullable();
            $table->string('FTE_dvb')->nullable();
            $table->string('FTE_satelit')->nullable();
            $table->string('FTE_freq')->nullable();
            $table->string('FTE_polarizace')->nullable();
            $table->string('FTE_Symbol')->nullable();
            $table->string('FEC_fec')->nullable();
            $table->string('CI_A')->nullable();
            $table->string('CI_B')->nullable();

            $table->string('titan_mode')->nullable();
            $table->string('titan_constelation')->nullable();
            $table->string('titan_freq')->nullable();
            $table->string('titan_tuner')->nullable();
            $table->string('titan_lnc')->nullable();
            $table->string('titan_SymbolRate')->nullable();
            $table->string('titan_spektrum')->nullable();
            $table->string('titan_satelit')->nullable();

            $table->string('remoteAccess')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
