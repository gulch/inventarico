<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationToPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Operation_Photo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id__Photo')->unsigned();
            $table->integer('id__Operation')->unsigned();

            /*$table->foreign('id__Photo')->references('id')->on('Photo')->onDelete('cascade');
            $table->foreign('id__Operation')->references('id')->on('Operation')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Operation_Photo');
    }
}
