<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/home', function () {
    return Redirect::action('DashboardController@index');
});

Auth::routes();

Route::get('view/{id}', 'CmsController@view');
Route::get('viewceo/{viewceo}', 'CmsController@viewceo');

#cms ratings
Route::get('rate/{id}/{cmsID}', 'CmsController@cmsratings');
// General Information
Route::get('general_information/view', 'CmsController@generalInformations');

//Users related requests
Route::get('users', 'UsersController@index');
//Route::get('users/modules', 'UsersController@viewModules');
Route::get('users/create', 'UsersController@create');
Route::get('users/{user}/edit', 'UsersController@edit');
Route::get('users/profile', 'UsersController@profile');
Route::post('users', 'UsersController@store');
Route::post('users/search', 'UsersController@getSearch');
Route::post('users/search/activate', 'UsersController@activateUsers');
Route::post('users/{user}/pw', 'UsersController@updatePassword');
Route::post('users/{user}/upw', 'UsersController@updateUserPassword');
Route::patch('users/{user}', 'UsersController@update');
Route::get('users/modules', 'UsersController@modules');
Route::get('users/public-holiday', 'UsersController@publicHoliday');
Route::get('users/setup', 'UsersController@companySetup');
Route::post('users/public-holiday/delete/{holidayId}', 'UsersController@deleteHoliday');
Route::post('users/public-holiday', 'UsersController@saveHoliday');
Route::patch('/users/holiday_edit/{holiday}', 'UsersController@updateHoliday');
Route::post('users/setup/modules', 'UsersController@addmodules');
Route::post('users/setup/add_ribbon/{mod}', 'UsersController@addribbon');
Route::get('/users/ribbons/{mod}', 'UsersController@ribbonView');
Route::patch('/users/module_edit/{mod}', 'UsersController@editModule');

// Reset password
Route::get('password/expired', 'ExpiredPasswordController@expired');
Route::post('password/post_expired/{user}', 'ExpiredPasswordController@postExpired');

Route::get('users/approval', 'UsersController@usersApproval');
Route::post('users/users-approval', 'UsersController@approvalUsers');
Route::get('/users/reports', 'UsersController@reports');
Route::post('/users/get_users_access_report', 'UsersController@getEmployeesReport');
Route::post('/users/get_users_report_print', 'UsersController@getEmployeesReportPrint');
Route::post('/users/get_users_report', 'UsersController@getUsersReport');
Route::post('/users/get_users_print', 'UsersController@getUsersReportPrint');
Route::patch('/ribbon/{ribbon}', 'UsersController@editRibbon');
Route::get('/users/module_active/{mod}', 'UsersController@moduleAct');
Route::get('/users/module_access/{user}', 'UsersController@moduleAccess');
Route::get('/users/ribbon_active/{rib}', 'UsersController@ribbonAct');
Route::get('/user/delete/{user}', 'UsersController@deleteUser');
Route::get('users/users-access', 'SecurityController@usersAccess');
Route::post('users/users-access', 'SecurityController@getEmployees');
Route::post('users/update-users-access', 'SecurityController@updateRights');
Route::post('/users/access_save/{user}', 'UsersController@accessSave');
Route::get('users/reports_to', 'SecurityController@reportTo');
Route::post('/users/get_reports_to', 'SecurityController@getReportsTo');
Route::post('/users/update-report-to', 'SecurityController@updateReportsTo');
Route::get('/security/password-reset', 'SecurityController@resetPassword');
Route::post('/users/get_reset_password', 'SecurityController@getResetPassword');
Route::post('/users/update-reset-password', 'SecurityController@updatePassword');
Route::get('/security/assign-jobtitles', 'SecurityController@assignJobTitle');
Route::post('/security/get_job_titles', 'SecurityController@getJobTitle');
Route::post('/security/update_job_title', 'SecurityController@updateJobTitle');
//#Contacts Management
Route::get('contacts', 'ContactsController@index');
Route::get('contacts/create', 'ContactsController@create');
Route::get('contacts/add-to-company/{companyID}', 'ContactsController@create');
Route::get('contacts/Clients-reports', 'ContactsController@reports');
Route::post('contacts/email', 'ContactsController@emailAdmin');
Route::get('contacts/{person}/edit', 'ContactsController@edit');
Route::get('contacts/{person}/activate', 'ContactsController@activateContact');
Route::get('contacts/{person}/delete', 'ContactsController@deleteContact');
Route::get('contacts/{person}/create-login', 'ContactsController@createLoginDetails');
Route::get('contacts/profile', 'ContactsController@profile');
Route::post('contacts', 'ContactsController@store');
Route::post('contacts/search', 'ContactsController@getSearch');
Route::post('contacts/search/print', 'ContactsController@printSearch');
Route::post('contacts/{user}/pw', 'ContactsController@updatePassword');
//Route::post('contacts/{user}/reset-random-pw', 'ContactsController@resetRandomPassword');
//contacts Documents
Route::get('contacts/{person}/viewdocuments', 'ContactsController@viewdocuments');
Route::post('contacts/add_document', 'ContactsController@addDocumets');
Route::get('contacts/clientdoc_act/{document}', 'ContactsController@clientdocAct');
Route::get('contacts/clientdoc/{document}/delete', 'ContactsController@deleteClientDoc');
Route::patch('contacts/editClientdoc/{document}', 'ContactsController@editClientdoc');

Route::patch('contacts/{contactPerson}', 'ContactsController@update');
Route::get('contacts/send-message', 'ContactsController@sendMessageIndex');
Route::post('contacts/send-message', 'ContactsController@sendMessage');
Route::post('contacts/sms_settings', 'ContactsController@saveSetup');
Route::get('contacts/setup', 'ContactsController@setup');
Route::patch('contacts/update_sms/{smsConfiguration}', 'ContactsController@updateSMS');
//#Company Identity (company details: logo, theme color, etc)
Route::post('security/setup/company_details', 'CompanyIdentityController@saveOrUpdate');

//Business Card
Route::get('hr/user_card', 'BusinessCardsController@userCard');
Route::get('hr/business_card', 'BusinessCardsController@view');
Route::get('hr/active_card', 'BusinessCardsController@cards');
Route::post('hr/search', 'BusinessCardsController@getSearch');
Route::post('hr/print_card', 'BusinessCardsController@busibess_card');
Route::post('/hr/card_active', 'BusinessCardsController@activeCard');
Route::post('hr/emial', 'LeaveController@getEmail');

#policy enforcement system
Route::get('System/policy/create', 'PolicyEnforcementController@create');
Route::get('System/policy/add/{policyCat}', 'PolicyEnforcementController@policyCat');
Route::get('System/policy/view_policies', 'PolicyEnforcementController@viewPolicies');
Route::post('System/policy/add_policy', 'PolicyEnforcementController@createpolicy');
Route::get('System/policy_act/{pol}', 'PolicyEnforcementController@policyAct');
Route::get('System/add_user_act/{policyUser}', 'PolicyEnforcementController@policyUserAct');
Route::get('system/policy/viewUsers/{users}', 'PolicyEnforcementController@viewUsers');
Route::post('System/policy/add_policyUsers', 'PolicyEnforcementController@addpolicyUsers');
Route::post('System/policy/update_status', 'PolicyEnforcementController@updatestatus');
Route::get('System/policy/search_policies', 'PolicyEnforcementController@policySearchindex');
Route::post('System/policy/docsearch', 'PolicyEnforcementController@docsearch');
Route::get('System/policy/reports', 'PolicyEnforcementController@reports');
Route::post('System/policy/reportsearch', 'PolicyEnforcementController@reportsearch');
Route::get('System/policy/view/{policy}', 'PolicyEnforcementController@viewPolicy');
Route::get('System/policy/viewdetails/{policydetails}', 'PolicyEnforcementController@viewdetails');
Route::post('System/policy/viewUsers', 'PolicyEnforcementController@viewpolicyUsers');
Route::patch('System/policy/edit_policy/{policy}', 'PolicyEnforcementController@editPolicy');
Route::get('System/policy/print/{policydetails}', 'PolicyEnforcementController@viewuserprint');
Route::get('policy/category', 'PolicyEnforcementController@viewCategories');
Route::post('policy/category', 'PolicyEnforcementController@categorySave');
Route::patch('policy/cat_edit/{category}', 'PolicyEnforcementController@editCategory');
Route::get('policy/cat_active/{category}', 'PolicyEnforcementController@categoryAct');
Route::get('policy/read-policy-document/{user}', 'PolicyEnforcementController@readPolicy');


Route::get('System/policy/viewuserdetails/{policydetails}', 'PolicyEnforcementController@viewuserdetails');
Route::get('leave/application', 'LeaveApplicationController@index');
Route::post('leave/application/hours', 'LeaveApplicationController@hours');
Route::post('leave/application/day', 'LeaveApplicationController@day');

//# leavesetup Controller
Route::get('leave/types', 'LeaveSetupController@setuptypes');
Route::get('/leave/setup', 'LeaveSetupController@showSetup');
Route::post('/leave/setup/{levg}', 'LeaveSetupController@store');
Route::post('/leave/setup/leave_type_edit/{lev}', 'LeaveSetupController@editsetupType');
Route::get('leave/setup/leave_credit', 'LeaveSetupController@apply');
Route::patch('/leave/setup/{id}', 'LeaveSetupController@addAnnual');
Route::patch('/leave/setup/{id}/sick', 'LeaveSetupController@addSick');

//#Leave Management
Route::post('leave/type/add_leave', 'LeaveController@addleave');
Route::patch('/leave/leave_type_edit/{lev}', 'LeaveController@editLeaveType');
Route::get('/leave/leave_active/{lev}', 'LeaveController@leaveAct');

// Search
Route::get('/leave/search', 'LeaveApplicationController@leaveSearch');
Route::post('leave/admin-cancellation', 'LeaveApplicationController@leaveSearchResults');
Route::get('/leave/view/application/{leave}', 'LeaveApplicationController@viewLeaveApplication');
Route::post('leave/cancellation/{leave}', 'LeaveApplicationController@cancelApplicationAdmin');
//leave Allocation
Route::get('leave/Allocate_leave_types', 'LeaveSetupController@show');
Route::post('leave/Allocate_leave', 'LeaveSetupController@Adjust');
Route::post('leave/Allocate_leave/resert', 'LeaveSetupController@resetLeave');
Route::post('leave/Allocate_leave/add', 'LeaveSetupController@allocate');

//leave Approval
Route::get('leave/approval', 'LeaveApplicationController@show');
Route::post('leave/reject/{levReject}', 'LeaveApplicationController@reject');
Route::get('leave/approval/{leaveId}', 'LeaveApplicationController@AcceptLeave');
//Cancel Leave Application
Route::patch('leave/application/{leaveApplication}/cancel', 'LeaveApplicationController@cancelApplication');

//leaveHistory audit
Route::get('leave/Leave_History_Audit', 'LeaveHistoryAuditController@show');
Route::get('leave/reports', 'LeaveHistoryAuditController@reports');
Route::post('leave/reports/result', 'LeaveHistoryAuditController@getReport');
Route::post('leave/reports/history', 'LeaveHistoryAuditController@getlevhistoryReport');

//leave history report
Route::post('appraisal/reports/result', 'AppraisalReportsController@getReport');
Route::post('appraisal/reports/result/print', 'AppraisalReportsController@printReport');

