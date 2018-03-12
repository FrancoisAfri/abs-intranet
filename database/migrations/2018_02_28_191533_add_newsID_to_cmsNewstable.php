<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewsIDToCmsNewstable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_news_ratings', function (Blueprint $table) {
           // $table->Integer('cmsnewsID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_news_ratings', function (Blueprint $table) {
           // $table->dropColumn('cmsnewsID');
        });
    }
}
