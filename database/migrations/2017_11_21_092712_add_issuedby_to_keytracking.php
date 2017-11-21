<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIssuedbyToKeytracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('keytracking', function ($table) {
             $table->string('issuedBy')->nullable()->unsigned()->index();
             $table->string('safeController')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('keytracking', function ($table) {
            $table->dropColumn('issuedBy');
            $table->dropColumn('safeController');
        });
    }
}
