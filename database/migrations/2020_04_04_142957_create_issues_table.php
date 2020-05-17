<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('title_id')->unsigned();
            $table->foreign('title_id')
                ->references('id')->on('titles')
                ->onDelete('cascade');
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->string('issue_number')->nullable();
            $table->integer('month_publication')->nullable();
            $table->integer('year_publication')->nullable();
            $table->integer('number_pages')->nullable();
            $table->string('isbn')->nullable();
            $table->longText('synopsis')->nullable();
            $table->string('image')->nullable();
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
        Schema::dropIfExists('issues');
    }
}
