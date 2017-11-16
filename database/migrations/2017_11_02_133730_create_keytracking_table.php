<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeytrackingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keytracking', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issued_to')->nullable()->unsigned()->index();
            $table->integer('employee')->nullable()->unsigned()->index();
            $table->integer('safe_name')->nullable();
            $table->integer('safe_controller')->nullable();
            $table->bigInteger('date_issued')->nullable();
            $table->bigInteger('date_status_change')->nullable();
            $table->integer('issued_by')->nullable()->unsigned()->index();
            $table->string('description')->nullable();
            $table->integer('status')->nullable();
            $table->string('captured_by')->nullable();
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
        Schema::dropIfExists('keytracking');
    }
}
