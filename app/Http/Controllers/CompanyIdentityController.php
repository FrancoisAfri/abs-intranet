<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Schema;

class CompanyIdentityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Save or update company identity data.
     *
     * @param   \App\Http\Requests
     * @return  \Illuminate\Http\Response
     */
    public function saveOrUpdate(Request $request)
    {
        $compDetails = CompanyIdentity::first();
        //$compDetails = (Schema::hasTable('company_identities')) ? CompanyIdentity::first() : null;
        if ($compDetails) { //update
            $compDetails->update($request->all());

            //Upload company logo if any
            $this->uploadLogo($request, $compDetails);
        } else { //insert
            $compDetails = new CompanyIdentity($request->all());
            $compDetails->save();

            //Upload company logo if any
            $this->uploadLogo($request, $compDetails);
        }

        return back()->with('changes_saved', 'Your changes have been saved successfully.');
    }

    /**
     * Helper function to upload logo files.
     *
     * @param   \App\Http\Requests
     * @param   \App\CompanyIdentity
     */
    private function uploadLogo(Request $request, CompanyIdentity $compDetails) {
        if ($request->hasFile('company_logo')) {
            $fileExt = $request->file('company_logo')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('company_logo')->isValid()) {
                $fileName = "logo_" . time() . '.' . $fileExt;
                $request->file('company_logo')->storeAs('logos', $fileName);
                //Update file name in the database
                $compDetails->company_logo = $fileName;
                $compDetails->update();
            }
        }
    }
}
