<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToProcurementSetupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procurement_setups', function (Blueprint $table) {
            $table->integer('is_role_general')->nullable()->unsigned()->index();
            $table->integer('email_po_to_supplier')->nullable()->unsigned()->index();
            $table->integer('email_role')->nullable()->unsigned()->index();
            $table->double('amount_required_double')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('procurement_setups', function (Blueprint $table) {
            $table->dropColumn('is_role_general');
            $table->dropColumn('email_po_to_supplier');
            $table->dropColumn('email_role');
            $table->dropColumn('amount_required_double');
		});
    }
}