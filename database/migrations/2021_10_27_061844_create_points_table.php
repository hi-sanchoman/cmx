<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('subpolygon_id')->unsigned();
            $table->string('lat');
            $table->string('lon');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subpolygon_id')->references('id')->on('subpolygons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('points');
    }
}
