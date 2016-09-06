<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Operation', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('condition', ['NEW', 'USED']);
            $table->float('price');
            $table->text('note');
            $table->integer('id__OperationType')->unsigned();
            $table->timestamps();

            /*$table->foreign('id__OperationType')->references('id')->on('OperationType')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('Operation');
    }
}
