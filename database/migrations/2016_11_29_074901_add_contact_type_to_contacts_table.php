<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactTypeToContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts_contacts', function($table) {
            $table->smallInteger('contact_type')->nullable();
            $table->smallInteger('organization_type')->nullable();
            $table->string('office_number')->nullable();
            $table->string('str_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts_contacts', function($table) {
            $table->dropColumn('contact_type');
            $table->dropColumn('organization_type');
            $table->dropColumn('office_number');
            $table->dropColumn('str_position');
        });
    }
}
