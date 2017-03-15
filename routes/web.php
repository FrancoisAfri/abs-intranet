<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
/*
Route::get('/', function () {
    return view('main_layout');
});
//Route::get('login', 'PagesController@login');
//Route::get('/home', 'HomeController@index');
*/

Route::get('/', 'DashboardController@index');
Route::get('test', 'PagesController@testPage');

Auth::routes();

//Users related requests
Route::get('users', 'UsersController@index');
Route::get('users/modules', 'UsersController@viewModules');
Route::get('users/create', 'UsersController@create');
Route::get('users/{user}/edit', 'UsersController@edit');
Route::get('users/profile', 'UsersController@profile');
Route::post('users', 'UsersController@store');
Route::post('users/search', 'UsersController@getSearch');
Route::post('users/{user}/pw', 'UsersController@updatePassword');
Route::post('users/{user}/upw', 'UsersController@updateUserPassword');
Route::patch('users/{user}', 'UsersController@update');
Route::get('users/setup', 'UsersController@setup');
Route::post('users/setup/modules', 'UsersController@addmodules');
Route::post('users/setup/add_ribbon/{mod}', 'UsersController@addribbon');
Route::get('/users/ribbons/{mod}', 'UsersController@ribbonView');
Route::patch('/users/module_edit/{mod}', 'UsersController@editModule');

Route::patch('/ribbon/{ribbon}', 'UsersController@editRibbon');
Route::get('/users/module_active/{mod}', 'UsersController@moduleAct');
Route::get('/users/module_access/{user}', 'UsersController@moduleAccess');
Route::get('/users/ribbon_active/{rib}', 'UsersController@ribbonAct');
Route::post('/users/access_save/{user}', 'UsersController@accessSave');

#Leave Management
Route::get('leave/types', 'LeaveController@types');
Route::post('leave/type/add_leave', 'LeaveController@addleave');
Route::patch('/leave/leave_type_edit/{lev}', 'LeaveController@editLeaveType');
Route::get('/leave/leave_active/{lev}', 'LeaveController@leaveAct');
#custom leave 
Route::post('leave/custom/add_leave', 'LeaveController@addcustom');
Route::get('/leave/custom/leave_type_edit/{lev}', 'LeaveController@customleaveAct');
Route::post('/leave/custom/leave_type_edit/{lev}', 'LeaveController@editcustomLeaveType');


//Contacts related requests
Route::get('contacts', 'ContactsController@index');
//Route::get('contacts/contact', 'ContactsController@addContact');
Route::get('contacts/public', 'PublicRegistrationController@create');
//Route::post('add_public_registration', 'PublicRegistrationController@store');
//Route::get('contacts/public/{public}/edit', 'PublicRegistrationController@edit');
//Route::patch('public/{public}', 'PublicRegistrationController@update');
//Route::get('contacts/educator', 'EducatorsController@create');
//Route::get('contacts/educator/extras/{project}/{activity}', ['uses' => 'EducatorsController@create', 'as' => 'educatorregistration']);
//Route::post('add_educator', 'EducatorsController@store');
//Route::get('contacts/educator/{educator}/edit', 'EducatorsController@edit');
//Route::patch('educators/{educator}', 'EducatorsController@update');
//Route::get('contacts/learner', 'LearnerRegistrationController@create');
//Route::get('contacts/learner/extras/{project}/{activity}', ['uses' => 'LearnerRegistrationController@create', 'as' => 'learnerregistration']);
//Route::post('add_learner', 'LearnerRegistrationController@store');
//Route::get('contacts/learner/{learner}/edit', 'LearnerRegistrationController@edit');
//Route::patch('learners/{learner}', 'LearnerRegistrationController@update');
//Route::get('contacts/profile', 'ContactsController@profile');
//Route::get('contacts/create', 'ContactsController@create');
//Route::post('contacts/email', 'ContactsController@emailAdmin');
Route::get('contacts/{contact}/edit', 'ContactsController@edit');
Route::post('contacts', 'ContactsController@store');
Route::post('contacts/search', 'ContactsController@getSearch');
Route::get('contacts/general_search', 'ClientSearchController@index');
//Route::post('educator/search', 'ClientSearchController@educatorSearch');
//Route::post('public_search', 'ClientSearchController@publicSearch');
//Route::post('group/search', 'ClientSearchController@groupSearch');
//Route::post('learner/search', 'ClientSearchController@LearnerSearch');

//Route::post('partners/search_results', 'PartnersSearchController@companySearch');
//Route::get('partners/search', 'PartnersSearchController@index');

