<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesApprovalHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quote_approval_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quotation_id')->unsigned()->index()->nullable();
            $table->integer('status')->unsigned()->index()->nullable();
            $table->string('comment')->unsigned()->index()->nullable();
            $table->bigInteger('approval_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quote_approval_history');
    }
}
