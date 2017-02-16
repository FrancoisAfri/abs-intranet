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
//Route::get('statements', 'DashboardController@printStatement');
//Route::post('statements/{loan}', 'DashboardController@printStatement');
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

//Loans related requests
/*
Route::get('loan/create', 'LoanApplicationController@create');
Route::get('loan/view/{loan}', 'LoanApplicationController@loan_view');
Route::patch('loan/{loan}', 'LoanApplicationController@update');
Route::get('loan/edit/{loan}', 'LoanApplicationController@edit');
Route::get('loan/search', 'LoanApplicationController@search');
Route::get('loan/setup', 'LoanApplicationController@setup');
Route::get('loan/{loan}/summary', 'LoanApplicationController@loanSummary');
Route::post('loan/setup/primerate', 'LoanApplicationController@addPrimeRate');
Route::post('loan_save', 'LoanApplicationController@store');
Route::post('loan/search_results', 'LoanApplicationController@searchResult');
Route::post('loan/{loan}/approve', 'LoanApplicationController@approveLoanApplication');
Route::post('loan/{loan}/reject', 'LoanApplicationController@rejectLoanApplication');
Route::post('loan/{loan}/capture_payment', 'LoanApplicationController@capturePayment');
Route::get('statement/{loan}', 'LoanApplicationController@printStatement');
*/
//Contacts related requests
Route::get('contacts', 'ContactsController@index');
//Route::get('contacts/contact', 'ContactsController@addContact');
Route::get('contacts/public', 'PublicRegistrationController@create');
Route::post('add_public_registration', 'PublicRegistrationController@store');
Route::get('contacts/public/{public}/edit', 'PublicRegistrationController@edit');
Route::patch('public/{public}', 'PublicRegistrationController@update');
Route::get('contacts/educator', 'EducatorsController@create');
Route::get('contacts/educator/extras/{project}/{activity}', ['uses' => 'EducatorsController@create', 'as' => 'educatorregistration']);
Route::post('add_educator', 'EducatorsController@store');
Route::get('contacts/educator/{educator}/edit', 'EducatorsController@edit');
Route::patch('educators/{educator}', 'EducatorsController@update');
Route::get('contacts/learner', 'LearnerRegistrationController@create');
Route::get('contacts/learner/extras/{project}/{activity}', ['uses' => 'LearnerRegistrationController@create', 'as' => 'learnerregistration']);
Route::post('add_learner', 'LearnerRegistrationController@store');
Route::get('contacts/learner/{learner}/edit', 'LearnerRegistrationController@edit');
Route::patch('learners/{learner}', 'LearnerRegistrationController@update');
Route::get('contacts/profile', 'ContactsController@profile');
Route::get('contacts/create', 'ContactsController@create');
Route::post('contacts/email', 'ContactsController@emailAdmin');
Route::get('contacts/{contact}/edit', 'ContactsController@edit');
Route::post('contacts', 'ContactsController@store');
Route::post('contacts/search', 'ContactsController@getSearch');
Route::get('contacts/general_search', 'ClientSearchController@index');
Route::post('educator/search', 'ClientSearchController@educatorSearch');
Route::post('public_search', 'ClientSearchController@publicSearch');
Route::post('group/search', 'ClientSearchController@groupSearch');
Route::post('learner/search', 'ClientSearchController@LearnerSearch');

Route::post('partners/search_results', 'PartnersSearchController@companySearch');
Route::get('partners/search', 'PartnersSearchController@index');

Route::post('contacts/{user}/pw', 'ContactsController@updatePassword');
Route::patch('contacts/{contact}', 'ContactsController@update');
Route::get('contacts/provider/create', 'ContactCompaniesController@createServiceProvider');
Route::get('contacts/sponsor/create', 'ContactCompaniesController@createSponsor');
Route::get('contacts/school/create', 'ContactCompaniesController@createSchool');
Route::post('contacts/company/create', 'ContactCompaniesController@store');
Route::get('contacts/company/{company}/view', 'ContactCompaniesController@show');
Route::post('contacts/company/{company}/reject', 'ContactCompaniesController@reject');
Route::post('contacts/company/{company}/approve', 'ContactCompaniesController@approve');
Route::get('contacts/company/{company}/edit', 'ContactCompaniesController@edit');
Route::patch('contacts/company/{company}', 'ContactCompaniesController@update');
//AGM
Route::get('contacts/agm', 'AGMContactsController@create');
Route::post('contacts/agm/store', 'AGMContactsController@store');

//Clients (contacts) registration
Route::post('contacts/register', 'ContactsRegisterController@register');
Route::post('users/recoverpw', 'ContactsRegisterController@recoverPassword');

