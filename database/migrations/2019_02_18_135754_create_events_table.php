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
            $table->string('Title',250)->nullable();
            $table->date('Datefrom')->nullable();
            $table->date('Dateto')->nullable();
            $table->string('Address',500)->nullable();
            $table->integer('Tickets',11)->nullable();
            $table->integer('Seatnumber',11)->nullable();
            $table->integer('Tablenumber',11)->nullable();
            $table->integer('Seatsontablenumber',11)->nullable();
            $table->string('Anotation',250)->nullable();
            $table->text('Description')->nullable();
            $table->tinyInteger('Melnraksts')->default(0);
            $table->tinyInteger('VIP')->default(0);
            $table->tinyInteger('Editable')->default(0);
            $table->string('imgextension',50)->nullable();
            $table->integer('user_id',11)->unsigned();
            $table->string('linkcode',50)->default($show);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
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
