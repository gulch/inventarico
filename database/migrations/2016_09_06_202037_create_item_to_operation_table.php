<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemToOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Item_Operation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id__Item')->unsigned();
            $table->integer('id__Operation')->unsigned();

            /*$table->foreign('id__Item')->references('id')->on('Item')->onDelete('cascade');
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
        Schema::drop('Item_Operation');
    }
}