//Leave Reports
Route::post('leave/reports/taken', 'LeaveHistoryAuditController@taken');
Route::post('leave/reports/leavebal', 'LeaveHistoryAuditController@leavebalance');
Route::post('leave/reports/leavepaOut', 'LeaveHistoryAuditController@leavepaidOut');
Route::post('leave/reports/leaveAll', 'LeaveHistoryAuditController@leaveAllowance');
Route::post('leave/reports/cancelled-leaves', 'LeaveHistoryAuditController@cancelledLeaves');
Route::post('leave/reports/cancelled-leaves/print', 'LeaveHistoryAuditController@cancelledLeavesPrint');
Route::post('leave/print-allowance', 'LeaveHistoryAuditController@leaveAllowancePrint');
Route::post('leave/print', 'LeaveHistoryAuditController@printlevhistoReport');
Route::post('leave/bal', 'LeaveHistoryAuditController@printlevbalReport');
Route::post('leave_taken/print', 'LeaveHistoryAuditController@takenPrint');

//#custom leave
Route::post('leave/custom/add_leave', 'LeaveController@addcustom');
Route::get('/leave/custom/leave_type_edit/{lev}', 'LeaveController@customleaveAct');
Route::post('/leave/custom/leave_type_edit/{lev}', 'LeaveController@editcustomLeaveType');
Route::get('leave/upload', 'LeaveSetupController@upload');
Route::post('leave/leave_upload', 'LeaveSetupController@leaveUpload');
Route::post('/leave/upload/app', 'LeaveSetupController@leaveUploadApplications');
// leave pay out scripts and employee restart
Route::post('leave/upload/reactivation', 'LeaveSetupController@leaveUploadReactivation');
Route::post('/leave/upload/paid', 'LeaveSetupController@leaveUploadPaid');
//Contacts related requests
//Route::get('contacts', 'ContactsController@index');
//Route::get('contacts/contact', 'ContactsController@addContact');
Route::get('contacts/public', 'PublicRegistrationController@create');

Route::get('contacts/general_search', 'ClientSearchController@index');
//Route::post('educator/search', 'ClientSearchController@educatorSearch');
//Route::post('public_search', 'ClientSearchController@publicSearch');
//Route::post('group/search', 'ClientSearchController@groupSearch');
//Route::post('learner/search', 'ClientSearchController@LearnerSearch');
//Route::post('partners/search_results', 'PartnersSearchController@companySearch');
//Route::get('partners/search', 'PartnersSearchController@index');
//Route::get('contacts/provider/create', 'ContactCompaniesController@createServiceProvider');
//Route::get('contacts/sponsor/create', 'ContactCompaniesController@createSponsor');
//Route::get('contacts/school/create', 'ContactCompaniesController@createSchool');
Route::get('contacts/company/create', 'ContactCompaniesController@create');
Route::post('contacts/company', 'ContactCompaniesController@storeCompany');
Route::get('contacts/company/{company}/view', 'ContactCompaniesController@showCompany');
Route::post('contacts/company/{company}/reject', 'ContactCompaniesController@reject');
Route::post('contacts/company/{company}/approve', 'ContactCompaniesController@approve');
Route::get('contacts/company/{company}/edit', 'ContactCompaniesController@editCompany');
Route::get('contacts/company/{company}/actdeact', 'ContactCompaniesController@actCompany');
//Route::get('contacts/company/{company}/notes', 'ContactCompaniesController@notes');
Route::patch('contacts/company/{company}', 'ContactCompaniesController@updateCompany');
Route::post('contacts/company/addnotes', 'ContactCompaniesController@addnote');
Route::patch('contacts/company/updatenotes/{note}', 'ContactCompaniesController@updateNote');
//#CompanyNotes
Route::get('contacts/company/{company}/notes', 'ContactCompaniesController@notes');

//#CompanyDocuments
Route::get('contacts/{company}/viewcompanydocuments', 'ContactCompaniesController@viewdocumets');
Route::post('contacts/add_companydocument', 'ContactCompaniesController@addCompanyDoc');
Route::get('contacts/companydoc/{document}/delete', 'ContactCompaniesController@deleteCompanyDoc');
Route::get('contacts/companydoc_act/{document}', 'ContactCompaniesController@companydocAct');
Route::patch('contacts/edit_companydoc/{company}', 'ContactCompaniesController@editCompanydoc');

//#reports
Route::post('contacts/reports/contact_note', 'ContactCompaniesController@contactnote');
Route::post('contacts/reports/meetings', 'ContactCompaniesController@meetings');
Route::post('contacts/reports/communication', 'ContactCompaniesController@communicationsReport');
Route::post('contacts/reports/documents_expired', 'ContactCompaniesController@expiredDocumentsReport');
Route::get('import/company', 'ContactsUploadController@index');
Route::post('contacts_upload', 'ContactsUploadController@store');
//reports
Route::post('reports/contact_note/meetingreport', 'ContactCompaniesController@printmeetingsReport');
Route::post('reports/contact_note/client_report', 'ContactCompaniesController@printclientReport');
Route::post('reports/contact_com_print', 'ContactCompaniesController@printcommunicationsReport');
Route::post('reports/contact_docs_print', 'ContactCompaniesController@printDocsReport');
Route::post('/crm/add_task/{company}', 'ContactCompaniesController@saveTask');

Route::get('contacts/company_search', 'CompanySearchController@index');
Route::post('contacts/company_search_results', 'CompanySearchController@companySearch');

//AGM
//Route::get('contacts/agm', 'AGMContactsController@create');
//Route::post('contacts/agm/store', 'AGMContactsController@store');
// Employee Records Module
Route::get('hr/Admin', 'Hr_Admin@view');
Route::post('hr/searchemployees', 'Hr_Admin@search_employees');
Route::post('hr/user_active', 'Hr_Admin@activeEmployee');
Route::get('hr/active_user', 'Hr_Admin@cards');

Route::get('hr/upload', 'EmployeeUploadController@index');
Route::get('hr/job_title', 'EmployeeJobTitleController@index');
Route::post('hr/categories', 'EmployeeJobTitleController@categorySave');
Route::patch('hr/category_edit/{jobCategory}', 'EmployeeJobTitleController@editCategory');
Route::get('hr/jobtitles/{jobCategory}', 'EmployeeJobTitleController@jobView');
Route::get('/hr/category_active/{jobCategory}', 'EmployeeJobTitleController@categoryAct');
Route::get('/hr/job_title_active/{jobTitle}', 'EmployeeJobTitleController@jobtitleAct');
Route::post('hr/add_jobtitle/{jobCategory}', 'EmployeeJobTitleController@addJobTitle');
Route::patch('job_title/{jobTitle}', 'EmployeeJobTitleController@editJobTitle');
Route::post('hr/role/add/', 'HrController@addRole');
Route::patch('hr/role/edit/{role}', 'HrController@editRole');
Route::get('/hr/role_users/{user}', 'HrController@assignRole');
Route::get('/hr/role/activate/{role}', 'HrController@roleAct');
Route::post('hr/roles-access/{user}', 'HrController@userRoleSave');
// Audit Module
Route::get('audit/reports', 'AuditReportsController@index');
Route::post('audits', 'AuditReportsController@getReport');
Route::post('audits/print', 'AuditReportsController@printreport');

//PRODUCTS
Route::get('product/Categories', 'Product_categoryController@index');
Route::get('Product/Product/{Category}', 'Product_categoryController@productView');
Route::post('Product/categories', 'Product_categoryController@categorySave');
Route::post('/Product/Product/add/{products}', 'Product_categoryController@addProductType');
Route::patch('Product/product_edit/{product}', 'Product_categoryController@editProduct');
Route::patch('Product/category_edit/{Category}', 'Product_categoryController@editCategory');
Route::get('/Product/category/{Category}', 'Product_categoryController@CategoryAct');
Route::get('/Product/product_act/{Category}', 'Product_categoryController@ProdAct');
Route::get('/Product/productPack_act/{product}', 'Product_categoryController@ProdPackAct');
Route::get('/Product/productpackagesAct/{product}', 'Product_categoryController@productpackagesAct');
Route::get('product/services', 'Product_categoryController@setupIndex');
Route::post('product/services', 'Product_categoryController@setupSave');
//
//----packages ---
Route::get('product/Packages', 'Product_categoryController@view_packages');
Route::post('Product/packages/add', 'Product_categoryController@packageSave');
Route::patch('Product/packages_edit/{package}', 'Product_categoryController@editPackage');

//----Promotions ---
Route::get('product/Promotions', 'Product_categoryController@view_promotions');
Route::post('Product/promotions/add', 'Product_categoryController@promotionSave');
Route::get('product/promotion/end/{promotion}', 'Product_categoryController@endPromotion');

//----price -----
Route::get('product/price', 'Product_categoryController@index');
Route::get('Product/price/{price}', 'Product_categoryController@view_prices');
Route::get('/Product/packages/{package}', 'Product_categoryController@viewProducts');
Route::post('product_packages/product/add/{package}', 'Product_categoryController@product_packageSave');
Route::post('/Product/price/add/{product}', 'Product_categoryController@priceSave');

//search
Route::get('product/Search', 'Product_categoryController@Search');
Route::post('product/product/Search', 'Product_categoryController@productSearch');
Route::post('product/category/Search', 'Product_categoryController@categorySearch');
Route::post('product/package/Search', 'Product_categoryController@packageSearch');
Route::post('product/promotion/Search', 'Product_categoryController@promotionSearch');

//Help Desk
Route::get('helpdesk/setup', 'HelpdeskController@viewsetup');
Route::post('help_desk/system/add', 'HelpdeskController@systemAdd');
Route::patch('help_desk/system/adit/{service}', 'HelpdeskController@editService');
Route::get('help_desk/service/{service}', 'HelpdeskController@view_service');
//--------------------#---------
Route::get('helpdesk/view_ticket', 'HelpdeskController@viewTicket');
Route::get('/helpdesk/helpdeskAct/{desk}', 'HelpdeskController@helpdeskAct');
//
Route::get('/helpdesk/operatorAct/{desk}', 'HelpdeskController@operatorAct');
Route::get('/helpdesk/help_deskAdmin/{desk}', 'HelpdeskController@help_deskAdmin');

//search
Route::get('helpdesk/search', 'HelpdeskController@searhTickets');
Route::post('helpdesk/search_results', 'HelpdeskController@searchResults');

// ------ Assign Tickets -------
Route::get('help_desk/assign_ticket/{ticket}', 'Assign_ticketController@assign_tickets');
Route::post('help_desk/operator/assign/{operatorID}', 'Assign_ticketController@assign_operator');

Route::get('helpdesk/ticket', 'HelpdeskController@createTicket');
Route::post('help_desk/operator/add/{serviceID}', 'HelpdeskController@Addoperator');
Route::patch('help_desk/operator/edit/{serviceID}', 'HelpdeskController@editoperator');
Route::post('help_desk/admin/add/{adminID}', 'HelpdeskController@addAdmin');
Route::patch('help_desk/admin/edit/{adminID}', 'HelpdeskController@editAdmin');
Route::post('help_desk/ticket/add', 'HelpdeskController@addTicket');
Route::post('help_desk/ticket/client', 'HelpdeskController@clientlTicket');

