<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancellationFiledsToLeaveApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_application', function($table) {
            $table->integer('canceller_id')->nullable();
            $table->string('cancellation_reason', 1000)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_application', function($table) {
            $table->dropColumn('canceller_id');
            $table->dropColumn('cancellation_reason');
        });
    }
}
