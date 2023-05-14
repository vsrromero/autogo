<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->string('name', 30);
            $table->string('image', 100)->nullable();
            $table->integer('number_of_doors');
            $table->integer('seats');
            $table->boolean('airbags');
            $table->boolean('abs');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('brand_id')->references('id')->on('brands');
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
        Schema::table('versions', function (Blueprint $table) {
            $table->dropForeign('versions_brand_id_foreign');
        });
        Schema::dropIfExists('versions');
    }
}
