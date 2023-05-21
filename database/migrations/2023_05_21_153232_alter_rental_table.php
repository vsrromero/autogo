<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRentalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->dateTime('end_date')->nullable()->change();
            $table->integer('final_ml')->nullable()->change();
            $table->renameColumn('inicial_ml', 'initial_ml');
            $table->renameColumn('planed_end_date', 'planned_return_date');
            $table->renameColumn('start_date', 'starting_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rentals', function (Blueprint $table) {
            $table->renameColumn('starting_date', 'start_date');
            $table->renameColumn('planned_return_date', 'planed_end_date');
            $table->renameColumn('initial_ml', 'inicial_ml');
            $table->integer('final_ml')->nullable(false)->change();
            $table->dateTime('end_date')->nullable(false)->change();
        });
    }
}
