<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('name')->nullable();
            $table->string('description')->nullable();
            $table->bigInteger('date_from')->nullable();
            $table->bigInteger('expirydate')->nullable();
            $table->string('supporting_docs')->nullable();
			$table->integer('client_id')->index()->unsigned()->nullable();
			$table->integer('status')->nullable();
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
        Schema::dropIfExists('client_documents');
    }
}
