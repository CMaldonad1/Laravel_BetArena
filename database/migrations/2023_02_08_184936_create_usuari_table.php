<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuari', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('password');
            $table->string('nom');
            $table->string('cognom')->nullable();
            $table->integer('admin');
            $table->integer('validat');
            $table->integer('pswrdreset');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuari');
    }
}
