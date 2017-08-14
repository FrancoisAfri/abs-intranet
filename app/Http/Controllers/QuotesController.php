<?php

namespace App\Http\Controllers;

use App\DivisionLevel;
use App\QuoteCompanyProfile;
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
        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->load('divisionLevelGroup');
        $validityPeriods = [7, 14, 30, 60, 90, 120];
        $quoteProfiles = QuoteCompanyProfile::where('status', 1)->where('division_level', $highestLvl->level)->get()->load('divisionLevelGroup');
        //return $quoteProfiles;

        $data['page_title'] = "Quotes";
        $data['page_description'] = "Quotation Settings";
        $data['breadcrumb'] = [
            ['title' => 'Quote', 'path' => '/quote', 'icon' => 'fa fa-file-text-o', 'active' => 0, 'is_module' => 1],
            ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Quote';
        $data['active_rib'] = 'setup';
        $data['highestLvl'] = $highestLvl;
        $data['validityPeriods'] = $validityPeriods;
        $data['quoteProfiles'] = $quoteProfiles;
        AuditReportsController::store('Quote', 'Quote Setup Page Accessed', "Accessed By User", 0);

        return view('quote.quote_setup')->with($data);
    }

    /**
     * Save the quote profile for a specific division.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function saveQuoteProfile(Request $request)
    {
        $this->validate($request, [
            'division_id' => 'bail|required|integer|min:1',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'validity_period' => 'required',
        ]);

        $highestLvl = DivisionLevel::where('active', 1)->orderBy('level', 'desc')->limit(1)->get()->first()->level;

        $quoteProfile = new QuoteCompanyProfile($request->all());
        $quoteProfile->division_level = $highestLvl;
        $quoteProfile->status = 1;
        $quoteProfile->save();

        //Upload the letter head image
        if ($request->hasFile('letter_head')) {
            $fileExt = $request->file('letter_head')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('letter_head')->isValid()) {
                $fileName = $quoteProfile->id . "_letter_head_" . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'New Quote Profile Added', "Added By User", 0);
        return response()->json(['profile_id' => $quoteProfile->id], 200);
    }

    /**
     * Save the quote profile for a specific division.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\QuoteCompanyProfile $quoteProfile
     * @return \Illuminate\Http\Response
     */
    public function updateQuoteProfile(Request $request, QuoteCompanyProfile $quoteProfile)
    {
        $this->validate($request, [
            'division_id' => 'bail|required|integer|min:1',
            'bank_name' => 'required',
            'bank_account_name' => 'required',
            'bank_account_number' => 'required',
            'validity_period' => 'required',
        ]);

        //$quoteProfile = new QuoteCompanyProfile($request->all());
        $quoteProfile->update($request->all());

        //Upload the letter head image
        if ($request->hasFile('letter_head')) {
            $fileExt = $request->file('letter_head')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('letter_head')->isValid()) {
                $fileName = $quoteProfile->id . "_letter_head_" . '.' . $fileExt;
                $request->file('letter_head')->storeAs('letterheads', $fileName);
                //Update file name in the appraisal_perks table
                $quoteProfile->letter_head = $fileName;
                $quoteProfile->update();
            }
        }

        AuditReportsController::store('Quote', 'Quote Profile Edited. (ID: )' . $quoteProfile->id, "Edited By User", 0);
        return response()->json(['profile_id' => $quoteProfile->id], 200);
    }
}
