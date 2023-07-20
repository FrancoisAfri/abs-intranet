<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use Illuminate\Http\Request;

use App\Traits\StoreImageTrait;
use App\Http\Requests;
use Illuminate\Support\Facades\Schema;

class CompanyIdentityController extends Controller
{

    use StoreImageTrait;

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

        $this->validate($request, [
            'mailing_address' => 'email',
            'support_email' => 'email',
            'company_logo' => 'image|mimes:jpeg,png,jpg|max:3000',
            'login_background_image' => 'image|mimes:jpeg,png,jpg|max:3000',
        ]);

        $compDetails = CompanyIdentity::first();
		
        //$compDetails = (Schema::hasTable('company_identities')) ? CompanyIdentity::first() : null;
        if ($compDetails) { //update
            $compDetails->update($request->all());

            //Upload company logo if any
            $this->uploadLogo($request, $compDetails);
            $this->uploadLoginImage($request, $compDetails);
            $this->uploadSystemImage($request, $compDetails);
			$this->uploadAdvertImage($request, $compDetails);
        } else { //insert
            $compDetails = new CompanyIdentity($request->all());
            $compDetails->save();

            //Upload company logo if any
            $this->uploadLogo($request, $compDetails);
            $this->uploadLoginImage($request, $compDetails);
            $this->uploadSystemImage($request, $compDetails);
			$this->uploadAdvertImage($request, $compDetails);
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
        $formInput = $request->all();
        $formInput['company_logo'] = $this->verifyAndStoreImage('logos', 'company_logo', $compDetails, $request);
    } 
	private function uploadLoginImage(Request $request, CompanyIdentity $compDetails) {
        $formInput = $request->all();
        $formInput['login_background_image'] = $this->verifyAndStoreImage('logos', 'login_background_image', $compDetails, $request);
    }
	private function uploadSystemImage(Request $request, CompanyIdentity $compDetails) {
            $formInput = $request->all();
            $formInput['system_background_image_'] = $this->verifyAndStoreImage('logos', 'system_background_image', $compDetails, $request);
    }
	private function uploadAdvertImage(Request $request, CompanyIdentity $compDetails) {
        if ($request->hasFile('brought_to_text_image')) {
            $fileExt = $request->file('brought_to_text_image')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('brought_to_text_image')->isValid()) {
                $fileName = "system_advert_image_" . time() . '.' . $fileExt;
                $request->file('brought_to_text_image')->storeAs('logos', $fileName);
                //Update file name in the database
                $compDetails->brought_to_text_image = $fileName;
                $compDetails->update();
            }
        }
    }
}
