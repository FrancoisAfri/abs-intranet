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
Route::get('/home', function () {
    return Redirect::action('DashboardController@index');
});

Auth::routes();

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
Route::patch('contacts/{contactPerson}', 'ContactsController@update');
Route::get('contacts/send-message', 'ContactsController@sendMessageIndex');
Route::post('contacts/send-message', 'ContactsController@sendMessage');
Route::post('contacts/sms_settings', 'ContactsController@saveSetup');
Route::get('contacts/setup', 'ContactsController@setup');
Route::patch('contacts/update_sms/{smsConfiguration}', 'ContactsController@updateSMS');
//#Company Identity (company details: logo, theme color, etc)
Route::post('security/setup/company_details', 'CompanyIdentityController@saveOrUpdate');

#Business Card
Route::get('hr/user_card', 'BusinessCardsController@userCard');
Route::get('hr/business_card', 'BusinessCardsController@view');
Route::get('hr/active_card', 'BusinessCardsController@cards');
Route::post('hr/search', 'BusinessCardsController@getSearch');
Route::post('hr/print_card', 'BusinessCardsController@busibess_card');
Route::post('/hr/card_active', 'BusinessCardsController@activeCard');
Route::post('hr/emial', 'LeaveController@getEmail');

//#Leave Management
Route::post('leave/type/add_leave', 'LeaveController@addleave');
Route::patch('/leave/leave_type_edit/{lev}', 'LeaveController@editLeaveType');
Route::get('/leave/leave_active/{lev}', 'LeaveController@leaveAct');

//# leavesetup Controller
Route::get('leave/types', 'LeaveSetupController@setuptypes');
Route::get('/leave/setup', 'LeaveSetupController@showSetup');
Route::post('/leave/setup/{levg}', 'LeaveSetupController@store');
Route::post('/leave/setup/leave_type_edit/{lev}', 'LeaveSetupController@editsetupType');
Route::get('leave/setup/leave_credit', 'LeaveSetupController@apply');
Route::patch('/leave/setup/{id}', 'LeaveSetupController@addAnnual');
Route::patch('/leave/setup/{id}/sick', 'LeaveSetupController@addSick');

#leave Allocation
Route::get('leave/Allocate_leave_types', 'LeaveSetupController@show');
Route::post('leave/Allocate_leave', 'LeaveSetupController@Adjust');
Route::post('leave/Allocate_leave/resert', 'LeaveSetupController@resert');
Route::post('leave/Allocate_leave/add', 'LeaveSetupController@allocate');

#leave Application
Route::get('leave/application', 'LeaveApplicationController@index');
Route::post('leave/application/hours', 'LeaveApplicationController@hours');
Route::post('leave/application/day', 'LeaveApplicationController@day');
Route::get('leave/approval/{id}', 'LeaveApplicationController@AcceptLeave');

#leave Approval
Route::get('leave/approval', 'LeaveApplicationController@show');
Route::post('leave/reject/{levReject}', 'LeaveApplicationController@reject');

#leaveHistory audit
Route::get('leave/Leave_History_Audit', 'LeaveHistoryAuditController@show');
Route::get('leave/reports', 'LeaveHistoryAuditController@reports');
Route::post('leave/reports/result', 'LeaveHistoryAuditController@getReport');
Route::post('leave/reports/history', 'LeaveHistoryAuditController@getlevhistoryReport');

#leave history report
Route::post('appraisal/reports/result', 'AppraisalReportsController@getReport');
Route::post('appraisal/reports/result/print', 'AppraisalReportsController@printReport');

#Leave Reports
Route::post('leave/reports/taken', 'LeaveHistoryAuditController@taken');
Route::post('leave/reports/leavebal', 'LeaveHistoryAuditController@leavebalance');
Route::post('leave/reports/leavepaOut', 'LeaveHistoryAuditController@leavepaidOut');
Route::post('leave/reports/leaveAll', 'LeaveHistoryAuditController@leaveAllowance');
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
##reports
Route::post('contacts/reports/contact_note', 'ContactCompaniesController@contactnote');
Route::post('contacts/reports/meetings', 'ContactCompaniesController@meetings');

#reports
Route::post('reports/contact_note/meetingreport', 'ContactCompaniesController@printmeetingsReport');
Route::post('reports/contact_note/client_report', 'ContactCompaniesController@printclientReport');

Route::get('contacts/company_search', 'CompanySearchController@index');
Route::post('contacts/company_search_results', 'CompanySearchController@companySearch');

