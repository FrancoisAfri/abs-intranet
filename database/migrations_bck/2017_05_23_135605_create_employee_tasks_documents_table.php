<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTasksDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_tasks_documents', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('task_id')->nullable();
			$table->integer('employee_id')->nullable();
			$table->integer('added_by')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('employee_tasks_documents');
    }
}
