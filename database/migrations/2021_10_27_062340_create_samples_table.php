<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSamplesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('point_id')->unsigned();
            $table->timestamp('date_selected')->nullable();
            $table->timestamp('date_received')->nullable();
            $table->integer('quantity')->unsigned();
            $table->string('passed');
            $table->string('accepted');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('point_id')->references('id')->on('points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('samples');
    }
}