//AGM
//Route::get('contacts/agm', 'AGMContactsController@create');
//Route::post('contacts/agm/store', 'AGMContactsController@store');
# Employee Records Module
Route::get('hr/Admin', 'Hr_Admin@view');
Route::post('hr/searchemployees', 'Hr_Admin@search_employees');
Route::post('hr/user_active', 'Hr_Admin@activeEmployee');
Route::get('hr/active_user', 'Hr_Admin@cards');

Route::get('hr/job_title', 'EmployeeJobTitleController@index');
Route::post('hr/categories', 'EmployeeJobTitleController@categorySave');
Route::patch('hr/category_edit/{jobCategory}', 'EmployeeJobTitleController@editCategory');
Route::get('hr/jobtitles/{jobCategory}', 'EmployeeJobTitleController@jobView');
Route::get('/hr/category_active/{jobCategory}', 'EmployeeJobTitleController@categoryAct');
Route::get('/hr/job_title_active/{jobTitle}', 'EmployeeJobTitleController@jobtitleAct');
Route::post('hr/add_jobtitle/{jobCategory}', 'EmployeeJobTitleController@addJobTitle');
Route::patch('job_title/{jobTitle}', 'EmployeeJobTitleController@editJobTitle');

# Audit Module
Route::get('audit/reports', 'AuditReportsController@index');
Route::post('audits', 'AuditReportsController@getReport');
Route::post('audits/print', 'AuditReportsController@printreport');

#PRODUCTS
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
//----packages ---
Route::get('product/Packages', 'Product_categoryController@view_packages');
Route::post('Product/packages/add', 'Product_categoryController@packageSave');
Route::patch('Product/packages_edit/{package}', 'Product_categoryController@editPackage');
//----Promotions ---
Route::get('product/Promotions', 'Product_categoryController@view_promotions');
Route::post('Product/promotions/add', 'Product_categoryController@promotionSave');
Route::get('product/promotion/end/{promotion}', 'Product_categoryController@endPromotion');
#----price -----
Route::get('product/price', 'Product_categoryController@index');
Route::get('Product/price/{price}', 'Product_categoryController@view_prices');
Route::get('/Product/packages/{package}', 'Product_categoryController@viewProducts');
Route::post('product_packages/product/add/{package}', 'Product_categoryController@product_packageSave');
Route::post('/Product/price/add/{product}', 'Product_categoryController@priceSave');

#search
Route::get('product/Search', 'Product_categoryController@Search');
Route::post('product/product/Search', 'Product_categoryController@productSearch');
Route::post('product/category/Search', 'Product_categoryController@categorySearch');
Route::post('product/package/Search', 'Product_categoryController@packageSearch');
Route::post('product/promotion/Search', 'Product_categoryController@promotionSearch');

