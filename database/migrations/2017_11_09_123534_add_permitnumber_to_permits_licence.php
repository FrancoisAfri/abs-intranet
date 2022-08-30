<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermitnumberToPermitsLicence extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permits_licence', function ($table) {
            $table->string('permits_licence_no')->nullable()->index();
            $table->string('permits_licence_numbers')->nullable()->index();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('permits_licence', function ($table) {
            $table->dropColumn('permits_licence_no');
            $table->dropColumn('permits_licence_numbers');
           
        });
    }
}
