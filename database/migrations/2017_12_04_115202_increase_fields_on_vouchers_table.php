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
            $table->longText('accqueries', 1500)->change();
            $table->longText('arr_desc', 1500)->change();
            $table->longText('arr_flt', 1500)->change();
            $table->longText('arr_tm', 1500)->change();
            $table->longText('arr_tm_at', 1500)->change();
            $table->longText('authby', 1500)->change();
            $table->longText('branch', 1500)->change();
            $table->longText('brn_name', 1500)->change();
            $table->longText('ccauthterms', 1500)->change();
            $table->longText('clnt_cell', 1500)->change();
            $table->longText('clnt_cellno', 1500)->change();
            $table->longText('clnt_name', 1500)->change();
            $table->longText('clntref10', 1500)->change();
            $table->longText('clntref2', 1500)->change();
            $table->longText('clntref3', 1500)->change();
            $table->longText('clntref4', 1500)->change();
            $table->longText('clntref5', 1500)->change();
            $table->longText('clntref6', 1500)->change();
            $table->longText('clntref7', 1500)->change();
            $table->longText('clntref8', 1500)->change();
            $table->longText('clntref9', 1500)->change();
            $table->longText('co_asata', 1500)->change();
            $table->longText('co_cell', 1500)->change();
            $table->longText('co_contact', 1500)->change();
            $table->longText('co_dochead', 1500)->change();
            $table->longText('co_email', 1500)->change();
            $table->longText('co_fax', 1500)->change();
            $table->longText('co_fullcontact', 1500)->change();
            $table->longText('co_fulldetails', 1500)->change();
            $table->longText('co_header_style', 1500)->change();
            $table->longText('co_iata', 1500)->change();
            $table->longText('co_iatalogo', 1500)->change();
            $table->longText('co_name', 1500)->change();
            $table->longText('co_phadd', 1500)->change();
            $table->longText('co_phadd1', 1500)->change();
            $table->longText('co_phadd2', 1500)->change();
            $table->longText('co_phadd3', 1500)->change();
            $table->longText('co_phadd4', 1500)->change();
            $table->longText('co_poadd', 1500)->change();
            $table->longText('co_poadd1', 1500)->change();
            $table->longText('co_poadd2', 1500)->change();
            $table->longText('co_poadd3', 1500)->change();
            $table->longText('co_poadd4', 1500)->change();
            $table->longText('co_reg', 1500)->change();
            $table->longText('co_tel', 1500)->change();
            $table->longText('co_vat', 1500)->change();
            $table->longText('co_webpage', 1500)->change();
            $table->longText('cons_cd', 1500)->change();
            $table->longText('cons_name', 1500)->change();
            $table->longText('dep_desc', 1500)->change();
            $table->longText('dep_flt', 1500)->change();
            $table->longText('dep_tm', 1500)->change();
            $table->longText('dep_tm_at', 1500)->change();
            $table->longText('div_name', 1500)->change();
            $table->longText('division', 1500)->change();
            $table->longText('dr_cd', 1500)->change();
            $table->longText('dr_name', 1500)->change();
            $table->longText('dr_name_order', 1500)->change();
            $table->longText('dr_vatno', 1500)->change();
            $table->longText('ext_ver_cd', 1500)->change();
            $table->longText('fare_savecode', 1500)->change();
            $table->longText('fare_savedesc', 1500)->change();
            $table->longText('fare_std', 1500)->change();
            $table->longText('flag1', 1500)->change();
            $table->longText('flag2', 1500)->change();
            $table->longText('flag3', 1500)->change();
            $table->longText('footer', 1500)->change();
            $table->longText('genterm1', 1500)->change();
            $table->longText('genterm2', 1500)->change();
            $table->longText('genterm3', 1500)->change();
            $table->longText('genterm4', 1500)->change();
            $table->longText('genterms', 1500)->change();
            $table->longText('grp_name', 1500)->change();
            $table->longText('inc_base', 1500)->change();
            $table->longText('isaccqueries', 1500)->change();
            $table->longText('lccconame', 1500)->change();
            $table->longText('lccct', 1500)->change();
            $table->longText('lccexpdt', 1500)->change();
            $table->longText('lccholder', 1500)->change();
            $table->longText('lccno', 1500)->change();
            $table->longText('lccttype', 1500)->change();
            $table->longText('lcctype', 1500)->change();
            $table->longText('logo1', 1500)->change();
            $table->longText('logo2', 1500)->change();
            $table->longText('mess', 1500)->change();
            $table->longText('mess1', 1500)->change();
            $table->longText('mess2', 1500)->change();
            $table->longText('mess3', 1500)->change();
            $table->longText('mess4', 1500)->change();
            $table->longText('msg_ins', 1500)->change();
            $table->longText('msg_inv', 1500)->change();
            $table->longText('msg_pvt', 1500)->change();
            $table->longText('msg_sys', 1500)->change();
            $table->longText('msg_terms', 1500)->change();
            $table->longText('msg_void', 1500)->change();
            $table->longText('orderno', 1500)->change();
            $table->longText('our_ref', 1500)->change();
            $table->longText('our_ref_full', 1500)->change();
            $table->longText('per_desc', 1500)->change();
            $table->longText('pmt_extras', 1500)->change();
            $table->longText('pmt_serv', 1500)->change();
            $table->longText('pmttype_extras', 1500)->change();
            $table->longText('pmttype_serv', 1500)->change();
            $table->longText('pnr', 1500)->change();
            $table->longText('rate_daily', 1500)->change();
            $table->longText('rate_full', 1500)->change();
            $table->longText('reg_name', 1500)->change();
            $table->longText('serv_cd', 1500)->change();
            $table->longText('serv_des', 1500)->change();
            $table->longText('serv_full', 1500)->change();
            $table->longText('sup_addblock1', 1500)->change();
            $table->longText('sup_addblock2', 1500)->change();
            $table->longText('sup_cd', 1500)->change();
            $table->longText('sup_contact', 1500)->change();
            $table->longText('sup_contactblock', 1500)->change();
            $table->longText('sup_email', 1500)->change();
            $table->longText('sup_fax', 1500)->change();
            $table->longText('sup_fullcontact', 1500)->change();
            $table->longText('sup_fulldetails', 1500)->change();
            $table->longText('sup_gpscoords', 1500)->change();
            $table->longText('sup_latitude', 1500)->change();
            $table->longText('sup_longitude', 1500)->change();
            $table->longText('sup_name', 1500)->change();
            $table->longText('sup_phadd', 1500)->change();
            $table->longText('sup_phadd1', 1500)->change();
            $table->longText('sup_phadd2', 1500)->change();
            $table->longText('sup_phadd3', 1500)->change();
            $table->longText('sup_phadd4', 1500)->change();
            $table->longText('sup_poadd', 1500)->change();
            $table->longText('sup_poadd1', 1500)->change();
            $table->longText('sup_poadd2', 1500)->change();
            $table->longText('sup_poadd3', 1500)->change();
            $table->longText('sup_poadd4', 1500)->change();
            $table->longText('sup_ref', 1500)->change();
            $table->longText('sup_tel', 1500)->change();
            $table->longText('trade_name', 1500)->change();
            $table->longText('unit_desc', 1500)->change();
            $table->longText('units', 1500)->change();
            $table->longText('vch_no', 1500)->change();
            $table->longText('vch_no_full', 1500)->change();
            $table->longText('vch_prn', 1500)->change();
            $table->longText('vch_rev', 1500)->change();
            $table->longText('vch_stamp', 1500)->change();
            $table->longText('vch_status', 1500)->change();
            $table->longText('vch_uniqueno', 1500)->change();
            $table->longText('vch_void', 1500)->change();
            $table->longText('cwebid', 1500)->change();
            $table->longText('version', 1500)->change();
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
