<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKmlsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kmls', function (Blueprint $table) {
            $table->id('id');
            $table->string('path');
            $table->text('content');
            $table->bigInteger('field_id')->unsigned();
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
        Schema::drop('kmls');
    }
}
