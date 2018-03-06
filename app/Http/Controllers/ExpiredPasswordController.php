<?php

namespace App\Http\Controllers;
use App\Http\Requests\PasswordExpiredRequest;
use Carbon\Carbon;
use App\CompanyIdentity;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ExpiredPasswordController extends Controller
{
	
	public function __construct()
    {
        //$this->middleware('auth');
    }
	// Call Password change form
	
    public function expired()
    {
		$data['page_title'] = "Complusary Password Change";
        $data['page_description'] = "Update Password";
        $data['breadcrumb'] = [
            ['title' => 'Security', 'path' => '/users', 'icon' => 'fa fa-money', 'active' => 0, 'is_module' => 1],
            ['title' => 'Complusary Password Change', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Security';
        $data['active_rib'] = 'Profile';
		$user = Auth::user();
		$data['user'] = $user;
		AuditReportsController::store('Security', 'Password Change Page', "Accessed By User", 0);
        
        return view('security.expired')->with($data);;
    }
	
	// change password funtion
	
	public function postExpired(Request $request, User $user)
    {
		$validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:new_password'
        ]);

        $validator->after(function($validator) use ($request, $user){
            $userPW = $user->password;
			$currentPassword = Hash::make($request['current_password']);
			
            if (!(Hash::check($request['current_password'], $userPW))) {
                $validator->errors()->add('current_password', 'The current password is incorrect, please enter the correct current password.');
            }
        });
        $validator->validate();
		// Get password duration date
		$compDetails = CompanyIdentity::first();
		$iduration = !empty($compDetails->password_expiring_month) ? $compDetails->password_expiring_month : 0;

		$expiredDate = !empty($iduration) ? mktime(0,0,0,date('m')+ $iduration,date('d'),date('Y')) : 0;

        $user->password = Hash::make($request->new_password);
        $user->password_changed_at = $expiredDate;
        $user->update();
		AuditReportsController::store('Security', 'Password Updated', "Accessed By User", 0);
        return back()->with('password_updated', "Password have been successfully changed.");
    }
}