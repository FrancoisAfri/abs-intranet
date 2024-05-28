<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\DivisionLevel;
use App\Models\Onboarding;
use App\Province;
use App\Http\Requests\SaveOnboardingRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Mail\OnboardingApproval;
use App\Http\Requests;
use App\HRPerson;
use App\Traits\StoreImageTrait;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
class OnboardingGuest extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('guest');
    }
	
    public function index()
    {
		$companyDetails = CompanyIdentity::systemSettings();
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $businessCard = DB::table('business_card')->get();
        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
		$employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();
        
        $data['page_title'] = "New Employee";
        $data['page_description'] = "New Employee Form";
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['positions'] = $positions;
        $data['division_levels'] = $divisionLevels;
        $data['marital_statuses'] = $marital_statuses;
        $data['leave_profile'] = $leave_profile;
        $data['employees'] = $employees;
        $data['breadcrumb'] = [
            ['title' => 'Employee Records', 'path' => '/guest/emp-form', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'New Employee Form details', 'active' => 1, 'is_module' => 0]
        ];
        $data['skinColor'] = $companyDetails['sys_theme_color'];
        $data['headerAcronymBold'] = $companyDetails['header_acronym_bold'];
        $data['headerAcronymRegular'] = $companyDetails['header_acronym_regular'];
        $data['headerNameBold'] = $companyDetails['header_name_bold'];
        $data['headerNameRegular'] = $companyDetails['header_name_regular'];
        $data['company_logo'] = $companyDetails['company_logo_url'];
        AuditReportsController::store('Employee Records', 'New Employee Form Accessed', "On Edit Mode", 0);
        return view('onboarding.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaveOnboardingRequest $request)
    {
		die('jndjdnjdnj');
		if (isset($request['date_joined'])) {
            $request['date_joined'] = str_replace('/', '-', $request['date_joined']);
            $request['date_joined'] = strtotime($request['date_joined']);
        }
		if (isset($request['date_of_birth'])) {
            $request['date_of_birth'] = str_replace('/', '-', $request['date_of_birth']);
            $request['date_of_birth'] = strtotime($request['date_of_birth']);
        }
		if (isset($request['med_start_date'])) {
            $request['med_start_date'] = str_replace('/', '-', $request['med_start_date']);
            $request['med_start_date'] = strtotime($request['med_start_date']);
        }
		if (isset($request['provident_start_date'])) {
            $request['provident_start_date'] = str_replace('/', '-', $request['provident_start_date']);
            $request['provident_start_date'] = strtotime($request['provident_start_date']);
        }
		$request['status'] = 1;
        $onboarding = Onboarding::create($request->all());
		
		$loan_configuration = DB::table('staff_loan_setups')->where("id", 1)->first();
        $hr = !empty($loan_configuration->hr) ? $loan_configuration->hr : 0;
		$hrUser = HRPerson::where('id',$hr)->first();
		// send email
		if (!empty($hrUser['email']))
            Mail::to($hrUser['email'])->send(new OnboardingApproval($hrUser['first_name']));
				
		AuditReportsController::store('Employee Records', 'New Employee For Saved', "Saved By User", 0);;
        Alert::toast('Record Updated Successfully, An email was sent to Hr for approval', 'success');
		return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
