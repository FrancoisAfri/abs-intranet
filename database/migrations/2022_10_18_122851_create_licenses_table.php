<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_license', function ($table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name')->nullable();
            $table->string('details')->nullable();
            $table->string('serial')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_cost', 8, 2)->nullable();
            $table->string('order_number');
            $table->integer('total')->default(1);
            $table->text('notes')->nullable();
            $table->integer('user_id')->nullable();
            $table->bigInteger('asset_type_id')->nullable();
            $table->date('expiration_date')->nullable();
            $table->String('licence_image')->nullable();
            $table->smallInteger('status')->default(1)->nullable();
            $table->string('licence_status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
