<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Item', function (Blueprint $table): void {
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
    public function down(): void
    {
        Schema::drop('Item');
    }
}
