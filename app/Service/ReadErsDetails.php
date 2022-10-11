<?php

namespace App\Service;

use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveHistoryAuditController;
use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\Mail\remindUserToapplyLeave;
use App\Mail\sendManagersListOfAbsentUsers;
use App\Models\ErsAbsentUsers;
use App\Models\ManagerReport;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use App\Traits\TotalDaysWithoutWeekendsTrait;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Integer;


class ReadErsDetails
{
    use TotalDaysWithoutWeekendsTrait;

    /**
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function connectToErs()
    {
        $client = new GuzzleHttp\Client();
        $ers_token = leave_configuration::pluck('ers_token_number')->first();
        if (!empty($ers_token)) {
            $token = $ers_token;
        } else {
            throw new ErrorException('Ers Token Not Found');
        }


        $date_from = Carbon::parse('07:00:00')->format('Y/m/d H:i:s');
        $date_to = Carbon::parse('18:00:00')->format('Y/m/d H:i:s');
        $todo = 'get_clocks';

        $theUrl = 'https://r14.ersbio.co.za/api/data_client.php?'
            . 't=' . $token
            . '&to_do=' . $todo
            . '&imei=0'
            . '&last_id=1&'
            . 'date_from=' . $date_from
            . '&date_to=' . $date_to
            . '&export=0'
            . '&display=2'; // export type


        $res = $client->request('GET', $theUrl);
        $body = $res->getBody()->getContents();
        return json_decode($body, true);

    }


    /**
     * @throws GuzzleException
     * @throws ErrorException
     */
    public function getErsDetails(): void
    {

        $date_from = date("F jS, Y");

        $date = strtotime($date_from);

        $absentUsers = $this->getAbsentUsers();

        $this->sendEmailToUser($absentUsers, $date, $date_from);

        $this->applyLeaveForUser();

    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function getAbsentUsers(): \Illuminate\Support\Collection
    {

        $resp = $this->connectToErs();

        if (!empty($resp)) {
            $response = $resp;
        } else {
            throw new ErrorException('No data found');
        }


        $Employees = HRPerson::getEmployeeNumber();

        $userArr = collect([]);

        foreach ($response as $key => $users) {

            unset($key);
            foreach ($users as $keys => $user) {
                $userArr->push($user['Employee_Pin']);
            }
        }

        $EmployeeId = $userArr->unique();

        return $Employees->diff($EmployeeId);
    }

    /**
     * @param $absentUsers
     * @param $date
     * @param $date_from
     * @return void
     */
    public function sendEmailToUser($absentUsers, $date, $date_from): void
    {

        foreach ($absentUsers as $usersId) {

            $getUsersDetails = HRPerson::getUserDetails($usersId);
            $full_nane = HRPerson::getFullName($getUsersDetails->first_name, $getUsersDetails->surname);

            $userID = $getUsersDetails->user_id;

            //check if user applied for leave
            $checkUserApplicationStatus = leave_application::checkIfUserApplied($userID, $date);

            if (!isset($checkUserApplicationStatus)) {
                //   persint in db date, user-id, isApplied
                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $userID,
                        'date' => $date,
                        'is_applied' => 0,
                        'is_email_sent' => 1,

                    ]);

                $valueArr = [
                    'name' => $full_nane,
                    'email' => $getUsersDetails->email,
                    'date_from' => $date_from
                ];

                //send email to remind them
                try {
                    Mail::to($getUsersDetails->email)->send(new remindUserToapplyLeave($full_nane, $getUsersDetails->email, $date_from));
                    echo 'Mail send successfully';
                } catch (\Exception $e) {
                    echo 'Error - ' . $e;
                }


            } else {

                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $userID,
                        'date' => $date,
                        'is_applied' => 1,
                        'is_email_sent' => 0,

                    ]);
            }
        }
    }

    /**
     * @return void
     * @throws ErrorException
     */
    public function applyLeaveForUser(): void
    {

        $today = date("Y-m-d");
        $getEscalationDays = leave_configuration::pluck('number_of_days_before_automate_application')->first();

        if (!empty($getEscalationDays)) {
            $days = $getEscalationDays;
        } else {
            throw new ErrorException('No days set');
        }

        $check = ErsAbsentUsers::getAbsentUsers();

        foreach ($check as $absent) {


            $absentDate = Carbon::parse(date("Y-m-d", $absent->date));
            $totaldays = LeaveApplicationController::calculatedays($absentDate, $today);

            if ($days == $totaldays) {

                $applicationStatus = LeaveApplicationController::ApplicationDetails(0, $absent->hr_id);

                $credit = leave_credit::getLeaveCredit($absent->hr_id, 1);

                $leaveBalance = $credit['leave_balance'];
                //persist to db
                $levApp = leave_application::create([
                    'leave_type_id' => 1,
                    'start_date' => $absent->date,
                    'end_date' => $absent->date,
                    'leave_taken' => 8,
                    'hr_id' => $absent->hr_id,
                    'notes' => 'The system has automatically applied for leave on your behalf',
                    'status' => $applicationStatus['status'],
                    'manager_id' => $applicationStatus['manager_id'],
                ]);


                // save audit
                LeaveHistoryAuditController::store(
                    "Leave application submitted by :"
                    . HRPerson::getFullName($applicationStatus['first_name']
                        , $applicationStatus['surname']),
                    'Leave application for day',
                    $credit['leave_balance'],
                    1,
                    $credit['leave_balance'],
                    1,
                    0,
                    1,
                    0
                );

                //this one is to update the ers table

                ErsAbsentUsers::where('id', $absent->hr_id)
                    ->update([
                        'hr_id' => $absent->hr_id,
                        'date' => $absent->date,
                        'is_applied' => 1
                    ]);

            } else {
                ///nothing
                $va = "do nothing";
            }
        }
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function sendAbsentUsersToManagers()
    {

        $date_now = Carbon::now()->toDayDateTimeString();
        $users = ManagerReport::getListOfManagers();

        $firstManager = ManagerReport::first();

        try {
            $absentUsers = $this->getAbsentUsers();
        } catch (ErrorException $e) {
            echo $e;
        }


        //create a new collection with name, surname, and employee  number
        $AbsentUsersColl = array();
        if (count($absentUsers) > 0) {
            foreach ($absentUsers as $absentUser) {
                $details = HRPerson::getUserDetails($absentUser);

                $AbsentUsersColl[] = ([
                    'Employee Number' => $details['employee_number'],
                    'Name' => $details['first_name'],
                    'Surname' => $details['surname'],
                    'Email' => $details['email'],
                ]);
            }
        }

        // Excel Doc
        $file = $this->createExcelDoc($AbsentUsersColl);


        $UsersArr = array();
        foreach ($users as $managers) {
            $managersDet = HRPerson::getManagerDetails($managers['hr_id']);

            try {
                Mail::to($managersDet['email'])->send(
                    new sendManagersListOfAbsentUsers(
                        $managersDet['first_name']
                        , $file, $date_now
                    ));
            } catch (\Exception $e) {
                echo 'Error - ' . $e;
            }
        }

    }

    /**
     * @param $AbsentUsersColl
     * @return mixed
     */
    private function createExcelDoc($AbsentUsersColl)
    {
        return Excel::create('Absent Users', function ($excel) use ($AbsentUsersColl) {
            $excel->sheet('Shortname', function ($sheet) use ($AbsentUsersColl) {
                $sheet->fromArray($AbsentUsersColl, null, 'A1');
            });

        });

    }
}