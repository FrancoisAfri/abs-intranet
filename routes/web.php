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
Route::get('users/setup', 'UsersController@companySetup');
Route::post('users/setup/modules', 'UsersController@addmodules');
Route::post('users/setup/add_ribbon/{mod}', 'UsersController@addribbon');
Route::get('/users/ribbons/{mod}', 'UsersController@ribbonView');
Route::patch('/users/module_edit/{mod}', 'UsersController@editModule');

// Reset password
Route::get('password/expired', 'ExpiredPasswordController@expired');
Route::post('password/post_expired/{user}', 'ExpiredPasswordController@postExpired');

Route::patch('/ribbon/{ribbon}', 'UsersController@editRibbon');
Route::get('/users/module_active/{mod}', 'UsersController@moduleAct');
Route::get('/users/module_access/{user}', 'UsersController@moduleAccess');
Route::get('/users/ribbon_active/{rib}', 'UsersController@ribbonAct');
Route::post('/users/access_save/{user}', 'UsersController@accessSave');
Route::get('/user/delete/{user}', 'UsersController@deleteUser');
Route::get('users/users-access', 'SecurityController@usersAccess');
Route::post('users/users-access', 'SecurityController@getEmployees');
Route::post('users/update-users-access', 'SecurityController@updateRights');

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
Route::get('System/policy/viewdetails/{policydetails}', 'PolicyEnforcementController@viewdetails');
Route::post('System/policy/viewUsers', 'PolicyEnforcementController@viewpolicyUsers');
Route::patch('System/policy/edit_policy/{policy}', 'PolicyEnforcementController@editPolicy');

Route::get('System/policy/viewuserdetails/{policydetails}', 'PolicyEnforcementController@viewuserdetails');
Route::get('leave/application', 'LeaveApplicationController@index');
Route::post('leave/application/hours', 'LeaveApplicationController@hours');
Route::post('leave/application/day', 'LeaveApplicationController@day');
Route::get('leave/approval/{id}', 'LeaveApplicationController@AcceptLeave');

//leave Approval
Route::get('leave/approval', 'LeaveApplicationController@show');
Route::post('leave/reject/{levReject}', 'LeaveApplicationController@reject');

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
Route::post('leave/print', 'LeaveHistoryAuditController@printlevhistoReport');
Route::post('leave/bal', 'LeaveHistoryAuditController@printlevbalReport');

//#custom leave
Route::post('leave/custom/add_leave', 'LeaveController@addcustom');
Route::get('/leave/custom/leave_type_edit/{lev}', 'LeaveController@customleaveAct');
Route::post('/leave/custom/leave_type_edit/{lev}', 'LeaveController@editcustomLeaveType');

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
Route::get('contacts/company/{company}/notes', 'ContactCompaniesController@notes');
Route::patch('contacts/company/{company}', 'ContactCompaniesController@updateCompany');
Route::post('contacts/company/addnotes', 'ContactCompaniesController@addnote');
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
Route::get('import/company', 'ContactsUploadController@index');
Route::post('contacts_upload', 'ContactsUploadController@store');
//reports
Route::post('reports/contact_note/meetingreport', 'ContactCompaniesController@printmeetingsReport');
Route::post('reports/contact_note/client_report', 'ContactCompaniesController@printclientReport');

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
Route::post('help_desk/admin/add/{adminID}', 'HelpdeskController@addAdmin');
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
Route::post('help_desk/email_setup', 'HelpdeskController@email_setup');

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
Route::get('vehicle_management/vehicle_approval', 'fleetcardController@vehicle_approval');
Route::post('vehicle_management/vehicleApproval', 'fleetcardController@vehicleApprovals');
Route::patch('vehicle_management/reject_vehicle/{reason}','fleetcardController@rejectReason' );
Route::get('vehicle_management/vehicle_approval', 'fleetcardController@vehicle_approval');

//##----bookings
Route::get('vehicle_management/create_request', 'VehicleBookingController@index');
Route::get('vehicle_management/vehicle_request', 'VehicleBookingController@vehiclerequest');
Route::post('vehicle_management/vehiclesearch', 'VehicleBookingController@VehicleSearch');
Route::get('vehicle_management/bookingdetails/{bookings}/{required}', 'VehicleBookingController@viewBooking');

