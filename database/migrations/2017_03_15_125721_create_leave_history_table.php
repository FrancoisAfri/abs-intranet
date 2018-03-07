<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hr_id')->nullable(); //#employee_id
            $table->string('action')->nullable();
            $table->smallInteger('description_action')->nullable();
            $table->smallInteger('previous_balance')->nullable();
            $table->smallInteger('transcation')->nullable();
            $table->smallInteger('current_balance')->nullable();
            $table->integer('leave_type_id')->nullable();
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
        Schema::dropIfExists('leave_history');
    }
}
