<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_identities', function (Blueprint $table) {
           $table->increments('id');
            $table->string('company_name')->nullable();
            $table->string('full_company_name')->nullable();
            $table->string('header_name_bold')->nullable();
            $table->string('header_name_regular')->nullable();
            $table->string('header_acronym_bold')->nullable();
            $table->string('header_acronym_regular')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('sys_theme_color')->nullable();
            $table->string('mailing_name')->nullable();
            $table->string('mailing_address')->nullable();
            $table->string('support_email')->nullable();
			$table->string('company_website')->nullable();
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
        Schema::dropIfExists('company_identities');
    }
}
