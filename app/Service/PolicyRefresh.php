<?php

namespace App\Service;

use App\CompanyIdentity;
use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\Policy;
use App\PolicyRefreshed;
use App\Mail\createPolicy;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use Illuminate\Support\Facades\Mail;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use phpDocumentor\Reflection\Types\Integer;
use Rap2hpoutre\FastExcel\FastExcel;


class PolicyRefresh
{

    /**
     * @return void
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function execute()
    {
		// get policies where that needs refreshment
		//$refreshPolicies = DB::table('policy')->select('id')->where('refresh_month', 1);
		// load all users
		//$policies = DB::table('policy_users')
        //           ->whereIn('policy_id', $refreshPolicies)
        //           ->get();
		$today =  strtotime(date("Y-m-d"));
		$policies = PolicyRefreshed::where('status', 1)
							->where('date_refreshed','<=', $today)->get();
		//$date_now = Carbon::now()->toDayDateTimeString();
		// get all assets where status is in "in use, unallocated, in store"
        
		/// check if assets cllectin retuned values
        if (count($policies) > 0) {
            foreach ($policies as $policy) 
			{
				// loop through results
				// get employee name
				$employee = HRPerson::getEmployee($policy->hr_id);
				$policy = Policy::where('id',$policy->policy_id)->first();
				#mail to user
				if (!empty($employee->email))
					Mail::to($employee->email)->send(new PolicyRefresh($employee->first_name, $policy->name, $policy->id));
				
            }
        }
        
    }

}