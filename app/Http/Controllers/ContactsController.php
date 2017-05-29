<?php

namespace App\Http\Controllers;

use App\ContactCompany;
use App\ContactPerson;
use App\Country;
use App\public_reg;
use App\Mail\ConfirmRegistration;
use Illuminate\Http\Request;
use App\Mail\adminEmail;
use App\Http\Requests;
use App\HRPerson;
use App\User;
use App\Province;
use App\Http\Controllers\AuditReportsController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {
        $data['page_title'] = "Clients";
        $data['page_description'] = "Search Clients";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search client', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'search';
		AuditReportsController::store('Clients', 'Clients Search Page Accessed', "Actioned By User", 0);
        return view('contacts.search_contact')->with($data);
    }
    public function create() {

        $contactTypes = [1 => 'Company Rep', 2 => 'Student', 3 => 'Learner', 4 => 'Official', 5 => 'Educator', 6 => 'Osizweni Employee', 7 => 'Osizweni Board Member', 8 => 'Other'];
        $orgTypes = [1 => 'Private Company', 2 => 'Parastatal', 3 => 'School', 4 => 'Government', 5 => 'Other'];
        $companies = ContactCompany::where('status', 1)->orderBy('name')->get();
        $data['companies'] = $companies;
		$data['page_title'] = "Contacts";
        $data['page_description'] = "Add a New Contact";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add contact', 'active' => 1, 'is_module' => 0]
        ];
        $data['contact_types'] = $contactTypes;
        $data['org_types'] = $orgTypes;
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Add Client';
		//die('what');
		AuditReportsController::store('Contacts', 'Contacts Contact Page Accessed', "Actioned By User", 0);
        return view('contacts.add_contact')->with($data);
    }
	public function addContact() {

        $data['page_title'] = "Contact";
        $data['page_description'] = "Add a New Contact";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add Contact', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Contact';
		$data['contact_type'] = 1; //Contacts
		AuditReportsController::store('Contacts', 'Contacts Contact Page Accessed', "Actioned By User", 0);
        return view('contacts.general_meeting')->with($data);
    }
	
	public function educatorRegistration() {
        $data['page_title'] = "Educator Registration";
        $data['page_description'] = "Add a New Educator Registration";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Educator Registration', 'active' => 1, 'is_module' => 0]
        ];

		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Educator Registration';
        return view('contacts.educator_registration')->with($data);
    }
	public function learnerRegistration() {
        $data['page_title'] = "Learner registration";
        $data['page_description'] = "Add a New Learner registration";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Learner registration', 'active' => 1, 'is_module' => 0]
        ];

		$ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
		$data['ethnicities'] = $ethnicities;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'Learner registration';
        return view('contacts.learner_registration')->with($data);
    }
    public function store(Request $request) {
        $this->validate($request, [
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'unique:contacts_contacts,email',
            'cell_number' => 'unique:contacts_contacts,cell_number',
        ]);
        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->type = 2;
        $user->status = 1;
        $user->save();

        //Save ContactPerson record
        $person = new ContactPerson($request->all());
        $person->cell_number = (empty($person->cell_number)) ? null : $person->cell_number;
        $person->status = 1;
        if (!empty($request->company_id)) $person->company_id = $request->company_id;
        $user->addPerson($person);

        //Send email to client
        //Mail::to("$user->email")->send(new ConfirmRegistration($user, $request->password));

        //Notify admin about the new applicant
       /* $administrators = HRPerson::where('position', 2)->get();
        foreach ($administrators as $admin) {
            Mail::to("$admin->email")->send(new NewClientAdminNotification($admin, $user->id));
        }
*/
        //Redirect to all usr view
		AuditReportsController::store('Contacts', 'New Contact Added', "Contact Successfully added", 0);
        return redirect("/contacts/$user->id/edit")->with('success_add', "The contact has been added successfully.");
    }
	
    /*public function edit(ContactPerson $contact) {
        $contactTypes = [1 => 'Company Rep', 2 => 'Student', 3 => 'Learner', 4 => 'Official', 5 => 'Educator', 6 => 'Osizweni Employee', 7 => 'Osizweni Board Member', 8 => 'Other'];
        $orgTypes = [1 => 'Private Company', 2 => 'Parastatal', 3 => 'School', 4 => 'Government', 5 => 'Other'];
        $data['page_title'] = "Contacts";
        $data['page_description'] = "View/Update contact details";
        $data['back'] = "/contacts";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Contact details', 'active' => 1, 'is_module' => 0]
        ];
        $data['contact'] = $contact;
        $data['contact_types'] = $contactTypes;
        $data['org_types'] = $orgTypes;
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'search';
		AuditReportsController::store('Contacts', 'Contact Edited', "Contact On Edit Mode", 0);
        return view('contacts.view_contact')->with($data);
    }*/
	public function edit(User $user) {
		
        $user->load('person');
        $avatar = $user->person->profile_pic;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $companies = ContactCompany::where('status', 1)->orderBy('name')->get();
        $data['page_title'] = "Clients";
        $data['page_description'] = "View/Update client details";
        $data['back'] = "/contacts";
        $data['view_by_admin'] = 1;
		
        $data['user'] = $user;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['marital_statuses'] = $marital_statuses;
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Client details', 'active' => 1, 'is_module' => 0]
        ];
		$data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'search';
        $data['companies'] = $companies;
        $data['view_by_admin'] = 1;
		AuditReportsController::store('Contacts', 'Contact Edited', "Contact On Edit Mode", 0);
        return view('contacts.view_contact')->with($data);
    }
    
    public function profile() {
        $user = Auth::user()->load('person');
        $avatar = $user->person->profile_pic;
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $data['page_title'] = "Clients";
        $data['page_description'] = "View/Update your details";
        $data['back'] = "/";
        $data['user_profile'] = 1;
        $data['user'] = $user;
        $data['avatar'] = (!empty($avatar)) ? Storage::disk('local')->url("avatars/$avatar") : '';
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['marital_statuses'] = $marital_statuses;
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'My profile', 'active' => 1, 'is_module' => 0]
        ];
		AuditReportsController::store('Contacts', 'Contact Profile Accessed', "Accessed By User", 0);
        return view('contacts.view_contact')->with($data);
    }
	public function emailAdmin(Request $request) {
		$emails = $request->all();
		$message  = $emails['message'];
        $user = Auth::user()->load('person');
		//return $user;
		$senderName = $user->person->first_name." ".$user->person->surname;
		$senderEmail = $user->person->email;
		$adminUser = DB::table('hr_people')->where('position', 2)->orderBy('id', 'asc')->get();
		foreach($adminUser as $admin)
		{
			Mail::to($admin->email)->send(new AdminEmail($admin->first_name, $senderName, $message, $senderEmail));
		}
		AuditReportsController::store('Contacts', 'New Email Sent', "Email Sent To Admin", 0);
        //return view('contacts.view_contact')->with($data);
		return redirect('/');
    }
    /*public function update(Request $request, ContactPerson $contact) {
        $this->validate($request, [
            'first_name' => 'required',
            'surname' => 'required',
        ]);

        //Cell number formatting
        $request['cell_number'] = str_replace(' ', '', $request['cell_number']);
        $request['cell_number'] = str_replace('-', '', $request['cell_number']);
        $request['cell_number'] = str_replace('(', '', $request['cell_number']);
        $request['cell_number'] = str_replace(')', '', $request['cell_number']);

        if ($request['email'] != $contact->email) {
            $this->validate($request, [
                'email' => 'unique:contacts_contacts,email',
            ]);
        }

        if ($request['cell_number'] != $contact->cell_number) {
            $this->validate($request, [
                'cell_number' => 'unique:contacts_contacts,cell_number',
            ]);
        }
        $contactData = $request->all();

        //Office number formatting
        $contactData['office_number'] = str_replace(' ', '', $contactData['office_number']);
        $contactData['office_number'] = str_replace('-', '', $contactData['office_number']);
        $contactData['office_number'] = str_replace('(', '', $contactData['office_number']);
        $contactData['office_number'] = str_replace(')', '', $contactData['office_number']);

        //Exclude empty fields from query
        foreach ($contactData as $key => $value)
        {
            if (empty($contactData[$key])) {
                unset($contactData[$key]);
            }
        }

        //Save ContactPerson record
        $contact->update($contactData);
		AuditReportsController::store('Contacts', 'Record Updated', "Updated By User", 0);
        //Redirect to all usr view
        return redirect("/contacts/$contact->id/edit")->with('success_edit', "The contact details have been updated successfully.");
    }*/
	
	public function update(Request $request, User $user) {
        //exclude token, method and command fields from query.
        $person = $request->all();
        unset($person['_token'], $person['_method'], $person['command']);

        //Cell number formatting
        $person['cell_number'] = str_replace(' ', '', $person['cell_number']);
        $person['cell_number'] = str_replace('-', '', $person['cell_number']);
        $person['cell_number'] = str_replace('(', '', $person['cell_number']);
        $person['cell_number'] = str_replace(')', '', $person['cell_number']);

        //exclude empty fields from query
        foreach ($person as $key => $value) {
            if (empty($person[$key])) {
                unset($person[$key]);
            }
        }

        //convert numeric values to numbers
        if (isset($person['res_postal_code'])) {
            $person['res_postal_code'] = (int) $person['res_postal_code'];
        }
        if (isset($person['res_province_id'])) {
            $person['res_province_id'] = (int) $person['res_province_id'];
        }
        if (isset($person['gender'])) {
            $person['gender'] = (int) $person['gender'];
        }
        if (isset($person['id_number'])) {
            $person['id_number'] = (int) $person['id_number'];
        }
        if (isset($person['marital_status'])) {
            $person['marital_status'] = (int) $person['marital_status'];
        }
        if (isset($person['ethnicity'])) {
            $person['ethnicity'] = (int) $person['ethnicity'];
        }

        //convert date of birth to unix time stamp
        if (isset($person['date_of_birth'])) {
            $person['date_of_birth'] = str_replace('/', '-', $person['date_of_birth']);
            $person['date_of_birth'] = strtotime($person['date_of_birth']);
        }

        //Update user and contact table
        $user->update($person);
        if (isset($person['company_id']) && $person['company_id'] > 0) $user->person->company_id = $person['company_id'];
        $user->person()->update($person);

        //Upload profile picture
        if ($request->hasFile('profile_pic')) {
            $fileExt = $request->file('profile_pic')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('profile_pic')->isValid()) {
                $fileName = $user->id . "_avatar." . $fileExt;
                $request->file('profile_pic')->storeAs('avatars', $fileName);
                //Update file name in hr table
                $user->person()->profile_pic = $fileName;
                $user->person()->update(['profile_pic' => $fileName]);
            }
        }
		AuditReportsController::store('Contacts', 'Record Updated', "Updated By User", 0);
        //return to the edit page
        return back();
    }
    public function getSearch(Request $request) {
        $personName = trim($request->person_name);
        $personIDNum = trim($request->id_number);

		$persons = DB::table('contacts_contacts')
		->where(function ($query) use ($personName) {
			if (!empty($personName)) {
				$query->where('first_name', 'ILIKE', "%$personName%");
			}
		})
		->where(function ($query) use ($personIDNum) {
			if (!empty($personIDNum)) {
				$query->where('id_number', 'ILIKE', "%$personIDNum%");
			}
		})
		->orderBy('first_name')
		->limit(100)
		->get();
			
        $data['page_title'] = "Clients";
        $data['page_description'] = "List of clients found";
        $data['persons'] = $persons;
        $data['m_silhouette'] = Storage::disk('local')->url('avatars/m-silhouette.jpg');
        $data['f_silhouette'] = Storage::disk('local')->url('avatars/f-silhouette.jpg');
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Client search result', 'active' => 1, 'is_module' => 0]
        ];
		AuditReportsController::store('Contacts', 'Contact Search Results Accessed', "Search Results Accessed", 0);
        return view('contacts.contacts')->with($data);
    }

    public function updatePassword(Request $request, User $user) {
        //return response()->json(['message' => $request['current_password']]);

        $validator = Validator::make($request->all(),[
            'current_password' => 'required',
            'new_password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:new_password'
        ]);

        $validator->after(function($validator) use ($request, $user){
            $userPW = $user->password;

            if (!(Hash::check($request['current_password'], $userPW))) {
                $validator->errors()->add('current_password', 'The current password is incorrect, please enter the correct current password.');
            }
        });

        $validator->validate();

        //Update user password
        $newPassword = $request['new_password'];
        $user->password = Hash::make($newPassword);
        $user->update();
		AuditReportsController::store('Contacts', 'Contact Password Updated', "Password Updated", 0);
        return response()->json(['success' => 'Password updated successfully.'], 200);
    }
}
