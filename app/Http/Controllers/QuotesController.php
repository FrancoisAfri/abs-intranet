<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class QuotesController extends Controller
{
    /**
     * Class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the quote setup page.
     *
     * @return \Illuminate\Http\Response
     */
    public function setupIndex()
    {
        $data['page_title'] = "Quotes";
        $data['page_description'] = "Quotation Settings";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'setup';
        AuditReportsController::store('Security', 'Quote Setup Page Accessed', "Accessed By User", 0);
        return view('quote.quote_setup')->with($data);
    }
}