#Help Desk
Route::get('helpdesk/setup', 'HelpdeskController@viewsetup');
Route::post('help_desk/system/add', 'HelpdeskController@systemAdd');
Route::patch('help_desk/system/adit/{service}', 'HelpdeskController@editService');
Route::get('help_desk/service/{service}', 'HelpdeskController@view_service');
Route::get('helpdesk/view_ticket', 'HelpdeskController@viewTicket');
Route::get('/helpdesk/helpdeskAct/{desk}', 'HelpdeskController@helpdeskAct');
Route::get('/helpdesk/operatorAct/{desk}', 'HelpdeskController@operatorAct');
Route::get('/helpdesk/help_deskAdmin/{desk}', 'HelpdeskController@help_deskAdmin');
#search
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
//   ----------------- Help Desk Settings ------------------
Route::post('help_desk/setup', 'HelpdeskController@setup');
Route::post('help_desk/notify_managers', 'HelpdeskController@notify_managers');
Route::post('help_desk/auto_escalations', 'HelpdeskController@auto_escalations');
Route::post('help_desk/unresolved_tickets', 'HelpdeskController@unresolved_tickets');
Route::post('help_desk/auto_responder_messages', 'HelpdeskController@auto_responder_messages');
Route::post('help_desk/email_setup', 'HelpdeskController@email_setup');
##*************** Vehicle Management ************
Route::get('vehicle_management/Manage_fleet_types', 'VehicleManagemntController@index');
Route::post('vehice/add_fleet', 'VehicleManagemntController@Addfleet');
Route::patch('vehice/edit_fleet/{fleet}', 'VehicleManagemntController@editfleet');
Route::get('/vehice/fleet_act/{fleet}', 'VehicleManagemntController@VehicleAct');
Route::get('vehice/Manage_fleet/{fleet}/delete', 'VehicleManagemntController@deletefleet');
Route::get('vehicle_management/fleet_card', 'VehicleManagemntController@Fleet_Card');
Route::post('vehice/add_fleetcard', 'VehicleManagemntController@AddfleetCards');
Route::patch('vehice/edit_fleetcard/{card}', 'VehicleManagemntController@editfleetcard');
Route::get('/vehice/fleetcard_act/{card}', 'VehicleManagemntController@fleetcardAct');
Route::get('vehice/Manage_fleetcard_types/{card}/delete', 'VehicleManagemntController@deletefleetcard');
Route::get('vehicle_management/fillingstaion', 'VehicleManagemntController@Fleet_fillingstaion');
Route::post('vehice/add_fillingstation', 'VehicleManagemntController@Addfillingstation');
Route::patch('vehice/edit_station/{station}', 'VehicleManagemntController@editstation');
Route::get('/vehice/station_act/{station}', 'VehicleManagemntController@stationcardAct');
Route::get('vehice/station/{station}/delete', 'VehicleManagemntController@deletestation');
Route::get('vehicle_management/Permit', 'VehicleManagemntController@Fleet_licencePermit');
Route::post('vehice/add_license', 'VehicleManagemntController@AddlicencePermit');
Route::patch('vehice/edit_license/{permit}', 'VehicleManagemntController@editlicense');
Route::get('/vehice/licence_act/{permit}', 'VehicleManagemntController@licensePermitAct');
Route::get('vehice/license/{permit}/delete', 'VehicleManagemntController@deleteLicensePermit');
Route::get('vehicle_management/Document_type', 'VehicleManagemntController@Fleet_DocumentType');
Route::post('vehice/add_document', 'VehicleManagemntController@AddDocumentType');
Route::patch('vehice/edit_document/{document}', 'VehicleManagemntController@EditDocumentType');
Route::get('/vehice/document_act/{document}', 'VehicleManagemntController@DocumentTypeAct');
Route::get('vehice/document/{document}/delete', 'VehicleManagemntController@deleteDocument');
Route::get('vehicle_management/Incidents_type', 'VehicleManagemntController@IncidentType');
Route::post('vehice/incident_type', 'VehicleManagemntController@AddIncidentType');
Route::patch('vehice/edit_incident/{incident}', 'VehicleManagemntController@EditIncidentType');
Route::get('/vehice/incident_act/{incident}', 'VehicleManagemntController@incidentTypeAct');
Route::get('vehice/incident/{incident}/delete', 'VehicleManagemntController@deleteIncident');
Route::get('vehicle_management/vehice_make', 'VehicleManagemntController@vehicemake');
Route::post('vehice/addvehicle_make', 'VehicleManagemntController@AddVehicleMake');
Route::patch('vehice/edit_vehicle_make/{vmake}', 'VehicleManagemntController@editvehiclemake');
Route::get('/vehice/vehiclemake_act/{vmake}', 'VehicleManagemntController@vehiclemakeAct');
Route::get('vehice/vehiclemake/{vmake}/delete', 'VehicleManagemntController@deleteVehiclemake');
Route::get('vehicle_management/vehice_model', 'VehicleManagemntController@vehicemodel');
Route::post('vehice/addvehicle_model', 'VehicleManagemntController@AddVehicleModel');
Route::patch('vehice/edit_vehicle_model/{vmodel}', 'VehicleManagemntController@editvehiclemodel');
Route::get('/vehice/vehiclemodle_act/{vmodel}', 'VehicleManagemntController@vehiclemodelAct');
Route::get('vehice/vehiclemodel/{vmodel}/delete', 'VehicleManagemntController@deleteVehiclemodel');
Route::post('vehicle_management/vehicle/Search', 'VehicleManagemntController@VehicleSearch');


Route::get('vehicle_management/group_admin', 'VehicleManagemntController@groupAdmin');
Route::post('vehice/groupadmin', 'VehicleManagemntController@Addgroupadmin');
Route::patch('vehice/edit_group/{group}', 'VehicleManagemntController@edit_group');
Route::get('/vehice/group_act/{group}', 'VehicleManagemntController@groupAct');
Route::get('vehicle_management/setup', 'VehicleManagemntController@VehicleSetup');
Route::get('vehicle_management/vehicle_configuration', 'VehicleManagemntController@VehicleConfiguration');
Route::post('vehicle_management/configuration/{configuration}', 'VehicleManagemntController@Configuration');
##*************** Job card Management ************
Route::get('Jobcard_management/Job_card', 'JobcardManagementController@JobcardManagent');
Route::get('Jobcard_management/addJob_card', 'JobcardManagementController@addJobcard');
Route::post('jobcard_management/add_maintenance', 'JobcardManagementController@Addmaintenance');
##*************** Fleet Management ************
Route::get('vehicle_management/manage_fleet', 'FleetManagementController@fleetManagent');
Route::get('vehicle_management/add_vehicle', 'FleetManagementController@addvehicle');
Route::post('vehicle_management/add_vehicleDetails', 'FleetManagementController@addvehicleDetails');
Route::get('vehicle_management/viewdetails/{maintenance}', 'FleetManagementController@viewDetails');