Route::post('vehicle_management/vehiclebooking', 'VehicleBookingController@vehiclebooking');
Route::get('vehicle_management/vehiclebooking_results', 'VehicleBookingController@booking_results');
//cancel booking
 Route::get('vehicle_management/cancel_booking/{booking}', 'VehicleBookingController@cancel_booking');
// edit booking
 Route::patch('vehicle_management/edit_booking/{Vehiclebookings}', 'VehicleBookingController@edit_bookings');
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

###

Route::get('vehicle_management/Manage_fleet_types', 'VehicleManagemntController@index');
Route::post('vehice/add_fleet', 'VehicleManagemntController@Addfleet');
Route::patch('vehice/edit_fleet/{fleet}', 'VehicleManagemntController@editfleet');
Route::get('/vehice/fleet_act/{fleet}', 'VehicleManagemntController@VehicleAct');
Route::get('vehice/Manage_fleet/{fleet}/delete', 'VehicleManagemntController@deletefleet');
// ---
Route::get('vehicle_management/fleet_card', 'VehicleManagemntController@Fleet_Card');
Route::post('vehice/add_fleetcard', 'VehicleManagemntController@AddfleetCards');
Route::patch('vehice/edit_fleetcard/{card}', 'VehicleManagemntController@editfleetcard');
Route::get('/vehice/fleetcard_act/{card}', 'VehicleManagemntController@fleetcardAct');
Route::get('vehice/Manage_fleetcard_types/{card}/delete', 'VehicleManagemntController@deletefleetcard');
// ---
Route::get('vehicle_management/fillingstaion', 'VehicleManagemntController@Fleet_fillingstaion');
Route::post('vehice/add_fillingstation', 'VehicleManagemntController@Addfillingstation');
Route::patch('vehice/edit_station/{station}', 'VehicleManagemntController@editstation');
Route::get('/vehice/station_act/{station}', 'VehicleManagemntController@stationcardAct');
Route::get('vehice/station/{station}/delete', 'VehicleManagemntController@deletestation');
// ---
Route::get('vehicle_management/Permit', 'VehicleManagemntController@Fleet_licencePermit');
Route::post('vehice/add_license', 'VehicleManagemntController@AddlicencePermit');
Route::patch('vehice/edit_license/{permit}', 'VehicleManagemntController@editlicense');
Route::get('/vehice/licence_act/{permit}', 'VehicleManagemntController@licensePermitAct');
Route::get('vehice/license/{permit}/delete', 'VehicleManagemntController@deleteLicensePermit');
// ---
Route::get('vehicle_management/Document_type', 'VehicleManagemntController@Fleet_DocumentType');
Route::post('vehice/add_document', 'VehicleManagemntController@AddDocumentType');
Route::patch('vehice/edit_document/{document}', 'VehicleManagemntController@EditDocumentType');
Route::get('/vehice/document_act/{document}', 'VehicleManagemntController@DocumentTypeAct');
Route::get('vehice/document/{document}/delete', 'VehicleManagemntController@deleteDocument');
//---
Route::get('vehicle_management/Incidents_type', 'VehicleManagemntController@IncidentType');
Route::post('vehice/incident_type', 'VehicleManagemntController@AddIncidentType');
Route::patch('vehice/edit_incident/{incident}', 'VehicleManagemntController@EditIncidentType');
Route::get('/vehice/incident_act/{incident}', 'VehicleManagemntController@incidentTypeAct');
Route::get('vehice/incident/{incident}/delete', 'VehicleManagemntController@deleteIncident');
// ----
Route::get('vehicle_management/vehice_make', 'VehicleManagemntController@vehicemake');
Route::post('vehice/addvehicle_make', 'VehicleManagemntController@AddVehicleMake');
Route::patch('vehice/edit_vehicle_make/{vmake}', 'VehicleManagemntController@editvehiclemake');
Route::get('/vehice/vehiclemake_act/{vmake}', 'VehicleManagemntController@vehiclemakeAct');
Route::get('vehice/vehiclemake/{vmake}/delete', 'VehicleManagemntController@deleteVehiclemake');
// ---
Route::get('vehicle_management/vehice_model/{model}', 'VehicleManagemntController@vehicemodel');
Route::post('vehice/addvehicle_model/{vehiclemake}', 'VehicleManagemntController@AddVehicleModel');
Route::patch('vehice/edit_vehicle_model/{vmodel}', 'VehicleManagemntController@editvehiclemodel');
Route::get('/vehice/vehiclemodle_act/{vmodel}', 'VehicleManagemntController@vehiclemodelAct');
Route::get('vehice/vehiclemodel/{vmodel}/delete', 'VehicleManagemntController@deleteVehiclemodel');
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
Route::get('vehicle_management/Manage_safe/{safe}/delete', 'VehicleManagemntController@deletesafe');

