<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_loan_documents', function (Blueprint $table) {
			$table->increments('id');
			$table->uuid('uuid')->index();
            $table->string('loan_id')->nullable();
            $table->string('supporting_docs')->nullable();
            $table->integer('status')->nullable();
            $table->string('doc_name')->nullable();
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
        Schema::drop('staff_loan_documents');
    }
}
