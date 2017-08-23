<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SystemEmailSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('system_email_setup', function (Blueprint $table) {
            $table->increments('id'); 
            $table->smallInteger('auto_processemails')->nullable();
            $table->smallInteger('anly_processreplies')->nullable();
            $table->string('email_address')->nullable(); 
            $table->string('server_name')->nullable(); 
            $table->smallInteger('preferred_communication_method')->nullable();
            $table->string('server_port')->nullable(); 
            $table->string('username')->nullable(); 
            $table->string('password')->nullable(); 
            $table->string('Signature_start')->nullable(); 
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
        Schema::dropIfExists('system_email_setup');
    }
}