//#*************** Fleet Management ************
Route::get('vehicle_management/manage_fleet', 'FleetManagementController@fleetManagent');
Route::get('vehicle_management/add_vehicle', 'FleetManagementController@addvehicle');
//
Route::post('vehicle_management/add_vehicleDetails', 'FleetManagementController@addvehicleDetails');
Route::get('vehicle_management/viewdetails/{maintenance}', 'FleetManagementController@viewDetails');
Route::patch('vehicle_management/edit_vehicleDetails/{vehicle_maintenance}', 'FleetManagementController@editvehicleDetails');

Route::get('/vehicle_management/vehicles_Act/{vehiclemaintenance}', 'FleetManagementController@vehiclesAct');

//******************** post redirects ****************
Route::get('vehicle_management/viewImage/{maintenance}', 'FleetManagementController@viewImage');
Route::get('vehicle_management/keys/{maintenance}', 'FleetManagementController@keys');
Route::get('vehicle_management/document/{maintenance}', 'VehicleFleetController@document');
Route::get('vehicle_management/contracts/{maintenance}', 'VehicleFleetController@contracts');

Route::get('vehicle_management/oil_log/{maintenance}', 'VehicleFleetController@viewOilLog');

Route::get('vehicle_management/fuel_log/{maintenance}', 'VehicleFleetController@viewFuelLog');
Route::post('vehicle_management/addvehiclefuellog', 'VehicleFleetController@addvehiclefuellog');
Route::get('vehice/Manage_fuullog/{fuel}/delete', 'VehicleFleetController@deletefuelLog');
Route::get('vehicle_management/fuel_log/{maintenance}/{date}', 'VehicleFleetController@viewFuelLog');
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
Route::get('vehicle_management/document/{documents}/delete', 'FleetManagementController@deleteDoc');
Route::patch('vehicle_management/edit_vehicledoc/{vehicledocumets}', 'FleetManagementController@editVehicleDoc');

Route::get('vehicle_management/notes/{maintenance}', 'VehicleFleetController@viewnotes');
Route::post('vehicle_management/add_new_note', 'FleetManagementController@newnotes');
Route::patch('vehicle_management/edit_note/{note}', 'FleetManagementController@editNote');
Route::get('vehicle_management/note/{note}/delete', 'FleetManagementController@deleteNote');

//#
Route::get('vehicle_management/general_cost/{maintenance}', 'VehicleFleetController@viewGeneralCost');
Route::post('vehicle_management/addcosts', 'VehicleFleetController@addcosts');
Route::patch('vehicle_management/edit_costs/{costs}', 'VehicleFleetController@editcosts');
Route::get('vehicle_management/Manage_costs/{costs}/delete', 'VehicleFleetController@deletecosts');

Route::get('vehicle_management/permits_licences/{maintenance}', 'FleetManagementController@permits_licences');
Route::post('vehicle_management/addPermit', 'FleetManagementController@addPermit');
Route::patch('vehicle_management/edit_permit/{permit}', 'FleetManagementController@editPermit');

Route::post('vehicle_management/add_images', 'FleetManagementController@addImages');
Route::post('vehicle_management/add_keys', 'FleetManagementController@addkeys');
Route::patch('vehicle_management/edit_images/{image}', 'FleetManagementController@editImage');

Route::patch('vehicle_management/edit_key/{keytracking}', 'FleetManagementController@editKeys');

//######## serch Docs ################
Route::get('vehicle_management/Search', 'VehicleDocSearchController@index');
Route::post('vehicle_management/doc_search', 'VehicleDocSearchController@doc_search');
Route::post('vehicle_management/image_search', 'VehicleDocSearchController@image_search');

