<?php

namespace App\Http\Controllers\Employee;

use App\DivisionLevel;
use App\EmployeeTasks;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Models\StoreRoom;
use App\Models\Video;
use App\modules;
use App\Province;
use App\Traits\BreadCrumpTrait;
use App\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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

        //$status = !empty($request['status_id']) ? $request['status_id'] : 'empty';

        //dd(isset($request['status_id']));

        if (!empty($request['status_id'])) {
            $status = 1;
        } else
            $status = $request['status_id'];

        $employee = HRPerson::getAllEmployeesByStatus($status, 0, 'get');

        $data = $this->breadCrump(
            "Employee Records",
            "Employee Management", "fa fa-lock",
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
        $data['employee'] = $employee;

        return view('Employees.employee_management')->with($data);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @return Factory|Application|View
     */
    public function show($id)
    {

        $videos = Video::all();


        $slugs = explode("-", str_replace('_', ' ', $id));

        $employee = HRPerson::getAllEmployeesByStatus($status = 1, $slugs[1], 'first');

        $userID = User::where('id', $slugs[1])->first();
        $user = $userID->load('person');


        $divLevel1 = (!empty($employee['division_level_1'])) ? $employee['division_level_1'] : 0;
        $divLevel2 = (!empty($employee['division_level_2'])) ? $employee['division_level_2'] : 0;
        $divLevel3 = (!empty($employee['division_level_3'])) ? $employee['division_level_3'] : 0;
        $divLevel4 = (!empty($employee['division_level_4'])) ? $employee['division_level_4'] : 0;
        $divLevel5 = (!empty($employee['division_level_5'])) ? $employee['division_level_5'] : 0;


        $hrPersonID = $slugs[1];
        $moduleID = 1;
        $status = 1;

        $specificVids = Video::getVideosByUser($divLevel1, $divLevel2, $divLevel3, $divLevel4, $divLevel5);

        $generalVids = Video::getAllGeneralVideos();


        $MaritalStatus = [
            1 => 'Single',
            2 => 'Married',
            3 => 'Divorced',
            4 => 'Widower',
        ];
        $Ethnicity = [
            1 => 'African',
            2 => 'Asian',
            3 => 'Caucasian',
            4 => 'Coloured',
            5 => 'Indian',
            6 => 'White',
        ];

        $leaveProfiles = [
            1 => 'Employee with no leave',
            2 => '5 Day Employee',
            3 => '6 Day Employee',
            4 => 'Shift Worker',
        ];

        $data = $this->breadCrump(
            "Employee Records",
            "Employee Management", "fa fa-lock",
            "Employee Management",
            "Employee Management",
            "/hr",
            "Employee Management",
            "Employee Management"
        );

        $m_silhouette = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $f_silhouette = Storage::disk('local')->url('avatars/f-silhouette.jpg');


        $taskStatus = array(1 => 'Not Started', 2 => 'In Progress', 3 => 'Paused', 4 => 'Completed');

        $checkTasks = EmployeeTasks::
        select('employee_tasks.description', 'employee_tasks.start_date', 'employee_tasks.manager_duration'
            , 'employee_tasks.employee_id', 'employee_tasks.upload_required'
            , 'employee_tasks.order_no', 'employee_tasks.status', 'employee_tasks.due_date'
            , 'employee_tasks.id as task_id', 'contact_companies.name as client_name'
            , 'employee_tasks.duration', 'employee_tasks.date_paused'
            , 'employee_tasks.date_started', 'employee_tasks.document_on_task')
            ->leftJoin('client_inductions', 'employee_tasks.induction_id', '=', 'client_inductions.id')
            ->leftJoin('contact_companies', 'client_inductions.company_id', '=', 'contact_companies.id')
            ->where('employee_tasks.employee_id', $user->person->id)
            ->where('employee_tasks.start_date', '<=', strtotime(date('Y-m-d')))
            ->where('employee_tasks.status', '<', 4)
            ->orderBy('client_name')
            ->orderBy('employee_tasks.order_no')
            ->get();


        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $leave_profile = DB::table('leave_profile')->orderBy('name', 'asc')->get();
        $employees = HRPerson::where('status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $positions = DB::table('hr_positions')->where('status', 1)->orderBy('name', 'asc')->get();
        $division_levels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();


        $data['marital_statuses'] = $marital_statuses;
        $data['ethnicities'] = $ethnicities;
        $data['checkTasks'] = $checkTasks;
        $data['specific'] = $specificVids;
        $data['general'] = $generalVids;
        $data['user'] = $user;
        $data['employees'] = $employees;
        $data['positions'] = $positions;
        $data['leave_profile'] = $leave_profile;
        $data['provinces'] = $provinces;
        $data['view_by_admin'] = 1;
        $data['division_levels'] = $division_levels;
        $data['videos'] = $videos;
        $data['leaveProfiles'] = $leaveProfiles;
        $data['m_silhouette'] = $m_silhouette;
        $data['f_silhouette'] = $f_silhouette;
        $data['Ethnicity'] = $Ethnicity;
        $data['employee'] = $employee;
        $data['MaritalStatus'] = $MaritalStatus;
        return view('Employees.view_User')->with($data);
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

    public function activate(User $user): RedirectResponse
    {

        $user->status == 1 ? $stastus = 0 : $stastus = 1;
        $user->status = $stastus;
        $user->update();

        Alert::success('Status changed', 'Status changed Successfully');

        AuditReportsController::store('User Management', 'User Status Changed', "User status Changed", 0);
        return back();
    }


}
