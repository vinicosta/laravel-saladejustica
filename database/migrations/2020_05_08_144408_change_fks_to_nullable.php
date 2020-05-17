<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFksToNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('titles', function (Blueprint $table) {
            $table->dropForeign(['publisher_id']);
            $table->dropForeign(['periodicity_id']);
            $table->dropForeign(['size_id']);
            $table->dropForeign(['genre_id']);
            $table->dropForeign(['subgenre_id']);
        });

        Schema::table('titles', function (Blueprint $table) {
            $table->dropColumn('publisher_id');
            $table->dropColumn('periodicity_id');
            $table->dropColumn('size_id');
            $table->dropColumn('genre_id');
            $table->dropColumn('subgenre_id');
        });

        Schema::table('titles', function (Blueprint $table) {
            $table->bigInteger('publisher_id')->unsigned()->after('type_id')->nullable();
            $table->foreign('publisher_id')
                ->references('id')->on('publishers')
                ->onDelete('cascade');
            $table->bigInteger('periodicity_id')->unsigned()->after('publisher_id')->nullable();
            $table->foreign('periodicity_id')
                ->references('id')->on('periodicities')
                ->onDelete('cascade');
            $table->bigInteger('size_id')->unsigned()->after('periodicity_id')->nullable();
            $table->foreign('size_id')
                ->references('id')->on('sizes')
                ->onDelete('cascade');
            $table->bigInteger('genre_id')->unsigned()->after('size_id')->nullable();
            $table->foreign('genre_id')
                ->references('id')->on('genres')
                ->onDelete('cascade');
            $table->bigInteger('subgenre_id')->unsigned()->after('genre_id')->nullable();
            $table->foreign('subgenre_id')
                ->references('id')->on('subgenres')
                ->onDelete('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('titles', function (Blueprint $table) {
            //
        });
    }
}
