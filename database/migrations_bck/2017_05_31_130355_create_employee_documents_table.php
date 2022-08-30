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
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->nullable();
            $table->integer('division_level_2')->nullable();
            $table->integer('division_level_1')->nullable();
            $table->integer('hr_person_id')->nullable();
			//$table->integer('document_type_id')->nullable();
			$table->integer('qualification_type_id')->nullable();
			$table->integer('doc_type_id')->nullable();
            $table->string('doc_description')->nullable();
			$table->bigInteger('date_from')->nullable();
            $table->bigInteger('expirydate')->nullable();
            $table->string('supporting_docs')->nullable();
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
        Schema::dropIfExists('employee_documents');
    }
}
