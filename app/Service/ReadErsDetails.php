<?php

namespace App\Service;

use App\CompanyIdentity;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\LeaveHistoryAuditController;
use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\leave_application;
use App\leave_configuration;
use App\leave_credit;
use App\leave_history;
use App\Mail\sendManagersListOfAbsentUsersToday;
use App\Mail\remindUserToapplyLeave;
use App\Mail\sendManagersListOfAbsentUsers;
use App\Models\ErsAbsentUsers;
use App\Models\ExemptedUsers;
use App\ManualClockin;
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
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use phpDocumentor\Reflection\Types\Integer;
use Rap2hpoutre\FastExcel\FastExcel;


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

        $today = Carbon::now();
        $start = $today->copy()->startOfDay();
        $end = $today->copy()->endOfDay();

        $startDate = strtotime($start);
        $endDate = strtotime($end);

        $absentUsers = $this->getAbsentUsers();

        $this->sendEmailToUser($absentUsers, $date, $date_from, $startDate, $endDate);

        $this->applyLeaveForUser();

    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function getAbsentUsers(): \Illuminate\Support\Collection
    {
        /**
         * call  connect to Ers class for the api response
         */
        $resp = $this->connectToErs();

        if (!empty($resp)) {
            $response = $resp;
        } else {
            throw new ErrorException('No data found');
        }

        /**
         * loop through the api response to get only employee ids
         * and then push them into an empty $userColl collection
         */
        $userColl = collect([]);
        foreach ($response as $key => $users) {

            unset($key);
            foreach ($users as $user) {
                $userColl->push($user['Employee_Pin']);
            }
        }


        $Employees = HRPerson::getEmployeeNumber();

        $exemptedUsers = ExemptedUsers::getExemptedUsers();
        $clockinUsers = ManualClockin::getclokinUsers();

        /**
         * remove duplicate records fromm the api response to get unique employee ids
         */
        $EmployeeId = $userColl->unique();


        /**
         * We remove list of exempted users from the Hr records collection
         */
        $CollectionWithExemptedUsers = $Employees->diff($exemptedUsers);

        /**
         * We remove users who clocked In on the manual system
         */


        $CollectionWithExemptedUsers = $CollectionWithExemptedUsers->diff($clockinUsers);

        /**
         * Compare the employee records with exempted users to the api response
         * to get absent users for the day
         */
        return $CollectionWithExemptedUsers->diff($EmployeeId);
    }

    /**
     * @param $absentUsers
     * @param $date
     * @param $date_from  schedule:sendAbsentUsersToManager
     * @return void
     */
    public function sendEmailToUser($absentUsers, $date, $date_from, $startDate, $endDate): void
    {

        foreach ($absentUsers as $usersId) {

            $getUsersDetails = HRPerson::getUserDetails($usersId);
            $full_nane = HRPerson::getFullName($getUsersDetails->first_name, $getUsersDetails->surname);

            $userID = $getUsersDetails->id;

            //check if user applied for leave
            $checkUserApplicationStatus = leave_application::checkIfUserApplied($userID, $startDate, $endDate);

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

                // check if user applied for leave //check if user applied for leave
                $absentday = Carbon::parse(date("Y-m-d", $absent->date));
                $start = $absentday->copy()->startOfDay();
                $end = $absentday->copy()->endOfDay();

                $startDate = strtotime($start);
                $endDate = strtotime($end);
                $checkUserApplicationStatus = leave_application::checkIfUserApplied($absent->hr_id, $startDate, $endDate);
                // if user applied for leave
                if (!isset($checkUserApplicationStatus)) {

                    $applicationStatus = LeaveApplicationController::ApplicationDetails(0, $absent->hr_id);

                    $credit = leave_credit::getLeaveCredit($absent->hr_id, 1);

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
                        "Leave application submitted by : Cron Job system",
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

                    ErsAbsentUsers::where('id', $absent->id)
                        ->update([
                            'hr_id' => $absent->hr_id,
                            'date' => $absent->date,
                            'is_applied' => 1
                        ]);
                } else {

                    //this one is to update the ers table

                    ErsAbsentUsers::where('id', $absent->id)
                        ->update([
                            'hr_id' => $absent->hr_id,
                            'date' => $absent->date,
                            'is_applied' => 1
                        ]);
                }

            } else {
                ///nothing
                $va = "do nothing";
            }
        }
    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function sendAbsentUsersToManagers()
    {

        $date_now = Carbon::now()->toDayDateTimeString();
        $users = ManagerReport::getListOfManagers();


        $absentUsers = $this->getAbsentUsers();


        //create a new collection with name, surname, and employee  number
        $AbsentUsersColl = array();
        if (count($absentUsers) > 0) {
            foreach ($absentUsers as $absentUser) {
                $details = HRPerson::getUserDetails($absentUser);

                $AbsentUsersColl[] = ([
                    'employee_number' => $details['employee_number'],
                    'name' => $details['first_name'],
                    'surname' => $details['surname'],
                    'email' => $details['email'],
                ]);
            }
        }

        
        /**
         * create an Excel file and store it the application
         */
        $header_style = (new StyleBuilder())->setFontBold()->build();
        $rows_style = (new StyleBuilder())
            ->setFontSize(10)
            ->setShouldWrapText()
            ->build();


        $ExcelDoc = (new FastExcel($AbsentUsersColl))
            ->headerStyle($header_style)
            ->rowsStyle($rows_style)
            ->export('storage/app/Absent Users.xls');

        /**
         * get the file from storage
         */
        $file = Storage::get('Absent Users.xls');

        /**
         * Delete the file from storage
         */
        Storage::delete('Absent Users.xls');


        foreach ($users as $managers) {
            $managersDet = HRPerson::getManagerDetails($managers->hr_id);
            try {
                Mail::to($managersDet['email'])->send(
                    new sendManagersListOfAbsentUsers(
                        $managersDet['first_name']
                        , $file, $date_now
                    ));
                echo 'mgs sent';
            } catch (\Exception $e) {
                echo 'Error - ' . $e;
            }
        }

    }

}