<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddStartDateToErsAbsentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ers_absent_users', function ($table) {
            $table->bigInteger('start_date')->nullable();
            $table->bigInteger('end_date')->default(0)->nullable();
            $table->smallInteger('is_email_sent')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ers_absent_users', function ($table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('is_email_sent');
        });
    }
}
