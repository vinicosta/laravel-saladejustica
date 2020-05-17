<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMonthAndYearToDatePublication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->date('date_publication')->nullable()->after('issue_number');
            $table->dropColumn('month_publication');
            $table->dropColumn('year_publication');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('issues', function (Blueprint $table) {
            $table->integer('month_publication')->nullable()->after('issue_number');
            $table->integer('year_publication')->nullable()->after('month_publication');
            $table->dropColumn('date_publication');
        });
    }
}
