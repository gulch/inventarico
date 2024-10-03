<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('Item', function (Blueprint $table): void {
            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
        });

        Schema::table('Photo', function (Blueprint $table): void {
            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
        });

        Schema::table('Operation', function (Blueprint $table): void {
            $table->foreign('id__OperationType')->references('id')->on('OperationType')->onDelete('cascade');
        });

        Schema::table('Operation_Photo', function (Blueprint $table): void {
            $table->foreign('id__Photo')->references('id')->on('Photo')->onDelete('cascade');
            $table->foreign('id__Operation')->references('id')->on('Operation')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('Item', function (Blueprint $table): void {
            $table->dropForeign(['id__User']);
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Photo', function (Blueprint $table): void {
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Operation', function (Blueprint $table): void {
            $table->dropForeign(['id__OperationType']);
        });

        Schema::table('Operation_Photo', function (Blueprint $table): void {
            $table->dropForeign(['id__Operation']);
            $table->dropForeign(['id__Photo']);
        });

        Schema::table('Item_Operation', function (Blueprint $table): void {
            $table->dropForeign(['id__Operation']);
            $table->dropForeign(['id__Item']);
        });
    }
}