//######## Vehicle Reports ################
Route::get('vehicle_management/vehicle_reports', 'VehicleReportsController@index');
Route::post('vehicle_management/vehicle_reports/general', 'VehicleReportsController@general');
Route::post('vehicle_management/vehicle_reports/jobcard', 'VehicleReportsController@jobcard');
  // ***************
Route::post('vehicle_management/vehicle_reports/details', 'VehicleReportsController@generaldetails');
Route::get('vehicle_management/vehicle_reports/viewfinedetails/{vehicleID}', 'VehicleReportsController@vehicleFineDetails');
Route::get('vehicle_management/vehicle_reports/viewbookingdetails/{vehicleID}', 'VehicleReportsController@vehicleBookingDetails');
Route::get('vehicle_management/vehicle_reports/viewfueldetails/{vehicleID}', 'VehicleReportsController@vehicleFuelDetails');
Route::get('vehicle_management/vehicle_reports/viewservicedetails/{vehicleID}', 'VehicleReportsController@vehicleServiceDetails');
Route::get('vehicle_management/vehicle_reports/Incidents_details/{vehicleID}', 'VehicleReportsController@vehicleIncidentsDetails');
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
Route::get('/employees_upload', 'EmployeeUploadController@store');

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
Route::post('users/recoverpw', 'UsersController@recoverPassword');

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
Route::get('quotes/authorisation', 'QuotesController@authorisationIndex');
Route::post('quote/setup/add-quote-profile', 'QuotesController@saveQuoteProfile');
Route::post('quote/setup/update-quote-profile/{quoteProfile}', 'QuotesController@updateQuoteProfile');
Route::get('quote/create', 'QuotesController@createIndex');
Route::post('quote/adjust', 'QuotesController@adjustQuote');
Route::post('quote/save', 'QuotesController@saveQuote');
Route::post('quote/update/{quote}', 'QuotesController@updateQuote');
Route::get('quote/view/{quotation}', 'QuotesController@viewQuote');
Route::get('quote/search', 'QuotesController@searchQuote');
Route::get('quote/view/{quotation}/pdf', 'QuotesController@viewPDFQuote');
Route::get('quote/approve_quote/{quote}', 'QuotesController@approveQuote');
Route::post('quote/client-approve/{quote}', 'QuotesController@clientApproveQuote');
Route::get('quote/decline_quote/{quote}', 'QuotesController@declineQuote');
Route::get('quote/modify_quote/{quote}', 'QuotesController@updateQuoteIndex');
Route::post('quote/adjust_modification/{quote}', 'QuotesController@adjustQuoteModification');
Route::post('quote/search', 'QuotesController@searchResults');
Route::get('quote/email_quote/{quote}', 'QuotesController@emailQuote');
Route::get('quote/cancel_quote/{quote}', 'QuotesController@cancelQuote');
Route::post('newquote/save', 'QuotesController@newQuote');
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
Route::post('api/vehiclemodeldropdown', 'DropDownAPIController@vehiclemomdeDDID')->name('Vmmdropdown');
Route::post('api/divisionsdropdown', 'DropDownAPIController@divLevelGroupDD')->name('divisionsdropdown');
Route::post('api/hrpeopledropdown', 'DropDownAPIController@hrPeopleDD')->name('hrpeopledropdown');
Route::post('api/kpadropdown', 'DropDownAPIController@kpaDD')->name('kpadropdown');
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
Route::get('api/leave/negativeDays/{hr_id}/{levID}', 'LeaveApplicationController@negativeDays');

Route::get('api/tasks/emp/meetingTask/{divLvl}/{divID}', 'EmployeeTasksWidgetController@getMeetingEmployees')->name('meetingTasksEmployee');
Route::get('api/tasks/emp/inductionTask/{divLvl}/{divID}', 'EmployeeTasksWidgetController@getInductionEmployees')->name('inductionTasksEmployee');
Route::get('api/tasks/{task}/duration/{timeInSeconds}', 'TaskTimerController@updateDuration');
Route::get('api/tasks/{task}/get-duration', 'TaskTimerController@getDuration');

Route::post('api/contact-people-dropdown', 'DropDownAPIController@contactPeopleDD')->name('contactsdropdown');

//Test leave cron
Route::get('test/cron', 'AllocateLeavedaysFamilyCronController@sickDays');