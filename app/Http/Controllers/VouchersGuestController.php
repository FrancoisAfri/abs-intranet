<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\Voucher;
use Illuminate\Http\Request;

use App\Http\Requests;

class VouchersGuestController extends Controller
{
    /**
     * This constructor specifies that this section of the application can be accessed by guest (unauthenticated) users
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display a to search vouchers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $companyDetails = CompanyIdentity::systemSettings();

        $data['page_title'] = "Vouchers";
        $data['page_description'] = "Find a Voucher";
        $data['breadcrumb'] = [
            ['title' => 'Voucher', 'path' => '/get-voucher', 'icon' => 'fa fa-file-text', 'active' => 0, 'is_module' => 1],
            ['title' => 'get voucher', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Vouchers';
        $data['active_rib'] = 'Get Voucher';
        $data['skinColor'] = $companyDetails['sys_theme_color'];
        $data['headerAcronymBold'] = $companyDetails['header_acronym_bold'];
        $data['headerAcronymRegular'] = $companyDetails['header_acronym_regular'];
        $data['headerNameBold'] = $companyDetails['header_name_bold'];
        $data['headerNameRegular'] = $companyDetails['header_name_regular'];
        $data['company_logo'] = $companyDetails['company_logo_url'];

        AuditReportsController::store('Vouchers', 'Search Voucher Page Accessed', "Accessed By Guest", 0);
        return view('vouchers.guests.index')->with($data);
    }

    /**
     * Search and return vouchers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function store(Request $request)
    {
        $messages = [
            'clnt_name.required' => 'The Full Name field is required.',
            'clnt_cellno.required' => 'The Cell Number field is required.'
        ];
        $this->validate($request, [
            'clnt_name' => 'required',
            'clnt_cellno' => 'required'
        ], $messages);

        $clientName = trim($request->input('clnt_name'));
        $clientCell = trim($request->input('clnt_cellno'));

        $vouchers = Voucher::where('clnt_name', 'ILIKE', '%' . $clientName . '%')
            ->where('clnt_cellno', 'ILIKE', '%' . $clientCell . '%')
            ->orderBy('vch_dt', 'DESC')->get();

        $companyDetails = CompanyIdentity::systemSettings();

        $data['page_title'] = "Vouchers";
        $data['page_description'] = "Client Vouchers";
        $data['breadcrumb'] = [
            ['title' => 'Voucher', 'path' => '/get-voucher', 'icon' => 'fa fa-file-text', 'active' => 0, 'is_module' => 1],
            ['title' => 'get voucher', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Vouchers';
        $data['active_rib'] = 'Get Voucher';
        $data['vouchers'] = $vouchers;
        $data['skinColor'] = $companyDetails['sys_theme_color'];
        $data['headerAcronymBold'] = $companyDetails['header_acronym_bold'];
        $data['headerAcronymRegular'] = $companyDetails['header_acronym_regular'];
        $data['headerNameBold'] = $companyDetails['header_name_bold'];
        $data['headerNameRegular'] = $companyDetails['header_name_regular'];
        $data['company_logo'] = $companyDetails['company_logo_url'];

        AuditReportsController::store('Vouchers', 'Search Voucher Page Accessed', "Accessed By Guest", 0);
        return view('vouchers.guests.vouchers')->with($data);
    }
}
