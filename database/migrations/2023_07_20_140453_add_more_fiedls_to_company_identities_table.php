<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFiedlsToCompanyIdentitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_identities', function ($table) {
            $table->string('brought_to_text')->unsigned()->index()->nullable();
            $table->string('brought_to_text_image')->unsigned()->index()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_identities', function ($table) {
            $table->dropColumn('brought_to_text');
            $table->dropColumn('brought_to_text_image');
        });
    }
}
