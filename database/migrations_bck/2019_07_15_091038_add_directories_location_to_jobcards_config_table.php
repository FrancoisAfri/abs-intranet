<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDirectoriesLocationToJobcardsConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobcard_config', function (Blueprint $table) {
            $table->string('service_file_from')->nullable()->unsigned()->index();
            $table->string('service_file_to')->nullable()->unsigned()->index();
            $table->string('break_test_from')->nullable()->unsigned()->index();
            $table->string('break_test_to')->nullable()->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobcard_config', function (Blueprint $table) {
            $table->dropColumn('service_file_from');
            $table->dropColumn('service_file_to');
            $table->dropColumn('break_test_from');
            $table->dropColumn('break_test_to');
		});
    }
}
