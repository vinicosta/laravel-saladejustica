<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReadingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reading', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('title_id')->unsigned();
            $table->foreign('title_id')
                ->references('id')->on('titles')
                ->onDelete('cascade');

            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::dropIfExists('reading');
    }
}