Route::post('contacts/{user}/pw', 'ContactsController@updatePassword');
Route::patch('contacts/{contact}', 'ContactsController@update');
//Route::get('contacts/provider/create', 'ContactCompaniesController@createServiceProvider');
//Route::get('contacts/sponsor/create', 'ContactCompaniesController@createSponsor');
//Route::get('contacts/school/create', 'ContactCompaniesController@createSchool');
Route::post('contacts/company/create', 'ContactCompaniesController@store');
Route::get('contacts/company/{company}/view', 'ContactCompaniesController@show');
Route::post('contacts/company/{company}/reject', 'ContactCompaniesController@reject');
Route::post('contacts/company/{company}/approve', 'ContactCompaniesController@approve');
Route::get('contacts/company/{company}/edit', 'ContactCompaniesController@edit');
Route::patch('contacts/company/{company}', 'ContactCompaniesController@update');
//AGM
//Route::get('contacts/agm', 'AGMContactsController@create');
//Route::post('contacts/agm/store', 'AGMContactsController@store');
# Employee Records Module
Route::get('hr/job_title', 'EmployeeJobTitleController@index');
Route::post('hr/categories', 'EmployeeJobTitleController@categorySave');
Route::patch('hr/category_edit/{jobCategory}', 'EmployeeJobTitleController@editCategory');
Route::get('hr/jobtitles/{jobCategory}', 'EmployeeJobTitleController@jobView');
Route::get('/hr/category_active/{jobCategory}', 'EmployeeJobTitleController@categoryAct');
Route::get('/hr/job_title_active/{jobTitle}', 'EmployeeJobTitleController@jobtitleAct');
Route::post('hr/add_jobtitle/{jobCategory}', 'EmployeeJobTitleController@addJobTitle');
Route::patch('job_title/{jobTitle}', 'EmployeeJobTitleController@editJobTitle');
Route::get('/hr/setup', 'HrController@showSetup');
Route::patch('/hr/grouplevel/{groupLevel}', 'HrController@updateGroupLevel');
Route::get('/hr/grouplevel/activate/{groupLevel}', 'HrController@activateGroupLevel');
# Audit Module
Route::get('audit/reports', 'AuditReportsController@index');
Route::post('audits', 'AuditReportsController@getReport');
Route::post('audits/print', 'AuditReportsController@printreport');

# Performance Appraisals Module
Route::get('appraisal/templates', 'AppraisalTemplatesController@viewTemlates');
Route::post('appraisal/template', 'AppraisalTemplatesController@temlateSave');
Route::patch('appraisal/template_edit/{template}', 'AppraisalTemplatesController@editTemplate');
Route::get('/appraisal/template_active/{template}', 'AppraisalTemplatesController@templateAct');
Route::get('appraisal/template/{template}', 'AppraisalTemplatesController@viewTemlate');
Route::get('appraisal/categories', 'AppraisalsCategoriesController@viewCategories');
Route::post('appraisal/category', 'AppraisalsCategoriesController@categorySave');
Route::patch('appraisal/cat_edit/{category}', 'AppraisalsCategoriesController@editCategory');
Route::get('/appraisal/cat_active/{category}', 'AppraisalsCategoriesController@categoryAct');
Route::get('appraisal/kpa/{category}', 'AppraisalsCategoriesController@viewKpas');
Route::get('appraisal/perks', 'AppraisalPerksController@index');

# Company setup Module
Route::get('/hr/company_setup', 'EmployeeCompanySetupController@viewLevel');
Route::patch('/hr/firstlevel/{firstLevel}', 'EmployeeCompanySetupController@UpdateLevel');
Route::get('/hr/firstlevel/activate/{firstLevel}', 'EmployeeCompanySetupController@activateFirstLevel');




//Route::post('audits', 'AuditReportsController@getReport');
//Route::post('audits/print', 'AuditReportsController@printreport');
//Clients (contacts) registration
//Route::post('contacts/register', 'ContactsRegisterController@register');
Route::post('users/recoverpw', 'ContactsRegisterController@recoverPassword');

//General Use (API)
Route::post('api/divisionsdropdown', 'DropDownAPIController@divLevelGroupDD')->name('divisionsdropdown');
Route::post('api/hrpeopledropdown', 'DropDownAPIController@hrPeopleDD')->name('hrpeopledropdown');

//Email Test
Route::get('testemail', function () {
    //Mail::to('smalto@afrixcel.co.za')->send(new UserCreated);
    $client = \App\User::find("3")->load('person');
    $loan = \App\Loan::first();
    //Mail::to("smalto@afrixcel.co.za")->send(new \App\Mail\RejectedLoanApplication($client, 'Some reason.', 1));
    Mail::to("smalto@afrixcel.co.za")->send(new \App\Mail\ApprovedLoanApplication($client, $loan));
    return back();
});