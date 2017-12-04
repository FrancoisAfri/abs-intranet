<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseFieldsOnVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('accqueries', 1500)->change();
            $table->string('arr_desc', 1500)->change();
            $table->string('arr_flt', 1500)->change();
            $table->string('arr_tm', 1500)->change();
            $table->string('arr_tm_at', 1500)->change();
            $table->string('authby', 1500)->change();
            $table->string('branch', 1500)->change();
            $table->string('brn_name', 1500)->change();
            $table->string('ccauthterms', 1500)->change();
            $table->string('clnt_cell', 1500)->change();
            $table->string('clnt_cellno', 1500)->change();
            $table->string('clnt_name', 1500)->change();
            $table->string('clntref10', 1500)->change();
            $table->string('clntref2', 1500)->change();
            $table->string('clntref3', 1500)->change();
            $table->string('clntref4', 1500)->change();
            $table->string('clntref5', 1500)->change();
            $table->string('clntref6', 1500)->change();
            $table->string('clntref7', 1500)->change();
            $table->string('clntref8', 1500)->change();
            $table->string('clntref9', 1500)->change();
            $table->string('co_asata', 1500)->change();
            $table->string('co_cell', 1500)->change();
            $table->string('co_contact', 1500)->change();
            $table->string('co_dochead', 1500)->change();
            $table->string('co_email', 1500)->change();
            $table->string('co_fax', 1500)->change();
            $table->string('co_fullcontact', 1500)->change();
            $table->string('co_fulldetails', 1500)->change();
            $table->string('co_header_style', 1500)->change();
            $table->string('co_iata', 1500)->change();
            $table->string('co_iatalogo', 1500)->change();
            $table->string('co_name', 1500)->change();
            $table->string('co_phadd', 1500)->change();
            $table->string('co_phadd1', 1500)->change();
            $table->string('co_phadd2', 1500)->change();
            $table->string('co_phadd3', 1500)->change();
            $table->string('co_phadd4', 1500)->change();
            $table->string('co_poadd', 1500)->change();
            $table->string('co_poadd1', 1500)->change();
            $table->string('co_poadd2', 1500)->change();
            $table->string('co_poadd3', 1500)->change();
            $table->string('co_poadd4', 1500)->change();
            $table->string('co_reg', 1500)->change();
            $table->string('co_tel', 1500)->change();
            $table->string('co_vat', 1500)->change();
            $table->string('co_webpage', 1500)->change();
            $table->string('cons_cd', 1500)->change();
            $table->string('cons_name', 1500)->change();
            $table->string('dep_desc', 1500)->change();
            $table->string('dep_flt', 1500)->change();
            $table->string('dep_tm', 1500)->change();
            $table->string('dep_tm_at', 1500)->change();
            $table->string('div_name', 1500)->change();
            $table->string('division', 1500)->change();
            $table->string('dr_cd', 1500)->change();
            $table->string('dr_name', 1500)->change();
            $table->string('dr_name_order', 1500)->change();
            $table->string('dr_vatno', 1500)->change();
            $table->string('ext_ver_cd', 1500)->change();
            $table->string('fare_savecode', 1500)->change();
            $table->string('fare_savedesc', 1500)->change();
            $table->string('fare_std', 1500)->change();
            $table->string('flag1', 1500)->change();
            $table->string('flag2', 1500)->change();
            $table->string('flag3', 1500)->change();
            $table->string('footer', 1500)->change();
            $table->string('genterm1', 1500)->change();
            $table->string('genterm2', 1500)->change();
            $table->string('genterm3', 1500)->change();
            $table->string('genterm4', 1500)->change();
            $table->string('genterms', 1500)->change();
            $table->string('grp_name', 1500)->change();
            $table->string('inc_base', 1500)->change();
            $table->string('isaccqueries', 1500)->change();
            $table->string('lccconame', 1500)->change();
            $table->string('lccct', 1500)->change();
            $table->string('lccexpdt', 1500)->change();
            $table->string('lccholder', 1500)->change();
            $table->string('lccno', 1500)->change();
            $table->string('lccttype', 1500)->change();
            $table->string('lcctype', 1500)->change();
            $table->string('logo1', 1500)->change();
            $table->string('logo2', 1500)->change();
            $table->string('mess', 1500)->change();
            $table->string('mess1', 1500)->change();
            $table->string('mess2', 1500)->change();
            $table->string('mess3', 1500)->change();
            $table->string('mess4', 1500)->change();
            $table->string('msg_ins', 1500)->change();
            $table->string('msg_inv', 1500)->change();
            $table->string('msg_pvt', 1500)->change();
            $table->string('msg_sys', 1500)->change();
            $table->string('msg_terms', 1500)->change();
            $table->string('msg_void', 1500)->change();
            $table->string('orderno', 1500)->change();
            $table->string('our_ref', 1500)->change();
            $table->string('our_ref_full', 1500)->change();
            $table->string('per_desc', 1500)->change();
            $table->string('pmt_extras', 1500)->change();
            $table->string('pmt_serv', 1500)->change();
            $table->string('pmttype_extras', 1500)->change();
            $table->string('pmttype_serv', 1500)->change();
            $table->string('pnr', 1500)->change();
            $table->string('rate_daily', 1500)->change();
            $table->string('rate_full', 1500)->change();
            $table->string('reg_name', 1500)->change();
            $table->string('serv_cd', 1500)->change();
            $table->string('serv_des', 1500)->change();
            $table->string('serv_full', 1500)->change();
            $table->string('sup_addblock1', 1500)->change();
            $table->string('sup_addblock2', 1500)->change();
            $table->string('sup_cd', 1500)->change();
            $table->string('sup_contact', 1500)->change();
            $table->string('sup_contactblock', 1500)->change();
            $table->string('sup_email', 1500)->change();
            $table->string('sup_fax', 1500)->change();
            $table->string('sup_fullcontact', 1500)->change();
            $table->string('sup_fulldetails', 1500)->change();
            $table->string('sup_gpscoords', 1500)->change();
            $table->string('sup_latitude', 1500)->change();
            $table->string('sup_longitude', 1500)->change();
            $table->string('sup_name', 1500)->change();
            $table->string('sup_phadd', 1500)->change();
            $table->string('sup_phadd1', 1500)->change();
            $table->string('sup_phadd2', 1500)->change();
            $table->string('sup_phadd3', 1500)->change();
            $table->string('sup_phadd4', 1500)->change();
            $table->string('sup_poadd', 1500)->change();
            $table->string('sup_poadd1', 1500)->change();
            $table->string('sup_poadd2', 1500)->change();
            $table->string('sup_poadd3', 1500)->change();
            $table->string('sup_poadd4', 1500)->change();
            $table->string('sup_ref', 1500)->change();
            $table->string('sup_tel', 1500)->change();
            $table->string('trade_name', 1500)->change();
            $table->string('unit_desc', 1500)->change();
            $table->string('units', 1500)->change();
            $table->string('vch_no', 1500)->change();
            $table->string('vch_no_full', 1500)->change();
            $table->string('vch_prn', 1500)->change();
            $table->string('vch_rev', 1500)->change();
            $table->string('vch_stamp', 1500)->change();
            $table->string('vch_status', 1500)->change();
            $table->string('vch_uniqueno', 1500)->change();
            $table->string('vch_void', 1500)->change();
            $table->string('cwebid', 1500)->change();
            $table->string('version', 1500)->change();
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
