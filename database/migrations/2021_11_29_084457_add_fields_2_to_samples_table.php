<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFields2ToSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples', function (Blueprint $table) {
            $table->double('na_x2')->nullable();
            $table->double('calcium_v1')->nullable();
            $table->double('calcium_v2')->nullable();
            $table->double('calcium_c')->nullable();
            $table->double('magnesium_v1')->nullable();
            $table->double('magnesium_v2')->nullable();
            $table->double('magnesium_c')->nullable();
            $table->double('absorbed_sum_v')->nullable();
            $table->double('absorbed_sum_m')->nullable();
            $table->double('absorbed_sum_c')->nullable();
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
            $table->dropColumn('na_x2');
            $table->dropColumn('calcium_v1');
            $table->dropColumn('calcium_v2');
            $table->dropColumn('calcium_c');
            $table->dropColumn('magnesium_v1');
            $table->dropColumn('magnesium_v2');
            $table->dropColumn('magnesium_c');
            $table->dropColumn('absorbed_sum_v');
            $table->dropColumn('absorbed_sum_m');
            $table->dropColumn('absorbed_sum_c');
        });
    }
}
