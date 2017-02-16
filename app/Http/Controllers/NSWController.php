<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\nswStx;
use App\contacts_company;
use App\nswStx_educator;
use App\nswStx_grade;
use App\HRPerson;
use App\User;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class NSWController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
        $data['page_title'] = "Group Learner Registration";
        $data['page_description'] = "Register Group(s) of Learners";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Group learner registration', 'active' => 1, 'is_module' => 0]
        ];
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $data['ethnicities'] = $ethnicities;
        $data['schools'] = $schools;
        $data['active_mod'] = 'clients';
        $data['active_rib'] = 'group learner registration';
		AuditReportsController::store('Clients', 'Group Learner Registration Form Viewed ', "Actioned By User", 0);
        return view('education.add_nsw')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
        $data['page_title'] = "Group Learner Registration";
        $data['page_description'] = "Register Group(s) of Learners";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Group learner registration', 'active' => 1, 'is_module' => 0]
        ];
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $data['ethnicities'] = $ethnicities;
        $data['schools'] = $schools;
        $data['active_mod'] = 'clients';
        $data['active_rib'] = 'group learner registration';
		AuditReportsController::store('Clients', 'Group Learner Registration Form Viewed ', "Actioned By User", 0);
        return view('education.add_nsw')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'school_id' => 'required',
            'date_attended' => 'bail|required|date_format:"d/m/Y"',
            'educator_first_name' => 'required',
            'educator_surname' => 'required',
            'grade' => 'required',
        ]);
        $groupLearnerData = $request->all();

        //Exclude empty fields from query
        foreach ($groupLearnerData as $key => $value)
        {
            if (empty($groupLearnerData[$key])) {
                unset($groupLearnerData[$key]);
            }
        }
		if (isset($groupLearnerData['date_attended'])) {
            $groupLearnerData['date_attended'] = str_replace('/', '-', $groupLearnerData['date_attended']);
            $groupLearnerData['date_attended'] = strtotime($groupLearnerData['date_attended']);
        }
		//return $groupLearnerData;
		# Save School
		$groupLearner = new nswStx($groupLearnerData);
        $groupLearner->save();
		
		#Save Educator Details
		$educator = new nswStx_educator($groupLearnerData);
        $groupLearner->addStxesEducator($educator);
	
		#Save Grade number
		$numRows = $index = 0;
		$totalFiles = !empty($groupLearnerData['total_files']) ? $groupLearnerData['total_files'] : 0;
		$grade = new nswStx_grade();
		while ($numRows != $totalFiles)
		{
			$index++;
			DB::table('nsw_stx_grades')->insert([
				'grade' => $request->grade[$index],
				'learner_number' => $request->learner_number[$index],
				'male_number' => $request->male_number[$index],
				'female_number' => $request->female_number[$index],
				'african_number' => $request->african_number[$index],
				'caucasian_number' => $request->caucasian_number[$index],
				'coloured_number' => $request->coloured_number[$index],
				'indian_number' => $request->indian_number[$index],
				'asian_number' => $request->asian_number[$index],
				'nsw_id' => $groupLearner->id,
			]);
			$numRows ++;
		}
		AuditReportsController::store('Clients', 'Group Learner Added ', "Actioned By User", 0);
		return redirect('/education/nsw/' . $groupLearner->id . '/edit')->with('success_add', "Group Learner has been added successfully.");
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
    public function edit(nswStx $group)
    {
		$group->load('nswStxesGrades', 'nswStxesEducators');
        $schools = contacts_company::where('company_type', 2)->where('status', 1)->orderBy('name')->get();
        $data['page_title'] = "Group Learner Registration";
        $data['page_description'] = "Register Group(s) of Learners";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Group learner registration', 'active' => 1, 'is_module' => 0]
        ];
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $data['ethnicities'] = $ethnicities;
        $data['schools'] = $schools;
        $data['group'] = $group;
        $data['active_mod'] = 'clients';
        $data['active_rib'] = 'group learner registration';
		AuditReportsController::store('Clients', 'Group Learner Edited ', "Actioned By User", 0);
        return view('education.view_nsw')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, nswStx $group)
    {
        $this->validate($request, [
            'school_id' => 'required',
            'date_attended' => 'bail|required|date_format:"d/m/Y"',
            'educator_first_name' => 'required',
            'educator_surname' => 'required',
            'educators_number' => 'required',
          
        ]);
        $groupLearnerData = $request->all();

        //Exclude empty fields from query
        foreach ($groupLearnerData as $key => $value)
        {
            if (empty($groupLearnerData[$key])) {
                unset($groupLearnerData[$key]);
            }
        }
		if (isset($groupLearnerData['date_attended'])) {
            $groupLearnerData['date_attended'] = str_replace('/', '-', $groupLearnerData['date_attended']);
            $groupLearnerData['date_attended'] = strtotime($groupLearnerData['date_attended']);
        }
		
		# Update School
        $group->update($groupLearnerData);
		
		#Save Educator Details
        $group->addOrUpdateStxesEducator($groupLearnerData);
		AuditReportsController::store('Clients', 'Group Learner Updated ', "Actioned By User", 0);
		return redirect('/education/nsw/' . $group->id . '/edit')->with('success_edit', "Group Learner has been updated successfully.");
    } 
	
	//updateGraderade
	public function updateGraderade(Request $request, nswStx_grade $grade)
    {
        $this->validate($request, [
            'grade' => 'required'
        ]);
                        
        $grade->grade = $request->input('grade');
        $grade->male_number = $request->input('male_number');
        $grade->learner_number = $request->input('learner_number');
        $grade->female_number = $request->input('female_number');
        $grade->african_number = $request->input('african_number');
        $grade->asian_number = $request->input('asian_number');
        $grade->caucasian_number = $request->input('caucasian_number');
        $grade->coloured_number = $request->input('coloured_number');
        $grade->indian_number = $request->input('indian_number');
		# Update grade
        $grade->update();
		AuditReportsController::store('Clients', 'Group Learner Grade Updated ', "Actioned By User", 0);
		return response()->json(['new_grade' => $grade->grade, 'new_male_number' => $grade->male_number], 200);
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
