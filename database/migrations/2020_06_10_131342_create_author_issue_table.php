<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorIssueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('author_issue', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('issue_id')->unsigned();
            $table->foreign('issue_id')
                ->references('id')->on('issues')
                ->onDelete('cascade');

            $table->bigInteger('author_id')->unsigned();
            $table->foreign('author_id')
                ->references('id')->on('authors')
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
        Schema::dropIfExists('author_issue');
    }
}
