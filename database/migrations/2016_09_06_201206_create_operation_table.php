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
            $table->enum('condition', ['NEW', 'USED'])->default('NEW');
            $table->float('price')->default(0);
            $table->text('note')->nullable();
            $table->integer('id__OperationType')->unsigned();
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
        Schema::drop('Operation');
    }
}
