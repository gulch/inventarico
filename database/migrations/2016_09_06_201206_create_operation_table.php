<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateOperationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Operation', function (Blueprint $table): void {
            $table->increments('id');
            $table->integer('id__OperationType')->unsigned();
            $table->timestamp('operated_at')->nullable();
            $table->enum('condition', ['NEW', 'USED'])->default('NEW');
            $table->float('price')->default(0);
            $table->enum('currency', ['UAH', 'USD', 'EUR'])->default('UAH');
            $table->text('note')->nullable();
            $table->integer('id__User')->unsigned();
            $table->timestamps();

            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('Operation');
    }
}
