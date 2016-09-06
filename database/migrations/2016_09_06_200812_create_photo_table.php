<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Photo', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description');
            $table->integer('id__User')->unsigned();
            $table->timestamps();

            /*$table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Photo');
    }
}
