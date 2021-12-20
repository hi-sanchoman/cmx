<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('field_id')->unsigned();
            $table->timestamp('date');
            $table->enum('status', ['pending','started','completed'])->default('pending');
            $table->timestamp('date_completed')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('field_id')->references('id')->on('fields');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('trips');
    }
}
