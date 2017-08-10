<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index()->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
			$table->string('ticket_number')->nullable();
            $table->integer('helpdesk_id')->unsigned()->index()->nullable();
            $table->string('subject')->nullable();
            $table->string('message')->nullable();
            $table->integer('admin_id')->unsigned()->index()->nullable();
            $table->integer('operator_id')->unsigned()->index()->nullable();    
            $table->smallInteger('status')->nullable();
            $table->bigInteger('ticket_date')->nullable(); 
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
        Schema::dropIfExists('ticket');
    }
}
