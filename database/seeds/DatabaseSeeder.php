<?php

use Illuminate\Database\Seeder;
use App\User;
use App\HRPerson;
use App\Country;
use App\Province;
use App\modules;
use App\module_ribbons;

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
        $user->password = Hash::make('123456');
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

        //insert default user
        $user = new User;
        $user->email = 'thato@afrixcel.co.za';
        $user->password = Hash::make('123456');
        $user->type = 3;
        $user->status = 1;
        $user->save();

        //insert default user's hr record
        $person = new HRPerson();
        $person->first_name = 'Thato';
        $person->surname = 'Lechuti';
        $person->email = 'thato@afrixcel.co.za';
        $person->status = 1;
        $user->addPerson($person);

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

        //insert some positions
        DB::table('hr_positions')->insert([
            'name' => 'General Manager',
            'status' => 1,
        ]);
        DB::table('hr_positions')->insert([
            'name' => 'Administrator',
            'status' => 1,
        ]);
        DB::table('hr_positions')->insert([
            'name' => 'Programme Manager',
            'status' => 1,
        ]);
        DB::table('hr_positions')->insert([
            'name' => 'Education and Learning Manager',
            'status' => 1,
        ]);
        DB::table('hr_positions')->insert([
            'name' => 'Project Manager',
            'status' => 1,
        ]);
        DB::table('hr_positions')->insert([
            'name' => 'Facilitator',
            'status' => 1,
        ]);

        //Insert navigation menus
        $module = new modules(); //Contacts
        $module->active = 1;
        $module->name = 'Contacts';
        $module->path = 'contacts';
        $module->font_awesome = 'fa-users';
        $module->save();
/*
        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'AGM';
        $ribbon->description = 'AGM';
        $ribbon->ribbon_path = 'contacts/agm';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Educator Registration';
        $ribbon->description = 'Add Educator';
        $ribbon->ribbon_path = 'contacts/educator';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Public Registration';
        $ribbon->description = 'Add Public';
        $ribbon->ribbon_path = 'contacts/public';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Learner Registration';
        $ribbon->description = 'Add Learner';
        $ribbon->ribbon_path = 'contacts/learner';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 5;
        $ribbon->ribbon_name = 'Add New NSW & STX';
        $ribbon->description = 'NSW & STX';
        $ribbon->ribbon_path = 'education/nsw';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $module = new modules();
        $module->active = 1;
        $module->name = 'Education Programmes';
        $module->path = 'education';
        $module->font_awesome = 'fa-graduation-cap';
        $module->save();

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Add New Programme';
        $ribbon->description = 'Add Programme';
        $ribbon->ribbon_path = 'education/programme/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Add New Project';
        $ribbon->description = 'Add Project';
        $ribbon->ribbon_path = 'education/project/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 3;
        $ribbon->ribbon_name = 'Add New Activity';
        $ribbon->description = 'Add Activity';
        $ribbon->ribbon_path = 'education/activity/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 4;
        $ribbon->ribbon_name = 'Search';
        $ribbon->description = 'Search';
        $ribbon->ribbon_path = 'education/search';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
*/
        $module = new modules();
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
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'hr/setup';
        $ribbon->access_level = 5;
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
        $ribbon->ribbon_name = 'Setup';
        $ribbon->description = 'Setup';
        $ribbon->ribbon_path = 'leave/setup';
        $ribbon->access_level = 5;
        $module->addRibbon($ribbon);
/*
        $module = new modules();
        $module->active = 1;
        $module->name = 'Partners';
        $module->path = 'contacts';
        $module->font_awesome = 'fa-group';
        $module->save();

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 1;
        $ribbon->ribbon_name = 'Add New School';
        $ribbon->description = 'Add School';
        $ribbon->ribbon_path = 'contacts/school/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);

        $ribbon = new module_ribbons();
        $ribbon->active = 1;
        $ribbon->sort_order = 2;
        $ribbon->ribbon_name = 'Add New Provider';
        $ribbon->description = 'Add Service Provider';
        $ribbon->ribbon_path = 'contacts/provider/create';
        $ribbon->access_level = 3;
        $module->addRibbon($ribbon);
*/
    }
}
