<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\DivisionLevel;

use App\DivisionLevelTwo;
use App\HRPerson;

use App\hr_person;
use App\User;

class EmployeeSearchController extends Controller
{
    //
       public function __construct()
    {
        $this->middleware('auth');
    } 
     public function index() {
        $data['page_title'] = "Users";
        $data['page_description'] = "Search Users";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search user', 'active' => 1, 'is_module' => 0]
        ];
		AuditReportsController::store('Security', 'Search User Page Accessed', "Accessed By User", 0);
        return view('hr.employee_search')->with($data);
    }

     public function getSearch(Request $request) {
		 
     	//ivisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();
        //ivision=DivisionLevelTwo::where('active', 1)->get();
        $personName = trim($request->person_name);
        $aPositions = [];
        $cPositions = DB::table('hr_positions')->get();
        foreach ($cPositions as $position) {
            $aPositions[$position->id] = $position->name;
        }

        $persons = HRPerson::whereHas('user', function ($query) {
            $query->whereIn('type', [1, 3]);
        })
            ->where(function ($query) use ($personName) {
                if (!empty($personName)) {
                    $query->where('first_name', 'ILIKE', "%$personName%");
                }
            })

            ->orderBy('first_name')
            //->limit(100)
            ->get();
        $data['page_title'] = "Employee Search";
        $data['page_description'] = "List of users found";
        $data['persons'] = $persons;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'HR', 'path' => '/users', 'icon' => 'fa fa-lock', 'active' => 0, 'is_module' => 1],
            ['title' => 'User search result', 'active' => 1, 'is_module' => 0]
        ];
		$data['positions'] = $aPositions;
		//data['division'] = $division;
        //$data['user'] = $user;
       //data['division_levels'] = $divisionLevels;
		$data['active_mod'] = 'Security';
        $data['active_rib'] = 'Search Users';
		AuditReportsController::store('Security', 'User Search Results Accessed', "By User", 0);
        return view('hr.users_search')->with($data);
    }
     public function store(Request $request) {
        //Save usr
        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 1;
        $user->status = 1;
        $user->save();

        //exclude empty fields from query
        $personData = $request->all();
        foreach ($personData as $key => $value) {
            if (empty($personData[$key])) {
                unset($personData[$key]);
            }
        }

        //Save HR record
        $person = new HRPerson($personData);
        $person->status = 1;
        $user->addPerson($person);

        //Send email
        Mail::to("$user->email")->send(new ConfirmRegistration($user, $request->password));
		AuditReportsController::store('Security', 'New User Created', "Login Details Sent To User $user->email", 0);
        //Redirect to all usr view
        return redirect('/users')->with('success_add', "The user has been added successfully. \nYou can use the search menu to view the user details.");
    }


}
