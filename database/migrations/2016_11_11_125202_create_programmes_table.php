<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgrammesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->nullable();
            $table->string('name')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->double('budget_expenditure')->nullable();
            $table->double('budget_income')->nullable();
            $table->string('description')->nullable();
            $table->integer('sponsor_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('approver_id')->nullable();
            $table->string('sponsor')->nullable();
            $table->string('sponsorship_amount')->nullable();
            $table->integer('service_provider_id')->unsigned()->index()->nullable();
            $table->double('contract_amount')->nullable();
            $table->string('contract_doc')->nullable();
            $table->integer('manager_id')->unsigned()->index()->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('programmes');
    }
}
