<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->double('p')->nullable();
            $table->double('k')->nullable();    // калий
            $table->double('s')->nullable();       // сера
            $table->double('humus')->nullable();
            $table->double('humus_mass')->nullable();    
            $table->double('no3')->nullable();
            $table->double('ph')->nullable();

            $table->double('b')->nullable();
            $table->double('fe')->nullable();
            $table->double('cu')->nullable();
            $table->double('zn')->nullable();
            $table->double('na')->nullable();
            $table->double('mn')->nullable();
            $table->double('calcium')->nullable();
            $table->double('magnesium')->nullable();
            $table->double('salinity')->nullable();
            $table->double('absorbed_sum')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->dropColumn('p');
            $table->dropColumn('k');
            $table->dropColumn('s');
            $table->dropColumn('humus');
            $table->dropColumn('humus_mass');
            $table->dropColumn('no3');
            $table->dropColumn('ph');
            $table->dropColumn('b');
            $table->dropColumn('fe');
            $table->dropColumn('cu');
            $table->dropColumn('zn');
            $table->dropColumn('mn');
            $table->dropColumn('na');
            $table->dropColumn('calcium');
            $table->dropColumn('magnesium');
            $table->dropColumn('salinity');
            $table->dropColumn('absorbed_sum');
        });
    }
}
