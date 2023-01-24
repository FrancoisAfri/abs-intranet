<?php

namespace App\Http\Controllers;

use App\HRPerson;
use App\programme;
use App\projects;
use App\User;
use App\AuditTrail;
use App\Mail\EmplyeesBirthdays;
use Illuminate\Http\Request;
use App\Http\Controllers\AuditReportsController;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EmployeesBirthdays extends Controller
{
    //
	public function execute()
    {
		$birthdays = HRPerson::select('hr_people.id'
					, 'hr_people.first_name', 'hr_people.surname'
					, 'hr_people.email'
					, 'hr_people.profile_pic'
					, 'hr_people.gender'
					,'hr_people.date_of_birth')
			->where('hr_people.status',1)
			->whereNotNull('hr_people.date_of_birth')
			->orderBy('hr_people.first_name')
			->orderBy('hr_people.surname')
			->get();
		
		//Flag employees that are on leave today
		foreach ($birthdays as $employee) {
			
			if ((date('j',$employee->date_of_birth) === date('j')) && (date('n',$employee->date_of_birth) == date('n')))
			{
				if (!empty($employee->email))
					Mail::to($employee->email)->send(new EmplyeesBirthdays($employee->first_name));
			}
		}
	}
}
