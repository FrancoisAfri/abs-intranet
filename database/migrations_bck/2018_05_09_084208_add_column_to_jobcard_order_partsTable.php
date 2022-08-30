<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToJobcardOrderPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
       Schema::table('jobcard__order_parts', function (Blueprint $table) {
            $table->integer('avalaible_transaction')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard__order_parts', function (Blueprint $table) {
            $table->dropColumn('avalaible_transaction');
        });
    }
}