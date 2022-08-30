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
            $table->longText('accqueries')->nullable();
            $table->bigInteger('arr_date')->nullable();
            $table->longText('arr_desc')->nullable();
            $table->bigInteger('arr_dt')->nullable();
            $table->longText('arr_flt')->nullable();
            $table->longText('arr_tm')->nullable();
            $table->longText('arr_tm_at')->nullable();
            $table->longText('authby')->nullable();
            $table->longText('branch')->nullable();
            $table->longText('brn_name')->nullable();
            $table->longText('ccauthterms')->nullable();
            $table->longText('clnt_cell')->nullable();
            $table->longText('clnt_cellno')->nullable();
            $table->longText('clnt_name')->nullable();
            $table->longText('clntref10')->nullable();
            $table->longText('clntref2')->nullable();
            $table->longText('clntref3')->nullable();
            $table->longText('clntref4')->nullable();
            $table->longText('clntref5')->nullable();
            $table->longText('clntref6')->nullable();
            $table->longText('clntref7')->nullable();
            $table->longText('clntref8')->nullable();
            $table->longText('clntref9')->nullable();
            $table->longText('co_asata')->nullable();
            $table->longText('co_cell')->nullable();
            $table->longText('co_contact')->nullable();
            $table->longText('co_dochead')->nullable();
            $table->longText('co_email')->nullable();
            $table->longText('co_fax')->nullable();
            $table->longText('co_fullcontact', 1000)->nullable();
            $table->longText('co_fulldetails', 1000)->nullable();
            $table->longText('co_header_style')->nullable();
            $table->longText('co_iata')->nullable();
            $table->longText('co_iatalogo')->nullable();
            $table->longText('co_name')->nullable();
            $table->longText('co_phadd', 1000)->nullable();
            $table->longText('co_phadd1')->nullable();
            $table->longText('co_phadd2')->nullable();
            $table->longText('co_phadd3')->nullable();
            $table->longText('co_phadd4')->nullable();
            $table->longText('co_poadd')->nullable();
            $table->longText('co_poadd1')->nullable();
            $table->longText('co_poadd2')->nullable();
            $table->longText('co_poadd3')->nullable();
            $table->longText('co_poadd4')->nullable();
            $table->longText('co_reg')->nullable();
            $table->longText('co_tel')->nullable();
            $table->longText('co_vat')->nullable();
            $table->longText('co_webpage')->nullable();
            $table->longText('cons_cd')->nullable();
            $table->longText('cons_name')->nullable();
            $table->bigInteger('darrdate')->nullable();
            $table->bigInteger('ddepdate')->nullable();
            $table->bigInteger('dep_date')->nullable();
            $table->longText('dep_desc')->nullable();
            $table->bigInteger('dep_dt')->nullable();
            $table->longText('dep_flt')->nullable();
            $table->longText('dep_tm')->nullable();
            $table->longText('dep_tm_at')->nullable();
            $table->longText('div_name')->nullable();
            $table->longText('division')->nullable();
            $table->longText('dr_cd')->nullable();
            $table->longText('dr_name')->nullable();
            $table->longText('dr_name_order')->nullable();
            $table->longText('dr_vatno')->nullable();
            $table->integer('duration')->nullable();
            $table->bigInteger('dvchdate')->nullable();
            $table->longText('ext_ver_cd')->nullable();
            $table->double('fare_act')->nullable();
            $table->double('fare_save')->nullable();
            $table->longText('fare_savecode')->nullable();
            $table->longText('fare_savedesc')->nullable();
            $table->longText('fare_std')->nullable();
            $table->longText('flag1')->nullable();
            $table->longText('flag2')->nullable();
            $table->longText('flag3')->nullable();
            $table->longText('footer')->nullable();
            $table->longText('genterm1')->nullable();
            $table->longText('genterm2')->nullable();
            $table->longText('genterm3')->nullable();
            $table->longText('genterm4')->nullable();
            $table->longText('genterms')->nullable();
            $table->longText('grp_name')->nullable();
            $table->longText('inc_base')->nullable();
            $table->double('inc_commper')->nullable();
            $table->double('inc_excl')->nullable();
            $table->double('inc_fee')->nullable();
            $table->double('inc_incl')->nullable();
            $table->double('inc_vat')->nullable();
            $table->double('inc_vatper')->nullable();
            $table->longText('isaccqueries')->nullable();
            $table->double('lccauthamt')->nullable();
            $table->longText('lccconame')->nullable();
            $table->longText('lccct')->nullable();
            $table->longText('lccexpdt')->nullable();
            $table->longText('lccholder')->nullable();
            $table->longText('lccno')->nullable();
            $table->longText('lccttype')->nullable();
            $table->longText('lcctype')->nullable();
            $table->longText('logo1')->nullable();
            $table->longText('logo2')->nullable();
            $table->longText('mess')->nullable();
            $table->longText('mess1')->nullable();
            $table->longText('mess2')->nullable();
            $table->longText('mess3')->nullable();
            $table->longText('mess4')->nullable();
            $table->longText('msg_ins')->nullable();
            $table->longText('msg_inv')->nullable();
            $table->longText('msg_pvt', 1000)->nullable();
            $table->longText('msg_sys')->nullable();
            $table->longText('msg_terms', 1000)->nullable();
            $table->longText('msg_void')->nullable();
            $table->integer('no_pax')->nullable();
            $table->longText('orderno')->nullable();
            $table->longText('our_ref')->nullable();
            $table->longText('our_ref_full')->nullable();
            $table->longText('per_desc')->nullable();
            $table->longText('pmt_extras')->nullable();
            $table->longText('pmt_serv')->nullable();
            $table->longText('pmttype_extras')->nullable();
            $table->longText('pmttype_serv')->nullable();
            $table->longText('pnr')->nullable();
            $table->longText('rate_daily')->nullable();
            $table->longText('rate_full')->nullable();
            $table->longText('reg_name')->nullable();
            $table->longText('serv_cd')->nullable();
            $table->longText('serv_des')->nullable();
            $table->longText('serv_full')->nullable();
            $table->longText('sup_addblock1')->nullable();
            $table->longText('sup_addblock2')->nullable();
            $table->longText('sup_cd')->nullable();
            $table->longText('sup_contact')->nullable();
            $table->longText('sup_contactblock')->nullable();
            $table->longText('sup_email')->nullable();
            $table->longText('sup_fax')->nullable();
            $table->longText('sup_fullcontact')->nullable();
            $table->longText('sup_fulldetails')->nullable();
            $table->longText('sup_gpscoords')->nullable();
            $table->longText('sup_latitude')->nullable();
            $table->longText('sup_longitude')->nullable();
            $table->longText('sup_name')->nullable();
            $table->longText('sup_phadd')->nullable();
            $table->longText('sup_phadd1')->nullable();
            $table->longText('sup_phadd2')->nullable();
            $table->longText('sup_phadd3')->nullable();
            $table->longText('sup_phadd4')->nullable();
            $table->longText('sup_poadd')->nullable();
            $table->longText('sup_poadd1')->nullable();
            $table->longText('sup_poadd2')->nullable();
            $table->longText('sup_poadd3')->nullable();
            $table->longText('sup_poadd4')->nullable();
            $table->longText('sup_ref')->nullable();
            $table->longText('sup_tel')->nullable();
            $table->longText('trade_name')->nullable();
            $table->longText('unit_desc')->nullable();
            $table->longText('units')->nullable();
            $table->bigInteger('vch_dt')->nullable();
            $table->longText('vch_no')->nullable();
            $table->longText('vch_no_full')->nullable();
            $table->longText('vch_prn')->nullable();
            $table->longText('vch_rev')->nullable();
            $table->longText('vch_stamp')->nullable();
            $table->longText('vch_status')->nullable();
            $table->longText('vch_uniqueno')->nullable();
            $table->longText('vch_void')->nullable();
            $table->longText('cwebid')->nullable();
            $table->longText('version')->nullable();
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