//Education related requests
Route::get('education/attendance', 'AttendanceRegisterController@index');
Route::post('education/get_attendance', 'AttendanceRegisterController@searchResults');
Route::post('education/mark_attendance', 'AttendanceRegisterController@store');
Route::get('education/programme/create', 'ProgrammesController@create');
Route::get('education/project/create', 'projectController@create');
Route::get('education/activity/create', 'ActivitiesController@create');
Route::get('education/nsw', 'NSWController@create');
Route::post('education/nsw_save', 'NSWController@store');
Route::get('education/nsw/{group}/edit', 'NSWController@edit');
Route::patch('education/group/{group}', 'NSWController@update');
Route::patch('education/grade_edit/{grade}', 'NSWController@updateGraderade');
Route::post('education/programme', 'ProgrammesController@store');
Route::post('education/programme/{programme}/reject', 'ProgrammesController@reject');
Route::post('education/programme/{programme}/approve', 'ProgrammesController@approve');
Route::post('education/programme/{programme}/complete', 'ProgrammesController@complete');
Route::get('education/programme/{programme}/view', 'ProgrammesController@show');
Route::post('education/activity', 'ActivitiesController@store');
Route::get('education/activity/{activity}/view', 'ActivitiesController@show');
Route::post('education/activity/{activity}/reject', 'ActivitiesController@reject');
Route::post('education/activity/{activity}/approve', 'ActivitiesController@approve');
Route::post('education/activity/{activity}/complete', 'ActivitiesController@complete');
Route::post('education/project', 'projectController@store');
Route::get('project/view/{project}', 'projectController@projectView');
Route::post('project/{project}/approve', 'projectController@approveProject');
Route::post('education/project/{project}/complete', 'projectController@complete');
Route::post('project/{project}/reject', 'projectController@rejectProject');
Route::get('education/search', 'educationSearchController@index');
Route::post('programme/search', 'educationSearchController@programmeSearch');
Route::get('programme/search/{status}', 'educationSearchController@programmestatusSearch');
Route::post('project/search', 'educationSearchController@projectsSearch');
Route::post('activity/search', 'educationSearchController@activitySearch');
Route::get('activity/search/{status}', 'educationSearchController@ActivitystatusSearch');
Route::get('project/search/{status}', 'educationSearchController@projectstatusSearch');
Route::get('education/registration', 'EnrolmentController@create');
Route::post('education/registration', 'EnrolmentController@store');
Route::get('education/loadclients', 'ResultsController@searchRegistrations');
Route::post('education/loadclients', 'ResultsController@getRegistrations');
Route::post('education/project/{projects}/addexpenditure', 'projectController@addExpenditure');
Route::post('education/programme/{programme}/addexpenditure', 'ProgrammesController@saveExpenditure');
Route::post('education/programme/{programme}/addincome', 'ProgrammesController@saveIncome');
Route::post('education/project/{projects}/addincome', 'projectController@saveIncome');
Route::post('education/project/{projects}/addexpenditure', 'projectController@saveExpenditure');
Route::post('education/activity/{activity}/addexpenditure', 'ActivitiesController@saveExpenditure');
Route::post('education/activity/{activity}/addincome', 'ActivitiesController@saveIncome');


// Reports
Route::get('reports/programme', 'ProgrammeReportsController@index');
Route::post('reports/programme', 'ProgrammeReportsController@programmeReports');
Route::post('reports/project', 'ProgrammeReportsController@projectsReports');
Route::post('reports/activity', 'ProgrammeReportsController@activityReports');
Route::post('reports/programme/print', 'ProgrammeReportsController@printProgramme');
Route::post('reports/projects/print', 'ProgrammeReportsController@printProjects');
Route::post('reports/activity/print', 'ProgrammeReportsController@printActivity');
Route::get('reports/learner', 'LeanersReportsController@index');
Route::post('reports/learners', 'LeanersReportsController@getReport');
Route::post('reports/learners/print', 'LeanersReportsController@printreport');
Route::get('reports/educator', 'EducatorsReportsController@index');
Route::post('reports/educators', 'EducatorsReportsController@getReport');
Route::post('reports/educators/print', 'EducatorsReportsController@printreport');
Route::get('reports/finance', 'FinanceReportsController@index');
Route::get('reports/registration', 'RegistrationReportsController@index');
//Route::post('reports/finances', 'FinanceReportsController@getReport');
//Route::post('reports/finances/print', 'FinanceReportsController@printreport');
Route::post('reports/programme/finance', 'FinanceReportsController@programmeReports');
Route::get('reports/audit', 'AuditReportsController@index');
Route::post('reports/audits', 'AuditReportsController@getReport');
Route::post('reports/audits/print', 'AuditReportsController@printreport');
Route::get('reports/registration', 'RegistrationReportsController@index');
Route::post('reports/registration', 'RegistrationReportsController@registrationReport');
Route::post('reports/registration/print/{print}', 'RegistrationReportsController@registrationReport');

//General Use (API)
Route::post('api/projectsdropdown', 'EnrolmentController@projectDD')->name('projectsdropdown');
Route::post('api/activitydropdown', 'ActivitiesController@activityDD')->name('activitydropdown');
//Route::post('api/projectsdropdown', 'LearnerRegistrationController@projectsDropdown')->name('learnerregistration');
Route::post('api/regyeardropdown', 'EnrolmentController@yearDD')->name('regyeardropdown');

//Email Test
Route::get('testemail', function () {
    //Mail::to('smalto22@gmail.com')->send(new UserCreated);
    $client = \App\User::find("3")->load('person');
    $loan = \App\Loan::first();
    //Mail::to("smalto22@gmail.com")->send(new \App\Mail\RejectedLoanApplication($client, 'Some reason.', 1));
    Mail::to("smalto22@gmail.com")->send(new \App\Mail\ApprovedLoanApplication($client, $loan));
    return back();
});