//   ----------------- Help Desk Settings ------------------   //
Route::post('help_desk/setup/{setup}', 'HelpdeskController@setup');
Route::post('help_desk/setup', 'HelpdeskController@setup');
Route::post('help_desk/notify_managers/{service}', 'HelpdeskController@notify_managers');
Route::post('help_desk/notify_managers', 'HelpdeskController@notify_managers');
Route::post('help_desk/auto_escalations/{settings}', 'HelpdeskController@auto_escalations');
Route::post('help_desk/auto_escalations', 'HelpdeskController@auto_escalations');
Route::post('help_desk/unresolved_tickets/{service}', 'HelpdeskController@unresolved_tickets');
Route::post('help_desk/unresolved_tickets', 'HelpdeskController@unresolved_tickets');
Route::post('help_desk/auto_responder_messages', 'HelpdeskController@auto_responder_messages');
Route::post('help_desk/auto_responder_messages/{service}', 'HelpdeskController@auto_responder_messages');
Route::post('help_desk/email_setup', 'HelpdeskController@email_setup');
Route::post('help_desk/email_setup/{service}', 'HelpdeskController@email_setup');

#//************Fleet Card *******************
Route::get('vehicle_management/fleet_cards', 'fleetcardController@index');
Route::post('vehicle_management/fleet_card_search', 'fleetcardController@fleetcardSearch');
Route::post('vehicle_management/add_vehiclefleetcard', 'fleetcardController@Addfleetcard');
Route::patch('vehicle_management/edit_vehiclefleetcard/{vehiclefleetcards}' ,'fleetcardController@editfleetcard');
//Route::patch('vehicle_management/edit_booking/{Vehiclebookings}', 'VehicleBookingController@edit_bookings');
#//************Manage Fuel Tanks *******************
Route::get('vehicle_management/fuel_tank', 'FuelManagementController@fueltankIndex');
Route::post('vehicle_management/addfueltank', 'FuelManagementController@Addfueltank');
Route::get('/vehicle_management/fueltank_act/{fuel}', 'FuelManagementController@FuelTankAct');
//tanktop up
Route::patch('vehicle_management/edit_fueltank/{Fueltanks}' ,'FuelManagementController@editfueltank');
Route::get('/vehicle_management/vehice_tank/{fuel}', 'FuelManagementController@ViewTank');
Route::post('vehicle_management/incoming/{tank}', 'FuelManagementController@incoming');
Route::post('vehicle_management/outgoing/{tank}', 'FuelManagementController@outgoing');
Route::post('vehicle_management/both/{tank}', 'FuelManagementController@both');
Route::post('vehicle_management/tank_topup', 'FuelManagementController@TanktopUp');
//tank private
Route::post('vehicle_management/tank_privateuse', 'FuelManagementController@TankprivateUse'); 

#******************** Tanks Approval *************************
Route::get('vehicle_management/tank_approval', 'FuelManagementController@tank_approval');
Route::post('vehicle_management/tanksearch_approval', 'FuelManagementController@ApproveTank');
Route::post('vehicle_management/otherApproval', 'FuelManagementController@otherApproval');
Route::post('vehicle_management/search', 'FuelManagementController@search');
Route::post('vehicle_management/tankApproval', 'FuelManagementController@tankApproval');
Route::post('vehicle_management/other', 'FuelManagementController@other');
Route::post('vehicle_management/fueltankApproval', 'FuelManagementController@fueltankApproval');
// Route::patch('vehicle_management/reject_vehicle/{reason}','fleetcardController@rejectReason' );                          


#******************** Driver Admin *************************
Route::get('vehicle_management/driver_admin', 'fleetcardController@driverAdmin');
Route::post('vehicle_management/driver_search', 'fleetcardController@driversearch');

#******************** Vehicle Approval *************************
Route::get('vehicle_management/vehicle_history/{fleet}', 'fleetcardController@vehicleHistories');
Route::get('vehicle_management/vehicle_history_print/{fleet}', 'fleetcardController@vehicleHistoriesPrint');
Route::get('vehicle_management/vehicle_approval', 'fleetcardController@vehicle_approval');
Route::post('vehicle_management/vehicleApproval', 'fleetcardController@vehicleApprovals');
Route::patch('vehicle_management/reject_vehicle/{reason}','fleetcardController@rejectReason' );
Route::patch('vehicle_management/reject-single/{fleet}','fleetcardController@vehicleRejectsSingle');
Route::get('vehicle_management/approve-single/{fleet}','fleetcardController@vehicleApprovalsSingle' );
//Route::get('vehicle_management/vehicle_approval', 'fleetcardController@vehicle_approval');

//##----bookings
Route::get('vehicle_management/bookings_search', 'VehicleBookingController@bookingSearch');
Route::post('vehicle_management/bookings_search_results', 'VehicleBookingController@bookingSearchResults');
Route::get('vehicle_management/create_request', 'VehicleBookingController@index');
Route::get('vehicle_management/vehicle_request', 'VehicleBookingController@vehiclerequest');
Route::post('vehicle_management/vehiclesearch', 'VehicleBookingController@VehicleSearch');
Route::get('vehicle_management/bookingdetails/{vehicle}/{required}', 'VehicleBookingController@viewBooking');

Route::post('vehicle_management/vehiclebooking/{vehicle}', 'VehicleBookingController@vehiclebooking');
Route::get('vehicle_management/vehiclebooking_results', 'VehicleBookingController@booking_results');
//cancel booking
 Route::get('vehicle_management/cancel_booking/{booking}', 'VehicleBookingController@cancel_booking');
// edit booking
 Route::patch('vehicle_management/edit_booking/{booking}', 'VehicleBookingController@edit_bookings');
// collect vehicle
 Route::get('/vehicle_management/collect/{collect}', 'VehicleBookingController@collect_vehicle');

 // Return vehicle
 Route::get('/vehicle_management/return_vehicle/{returnVeh}', 'VehicleBookingController@returnVehicle');
 // View Vehicle Appprovals
 Route::get('vehicle_management/approval', 'VehicleBookingController@vewApprovals');
 //Decline vehicle booking
 Route::patch('vehicle_management/decline_booking/{booking}', 'VehicleBookingController@Decline_booking');
  //Approve Vehicle Approval
 Route::get('vehicle_management/approval/{approve}', 'VehicleBookingController@Approve_booking'); 
 // confirm collection
 Route::post('vehicle_management/add_collectiondoc', 'VehicleBookingController@AddcollectionDoc');
 Route::post('vehicle_management/addcollectionImage', 'VehicleBookingController@AddcollectionImage');
 Route::patch('vehicle_management/{confirm}/confirmbooking', 'VehicleBookingController@confrmCollection');
// confirm return
Route::post('vehicle_management/return_document', 'VehicleBookingController@AddreturnDoc');
Route::post('vehicle_management/return_Image', 'VehicleBookingController@AddreturnImage');
Route::patch('vehicle_management/{confirm}/confirmreturn', 'VehicleBookingController@confirmReturn');
// vehicle_ispection
Route::get('vehicle_management/vehicle_ispection/{ispection}', 'VehicleBookingController@viewVehicleIspectionDocs'); 
Route::get('vehicle_management/view_booking/{booking}', 'VehicleBookingController@viewBookingDetails'); 

### fire_extinguishers
Route::get('vehicle_management/fire_extinguishers/{maintenance}', 'FleetManagementController@viewfireExtinguishers'); 
Route::post('vehicle_management/addfireextinguishers', 'FleetManagementController@addvehicleextinguisher');
Route::patch('vehicle_management/changestatus/{extinguishers}', 'FleetManagementController@changeFirestatus');
Route::patch('vehicle_management/editfireexting/{extinguishers}', 'FleetManagementController@editeditfireexting');

##########################  Alerts  #########################
Route::get('vehicle_management/vehicle_alerts', 'vehiclealertController@index');



Route::get('vehicle_management/Manage_fleet_types', 'VehicleManagemntController@index');
Route::post('vehice/add_fleet', 'VehicleManagemntController@Addfleet');
Route::patch('vehice/edit_fleet/{fleet}', 'VehicleManagemntController@editfleet');
Route::get('/vehice/fleet_act/{fleet}', 'VehicleManagemntController@VehicleAct');
Route::post('vehice/Manage_fleet/{fleet}', 'VehicleManagemntController@deletefleet');
// ---
Route::get('vehicle_management/fleet_card', 'VehicleManagemntController@Fleet_Card');
Route::post('vehice/add_fleetcard', 'VehicleManagemntController@AddfleetCards');
Route::patch('vehice/edit_fleetcard/{card}', 'VehicleManagemntController@editfleetcard');
Route::get('/vehice/fleetcard_act/{card}', 'VehicleManagemntController@fleetcardAct');
Route::post('vehice/Manage_fleetcard_types/{card}', 'VehicleManagemntController@deletefleetcard');
// ---
Route::get('vehicle_management/fillingstaion', 'VehicleManagemntController@Fleet_fillingstaion');
Route::post('vehice/add_fillingstation', 'VehicleManagemntController@Addfillingstation');
Route::patch('vehice/edit_station/{station}', 'VehicleManagemntController@editstation');
Route::get('/vehice/station_act/{station}', 'VehicleManagemntController@stationcardAct');
Route::post('vehice/station/{station}', 'VehicleManagemntController@deletestation');
// ---
Route::get('vehicle_management/Permit', 'VehicleManagemntController@Fleet_licencePermit');
Route::post('vehice/add_license', 'VehicleManagemntController@AddlicencePermit');
Route::patch('vehice/edit_license/{permit}', 'VehicleManagemntController@editlicense');
Route::get('/vehice/licence_act/{permit}', 'VehicleManagemntController@licensePermitAct');
Route::post('vehice/license/{permit}', 'VehicleManagemntController@deleteLicensePermit');

