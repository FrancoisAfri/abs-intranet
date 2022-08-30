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
            $table->unsignedInteger('company_id')->index()->nullable();
            $table->unsignedInteger('client_id')->index()->nullable();
            $table->unsignedInteger('type')->index()->nullable();
            $table->unsignedInteger('type_complaint_compliment')->index()->nullable();
            $table->unsignedInteger('employee_id')->index()->nullable();
            $table->unsignedInteger('created_by')->index()->nullable();
            $table->unsignedInteger('responsible_party')->index()->nullable();
            $table->unsignedBigInteger('date_complaint_compliment')->index()->nullable();
            $table->unsignedBigInteger('date_created')->index()->nullable();
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