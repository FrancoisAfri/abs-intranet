<?php

namespace App\Http\Controllers;

use App\EmployeeTasks;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class TaskTimerController extends Controller
{
    /**
     * Update the duration of a task.
     *
     * @param  \App\EmployeeTasks  $task
     * @param  $timeInSeconds
     * @return \Illuminate\Http\Response
     */
    public function updateDuration(EmployeeTasks $task, $timeInSeconds)
    {
        $user = Auth::user()->load('person');
        $inProgress = EmployeeTasks::where('employee_id', $user->person->id)->where('status', 2)->whereNotIn('id', [$task->id])->get();
        if (count($inProgress) > 0)
            return response()->json(['error_starting' => 'You can not start this task, You have another task in progress.'], 422);

        $auditAction = ['' => '', 2 => 'Task Started', 3 => 'Task Paused'];
        $status = $task->status;
        if ($status == 1) //task is new
        {
            $status = 2;
            $task->date_started = time();
        } elseif ($status == 3) //task paused
        {
            $status = 2;
        } elseif ($status == 2) //task is running
        {
            $status = 3;
            $task->date_paused =  time();
        }
        $task->status = $status;
//
        $timeInSeconds = (int) $timeInSeconds;
        $task->duration = ($timeInSeconds > $task->duration) ? $timeInSeconds : $task->duration;
        $task->update();
        AuditReportsController::store('Task Management', "$auditAction[$status] [Task#: $task->id]", "By User", 0);
        return response()->json(['success' => 'Task Status Changed.'], 200);
    }

    /**
     * Get the duration of a task.
     *
     * @param  \App\EmployeeTasks  $task
     * @return  $task->duration
     */
    public function getDuration(EmployeeTasks $task)
    {
        $user = Auth::user()->load('person');
        $inProgress = EmployeeTasks::where('employee_id', $user->person->id)->where('status', 2)->whereNotIn('id', [$task->id])->get();
        if (count($inProgress) > 0)
            return response()->json(['error_starting' => 'You can not start this task, You have another task in progress.'], 422);

        //change status to running
        $task->status = 2;
        $task->update();

        AuditReportsController::store('Task Management', "Task Status Changed [Task#: $task->id]", "By User", 0);
        return $task->duration;
    }
}