#Performance Appraisals Module

Route::get('appraisal/setup', 'AppraisalSetupController@index');
Route::post('appraisal/setup', 'AppraisalSetupController@saveOrUpdate');
//Route::post('/appraisal/add', 'AppraisalSetupController@addAppraisal');
//Route::patch('/appraisal/latecomers/{appraisal_setup}', 'AppraisalSetupController@updateAppraisal');
//Route::get('/appraisals/latecomers/{appraisal_setup}/activate', 'AppraisalSetupController@activateAppraisal');
# Performance Appraisals Module

Route::get('appraisal/templates', 'AppraisalTemplatesController@viewTemlates');
Route::post('appraisal/template', 'AppraisalTemplatesController@temlateSave');
Route::patch('appraisal/template_edit/{template}', 'AppraisalTemplatesController@editTemplate');
#Performance Appraisals Module
Route::get('/appraisal/template_active/{template}', 'AppraisalTemplatesController@templateAct');
Route::get('appraisal/template/{template}', 'AppraisalTemplatesController@viewTemlate');

Route::post('appraisal/kpi', 'AppraisalTemplatesController@kpiSave');
Route::patch('appraisal/kpi_edit/{kpi}', 'AppraisalTemplatesController@editKpi');
Route::get('/appraisal/kpi_active/{kpi}', 'AppraisalTemplatesController@kpiAct');

#Kpi Types
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

//Emp appraisal and 360 appraisal
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
// Route::get('/hr/document', 'DocumentTypeController@viewDoc');
// Route::post('/hr/document/add/doc_type', 'DocumentTypeController@addList');
// Route::get('/hr/document/{listLevel}/activate', 'DocumentTypeController@activateList');
// Route::patch('/hr/document/{doc_type}', 'DocumentTypeController@updateList');
// Route::get('/hr/category', 'DocumentTypeController@viewCategory');
// Route::post('/hr/category/add/doc_type_category', 'DocumentTypeController@addDoc');
// Route::get('/hr/category/{listLevel}/activate', 'DocumentTypeController@activateDoc');
// Route::patch('/hr/category/{doc_type_category}', 'DocumentTypeController@updateDoc');
#Employees Documents Module
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

#Employees Qualifications Module
Route::get('/hr/emp_qualification', 'EmployeeQualificationsController@viewDoc');
Route::post('/hr/emp_qual/Search', 'EmployeeQualificationsController@Searchqul');
Route::post('/hr/upload/{docs}', 'EmployeeQualificationsController@uploadDocs');

#Employees upload
Route::get('/employee_upload', 'EmployeeUploadController@index');
Route::get('/employees_upload', 'EmployeeUploadController@store');

#Employee Search
Route::get('/hr/emp_search', 'EmployeeSearchController@index');
Route::post('/hr/users_search', 'EmployeeSearchController@getSearch');

