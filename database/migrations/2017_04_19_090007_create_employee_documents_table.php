<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('EmployeeDocuments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doc_description')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('category_id')->nullable();
            $table->string('doc')->nullable();
            $table->string('manager_id')->nullable();
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
        Schema::dropIfExists('EmployeeDocuments');
    }
}


