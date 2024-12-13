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
use App\EmployeesTimeAndAttendance;
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
use Illuminate\Support\Facades\DB;

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

        $date_from = Carbon::parse('05:00:00')->format('Y/m/d H:i:s');
        $date_to = Carbon::parse('20:00:00')->format('Y/m/d H:i:s');
        //$date_from = Carbon::now()->format('Y/m/d');
        //$date_to = Carbon::now()->format('Y/m/d');

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
		// return $CollectionWithExemptedUsers->diff($EmployeeId);
		
		$collection =  $CollectionWithExemptedUsers->diff($EmployeeId);
		// remove duplicate
		$collection = $collection->unique();

        return $collection;
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
				// save in attendancde table
				$timeAndAttendance = new EmployeesTimeAndAttendance();
				$timeAndAttendance->hr_id = $userID;
				$timeAndAttendance->employee_number = $getUsersDetails->employee_number;
				$timeAndAttendance->clokin_time = '';
				$timeAndAttendance->clockin_locations = '';
				$timeAndAttendance->clockout_time = '';
				$timeAndAttendance->clockout_locations = '';
				$timeAndAttendance->date_of_action = $date;
				$timeAndAttendance->hours_worked = 0;
				$timeAndAttendance->late_arrival = '';
				$timeAndAttendance->early_clockout = '';
				$timeAndAttendance->absent = true;
				$timeAndAttendance->onleave = false;
				$timeAndAttendance->save();
                //send email to remind them
                try {
                    Mail::to($getUsersDetails->email)->send(new remindUserToapplyLeave($full_nane, $getUsersDetails->email, $date_from));
                    echo 'Mail send successfully';
                } catch (\Exception $e) {
                    echo 'Error - ' . $e;
                }


            } 
			else 
			{
                ErsAbsentUsers::updateOrCreate(
                    [
                        'hr_id' => $userID,
                        'date' => $date,
                        'is_applied' => 1,
                        'is_email_sent' => 0,

                    ]);
				// save in attendancde table
				$timeAndAttendance = new EmployeesTimeAndAttendance();
				$timeAndAttendance->hr_id = $userID;
				$timeAndAttendance->employee_number = $getUsersDetails->employee_number;
				$timeAndAttendance->clokin_time = '';
				$timeAndAttendance->clockin_locations = '';
				$timeAndAttendance->clockout_time = '';
				$timeAndAttendance->clockout_locations = '';
				$timeAndAttendance->date_of_action = $date;
				$timeAndAttendance->hours_worked = 0;
				$timeAndAttendance->late_arrival = '';
				$timeAndAttendance->early_clockout = '';
				$timeAndAttendance->absent = false;
				$timeAndAttendance->onleave = true;
				$timeAndAttendance->leave_type = $checkUserApplicationStatus->leavetpe->name;
				$timeAndAttendance->save();
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
        $allowUnpaidLeave = leave_configuration::pluck('allow_unpaid_leave_when_annual_done')->first();

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
					// check if the user doest have annual leave the apply for unpaid
					
					//$allowUnpaid = !empty($row->allow_unpaid_leave_when_annual_done) ? $row->allow_unpaid_leave_when_annual_done : 0;
					$leaveType = 1;
					$credit = leave_credit::getLeaveCredit($absent->hr_id, $leaveType);
					if (!empty($allowUnpaidLeave))
					{
						
						$availableBalance = !empty($credit->leave_balance) ? $credit->leave_balance / 8 : 0;
						if ($availableBalance < 0)
						{
							$leaveType = 7;
							$credit = leave_credit::getLeaveCredit($absent->hr_id, $leaveType);
						}
					}

                    //persist to db
                    $levApp = leave_application::create([
                        'leave_type_id' => $leaveType,
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
                        $leaveType,
                        $absent->hr_id,
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
                }
				else 
				{
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
		// add all 
		$arrayEmpNum = array();
		if (count($absentUsers) > 0) {
            foreach ($absentUsers as $emploNumb) {
				
				if (in_array($emploNumb, $arrayEmpNum)) {
					//echo "Got Irix";
				}
				else
					array_push($arrayEmpNum,$emploNumb);
			}
		}

		$results = HRPerson::select('employee_number')->whereIn('employee_number', $arrayEmpNum)->where('status',1)->orderBy('manager_id')->get();

        //create a new collection with name, surname, and employee  number
        $AbsentUsersColl = array();

        if (count($results) > 0) {
            foreach ($results as $absentUser) {
				
                $details = HRPerson::getUserDetails($absentUser->employee_number);
                // if manager and second manager are set on the on employee profile
				if (!empty($details['manager_id']))
				{
					$managerDetails = HRPerson::getManagername($details['manager_id']);
					if (!empty($managerDetails['first_name']) && $managerDetails['surname'])
						$managerName = $managerDetails['first_name']." ".$managerDetails['surname'];
					else $managerName = '' ;
				}
				else $managerName;
				// if department id are set on the on employee profile
				if (!empty($details['division_level_4']))
				{
					$departDetails = HRPerson::getUserDepartment($details['division_level_4']);
					if (!empty($departDetails['name']))
						$deptName = $departDetails['name'];
					else $deptName = '' ;
				}
				else $deptName;
				// check leave status
                $checkStatus = $this->userOnLeave($details->id);
				if (!empty($checkStatus)) $leave = 'Yes';
				else $leave = '';
				// Initialize the array with the heading row
				$AbsentUsersColl = [
					[
						'Heading' => 'Absent Users List Report',
						'Date' => date('Y-m-d'), // You can change the format as needed
					]
				];
				$AbsentUsersColl[] = ([
					'employee_number' => $details['employee_number'],
					'name' => $details['first_name'],
					'surname' => $details['surname'],
					'email' => $details['email'],
					'On Leave' => $leave,
					'Department' => $deptName,
					'Manager' => $managerName,
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
	/**
     * @return void
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function userOnLeave($hr_id)
    {
        $monthStart = new Carbon('first day of this month');
		$monthStart->startOfDay();
		$monthStart = $monthStart->timestamp;
		$monthEnd = new Carbon('last day of this month');
		$monthEnd->endOfDay();
		$monthEnd = $monthEnd->timestamp;
		$today = Carbon::now();
		$todayStart = $today->copy()->startOfDay()->timestamp;
		$todayEnd = $today->copy()->endOfDay()->timestamp;
		$onLeaveThisMonth = HRPerson::select('hr_people.id', 'hr_people.first_name', 'hr_people.surname', 'hr_people.profile_pic',
			'leave_application.start_date', 'leave_application.start_time', 'leave_application.end_date', 'leave_application.end_time')
			->join('leave_application', 'hr_people.id', '=', 'leave_application.hr_id')
			->where('hr_people.id', $hr_id)
			->where('leave_application.status', 1)
			->where(function ($query) use ($todayStart) {
				$query->whereRaw('leave_application.start_date >= ' . $todayStart);
				$query->orWhereRaw('leave_application.end_date >= ' . $todayStart);
			})
			->where(function ($query) use ($monthEnd) {
				$query->whereRaw('leave_application.start_date <= ' . $monthEnd);
				$query->orWhereRaw('leave_application.end_date <= ' . $monthEnd);
			})
			->orderBy('leave_application.start_date')
			->get();
		//Flag employees that are on leave today
		foreach ($onLeaveThisMonth as $employee) {
			$isOnLeaveToday = false;
			if (($employee->start_date <= $todayStart && $employee->end_date >= $todayStart) || ($employee->start_date >= $todayStart && $employee->start_date <= $todayEnd)) $isOnLeaveToday = 1;
				return $isOnLeaveToday;
		}
    }
	
	// getClockinRecordsFromERS
	public function getTimeAndAttendance()
    {
        /**
         * call  connect to Ers class for the api response
         */
        try {
            // Connect to the external API
            $resp = $this->connectToErs();

            if (empty($resp['Raw_Clocks'])) {
                throw new \ErrorException('No clock-in data found.');
            }

            // Decode API response
            $clockData = $resp['Raw_Clocks'];

            // Convert data to a collection for processing
            $collection = collect($clockData);

            // Group data by Employee_Pin
            $grouped = $collection->groupBy('Employee_Pin');

            // Process each group and save to the database
            $grouped->each(function ($records, $pin) {
                $firstClockIn = collect($records)->sortBy('Clocked')->first();
                $lastClockIn = collect($records)->sortByDesc('Clocked')->first();

                $data = [
                    'hr_id' => $this->getHRID($pin),
                    'employee_number' => $pin,
                    'clokin_time' => $firstClockIn['Clocked'],
                    'clockin_locations' => $firstClockIn['Device'],
                    'clockout_time' => $lastClockIn['Clocked'],
                    'clockout_locations' => $lastClockIn['Device'],
                    'date_of_action' =>  strtotime(Carbon::today()),
                    'hours_worked' => $this->calculateHoursWorked($firstClockIn['Clocked'], $lastClockIn['Clocked']),
                    'late_arrival' => $this->isLate($firstClockIn['Clocked'], $pin),
                    'early_clockout' => $this->earlyLeft($lastClockIn['Clocked'], $pin),
                    'absent' => false, // Add logic for absence if needed
                    'onleave' => false // Add logic for leave if needed
                ];
				// get if a record has already been saved for the day on the user
				$existingRecord = EmployeesTimeAndAttendance::where('hr_id', $data['hr_id'])
				->where('date_of_action', strtotime(Carbon::today()))->first();
				if (empty($existingRecord)) 
				{
					$timeAndAttendance = new EmployeesTimeAndAttendance();
					$timeAndAttendance->hr_id = $data['hr_id'];
					$timeAndAttendance->employee_number = $data['employee_number'];
					$timeAndAttendance->clokin_time = $data['clokin_time'];
					$timeAndAttendance->clockin_locations = $data['clockin_locations'];
					$timeAndAttendance->clockout_time = $data['clockout_time'];
					$timeAndAttendance->clockout_locations = $data['clockout_locations'];
					$timeAndAttendance->date_of_action = $data['date_of_action'];
					$timeAndAttendance->hours_worked = $data['hours_worked'];
					$timeAndAttendance->late_arrival = $data['late_arrival'];
					$timeAndAttendance->early_clockout = $data['early_clockout'];
					$timeAndAttendance->absent = $data['absent'];
					$timeAndAttendance->onleave = $data['onleave'];
					$timeAndAttendance->save();
				}
            });

            return response()->json(['message' => 'Clock-in data processed and saved successfully.']);
        } catch (\Exception $e) {
			
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
	private function calculateHoursWorked($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        return $start->diffInHours($end); // Calculate hours worked
    }
	// check if user clocked in late
	private function isLate($clockinTime, $pin)
	{
		$clockIn = Carbon::parse($clockinTime);
		$expected = HRPerson::where('employee_number', $pin)->first();

		if (!$expected || empty($expected->start_time)) {
			return false; // Default to not late if no start time is defined
		}

		// Combine the start_time with the date from clockIn for comparison
		$expectedStart = Carbon::createFromFormat(
			'Y-m-d H:i:s',
			$clockIn->format('Y-m-d') . ' ' . $expected->start_time . ':00'
		);

		// Add 15 minutes buffer to the expected start time
		$thresholdTime = $expectedStart->addMinutes(15);

		// Check if the clock-in time is after the 15-minute threshold
		return $clockIn->greaterThan($thresholdTime);
	}
	// check if user clocked in early
	private function earlyLeft($clockoutTime, $pin)
	{
		$clockOut = Carbon::parse($clockoutTime);
		$expected = HRPerson::where('employee_number', $pin)->first();

		if (!$expected || empty($expected->end_time)) {
			return false; // Default to not early if no end time is defined
		}

		// Combine the end_time with the date from clockOut for comparison
		$expectedEnd = Carbon::createFromFormat(
			'Y-m-d H:i:s',
			$clockOut->format('Y-m-d') . ' ' . $expected->end_time . ':00'
		);

		// Subtract 15 minutes from the expected end time
		$thresholdTime = $expectedEnd->subMinutes(15);

		// Check if the clock-out time is before the 15-minute threshold
		return $clockOut->lessThan($thresholdTime);
	}
	// check if user clocked in early
	private function getHRID($pin)
	{
		$result = HRPerson::getUserDetails($pin);
		if (empty($result->id)) {
			return false; // Default to not early if no end time is defined
		}
		return $result->id;
	}

}