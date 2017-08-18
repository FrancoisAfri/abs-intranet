<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		/*task_type 1== induction  2== Meeting tasks 3== Normal Tasks 4== Helpdesk Tasks*/
		/*priority 1== low 2== Medium 3== High*/
		/*upload_required 1== NO 02== Yes*/
        Schema::create('employee_tasks', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('order_no')->nullable();
			$table->integer('escalation_id')->nullable();
			$table->integer('employee_id')->nullable();
			$table->integer('library_id')->nullable();
			$table->integer('added_by')->nullable();
			$table->integer('duration')->nullable();
			//$table->integer('helpdesk_id')->nullable();
			//$table->integer('ticket_id')->nullable();
            $table->string('description')->nullable();
            $table->string('notes')->nullable();
			$table->smallInteger('priority')->nullable();
			$table->smallInteger('task_type')->nullable();
			$table->smallInteger('upload_required')->nullable();
			$table->smallInteger('status')->nullable();
			$table->bigInteger('start_date')->nullable();
			$table->bigInteger('date_started')->nullable();
			$table->bigInteger('date_completed')->nullable();
			$table->bigInteger('date_paused')->nullable();
			$table->bigInteger('due_date')->nullable();
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
        Schema::dropIfExists('employee_tasks');
    }
}
