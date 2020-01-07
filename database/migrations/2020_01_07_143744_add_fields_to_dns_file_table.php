<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDnsFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dms_files', function (Blueprint $table) {
            $table->string('current_version')->index()->unsigned()->nullable();
            $table->string('description')->index()->unsigned()->nullable();
            $table->string('document_name')->index()->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dms_files', function (Blueprint $table) {
            $table->dropColumn('current_version');
            $table->dropColumn('description');
            $table->dropColumn('document_name');
        });
    }
}