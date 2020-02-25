<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlankomPortsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blankom_ports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('rf1')->nullable();
            $table->string('rf2')->nullable();
            $table->string('rf3')->nullable();
            $table->string('rf4')->nullable();
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
        Schema::dropIfExists('blankom_ports');
    }
}
