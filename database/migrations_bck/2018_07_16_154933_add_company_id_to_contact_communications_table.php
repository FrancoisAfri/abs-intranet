<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyIdToContactCommunicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      ///  Schema::table('contacts_communications', function ($table) {
        //    $table->string('company_id')->unsigned()->index()->nullable();
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // Schema::table('contacts_communications', function ($table) {
        //    $table->dropColumn('company_id');
       // });
    }
}
