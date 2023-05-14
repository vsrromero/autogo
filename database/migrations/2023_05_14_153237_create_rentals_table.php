<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('car_id');
            $table->dateTime('start_date');
            $table->dateTime('planed_end_date');
            $table->dateTime('end_date');
            $table->float('value', 8,2);
            $table->integer('inicial_ml');
            $table->integer('final_ml');
            $table->timestamps();
    
            //foreign key (constraints)
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('car_id')->references('id')->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
            //drop foreign key
            Schema::table('rentals', function (Blueprint $table) {
                $table->dropForeign('rentals_customer_id_foreign');
                $table->dropForeign('rentals_car_id_foreign');
            });
        Schema::dropIfExists('rentals');
    }
}
