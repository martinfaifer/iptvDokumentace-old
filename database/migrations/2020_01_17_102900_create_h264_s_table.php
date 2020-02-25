<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH264STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h264_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_channel');
            $table->string('kodek');
            $table->string('chunk_Store_id');
            $table->string('id_device');
            $table->string('K1080')->nullable();
            $table->string('K720')->nullable();
            $table->string('K576')->nullable();
            $table->string('K270')->nullable();
            $table->string('K404')->nullable();
            $table->string('m3u8_stb');
            $table->string('m3u8_HLS_kdekoliv');
            $table->string('m3u8_mobile');
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
        Schema::dropIfExists('h264_s');
    }
}