// ---
Route::get('vehicle_management/Document_type', 'VehicleManagemntController@Fleet_DocumentType');
Route::post('vehice/add_document', 'VehicleManagemntController@AddDocumentType');
Route::patch('vehice/edit_document/{document}', 'VehicleManagemntController@EditDocumentType');
Route::get('/vehice/document_act/{document}', 'VehicleManagemntController@DocumentTypeAct');
Route::post('vehice/document/{document}', 'VehicleManagemntController@deleteDocument');
//---
Route::get('vehicle_management/Incidents_type', 'VehicleManagemntController@IncidentType');
Route::post('vehice/incident_type', 'VehicleManagemntController@AddIncidentType');
Route::patch('vehice/edit_incident/{incident}', 'VehicleManagemntController@EditIncidentType');
Route::get('/vehice/incident_act/{incident}', 'VehicleManagemntController@incidentTypeAct');
Route::post('vehice/incident/{incident}', 'VehicleManagemntController@deleteIncident');
// ----
Route::get('vehicle_management/vehice_make', 'VehicleManagemntController@vehicemake');
Route::post('vehice/addvehicle_make', 'VehicleManagemntController@AddVehicleMake');
Route::patch('vehice/edit_vehicle_make/{vmake}', 'VehicleManagemntController@editvehiclemake');
Route::get('/vehice/vehiclemake_act/{vmake}', 'VehicleManagemntController@vehiclemakeAct');
Route::post('vehice/vehiclemake/{vmake}', 'VehicleManagemntController@deleteVehiclemake');
// ---
Route::get('vehicle_management/vehice_model/{make}', 'VehicleManagemntController@vehicemodel');
Route::post('vehice/addvehicle_model/{vehiclemake}', 'VehicleManagemntController@AddVehicleModel');
Route::patch('vehice/edit_vehicle_model/{vmodel}', 'VehicleManagemntController@editvehiclemodel');
Route::get('/vehice/vehiclemodle_act/{vmodel}', 'VehicleManagemntController@vehiclemodelAct');
Route::post('vehice/vehiclemodel/{vmodel}', 'VehicleManagemntController@deleteVehiclemodel');
// --- vehicle Search
Route::post('vehicle_management/vehicle/Search', 'VehicleManagemntController@VehicleSearch');
// ---
Route::get('vehicle_management/group_admin', 'VehicleManagemntController@groupAdmin');
Route::post('vehice/groupadmin', 'VehicleManagemntController@Addgroupadmin');
Route::patch('vehice/edit_group/{group}', 'VehicleManagemntController@edit_group');
Route::get('/vehice/group_act/{group}', 'VehicleManagemntController@groupAct');
// ---setup
Route::get('vehicle_management/setup', 'VehicleManagemntController@VehicleSetup');
Route::get('vehicle_management/vehicle_configuration', 'VehicleManagemntController@VehicleConfiguration');
Route::post('vehicle_management/configuration/{configuration}', 'VehicleManagemntController@Configuration');
//#*************** Job card Management ************
Route::get('Jobcard_management/Job_card', 'JobcardManagementController@JobcardManagent');
Route::get('Jobcard_management/addJob_card', 'JobcardManagementController@addJobcard');
Route::post('jobcard_management/add_maintenance', 'JobcardManagementController@Addmaintenance');
////
//**************** SAFE ***********************
Route::get('vehicle_management/safe', 'VehicleManagemntController@safe');
Route::post('vehicle_management/addsafe', 'VehicleManagemntController@Addsafe');
Route::patch('vehicle_management/edit_safe/{safe}', 'VehicleManagemntController@editsafe');
Route::get('vehicle_management/safe_act/{safe}', 'VehicleManagemntController@safeAct');
Route::post('vehicle_management/Manage_safe/{safe}', 'VehicleManagemntController@deletesafe');

//#*************** Fleet Management ************
Route::get('vehicle_management/manage_fleet', 'FleetManagementController@fleetManagent');
Route::get('vehicle_management/add_vehicle', 'FleetManagementController@addvehicle');
//
Route::post('vehicle_management/add_vehicleDetails', 'FleetManagementController@addvehicleDetails');
Route::patch('vehicle_management/change-fleet-status/{vehicle_maintenance}', 'FleetManagementController@changeVehicleStatus');
Route::get('vehicle_management/viewdetails/{maintenance}', 'FleetManagementController@viewDetails');
Route::patch('vehicle_management/edit_vehicleDetails/{vehicle_maintenance}', 'FleetManagementController@editvehicleDetails');

Route::get('/vehicle_management/vehicles_Act/{vehiclemaintenance}', 'FleetManagementController@vehiclesAct');

//******************** post redirects ****************
Route::get('vehicle_management/viewImage/{maintenance}', 'FleetManagementController@viewImage');
Route::get('fleet/add_images/{maintenance}', 'FleetManagementController@uploadImages');
Route::get('vehicle_management/keys/{maintenance}', 'FleetManagementController@keys');
Route::get('vehicle_management/document/{maintenance}', 'VehicleFleetController@document');
Route::get('vehicle_management/contracts/{maintenance}', 'VehicleFleetController@contracts');

Route::get('vehicle_management/oil_log/{maintenance}', 'VehicleFleetController@viewOilLog');

Route::get('vehicle_management/fuel_log/{maintenance}', 'VehicleFleetController@viewFuelLog');
Route::post('vehicle_management/addvehiclefuellog', 'VehicleFleetController@addvehiclefuellog');
Route::patch('vehicle_management/update_fuel_record/{fuel}', 'VehicleFleetController@updateFuelLog');
Route::post('vehice/Manage_fuellog/{fuel}/delete', 'VehicleFleetController@deletefuelLog');
Route::get('vehicle_management/fuel_log/{maintenance}/{date}', 'VehicleFleetController@viewFuelLog');
Route::get('vehicle-management/fuel-log-edit/{fuel}', 'VehicleFleetController@editFuel');
#
Route::get('vehicle_management/bookin_log/{maintenance}', 'VehicleFleetController@viewBookingLog');


Route::get('vehicle_management/service_details/{maintenance}', 'VehicleFleetController@viewServiceDetails');
Route::post('vehicle_management/addservicedetails', 'VehicleFleetController@addServiceDetails');
Route::patch('vehicle_management/edit_servicedetails/{details}', 'VehicleFleetController@editservicedetails');

Route::get('vehicle_management/fines/{maintenance}', 'VehicleFleetController@viewFines');
Route::post('vehicle_management/addvehiclefines', 'VehicleFleetController@addvehiclefines');
Route::patch('vehicle_management/edit_fines/{fines}', 'VehicleFleetController@edit_finesdetails');

Route::get('vehicle_management/incidents/{maintenance}', 'VehicleFleetController@viewIncidents');
Route::post('vehicle_management/addvehicleincidents', 'VehicleFleetController@addvehicleincidents');
Route::patch('vehicle_management/edit_vehicleincidents/{incident}', 'VehicleFleetController@editvehicleincidents');
##fix vehicle
Route::get('vehicle_management/fixvehicle/{vehicle}', 'VehicleFleetController@fixVehicle');


Route::get('vehicle_management/insurance/{maintenance}', 'VehicleFleetController@viewInsurance');
Route::post('vehicle_management/addpolicy', 'VehicleFleetController@addInsurance');
Route::get('vehicle_management/policy_act/{policy}', 'VehicleFleetController@InsuranceAct');
Route::patch('vehicle_management/edit_policy/{policy}', 'VehicleFleetController@editInsurance');

//
Route::get('vehicle_management/warranties/{maintenance}', 'VehicleFleetController@viewWarranties');
Route::post('vehicle_management/Addwarranty', 'VehicleFleetController@addwarranty');
Route::get('vehicle_management/warranty_act/{warranties}', 'VehicleFleetController@warrantyAct');
Route::patch('vehicle_management/edit_warrantie/{warranties}', 'VehicleFleetController@editwarranty');

Route::get('vehicle_management/reminders/{maintenance}', 'VehicleFleetController@reminders');
Route::post('vehicle_management/addreminder', 'VehicleFleetController@addreminder');
Route::patch('vehicle_management/edit_reminder/{reminder}', 'VehicleFleetController@editreminder');
Route::get('vehicle_management/reminder_act/{reminder}', 'VehicleFleetController@reminderAct');
Route::get('vehicle_management/reminder/{reminder}/delete', 'VehicleFleetController@deletereminder');

Route::post('vehicle_management/add_new_document', 'FleetManagementController@newdocument');
Route::post('vehicle_management/document/delete/{document}', 'FleetManagementController@deleteDoc');
Route::patch('vehicle_management/edit_vehicledoc/{vehicledocumets}', 'FleetManagementController@editVehicleDoc');

Route::get('vehicle_management/vehicledoc_act/{documents}', 'FleetManagementController@ActivateDoc');

Route::get('vehicle_management/notes/{maintenance}', 'VehicleFleetController@viewnotes');
Route::post('vehicle_management/add_new_note', 'FleetManagementController@newnotes');
Route::patch('vehicle_management/edit_note/{note}', 'FleetManagementController@editNote');
Route::post('vehicle_management/delete_note/{note}', 'FleetManagementController@deleteNote');
//
Route::get('vehicle_management/fleet-communications/{maintenance}', 'VehicleFleetController@viewCommunications');
Route::get('vehicle_management/send-communication/{maintenance}', 'VehicleFleetController@sendMessageIndex');
Route::get('vehicle_management/vehicle_communication_print/{maintenance}', 'VehicleFleetController@communicationsPrint');
Route::post('vehicle_management/send-communicaions/{maintenance}', 'VehicleFleetController@sendCommunication');
//#
Route::get('vehicle_management/general_cost/{maintenance}', 'VehicleFleetController@viewGeneralCost');
Route::post('vehicle_management/addcosts', 'VehicleFleetController@addcosts');
Route::patch('vehicle_management/edit_costs/{costs}', 'VehicleFleetController@editcosts');
Route::get('vehicle_management/Manage_costs/{costs}/delete', 'VehicleFleetController@deletecosts');

Route::get('vehicle_management/permits_licences/{maintenance}', 'FleetManagementController@permits_licences');
Route::post('vehicle_management/addPermit', 'FleetManagementController@addPermit');
Route::patch('vehicle_management/edit_permit/{permit}', 'FleetManagementController@editPermit');

Route::post('vehicle_management/add_images/{maintenance}', 'FleetManagementController@addImages');
Route::post('vehicle_management/add_keys', 'FleetManagementController@addkeys');
Route::patch('vehicle_management/edit_images/{image}', 'FleetManagementController@editImage');

Route::patch('vehicle_management/edit_key/{keytracking}', 'FleetManagementController@editKeys');

//######## serch Docs ################
Route::get('vehicle_management/Search', 'VehicleDocSearchController@index');
Route::post('vehicle_management/doc_search', 'VehicleDocSearchController@doc_search');
Route::post('vehicle_management/image_search', 'VehicleDocSearchController@image_search');

//######## Vehicle Reports ################
Route::get('vehicle_management/vehicle_reports', 'VehicleReportsController@general');
Route::post('vehicle_management/booking_report', 'VehicleReportsController@bookingReports');
Route::post('vehicle_management/fleet_card_report', 'VehicleReportsController@fleetCardReport');
Route::post('fleet/reports/fleet_card/print', 'VehicleReportsController@fleetCardReportPrint');
Route::post('vehicle_management/fuel_report', 'VehicleReportsController@fuelReports');
Route::post('vehicle_management/fine_report', 'VehicleReportsController@vehicleFineDetails');
Route::post('vehicle_management/report_services', 'VehicleReportsController@vehicleServiceDetails');
Route::post('vehicle_management/report_incidents', 'VehicleReportsController@vehicleIncidentsDetails');
Route::post('vehicle_management/report_vehicle_details', 'VehicleReportsController@vehiclesDetails');
Route::post('vehicle_management/report_expiry_documents', 'VehicleReportsController@vehiclesExpiry_documents');
Route::post('vehicle_management/report_external_diesel', 'VehicleReportsController@vehiclesExternaldiesel');
Route::post('vehicle_management/report_internal_diesel', 'VehicleReportsController@vehiclesInternaldiesel');
Route::post('fleet/reports/booking/print', 'VehicleReportsController@bookingReportsPrint');
Route::post('fleet/reports/fuel/print', 'VehicleReportsController@fuelReportPrint');
Route::post('fleet/reports/fine/print', 'VehicleReportsController@fineReportPrint');
Route::post('fleet/reports/Service/print', 'VehicleReportsController@ServiceReportPrint');
Route::post('fleet/reports/incident/print', 'VehicleReportsController@IncidentReportPrint');
Route::post('fleet/reports/details/print', 'VehicleReportsController@DetailsReportPrint');

