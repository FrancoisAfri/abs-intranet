<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\CompanyIdentity;
use App\Mail\SendVoucher;
use App\Voucher;
use App\carVoucher;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            'sup_ref.required' => 'The Ref No field is required.'
        ];
        $this->validate($request, [
            'clnt_name' => 'required',
			'sup_ref' => 'required',
        ], $messages);

        $clientName = trim($request->input('clnt_name'));
        $supRef = trim($request->input('sup_ref'));

        $vouchers = Voucher::where('clnt_name', 'ILIKE', '%' . $clientName . '%')
            ->where('sup_ref', 'ILIKE', '%' . $supRef . '%')
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
	
	public function carVoucher(Request $request)
    {
        $messages = [
            'clnt_name.required' => 'The Full Name field is required.',
            'c_sup_vouch_no.required' => 'The Ref No field is required.'
        ];
        $this->validate($request, [
            'clnt_name' => 'required',
            'c_sup_vouch_no' => 'required',
        ], $messages);
        $clientName = trim($request->input('clnt_name'));
        $cSupVouchNo = trim($request->input('c_sup_vouch_no'));

        $vouchers = DB::table('car_vouchers')
		->where('c_pax_name', 'ILIKE', '%' . $clientName . '%')
        ->where('c_sup_vouch_no', 'ILIKE', '%' . $cSupVouchNo . '%')
        ->orderBy('id', 'DESC')->get();

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
        return view('vouchers.guests.car_vouchers')->with($data);
    }

    /**
     * Show voucher in PDF format.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\View\View
     */
    public function voucherPDF(Voucher $voucher, $emailVoucher = false)
    {
        if (!$voucher) return "Invalid Voucher.";

        $companyDetails = CompanyIdentity::systemSettings();

        $data['file_name'] = 'Voucher';
        $data['page_title'] = 'Voucher PDF';
        $data['voucher'] = $voucher;
        $data['companyDetails'] = $companyDetails;
        $data['usersImg'] = public_path() . Storage::disk('local')->url('voucher_icons/users.png');
        $data['folderImg'] = public_path() . Storage::disk('local')->url('voucher_icons/folder.png');
        $data['calculatorImg'] = public_path() . Storage::disk('local')->url('voucher_icons/calculator.png');
        $data['paymentImg'] = public_path() . Storage::disk('local')->url('voucher_icons/cash_payment.png');
        $data['calendarClockImg'] = public_path() . Storage::disk('local')->url('voucher_icons/calendar-clock-icon2.png');
        $data['starImg'] = public_path() . Storage::disk('local')->url('voucher_icons/star-icon.png');
        $data['tcImg'] = public_path() . Storage::disk('local')->url('voucher_icons/terms-and-conditions-journals.png');
        $data['iataImg'] = public_path() . Storage::disk('local')->url('voucher_icons/IATA.png');
        $data['asataImg'] = public_path() . Storage::disk('local')->url('voucher_icons/ASATA.png');
        //return public_path() . Storage::disk('local')->url('voucher_icons/users.png');

        $view = view('vouchers.guests.pdf_voucher', $data)->render();
        //return $view;
        $pdf = resolve('dompdf.wrapper');
        $pdf->loadHTML($view);
        if ($emailVoucher) return $pdf->output();
        else return $pdf->stream('voucher_' . $voucher->id . '.pdf');

        //if ($printQuote) return $pdf->stream('quotation_' . $quotation->id . '.pdf');
        //elseif ($emailQuote) return $pdf->output();
    }
	public function carVvoucherPDF(carVoucher $voucher, $emailVoucher = false)
    {
        if (!$voucher) return "Invalid Voucher.";

        $companyDetails = CompanyIdentity::systemSettings();
//return $voucher;
        $data['file_name'] = 'Voucher';
        $data['page_title'] = 'Voucher PDF';
        $data['carVoucher'] = $voucher;
        $data['companyDetails'] = $companyDetails;
        $data['usersImg'] = public_path() . Storage::disk('local')->url('voucher_icons/users.png');
        $data['folderImg'] = public_path() . Storage::disk('local')->url('voucher_icons/folder.png');
        $data['calculatorImg'] = public_path() . Storage::disk('local')->url('voucher_icons/calculator.png');
        $data['paymentImg'] = public_path() . Storage::disk('local')->url('voucher_icons/cash_payment.png');
        $data['calendarClockImg'] = public_path() . Storage::disk('local')->url('voucher_icons/calendar-clock-icon2.png');
        $data['starImg'] = public_path() . Storage::disk('local')->url('voucher_icons/star-icon.png');
        $data['tcImg'] = public_path() . Storage::disk('local')->url('voucher_icons/terms-and-conditions-journals.png');
        $data['iataImg'] = public_path() . Storage::disk('local')->url('voucher_icons/IATA.png');
        $data['asataImg'] = public_path() . Storage::disk('local')->url('voucher_icons/ASATA.png');
        //return public_path() . Storage::disk('local')->url('voucher_icons/users.png');

        $view = view('vouchers.guests.pdf_car_voucher', $data)->render();
        //return $view;
        $pdf = resolve('dompdf.wrapper');
        $pdf->loadHTML($view);
        if ($emailVoucher) return $pdf->output();
        else return $pdf->stream('car voucher_' . $voucher->id . '.pdf');

        //if ($printQuote) return $pdf->stream('quotation_' . $quotation->id . '.pdf');
        //elseif ($emailQuote) return $pdf->output();
    }

    /**
     * Email the voucher to a recipient
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function emailVoucher(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'email' => 'bail|required|email',
        ]);

        $voucherAttachment = $this->voucherPDF($voucher, true);
        Mail::to($request->input('email'))->send(new SendVoucher($voucher->clnt_name, $voucherAttachment));

        return response()->json(['success' => 'The voucher has been successfully emailed to the recipient!']);
    }
	
	public function carEmailVoucher(Request $request, Voucher $voucher)
    {
        $this->validate($request, [
            'email' => 'bail|required|email',
        ]);

        $voucherAttachment = $this->carVvoucherPDF($voucher, true);
        Mail::to($request->input('email'))->send(new SendVoucher($voucher->c_pax_name, $voucherAttachment));

        return response()->json(['success' => 'The voucher has been successfully emailed to the recipient!']);
    }
}
