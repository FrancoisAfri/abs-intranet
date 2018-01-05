<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('n_commact_excl', 1500)->nullable();
            $table->string('c_agency_addr1', 1500)->nullable();
            $table->string('c_agency_addr2', 1500)->nullable();
            $table->string('c_agency_Addr3', 1500)->nullable();
            $table->string('c_agency_emergency_cont', 1500)->nullable();
            $table->string('c_agency_fax', 1500)->nullable();
            $table->string('c_agency_full_addr', 1500)->nullable();
            $table->string('c_agency_name', 1500)->nullable();
            $table->string('c_aAgency_addr_code', 1500)->nullable();
            $table->string('c_agency_reg', 1500)->nullable();
            $table->string('c_agency_tel', 1500)->nullable();
            $table->string('c_agency_tel_fax', 1500)->nullable();
            $table->string('c_agency_pays', 1500)->nullable();
            $table->string('c_arr_flight', 1500)->nullable();
            $table->string('c_bill_account_no', 1500)->nullable();
            $table->string('c_bill_postal_address_code', 1500)->nullable();
            $table->string('c_bill_address_full', 1500)->nullable();
            $table->string('c_bill_postal_address1', 1500)->nullable();
            $table->string('c_bill_postal_address2', 1500)->nullable();
            $table->string('c_bill_name', 1500)->nullable();
            $table->string('c_branch', 1500)->nullable();
            $table->string('c_bus_name', 1500)->nullable();
            $table->string('c_car_code', 1500)->nullable();
            $table->string('c_car_description', 1500)->nullable();
            $table->string('c_cl_ref0', 1500)->nullable();
            $table->string('c_cl_ref0_desc', 1500)->nullable();
            $table->string('c_cl_ref2', 1500)->nullable();
            $table->string('c_cl_ref2_desc', 1500)->nullable();
            $table->string('c_cl_ref3', 1500)->nullable();
            $table->string('c_cl_ref3_desc', 1500)->nullable();
            $table->string('c_cl_ref4', 1500)->nullable();
            $table->string('c_cl_ref4_desc', 1500)->nullable();
            $table->string('c_cl_ref5', 1500)->nullable();
            $table->string('c_cl_ref5_desc', 1500)->nullable();
            $table->string('c_cl_ref6', 1500)->nullable();
            $table->string('c_cl_ref6_desc', 1500)->nullable();
            $table->string('c_cl_ref7', 1500)->nullable();
            $table->string('c_cl_ref7_desc', 1500)->nullable();
            $table->string('c_cl_ref8', 1500)->nullable();
            $table->string('c_cl_ref8_desc', 1500)->nullable();
            $table->string('c_cl_ref9', 1500)->nullable();
            $table->string('c_cl_ref9_desc', 1500)->nullable();
            $table->string('c_client_code', 1500)->nullable();
            $table->string('c_client_name', 1500)->nullable();
            $table->string('c_cl_name', 1500)->nullable();
            $table->string('c_cl_ord_no', 1500)->nullable();
            $table->string('c_cl_po_add1', 1500)->nullable();
            $table->string('c_cl_po_add2', 1500)->nullable();
            $table->string('c_cl_po_add3', 1500)->nullable();
            $table->string('c_cl_po_add_full', 1500)->nullable();
            $table->string('c_cl_po_add4', 1500)->nullable();
            $table->string('c_clnt_ref1_name', 1500)->nullable();
            $table->string('c_clnt_ref1_value', 1500)->nullable();
            $table->string('c_Clnt_ref2_name', 1500)->nullable();
            $table->string('c_clnt_ref2_Value', 1500)->nullable();
            $table->string('c_clnt_ref3_name', 1500)->nullable();
            $table->string('c_clnt_ref3_value', 1500)->nullable();
            $table->string('c_clnt_ref4_name', 1500)->nullable();
            $table->string('c_clnt_ref4_value', 1500)->nullable();
            $table->string('c_client_pays', 1500)->nullable();
            $table->string('n_comm_due_excl', 1500)->nullable();
            $table->string('c_cons_code', 1500)->nullable();
            $table->string('c_cost_centre', 1500)->nullable();
            $table->string('c_ct_card_warning', 1500)->nullable();
            $table->string('c_cr_card', 1500)->nullable();
            $table->string('c_cc_exp', 1500)->nullable();
            $table->string('c_ct_card_no', 1500)->nullable();
            $table->string('c_cr_code', 1500)->nullable();
            $table->string('c_currency', 1500)->nullable();
            $table->string('c_curr_code', 1500)->nullable();
            $table->string('c_date', 1500)->nullable();
            $table->string('c_time', 1500)->nullable();
            $table->string('c_user_code', 1500)->nullable();
            $table->string('c_cl_code', 1500)->nullable();
            $table->string('c_dep_flight', 1500)->nullable();
            $table->string('c_disclaimer', 1500)->nullable();
            $table->string('c_div_add', 1500)->nullable();
            $table->string('c_division', 1500)->nullable();
            $table->string('c_div_co_reg', 1500)->nullable();
            $table->string('c_div_emergency', 1500)->nullable();
            $table->string('c_div_iata_no', 1500)->nullable();
            $table->string('c_div_name', 1500)->nullable();
            $table->string('c_div_tel', 1500)->nullable();
			 $table->string('c_div_vat_reg', 1500)->nullable();
            $table->string('c_dom_int', 1500)->nullable();
            $table->string('c_sup_end_add', 1500)->nullable();
            $table->string('c_sup_end_cell', 1500)->nullable();
            $table->string('c_sup_end_key', 1500)->nullable();
            $table->string('c_sup_end_email', 1500)->nullable();
            $table->string('c_sup_end_fax', 1500)->nullable();
            $table->string('c_sup_end_name', 1500)->nullable();
            $table->string('c_sup_end_tel', 1500)->nullable();
            $table->string('c_return_addr1', 1500)->nullable();
            $table->string('c_return-addr2', 1500)->nullable();
            $table->string('c_return_addr3', 1500)->nullable();
            $table->string('c_return_cell', 1500)->nullable();
            $table->string('c_return_date', 1500)->nullable();
            $table->string('c_return_email', 1500)->nullable();
            $table->string('c_return_fax', 1500)->nullable();
            $table->string('c_return_code', 1500)->nullable();
            $table->string('c_return_location', 1500)->nullable();
            $table->string('c_return_addr_code', 1500)->nullable();
            $table->string('c_return_tel', 1500)->nullable();
            $table->string('c_duration', 1500)->nullable();
            $table->string('c_end_date', 1500)->nullable();
			$table->string('c_end_location', 1500)->nullable();
            $table->string('c_end_time', 1500)->nullable();
            $table->string('c_ext_src', 1500)->nullable();
            $table->string('c_ext_ver_code', 1500)->nullable();
            $table->string('c_flight_no', 1500)->nullable();
            $table->string('l_free_loc', 1500)->nullable();
            $table->string('c_qfd_book_no', 1500)->nullable();
            $table->string('c_qfd_seq', 1500)->nullable();
            $table->string('c_go_book_method', 1500)->nullable();
            $table->string('c_go_trip_no', 1500)->nullable();
            $table->string('c_go_trip_purpose', 1500)->nullable();
            $table->string('c_grp_code', 1500)->nullable();
            $table->string('c_insurance', 1500)->nullable();
            $table->string('c_internal_no', 1500)->nullable();
            $table->string('c_inv_com_ref', 1500)->nullable();
            $table->string('c_wti_brn', 1500)->nullable();
            $table->string('c_issue_date', 1500)->nullable();
            $table->string('c_iss_date', 1500)->nullable();
            $table->string('c_issued_by', 1500)->nullable();
            $table->string('c_issued_email', 1500)->nullable();
            $table->string('c_license_no', 1500)->nullable();
            $table->string('c_order_no', 1500)->nullable();
			 $table->string('c_our_ref', 1500)->nullable();
            $table->string('c_pnr', 1500)->nullable();
            $table->string('c_cell', 1500)->nullable();
            $table->string('c_pax_cell_no', 1500)->nullable();
            $table->string('c_pax_email', 1500)->nullable();
            $table->string('c_pax_name', 1500)->nullable();
            $table->string('c_pax_names', 1500)->nullable();
            $table->string('c_sup_beg_add', 1500)->nullable();
            $table->string('c_sup_beg_cell', 1500)->nullable();
            $table->string('c_sup_beg_key', 1500)->nullable();
            $table->string('c_sup_beg_email', 1500)->nullable();
            $table->string('c_sup_beg_fax', 1500)->nullable();
            $table->string('c_sup_beg_name', 1500)->nullable();
            $table->string('c_sup_beg_tel', 1500)->nullable();
            $table->string('c_rental_addr1', 1500)->nullable();
            $table->string('c_rental_addr2', 1500)->nullable();
            $table->string('c_rental_addr3', 1500)->nullable();
            $table->string('c_rental_cell', 1500)->nullable();
            $table->string('c_rental_date', 1500)->nullable();
            $table->string('c_rental_email', 1500)->nullable();
            $table->string('c_rental_fax', 1500)->nullable();
            $table->string('c_rental_code', 1500)->nullable();
            $table->string('c_rental_location', 1500)->nullable();
            $table->string('c_rental_addr_code', 1500)->nullable();
            $table->string('c_rental_tel', 1500)->nullable();
			$table->string('c_proc_code', 1500)->nullable();
            $table->string('c_processor', 1500)->nullable();
            $table->string('c_qt_key', 1500)->nullable();
            $table->string('c_qt_acc_no', 1500)->nullable();
            $table->string('c_range_cl_code', 1500)->nullable();
            $table->string('c_rate_code', 1500)->nullable();
            $table->string('c_rate_description', 1500)->nullable();
            $table->string('c_rate_name', 1500)->nullable();
            $table->string('c_remarks', 1500)->nullable();
            $table->string('c_reservation_no', 1500)->nullable();
            $table->string('c_revision', 1500)->nullable();
            $table->string('l_sms_sent', 1500)->nullable();
			$table->string('c_beg_date', 1500)->nullable();
            $table->string('c_beg_location', 1500)->nullable();
            $table->string('c_beg_time', 1500)->nullable();
            $table->string('c_supplier_code', 1500)->nullable();
            $table->string('c_sup_code', 1500)->nullable();
            $table->string('c_cup_inv_cons', 1500)->nullable();
            $table->string('n_inv_tot', 1500)->nullable();
            $table->string('c_sup_inv_date', 1500)->nullable();
            $table->string('c_sup_inv_dt_rcv', 1500)->nullable();
            $table->string('c_sup_inv_no', 1500)->nullable();
			$table->string('c_sup_name', 1500)->nullable();
            $table->string('c_supplier_name', 1500)->nullable();
            $table->string('c_sup_ref', 1500)->nullable();
            $table->string('c_sup_vouch_no', 1500)->nullable();
            $table->string('c_range_key', 1500)->nullable();
            $table->string('c_tour_code', 1500)->nullable();
            $table->string('n_incl_rate', 1500)->nullable();
            $table->string('l_web_upLoad', 1500)->nullable();
            $table->string('c_ver_code', 1500)->nullable();
            $table->string('c_void_text', 1500)->nullable();
            $table->string('c_voucher_hash', 1500)->nullable();
            $table->string('c_voucher_message', 1500)->nullable();
            $table->string('c_full_voucher_no', 1500)->nullable();
            $table->string('c_voucher_no', 1500)->nullable();
			$table->string('c_voucher_no_unique', 1500)->nullable();
            $table->string('c_int_vouch_no', 1500)->nullable();
            $table->string('c_status', 1500)->nullable();
            $table->string('c_voucher_subtitle', 1500)->nullable();
            $table->string('c_type', 1500)->nullable();
            $table->string('c_voucher_value', 1500)->nullable();
            $table->string('c_vouch_value', 1500)->nullable();
            $table->string('c_web_id', 1500)->nullable();
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
        Schema::dropIfExists('car_vouchers');
    }
}