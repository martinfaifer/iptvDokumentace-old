<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelSchedulersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_schedulers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('channelId')->index();
            $table->string('start_day')->index();
            $table->string('start_time')->index();
            $table->string('end_day')->index();
            $table->string('end_time')->index();
            $table->string('every_day')->index();
            $table->longText('event');
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
        Schema::dropIfExists('channel_schedulers');
    }
}