Route::post('fleet/reports/expdocs/print', 'VehicleReportsController@ExpdocsReportPrint');
Route::post('fleet/reports/expLic/print', 'VehicleReportsController@ExpLicencesReportPrint');
Route::post('fleet/reports/extOil/print', 'VehicleReportsController@ExternalFuelReportPrint');
Route::post('vehicle_management/fire_extinguishers', 'VehicleReportsController@fireExtinguishersReport');
Route::post('fleet/reports/fireExtinguisher/print', 'VehicleReportsController@fireExtinguishersReportPrint');

//Route::post('vehicle_management/vehicle_reports/general', 'VehicleReportsController@general');
Route::post('vehicle_management/vehicle_reports/jobcard', 'VehicleReportsController@jobcard');
Route::get('vehicle/overview', 'VehicleDashboard@index');
  // ***************
Route::post('vehicle_management/vehicle_reports/details', 'VehicleReportsController@generaldetails');
Route::get('vehicle_management/vehicle_reports/viewfinedetails/{vehicleID}', 'VehicleReportsController@vehicleFineDetails');
Route::get('vehicle_management/vehicle_reports/viewbookingdetails/{vehicleID}', 'VehicleReportsController@vehicleBookingDetails');
Route::get('vehicle_management/vehicle_reports/viewfueldetails/{vehicleID}', 'VehicleReportsController@vehicleFuelDetails');
Route::get('vehicle_management/vehicle_reports/viewservicedetails/{vehicleID}', 'VehicleReportsController@vehicleServiceDetails');
Route::get('vehicle_management/vehicle_reports/Incidents_details/{vehicleID}', 'VehicleReportsController@vehicleIncidentsDetails');
Route::get('alerts/print_pdf', 'vehiclealertController@printPdfAlerts');
//Route::get('downloadExcel', 'vehiclealertController@printPdfAlerts');
// Performance Appraisals Module

Route::get('appraisal/setup', 'AppraisalSetupController@index');
Route::post('appraisal/setup', 'AppraisalSetupController@saveOrUpdate');
//Route::post('/appraisal/add', 'AppraisalSetupController@addAppraisal');
//Route::patch('/appraisal/latecomers/{appraisal_setup}', 'AppraisalSetupController@updateAppraisal');
//Route::get('/appraisals/latecomers/{appraisal_setup}/activate', 'AppraisalSetupController@activateAppraisal');
// Performance Appraisals Module

Route::get('appraisal/templates', 'AppraisalTemplatesController@viewTemlates');
Route::post('appraisal/template', 'AppraisalTemplatesController@temlateSave');
Route::patch('appraisal/template_edit/{template}', 'AppraisalTemplatesController@editTemplate');
// Performance Appraisals Module
Route::get('/appraisal/template_active/{template}', 'AppraisalTemplatesController@templateAct');
Route::get('appraisal/template/{template}', 'AppraisalTemplatesController@viewTemlate');

Route::post('appraisal/kpi', 'AppraisalTemplatesController@kpiSave');
Route::patch('appraisal/kpi_edit/{kpi}', 'AppraisalTemplatesController@editKpi');
Route::get('/appraisal/kpi_active/{kpi}', 'AppraisalTemplatesController@kpiAct');

//# Job card settings
Route::get('jobcards/create-job-card', 'JobcardController@create');
Route::get('jobcards/set_up', 'JobcardController@jobcard_settings');
Route::get('jobcards/servicetype', 'JobcardController@servicetype');
Route::post('jobcards/addservicetype', 'JobcardController@addservicetype');
Route::patch('jobcards/edit_servicetype/{service}', 'JobcardController@editservicetype');
Route::get('jobcards/service_act/{service}', 'JobcardController@servicetype_act');
Route::get('jobcards/serveice/{service}', 'JobcardController@deleteservicetype');
Route::get('jobcards/configuration', 'JobcardController@configuration');
Route::post('jobcards/configuration_setings/{config}', 'JobcardController@configurationSetings');
Route::get('jobcards/mycards', 'JobcardController@myjobcards');
Route::get('jobcards/approval_process', 'JobcardController@procesflow');
Route::post('jobcards/add_processflow', 'JobcardController@addprocessflow');
Route::patch('jobcards/edit_step/{steps}', 'JobcardController@editprocessflow');
Route::get('jobcards/process_act/{steps}', 'JobcardController@steps_act');
Route::post('jobcards/addjobcard', 'JobcardController@addjobcardmanagement');
Route::patch('jobcards/updatejobcard/{jobCard}', 'JobcardController@updateJobCard');
Route::get('jobcards/approval', 'JobcardController@jobcardsApprovals');
Route::get('jobcards/search', 'JobcardController@cardsearch');
Route::post('jobcards/jobcardsearch', 'JobcardController@jobcardsearch');
Route::get('jobcards/mechanic-feedback/{card}', 'JobcardController@mechanicFeedback');
Route::get('jobcards/viewcard/{card}', 'JobcardController@viewjobcard');
Route::post('jobcards/appovecards', 'JobcardController@appovecards');
Route::post('jobcard/mecfeedback/{card}', 'JobcardController@mechanicomplete');
Route::post('jobcards/closejobcard/{card}', 'JobcardController@completeJobcard');
Route::post('jobcards/documentjobcard/{card}', 'JobcardController@documnentJobcard');
Route::get('jobcard/next-step/{card}', 'JobcardController@nextStep');
Route::post('jobcards/addjobcardnotes', 'JobcardController@addjobcardnotes');
Route::get('jobcards/jobcardnotes/{card}', 'JobcardController@viewjobcardnotes');
Route::get('jobcard/jobcard_history/{card}', 'JobcardController@jobcardhistory');
Route::get('jobcard/jobcard_print/{card}', 'JobcardController@jobcardHistoriesPrint');
Route::patch('jobcards/instructions-update/{instruction}', 'JobcardController@editInstruction');
Route::get('jobcards/edit_instructions/{instruction}','JobcardController@editjobcardinstructions');
Route::get('jobcard/cancellation/{card}','JobcardController@canceljobcardnotes');
Route::get('jobcards/jobcardimages/{images}','JobcardController@jobcardimages');
Route::post('jobcard/addimages','JobcardController@addcardimages');
Route::patch('jobcard/edit_images/{image}','JobcardController@editImage');
Route::post('jobcards/audits/print','JobcardController@printAudit');
Route::post('jobcards/addcatergory','JobcardController@addpartscatergory');
Route::get('jobcards/addparts/{parts}','JobcardController@viewjobcardparts');
Route::patch('jobcards/edit_partscatagory/{parts}', 'JobcardController@editpartscatagory');
Route::get('jobcards/card_act/{parts}', 'JobcardController@jobcat_act');
Route::post('jobcards/delete_partscatergory/{parts}', 'JobcardController@deletepartscatergory');
Route::post('jobcards/addjobcardparts','JobcardController@addjobcardparts');
Route::get('jobcards/parts_act/{parts}', 'JobcardController@parts_act');
Route::patch('jobcards/edit_cardparts/{parts}', 'JobcardController@editcardparts');
Route::post('jobcards/delete_jobcards/{parts}', 'JobcardController@deletejobcards');
Route::get('jobcard/addparts/{jobcardpart}', 'JobcardController@addparts');
Route::get('jobcard/parts/{jobcardparts}', 'JobcardController@viewparts');
Route::post('jobcard/addjobparts/{jobcardpart}', 'JobcardController@addjobparts');
Route::get('jobcards/print/{print}', 'JobcardController@printcards');
Route::get('jobcards/reports', 'JobcardController@viewreport');
Route::post('jobcards/reports/cards', 'JobcardController@cards');
Route::post('jobcards/printcards', 'JobcardController@printscard');
Route::post('jobcards/reports/printparts', 'JobcardController@printsparts');
Route::post('jobcards/reports/notesprint', 'JobcardController@printnotes');
Route::post('jobcards/reports/parts', 'JobcardController@parts');
Route::post('jobcards/reports/notes', 'JobcardController@notes');
Route::post('jobcard/{jobcard}/delete', 'JobcardController@deleteJobcard');

// stock
Route::get('/stock/approval_level', 'StockApprovalsController@index');
Route::get('/stock/store_management', 'StoreManagement@viewLevel');
Route::post('/stock/firstlevelstock/add/{stockLevel}', 'StoreManagement@addLevel');
Route::patch('/stock/level_edit/{stockLevel}/{childID}', 'StoreManagement@updateLevel');
////
Route::get('/stock/setup', 'StoreManagement@showSetup');
Route::post('stock/settings/{settings}', 'StoreManagement@addSettings');
Route::post('stock_approval/settings', 'StoreManagement@approvalSettings');
Route::post('stock_approval/settings/{settings}', 'StoreManagement@approvalSettings');
Route::post('stock/settings', 'StoreManagement@addSettings');
Route::patch('/stock/grouplevel/{groupLevel}', 'StoreManagement@updateGroupLevel');
Route::get('/stock/grouplevel/activate/{groupLevel}', 'StoreManagement@activateGroupLevel');
/////
Route::get('/stock/child_setup/{parentLevel}/{parent_id}', 'StoreManagement@viewchildLevel');
Route::patch('/stock/firstchild/{parentLevel}/{childID}', 'StoreManagement@updateChild');
Route::post('/stock/firstchild/add/{parentLevel}/{parent_id}', 'StoreManagement@addChild');
Route::get('/stock/store_edit/{parentLevel}/{childID}/activate', 'StoreManagement@activateChild');
////
Route::get('stock/storckmanagement', 'StockController@mystock');
Route::post('stock/stock_search', 'StockController@stock');
Route::get('stock/stock_allocation', 'StockController@takeout');
Route::post('stock/stock_outsearch', 'StockController@stockout');
Route::post('stock/add_stock' ,'StockController@add_stock');
Route::post('stock/takestock' ,'StockController@takestockout');
Route::get('stock/kit_management', 'StockController@kitIndex');
Route::get('stock/reports', 'StockController@viewreports');
Route::post('stock/stock_history/print', 'StockController@printreport');
Route::post('stock/kit/add', 'StockController@kitSave');
Route::patch('stock/kit/update/{kit}', 'StockController@kitUpdate');
Route::get('stock/kit_act/{kit}', 'StockController@kitAct');
Route::get('product/kit/{kit}', 'StockController@viewKitProducts');
Route::get('stock/productkitAct/{product}', 'StockController@kitProductAct');
Route::post('stock/products_kit/add/{kit}', 'StockController@addProductToKit');
Route::patch('stock/product/update/{prod}', 'StockController@updateProductToKit');
Route::get('stock/viewrequest/{stock}', 'StockRequest@viewRequest');
Route::get('stock/viewrequest/{stock}/{back}', 'StockRequest@viewRequest');
Route::get('stock/viewrequest/{stock}/{back}/{app}', 'StockRequest@viewRequest');
Route::post('stock/remove/items/{item}', 'StockRequest@removeItems');
Route::patch('stock/updateitems/{stock}', 'StockRequest@updateRequest');
Route::get('stock/seach_request', 'StockRequest@requestSearch');
Route::post('stock/search_results', 'StockRequest@requestResults');
Route::post('stock/search_report' ,'StockController@searchreport');
// Request & Approvals 
Route::get('stock/request_items', 'StockRequest@create');
Route::get('stock/request_collection', 'StockRequest@collectRequest');
Route::post('stock/addstockrequest', 'StockRequest@store');
Route::get('stock/request_approval', 'StockRequest@requestApprovals');
Route::post('stock/appoverequest', 'StockRequest@appoveRequest');
Route::get('stock/approve-request-single/{stock}', 'StockRequest@appoveRequestSingle');
Route::get('stock/stocklocation/{product}', 'Product_categoryController@stockLocation');
Route::post('stock/stock_loc/add/{product}', 'Product_categoryController@addStockLocation');
Route::patch('stock/stock_loc/update/{stock}', 'Product_categoryController@updateStockLocation');
Route::get('stock/stockinfo/{product}', 'Product_categoryController@stockInfos');
Route::post('stock/stock_info/add/{product}', 'Product_categoryController@addStockInfo');
Route::patch('stock/stock_info/update/{stock}', 'Product_categoryController@updateStockInfo');
Route::post('stock/pre_supplier/add/{product}', 'Product_categoryController@addPreferredSupplier');
Route::patch('stock/pre_supplier/update/{preferred}', 'Product_categoryController@updatePreSupplier');
Route::post('stock/add_step', 'StockApprovalsController@store');
Route::patch('stock/edit_step/update/{step}', 'StockApprovalsController@update');
Route::get('stock/print_delivery_note/{stock}', 'StockRequest@viewDeliveryNotePrint');
Route::post('stock/reject-reason/{stock}', 'StockRequest@rejectRequestSingle');
Route::get('stock/viewcollection/{stock}', 'StockRequest@viewRequestCollection');
Route::post('stock/close-request/{stock}', 'StockRequest@closeRequest');
Route::get('stock/process_act/{step}', 'StockApprovalsController@steps_act');
//    -Kpi Types
Route::get('/appraisal/kpi_range/{kpi}', 'AppraisalKpiTypeController@kpiRange');
Route::post('appraisal/range', 'AppraisalKpiTypeController@kpiAddRange');
Route::patch('appraisal/range_edit/{range}', 'AppraisalKpiTypeController@kpiEditRange');
Route::get('/appraisal/range_active/{range}', 'AppraisalKpiTypeController@rangeAct');

