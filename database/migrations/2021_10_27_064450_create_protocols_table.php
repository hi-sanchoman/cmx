<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtocolsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('protocols', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('cartogram_id')->unsigned();
            $table->string('path');
            $table->string('access_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('cartogram_id')->references('id')->on('cartograms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('protocols');
    }
}
