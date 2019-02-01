<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsStockRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_stocks', function ($table) {
            $table->string('collection_note')->unsigned()->index()->nullable();
            $table->string('collection_document')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_stocks', function ($table) {
            $table->dropColumn('collection_note');
            $table->dropColumn('collection_document');
        });
    }
}
