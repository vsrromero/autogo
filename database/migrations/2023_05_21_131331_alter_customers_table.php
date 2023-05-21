<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function(Blueprint $table){
            $table->string('phone', 20)->after('name')->nullable();
            $table->string('email', 100)->after('phone')->unique();
            $table->string('address', 500)->after('email');
            $table->string('postcode', 10)->after('address');
            $table->string('city', 50)->after('postcode');
            $table->string('county', 50)->after('city');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table( 'customers', function(Blueprint $table){
            $table->dropIfExists('phone');
            $table->dropIfExists('email');
            $table->dropIfExists('address');
            $table->dropIfExists('postcode');
            $table->dropIfExists('city');
            $table->dropIfExists('county');
        });
    }
}
