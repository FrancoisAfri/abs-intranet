<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveAllocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_allocation', function (Blueprint $table) {
            $table->increments('id');
             $table->integer('hr_id')->nullable();
             $table->integer('month_allocated')->nullable();
             $table->integer('leave_type_id')->nullable();
             $table->integer('allocated_by')->nullable();
             $table->bigInteger('date_allocated')->nullable();
             $table->double('balance_before')->nullable();
             $table->double('current_balance')->nullable();
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
        Schema::dropIfExists('leave_allocation');
    }
}
