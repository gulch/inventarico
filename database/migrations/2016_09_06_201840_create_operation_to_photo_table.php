<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateOperationToPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Operation_Photo', function (Blueprint $table): void {
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
    public function down(): void
    {
        Schema::drop('Operation_Photo');
    }
}
