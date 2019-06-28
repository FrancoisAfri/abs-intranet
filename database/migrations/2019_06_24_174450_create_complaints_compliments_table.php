<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintsComplimentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints_compliments', function (Blueprint $table) {
            $table->increments('id');
			$table->string('office')->nullable();       
			$table->string('error_type')->nullable();       
			$table->string('pending_reason')->nullable();       
			$table->string('summary_corrective_measure')->nullable();       
			$table->string('summary_complaint_compliment')->nullable();       
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('client_id')->unsigned()->index()->nullable();
            $table->integer('type')->unsigned()->index()->nullable();
            $table->integer('type_complaint_compliment')->unsigned()->index()->nullable();
            $table->integer('employee_id')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('responsible_party')->unsigned()->index()->nullable();
            $table->bigInteger('date_complaint_compliment')->unsigned()->index()->nullable();
            $table->bigInteger('date_created')->unsigned()->index()->nullable();
            $table->smallInteger('status')->nullable();
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
        Schema::dropIfExists('complaints_compliments');
    }
}