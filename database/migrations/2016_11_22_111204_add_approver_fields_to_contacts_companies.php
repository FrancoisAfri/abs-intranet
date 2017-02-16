<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApproverFieldsToContactsCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contacts_companies', function($table) {
            $table->integer('loader_id')->nullable();
            $table->integer('first_approver_id')->nullable();
            $table->integer('second_approver_id')->nullable();
            $table->string('first_rejection_reason')->nullable();
            $table->string('second_rejection_reason')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contacts_companies', function($table) {
            $table->dropColumn('loader_id');
            $table->dropColumn('first_approver_id');
            $table->dropColumn('second_approver_id');
            $table->dropColumn('first_rejection_reason');
            $table->dropColumn('second_rejection_reason');
        });
    }
}
