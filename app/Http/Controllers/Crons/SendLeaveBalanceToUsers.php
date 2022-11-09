<?php

namespace App\Http\Controllers\Crons;

use App\CompanyIdentity;
use App\DivisionLevelFour;
use App\DivisionLevelTwo;
use App\HRPerson;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\LeaveApplicationController;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_history;
use App\Mail\escalateleaveApplication;
use App\Mail\LeaveBalanceReminder;
use App\Mail\sendManagersListOfAbsentUsersToday;
use App\Mail\managerReminder;
use App\Mail\remindUserToapplyLeave;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Exception\InvalidArgumentException;
use OpenSpout\Common\Exception\IOException;
use OpenSpout\Common\Exception\UnsupportedTypeException;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Exception\WriterNotOpenedException;
use phpDocumentor\Reflection\Types\Array_;
use Rap2hpoutre\FastExcel\FastExcel;


class SendLeaveBalanceToUsers extends Controller
{
    /**
     * @return void
     */
    public function execute()
    {

        $users = HRPerson::where('status', 1)->pluck('id');

        foreach ($users as $empID) {

            $leaveBalance = leave_credit::where('hr_id', $empID)->pluck('leave_balance');

            $hrDetails = HRPerson::getManagerDetails($empID);
            $fullnane = $hrDetails->first_name . ' ' . $hrDetails->surname;
            $datanow = Carbon::now()->toDayDateTimeString();


            if (isset($leaveBalance) == false) {
                $leaveBalance = 0;
            }

            if (!empty($hrDetails->email))
                Mail::to($hrDetails->email)->send(new LeaveBalanceReminder($fullnane, $leaveBalance, $hrDetails->email, $datanow));
        }
    }

    /**
     * @return void
     */
//    public function managerReminder()
//    {
//
//        #check who is the manager
//        #send a reminder
//        #runs everyday
//        $date_now = Carbon::now()->toDayDateTimeString();
//
//        $days = leave_configuration::pluck('number_of_days_to_remind_manager')->first();
//
//        $date = Carbon::today()->subDays($days);
//        dd($date);
//
//        $user = leave_application::where('status', '>=', 2)
//            ->where('created_at', '>=', $date)
//            ->pluck('hr_id');
//
//        $users = $user->unique();
//
//        $outputArray = array();
//        foreach ($users as $empID) {
//            $leaveApplications = leave_application::where(
//                'hr_id', $empID
//            )->get();
//            if (isset($leaveApplications->manager_id))
//                $managerId = $leaveApplications->manager_id;
//
//
//            $hrDetails = HRPerson::getManagerDetails($managerId);
//
//            $outputArray[] = $hrDetails;
//        }
//        // remeove duplicates so that we only send 1 email to diffrent managers
//        $hrDetails = collect($outputArray)->unique();
//
//        //needs improvement
//        foreach ($hrDetails as $hrDetail) {
//            $unapproved = leave_application::getUnapprovedApplications($date, $hrDetail->id);
//
//            $fullnane = $hrDetail->first_name . ' ' . $hrDetail->surname;
//            if (!empty($hrDetail->email))
//                Mail::to($hrDetail->email)->send(new managerReminder($fullnane, $hrDetail->email, $date_now, $unapproved));
//
//        }
//
//    }

    public function managerReminder()
    {

        #check who is the manager
        #send a reminder
        #runs everyday
        $date_now = Carbon::now()->toDayDateTimeString();

        $days = leave_configuration::pluck('number_of_days_to_remind_manager')->first();

        //
        $daysToEscalation = leave_configuration::pluck('mumber_of_days_until_escalation')->first();
        $date_now = Carbon::today();
        $date = Carbon::today()->subDays($daysToEscalation);

        $applications = leave_application::where('status', '>=', 2)
            ->whereBetween('created_at', [$date, $date_now])
            //->where('created_at', '>=', $date)
            ->get();
        dd($applications);


        $applications = leave_application::where('status', '>=', 2)
            ->where('created_at', '=', $date)
            ->get();

        foreach ($applications as $application) {

            if (!empty($application->manager_id) && !empty($application->hr_id)) {
                // get manager details
                $hrDetail = HRPerson::getManagerDetails($application->manager_id);
                $empDetails = HRPerson::getManagerDetails($application->hr_id);
                $fullnane = $hrDetail->first_name . ' ' . $hrDetail->surname;
                $fullnaneEmp = $empDetails->first_name . ' ' . $empDetails->surname;
                // emails manager
                if (!empty($hrDetail->email))
                    Mail::to($hrDetail->email)->send(new managerReminder($fullnane, $fullnaneEmp));
            }
        }
    }

