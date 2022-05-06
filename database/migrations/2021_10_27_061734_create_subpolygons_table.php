<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubpolygonsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subpolygons', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('polygon_id')->unsigned();
            $table->text('geometry');
            $table->timestamps();
            $table->softDeletes();
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
        Schema::drop('subpolygons');
    }
}
