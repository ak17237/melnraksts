<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Title');
            $table->dateTime('Datefrom');
            $table->dateTime('Dateto');
            $table->string('Address');
            $table->integer('Seatnumber');
            $table->integer('Tablenumber');
            $table->integer('Seatsontablenumber');
            $table->string('Anotation');
            $table->text('Description');
            $table->boolean('Melnraksts');
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
        Schema::dropIfExists('events');
    }
}
