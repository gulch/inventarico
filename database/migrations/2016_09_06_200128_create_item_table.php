<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Item', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');
            $table->text('overview');
            $table->integer('id__User')->unsigned();
            $table->integer('id__Photo')->unsigned();
            $table->timestamps();

            /*$table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
            $table->foreign('id__Photo')->references('id')->on('Photo')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Item');
    }
}
