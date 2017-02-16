<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('status');
			$table->string('name')->nullable();
			$table->string('code')->nullable();
			$table->string('rejection_reason')->nullable();
			$table->string('sponsor')->nullable();
			$table->string('contract_doc')->nullable();
			$table->string('supporting_doc')->nullable();
			$table->string('description')->nullable();
			$table->string('comment')->nullable();
			$table->integer('user_id')->nullable();
            $table->integer('approver_id')->nullable();
			$table->bigInteger('start_date')->nullable();
			$table->bigInteger('end_date')->nullable();
			$table->integer('programme_id')->nullable();
			$table->integer('facilitator_id')->nullable();
			$table->integer('manager_id')->nullable();
			$table->integer('sponsor_id')->nullable();
			$table->integer('service_provider_id')->nullable();
			$table->double('budget')->nullable();
			$table->double('sponsorship_amount')->nullable();
			$table->double('contract_amount')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
