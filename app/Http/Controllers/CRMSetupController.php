<?php

namespace App\Http\Controllers;

use App\EmailTemplate;
use Illuminate\Http\Request;

use App\Http\Requests;

class CRMSetupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $sendInvoiceTemplate = EmailTemplate::where('template_key', 'send_invoice')->first();

        $data['page_title'] = "CRM";
        $data['page_description'] = "CRM Settings";
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/quote', 'icon' => 'fa fa-handshake-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'setup';
        $data['sendInvoiceTemplate'] = $sendInvoiceTemplate;
        AuditReportsController::store('CRM', 'CRM Setup Page Accessed', "Accessed By User", 0);

        return view('crm.setup')->with($data);
    }
}
