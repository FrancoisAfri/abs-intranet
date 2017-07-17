<?php

namespace App\Http\Controllers;

use App\AppraisalKPIResult;
use App\AppraisalPerk;
use App\DivisionLevel;
use App\HRPerson;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EmployeeTasksWidgetController extends Controller
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
	
    //returns a group's avg performance from jan to last month or a list of emp from that group and their avg performances
    public static function empGroupPerformance($divID, $divLvl, $meetingtask = false, $inductiontask = false) {
		$atual    = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$today    = mktime(0, 0, 0, date('m'), date('d')+14, date('Y'));
		if (!empty($meetingtask)) $taskType = 2;
		else $taskType = 1;
		$tasks = DB::table('hr_people')
		->select('employee_tasks.id as task_id','employee_tasks.employee_id'
		,'employee_tasks.due_date','employee_tasks.description'
		,'hr_people.first_name as hr_fist_name','hr_people.surname as hr_surname')
		->leftJoin('employee_tasks', 'hr_people.id', '=', 'employee_tasks.employee_id')
		->where(function ($query) use($divID, $divLvl) {
		if ($divLvl == 5) $query->where('division_level_5', $divID);
		elseif ($divLvl == 4) $query->where('division_level_4', $divID);
		elseif ($divLvl == 3) $query->where('division_level_3', $divID);
		elseif ($divLvl == 2) $query->where('division_level_2', $divID);
		elseif ($divLvl == 1) $query->where('division_level_1', $divID);})
		/*->where(function ($query) use ($atual, $today) {
		if ($atual > 0 && $today  > 0) {
			$query->whereBetween('employee_tasks.due_date', [$atual, $today]);
		}
		})*/
		->where('employee_tasks.status', '<', 4)
		->where('employee_tasks.due_date', '<=', $today)
		->where('hr_people.status', '=', 1)
		->where('employee_tasks.task_type', $taskType)
		->orderBy('employee_tasks.employee_id')
		->get();

        $taskLists = [];
        foreach ($tasks as $task) {
			$objEmp = (object) [];
			$objEmp->emp_id = $task->employee_id;
			$objEmp->task_id = $task->task_id;
			$objEmp->emp_full_name = $task->hr_fist_name." ".$task->hr_surname;
			$objEmp->task_desription = $task->description;
			$objEmp->due_date = !empty($task->due_date) ? date("Y-m-d", $task->due_date) : '';
			$taskLists[] = $objEmp;
        }
		return $taskLists;
    }

    //returns employees meeting tasks
    public function getMeetingEmployees($divLevel = 0, $divID = 0) {
        return EmployeeTasksWidgetController::empGroupPerformance($divID, $divLevel, true);
    }

    //returns employees induction tasks
    public function getInductionEmployees($divLevel = 0, $divID = 0) {
        return EmployeeTasksWidgetController::empGroupPerformance($divID, $divLevel, false, true);
    }
}
