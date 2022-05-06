<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFieldsResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            $table->renameColumn('value1', 'humus');
            $table->renameColumn('value2', 'ph');
            $table->renameColumn('value3', 'no3');
            $table->renameColumn('value4', 'p');
            $table->renameColumn('value5', 'k');
            $table->renameColumn('value6', 's');
            
            $table->renameColumn('value7', 'b');
            $table->renameColumn('value8', 'fe');
            $table->renameColumn('value9', 'mn');
            $table->renameColumn('value10', 'cu');
            $table->renameColumn('value11', 'zn');
            $table->renameColumn('value12', 'na');
            $table->renameColumn('value13', 'calcium');

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
        Schema::table('results', function (Blueprint $table) {
            $table->renameColumn('humus', 'value1');
            $table->renameColumn('ph', 'value2');
            $table->renameColumn('no3', 'value3');
            $table->renameColumn('p', 'value4');
            $table->renameColumn('k', 'value5');
            $table->renameColumn('s', 'value6');
            
            $table->renameColumn('b', 'value7');
            $table->renameColumn('fe', 'value8');
            $table->renameColumn('mn', 'value9');
            $table->renameColumn('cu', 'value10');
            $table->renameColumn('zn', 'value11');
            $table->renameColumn('na', 'value12');
            $table->renameColumn('calcium', 'value13');

            $table->dropColumn('magnesium');
            $table->dropColumn('salinity');
            $table->dropColumn('absorbed_sum');
        });
    }
}