Route::get('/appraisal/kpi_number/{kpi}', 'AppraisalKpiTypeController@kpiNumber');
Route::post('appraisal/number', 'AppraisalKpiTypeController@kpiAddNumber');
Route::patch('appraisal/number_edit/{number}', 'AppraisalKpiTypeController@kpiEditNumber');
Route::get('/appraisal/number_active/{number}', 'AppraisalKpiTypeController@numberAct');

Route::get('appraisal/kpi_from_to/{kpi}', 'AppraisalKpiTypeController@kpiIntegerRange');
Route::post('appraisal/kpi_from_to/{kpi}/add_int_score', 'AppraisalKpiTypeController@kpiAddIntegerScoreRange');
Route::patch('appraisal/kpi_from_to/{score}', 'AppraisalKpiTypeController@kpiEditIntegerScoreRange');
Route::get('appraisal/kpi_from_to/{score}/activate', 'AppraisalKpiTypeController@actIntegerScoreRange');

Route::get('appraisal/categories', 'AppraisalsCategoriesController@viewCategories');
Route::post('appraisal/category', 'AppraisalsCategoriesController@categorySave');
Route::patch('appraisal/cat_edit/{category}', 'AppraisalsCategoriesController@editCategory');
Route::get('/appraisal/cat_active/{category}', 'AppraisalsCategoriesController@categoryAct');
Route::get('appraisal/kpa/{category}', 'AppraisalsCategoriesController@viewKpas');
Route::post('appraisal/add_kpa/{category}', 'AppraisalsCategoriesController@kpasSave');
Route::patch('appraisal/kpas/{kpa}', 'AppraisalsCategoriesController@editKpas');
Route::get('/appraisal/kpa_active/{kpa}', 'AppraisalsCategoriesController@kpasAct');
Route::get('appraisal/perks', 'AppraisalPerksController@index');
Route::post('appraisal/perks/new', 'AppraisalPerksController@store');
Route::patch('appraisal/perks/{perk}', 'AppraisalPerksController@update');
Route::get('appraisal/perks/{perk}/activate', 'AppraisalPerksController@activate');

Route::post('appraisal/add_kpa/{category}', 'AppraisalsCategoriesController@kpasSave');
Route::patch('appraisal/kpas/{kpa}', 'AppraisalsCategoriesController@editKpas');
Route::get('/appraisal/kpa_active/{kpa}', 'AppraisalsCategoriesController@kpasAct');

Route::get('appraisal/load_appraisals', 'AppraisalKPIResultsController@index');
//Route::post('appraisal/load_emp_appraisals', 'AppraisalKPIResultsController@loadEmpAppraisals');
Route::get('appraisal/load/result/{emp}/{month}', 'AppraisalKPIResultsController@loadEmpAppraisals');
Route::post('appraisal/emp/appraisal/save', 'AppraisalKPIResultsController@storeEmpAppraisals');

Route::post('appraisal/load_emp_appraisals', 'AppraisalKPIResultsController@index');

Route::post('appraisal/upload_appraisals', 'AppraisalKPIResultsController@uploadAppraisal');
Route::post('appraisal/kpi_upload', 'AppraisalKPIResultsController@uploadkpi');
// run this for excel composer require maatwebsite/excel
// Appraisal search
///appraisal/' . $emp->id . '/' . $key. '/' .  $year. '/kpas
Route::get('appraisal/search', 'AppraisalSearchController@index');
Route::get('appraisal/{empID}/viewappraisal', 'AppraisalSearchController@viewAppraisals');
Route::get('appraisal/{emp}/{monthYear}/kpas', 'AppraisalSearchController@kpasView');
Route::get('appraisal/{emp}/{kpaID}/{dateUploaded}/kpis', 'AppraisalSearchController@kpisView');
Route::post('appraisal/search_results', 'AppraisalSearchController@searchResults');
Route::get('appraisal/search_results/{empID}/{monthName}', 'AppraisalSearchController@searchResultsWithParameter');
Route::get('appraisal/kpi_view_more/{emp}/{monthYear}/{kpi}', 'AppraisalSearchController@queryReport');

//  Emp appraisal and 360 appraisal
Route::get('appraisal/appraise-yourself', 'AppraisalThreeSixtyController@index');
Route::post('appraisal/appraise-yourself', 'AppraisalThreeSixtyController@storeEmpAppraisals');
Route::get('appraisal/appraise-your-colleague/{empID}', 'AppraisalThreeSixtyController@indexThreeSixty');
Route::post('appraisal/add-three-sixty-people/{empID}', 'AppraisalThreeSixtyController@addEmpToThreeSixty');
Route::get('appraisal/remove-from-three-sixty-people/{empID}/{threeSixtyPersonID}', 'AppraisalThreeSixtyController@removeEmpFromThreeSixty');

//Appraisal reports
Route::get('appraisal/reports', 'AppraisalReportsController@index');
Route::post('appraisal/reports/result', 'AppraisalReportsController@getReport');
Route::post('appraisal/reports/result/print', 'AppraisalReportsController@printReport');

// #Document setup module
 //Route::get('/hr/document', 'DocumentTypeController@viewDoc');
// Route::post('/hr/document/add/doc_type', 'DocumentTypeController@addList');
// Route::get('/hr/document/{listLevel}/activate', 'DocumentTypeController@activateList');
// Route::patch('/hr/document/{doc_type}', 'DocumentTypeController@updateList');
// Route::get('/hr/category', 'DocumentTypeController@viewCategory');
// Route::post('/hr/category/add/doc_type_category', 'DocumentTypeController@addDoc');
// Route::get('/hr/category/{listLevel}/activate', 'DocumentTypeController@activateDoc');
// Route::patch('/hr/category/{doc_type_category}', 'DocumentTypeController@updateDoc');
//Employees Documents Module
Route::get('/hr/emp_document', 'EmployeeDocumentsController@viewDoc');
Route::get('/hr/{user}/edit', 'EmployeeDocumentsController@editUser');
Route::get('/hr/doc_results', 'EmployeeDocumentsController@SearchResults');
// Route::get('/hr/emp_document', 'EmployeeDocumentsController@viewQul');
// Route::post('/hr/emp_document/docs', 'EmployeeDocumentsController@acceptDocs');
//Route::post('/hr/emp_document/docs', 'EmployeeDocumentsController@Searchdoc');

Route::post('/hr/emp_doc/Search', 'EmployeeDocumentsController@Searchdoc');
Route::post('/hr/emp_document/upload_doc', 'EmployeeDocumentsController@uploadDoc');
Route::post('/hr/emp_qual/Search', 'EmployeeDocumentsController@Searchqul');
Route::post('/hr/emp_search/Search', 'EmployeeDocumentsController@SearchEmp');

//Employees Qualifications Module
Route::get('/hr/emp_qualification', 'EmployeeQualificationsController@viewDoc');
Route::post('/hr/emp_qual/Search', 'EmployeeQualificationsController@Searchqul');
Route::post('/hr/upload/{docs}', 'EmployeeQualificationsController@uploadDocs');

//Employees upload
Route::get('/employee_upload', 'EmployeeUploadController@index');
Route::post('/hr/employees_upload', 'EmployeeUploadController@store');

//Employee Search
Route::get('/hr/emp_search', 'EmployeeSearchController@index');
Route::post('/hr/users_search', 'EmployeeSearchController@getSearch');

// Company setup Module
Route::get('/hr/company_setup', 'EmployeeCompanySetupController@viewLevel');
Route::post('/hr/firstleveldiv/add/{divLevel}', 'EmployeeCompanySetupController@addLevel');
Route::patch('/hr/company_edit/{divLevel}/{childID}', 'EmployeeCompanySetupController@updateLevel');
// #Add qualification Type
// Route::post('hr/addqultype', 'EmployeeCompanySetupController@addqualType');
// Route::get('/hr/addqul/{sta}', 'EmployeeCompanySetupController@QualAct');
// Route::post('hr/qul_type_edit/{qul}', 'EmployeeCompanySetupController@editQualType');
//DocType
// Route::post('hr/addDoctype', 'EmployeeCompanySetupController@addDocType');
// Route::get('/hr/adddoc/{sta}', 'EmployeeCompanySetupController@DocAct');
// Route::post('/hr/Doc_type_edit/{doc}', 'EmployeeCompanySetupController@editDocType');
//Route::post('/hr/company_edit/{divLevel}', 'EmployeeCompanySetupController@editlevel');
Route::get('/hr/company_edit/{divLevel}/{childID}/activate', 'EmployeeCompanySetupController@activateLevel');
Route::get('/hr/child_setup/{level}/{parent_id}', 'EmployeeCompanySetupController@viewchildLevel');
Route::patch('/hr/firstchild/{parentLevel}/{childID}', 'EmployeeCompanySetupController@updateChild');
Route::post('/hr/firstchild/add/{parentLevel}/{parent_id}', 'EmployeeCompanySetupController@addChild');
Route::get('/hr/firstchild/{parentLevel}/{childID}/activate', 'EmployeeCompanySetupController@activateChild');
// Induction
Route::get('/induction/create', 'InductionAdminController@index');
Route::get('/induction/search', 'InductionAdminController@search');
Route::get('/induction/{induction}/view', 'InductionAdminController@show');
Route::get('/induction/delete/{induction}', 'InductionAdminController@deleteInduction');
Route::post('/induction/complete', 'InductionAdminController@completeInduction');
Route::get('/induction/tasks_library', 'TaskLibraryController@index');
Route::post('induction/add_library_task', 'TaskLibraryController@store');
Route::post('induction/client_add', 'InductionAdminController@store');
Route::post('induction/search_results', 'InductionAdminController@searchResults');
Route::patch('/induction/tasks_library_edit/{TaskLibrary}', 'TaskLibraryController@update');
Route::get('/induction/library_tasks_activate/{TaskLibrary}', 'TaskLibraryController@actDeact');
Route::get('/task/start/{task}', 'TaskManagementController@startTask');
Route::get('/task/pause/{task}', 'TaskManagementController@pauseTask');
Route::patch('/tasks/update/{task}', 'TaskManagementController@update');
Route::post('/task/end', 'TaskManagementController@endTask');
Route::post('/task/check', 'TaskManagementController@checkTask');
Route::get('/induction/reports', 'InductionAdminController@reports');
Route::post('/induction/reports', 'InductionAdminController@getReport');
Route::post('/induction_tasks/print', 'InductionAdminController@printreport');
Route::get('/cron/induction', 'InductionCronController@execute');
Route::get('induction/tasks_library/{task}/delete', 'InductionAdminController@deleteTask');

