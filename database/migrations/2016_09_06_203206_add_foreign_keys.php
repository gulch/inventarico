<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Item', function (Blueprint $table) {
            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
            $table->foreign('id__Photo')->references('id')->on('Photo')->onDelete('cascade');
        });

        Schema::table('Photo', function (Blueprint $table) {
            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
        });

        Schema::table('Operation', function (Blueprint $table) {
            $table->foreign('id__OperationType')->references('id')->on('OperationType')->onDelete('cascade');
        });

        Schema::table('Operation_Photo', function (Blueprint $table) {
            $table->foreign('id__Photo')->references('id')->on('Photo')->onDelete('cascade');
            $table->foreign('id__Operation')->references('id')->on('Operation')->onDelete('cascade');
        });

        Schema::table('Item_Operation', function (Blueprint $table) {
            $table->foreign('id__Item')->references('id')->on('Item')->onDelete('cascade');
            $table->foreign('id__Operation')->references('id')->on('Operation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Item', function (Blueprint $table) {
            $table->dropForeign(['id__User']);
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Photo', function (Blueprint $table) {
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Operation', function (Blueprint $table) {
            $table->dropForeign(['id__OperationType']);
        });

        Schema::table('Operation_Photo', function (Blueprint $table) {
            $table->dropForeign(['id__Operation']);
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Item_Operation', function (Blueprint $table) {
            $table->dropForeign(['id__Operation']);
            $table->dropForeign(['id__Item']);
        });
    }
}
