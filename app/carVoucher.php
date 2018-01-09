<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class carVoucher extends Model
{
   //Specify the table name
    public $table = 'car_vouchers';

    // Mass assignable fields
    protected $fillable = [
        'n_commact_excl', 'c_agency_addr1', 'c_agency_addr2', 'c_agency_emergency_cont'
		, 'c_agency_fax', 'c_agency_full_addr', 'c_agency_name', 'c_agency_reg', 'c_agency_tel'
		, 'c_agency_tel_fax', 'c_agency_pays',
        'c_arr_flight', 'c_bill_account_no', 'c_bill_postal_address_code', 'c_bill_address_full', 'c_bill_postal_address1'
		, 'c_bill_postal_address2', 'c_bill_name', 'c_branch', 'c_bus_name', 'c_car_code', 'c_car_description',
        'c_cl_ref0', 'c_cl_ref0_desc', 'c_cl_ref2', 'c_cl_ref2_desc', 'c_cl_ref3'
		, 'c_cl_ref3_desc', 'c_cl_ref4', 'c_cl_ref4_desc',
		'c_cl_ref5', 'c_cl_ref5_desc',
        'c_cl_ref6', 'c_cl_ref6_desc', 'c_cl_ref7', 'c_cl_ref7_desc', 'c_cl_ref8'
		, 'c_cl_ref8_desc', 'c_cl_ref9', 'c_cl_ref9_desc', 'c_client_code', 'c_client_name'
		, 'c_cl_name',
        'c_cl_ord_no', 'c_cl_po_add1', 'c_cl_po_add2', 'c_cl_po_add3', 'c_cl_po_add_full'
		, 'c_cl_po_add4', 'c_clnt_ref1_name', 'c_clnt_ref1_value', 'c_clnt_ref3_name'
		, 'c_clnt_ref3_value', 'c_clnt_ref4_name', 'c_clnt_ref4_value',
        'c_client_pays', 'n_comm_due_excl', 'c_cons_code', 'c_cost_centre', 'c_ct_card_warning'
		, 'c_cr_card', 'c_cc_exp', 'c_ct_card_no', 'c_cr_code', 'c_currency', 'c_curr_code'
		, 'c_date',
        'c_time', 'c_user_code', 'c_cl_code', 'c_dep_flight', 'fare_savedesc', 'c_disclaimer'
		, 'c_div_add', 'c_division', 'c_div_co_reg', 'c_div_emergency', 'c_div_iata_no'
		, 'c_div_name',
        'c_div_tel', 'c_div_vat_reg', 'c_dom_int', 'c_sup_end_add', 'c_sup_end_cell'
		, 'c_sup_end_key', 'c_sup_end_email', 'c_sup_end_fax', 'c_sup_end_name'
		, 'c_sup_end_tel', 'c_return_addr1',
        'c_return_addr3', 'c_return_cell', 'lccconame', 'c_return_date', 'c_return_email'
		, 'c_return_fax', 'c_return_code', 'c_return_location', 'c_return_addr_code'
		, 'c_return_tel', 'c_duration', 'c_end_date',
        'c_end_location', 'c_end_time', 'c_ext_src', 'c_ext_ver_code', 'c_flight_no'
		, 'l_free_loc', 'c_qfd_book_no', 'c_qfd_seq', 'c_go_book_method', 'c_go_trip_no'
		, 'c_go_trip_purpose', 'c_grp_code', 'c_insurance',
        'c_internal_no', 'c_inv_com_ref', 'c_wti_brn', 'c_issue_date', 'c_iss_date'
		, 'c_issued_by', 'c_issued_email', 'c_license_no', 'c_order_no', 'c_our_ref'
		, 'c_pnr',
        'c_cell', 'c_pax_email', 'c_pax_cell_no', 'c_pax_name', 'c_pax_names'
		, 'c_sup_beg_add', 'c_sup_beg_cell', 'c_sup_beg_key', 'c_sup_beg_email'
		, 'c_sup_beg_fax',
        'c_sup_beg_name', 'c_sup_beg_tel', 'c_rental_addr1', 'c_rental_addr2'
		, 'c_rental_addr3', 'c_rental_cell', 'c_rental_date', 'c_rental_email'
		, 'c_rental_fax',
        'c_rental_code', 'c_rental_location', 'c_rental_addr_code', 'c_rental_tel'
		, 'c_proc_code', 'c_processor', 'c_qt_key', 'c_qt_acc_no', 'c_range_cl_code'
		, 'c_rate_code', 'c_rate_description',
        'c_rate_name', 'c_remarks', 'c_reservation_no', 'c_revision', 'l_sms_sent'
		, 'c_beg_date', 'c_beg_location', 'c_beg_time', 'c_supplier_code', 'c_sup_code'
		, 'c_cup_inv_cons', 'n_inv_tot', 'c_sup_inv_date', 'c_sup_inv_dt_rcv', 'c_sup_inv_no'
		, 'c_sup_name', 'c_supplier_name', 'c_sup_ref', 'c_sup_vouch_no', 'c_range_key'
		, 'c_tour_code', 'n_incl_rate', 'l_web_upLoad', 'c_ver_code', 'c_void_text'
		, 'c_voucher_hash', 'c_voucher_message', 'c_full_voucher_no', 'c_voucher_no'
		, 'c_voucher_no_unique', 'c_int_vouch_no', 'c_status', 'c_voucher_subtitle'
		, 'c_type', 'c_voucher_value', 'c_vouch_value', 'c_web_id', 'c_agency_addr3'
		, 'c_agency_addr_code', 'c_clnt_ref2_name', 'c_clnt_ref2_value'
		, 'c_bill_postal_address3', 'c_div_fax', 'c_return_addr2', 'c_voucher_title'
    ];
}
