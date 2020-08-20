<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nazev');
            $table->string('iptvPackage');
            $table->string('iptvSubPackage');
            $table->string('ipKstb')->index();
            $table->longText('dohledUrl');
            $table->string('multiplexer_id')->index();
            $table->string('device_id')->index();
            $table->string('device_backup_id');
            $table->string('blankom_rf');
            $table->string('ca_modul')->index();
            $table->string('ci')->index();
            $table->integer('ca_modul_number')->index();
            $table->string('max_ca_module_channels')->index();
            $table->string('poznamka');
            $table->string('tags')->nullable();
            $table->string('isActive')->index();
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
        Schema::dropIfExists('channels');
    }
}
