<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partit', function (Blueprint $table) {
            $table->id();
            $table->integer('jornada')->references('id')->on('jornada');
            $table->string('local');
            $table->string('visitant');
            $table->char('resultat', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partit');
    }
}
