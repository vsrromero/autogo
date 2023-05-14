<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id');
            $table->string('reg', 10)->unique();
            $table->boolean('available');
            $table->integer('ml');
            $table->timestamps();
    
            //foreign key (constraints)
            $table->foreign('version_id')->references('id')->on('versions');
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
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign('cars_version_id_foreign');
        });
        Schema::dropIfExists('cars');
    }
}
