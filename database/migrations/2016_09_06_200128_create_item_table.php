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
            $table->string('title')->default('');
            $table->text('description')->nullable();
            $table->text('overview')->nullable();
            $table->integer('id__User')->unsigned();
            $table->integer('id__Photo')->unsigned()->default(0);
            $table->integer('id__Category')->unsigned();
            $table->timestamps();
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