    /**
     * @return void
     * function to escalate unresolved applications
     */
    public function leaveEscallation()
    {
        #check who is the manager
        #send a reminder
        #runs everyday
        $date_now = Carbon::now()->toDayDateTimeString();

        $daysToEscalation = leave_configuration::pluck('mumber_of_days_until_escalation')->first();


        $date = Carbon::today()->subDays($daysToEscalation);

        $user = leave_application::where('status', '>=', 2)
            ->where('created_at', '>=', $date)
            ->pluck('hr_id');


        $users = $user->unique();

        $outputArray = array();
        foreach ($users as $empID) {
            $leaveApplications = leave_application::where('hr_id', $empID)->first();
            if (isset($leaveApplications->manager_id))
                $managerId = $leaveApplications->manager_id;

            $hrDetails = HRPerson::getManagerDetails($managerId);

            $outputArray[] = $hrDetails;
        }
        // remove duplicates so that we only send 1 email to different managers
        $hrDetails = collect($outputArray)->unique();

        //needs improvement
        $headDep = array();
        foreach ($hrDetails as $hrDetail) {

            $Dept = DivisionLevelFour::where('id', $hrDetail->division_level_4)->first();

            $deptDetails = HRPerson::where('id', $Dept->manager_id)->where('status', 1)
                ->select('first_name', 'surname', 'email')
                ->first();

            $unapproved = leave_application::getUnapprovedApplications($date, $hrDetail->id);

            $fullnane = $hrDetail->first_name . ' ' . $hrDetail->surname;

            if ($unapproved > 1) {
                if (!empty($hrDetail->email))
                    Mail::to($hrDetail->email)->send(new managerReminder($fullnane, $hrDetail->email, $date_now, $unapproved));
            }

            $headName = $deptDetails->first_name . ' ' . $deptDetails->surname;

        }

        if ($unapproved > 1) {
            if (!empty($deptDetails->email))
                Mail::to($deptDetails->email)->send(new escalateleaveApplication($headName, $hrDetail->email, $date_now, $unapproved, $fullnane));
        }

    }


    /**
     * @return mixed
     * @throws \Throwable
     */
    public function viewBalance()
    {
        $credit = leave_history::getLeaveBalance();
        $this->createExcel();

        $date_now = Carbon::now()->toDayDateTimeString();

        $companyDetails = CompanyIdentity::systemSettings();
        $companyName = $companyDetails['company_name'];

        $data['support_email'] = $companyDetails['support_email'];
        $data['company_name'] = $companyName;
        $data['full_company_name'] = $companyDetails['full_company_name'];
        $data['company_logo'] = url('/') . $companyDetails['company_logo_url'];

        $data['date'] = $date_now;
        $data['credit'] = $credit;
        $data['file_name'] = 'LeaveBalance';
        $view = view('leave.reports.leave_balance', $data)->render();

        $pdf = resolve('dompdf.wrapper');
        $pdf->getDomPDF()->set_option('enable_html5_parser', true);
        $pdf->loadHTML($view);
        return $pdf->output();

    }

    /**
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function createExcel()
    {
        $credits = leave_history::getLeaveBalance();

        $AbsentUsersColl = array();

        if (count($credits) > 0) {
            foreach ($credits as $balances) {
                $details = HRPerson::getUserDetails($balances['employee_number']);

                $AbsentUsersColl[] = ([
                    'Employee number' => $details['employee_number'],
                    'First Name' => $details['first_name'],
                    'surname' => $details['surname'],
                    'Leave Type' => $balances['leaveType'],
                    'Balance' => $balances['Balance'],
                ]);
            }
        }
        /**
         * create an Excel file and store it the application
         */
        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())
            ->setFontSize(12)
            ->setShouldWrapText()
            ->build();


        return (new FastExcel($AbsentUsersColl))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export('storage/app/Leave balance.xls');


    }


    public function sendReport()
    {
        //get the user selected on the settings
        $userId = leave_configuration::pluck('hr_person_id')->first();
        $userDetails = HRPerson::getManagerDetails($userId);

        $fullname = $userDetails->firstname . ' ' . $userDetails->surname;

        /**
         * pdf attachment
         */
        $leaveAttachment = $this->viewBalance();

        /**
         * excel attachment
         */
        $Attachment = $this->createExcel();

        /**
         * get the file from storage
         */
        $file = Storage::get('Leave balance.xls');

        /**
         * Delete the file from storage
         */
        Storage::delete('Leave balance.xls');

        try {
            Mail::to($userDetails->email)->send(
                new sendManagersListOfAbsentUsersToday(
                    $userDetails->email,
                    $leaveAttachment,
                    $file
                ));
            echo 'Mail send successfully';
        } catch (\Exception $e) {
            echo 'Error - ' . $e;
        }

    }
}