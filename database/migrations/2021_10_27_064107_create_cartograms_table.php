<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartogramsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cartograms', function (Blueprint $table) {
            $table->id('id');
            $table->bigInteger('field_id')->unsigned();
            $table->enum('status', ['pending','completed']);
            $table->string('access_url')->nullable();
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
        Schema::drop('cartograms');
    }
}
