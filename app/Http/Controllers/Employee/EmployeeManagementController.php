<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevelFour;
use App\DivisionLevelThree;
use App\DivisionLevelTwo;
use App\ManualClockin;
use App\TrainingDocuments;
use App\DivisionLevel;
use App\EmployeeTasks;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveApplicationController;
use App\Models\AssetTransfers;
use App\Models\LicencesAllocation;
use App\Models\StoreRoom;
use App\Models\Video;
use App\modules;
use App\module_access;
use App\Province;
use App\employee_documents;
use App\Traits\BreadCrumpTrait;
use App\User;
use App\doc_type;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RealRashid\SweetAlert\Facades\Alert;

class EmployeeManagementController extends Controller
{
    use BreadCrumpTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!empty($request['status_id'])) {
            $status = 1;
        } else
            $status = $request['status_id'];

        $employee = HRPerson::getAllEmployeesByStatus($status, 0, 'get');
        //return $employee;

        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->get();

        $data = $this->breadCrump(
            "Employee Records",
            "Search", "fa fa-lock",
            "Employee Management",
            "Employee Management",
            "/hr",
            "Employee Management",
            "Employee Management"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['employees'] = $employees;
        $data['employee'] = $employee;

        return view('Employees.employee_management')->with($data);
    }

    // clockin Report
    public function clockinReports(Request $request)
    {
        //Inputs
        $employeID = !empty($request['employee_number']) ? $request['employee_number'] : 0;
        $date = !empty($request['action_date']) ? $request['action_date'] : 0;
        $clocktypes = !empty($request['clockin_type']) ? $request['clockin_type'] : 0;
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->get();
        // get data
        $clockins = ManualClockin::getAllattendance($clocktypes, $employeID, $date);
        //return $clockins;
        $data = $this->breadCrump(
            "Employee Records",
            "Clockin Report", "fa fa-lock",
            "Employee",
            "Clockin Report",
            "employee/clockin_report",
            "Employee Management",
            "Employee Management"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['clockins'] = $clockins;
        $data['employees'] = $employees;

        return view('Employees.clockin_report')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clockin()
    {
        // get user details
        $user = Auth::user()->load('person');
        // check if user clockin

        $clockin = ManualClockin::checkClockin($user->person->employee_number);
        $clockout = ManualClockin::checkClockout($user->person->employee_number);

        $data = $this->breadCrump(
            "Employee Records",
            "Clockin", "fa fa-lock",
            "Employee",
            "Attendance Management",
            "/hr",
            "Employee Management",
            "Employee Management"
        );

        $data['clockout'] = $clockout;
        $data['clockin'] = $clockin;
        $data['user'] = $user;

        return view('Employees.clockin')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        //dd($request);
        /**
         * get user location
         */
        $latLong = $request['latitudes'] . ',' . $request['longitudes'];

        if (!empty($request['latitudes']) && !empty($request['longitudes']))
            $location = $this->getLocation($latLong);
        else $location = 'User did not allowed the location to be shared';

        $user = Auth::user()->load('person');
        $status = !empty($request['clockin']) ? 1 : 2;

        ManualClockin::create([
            'ip_addresss' => $request->ip(),
            'hr_id' => $user->person->id,
            'clockin_type' => $status,
            'clockin_time' => strtotime(date('Y-m-d H:i:s')),
            'location' => $location,
            'employee_number' => $user->person->employee_number
        ]);

        return redirect()->route('employee.clockin')->with('status', 'Clockin Saved!');
    }

    /**
     * @param $latlong
     * @return mixed|string
     */
    private function getLocation($latlong)
    {

        $APIKEY = env('GOOGLE_KEY');
        $APIKEY = 'AIzaSyBfSC6GdEljNfHpJGW8ryGX-Ragq93kfdU';
        $googleMapsUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&language=ar&key=" . $APIKEY;

        $response = file_get_contents($googleMapsUrl);
        $response = json_decode($response, true);
        $results = $response["results"];
        $location = '';
        ///////
        ///
        foreach ($results as $component) {

            $arrayAddress = (explode(",", $component["formatted_address"]));
            if (!empty($arrayAddress[0]) && !empty($arrayAddress[1]) && !empty($arrayAddress[2]) && !empty($arrayAddress[3])) {
                $matches = array();
                preg_match_all('!\d+!', $arrayAddress[3], $matches);
				if (!empty($matches[0][0])) $match = $matches[0][0];
				else $match = '';
                $location = $arrayAddress[0] . ', ' . $arrayAddress[1] . ', ' . $arrayAddress[2] . ', ' . $match;
                break;
            }
        }

        if (empty($location)) {
            echo "Failed to get CityName";
        } else {
            echo $location;
        }

        return $location;
    }

    /**
     * @return string|void
     */
    private function getIp()
    {
        foreach (array('HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $IPaddress) {
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                            FILTER_VALIDATE_IP,
                            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        } // it will return server ip when no client ip found
    }

    /**
     * @param $id
     * @return Factory|Application|View
     */
    public function show($id)
    {
        $videos = Video::all();
		$now = time();
        $slugs = explode("-", str_replace('_', ' ', $id));

        $employee = HRPerson::getEmployee($slugs[1]);

        //get manager details
        if (!empty($employee->manager_id))
            $managerDetails = HRPerson::getManagername($employee->manager_id);
        else $managerDetails = '';
        //get second manager details
        if (!empty($employee->second_manager_id))
            $secondmanagerDetails = HRPerson::getSecondManagername($employee->second_manager_id);
        else $secondmanagerDetails = '';
        //chexk user right
        $userLog = Auth::user()->load('person');
        $objModAccess = module_access::where('module_id', 6)->where('user_id', $userLog->id)->get();
        if ($objModAccess && count($objModAccess) > 0)
            $modAccess = $objModAccess->first()->access_level;
        else
            $modAccess = 0;

        $userID = User::where('id', $employee->user_id)->first();
        $user = $userID->load('person');

        $assets = AssetTransfers::getAssetByUser($slugs[1]);

        $license_allocation = LicencesAllocation::getLicenceAllocation($slugs[1]);

        $divLevel1 = (!empty($employee['division_level_1'])) ? $employee['division_level_1'] : 0;
        $divLevel2 = (!empty($employee['division_level_2'])) ? $employee['division_level_2'] : 0;
        $divLevel3 = (!empty($employee['division_level_3'])) ? $employee['division_level_3'] : 0;
        $divLevel4 = (!empty($employee['division_level_4'])) ? $employee['division_level_4'] : 0;
        $divLevel5 = (!empty($employee['division_level_5'])) ? $employee['division_level_5'] : 0;

        $hrPersonID = $slugs[1];
        $moduleID = 1;
        $status = 1;

        $trainingDocs = TrainingDocuments::getDocuments($divLevel3, $divLevel4, $divLevel5);

        $specificVids = Video::getVideosByUser($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5);

        $generalVids = Video::getAllGeneralVideos();

        $MaritalStatus = [1 => 'Single',2 => 'Married',3 => 'Divorced',4 => 'Widower'];
        $Ethnicity = [1 => 'African',2 => 'Asian',3 => 'Caucasian',4 => 'Coloured',5 => 'Indian',6 => 'White'];
        $leaveProfiles = [ 1 => 'Employee with no leave',2 => '5 Day Employee',3 => '6 Day Employee',4 => 'Shift Worker'];
        $titles = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];
        $disabilities = [ 1 => 'Yes',2 => 'No'];
        $employmentTypes = [ 1 => 'Permanent',2 => 'Temporary'];
        $occupationalLevels = [ 1 => 'Senior Management',2 => 'Middle Management',3 => 'Junior Management',4 => 'Semi Skilled',4 => 'Unskilled'];
        $jobFunctions = [ 1 => 'Mr',2 => 'Miss',3 => 'Ms',4 => 'Dr'];

        $data = $this->breadCrump(
            "Employee Records",
            "Search", "fa fa-lock",
            "Employee Management",
            "Employee Management",
            "/hr",
            "Employee Management",
            "Employee Management"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');

        $taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');

        // task list
        $tasks = EmployeeTasks::
        select('employee_tasks.description', 'employee_tasks.start_date', 'employee_tasks.manager_duration'
            , 'employee_tasks.employee_id', 'employee_tasks.upload_required'
            , 'employee_tasks.order_no', 'employee_tasks.status', 'employee_tasks.due_date'
            , 'employee_tasks.id as task_id', 'contact_companies.name as client_name'
            , 'employee_tasks.duration', 'employee_tasks.date_paused'
            , 'employee_tasks.date_started', 'employee_tasks.document_on_task')
            ->leftJoin('contact_companies', 'employee_tasks.client_id', '=', 'contact_companies.id')
            ->where('employee_tasks.employee_id', $employee->id)
            ->where('employee_tasks.status', '<', 4)
            ->orderBy('client_name')
            ->orderBy('employee_tasks.order_no')
            ->get();

        $taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        $division_levels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        $routeUser = str_replace(' ', '_', strtolower($employee->first_name)) . '-' . $employee->id . '-' . str_replace(' ', '_', strtolower($employee->surname));
        /// leave code
        #leave Balance
        /**
         *
         */
        $balances = DB::table('leave_credit')
            ->select('leave_credit.*', 'leave_types.name as leavetype')
            ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
            ->where('leave_credit.hr_id', $employee->id)
            ->orderBy('leave_types.name')
            ->get();
		foreach ($balances as $balance)
		{
			$availableBalance = DB::table('leave_application')
			->select(DB::raw('SUM(leave_taken) as total'))
			->where('hr_id', $employee->id)
			->where('leave_type_id', $balance->leave_type_id)
			->where('start_date', '>', $now)  // Replace 'now()' with 'today()' if you're using Carbon for the date.
			->where('status', '=', 1)  // Replace 'now()' with 'today()' if you're using Carbon for the date.
			->first();
			
			if (!empty($availableBalance->total))
				$balance->available_balance = $availableBalance->total;
		}
		#leave Application
        $application = DB::table('leave_application')
            ->select('leave_application.*', 'leave_types.name as leavetype')
            ->leftJoin('leave_types', 'leave_application.leave_type_id', '=', 'leave_types.id')
            ->where('leave_application.hr_id', $employee->id)
            ->orderBy('leave_application.id', 'desc')
            ->limit(15)
            ->get();
        // get surbodinates leave balances
        $surbodinateArray = array();

        $surbs = HRPerson::where('status', 1)->where('manager_id', $employee->id)->first();
        $surbodinates = HRPerson::where('status', 1)->where('manager_id', $employee->id)->pluck('id');
        if (!empty($surbodinates)) {
            foreach ($surbodinates as $surbodinate) {
                $surbodinateArray[] = $surbodinate;
            }

            $surbodinateBalances = DB::table('leave_credit')
                ->select('leave_credit.*', 'leave_types.name as leave_types'
                    , 'hr_people.first_name as hr_first_name', 'hr_people.surname as hr_surname'
                    , 'hr_people.employee_number as hr_employee_number')
                ->leftJoin('leave_types', 'leave_credit.leave_type_id', '=', 'leave_types.id')
                ->leftJoin('hr_people', 'leave_credit.hr_id', '=', 'hr_people.id')
                ->whereIn('leave_credit.hr_id', $surbodinateArray)
                ->orderBy('hr_people.first_name')
                ->orderBy('hr_people.surname')
                ->orderBy('leave_types.name')
                ->get();
        }
        // get active modules
        $activeModules = modules::where('active', 1)->get();
        // get document
        $documents = employee_documents::orderby('doc_description', 'asc')->where('hr_person_id', $employee->id)->get();
        if (!empty($documents)) $documents = $documents->load('documentType');
        $types = doc_type::where('active', 1)->orderBy('name', 'asc')->get();

        $data['leaveStatusNames'] = LeaveApplicationController::status();
        $data['license_allocation'] = $license_allocation;
        $data['documents'] = $documents;
        $data['activeModules'] = $activeModules;
        $data['surbs'] = $surbs;
        $data['surbodinates'] = $surbodinates;
        if (!empty($surbodinates))
            $data['surbodinateBalances'] = $surbodinateBalances;
        $data['balances'] = $balances;
        $data['types'] = $types;
        $data['application'] = $application;
        $data['routeUser'] = $routeUser;
        $data['modAccess'] = $modAccess;
        $data['taskStatus'] = $taskStatus;
        $data['titles'] = $titles;
        $data['disabilities'] = $disabilities;
        $data['employmentTypes'] = $employmentTypes;
        $data['occupationalLevels'] = $occupationalLevels;
        $data['jobFunctions'] = $jobFunctions;
        $data['marital_statuses'] = $marital_statuses;
        $data['ethnicities'] = $ethnicities;
        $data['specific'] = $specificVids;
        $data['general'] = $generalVids;
        $data['secondmanagerDetails'] = $secondmanagerDetails;
        $data['managerDetails'] = $managerDetails;
        $data['assets'] = $assets;
        $data['trainingDocs'] = $trainingDocs;
        $data['user'] = $user;
        $data['employees'] = $employees;
        $data['positions'] = $positions;
        $data['leave_profile'] = $leave_profile;
        $data['provinces'] = $provinces;
        $data['view_by_admin'] = 1;
        $data['division_levels'] = $division_levels;
        $data['videos'] = $videos;
        $data['tasks'] = $tasks;
        $data['leaveProfiles'] = $leaveProfiles;
        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['Ethnicity'] = $Ethnicity;
        $data['employee'] = $employee;
        $data['MaritalStatus'] = $MaritalStatus;
        return view('Employees.view_user')->with($data);
    }

    public function addDoc(Request $request)
    {
        $this->validate($request, [
            'doc_description' => 'required',
            'supporting_docs' => 'required',
            'doc_type_id' => 'required',
            'hr_person_id' => 'required',
        ]);

        $contactsEmpdocs = $request->all();
        unset($contactsEmpdocs['_token']);
        if (!empty($contactsEmpdocs['date_from'])) {
            $Datefrom = $contactsEmpdocs['date_from'] = str_replace('/', '-', $contactsEmpdocs['date_from']);
            $Datefrom = $contactsEmpdocs['date_from'] = strtotime($contactsEmpdocs['date_from']);
        } else $Datefrom = 0;
        if (!empty($contactsEmpdocs['expirydate'])) {
            $Expirydate = $contactsEmpdocs['expirydate'] = str_replace('/', '-', $contactsEmpdocs['expirydate']);
            $Expirydate = $contactsEmpdocs['expirydate'] = strtotime($contactsEmpdocs['expirydate']);
        } else $Expirydate = 0;

        $employeeDoc = new employee_documents();
        $employeeDoc->doc_description = $contactsEmpdocs['doc_description'];
        $employeeDoc->date_from = $Datefrom;
        $employeeDoc->expirydate = $Expirydate;
        $employeeDoc->hr_person_id = $contactsEmpdocs['hr_person_id'];
        $employeeDoc->doc_type_id = $contactsEmpdocs['doc_type_id'];
        $employeeDoc->save();
        $employee = HRPerson::find($contactsEmpdocs['hr_person_id']);
        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_employee_documents." . $fileExt;
                $request->file('supporting_docs')->storeAs('Employee/documents', $fileName);
                //Update file name in the table
                $employeeDoc->supporting_docs = $fileName;
                $employeeDoc->update();
            }
        }
        AuditReportsController::store('Contacts', 'Company Document Added', "Company Document added , Document Name: $contactsEmpdocs[doc_description], 
		Document description: $contactsEmpdocs[doc_description], Document expiry date: $Expirydate ,  Name : $employee->first_name $employee->surname", 0);
        return response()->json();
    }

    // edit employee doc
    public function editdoc(Request $request, employee_documents $doc)
    {
        $this->validate($request, [
            'description_update' => 'required',
//            'name' => 'required|unique:contactsEmpdocs,name',
//            'exp_date' => 'required',
            'doc_type_update' => 'required',
        ]);

        $contactsEmpdocs = $request->all();
        unset($contactsEmpdocs['_token']);

        $Datefrom = $contactsEmpdocs['date_from_update'] = str_replace('/', '-', $contactsEmpdocs['date_from_update']);
        $Datefrom = $contactsEmpdocs['date_from_update'] = strtotime($contactsEmpdocs['date_from_update']);

        $Expirydatet = $contactsEmpdocs['expirydate'] = str_replace('/', '-', $contactsEmpdocs['expirydate']);
        $Expirydate = $contactsEmpdocs['expirydate'] = str_replace('/', '-', $contactsEmpdocs['expirydate']);
        $Expirydate = $contactsEmpdocs['expirydate'] = strtotime($contactsEmpdocs['expirydate']);
        // update database
        $doc->doc_description = $contactsEmpdocs['description_update'];
        $doc->date_from = $Datefrom;
        $doc->expirydate = $Expirydate;
        $doc->doc_type_id = $contactsEmpdocs['doc_type_update'];
        $doc->update();

        //Upload supporting document
        if ($request->hasFile('supporting_docs_update')) {
            $fileExt = $request->file('supporting_docs_update')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs_update')->isValid()) {
                $fileName = time() . "_employee_documents." . $fileExt;
                $request->file('supporting_docs_update')->storeAs('Employee/documents', $fileName);
                //Update file name in the table
                $doc->supporting_docs = $fileName;
                $doc->update();
            }
        }

        $employee = HRPerson::find($doc->hr_person_id);

        AuditReportsController::store('Contacts', 'Document Updated', "Company Document Updated,, 
		Document description: $contactsEmpdocs[description_update], Document expiry date: $Expirydatet, Name : $employee->first_name $employee->surname ", 0);
        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function activate(User $user ): RedirectResponse
    {

        $user->status == 1 ? $stastus = 0 : $stastus = 1;
        $user->status = $stastus;
        $user->update();

        // update the hr_people
        $hrPerson = HRPerson::where('user_id', $user->id)->first();
        $hrPerson->status == 1 ? $stastus = 0 : $stastus = 1;
        $hrPerson->status = $stastus;
        $hrPerson->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('User Management', 'User Status Changed', "User status Changed", 0);
        return back();
    }

    public function organogramView()
    {

        $division_levels = DivisionLevel::with(
            'Div5.manager',
            'Div4',
            'Div3',
            'Div2',
            'Div1',
        )->where('active', 1)->orderBy('id', 'desc')->get();

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');


        $data = $this->breadCrump(
            "Employee Records",
            "Organogram", "fa fa-lock",
            "Employee Management",
            "Employee Management",
            "/hr",
            "Employee Management",
            "Employee Management"
        );

        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['division_levels'] = $division_levels;
        return view('Employees.organisation_chart')->with($data);
    }


}
