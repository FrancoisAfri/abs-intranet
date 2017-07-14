<?php

use Illuminate\Database\Seeder;
use App\User;
use App\HRPerson;
use App\Country;
use App\Province;
use App\modules;
use App\module_ribbons;
use App\DivisionLevel;
use App\LeaveType;
use App\business_card;
use App\leave_profile;
use App\leave_configuration;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //insert default user
        $user = new User;
        $user->email = 'smalto@afrixcel.co.za';
        $user->password = Hash::make('sptusr1');
        $user->type = 3;
        $user->status = 1;
        $user->save();

        //insert default user's hr record
        $person = new HRPerson();
        $person->first_name = 'Admin';
        $person->surname = 'User';
        $person->email = 'smalto@afrixcel.co.za';
        $person->status = 1;
        $user->addPerson($person);

        //insert default user
        $user = new User;
        $user->email = 'francois@afrixcel.co.za';
        $user->password = Hash::make('sptusr@!');
        $user->type = 3;
        $user->status = 1;
        $user->save();
		
		//insert default user's hr record
        $person = new HRPerson();
        $person->first_name = 'Francois';
        $person->surname = 'keou';
        $person->email = 'francois@afrixcel.co.za';
        $person->status = 1;
        $user->addPerson($person);

        //insert default user
        $user = new User;
        $user->email = 'nkosana@afrixcel.co.za';
        $user->password = Hash::make('theone');
        $user->type = 3;
        $user->status = 1;
        $user->save();

        //insert default user's hr record
        $person = new HRPerson();
        $person->first_name = 'Nkosana';
        $person->surname = 'Biyela';
        $person->email = 'nkosana@afrixcel.co.za';
        $person->status = 1;
        $user->addPerson($person);

        # 
        $card = new business_card;
        $card->hr_id = 1;
        $card->status = 0;
        $card->save();

        $card = new business_card;
        $card->hr_id = 2;
        $card->status = 0;
        $card->save();

        $card = new business_card;
        $card->hr_id = 3;
        $card->status = 0;
        $card->save();
        


        //insert default country
        $country = new Country;
        $country->name = 'South Africa';
        $country->a2_code = 'ZA';
        $country->a3_code = 'ZAF';
        $country->numeric_code = 710;
        $country->dialing_code = '27';
        $country->abbreviation = 'RSA';
        $country->save();
        
        //insert default country's provinces
        $province = new Province();
        $province->name = 'Eastern Cape';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Free State';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Gauteng';
        $province->abbreviation = 'GP';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'KwaZulu-Natal';
        $province->abbreviation = 'KZN';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Limpopo';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Mpumalanga';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'North West';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Northern Cape';
        $country->addProvince($province);

        $province = new Province();
        $province->name = 'Western Cape';
        $country->addProvince($province);
        #
        
         //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Approved',
            'description' => 'Approved',
        ]);
         //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Require managers approval',
            'description' => 'Require managers approval',
        ]);
         //insert leave statuses
         DB::table('leave_status')->insert([
            'name' => 'Require department head approval',
            'description' => 'Require department head approval',
        ]);
         //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Require hr approval',
            'description' => 'Require hr approval',
        ]);
          //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Require payroll approval',
            'description' => 'Require payroll approval',
        ]);
          //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Rejectd by managers',
            'description' => 'Rejectd by managers',
        ]);
          //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Rejectd by department head',
            'description' => 'Rejectd by department head',
        ]);
          //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Rejectd by hr',
            'description' => 'rejectd_by_hr',
        ]);
          //insert leave statuses
        DB::table('leave_status')->insert([
            'name' => 'Rejectd by payroll',
            'description' => 'rejectd_by_payroll',
        ]);



        #
        //insert marital statuses
        DB::table('marital_statuses')->insert([
            'value' => 'Single',
            'status' => 1,
        ]);
        DB::table('marital_statuses')->insert([
            'value' => 'Married',
            'status' => 1,
        ]);
        DB::table('marital_statuses')->insert([
            'value' => 'Divorced',
            'status' => 1,
        ]);
        DB::table('marital_statuses')->insert([
            'value' => 'Widower',
            'status' => 1,
        ]);

        //insert ethnicity
        DB::table('ethnicities')->insert([
            'value' => 'African',
            'status' => 1,
        ]);
        DB::table('ethnicities')->insert([
            'value' => 'Asian',
            'status' => 1,
        ]);
        DB::table('ethnicities')->insert([
            'value' => 'Caucasian',
            'status' => 1,
        ]);
        DB::table('ethnicities')->insert([
            'value' => 'Coloured',
            'status' => 1,
        ]);
        DB::table('ethnicities')->insert([
            'value' => 'Indian',
            'status' => 1,
        ]);
		//insert public Holidays
        DB::table('public_holidays')->insert([
            'day' => 1482789600,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Public Holiday',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1293228000,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Christmas Day',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1285279200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Heritage Day',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1293314400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Day of Goodwill',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1269122400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Human Rights Day',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1272319200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Freedom Day',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1272664800,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Workers Day',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1276639200,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Youth Day',
        ]);DB::table('public_holidays')->insert([
            'day' => 1281304800,
            'country_id' => 197,
            'year' => 0,
            'name' => "National Women's Day",
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1292450400,
            'country_id' => 197,
            'year' => 0,
            'name' => 'Day of Reconciliation',
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1262296800,
            'country_id' => 197,
            'year' => 0,
            'name' => "New Year's Day",
        ]);
		DB::table('public_holidays')->insert([
            'day' => 1399413600,
            'country_id' => 197,
            'year' => 2014,
            'name' => 'Voting Day',
        ]);

        //insert the employees group levels (division departments)
        $groupLevel = new DivisionLevel();
        $groupLevel->level = 1;
        $groupLevel->active = 0;
        $groupLevel->save();
        
        $groupLevel = new DivisionLevel();
        $groupLevel->level = 2;
        $groupLevel->active = 0;
        $groupLevel->save();

        $groupLevel = new DivisionLevel();
        $groupLevel->level = 3;
        $groupLevel->active = 0;
        $groupLevel->save();

        $groupLevel = new DivisionLevel();
        $groupLevel->level = 4;
        $groupLevel->name = "Department";
        $groupLevel->plural_name = "Departments";
        $groupLevel->active = 1;
        $groupLevel->save();

        $groupLevel = new DivisionLevel();
        $groupLevel->level = 5;
        $groupLevel->name = "Division";
        $groupLevel->plural_name = "Divisions";
        $groupLevel->active = 1;
        $groupLevel->save();

        //Insert navigation menus
        $module = new modules(); //Contacts
        $module->active = 1;
        $module->name = 'Contacts';
        $module->path = 'contacts';
        $module->font_awesome = 'fa-users';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Add Company';
        $ribbon->description = 'Add Company';
        $ribbon->ribbon_path = 'contacts/company/create';
        $ribbon->access_level = 2;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Add Client';
        $ribbon->description = 'Add Client';
        $ribbon->ribbon_path = 'contacts/create';
        $ribbon->access_level = 2;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Search Clients';
        $ribbon->description = 'Search Clients';
        $ribbon->ribbon_path = 'contacts';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Search Company';
        $ribbon->description = 'Search Company';
        $ribbon->ribbon_path = 'contacts/company_search';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		
        $module = new modules(); // Security
        $module->active = 1;
        $module->name = 'Security';
        $module->path = 'users';
        $module->font_awesome = 'fa-lock';
        $module->save();

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Create User';
        $ribbon->description = 'Add User';
        $ribbon->ribbon_path = 'users/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Search Users';
        $ribbon->description = 'Search Users';
        $ribbon->ribbon_path = 'users';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Modules';
        $ribbon->description = 'Modules';
        $ribbon->ribbon_path = 'users/modules';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'users/setup';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$module = new modules(); //Employee Records
        $module->active = 1;
        $module->name = 'Employee Records';
        $module->path = 'hr ';
        $module->font_awesome = 'fa-users';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Job Titles';
        $ribbon->description = 'Job Titles';
        $ribbon->ribbon_path = 'hr/job_title';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Company Setup';
        $ribbon->description = 'Company Setup';
        $ribbon->ribbon_path = 'hr/company_setup';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Business card';
        $ribbon->description = 'Business card';
        $ribbon->ribbon_path = 'hr/business_card';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        // $ribbon = new module_ribbons();
        // $ribbon->active = 1;
        // $ribbon->sort_order = 4;
        // $ribbon->ribbon_name = 'Employees Documents';
        // $ribbon->description = 'Employees Documents';
        // $ribbon->ribbon_path = 'hr/emp_document';
        // $ribbon->access_level = 4;
        // $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Search';
        $ribbon->description = 'Search';
        $ribbon->ribbon_path = 'hr/emp_qualification';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 6;
        $ribbon->ribbon_name = 'Talent Pool';
        $ribbon->description = 'Talent Pool';
        $ribbon->ribbon_path = 'hr/talent_pool';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        // $ribbon = new module_ribbons();
        // $ribbon->active = 1;
        // $ribbon->sort_order = 7;
        // $ribbon->ribbon_name = 'Employee Search';
        // $ribbon->description = 'Employee Search';
        // $ribbon->ribbon_path = 'hr/emp_search';
        // $ribbon->access_level = 4;
        // $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 8;
        $ribbon->ribbon_name = 'Upload';
        $ribbon->description = 'Upload';
        $ribbon->ribbon_path = 'hr/upload';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 9;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'hr/setup';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$module = new modules(); //Leave Management
        $module->active = 1;
        $module->name = 'Leave Management';
        $module->path = 'leave';
        $module->font_awesome = 'fa-glass';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Apply';
        $ribbon->description = 'Leave Application';
        $ribbon->ribbon_path = 'leave/application';
        $ribbon->access_level = 1;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Approval';
        $ribbon->description = 'Leave Approval';
        $ribbon->ribbon_path = 'leave/approval';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Reports';
        $ribbon->ribbon_path = 'leave/reports';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Leave Types';
        $ribbon->description = 'Leave Types';
        $ribbon->ribbon_path = 'leave/types';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Allocate Leave Types';
        $ribbon->description = 'Allocate Leave Types';
        $ribbon->ribbon_path = 'leave/Allocate_leave_types';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);


		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'leave/setup';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);

        
        //  $ribbon = new module_ribbons();
        // $ribbon->active = 1;
        // $ribbon->sort_order = 4;
        // $ribbon->ribbon_name = 'Leave History Audit';
        // $ribbon->description = 'Leave History Audit';
        // $ribbon->ribbon_path = 'leave/Leave_History_Audit';
        // $ribbon->access_level = 5;
        // $module->addRibbon($ribbon);
		
		$module = new modules(); //Audit Management
        $module->active = 1;
        $module->name = 'Audit';
        $module->path = 'Audit Management';
        $module->font_awesome = 'fa-eye';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Audit Report';
        $ribbon->description = 'Audit Report';
        $ribbon->ribbon_path = 'audit/reports';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$module = new modules(); //Performance Appraisal
        $module->active = 1;
        $module->name = 'Performance Appraisal';
        $module->path = 'appraisal';
        $module->font_awesome = 'fa-line-chart';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Templates';
        $ribbon->description = 'Templates';
        $ribbon->ribbon_path = 'appraisal/templates';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Categories';
        $ribbon->description = 'Categories';
        $ribbon->ribbon_path = 'appraisal/categories';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Appraisals';
        $ribbon->description = 'Appraisals';
        $ribbon->ribbon_path = 'appraisal/load_appraisals';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'My Appraisal';
        $ribbon->description = 'My Appraisal';
        $ribbon->ribbon_path = 'appraisal/appraise-yourself';
        $ribbon->access_level = 2;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Perks';
        $ribbon->description = 'Appraisal Perks';
        $ribbon->ribbon_path = 'appraisal/perks';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 6;
        $ribbon->ribbon_name = 'Search';
        $ribbon->description = 'Search';
        $ribbon->ribbon_path = 'appraisal/search';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 7;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Reports';
        $ribbon->ribbon_path = 'appraisal/reports';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 8;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'appraisal/setup';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
		
		$module = new modules(); //Induction/Tasks
        $module->active = 1;
        $module->name = 'Induction';
        $module->path = 'induction';
        $module->font_awesome = 'fa-tasks';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Create Induction';
        $ribbon->description = 'Create Induction';
        $ribbon->ribbon_path = 'induction/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Tasks Library';
        $ribbon->description = 'Tasks Library';
        $ribbon->ribbon_path = 'induction/tasks_library';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Induction Search';
        $ribbon->description = 'Induction Search';
        $ribbon->ribbon_path = 'induction/search';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Reports';
        $ribbon->ribbon_path = 'induction/reports';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'induction/setup';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$module = new modules(); //Meeting Munites/Tasks
        $module->active = 1;
        $module->name = 'Meeting Minutes';
        $module->path = 'meeting_minutes';
        $module->font_awesome = 'fa-calendar-check-o';
        $module->save();
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Create Minutes';
        $ribbon->description = 'Create Minutes';
        $ribbon->ribbon_path = 'meeting_minutes/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Search Minutes';
        $ribbon->description = 'Search Minutes';
        $ribbon->ribbon_path = 'meeting_minutes/search';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Reports';
        $ribbon->ribbon_path = 'meeting_minutes/reports';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'meeting_minutes/setup';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $module = new modules(); //Survey
        $module->active = 1;
        $module->name = 'Survey';
        $module->path = 'survey';
        $module->font_awesome = 'fa-list-alt';
        $module->save();
        
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Rating Links';
        $ribbon->description = 'Generate rate my service links';
        $ribbon->ribbon_path = 'survey/rating-links';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
		$ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Survey Questions';
        $ribbon->description = 'Survey Questions';
        $ribbon->ribbon_path = '	survey/questions';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Reports';
        $ribbon->description = 'Generate survey reports';
        $ribbon->ribbon_path = 'survey/reports';
        $ribbon->access_level = 4;
        $module->addRibbon($ribbon);
		
        $leave_config = new leave_configuration();
        $leave_config->save();
        
        //#leave_types
        $type = new LeaveType();
        $type->name = 'Annual';
        $type->status =1;
        $type->description = 'Annual Leave Type' ;
        $type->save();

        $type = new LeaveType();
        $type->name = 'Family';
        $type->status =1;
        $type->description = 'Family Leave Type' ;
        $type->save();

        $type = new LeaveType();
        $type->name = 'Maternity';
        $type->status =1;
        $type->description = 'Maternity Leave Type' ;
        $type->save();

        $type = new LeaveType();
        $type->name = 'Other/Special';
        $type->status ='1';
        $type->description = 'Other/Special Leave Type' ;
        $type->save();
        
        $type = new LeaveType();
        $type->name = 'Sick';
        $type->status =1;
        $type->description = 'Sick Leave Type' ;
        $type->save();
        
        $type = new LeaveType();
        $type->name = 'Study';
        $type->status =1;
        $type->description = 'Study Leave Type' ;
        $type->save();
        
        $type = new LeaveType();
        $type->name = 'Unpaid';
        $type->status =1;
        $type->description = 'Unpaid Leave Type' ;
        $type->save();
        
        $type = new LeaveType();
        $type->name = 'Special Leave 2';
        $type->status =1;
        $type->description = 'Special Leave Type' ;
        $type->save();

        //#insert leave profiles
        $profile = new leave_profile();
        $profile->name = 'Employee with no leave';
        $profile->description = 'Employee with no leave' ;
        $profile->save();

        $profile = new leave_profile();
        $profile->name = '5 Day Employee';
        $profile->description = '5 Day Employee leave' ;
        $profile->save();

        $profile = new leave_profile();
        $profile->name = '6 Day Employee';
        $profile->description = '6 Day Employee leave' ;
        $profile->save();

        $profile = new leave_profile();
        $profile->name = 'Shift Worker';
        $profile->description = 'Shift Worker Employee leave' ;
        $profile->save();

    }
}
