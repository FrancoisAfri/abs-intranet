<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactFieldsToQuoteCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quote_company_profiles', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('phys_address')->nullable();
            $table->string('phys_city')->nullable();
            $table->string('phys_postal_code')->nullable();
            $table->integer('phys_province')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quote_company_profiles', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('phone_number');
            $table->dropColumn('phys_address');
            $table->dropColumn('phys_city');
            $table->dropColumn('phys_postal_code');
            $table->dropColumn('phys_province');
        });
    }
}