// Minutes Meeting
Route::get('/meeting_minutes/recurring', 'RecurringMeetingsController@index');
Route::get('/meeting_minutes/recurring/{recurring}/view', 'RecurringMeetingsController@show');
Route::get('/meeting_minutes/recurring/{recurring}/actdect', 'RecurringMeetingsController@meetingAct');
Route::get('/meeting_recurring/actdeac/{recurring}', 'RecurringMeetingsController@attendeeAct');
Route::post('/meeting/add_recurring_attendees', 'RecurringMeetingsController@saveRecurringAttendee');
Route::post('/meeting_minutes/add_recurring_meeting', 'RecurringMeetingsController@store');
Route::patch('/meeting_minutes/recurring/update/{recurring}', 'RecurringMeetingsController@update');
Route::get('/meeting_minutes/create', 'MeetingMinutesAdminController@index');
Route::post('/meeting/search_results', 'MeetingMinutesAdminController@searchResults');
Route::post('/meeting/add_attendees/{meeting}', 'MeetingMinutesAdminController@saveAttendee');
Route::post('/meeting/add_minutes/{meeting}', 'MeetingMinutesAdminController@saveMinute');
Route::post('/meeting/add_task/{meeting}', 'MeetingMinutesAdminController@saveTask');
Route::post('/meeting_minutes/add_meeting', 'MeetingMinutesAdminController@store');
Route::get('/meeting_minutes/view_meeting/{meeting}/view', 'MeetingMinutesAdminController@show');
Route::get('/meeting_minutes/search', 'MeetingMinutesAdminController@search');
Route::patch('/meeting/update/{meeting}', 'MeetingMinutesAdminController@update');
Route::post('/meeting/update_attendee/{attendee}', 'MeetingMinutesAdminController@updateAttendee');
Route::get('/meeting/prnt_meeting/{meeting}', 'MeetingMinutesAdminController@printMinutes');
Route::get('/meeting/email_meeting/{meeting}', 'MeetingMinutesAdminController@emailMinutes');
// Task Management
Route::get('/tasks/add_task', 'TaskManagementController@addTask');
Route::get('/tasks/search_task', 'TaskManagementController@index');
Route::post('/tasks/add_new_task', 'TaskManagementController@addNewTask');
Route::post('/task/search_results', 'TaskManagementController@searchResults');
Route::get('/tasks/task_report', 'TaskManagementController@report');
Route::post('/task/indtuction_report', 'InductionAdminController@getReport');
Route::post('/task/normal_report', 'TaskManagementController@getReportNormal');
Route::post('/task/meeting_report', 'TaskManagementController@getReport');
Route::post('/task/normal/print', 'TaskManagementController@printNormalReport');
Route::post('/task/meeting/print', 'TaskManagementController@printreport');
//Clients (contacts) registration
//Route::post('contacts/register', 'ContactsRegisterController@register');
//Route::post('users/recoverpw', 'ContactsRegisterController@recoverPassword');
Route::post('users/recoverpw', 'ContactsRegisterController@recoverPassword');

//Route

//Survey (Guest)
Route::get('rate-our-services/{eid}', 'SurveyGuestsController@index');
Route::post('rate-our-services', 'SurveyGuestsController@store');
//Voucher (Guest)
Route::get('vouchers/get-voucher', 'VouchersGuestController@index');
Route::post('vouchers/get-voucher', 'VouchersGuestController@store');
Route::get('vouchers/view/{voucher}', 'VouchersGuestController@voucherPDF');
Route::post('vouchers/email/{voucher}', 'VouchersGuestController@emailVoucher');
Route::post('vouchers/get-car-voucher', 'VouchersGuestController@carVoucher');
Route::get('vouchers/view-car/{voucher}', 'VouchersGuestController@carVvoucherPDF');
Route::post('vouchers/email-car/{voucher}', 'VouchersGuestController@carEmailVoucher');
//Survey
Route::get('survey/reports', 'SurveysController@indexReports');
Route::get('survey/question_activate/{question}', 'SurveysController@actDeact');
Route::get('survey/questions', 'SurveysController@questionsLists');
Route::get('survey/rating-links', 'SurveysController@indexRatingLinks');
Route::post('survey/add_question', 'SurveysController@saveQuestions');
Route::post('survey/reports', 'SurveysController@getReport');
Route::post('survey/reports/print', 'SurveysController@printReport');
Route::patch('/survey/question_update/{question}', 'SurveysController@updateQuestions');

// Company setup Module
Route::get('/hr/setup', 'HrController@showSetup');
Route::patch('/hr/grouplevel/{groupLevel}', 'HrController@updateGroupLevel');
Route::get('/hr/grouplevel/activate/{groupLevel}', 'HrController@activateGroupLevel');
//
Route::post('hr/addqultype', 'HrController@addqualType');
Route::get('/hr/addqul/{sta}', 'HrController@QualAct');
Route::post('hr/qul_type_edit/{qul}', 'HrController@editQualType');
//
Route::get('/hr/document', 'HrController@viewDoc');
Route::post('/hr/document/add/doc_type', 'HrController@addList');
Route::get('/hr/document/{listLevel}/activate', 'HrController@activateList');
Route::patch('/hr/document/{cat_type}', 'HrController@updateList');
Route::get('/hr/category/{category}', 'HrController@viewCategory');
Route::post('/hr/category/add/doc_type_category', 'HrController@addDoc');
Route::get('/hr/category/{listLevel}/activate', 'HrController@activateDoc');
Route::patch('/hr/category/{doc_type_category}', 'HrController@updateDoc');
//
Route::post('hr/addDoctype/{category}', 'HrController@addDocType');
Route::post('/hr/Doc_type_edit/{edit_DocID}', 'HrController@editDocType');
Route::get('/hr/adddoc/{sta}', 'HrController@DocAct');
// /hr/category/' . $type->id
//quote
Route::get('quote/setup', 'QuotesController@setupIndex');
Route::get('quote/configuration', 'QuotesController@setupConfiguration');
Route::post('quote/configuration_setings/{setup}', 'QuotesController@AddQuotesettings');
Route::post('quote/configuration_setings', 'QuotesController@AddQuotesetting');

Route::get('quotes/authorisation', 'QuotesController@authorisationIndex');
Route::post('quote/setup/add-quote-profile', 'QuotesController@saveQuoteProfile');
Route::post('quote/setup/update-quote-profile/{quoteProfile}', 'QuotesController@updateQuoteProfile');
Route::get('quote/create', 'QuotesController@createIndex');
Route::post('quote/adjust', 'QuotesController@adjustQuote');
Route::post('quote/save', 'QuotesController@saveQuote');
Route::post('quote/update/{quote}', 'QuotesController@updateQuote');
Route::get('quote/view/{quotation}/{companyID}', 'QuotesController@viewQuote');
Route::get('quote/search', 'QuotesController@searchQuote');
Route::get('quote/view/{quotation}/pdf', 'QuotesController@viewPDFQuote');
Route::get('quote/approve_quote/{quote}', 'QuotesController@approveQuote');
Route::post('quote/client-approve/{quote}', 'QuotesController@clientApproveQuote');
Route::get('quote/decline_quote/{quote}', 'QuotesController@declineQuote');
Route::post('quote/client-decline-quote/{quote}', 'QuotesController@clientDeclineQuote');
//Route::get('quote/decline_quote/{quote}', 'QuotesController@');
Route::get('quote/modify_quote/{quote}', 'QuotesController@updateQuoteIndex');
Route::post('quote/adjust_modification/{quote}', 'QuotesController@adjustQuoteModification');
Route::post('quote/search', 'QuotesController@searchResults');
Route::get('quote/email_quote/{quote}', 'QuotesController@emailQuote');
Route::get('quote/cancel_quote/{quote}', 'QuotesController@cancelQuote');
Route::post('newquote/save', 'QuotesController@newQuote');

//quotes reports
Route::get('quote/reports', 'QuotesController@Quotereports');
Route::post('quote/searchreports', 'QuotesController@reportsinndex');
Route::post('quote/report-history', 'QuotesController@historyReports');
Route::post('quote-history/reports/print', 'QuotesController@historyReportsPrint');


// Quote term Categories
Route::get('quote/categories-terms', 'QuotesTermConditionsController@index');
Route::get('quote/term-conditions/{cat}', 'QuotesTermConditionsController@viewTerm');
Route::get('quote/term-actdeact/{term}', 'QuotesTermConditionsController@termAct');
Route::get('quote/cat_active/{cat}', 'QuotesTermConditionsController@termCatAct');
Route::get('quote/term-edit/{term}', 'QuotesTermConditionsController@editterm');
Route::post('quote/category', 'QuotesTermConditionsController@saveCat');
Route::post('quote/add-quote-term/{cat}', 'QuotesTermConditionsController@store');
Route::patch('quote/cat_edit/{cat}', 'QuotesTermConditionsController@updateTermCat');
Route::patch('quote/term-update/{term}', 'QuotesTermConditionsController@updateTerm');

//CRM
Route::get('crm/account/{account}', 'CRMAccountController@viewAccount');
Route::get('crm/account/quote/{quote}', 'CRMAccountController@viewAccountFromQuote');
Route::get('crm/setup', 'CRMSetupController@index');
Route::get('crm/search', 'CRMSetupController@search');
Route::get('crm/invoice/view/{quotation}/pdf', 'CRMInvoiceController@viewPDFInvoice');
Route::get('crm/invoice/view/{quotation}/{invoice}/pdf', 'CRMInvoiceController@viewPDFMonthlyInvoice');
Route::get('crm/invoice/mail/{quotation}', 'CRMInvoiceController@emailInvoice');
Route::get('crm/invoice/mail/{quotation}/{invoice}', 'CRMInvoiceController@emailMonthlyInvoice');
Route::post('crm/capture-payment/{quotation}/{invoice}', 'CRMAccountController@capturePayment');
Route::post('crm/accounts/search', 'CRMSetupController@searchResults');
Route::get('crm/search_account', 'CRMSetupController@searchAccount');
Route::post('crm/add_document_type', 'ContactsController@saveDocumentType');
Route::patch('crm/document_type/update/{type}', 'ContactsController@updateDocumentType');
Route::get('crm/document_act/{type}', 'ContactsController@docActivate');

