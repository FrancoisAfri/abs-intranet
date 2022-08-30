<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class KeyTypeToKeytracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('keytracking', function (Blueprint $table) {
            $table->string('key_number')->nullable();
            $table->unsignedInteger('key_type')->nullable()->index();
            $table->unsignedInteger('key_status')->nullable()->index();
            $table->unsignedInteger('vehicle_type')->nullable()->index();
            $table->unsignedInteger('vehicle_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keytracking', function (Blueprint $table) {
           $table->dropColumn('key_number');
            $table->dropColumn('key_type');
            $table->dropColumn('key_status');
            $table->dropColumn('vehicle_type');
            $table->dropColumn('vehicle_id');
        });
    }
}
