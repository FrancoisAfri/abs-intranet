<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('stock_history', function (Blueprint $table) {
            $table->integer('user_allocated_id')->unsigned()->index()->nullable();
            $table->integer('balance_before')->nullable()->index();;
            $table->integer('balance_after')->nullable()->index();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_history', function (Blueprint $table) {
            $table->dropColumn('user_allocated_id');
            $table->dropColumn('balance_before');
            $table->dropColumn('balance_after');
        });
    }
}