// CRM Reports
Route::get('crm/reports', 'CRMAccountController@crmreportIndex');

// CMS
Route::get('cms/viewnews', 'CmsController@addnews');
Route::post('cms/crm_news', 'CmsController@addcmsnews');
Route::get('cms/viewnews/{news}', 'CmsController@viewnews');
Route::post('cms/updatenews', 'CmsController@updatenews');
Route::get('cms/cmsnews_act/{news}', 'CmsController@newsAct');
Route::get('/cms/news/{news}/delete', 'CmsController@deleteNews');
Route::patch('cms/{news}/update', 'CmsController@updatContent');

// cms ceo news
Route::get('cms/ceo/add_news', 'CmsController@addCeonews');
Route::post('cms/add_ceo_news', 'CmsController@addcmsceonews');
Route::get('cms/ceo_cmsnews_act/{news}', 'CmsController@ceonewsAct');
Route::get('/cms/ceo_news/{news}/delete', 'CmsController@deleteCeoNews');
Route::get('cms/editCeonews/{news}', 'CmsController@editCeoNews');
Route::patch('cms/ceonews/{news}/update', 'CmsController@updatCeonewsContent');

// cms search
Route::get('cms/search', 'CmsController@search');
Route::post('cms/search/CeoNews', 'CmsController@cmsceonews');
Route::post('cms/search/CamponyNews', 'CmsController@CamponyNews');

// cms Reports
Route::get('cms/cms_report', 'CmsController@cms_report');
Route::post('cms/cms_news_ranking', 'CmsController@cms_rankings');
Route::get('cms/cms_newsrankings/{news}', 'CmsController@cms_Star_rankings');

//Email Template
Route::post('email-template/save', 'EmailTemplatesController@saveOrUpdate');

//General Use (API)
Route::get('api/vehiclestatusgraphdata', 'VehicleDashboard@vehicleStatus')->name('vehiclestatus');
Route::post('api/productCategorydropdown', 'DropDownAPIController@productCategoryDDID')->name('pcdropdown');
Route::post('api/jobcategorymodeldropdown', 'DropDownAPIController@jobcategorymomdelDDID')->name('jcmdropdown');
Route::post('api/vehiclemodeldropdown', 'DropDownAPIController@vehiclemomdeDDID')->name('Vmmdropdown');
Route::post('api/divisionsdropdown', 'DropDownAPIController@divLevelGroupDD')->name('divisionsdropdown');
Route::post('api/hrpeopledropdown', 'DropDownAPIController@hrPeopleDD')->name('hrpeopledropdown');
Route::post('api/kpadropdown', 'DropDownAPIController@kpaDD')->name('kpadropdown');
Route::post('api/stockdropdown', 'DropDownAPIController@stockLevelGroupDD')->name('stockdropdown');
Route::get('api/emp/{empID}/monthly-performance', 'AppraisalGraphsController@empMonthlyPerformance');
Route::get('api/divlevel/{divLvl}/group-performance', 'AppraisalGraphsController@divisionsPerformance');
Route::get('api/divlevel/{divLvl}/parentdiv/{parentDivisionID}/group-performance', 'AppraisalGraphsController@divisionsPerformance');
Route::get('api/divlevel/{divLvl}/parentdiv/{parentDivisionID}/manager/{managerID}/group-performance', 'AppraisalGraphsController@divisionsPerformance');
Route::get('api/divlevel/{divLvl}/div/{divID}/emps-performance', 'AppraisalGraphsController@empListPerformance');
Route::get('api/availableperks', 'AppraisalGraphsController@getAvailablePerks')->name('availableperks');
Route::get('api/appraisal/emp/topten/{divLvl}/{divID}', 'AppraisalGraphsController@getTopTenEmployees')->name('toptenemp');
Route::get('api/appraisal/emp/bottomten/{divLvl}/{divID}', 'AppraisalGraphsController@getBottomTenEmployees')->name('bottomtenemp');
Route::get('api/appraisal/staffunder/{managerID}', 'AppraisalGraphsController@getSubordinates')->name('staffperform');
Route::get('api/leave/availableBalance/{hr_id}/{levID}', 'LeaveApplicationController@availableDays');
Route::get('api/leave/availableBalance/{hr_id}/{levID}', 'LeaveApplicationController@availableDays');
Route::get('api/leave/calleavedays/{dateFrom}/{dateTo}', 'LeaveApplicationController@calculatedays');

Route::get('api/tasks/emp/meetingTask/{divLvl}/{divID}', 'EmployeeTasksWidgetController@getMeetingEmployees')->name('meetingTasksEmployee');
Route::get('api/tasks/emp/inductionTask/{divLvl}/{divID}', 'EmployeeTasksWidgetController@getInductionEmployees')->name('inductionTasksEmployee');
Route::get('api/tasks/{task}/duration/{timeInSeconds}', 'TaskTimerController@updateDuration');
Route::get('api/tasks/{task}/get-duration', 'TaskTimerController@getDuration');
Route::post('api/contact-people-dropdown', 'DropDownAPIController@contactPeopleDD')->name('contactsdropdown');
//Test leave cron
Route::get('test/cron', 'AllocateLeavedaysFamilyCronController@sickDays');

// Procurement
Route::get('procurement/approval_level', 'procurementApprovalsController@index');
Route::post('procurement/add_step', 'procurementApprovalsController@store');
Route::get('procurement/process_act/{step}', 'procurementApprovalsController@steps_act');
Route::patch('procument/edit_step/update/{step}', 'procurementApprovalsController@update');
Route::get('procurement/create_request', 'procurementRequestController@index');
Route::get('procurement/create-request', 'procurementRequestController@create');
Route::post('procurement/adjust-request', 'procurementRequestController@adjustProcurement');
Route::post('procurement/save', 'procurementRequestController@saveRequest');
Route::post('procurement/update/{procurement}', 'procurementRequestController@updateQuote');
Route::get('procurement/seach_request', 'procurementRequestController@requestSearch');
Route::get('procurement/viewrequest/{procurement}', 'procurementRequestController@viewRequest');
Route::get('procurement/viewrequest/{procurement}/{back}', 'procurementRequestController@viewRequest');
Route::get('procurement/viewrequest/{procurement}/{back}/{app}', 'procurementRequestController@viewRequest');
Route::post('procurement/search_results', 'procurementRequestController@requestResults');
Route::get('procurement/request_approval', 'procurementRequestController@requestApprovals');
Route::post('procurement/appoverequests', 'procurementRequestController@appoveRequest');
Route::get('procurement/setup', 'procurementRequestController@showSetup');
Route::post('procurement/setups/{setup}', 'procurementRequestController@addSetup');
Route::post('procurement/setups', 'procurementRequestController@addSetup');
Route::get('procuremnt/modify_request/{procurement}', 'procurementRequestController@updateproIndex');
Route::post('procuremnt/adjust_modification/{procurement}', 'procurementRequestController@adjustProcurementEdit');
Route::post('procuremnt/update/{procurement}', 'procurementRequestController@updateProcurement');
Route::post('procuremnt/quote/new/{procurement}', 'procurementRequestController@saveQuote');
Route::get('procurement/print/{procurement}', 'procurementRequestController@printRequest');

Route::get('procurement/viewrequest/{procurement}', 'procurementRequestController@viewRequest');
// Complaints & Compliments
Route::post('complaint/close/{complaint}', 'ComplaintsController@closeComplaint');
Route::get('complaints/queue', 'ComplaintsController@queue');
Route::get('complaints/create', 'ComplaintsController@create');
Route::post('complaints/add', 'ComplaintsController@store');
Route::get('complaints/view/{complaint}', 'ComplaintsController@show');
Route::get('complaints/search', 'ComplaintsController@index');
Route::get('complaint/edit/{complaint}', 'ComplaintsController@edit');
Route::patch('complaint/update/{complaint}', 'ComplaintsController@update');
Route::post('conplaints/search_results', 'ComplaintsController@searchResults');
Route::get('complaints/reports', 'ComplaintsController@reports');
Route::post('conplaints/reports_results', 'ComplaintsController@reportSearchResults');
// Document Management
Route::get('dms/setup', 'DMSSetupController@index');
Route::post('dms/setup/{setup}', 'DMSSetupController@store');
Route::post('dms/setup', 'DMSSetupController@store');
Route::get('dms/folders', 'DMSFoldersController@index');
Route::post('dms/add_folder', 'DMSFoldersController@store');
Route::post('dms/add_sub_folder/{folder}', 'DMSFoldersController@storeSubfolders');
Route::get('dms/folder/view/{folder}', 'DMSFoldersController@subfolders');
Route::post('dms/add_files/{folder}', 'DMSFoldersController@storeFile');
Route::get('dms/folder_management/{folder}', 'DMSFoldersController@manageFolder');
Route::get('dms/file_management/{file}', 'DMSFoldersController@manageFile');
Route::get('dms/folder/{folder}/delete', 'DMSFoldersController@destroy');
Route::patch('dms/edit_folder_details/{folder}', 'DMSFoldersController@update');
Route::get('dms/folder/company_access/{folder}', 'DMSFoldersController@companyFolderAccess');
Route::get('dms/folder/group_access/{folder}', 'DMSFoldersController@groupFolderAccess');
Route::get('dms/file/user_access/{folder}', 'DMSFoldersController@userFolderAccess');
Route::get('dms/file/company_access/{folder}', 'DMSFoldersController@companyFileAccess');
Route::get('dms/file/group_access/{folder}', 'DMSFoldersController@groupFileAccess');
Route::get('dms/folder/user_access/{folder}', 'DMSFoldersController@userFileAccess');
Route::get('dms/group_admin', 'DMSGroupAdminController@index');
Route::post('dms/add_group', 'DMSGroupAdminController@store');
Route::get('dms/group/{group}/actdect', 'DMSGroupAdminController@groupAct');
Route::patch('dms/group/update/{group}', 'DMSGroupAdminController@update');
Route::get('dms/group/users/{group}/view', 'DMSGroupAdminController@groupUsers');
Route::post('dms/add_group_users', 'DMSGroupAdminController@saveGroupUsers');
Route::get('dms/group/users/actdeac/{groupUser}', 'DMSGroupAdminController@groupUsersAct');
Route::get('dms/grant_access', 'DMSGrantAccessController@index');
Route::post('dms/company/access_save', 'DMSGrantAccessController@storeCompanyAccess');
Route::post('dms/group/access_save', 'DMSGrantAccessController@storeGroupAccess');
Route::post('dms/user/access_save', 'DMSGrantAccessController@storeUserAccess');
Route::get('dms/comp_access/{company}/actdect', 'DMSGrantAccessController@companyAct');
Route::get('dms/group_access/{group}/actdect', 'DMSGrantAccessController@groupActive');
Route::get('dms/user_access/{user}/actdect', 'DMSGrantAccessController@userAct');
Route::get('dms/my_folders', 'DMSMyfolderController@index');
Route::get('dms/read-view-document/{file}', 'DMSMyfolderController@viewFile');