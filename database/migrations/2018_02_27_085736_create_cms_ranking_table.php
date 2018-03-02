<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cms_news_ratings', function (Blueprint $table) {

            $table->increments('id');
            $table->bigInteger('rating_1');
            $table->bigInteger('rating_2');
            $table->bigInteger('rating_3');
            $table->bigInteger('rating_4');
            $table->bigInteger('rating_5');
            $table->integer('user_id')->index()->unsigned()->nullable();
            $table->timestamps();

        });

    }

    /**

     * Reverse the migrations.

     *

     * @return void

     */

    public function down()

    {

        Schema::dropIfExists('cms_news_ratings');

    }

}
