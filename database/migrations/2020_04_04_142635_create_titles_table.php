<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTitlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('type_id');
            $table->bigInteger('publisher_id')->unsigned();
            $table->foreign('publisher_id')
                ->references('id')->on('publishers')
                ->onDelete('cascade');
            $table->bigInteger('periodicity_id')->unsigned();
            $table->foreign('periodicity_id')
                ->references('id')->on('periodicities')
                ->onDelete('cascade');
            $table->bigInteger('size_id')->unsigned();
            $table->foreign('size_id')
                ->references('id')->on('sizes')
                ->onDelete('cascade');
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
        Schema::dropIfExists('titles');
    }
}
