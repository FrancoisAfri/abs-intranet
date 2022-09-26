<?php

namespace App\Service;

use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\leave_configuration;
use ErrorException;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use phpDocumentor\Reflection\Types\Integer;


//import gu zzle

class ReadErsDetails
{

    /**
     * @return void
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function getErsDetails()
    {

        $client = new GuzzleHttp\Client();
        $ers_token = leave_configuration::pluck('ers_token_number')->first();
        if (!empty($ers_token)) {
            $token = $ers_token;
        } else {
            throw new ErrorException('Ers Token Not Found');
        }

        $todo = 'get_clocks';
        $date_from = '2022-09-23';
        $date_to = '2022-09-23';
        $theUrl = 'https://r14.ersbio.co.za/api/data_client.php?' . 't=' . $token . '&to_do=' . $todo . '&imei=0&last_id=1' . '&date_from=' . $date_from . '&date_to' . $date_to . '&direction=o' . '&export=0&display=2';

        $res = $client->request('GET', $theUrl);
        $body = $res->getBody()->getContents();
        $response = json_decode($body, true);
        $usersCollection = collect($body);

        $Employees = HRPerson::where('status', 1)->pluck('employee_number');

        $userArr = collect([]);

        foreach ($response as $key => $users) {
            unset($key);
            foreach ($users as $keys => $user) {
                $userArr->push($user['Employee_Pin']);
            }
        }
        $EmployeeId = $userArr->unique();

        $absentUsers = $Employees->diff($EmployeeId);
        dd($absentUsers);

        // get their user details
        //check if they applied for a leave for that particular date
        //if not senf them an email to apply for a leave for exact date
        //persist their details in a table


    }


}