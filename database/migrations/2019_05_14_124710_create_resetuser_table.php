<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResetuserTable extends Migration
{
    public function boot()
{
    Schema::defaultStringLength(191);
}
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resetusers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('First_name');
            $table->string('Last_name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('resetuser');
    }
}
