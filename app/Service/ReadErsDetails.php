<?php

namespace App\Service;

use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\leave_application;
use App\leave_configuration;
use App\Mail\remindUserToapplyLeave;
use App\Models\ErsAbsentUsers;
use App\Models\ManagerReport;
use Carbon\Carbon;
use ErrorException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use App\Traits\TotalDaysWithoutWeekendsTrait;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Integer;


//import gu zzle

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


        $todo = 'get_clocks';
//        $date_from = Carbon::parse('07:00:00')->format('Y/m/d H:i:s');
//        $date_to = Carbon::parse('18:00:00')->format('Y/m/d H:i:s');
        $date_from = '2022-09-28';
        $date_to = '2022-09-28';
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

        /**
         *  call the absent user function to get all absent users
         */
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


        $Employees = HRPerson::where('status', 1)->pluck('employee_number');

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
            $fullnane = $getUsersDetails->first_name . ' ' . $getUsersDetails->surname;
            $userID = $getUsersDetails->user_id;

            //check if user applied for leave
            $checkUserApplicationStatus = leave_application::checkIfUserApplied($userID, $date);

            $checkEmail = ErsAbsentUsers::where(
                [
                    'hr_id' => $userID,
                    'is_applied' => 0,
                    'is_email_sent' => 1
                ]
            )->first();

//            dd(isset($checkEmail));
            //check if record exist


            if (!isset($checkUserApplicationStatus)) {

                //   persint in db date, user-id, isApplied
                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $userID,
                        'date' => $date,
                        'is_applied' => 0,
                        'is_email_sent' => 1,

                    ]);

                //send email to remind them
                if (!isset($checkEmail)) {
                    if (!empty($getUsersDetails->email))
                        Mail::to($getUsersDetails->email)->send(new remindUserToapplyLeave($fullnane, $getUsersDetails->email, $date_from));
                } else {
                    "do nothing";
                }


            } else {

                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $userID,
                        'date' => $date,
                        'is_applied' => 1,
                        'is_email_sent' => 0,

                    ]);
                //1664143200

            }

        }

    }

    /**
     * @return void
     * @throws ErrorException
     */
    public function applyLeaveForUser(): void
    {

        // check the days from the settings to dertemine the days to auto apply for leave
        //check if the user did apply for leave within those days
        // if not apply for leave for the user
        // send email informing the user the system has applied leave for them on their behalf
        //check how many days the user was absent
        //get the fist date and last date
        $from = date("Y-m-d");
        $getEscalationDays = leave_configuration::pluck('number_of_days_before_automate_application')->first();


        if (!empty($getEscalationDays)) {
            $days = $getEscalationDays;
        } else {
            throw new ErrorException('No days set');
        }


        $check = ErsAbsentUsers::getAbsentUsers();

        foreach ($check as $absent) {

            $to = Carbon::parse(date("Y-m-d", $absent->date));

            $totaldays = $to->diffInWeekdays($from);


            if ($getEscalationDays == $totaldays) {

                //count leave taken
                $leavetaken = ErsAbsentUsers::getLeaveTaken($absent->hr_id);

                $applicationStatus = LeaveApplicationController::ApplicationDetails(0, $absent->hr_id, $absent->date, $absent->date);

                //get manager details
                $managerDetails = HRPerson::getManagerDetails($absent->hr_id);
                $managerID = !empty($managerDetails['manager_id']) ? $managerDetails['manager_id'] : 0;

                //persist to db
                $levApp = leave_application::create([
                    'leave_type_id' => 1,
                    'start_date' => $absent->date,
                    'end_date' => $absent->date,
                    'leave_taken' => $leavetaken,
                    'hr_id' => $absent->hr_id,
                    'notes' => 'The system has automatically applied for leave on your behalf',
                    'status' => $applicationStatus['status'],
                    'manager_id' => $managerID,
                ]);


                //this one is to update the erstable
//                $absent = ErsAbsentUsers::find($absent->hr_id);
//                $absent->hr_id = $absent->hr_id;
//                $absent->date = $absent->date;
//                $absent->is_applied = 1;
//                $absent->update();

            } else {

                ///nothing
                $va = "do nothing";
            }

        }

    }


    /**
     * @return void
     */
    public function sendAbsentUsersToManagers()
    {

        //get list of managers from settings
        //get list of absent users for the day
        //compile a document with list of ansent users

        $managers = ManagerReport::getListOfManagers();

        $absentUsers = $this->getAbsentUsers();

        dd(ErsAbsentUsers::all());


    }


}