<?php

namespace App\Http\Controllers;

use App\ContactPerson;
use App\Mail\ConfirmRegistration;
use App\Mail\ResetPassword;
use App\User;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;
use Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;

class ContactsRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Contacts Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users of type contact as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register (Request $request) {
        //validate inputs
        $this->validate($request, [
            'first_name' => 'bail|required|min:2',
            'surname' => 'bail|required|min:2',
            'email' => 'bail|required|email|unique:users,email',
            'g-recaptcha-response' => 'required|captcha'
        ]);

        //Save usr
        $user = new User;
        $user->email = $request->email;
        $randomPass = str_random(10);
        $user->password = Hash::make($randomPass);
        $user->type = 2;
        $user->status = 1;
        $user->save();

        //Save Contact record
        $person = new ContactPerson($request->all());
        $person->status = 1;
        $user->addPerson($person);

        //Send email
        Mail::to("$user->email")->send(new ConfirmRegistration($user, $randomPass));

        //Show confirmation modal
        //Redirect to login page
		AuditReportsController::store('Contact', 'New Contact Saved', "Contact Registration", 0);
        return redirect('/login')->with('success_modal', "Your registration was successful! \nA confirmation email has been sent to your inbox with your login details. \nPlease check your inbox");
    }

    public function recoverPassword(Request $request) {
        //return response()->json(['message' => $request['current_password']]);

        $validator = Validator::make($request->all(),[
            'reset_email' => 'bail|required|exists:users,email',
        ]);

        $validator->validate();

        //find the user
        $user = User::where('email', $request['reset_email'])->first();
        //Update user password
        $randomPass = str_random(10);
        Mail::to("$user->email")->send(new ResetPassword($user, $randomPass));
        $user->password = Hash::make($randomPass);
        $user->password_changed_at = time();
        $user->update();
        //email new password to user
		AuditReportsController::store('Security', 'User Password Recoverd', "User Password Recoverd", 0);
        return response()->json(['success' => 'Password successfully reset.'], 200);
    }
}