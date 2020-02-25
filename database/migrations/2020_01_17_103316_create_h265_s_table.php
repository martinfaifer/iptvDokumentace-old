<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateH265STable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('h265_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_channel');
            $table->string('id_device');
            $table->string('K1080');
            $table->string('K720');
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
        Schema::dropIfExists('h265_s');
    }
}
