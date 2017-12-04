<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    //Specify the table name
    public $table = 'vouchers';

    // Mass assignable fields
    protected $fillable = [
        'accqueries', 'arr_date', 'arr_desc', 'arr_dt', 'arr_flt', 'arr_tm', 'arr_tm_at', 'authby', 'branch', 'brn_name', 'ccauthterms',
        'clnt_cell', 'clnt_cellno', 'clnt_name', 'clntref10', 'clntref2', 'clntref3', 'clntref4', 'clntref5', 'clntref6', 'clntref7', 'clntref8',
        'clntref9', 'co_asata', 'co_cell', 'co_contact', 'co_dochead', 'co_email', 'co_fax', 'co_fullcontact', 'co_fulldetails', 'co_header_style',
        'co_iata', 'co_iatalogo', 'co_name', 'co_phadd', 'co_phadd1', 'co_phadd2', 'co_phadd3', 'co_phadd4', 'co_poadd', 'co_poadd1', 'co_poadd2',
        'co_poadd3', 'co_poadd4', 'co_reg', 'co_tel', 'co_vat', 'co_webpage', 'cons_cd', 'cons_name', 'darrdate', 'ddepdate', 'dep_date', 'dep_desc',
        'dep_dt', 'dep_flt', 'dep_tm', 'dep_tm_at', 'div_name', 'division', 'dr_cd', 'dr_name', 'dr_name_order', 'dr_vatno', 'duration', 'dvchdate',
        'ext_ver_cd', 'fare_act', 'fare_save', 'fare_savecode', 'fare_savedesc', 'fare_std', 'flag1', 'flag2', 'flag3', 'footer', 'genterm1', 'genterm2',
        'genterm3', 'genterm4', 'genterms', 'grp_name', 'inc_base', 'inc_commper', 'inc_excl', 'inc_fee', 'inc_incl', 'inc_vat', 'inc_vatper',
        'isaccqueries', 'lccauthamt', 'lccconame', 'lccct', 'lccexpdt', 'lccholder', 'lccno', 'lccttype', 'lcctype', 'logo1', 'logo2', 'mess',
        'mess1', 'mess2', 'mess3', 'mess4', 'msg_ins', 'msg_inv', 'msg_pvt', 'msg_sys', 'msg_terms', 'msg_void', 'no_pax', 'orderno', 'our_ref',
        'our_ref_full', 'per_desc', 'pmt_extras', 'pmt_serv', 'pmttype_extras', 'pmttype_serv', 'pnr', 'rate_daily', 'rate_full', 'reg_name', 'serv_cd',
        'serv_des', 'serv_full', 'sup_addblock1', 'sup_addblock2', 'sup_cd', 'sup_contact', 'sup_contactblock', 'sup_email', 'sup_fax', 'sup_fullcontact',
        'sup_fulldetails', 'sup_gpscoords', 'sup_latitude', 'sup_longitude', 'sup_name', 'sup_phadd', 'sup_phadd1', 'sup_phadd2', 'sup_phadd3',
        'sup_phadd4', 'sup_poadd', 'sup_poadd1', 'sup_poadd2', 'sup_poadd3', 'sup_poadd4', 'sup_ref', 'sup_tel', 'trade_name', 'unit_desc', 'units',
        'vch_dt', 'vch_no', 'vch_no_full', 'vch_prn', 'vch_rev', 'vch_stamp', 'vch_status', 'vch_uniqueno', 'vch_void', 'cwebid', 'version'
    ];
}
