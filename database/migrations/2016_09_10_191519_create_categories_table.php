<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('Category', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('title')->default('');
            $table->integer('id__User')->unsigned();

            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('position', false, true);
            $table->integer('real_depth', false, true);

            $table->timestamps();

            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');

            $table->foreign('parent_id')
                ->references('id')
                ->on('Category')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('Category');
    }
}