# Company setup Module
Route::get('/hr/company_setup', 'EmployeeCompanySetupController@viewLevel');
Route::post('/hr/firstleveldiv/add/{divLevel}', 'EmployeeCompanySetupController@addLevel');
Route::patch('/hr/company_edit/{divLevel}/{childID}', 'EmployeeCompanySetupController@updateLevel');
// #Add qualification Type
// Route::post('hr/addqultype', 'EmployeeCompanySetupController@addqualType');
// Route::get('/hr/addqul/{sta}', 'EmployeeCompanySetupController@QualAct');
// Route::post('hr/qul_type_edit/{qul}', 'EmployeeCompanySetupController@editQualType');
#DocType
// Route::post('hr/addDoctype', 'EmployeeCompanySetupController@addDocType');
// Route::get('/hr/adddoc/{sta}', 'EmployeeCompanySetupController@DocAct');
// Route::post('/hr/Doc_type_edit/{doc}', 'EmployeeCompanySetupController@editDocType');
//Route::post('/hr/company_edit/{divLevel}', 'EmployeeCompanySetupController@editlevel');
Route::get('/hr/company_edit/{divLevel}/{childID}/activate', 'EmployeeCompanySetupController@activateLevel');
Route::get('/hr/child_setup/{level}/{parent_id}', 'EmployeeCompanySetupController@viewchildLevel');
Route::patch('/hr/firstchild/{parentLevel}/{childID}', 'EmployeeCompanySetupController@updateChild');
Route::post('/hr/firstchild/add/{parentLevel}/{parent_id}', 'EmployeeCompanySetupController@addChild');
Route::get('/hr/firstchild/{parentLevel}/{childID}/activate', 'EmployeeCompanySetupController@activateChild');
# Induction
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
Route::post('/task/meeting_report', 'TaskManagementController@getReport');
Route::post('/task/meeting/print', 'TaskManagementController@printreport');
//Clients (contacts) registration
//Route::post('contacts/register', 'ContactsRegisterController@register');
Route::post('users/recoverpw', 'ContactsRegisterController@recoverPassword');
//Survey (Guest)
Route::get('rate-our-services/{eid}', 'SurveyGuestsController@index');
Route::post('rate-our-services', 'SurveyGuestsController@store');
//Survey
Route::get('survey/reports', 'SurveysController@indexReports');
Route::get('survey/question_activate/{question}', 'SurveysController@actDeact');
Route::get('survey/questions', 'SurveysController@questionsLists');
Route::get('survey/rating-links', 'SurveysController@indexRatingLinks');
Route::post('survey/add_question', 'SurveysController@saveQuestions');
Route::post('survey/reports', 'SurveysController@getReport');
Route::post('survey/reports/print', 'SurveysController@printReport');
Route::patch('/survey/question_update/{question}', 'SurveysController@updateQuestions');

# Company setup Module
Route::get('/hr/setup', 'HrController@showSetup');
Route::patch('/hr/grouplevel/{groupLevel}', 'HrController@updateGroupLevel');
Route::get('/hr/grouplevel/activate/{groupLevel}', 'HrController@activateGroupLevel');
Route::post('hr/addqultype', 'HrController@addqualType');
Route::get('/hr/addqul/{sta}', 'HrController@QualAct');
Route::post('hr/qul_type_edit/{qul}', 'HrController@editQualType');
Route::get('/hr/document', 'HrController@viewDoc');
Route::post('/hr/document/add/doc_type', 'HrController@addList');
Route::get('/hr/document/{listLevel}/activate', 'HrController@activateList');
Route::patch('/hr/document/{cat_type}', 'HrController@updateList');
Route::get('/hr/category/{category}', 'HrController@viewCategory');
Route::post('/hr/category/add/doc_type_category', 'HrController@addDoc');
Route::get('/hr/category/{listLevel}/activate', 'HrController@activateDoc');
Route::patch('/hr/category/{doc_type_category}', 'HrController@updateDoc');
Route::post('hr/addDoctype/{category}', 'HrController@addDocType');
Route::post('/hr/Doc_type_edit/{edit_DocID}', 'HrController@editDocType');
Route::get('/hr/adddoc/{sta}', 'HrController@DocAct');
//quote
Route::get('quote/setup', 'QuotesController@setupIndex');
Route::get('quotes/authorisation', 'QuotesController@authorisationIndex');
Route::get('quote/term-conditions', 'QuotesTermConditionsController@index');
Route::post('quote/add-quote-term', 'QuotesTermConditionsController@store');
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
//CRM
Route::get('crm/account/{account}', 'CRMAccountController@viewAccount');
Route::get('crm/account/quote/{quote}', 'CRMAccountController@viewAccountFromQuote');
Route::get('crm/setup', 'CRMSetupController@index');
Route::get('crm/invoice/view/{quotation}/pdf', 'CRMInvoiceController@viewPDFInvoice');
Route::get('crm/invoice/mail/{quotation}', 'CRMInvoiceController@emailInvoice');
Route::post('crm/capture-payment/{quotation}/{invoice}', 'CRMAccountController@capturePayment');

//Email Template
Route::post('email-template/save', 'EmailTemplatesController@saveOrUpdate');

//General Use (API)
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
//Email Test
/*Route::get('testemail', function () {
    //Mail::to('smalto@afrixcel.co.za')->send(new UserCreated);
    $client = \App\User::find("3")->load('person');
    $loan = \App\Loan::first();
    //Mail::to("smalto@afrixcel.co.za")->send(new \App\Mail\RejectedLoanApplication($client, 'Some reason.', 1));
    Mail::to("smalto@afrixcel.co.za")->send(new \App\Mail\ApprovedLoanApplication($client, $loan));
    return back();
});*/