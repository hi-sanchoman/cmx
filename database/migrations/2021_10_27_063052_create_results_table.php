<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('sample_id')->unsigned();
            $table->string('passed');
            $table->string('accepted');
            $table->double('value1')->nullable();
            $table->double('value2')->nullable();
            $table->double('value3')->nullable();
            $table->double('value4')->nullable();
            $table->double('value5')->nullable();
            $table->double('value6')->nullable();
            $table->double('value7')->nullable();
            $table->double('value8')->nullable();
            $table->double('value9')->nullable();
            $table->double('value10')->nullable();
            $table->double('value11')->nullable();
            $table->double('value12')->nullable();
            $table->double('value13')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sample_id')->references('id')->on('samples');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('results');
    }
}
