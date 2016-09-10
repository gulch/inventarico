<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdUserColumnToOperationtypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('OperationType', function (Blueprint $table) {
            $table->addColumn('integer', 'id__User', ['unsigned' => true]);
            $table->foreign('id__User')->references('id')->on('User')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('OperationType', function (Blueprint $table) {
            $table->removeColumn('id__User');
            $table->dropForeign('id__User');
        });
    }
}
