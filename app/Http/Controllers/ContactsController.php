<?php

namespace App\Http\Controllers;

use App\CompanyIdentity;
use App\ContactCompany;
use App\ContactPerson;
use App\ContactsCommunication;
use App\CrmDocumentType;
use App\Country;
use App\public_reg;
use App\contactsClientdocuments;
use App\Mail\ConfirmRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\adminEmail;
use App\Mail\ClientCommunication;
use App\Http\Requests;
use App\HRPerson;
use App\SmS_Configuration;
use App\User;
use App\DivisionLevel;
use App\Province;
use App\Http\Controllers\AuditReportsController;
use App\Http\Controllers\BulkSMSController;
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

    public function index()
    {
        $companies = ContactCompany::where('status', 1)->orderBy('name')->get();
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();

        $data['page_title'] = "Clients";
        $data['page_description'] = "Search Clients";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Search client', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'search clients';
        $data['companies'] = $companies;
        $data['provinces'] = $provinces;
        AuditReportsController::store('Clients', 'Clients Search Page Accessed', "Actioned By User", 0);
        return view('contacts.search_contact')->with($data);
    }

    public function create($companyID = null)
    {
        $contactTypes = [1 => 'Company Rep', 2 => 'Student', 3 => 'Learner', 4 => 'Official', 5 => 'Educator', 6 => 'Osizweni Employee', 7 => 'Osizweni Board Member', 8 => 'Other'];
        $orgTypes = [1 => 'Private Company', 2 => 'Parastatal', 3 => 'School', 4 => 'Government', 5 => 'Other'];
        $companies = ContactCompany::where('status', 1)
            ->where(function ($query) use ($companyID) {
                if ($companyID) $query->where('id', $companyID);
            })
            ->orderBy('name')->get();
        $data['companies'] = $companies;
        $data['page_title'] = "Contacts";
        $data['page_description'] = "Add a New Contact";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Add contact', 'active' => 1, 'is_module' => 0]
        ];
        $data['contact_types'] = $contactTypes;
        $data['org_types'] = $orgTypes;
        $data['companyID'] = $companyID;
        $data['active_mod'] = 'CrM';
        $data['active_rib'] = 'Search Company';
        //die('what');
        AuditReportsController::store('Contacts', 'Contacts Contact Page Accessed', "Actioned By User", 0);
        return view('contacts.add_contact')->with($data);
    }

    public function addContact()
    {
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

    public function educatorRegistration()
    {
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

    public function learnerRegistration()
    {
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'surname' => 'required',
            'create_login' => 'required',
            'email' => 'bail|unique:contacts_contacts,email|required_if:create_login,1',
            'cell_number' => 'unique:contacts_contacts,cell_number',
        ]);

        $personData = $request->all();

        //Cell number formatting
        $personData['cell_number'] = str_replace(' ', '', $personData['cell_number']);
        $personData['cell_number'] = str_replace('-', '', $personData['cell_number']);
        $personData['cell_number'] = str_replace('(', '', $personData['cell_number']);
        $personData['cell_number'] = str_replace(')', '', $personData['cell_number']);

        //Create person object
        $person = new ContactPerson($personData);
        $person->cell_number = (empty($person->cell_number)) ? null : $person->cell_number;
        $person->status = 1;
        if (!empty($request->company_id)) $person->company_id = $request->company_id;

        $createLogin = (int)$request->input('create_login');
        if ($createLogin === 1) {
            //save user details
            $user = new User;
            $user->email = $request->email;
            $generatedPassword = str_random(10);
            $user->password = Hash::make($generatedPassword);
            $user->type = 2;
            $user->status = 1;
            $user->save();

            //Save ContactPerson record
            $user->addPerson($person);

            //Send email to client
            Mail::to($user->email)->send(new ConfirmRegistration($user, $generatedPassword));
        } else $person->save(); //save ContactPerson without login details

        //Notify admin about the new applicant
        /* $administrators = HRPerson::where('position', 2)->get();
         foreach ($administrators as $admin) {
             Mail::to("$admin->email")->send(new NewClientAdminNotification($admin, $user->id));
         }
 */
        //Redirect to all usr view
        AuditReportsController::store('Contacts', 'New Contact Added', "Contact Successfully added", 0);
        return redirect("/contacts/$person->id/edit")->with('success_add', "The contact has been added successfully.");
    }
    /*public function addToCompany(Request $request, $companyID) {
        return $this->store($request, $companyID);
    }*/

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
    public function edit(ContactPerson $person)
    {
        $loggedInUser = Auth::user();
        $person->load('user', 'company');
        $provinces = Province::where('country_id', 1)->orderBy('name', 'asc')->get();
        $ethnicities = DB::table('ethnicities')->where('status', 1)->orderBy('value', 'asc')->get();
        $marital_statuses = DB::table('marital_statuses')->where('status', 1)->orderBy('value', 'asc')->get();
        $divisionLevels = DivisionLevel::where('active', 1)->orderBy('id', 'desc')->get();//->load('divisionLevelGroup');
        $companies = ContactCompany::where('status', 1)->orderBy('name')->get();
        $canDeleteAndActivate = false;
        if ($loggedInUser->type == 1 || $loggedInUser->type == 3) $canDeleteAndActivate = true;
        $data['page_title'] = "Clients";
        $data['page_description'] = "View/Update client details";
        $data['back'] = "/contacts";
        $data['view_by_admin'] = 1;

        $data['division_levels'] = $divisionLevels;
        $data['contactPerson'] = $person;
        $data['avatar'] = $person->profile_pic_url;
        $data['provinces'] = $provinces;
        $data['ethnicities'] = $ethnicities;
        $data['marital_statuses'] = $marital_statuses;
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Client details', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'search clients';
        $data['companies'] = $companies;
        $data['canDeleteAndActivate'] = $canDeleteAndActivate;
        $data['view_by_admin'] = 1;
        AuditReportsController::store('Contacts', 'Contact Edited', "Contact On Edit Mode", 0);
        return view('contacts.view_contact')->with($data);
    }

    public function profile()
    {
        $user = Auth::user()->load('person');
        $person = $user->person;
        return $this->edit($person);
    }

    /*
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
    */
    public function emailAdmin(Request $request)
    {
        $emails = $request->all();
        $message = $emails['message'];
        $user = Auth::user()->load('person');
        //return $user;
        $senderName = $user->person->first_name . " " . $user->person->surname;
        $senderEmail = $user->person->email;
        $adminUser = DB::table('hr_people')->where('position', 2)->orderBy('id', 'asc')->get();
        foreach ($adminUser as $admin) {
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

    public function update(Request $request, ContactPerson $contactPerson)
    {
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
            $person['res_postal_code'] = (int)$person['res_postal_code'];
        }
        if (isset($person['res_province_id'])) {
            $person['res_province_id'] = (int)$person['res_province_id'];
        }
        if (isset($person['gender'])) {
            $person['gender'] = (int)$person['gender'];
        }
        if (isset($person['id_number'])) {
            $person['id_number'] = (int)$person['id_number'];
        }
        if (isset($person['marital_status'])) {
            $person['marital_status'] = (int)$person['marital_status'];
        }
        if (isset($person['ethnicity'])) {
            $person['ethnicity'] = (int)$person['ethnicity'];
        }
        if (isset($person['division_level_5'])) {
            $person['division_level_5'] = (int)$person['division_level_5'];
        }
        if (isset($person['division_level_4'])) {
            $person['division_level_4'] = (int)$person['division_level_4'];
        }
        if (isset($person['division_level_3'])) {
            $person['division_level_3'] = (int)$person['division_level_3'];
        }
        if (isset($person['division_level_2'])) {
            $person['division_level_2'] = (int)$person['division_level_2'];
        }
        if (isset($person['division_level_1'])) {
            $person['division_level_1'] = (int)$person['division_level_1'];
        }

        //convert date of birth to unix time stamp
        if (isset($person['date_of_birth'])) {
            $person['date_of_birth'] = str_replace('/', '-', $person['date_of_birth']);
            $person['date_of_birth'] = strtotime($person['date_of_birth']);
        }

        //Update user and contact table
        if ($contactPerson->user_id) $contactPerson->user()->update($person);
        if (isset($person['company_id']) && $person['company_id'] > 0) $contactPerson->company_id = $person['company_id'];
        $contactPerson->update($person);

        //Upload profile picture
        if ($request->hasFile('profile_pic')) {
            $fileExt = $request->file('profile_pic')->extension();
            if (in_array($fileExt, ['jpg', 'jpeg', 'png']) && $request->file('profile_pic')->isValid()) {
                $fileName = time() . "_avatar." . $fileExt;
                $request->file('profile_pic')->storeAs('avatars', $fileName);
                //Update file name in hr table
                $contactPerson->update(['profile_pic' => $fileName]);
            }
        }
        AuditReportsController::store('Contacts', 'Record Updated', "Updated By User", 0);
        //return to the edit page
        return back();
    }

    public function getSearch(Request $request, $print = false)
    {
        $personName = trim($request->person_name);
        $personIDNum = trim($request->id_number);
        $personPassportNum = trim($request->passport_number);
        $personCompanyID = $request->company_id;
        $personCompanyName = $request->company_name;
        $provinceID = $request->res_province_id;
        $provinceName = $request->res_province_name;

        $persons = ContactPerson::
        where(function ($query) use ($personName) {
            if (!empty($personName)) {
                $query->where('first_name', 'ILIKE', "%$personName%");
                $query->orWhere('surname', 'ILIKE', "%$personName%");
            }
        })
            ->where(function ($query) use ($personIDNum) {
                if (!empty($personIDNum)) {
                    $query->where('id_number', 'ILIKE', "%$personIDNum%");
                }
            })
            ->where(function ($query) use ($personPassportNum) {
                if (!empty($personPassportNum)) {
                    $query->where('passport_number', 'ILIKE', "%$personPassportNum%");
                }
            })
            ->where(function ($query) use ($provinceID) {
                if (!empty($provinceID)) {
                    $query->where('res_province_id', $provinceID);
                }
            })
            ->where(function ($query) use ($personCompanyID) {
                if (!empty($personCompanyID)) {
                    $query->where('company_id', $personCompanyID);
                }
            })
            ->orderBy('first_name')
            ->orderBy('surname')
            //->limit(100)
            ->with('company')
            ->get();

        $data['page_title'] = "Clients";
        $data['page_description'] = "List of clients found";
        $data['personName'] = $personName;
        $data['personIDNum'] = $personIDNum;
        $data['personPassportNum'] = $personPassportNum;
        $data['personCompanyID'] = $personCompanyID;
        $data['personCompanyName'] = $personCompanyName;
        $data['provinceID'] = $provinceID;
        $data['provinceName'] = $provinceName;
        $data['persons'] = $persons;
        $data['status_values'] = [0 => 'Inactive', 1 => 'Active'];
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Client search result', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'Contacts';
        $data['active_rib'] = 'Search Clients';
        AuditReportsController::store('Contacts', 'Contact Search Results Accessed', "Search Results Accessed", 0);

        if ($print) {
            $data['report_name'] = 'Contacts Search Result';
            $data['user'] = Auth::user()->load('person');
            $data['company_logo'] = CompanyIdentity::systemSettings()['company_logo_url'];
            $data['date'] = Carbon::now()->format('d/m/Y');

            return view('contacts.print_contacts')->with($data);
        } else return view('contacts.contacts')->with($data);
    }

    public function printSearch(Request $request)
    {
        return $this->getSearch($request, true);
    }

    public function updatePassword(Request $request, User $user)
    {
        //return response()->json(['message' => $request['current_password']]);

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'bail|required|min:6',
            'confirm_password' => 'bail|required|same:new_password'
        ]);

        $validator->after(function ($validator) use ($request, $user) {
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

    //function to activate/deactivate a contact person and his security user credentials
    public function activateContact(ContactPerson $person)
    {
        $person->load('user');
        if ($person->status == 1) $status = 0;
        else $status = 1;

        $person->status = $status;
        $person->update();

        if ($person->user) {
            $user = $person->user;
            $user->status = $status;
            $user->update();
        }

        AuditReportsController::store('Contacts', 'Client Status Changed', "Status Changed to $status for [$person->id] $person->full_name", 0);
        return back();
    }

    //function to delete a contact person and his security user credentials
    public function deleteContact(ContactPerson $person)
    {
        $user = Auth::user();
        if ($user->type == 1 || $user->type == 3) {
            $person->load('user');
            if ($person->user) {
                $user = $person->user;
                $user->delete();
            }
            $person->delete();

            AuditReportsController::store('Contacts', 'Client Deleted', "Client ID has been deleted", 0);
            return redirect('/contacts');
        }
    }

    public function createLoginDetails(ContactPerson $person)
    {
        //save user details
        $user = new User;
        $user->email = $person->email;
        $generatedPassword = str_random(10);
        $user->password = Hash::make($generatedPassword);
        $user->type = 2;
        $user->status = 1;
        $user->save();

        //update ContactPerson record
        $person->user_id = $user->id;
        $person->update();

        //Send email to client
        Mail::to($user->email)->send(new ConfirmRegistration($user, $generatedPassword));
        AuditReportsController::store('Contacts', 'Login Details Create', "Contact  Login Details Successfully Created", 0);

        return back();
    }

    //function to reset the client's password to a random one and email it to the client
    /*public function resetRandomPassword(User $user) {
        $generatedPassword = str_random(10);
        $user->password = Hash::make($generatedPassword);
        $user->update();

        //send email
        //Mail::to($user->email)->send(new ConfirmRegistration($user, $generatedPassword));

        AuditReportsController::store('Contacts', 'Contact Password Updated', "Password Updated", 0);

        return back()->with(['success_pw_updated' => "The client's password has been successfully reset. the client will receive an email with the new password."]);
    }*/

    public function sendMessageIndex()
    {
        $contactPersons = DB::table('contacts_contacts')
            ->select('contacts_contacts.*', 'contact_companies.name as comp_name')
            ->leftJoin('contact_companies', 'contacts_contacts.company_id', '=', 'contact_companies.id')
            ->where('contacts_contacts.status', 1)->orderBy('first_name', 'asc')->orderBy('surname', 'asc')->get();

        $data['page_title'] = "Client Communication";
        $data['page_description'] = "Send a Message To Your Clients";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1],
            ['title' => 'Send Message', 'active' => 1, 'is_module' => 0]
        ];
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'send message';
        $data['contactPersons'] = $contactPersons;
        AuditReportsController::store('Contacts', 'Send Message Page Accessed', "Actioned By User", 0);
        return view('contacts.send_message')->with($data);
    }

    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'clients.*' => 'required',
            'message_type' => 'required',
            'email_content' => 'bail|required_if:message_type,1',
            'sms_content' => 'bail|required_if:message_type,2|max:180',
        ]);
		$mobileArray = array();
        $CommunicationData = $request->all();
        unset($CommunicationData['_token']);

        # Save email
        $user = Auth::user()->load('person');;
		$email = !empty($user->person->email) ? $user->person->email : '';
        foreach ($CommunicationData['clients'] as $clientID) {
            $client = ContactPerson::where('id', $clientID)->first();
			$companyID = !empty($client->company_id) ? $client->company_id :0 ;
			if (!empty($companyID))
			{
				$ContactsCommunication = new ContactsCommunication;
				$ContactsCommunication->message = !empty($CommunicationData['email_content']) ? $CommunicationData['email_content'] : $CommunicationData['sms_content'];
				$ContactsCommunication->communication_type = $CommunicationData['message_type'];
				$ContactsCommunication->contact_id = $clientID;
				$ContactsCommunication->company_id = $companyID;
				$ContactsCommunication->status = 1;
				$ContactsCommunication->sent_by = $user->person->id;
				$ContactsCommunication->communication_date = strtotime(date("Y-m-d"));
				$ContactsCommunication->time_sent = date("h:i:sa");
				$ContactsCommunication->save();
				if ($CommunicationData['message_type'] == 1 && !empty($client->email))
					# Send Email to Client
					Mail::to($client->email)->send(new ClientCommunication($client, $ContactsCommunication, $email));
				elseif ($CommunicationData['message_type'] == 2 && !empty($client->cell_number))
						$mobileArray[] = $this->formatCellNo($client->cell_number);
			}
        }
        if ($CommunicationData['message_type'] == 2 && !empty($mobileArray)) {
            #format cell numbers
            # send out the message
            $CommunicationData['sms_content'] = str_replace("<br>", "", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace(">", "-", $CommunicationData['sms_content']);
            $CommunicationData['sms_content'] = str_replace("<", "-", $CommunicationData['sms_content']);
            BulkSMSController::send($mobileArray, $CommunicationData['sms_content']);
        }
        AuditReportsController::store('Contacts', 'Client Communication Sent', "Message: $ContactsCommunication->message", 0);
        return redirect("/contacts/send-message")->with('success_sent', "Communication Successfully Sent to Client");
    }

    public function setup()
    {
        $data['page_title'] = "CRM Setup";
        $data['page_description'] = "CRM set up ";
        $data['breadcrumb'] = [
            ['title' => 'CRM', 'path' => '/contacts/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];
        $SmSConfiguration = SmS_Configuration::first();
        $crmDocumentTypes = CrmDocumentType::get();
        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'setup';
        $data['SmSConfiguration'] = $SmSConfiguration;
        $data['crmDocumentTypes'] = $crmDocumentTypes;
        //return $SmSConfiguration;
        AuditReportsController::store('Contacts', 'Setup Search Page Accessed', "Actioned By User", 0);
        return view('contacts.setup')->with($data);
    }
	// save Document type
	public function saveDocumentType(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $documentType = new CrmDocumentType;
        $documentType->name = $docData['name'];
        $documentType->description = !empty($docData['description']) ? $docData['description']: '';
        $documentType->status = 1;
        $documentType->save();
        AuditReportsController::store('CRM', 'Document Type Saved', "Actioned By User", 0);
        return response()->json();
    }
	// update Document type
	public function updateDocumentType(Request $request, CrmDocumentType $type)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $docData = $request->all();
        unset($docData['_token']);

        $type->name = $docData['name'];
        $type->description = !empty($docData['description']) ? $docData['description']: '';
        $type->update();
        AuditReportsController::store('CRM', 'Document Type Updateded', "Actioned By User", 0);
        return response()->json();
    }
	//
	 public function docActivate(CrmDocumentType $type)
    {
        if ($type->status == 1)
            $stastus = 0;
        else
            $stastus = 1;

        $type->status = $stastus;
        $type->update();
        return back();
    }
	///
	public function saveSetup(Request $request)
    {
        $this->validate($request, [
            'sms_provider' => 'required',
            'sms_username' => 'required',
            'sms_password' => 'required',
        ]);

        $smsData = $request->all();
        unset($smsData['_token']);

        $SmSConfiguration = new SmS_Configuration;
        $SmSConfiguration->sms_provider = $smsData['sms_provider'];
        $SmSConfiguration->sms_username = $smsData['sms_username'];
        $SmSConfiguration->sms_password = $smsData['sms_password'];
        $SmSConfiguration->save();
        AuditReportsController::store('Contacts', 'SMS Setup Saved', "Actioned By User", 0);
        return redirect('/contacts/setup');
    }
    public function updateSMS(Request $request, SmS_Configuration $smsConfiguration)
    {
        $this->validate($request, [
            'sms_provider' => 'required',
            'sms_username' => 'required',
            'sms_password' => 'required',
        ]);
        $smsData = $request->all();
        unset($smsData['_token']);

        $smsConfiguration->sms_provider = $smsData['sms_provider'];
        $smsConfiguration->sms_username = $smsData['sms_username'];
        $smsConfiguration->sms_password = $smsData['sms_password'];
        $smsConfiguration->update();
        AuditReportsController::store('Contacts', 'SMS Setup Updated', "Actioned By User", 0);
        return redirect('/contacts/setup');
    }

    function formatCellNo($sCellNo)
    {
        # Remove the following characters from the phone number
        $cleanup_chr = array("+", " ", "(", ")", "\r", "\n", "\r\n");

        # clean phone number
        $sCellNo = str_replace($cleanup_chr, '', $sCellNo);

        #Internationalise  the number
        if ($sCellNo{0} == "0") $sCellNo = "27" . substr($sCellNo, 1);

        return $sCellNo;
    }

    public function viewdocuments(ContactPerson $person)
    {
        //return $person;
        $personID = $person->id;
        $data['page_title'] = "Contacts Setup";
        $data['page_description'] = "Contacts set up ";
        $data['breadcrumb'] = [
            ['title' => 'Contacts', 'path' => '/contacts/setup', 'icon' => 'fa fa-users', 'active' => 0, 'is_module' => 1], ['title' => 'Setup', 'active' => 1, 'is_module' => 0]
        ];

        $documents = contactsClientdocuments::orderby('id', 'asc')->where('client_id', $personID)->get();

        $data['person'] = $person;
        $data['documents'] = $documents;
        $data['personID'] = $personID;
        $data['active_mod'] = 'contacts';
        $data['active_rib'] = 'setup';
        AuditReportsController::store('Contacts',"Accessed Documents For Client: $person->first_name $person->surname", "Accessed By User", 0);
        return view('contacts.mydocuments')->with($data);
    }

    public function addDocumets(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:client_documents,document_name',
            'date_from' => 'required',
            'exp_date' => 'required',
            'supporting_docs' => 'required',
        ]);
        $clientdocData = $request->all();
        unset($clientdocData['_token']);

        $Datefrom = $clientdocData['date_from'] = str_replace('/', '-', $clientdocData['date_from']);
        $Datefrom = $clientdocData['date_from'] = strtotime($clientdocData['date_from']);

        $Expirydate = $clientdocData['exp_date'] = str_replace('/', '-', $clientdocData['exp_date']);
        $Expirydate = $clientdocData['exp_date'] = strtotime($clientdocData['exp_date']);
		$clientDetails = ContactPerson::where('id', $clientdocData['clientID'])->first();
        $clientDoc = new contactsClientdocuments();
        $clientDoc->document_name = $clientdocData['name'];
        $clientDoc->description = $clientdocData['description'];
        $clientDoc->date_from = $Datefrom;
        $clientDoc->expirydate = $Expirydate;
        $clientDoc->client_id = $clientdocData['clientID'];
        $clientDoc->status = 1;
        $clientDoc->save();

        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_client_documents." . $fileExt;
                $request->file('supporting_docs')->storeAs('ContactClient/client_documents', $fileName);
                //Update file name in the table
                $clientDoc->supporting_docs = $fileName;
                $clientDoc->update();
            }
        }
        AuditReportsController::store('Contacts', "New Document Added For Client: $clientDetails->first_name $clientDetails->surname, Document Name:$clientDoc->document_name", "Added by User", 0);
        return response()->json();
    }

    public function editClientdoc(Request $request, contactsClientdocuments $document)
    {
        $this->validate($request, [
//            'name' => 'required|unique:contactsClientdocuments,name',
//            'date_from' => 'required',
//            'exp_date' => 'required',
//            'supporting_docs' => 'required',
        ]);
        $clientdocData = $request->all();
        unset($clientdocData['_token']);

        $Datefrom = $clientdocData['date_from'] = str_replace('/', '-', $clientdocData['date_from']);
        $Datefrom = $clientdocData['date_from'] = strtotime($clientdocData['date_from']);

        $Expirydate = $clientdocData['expirydate'] = str_replace('/', '-', $clientdocData['expirydate']);
        $Expirydate = $clientdocData['expirydate'] = strtotime($clientdocData['expirydate']);

        $document->document_name = $clientdocData['document_name'];
        $document->description = $clientdocData['description'];
        $document->date_from = $Datefrom;
        $document->expirydate = $Expirydate;
        $document->client_id = $clientdocData['clientID'];
        $document->status = 1;
        $document->save();
		$clientDetails = ContactPerson::where('id', $clientdocData['clientID'])->first();
        //Upload supporting document
        if ($request->hasFile('supporting_docs')) {
            $fileExt = $request->file('supporting_docs')->extension();
            if (in_array($fileExt, ['pdf', 'docx', 'doc']) && $request->file('supporting_docs')->isValid()) {
                $fileName = time() . "_client_documents." . $fileExt;
                $request->file('supporting_docs')->storeAs('ContactClient/client_documents', $fileName);
                //Update file name in the table
                $document->supporting_docs = $fileName;
                $document->update();
            }
        }
        AuditReportsController::store('Contacts', "Client Document Updated For client: $clientDetails->first_name $clientDetails->surname, Document Name: $document->document_name", "Updated By User", 0);
        return response()->json();
    }

    public function clientdocAct(Request $request, contactsClientdocuments $document)
    {
		$clientDetails = ContactPerson::where('id', $document->client_id)->first();
        if ($document->status == 1)
		{
            $stastus = 0;
			$label = "De-Activated";
		}
        else
		{
            $stastus = 1;
			$label = "Activated";
		}
		
        $document->status = $stastus;
        $document->update();
		AuditReportsController::store('Contacts', "Client Document Status Updated For Client: $clientDetails->first_name $clientDetails->surname To $label, Document Name: $document->document_name", "Updated By User", 0);
        return back();
    }

    public function deleteClientDoc(contactsClientdocuments $document)
    {
		$clientDetails = ContactPerson::where('id', $document->client_id)->first();
        $document->delete();
        AuditReportsController::store('Contacts', "Client Document Deleted, Document Name:$document->name, Client:$clientDetails->first_name $clientDetails->surname, Document Name: $document->document_name", "Deleted By User", 0);
        return back();
    }
	
	public function reports()
    {
        $hrID = Auth::user()->person->id;
        $employees = HRPerson::where('status', 1)->get();
        // return $employees;
		$hrID = Auth::user()->id;
		$userAccess = DB::table('security_modules_access')->select('security_modules_access.user_id')
            ->leftJoin('security_modules', 'security_modules_access.module_id', '=', 'security_modules.id')
            ->where('security_modules.code_name', 'job_cards')->where('security_modules_access.access_level', '>=', 4)
            ->where('security_modules_access.user_id', $hrID)->pluck('user_id')->first();
		if (!empty($userAccess))
			$employees = HRPerson::where('status', 1)->where('id',$hrID)->get();

        $companies = ContactCompany::where('status', 1)->orderBy('name')->get();
        $contacts = ContactPerson::where('status', 1)->orderBy('first_name')->get();
		$types = CrmDocumentType::where('status', 1)->orderBy('name', 'asc')->get();
        $data['page_title'] = "contacts";
        $data['page_description'] = "Reports";
        $data['breadcrumb'] = [
            ['title' => 'Clients', 'path' => '/contacts/Clients-reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 1],
            ['title' => 'Clients', 'path' => '/Clients/Clients-reports', 'icon' => 'fa-tasks', 'active' => 0, 'is_module' => 0],
            ['title' => 'Clients Clients-reports', 'active' => 1, 'is_module' => 0]
        ];

        $data['active_mod'] = 'CRM';
        $data['active_rib'] = 'reports';
        $data['companies'] = $companies;
        $data['contacts'] = $contacts;
        $data['types'] = $types;

        $data['employees'] = $employees;
        AuditReportsController::store('Audit', 'View Audit Search', "view Audit", 0);
        return view('contacts.contacts_report_index')->with($data);
    }
}