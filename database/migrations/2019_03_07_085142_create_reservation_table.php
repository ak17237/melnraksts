<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id',false,true)->length(11);
            $table->integer('EventID',false,true)->length(11);
            $table->integer('Tickets',false,false)->length(11);
            $table->integer('Seats',false,false)->length(11)->default(0);
            $table->integer('TableNr',false,false)->length(11)->default(0);
            $table->integer('TableSeats',false,false)->length(11)->default(0);
            $table->string('Transport')->default(0);
            $table->string('QRcode',50)->nullable();
            $table->tinyInteger('Editable',false,false)->default(0);

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('EventID')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation');
    }
}
