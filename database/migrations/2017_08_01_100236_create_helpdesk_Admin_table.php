<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHelpdeskAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('helpdesk_Admin', function (Blueprint $table) {
            $table->increments('id');
             $table->integer('admin_id')->nullable();
            $table->integer('helpdesk_id')->nullable();
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
        Schema::dropIfExists('helpdesk_Admin');
    }
}
