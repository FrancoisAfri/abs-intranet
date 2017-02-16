<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('status')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->nullable();
            $table->string('topic')->nullable();
            $table->double('budget')->nullable();
            $table->integer('programme_id')->unsigned()->index()->nullable();
            $table->integer('project_id')->unsigned()->index()->nullable();
            $table->integer('facilitator_id')->unsigned()->index()->nullable();
            $table->integer('sponsor_id')->unsigned()->index()->nullable();
            $table->integer('service_provider_id')->unsigned()->index()->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('approver_id')->nullable();
            $table->string('description')->nullable();
            $table->double('actual_cost')->nullable();
            $table->string('sponsor')->nullable();
            $table->double('sponsorship_amount')->nullable();
            $table->double('contract_amount')->nullable();
            $table->string('contract_doc')->nullable();
            $table->string('supporting_doc')->nullable();
            $table->string('comment')->nullable();
            $table->string('rejection_reason')->nullable();
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
        Schema::dropIfExists('activities');
    }
}
