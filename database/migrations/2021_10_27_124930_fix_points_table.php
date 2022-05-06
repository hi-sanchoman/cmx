<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('points', function (Blueprint $table) {
            // $table->dropColumn('subpolygon_id');
            $table->dropForeign(['subpolygon_id']);

            $table->bigInteger('polygon_id')->unsigned();
            $table->foreign('polygon_id')->references('id')->on('polygons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('points', function (Blueprint $table) {
            // $table->dropColumn('polygon_id');
            $table->dropForeign(['polygon_id']);

            $table->bigInteger('subpolygon_id')->unsigned();
            $table->foreign('subpolygon_id')->references('id')->on('subpolygons');
        });
    }
}
