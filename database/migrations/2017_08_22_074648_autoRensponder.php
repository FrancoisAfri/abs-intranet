<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AutoRensponder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auto_rensponder', function (Blueprint $table) {
            $table->increments('id');
            $table->string('responder_messages')->nullable();
            $table->string('response_emails')->nullable();
            $table->string('ticket_completion_req')->nullable();
            $table->string('ticket_completed')->nullable(); 
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
        Schema::dropIfExists('auto_rensponder');
    }
}
