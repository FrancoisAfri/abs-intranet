<?php

namespace App\Service;

use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\leave_application;
use App\leave_configuration;
use App\Mail\remindUserToapplyLeave;
use App\Models\ErsAbsentUsers;
use Carbon\Carbon;
use ErrorException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use Illuminate\Support\Facades\Mail;
use phpDocumentor\Reflection\Types\Integer;


//import gu zzle

class ReadErsDetails
{


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

        $d = date('Y/m/d');



        $todo = 'get_clocks';
       // $date_from = Carbon::parse('07:00:00')->format('Y/m/d H:i:s');
       // $date_to = Carbon::parse('18:00:00')->format('Y/m/d H:i:s');
        $date_from = '2022-09-26';
        $date_to  = '2022-09-26';
        $theUrl = 'https://r14.ersbio.co.za/api/data_client.php?'
            . 't=' . $token
            . '&to_do=' . $todo
            . '&imei=0&last_id=1'
            . '&date_from='
            . $date_from
            . '&date_to'
            . $date_to
            . '&direction=o'
            . '&export=0&display=2';

        $res = $client->request('GET', $theUrl);
        $body = $res->getBody()->getContents();
        return json_decode($body, true);
    }


    /**
     * @throws GuzzleException
     * @throws ErrorException
     */
    public function getErsDetails()
    {


        $date_from = date("F jS, Y");

        $date = strtotime($date_from);

        dd($this->applyLeaveForUser());
        /**
         *  call the absent user function to get all absent users
         */
        $absentUsers = $this->getAbsentUsers();

       $this->sendEmailToUser($absentUsers, $date, $date_from);


    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function getAbsentUsers(): \Illuminate\Support\Collection
    {

        $response = $this->connectToErs();

        $Employees = HRPerson::where('status', 1)->pluck('employee_number');

        $userArr = collect([]);

        foreach ($response as $key => $users) {
            unset($key);
            foreach ($users as $keys => $user) {
                $userArr->push($user['Employee_Pin']);
            }
        }
        $EmployeeId = $userArr->unique();

        return $absentUsers = $Employees->diff($EmployeeId);

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

            //check if user applied for leave
            $checkUserApplicationStatus = leave_application::checkIfUserApplied($getUsersDetails->user_id, $date);

            if (!isset($checkUserApplicationStatus)) {
                //send email
                //   persint in db date, user-id, isApplied
                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $getUsersDetails->user_id,
                        'date' => $date,
                        'is_applied' => 0,

                    ]);

                //send email to remind them
                if (!empty($getUsersDetails->email))
                    Mail::to($getUsersDetails->email)->send(new remindUserToapplyLeave($fullnane, $getUsersDetails->email, $date_from));

            } else {
                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $getUsersDetails->user_id,
                        'date' => $date,
                        'is_applied' => 1, // 1 means user did apply for leave

                    ]);
            }

        }

    }


    public function applyLeaveForUser(): void
    {

        // check the days from the settings to dertemine the days to auto apply for leave
        //check if the user did apply for leave within those days
        // if not apply for leave for the user
        // send email informing the user the system has applied leave for them on their behalf

        $getEscalationDays = leave_configuration::pluck('number_of_days_before_automate_application')->first();

        if (!empty($getEscalationDays)) {
            $days = $getEscalationDays;
        } else {
            throw new ErrorException('No days set');
        }

       // $date = Carbon::today()->subDays($days);
        $date = '2022-09-26';
        $day = strtotime($date);


       $check  =  ErsAbsentUsers::where(
            [
                'date' => $day,
                'is_applied' => 0
            ]
        )->get();

       foreach ($check as $details){

           //call the apply method
       }
      //get user id



    }


}