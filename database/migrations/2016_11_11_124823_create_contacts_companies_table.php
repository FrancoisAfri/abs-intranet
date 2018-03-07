<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts_companies', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('company_type')->nullable();
            $table->string('name')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->double('bee_score')->nullable();
            $table->string('bee_certificate_doc')->nullable();
            $table->string('comp_reg_doc')->nullable();
            $table->string('sector')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('phys_address')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('phys_circuit')->nullable();
            $table->string('phys_region')->nullable();
            $table->string('phys_district')->nullable();
            $table->string('phys_province_id')->nullable();
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
        Schema::dropIfExists('contacts_companies');
    }
}
