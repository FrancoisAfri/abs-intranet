<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('accqueries')->nullable();
            $table->bigInteger('arr_date')->nullable();
            $table->string('arr_desc')->nullable();
            $table->bigInteger('arr_dt')->nullable();
            $table->string('arr_flt')->nullable();
            $table->string('arr_tm')->nullable();
            $table->string('arr_tm_at')->nullable();
            $table->string('authby')->nullable();
            $table->string('branch')->nullable();
            $table->string('brn_name')->nullable();
            $table->string('ccauthterms')->nullable();
            $table->string('clnt_cell')->nullable();
            $table->string('clnt_cellno')->nullable();
            $table->string('clnt_name')->nullable();
            $table->string('clntref10')->nullable();
            $table->string('clntref2')->nullable();
            $table->string('clntref3')->nullable();
            $table->string('clntref4')->nullable();
            $table->string('clntref5')->nullable();
            $table->string('clntref6')->nullable();
            $table->string('clntref7')->nullable();
            $table->string('clntref8')->nullable();
            $table->string('clntref9')->nullable();
            $table->string('co_asata')->nullable();
            $table->string('co_cell')->nullable();
            $table->string('co_contact')->nullable();
            $table->string('co_dochead')->nullable();
            $table->string('co_email')->nullable();
            $table->string('co_fax')->nullable();
            $table->string('co_fullcontact', 1000)->nullable();
            $table->string('co_fulldetails', 1000)->nullable();
            $table->string('co_header_style')->nullable();
            $table->string('co_iata')->nullable();
            $table->string('co_iatalogo')->nullable();
            $table->string('co_name')->nullable();
            $table->string('co_phadd', 1000)->nullable();
            $table->string('co_phadd1')->nullable();
            $table->string('co_phadd2')->nullable();
            $table->string('co_phadd3')->nullable();
            $table->string('co_phadd4')->nullable();
            $table->string('co_poadd')->nullable();
            $table->string('co_poadd1')->nullable();
            $table->string('co_poadd2')->nullable();
            $table->string('co_poadd3')->nullable();
            $table->string('co_poadd4')->nullable();
            $table->string('co_reg')->nullable();
            $table->string('co_tel')->nullable();
            $table->string('co_vat')->nullable();
            $table->string('co_webpage')->nullable();
            $table->string('cons_cd')->nullable();
            $table->string('cons_name')->nullable();
            $table->bigInteger('darrdate')->nullable();
            $table->bigInteger('ddepdate')->nullable();
            $table->bigInteger('dep_date')->nullable();
            $table->string('dep_desc')->nullable();
            $table->bigInteger('dep_dt')->nullable();
            $table->string('dep_flt')->nullable();
            $table->string('dep_tm')->nullable();
            $table->string('dep_tm_at')->nullable();
            $table->string('div_name')->nullable();
            $table->string('division')->nullable();
            $table->string('dr_cd')->nullable();
            $table->string('dr_name')->nullable();
            $table->string('dr_name_order')->nullable();
            $table->string('dr_vatno')->nullable();
            $table->integer('duration')->nullable();
            $table->bigInteger('dvchdate')->nullable();
            $table->string('ext_ver_cd')->nullable();
            $table->double('fare_act')->nullable();
            $table->double('fare_save')->nullable();
            $table->string('fare_savecode')->nullable();
            $table->string('fare_savedesc')->nullable();
            $table->string('fare_std')->nullable();
            $table->string('flag1')->nullable();
            $table->string('flag2')->nullable();
            $table->string('flag3')->nullable();
            $table->string('footer')->nullable();
            $table->string('genterm1')->nullable();
            $table->string('genterm2')->nullable();
            $table->string('genterm3')->nullable();
            $table->string('genterm4')->nullable();
            $table->string('genterms')->nullable();
            $table->string('grp_name')->nullable();
            $table->string('inc_base')->nullable();
            $table->double('inc_commper')->nullable();
            $table->double('inc_excl')->nullable();
            $table->double('inc_fee')->nullable();
            $table->double('inc_incl')->nullable();
            $table->double('inc_vat')->nullable();
            $table->double('inc_vatper')->nullable();
            $table->string('isaccqueries')->nullable();
            $table->double('lccauthamt')->nullable();
            $table->string('lccconame')->nullable();
            $table->string('lccct')->nullable();
            $table->string('lccexpdt')->nullable();
            $table->string('lccholder')->nullable();
            $table->string('lccno')->nullable();
            $table->string('lccttype')->nullable();
            $table->string('lcctype')->nullable();
            $table->string('logo1')->nullable();
            $table->string('logo2')->nullable();
            $table->string('mess')->nullable();
            $table->string('mess1')->nullable();
            $table->string('mess2')->nullable();
            $table->string('mess3')->nullable();
            $table->string('mess4')->nullable();
            $table->string('msg_ins')->nullable();
            $table->string('msg_inv')->nullable();
            $table->string('msg_pvt', 1000)->nullable();
            $table->string('msg_sys')->nullable();
            $table->string('msg_terms', 1000)->nullable();
            $table->string('msg_void')->nullable();
            $table->integer('no_pax')->nullable();
            $table->string('orderno')->nullable();
            $table->string('our_ref')->nullable();
            $table->string('our_ref_full')->nullable();
            $table->string('per_desc')->nullable();
            $table->string('pmt_extras')->nullable();
            $table->string('pmt_serv')->nullable();
            $table->string('pmttype_extras')->nullable();
            $table->string('pmttype_serv')->nullable();
            $table->string('pnr')->nullable();
            $table->string('rate_daily')->nullable();
            $table->string('rate_full')->nullable();
            $table->string('reg_name')->nullable();
            $table->string('serv_cd')->nullable();
            $table->string('serv_des')->nullable();
            $table->string('serv_full')->nullable();
            $table->string('sup_addblock1')->nullable();
            $table->string('sup_addblock2')->nullable();
            $table->string('sup_cd')->nullable();
            $table->string('sup_contact')->nullable();
            $table->string('sup_contactblock')->nullable();
            $table->string('sup_email')->nullable();
            $table->string('sup_fax')->nullable();
            $table->string('sup_fullcontact')->nullable();
            $table->string('sup_fulldetails')->nullable();
            $table->string('sup_gpscoords')->nullable();
            $table->string('sup_latitude')->nullable();
            $table->string('sup_longitude')->nullable();
            $table->string('sup_name')->nullable();
            $table->string('sup_phadd')->nullable();
            $table->string('sup_phadd1')->nullable();
            $table->string('sup_phadd2')->nullable();
            $table->string('sup_phadd3')->nullable();
            $table->string('sup_phadd4')->nullable();
            $table->string('sup_poadd')->nullable();
            $table->string('sup_poadd1')->nullable();
            $table->string('sup_poadd2')->nullable();
            $table->string('sup_poadd3')->nullable();
            $table->string('sup_poadd4')->nullable();
            $table->string('sup_ref')->nullable();
            $table->string('sup_tel')->nullable();
            $table->string('trade_name')->nullable();
            $table->string('unit_desc')->nullable();
            $table->string('units')->nullable();
            $table->bigInteger('vch_dt')->nullable();
            $table->string('vch_no')->nullable();
            $table->string('vch_no_full')->nullable();
            $table->string('vch_prn')->nullable();
            $table->string('vch_rev')->nullable();
            $table->string('vch_stamp')->nullable();
            $table->string('vch_status')->nullable();
            $table->string('vch_uniqueno')->nullable();
            $table->string('vch_void')->nullable();
            $table->string('cwebid')->nullable();
            $table->string('version')->nullable();
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
        Schema::dropIfExists('vouchers');
    }
}
