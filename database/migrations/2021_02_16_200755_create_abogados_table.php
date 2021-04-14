<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbogadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abogados', function (Blueprint $table) {
            $table->integer('id');
            $table->timestamps();
            $table->integer('active');
            $table->string('email');
            $table->string('fullname');
            $table->integer('gender');
            $table->integer('identification');
            $table->string('address');
            $table->integer('document1');
            $table->string('university')->nullable();
            $table->string('license');
            $table->integer('experience');
            $table->string('years')->nullable();
            $table->integer('investigate');
            $table->integer('pleasures');
            $table->string('pleasures_other')->nullable();
            $table->string('price');
            $table->string('cv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abogados');
    }
}
