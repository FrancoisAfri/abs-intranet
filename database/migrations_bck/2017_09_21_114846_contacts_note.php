<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContactsNote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('contacts_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('originator_type')->nullable();
            $table->integer('company_id')->unsigned()->index()->nullable();
            $table->integer('hr_person_id')->unsigned()->index()->nullable();
            $table->integer('employee_id')->unsigned()->index()->nullable();
            // $table->string('comment')->unsigned()->index()->nullable();
            $table->bigInteger('date')->nullable();
            $table->bigInteger('time')->nullable();
            $table->integer('communication_method')->nullable();
            $table->integer('rensponse')->nullable();
            $table->string('notes')->nullable();
            $table->integer('next_action')->nullable();
            $table->bigInteger('follow_date')->nullable();
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
         Schema::dropIfExists('contacts_notes');
    }
}
