<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\CallController;
use App\Http\Controllers\Admin\OfficeLocationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CustomerCompanyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VisitAndVisitPlanController;
use App\Http\Controllers\Employee\ConvayanceController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Employee\ECustomerCompanyController;
use App\Http\Controllers\Employee\ECustomerController;
use App\Http\Controllers\Employee\ECustomerVisit;
use App\Http\Controllers\Employee\EmployeeDashboardController;
use App\Http\Controllers\Employee\MyEmployeeController;
use App\Http\Controllers\Employee\VisitController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\RfidDeviceController;
use App\Http\Controllers\Employee\ECustomerOfferController;
use App\Http\Controllers\Admin\CustomerOfferController;
use App\Http\Controllers\Admin\InventoryMaintainController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RequisitionController;
use App\Http\Controllers\Employee\EmAccountCompanyController;
use App\Http\Controllers\Employee\EmpRequisition;
use App\Http\Controllers\GlobalController;
use App\Http\Controllers\GlobalImageController;
use App\Http\Controllers\HolidayController;
use App\Models\GlobalImage;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/test', function () {
    flash('Hello')->warning();
    return back();
})->name('test');

Route::get('/', [WelcomeController::class, 'welcome'])->name('welcome');

Route::get('/link', function () {
    return \Illuminate\Support\Facades\Artisan::call('storage:link');
});

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/check/prefix', [App\Http\Controllers\HomeController::class, 'prefix'])->name('prefix');
Route::get('/track', [App\Http\Controllers\HomeController::class, 'trackLocaion'])->name('track');


Route::group(['middleware' => ['auth', 'role:admin|moderator'], 'as' => 'admin.', 'prefix' => 'dashboard'], function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('my/profile', [AdminDashboardController::class, 'myProfile'])->name('myProfile');
    Route::get('edit/my/profile', [AdminDashboardController::class, 'editMyProfile'])->name('editMyProfile');
    Route::post('update/my/profile', [AdminDashboardController::class, 'updateMyProfile'])->name('updateMyProfile');
    Route::get('select/user/for/assign/role', [AdminDashboardController::class, 'selectUserForAssignRole'])->name('selectUserForAssignRole');

    Route::resource('user', UserController::class);
    Route::get('select-user', [UserController::class, 'selectUser'])->name('user.selectUser');
    Route::get('user/with/roles', [UserController::class, 'userWithRoles'])->name('user.userWithRoles');
    Route::get('search/user', [UserController::class, 'searchUser'])->name('user.search');
    Route::get('company/wise/teams/{user}', [UserController::class, 'CompanyTeams'])->name('user.CompanyTeams');
    Route::post('user/{user}/update/type/{type}', [UserController::class, 'userUpdate'])->name('userUpdate');
    Route::get('location/user/{user}', [UserController::class, 'location'])->name('user.location');
    Route::get('attaendance/user/{user}', [UserController::class, 'attaendance'])->name('user.attaendance');
    Route::any('user/{user}/assign/role/to/the/user', [UserController::class, 'assignRole'])->name('user.assignrole');
    // Route::post('user/{user}/assign/role/to/the/user', [UserController::class, 'assignRole'])->name('user.assignrole');

    Route::resource('roles', RoleController::class);
    Route::get('assign/role', [RoleController::class, 'assignRole'])->name('assignRole');
    Route::post('assign/role/post', [RoleController::class, 'assignRolePost'])->name('assignRolePost');
    Route::resource('permissions', PermissionController::class);

    Route::resource('company', CompanyController::class);
    Route::get('company/{company}/customers', [CompanyController::class, 'companyCustomers'])->name('company.customers');
    Route::get('company/{company}/office', [CompanyController::class, 'companyOffice'])->name('company.office');
    Route::get('company/{company}/employee', [CompanyController::class, 'companyEmployee'])->name('company.employee');
    Route::get('company/{company}/teams', [CompanyController::class, 'companyTeams'])->name('company.teams');
    Route::get('company/{company}/office/create', [CompanyController::class, 'companyOfficeCreate'])->name('company.office.create');
    Route::post('company/{company}/office/store', [CompanyController::class, 'companyOfficeStore'])->name('company.office.store');
    Route::get('company/{company}/office/{office}/edit', [CompanyController::class, 'companyOfficeEdit'])->name('company.office.edit');
    Route::patch('company/{company}/office/{office}/update', [CompanyController::class, 'companyOfficeUpdate'])->name('company.office.update');
    Route::delete('company/{company}/office/{office}/delete', [CompanyController::class, 'companyOfficeDelete'])->name('company.office.delete');

    Route::resource('team', TeamController::class);
    Route::get('team/{team}/roles', [TeamController::class, 'teamRoles'])->name('teamRoles');
    Route::post('add/team/{team}/role/type/{type}', [TeamController::class, 'addTeamRole'])->name('addTeamRole');
    Route::get('delete/team/{team}/role/{role}', [TeamController::class, 'roleDelete'])->name('roleDelete');


    Route::resource('customer', CustomerController::class);
    Route::get('customer/{customer}/transaction-history/', [CustomerController::class, 'customerTransactionHistory'])->name('customerTransactionHistory');

    Route::get('customer_to_location', [CustomerController::class, 'customer_to_location'])->name('customer_to_location');
    Route::get('specific/customer/search', [CustomerController::class, 'customerSearch'])->name('customerSearch');
    Route::post('customer/bulk/upload', [CustomerController::class, 'importCustomer'])->name('importCustomer');
    Route::post('customer/location', [CustomerController::class, 'importCustomer'])->name('importCustomer');
    Route::get('select/new/role', [CustomerController::class, 'selectNewRole'])->name('selectNewRole');
    Route::get('select/new/role/without/customer', [CustomerController::class, 'selectNewRoleWithoutCustomer'])->name('selectNewRoleWithoutCustomer');
    Route::resource('customer_company', CustomerCompanyController::class);

    Route::get('customer_company/{customer_company}/office', [CustomerCompanyController::class, 'customerCompanyOffice'])->name('customerCompanyOffice');
    Route::get('customer_company/{customer_company}/office/create', [CustomerCompanyController::class, 'customerCompanyOfficeCreate'])->name('customerCompanyOffice.create');
    Route::post('customer_company/{customer_company}/office/store', [CustomerCompanyController::class, 'customerCompanyOfficeStore'])->name('customerCompanyOffice.store');
    Route::get('customer_company/{customer_company}/office/{office}/edit', [CustomerCompanyController::class, 'customerCompanyOfficeEdit'])->name('customerCompanyOffice.edit');
    Route::patch('customer_company/{customer_company}/office/{office}/update', [CustomerCompanyController::class, 'customerCompanyOfficeUpdate'])->name('customerCompanyOffice.update');
    Route::delete('customer_company/{customer_company}/office/{office}/delete', [CustomerCompanyController::class, 'customerCompanyOfficeDelete'])->name('customerCompanyOffice.delete');


    Route::get('customer_company/{customer_company}/customers', [CustomerCompanyController::class, 'customerCompanyCustomers'])->name('customerCompanyCustomers');



    Route::get('/attendance/users/status/{status?}', [AttendanceController::class, 'attendeance'])->name('attendance');
    Route::get('/attendance/history', [AttendanceController::class, 'attendanceHistory'])->name('attendanceHistory');
    Route::get('/attendance/report/type/{type?}', [AttendanceController::class, 'attendanceReport'])->name('attendanceReport');
    Route::get('/attendance/company-wise/report/type/{type?}', [AttendanceController::class, 'companyWiseAttendanceReport'])->name('companyWiseAttendanceReport');




    Route::resource('location', OfficeLocationController::class);
    Route::post('customer/offic/bulk/upload', [OfficeLocationController::class, 'bulkUpload'])->name('bulkUpload');
    Route::get('location/{location}/rfid/devices', [RfidDeviceController::class, 'rfidDevices'])->name('rfidDevices');
    Route::post('location/{location}/rfid/device/add', [RfidDeviceController::class, 'rfidDeviceAdd'])->name('rfidDeviceAdd');
    Route::patch('location/{location}/rfid/{rfid}/device/update', [RfidDeviceController::class, 'rfidDeviceUpdate'])->name('rfidDeviceUpdate');
    Route::get('location/{location}/rfid/{rfid}/device/delete', [RfidDeviceController::class, 'rfidDeviceDelete'])->name('rfidDeviceDelete');
    Route::get('customer/office/create', [OfficeLocationController::class, 'customerOfficeCreate'])->name('customerOffice.create');
    Route::get('customer/office/seach', [OfficeLocationController::class, 'customerCompanyOfficeSearch'])->name('customerCompanyOfficeSearch');
    Route::post('customer/office/store', [OfficeLocationController::class, 'customerOfficeStore'])->name('customerOffice.store');
    Route::get('customer/office/{office}/edit', [OfficeLocationController::class, 'customerOfficeEdit'])->name('customerOffice.edit');
    Route::patch('customer/office/{office}/update', [OfficeLocationController::class, 'customerOfficeUpdate'])->name('customerOffice.update');
    Route::delete('customer/office/{office}/delete', [OfficeLocationController::class, 'customerOfficeDelete'])->name('customerOffice.delete');




    Route::resource('employee', EmployeeController::class);
    Route::get('search/employee', [EmployeeController::class, 'searchEmployee'])->name('employee.search');
    Route::get('employee/{employee}/location/', [EmployeeController::class, 'employeeLocation'])->name('user.employeeLocation');
    Route::get('employee/{employee}/attaendance', [EmployeeController::class, 'employeeAttaendance'])->name('user.employeeAttaendance');
    Route::get('employee/{employee}/office-visits', [EmployeeController::class, 'employeeOfficeVisits'])->name('user.employeeOfficeVisits');
    Route::get('employee/{employee}/office-visits/{date}', [EmployeeController::class, 'employeeOfficeVisitsDetails'])->name('user.employeeOfficeVisitsDetails');
    Route::get('load/company/location/ajax', [EmployeeController::class, 'loadLocation'])->name('loadLocationAjax');

    Route::get('employee/{employee}/customer', [EmployeeController::class, 'employeeCustomers'])->name('employeeCustomers');
    Route::get('employee/{employee}/customer/search', [EmployeeController::class, 'employeeCustomersSearch'])->name('employeeCustomersSearch');
    Route::get('employee/{employee}/customer/add', [EmployeeController::class, 'employeeCustomersAdd'])->name('employeeCustomersAdd');
    Route::post('employee/{employee}/customer/store', [EmployeeController::class, 'employeeCustomersStore'])->name('employeeCustomersStore');
    Route::get('employee/{employee}/customer/{customer}/edit', [EmployeeController::class, 'employeeCustomersEdit'])->name('employeeCustomersEdit');
    Route::patch('employee/{employee}/customer/{customer}/update', [EmployeeController::class, 'employeeCustomersUpdate'])->name('employeeCustomersUpdate');
    Route::delete('employee/{employee}/customer/{customer}/delete', [EmployeeController::class, 'employeeCustomersDelete'])->name('employeeCustomersDelete');
    Route::get('employee/{employee}/get/team/admin', [EmployeeController::class, 'teamAdminListAjax'])->name('teamAdminListAjax');
    Route::get('employee/{employee}/convayances-bill-history', [EmployeeController::class, 'convayancesBillHistory'])->name('convayancesBillHistory');

    Route::resource('designation', DesignationController::class);
    Route::resource('department', DepartmentController::class);

    // dummy
    // Route::get('my/customer/offers/{type}', [ECustomerOfferController::class, 'myCustomerOffers'])->name('myCustomerOffers');
    // Route::get('my/customer/{customer}/offers', [ECustomerOfferController::class, 'index'])->name('customerOffer');
    // Route::get('my/customer/{customer}/transaction-history/', [ECustomerOfferController::class, 'customerTransactionHistory'])->name('customerTransactionHistory');
    // Route::get('my/customer/{customer}/create', [ECustomerOfferController::class, 'create'])->name('customerOffer.create');
    // Route::get('my/customer/{customer}/offer/{offer}', [ECustomerOfferController::class, 'customerOfferItemAjax'])->name('customerOfferItemAjax');
    // Route::get('my/customer/offer/{offer}/item/{item}/ajax', [ECustomerOfferController::class, 'customerOfferItemUpdate'])->name('customerOfferItemUpdate');
    // Route::get('my/customer/offer/{offer}/item/{item}/delete', [ECustomerOfferController::class, 'customerOfferItemDelete'])->name('customerOfferItemDelete');
    Route::post('my/customer/offer/{offer}/final-save', [ECustomerOfferController::class, 'customerOfferFinalSave'])->name('customerOfferFinalSave');
    // Route::get('my/customer/offer/{offer}/edit', [ECustomerOfferController::class, 'customerOfferEdit'])->name('customerOfferEdit');
    // Route::post('my/customer/offer/{offer}/update', [ECustomerOfferController::class, 'customerOfferUpdate'])->name('customerOfferUpdate');
    // Route::post('my/customer/offer/{offer}//status/update', [ECustomerOfferController::class, 'customerOfferStatusUpdate'])->name('customerOfferStatusUpdate');
    // Route::get('my/customer/offer/{offer}/view', [ECustomerOfferController::class, 'customerOfferDetails'])->name('customerOfferDetails');
    // Route::get('my/customer/offer/{offer}/delete', [ECustomerOfferController::class, 'customerOfferDelete'])->name('customerOfferDelete');

    // Route::get('my/customer/{customer}/offer/{offer}/category/to/product', [ECustomerOfferController::class, 'categoryToProductAjax'])->name('categoryToProductAjax');

    //Customer Offers Start
    Route::get('my/customer/offers/type/{type}', [CustomerOfferController::class, 'customerOffers'])->name('customerOffers');
    Route::get('customer/{customer}/offer/{offer}/view', [CustomerOfferController::class, 'offerDetails'])->name('offerDetails');
    Route::get('my/customer/{customer}/offers', [CustomerOfferController::class, 'index'])->name('customerOffer');
    Route::get('my/customer/{customer}/create', [CustomerOfferController::class, 'create'])->name('customerOffer.create');
    Route::get('my/customer/{customer}/offer/{offer}', [CustomerOfferController::class, 'customerOfferItemAjax'])->name('customerOfferItemAjax');
    Route::get('my/customer/offer/{offer}/item/{item}/ajax', [CustomerOfferController::class, 'customerOfferItemUpdate'])->name('customerOfferItemUpdate');
    Route::get('my/customer/offer/{offer}/item/{item}/delete', [CustomerOfferController::class, 'customerOfferItemDelete'])->name('customerOfferItemDelete');
    Route::post('my/customer/offer/{offer}/final-save', [CustomerOfferController::class, 'customerOfferFinalSave'])->name('customerOfferFinalSave');
    Route::get('my/customer/offer/{offer}/edit', [CustomerOfferController::class, 'customerOfferEdit'])->name('customerOfferEdit');
    Route::post('my/customer/offer/{offer}/update', [CustomerOfferController::class, 'customerOfferUpdate'])->name('customerOfferUpdate');
    Route::get('my/customer/offer/{offer}/view', [CustomerOfferController::class, 'customerOfferDetails'])->name('customerOfferDetails');
    Route::get('my/customer/offer/{offer}/delete', [CustomerOfferController::class, 'customerOfferDelete'])->name('customerOfferDelete');
    Route::post('my/customer/offer/{offer}//status/update', [CustomerOfferController::class, 'customerOfferStatusUpdate'])->name('customerOfferStatusUpdate');

    //Customer Offers End

    //Visit Plan
    Route::get('visit/plan/', [VisitAndVisitPlanController::class, 'visitPlans'])->name('visitPlans');
    Route::get('visit/plan/create/', [VisitAndVisitPlanController::class, 'visitPlanCreate'])->name('visitPlan.create');
    Route::get('visit/plan/employee/to/customer/Ajax', [VisitAndVisitPlanController::class, 'visitPlanEmployeeToCustomers'])->name('visitPlanEmployeeToCustomers');
    Route::get('visit/plan/customer/to/office/Ajax', [VisitAndVisitPlanController::class, 'visitPlanCustomersToOffice'])->name('visitPlanCustomersToOffice');
    Route::post('visit/plan/store/', [VisitAndVisitPlanController::class, 'visitPlanStore'])->name('visitPlan.store');
    Route::match(['get', 'patch'], 'visit/plan/{visitPlan}/edit/', [VisitAndVisitPlanController::class, 'visitPlanEdit'])->name('visitPlan.edit');

    // Visits
    Route::get('customer/visits/{visit_plan}/visit/{visit}/status/{status}/update', [VisitAndVisitPlanController::class, 'customerVisitStatusUpdate'])->name('customerVisitStatusUpdate');


    Route::get('visits/{visitPlan}', [VisitAndVisitPlanController::class, 'visits'])->name('visits');
    Route::match(['get', 'post'], 'visits/{visitPlan}/add', [VisitAndVisitPlanController::class, 'visitAdd'])->name('visit.add');
    Route::match(['get', 'patch'], 'visits/{visit}/edit', [VisitAndVisitPlanController::class, 'visitEdit'])->name('visit.edit');
    Route::get('visits/{visit}/update/{status}', [VisitAndVisitPlanController::class, 'visitUpdateStatus'])->name('visitUpdateStatus');
    Route::get('visits/{visit_plan}/sales/item/add/ajax', [VisitAndVisitPlanController::class, 'tempSalesItemAjax'])->name('tempSalesItemAjax');
    Route::get('visits/{visit_plan}/sales/item/{item}/delete/ajax', [VisitAndVisitPlanController::class, 'tempSalesItemDeleteAjax'])->name('tempSalesItemDeleteAjax');
    Route::get('visits/{visit_plan}/sales/item/{item}/update/ajax', [VisitAndVisitPlanController::class, 'tempSalesItemUpdateAjax'])->name('tempSalesItemUpdateAjax');



    Route::get('visits/{visit}/convayances', [VisitAndVisitPlanController::class, 'convayances'])->name('convayances');
    Route::get('visits/{visit}/convayances/{convayance}', [VisitAndVisitPlanController::class, 'convayancesStore'])->name('convayance.store');
    Route::get('visits/convayances/{convayance}/item/{item}/delete/ajax', [VisitAndVisitPlanController::class, 'convayancesDelete'])->name('convayance.delete');
    Route::get('visits/convayances/{convayance}/item/{item}/update/ajax', [VisitAndVisitPlanController::class, 'convayancesChangeAjax'])->name('convayance.change');
    Route::get('visits/customer/visit/{visit}/convayances/{convayance}/item/add/ajax', [VisitAndVisitPlanController::class, 'convayancesAdd'])->name('convayance.add');
    Route::get('customer/visit/{visit}/employee/type/{type}', [VisitAndVisitPlanController::class, 'emplyeeDetailsAboutMovement'])->name('emplyeeDetailsAboutMovement');
    Route::get('visits/of-visit/plan/all-type/', [VisitAndVisitPlanController::class, 'allVisits'])->name('allVisits');
    Route::get('visits/type/{type}/sales-items', [VisitAndVisitPlanController::class, 'visitSaleItems'])->name('visitSaleItems');
    Route::get('customer/visit_plan/{visit_plan}/checked', [VisitAndVisitPlanController::class, 'customerVisited'])->name('customerVisited');



    Route::get('customer/visit_plan/{visit_plan}/category/to/service/product/visit/{visit?}', [CustomerOfferController::class, 'categoryToServiceProduct'])->name('categoryToServiceProduct');
    Route::get('customer/visit_plan/{visit_plan}/category/to/sale/product/visit/{visit?}', [CustomerOfferController::class, 'categoryToSaleProduct'])->name('categoryToSaleProduct');

    Route::get('customer/visits/{visit_plan}/service/item/add/ajax', [VisitAndVisitPlanController::class, 'ServiceProductAjax'])->name('ServiceProductAjax');
    Route::get('customer/visits/{visit_plan}/service/item/{item}/delete/ajax', [VisitAndVisitPlanController::class, 'serviceProductDeleteAjax'])->name('serviceProductDeleteAjax');
    Route::get('customer/visits/{visit_plan}/service/item/{item}/update/ajax', [VisitAndVisitPlanController::class, 'serviceProductUpdateAjax'])->name('serviceProductUpdateAjax');

    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/add/ajax', [VisitAndVisitPlanController::class, 'addRequirementsOfBattAndSpearPartAjax'])->name('addRequirementsOfBattAndSpearPartAjax');
    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/{item}/delete/ajax', [VisitAndVisitPlanController::class, 'deleteRequirementsOfBattAndSpearPartAjax'])->name('deleteRequirementsOfBattAndSpearPartAjax');
    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/{item}/update/ajax', [VisitAndVisitPlanController::class, 'updateRequirementsOfBattAndSpearPartAjax'])->name('updateRequirementsOfBattAndSpearPartAjax');

    Route::get('customer/visit-plan/{visit_plan}/visit/{visit}/view', [VisitAndVisitPlanController::class, 'customerVisitview'])->name('customerVisitview');
    Route::get('customer/visits/{visit_plan}/visit/{visit}/edit', [VisitAndVisitPlanController::class, 'customerVisitEdit'])->name('customerVisitEdit');
    Route::post('customer/visits/{visit_plan}/visit/{visit}/update', [VisitAndVisitPlanController::class, 'customerVisitUpdate'])->name('customerVisitUpdate');
    Route::get('customer/visits/{visit_plan}/visit/{visit}/status/{status}/update', [VisitAndVisitPlanController::class, 'customerVisitStatusUpdate'])->name('customerVisitStatusUpdate');


    //Call Assign Start

    Route::get('customer/all/Ajax', [CallController::class, 'customersAllAjax'])->name('customersAllAjax');
    Route::get('employee/all/Ajax', [CallController::class, 'employeesAllAjax'])->name('employeesAllAjax');

    Route::get('customer/office/ajax', [CallController::class, 'getCustomerOffice'])->name('getCustomerOffice');

    Route::get('calls/type/{type?}', [CallController::class, 'calls'])->name('calls');
    Route::get('calls/reffered/', [CallController::class, 'refferedCall'])->name('refferedCall');
    Route::match(['get', 'post'], 'calls/add', [CallController::class, 'addCalls'])->name('addCalls');
    Route::match(['get', 'post'], 'calls/{call}/edit-update', [CallController::class, 'updateCalls'])->name('updateCalls');
    Route::get('calls/{call}/visit/plan', [CallController::class, 'callWiseVisitPlan'])->name('callWiseVisitPlan');
    Route::match(['get', 'post'], 'calls/{call}/add/visit/plan', [CallController::class, 'addVisitPlan'])->name('addVisitPlan');

    //Call Assign End

       //Send Product Request From inhouse Start
       Route::get('send-customer-request-from/call/{call}', [CallController::class, 'sendRequestToTheCustomer'])->name('sendRequestToTheCustomer');
       Route::get('send-customer-request-from/call/{call}/Ajax', [CallController::class, 'addProductToSendRequestToTheCustomerAjax'])->name('addProductToSendRequestToTheCustomerAjax');
       Route::get('send-customer-request-from/call/{call}/item/{item}/Ajax', [CallController::class, 'deleteProductToSendRequestToTheCustomerAjax'])->name('deleteProductToSendRequestToTheCustomerAjax');
       Route::post('send-customer-request-from/call/{call}', [CallController::class, 'sendRequestToTheCustomerPost'])->name('sendRequestToTheCustomerPost');


       Route::get('receive-customer-request-product/type/{type}', [CallController::class, 'receivedCustomerRequestProduct'])->name('receivedCustomerRequestProduct');
       Route::get('receive-customer-request-product/item/{item}', [CallController::class, 'receivedCustomerRequestProductitem'])->name('receivedCustomerRequestProductitem');

       //Send Product Request From inhouse Start


    // Sales
    Route::get('visit/sales', [VisitAndVisitPlanController::class, 'visitSales'])->name('visitSales');
    Route::get('visit/collection', [VisitAndVisitPlanController::class, 'visitCollection'])->name('visitCollections');


    Route::resource('category', ProductCategoryController::class);
    Route::resource('brand', ProductBrandController::class);
    Route::resource('product', ProductController::class);
    Route::get('stock-history-of-product/{product}', [ProductController::class, 'stockhistory'])->name('stockHistory');
    Route::resource('holiday', HolidayController::class);
    Route::get('holiday-temp', [HolidayController::class, 'tempHoliday'])->name('holiday.temp');

    Route::get('requisitions/{type}/status/{status}', [RequisitionController::class, 'requisitions'])->name('requisitions');
    Route::get('requisition-details-of/{requisition}/{type}/status/{status}', [RequisitionController::class, 'requisitionDetails'])->name('requisitionDetails');
    Route::post('requisition-update-of/{requisition}/{type}', [RequisitionController::class, 'requisitionUpdate'])->name('requisitionUpdate');


    Route::get('requisition/of/visit/{visit}/type/{type}', [RequisitionController::class, 'requisition'])->name('requisition');
    Route::get('requisition/add-of/visit/{visit}/type/{type}', [RequisitionController::class, 'addRequisition'])->name('addRequisition');
    Route::post('requisition/update-of/visit/{visit?}/type/{type}/requisition/{requisition}', [RequisitionController::class, 'updateRequisition'])->name('updateRequisition');
    Route::get('requisition/edit-of/visit/{visit}/type/{type}/requisition/{requisition}', [RequisitionController::class, 'editRequisition'])->name('editRequisition');


    Route::get('inventory-maintains/type/{type}', [InventoryMaintainController::class, 'inventoryMaintain'])->name('inventoryMaintain');
    Route::get('inventory-maintain-details/type/{type}/requisition/{requisition}', [InventoryMaintainController::class, 'inventoryMaintainDetails'])->name('inventoryMaintainDetails');
    Route::get('inventory-update-details/type/{type}/requisition/{requisition}/item/{item}', [InventoryMaintainController::class, 'inventoryMaintainUpdate'])->name('inventoryMaintainUpdate');

    Route::get('requisition/item/return/maintain', [InventoryMaintainController::class, 'returnMaintainUpdate'])->name('returnMaintainUpdate');
    Route::post('send-to-received/requisition/{requisition}', [InventoryMaintainController::class, 'sendToReceived'])->name('sendToReceived');

    Route::get('recevie-product-from-requisition/type/{type}', [RequisitionController::class, 'readyToReceiveProduct'])->name('readyToReceiveProduct');
    Route::get('recevie-product-from-requisition/{requisition}/type/{type}', [RequisitionController::class, 'readyToReceiveProductDetails'])->name('readyToReceiveProductDetails');
    Route::get('recevie-product-from-requisition/{requisition}/status/{status}', [RequisitionController::class, 'readyToReceiveProductDetailsUpdate'])->name('readyToReceiveProductDetailsUpdate');

    Route::get('unused-product-from-requisition/type/{type}', [RequisitionController::class, 'unUsedProduct'])->name('unUsedProduct');
    Route::get('unused-product/item/{item}/send/to/the/store-head', [RequisitionController::class, 'unUsedProductSendToStoreHead'])->name('unUsedProductSendToStoreHead');
    Route::get('receive-unused/product/type/{type}', [RequisitionController::class, 'receiveUnusedProducts'])->name('receiveUnusedProducts');
    Route::get('receive/unused/{unused}/product/status/{status}/sent-to-team-member', [RequisitionController::class, 'sendReceiveUnusedProductToTeamMember'])->name('sendReceiveUnusedProductToTeamMember');
    Route::post('receive/unused/{unused}/product/status/{status}/sent-to-team-member', [RequisitionController::class, 'sendReceiveUnusedProductToTeamMemberPost'])->name('sendReceiveUnusedProductToTeamMemberPost');
    Route::get('products-of/status/{status}', [RequisitionController::class, 'ProductOfRepairRecharge'])->name('ProductOfRepairRecharge');
    Route::post('receive-unused/product/item/{item}/status/{status?}/update', [RequisitionController::class, 'receiveUnusedProductStatusUpdate'])->name('receiveUnusedProductStatusUpdate');
    Route::post('receive/unused/{unused}/product/status/{status}/sent-to-team-member', [RequisitionController::class, 'sendReceiveUnusedProductToTeamMemberPost'])->name('sendReceiveUnusedProductToTeamMemberPost');

    Route::get('show-received-product-in-service-th', [RequisitionController::class, 'showReceivedProductInServiceTeamHead'])->name('showReceivedProductInServiceTeamHead');

    //Product Received ->> Service TH, Store TH
    Route::get('recevie-product-from-for-inventory-stock-manage/type/{type}', [RequisitionController::class, 'receiveProductForStockManage'])->name('receiveProductForStockManage');
    Route::get('recevie-product-from-for-inventory-stock-manage/add', [RequisitionController::class, 'addReceiveProductForStockManage'])->name('addReceiveProductForStockManage');
    Route::get('recevie-product-from-for-inventory-stock-manage/item/{item}/delete', [RequisitionController::class, 'updateReceiveProductForStockManage'])->name('updateReceiveProductForStockManage');
    Route::get('temp-add-receive=product-for-stock-manage', [RequisitionController::class, 'tempAddReceiveProductForStockManage'])->name('tempAddReceiveProductForStockManage');
    Route::post('recevie-product-from-for-inventory-stock-manage/store', [RequisitionController::class, 'storeReceiveProductForStockManage'])->name('storeReceiveProductForStockManage');
    Route::get('ready-for-approve-recevie-product-from-for-inventory-stock/status/change', [RequisitionController::class, 'readyForApproveReceiveProductForStockManage'])->name('readyForApproveReceiveProductForStockManage');
    Route::get('approve-recevie-product-from-for-inventory-stock/status/approved/item/{item}', [RequisitionController::class, 'changeStatusReceiveProductForStockManage'])->name('changeStatusReceiveProductForStockManage');


    Route::get('old-stock-product/status/{status}', [RequisitionController::class, 'oldStockProduct'])->name('oldStockProduct');
    Route::get('challan-invoice/type/{type}', [RequisitionController::class, 'chalanAndInvoice'])->name('chalanAndInvoice');
    Route::get('challan/{challan}/received', [RequisitionController::class, 'chalanProductReceived'])->name('chalanProductReceived');

    Route::get('challan-invoice/{id}/type/{type}', [RequisitionController::class, 'chalanAndInvoiceDetails'])->name('chalanAndInvoiceDetails');

    Route::get('bill-collection/type/{type}', [AccountController::class, 'billCollection'])->name('billCollection');
    Route::get('assign-to-bill-collection/for/invoice/{invoice}', [AccountController::class, 'assignToBillCollection'])->name('assignToBillCollection');
    Route::get('collection/list/type/{type}', [AccountController::class, 'collectionList'])->name('collectionList');
    Route::get('transection/history', [AccountController::class, 'transectionHistory'])->name('transectionHistory');
    Route::get('customer/visits/of-invoice/{invoice}', [AccountController::class, 'customerVisitPlansOfInvoice'])->name('customerVisitPlansOfInvoice');
    Route::post('customer-visit/plan/', [AccountController::class, 'store'])->name('customerVisit.store');
    Route::get('attendance-report/type/{type?}', [ReportController::class, 'attendanceReport'])->name('attendanceReport');
    Route::get('collection-report/type/{type?}', [ReportController::class, 'collectionReport'])->name('collectionReport');


    Route::get('convayances/bill/type/{type}', [AccountController::class, 'allConvayances'])->name('allConvayances');
    Route::get('convayances/{convayance}/bill/details', [AccountController::class, 'convayancesDetails'])->name('convayancesDetails');

    // Convayance Bill Payment
    Route::get('convayances/bill/payment/type/', [AccountController::class, 'convayancesBillPayment'])->name('convayancesBillPayment');
    Route::get('convayances/{convayance}/bill/payment/details', [AccountController::class, 'convayancesBillPaymentDetails'])->name('convayancesBillPaymentDetails');
    Route::post('convayances/{convayance}/bill/paid', [AccountController::class, 'convayancesBillPaid'])->name('convayancesBillPaid');
    Route::get('customer/visit/{visit}/convayances/{convayances}/submit', [AccountController::class, 'convayancesSubmit'])->name('convayancesSubmit');
});


Route::group(['middleware' => ['myrole:employee', 'auth'], 'as' => 'employee.', 'prefix' => 'employee'], function () {


    Route::post('requisition-update-of/{requisition}/{type}', [RequisitionController::class, 'requisitionUpdate'])->name('requisitionUpdate');

    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('my/profile', [EmployeeDashboardController::class, 'myProfile'])->name('myProfile');
    Route::get('edit/my/profile', [EmployeeDashboardController::class, 'editMyProfile'])->name('editMyProfile');
    Route::post('edit/employee/{employee}/profile', [EmployeeDashboardController::class, 'updateMyProfile'])->name('updateMyProfile');

    Route::get('my/team', [MyEmployeeController::class, 'myTeam'])->name('myTeam');
    Route::match(['GET', 'POST', 'PATCH'], 'my/employee/add', [MyEmployeeController::class, 'myTeamAdd'])->name('myTeamAdd');
    Route::match(['GET', 'PATCH'], 'my/employee/{employee}/edit', [MyEmployeeController::class, 'myTeamEdit'])->name('myTeamEdit');
    Route::get('my/employee/{employee}/locations', [MyEmployeeController::class, 'myEmployeeLocation'])->name('myEmployeeLocation');
    Route::get('my/employee/{employee}/attandance', [MyEmployeeController::class, 'myEmployeeAttandace'])->name('myEmployeeAttandace');
    Route::get('my/employee/{employee}/customers', [MyEmployeeController::class, 'myEmployeeCustomers'])->name('myEmployeeCustomers');
    Route::get('my/employee/{employee}/office-visit', [MyEmployeeController::class, 'myEmployeeOfficeVisit'])->name('myEmployeeOfficeVisit');
    Route::get('my/employee/{employee}/office-visit/{date}', [MyEmployeeController::class, 'myEmployeeOfficeVisitByDate'])->name('myEmployeeOfficeVisitByDate');
    Route::get('my/employee/{employee}/visit/sales', [MyEmployeeController::class, 'myEmployeeVisitSale'])->name('myEmployeeVisitSale');
    Route::get('my/employee/{employee}/visit/collection', [MyEmployeeController::class, 'myEmployeeVisitCollection'])->name('myEmployeeVisitCollection');

    Route::get('customer/visit/{visit}/employee/type/{type}', [MyEmployeeController::class, 'emplyeeDetailsAboutMovement'])->name('emplyeeDetailsAboutMovement');
    //Call Assign Start
    Route::get('product/all/Ajax', [CallController::class, 'productAllAjax'])->name('productAllAjax');

    Route::get('calls', [MyEmployeeController::class, 'calls'])->name('calls');
    Route::get('referance/call', [MyEmployeeController::class, 'referanceCall'])->name('referanceCall');
    Route::match(['get', 'post'], 'calls/add', [MyEmployeeController::class, 'addCalls'])->name('addCalls');
    Route::match(['get', 'post'], 'calls/{call}/edit-update', [MyEmployeeController::class, 'updateCalls'])->name('updateCalls');
    Route::get('calls/{call}/visit/plan', [MyEmployeeController::class, 'callWiseVisitPlan'])->name('callWiseVisitPlan');
    Route::match(['get', 'post'], 'calls/{call}/add/visit/plan', [MyEmployeeController::class, 'addVisitPlan'])->name('addVisitPlan');
    //Call Assign End

    //Send Product Request From inhouse Start
    Route::get('send-customer-request-from/call/{call}', [MyEmployeeController::class, 'sendRequestToTheCustomer'])->name('sendRequestToTheCustomer');
    Route::get('send-customer-request-from/call/{call}/Ajax', [MyEmployeeController::class, 'addProductToSendRequestToTheCustomerAjax'])->name('addProductToSendRequestToTheCustomerAjax');
    Route::get('send-customer-request-from/call/{call}/item/{item}/Ajax', [MyEmployeeController::class, 'deleteProductToSendRequestToTheCustomerAjax'])->name('deleteProductToSendRequestToTheCustomerAjax');
    Route::post('send-customer-request-from/call/{call}', [MyEmployeeController::class, 'sendRequestToTheCustomerPost'])->name('sendRequestToTheCustomerPost');


    Route::get('receive-customer-request-product/type/{type}', [MyEmployeeController::class, 'receivedCustomerRequestProduct'])->name('receivedCustomerRequestProduct');
    Route::get('receive-customer-request-product/item/{item}', [MyEmployeeController::class, 'receivedCustomerRequestProductitem'])->name('receivedCustomerRequestProductitem');

    //Send Product Request From inhouse Start


    Route::get('my/employee/attandance', [MyEmployeeController::class, 'myEmployeeAttandance'])->name('myEmployeeAttandance');
    Route::get('my/employee/attendance/history', [MyEmployeeController::class, 'myEmployeeAttendanceHistory'])->name('myEmployeeAttendanceHistory');
    Route::get('my/employee/attendance/report/type/{type?}', [MyEmployeeController::class, 'myEmployeeAttendanceReport'])->name('myEmployeeAttendanceReport');



    Route::get('my/customers', [ECustomerController::class, 'index'])->name('myCustomers');
    Route::get('my/customer/search', [ECustomerController::class, 'myCustomerSearch'])->name('myCustomerSearch');
    Route::get('my/customers/create', [ECustomerController::class, 'create'])->name('myCustomers.create');
    Route::post('my/customer/store', [ECustomerController::class, 'store'])->name('myCustomers.store');
    Route::get('my/customer/{customer}/edit', [ECustomerController::class, 'edit'])->name('myCustomers.edit');
    Route::patch('my/customer/{customer}/update', [ECustomerController::class, 'update'])->name('myCustomers.update');
    Route::get('others/customers', [ECustomerController::class, 'othersCustomers'])->name('othersCustomers');
    Route::get('others/customer/search', [ECustomerController::class, 'othersCustomerSearch'])->name('othersCustomerSearch');
    Route::get('others/customer/{customer}/edit', [ECustomerController::class, 'othersCustomerEdit'])->name('othersCustomerEdit');
    Route::patch('others/customer/{customer}/update', [ECustomerController::class, 'othersCustomerUpdate'])->name('othersCustomerUpdate');

    Route::get('my/customer/{customer}/offices', [ECustomerController::class, 'myCustomerOffices'])->name('myCustomerOffices');
    Route::get('my/customer/{customer}/office/search', [ECustomerController::class, 'myCustomerOfficeSearch'])->name('myCustomerOfficeSearch');
    Route::get('my/customer/{customer}/office/add', [ECustomerController::class, 'myCustomerOfficeAdd'])->name('myCustomerOfficeAdd');
    Route::post('my/customer/{customer}/office/store', [ECustomerController::class, 'myCustomerOfficeStore'])->name('myCustomerOfficeStore');
    Route::get('my/customer/{customer}/office/{office}/edit', [ECustomerController::class, 'myCustomerOfficeEdit'])->name('myCustomerOfficeEdit');
    Route::patch('my/customer/{customer}/office/{office}/update', [ECustomerController::class, 'myCustomerOfficeUpdate'])->name('myCustomerOfficeUpdate');

    //Customer Offers Start
    Route::get('my/customer/offers/{type}', [ECustomerOfferController::class, 'myCustomerOffers'])->name('myCustomerOffers');
    Route::get('my/customer/{customer}/offers', [ECustomerOfferController::class, 'index'])->name('customerOffer');
    Route::get('my/customer/{customer}/transaction-history/', [ECustomerOfferController::class, 'customerTransactionHistory'])->name('customerTransactionHistory');
    Route::get('my/customer/{customer}/create', [ECustomerOfferController::class, 'create'])->name('customerOffer.create');
    Route::get('my/customer/{customer}/offer/{offer}', [ECustomerOfferController::class, 'customerOfferItemAjax'])->name('customerOfferItemAjax');
    Route::get('my/customer/offer/{offer}/item/{item}/ajax', [ECustomerOfferController::class, 'customerOfferItemUpdate'])->name('customerOfferItemUpdate');
    Route::get('my/customer/offer/{offer}/item/{item}/delete', [ECustomerOfferController::class, 'customerOfferItemDelete'])->name('customerOfferItemDelete');
    Route::post('my/customer/offer/{offer}/final-save', [ECustomerOfferController::class, 'customerOfferFinalSave'])->name('customerOfferFinalSave');
    Route::get('my/customer/offer/{offer}/edit', [ECustomerOfferController::class, 'customerOfferEdit'])->name('customerOfferEdit');
    Route::post('my/customer/offer/{offer}/update', [ECustomerOfferController::class, 'customerOfferUpdate'])->name('customerOfferUpdate');
    Route::post('my/customer/offer/{offer}//status/update', [ECustomerOfferController::class, 'customerOfferStatusUpdate'])->name('customerOfferStatusUpdate');
    Route::get('my/customer/offer/{offer}/view', [ECustomerOfferController::class, 'customerOfferDetails'])->name('customerOfferDetails');
    Route::get('my/customer/offer/{offer}/delete', [ECustomerOfferController::class, 'customerOfferDelete'])->name('customerOfferDelete');

    Route::get('my/customer/{customer}/offer/{offer}/category/to/product', [ECustomerOfferController::class, 'categoryToProductAjax'])->name('categoryToProductAjax');


    //Customer Offers End

    Route::get('customer/company', [ECustomerCompanyController::class, 'CustomerCompany'])->name('CustomerCompany');
    Route::post('customer/company/add', [ECustomerCompanyController::class, 'addCustomerCompany'])->name('addCustomerCompany');
    Route::patch('customer/company/{customerCompany}/update', [ECustomerCompanyController::class, 'updateCustomerCompany'])->name('updateCustomerCompany');
    Route::match(['get', 'Patch'], 'customer/company/edit', [ECustomerCompanyController::class, 'editCustomerCompany'])->name('editCustomerCompany');
    Route::get('customer/company/{customer_company}/office', [ECustomerCompanyController::class, 'customerCompanyOffice'])->name('customerCompanyOffice');
    Route::match(['get', 'post'], 'customer/company/{customer_company}/add', [ECustomerCompanyController::class, 'customerCompanyOfficeAdd'])->name('customerCompanyOfficeAdd');
    Route::match(['get', 'patch'], 'customer/company/{customer_company}/{office}/edit', [ECustomerCompanyController::class, 'customerCompanyOfficeEdit'])->name('customerCompanyOfficeEdit');


    Route::match(['get', 'post'], 'my/customer/add', [EmployeeDashboardController::class, 'addMyCustomers'])->name('addMyCustomers');

    // Route::get('my/location/history', [EmployeeDashboardController::class, 'myLocationHistory'])->name('myLocationHistory');
    Route::get('customer-visit/plan', [ECustomerVisit::class, 'index'])->name('customerVisit.index');
    Route::get('customer-visit/plan/create', [ECustomerVisit::class, 'create'])->name('customerVisit.create');
    Route::get('customer-visit/plan/{visit}/status/{status}/update', [ECustomerVisit::class, 'customerVisitPlanStatusUpdate'])->name('customerVisitPlanStatusUpdate');
    Route::get('customer-visit/plan/{visit}/edit', [ECustomerVisit::class, 'edit'])->name('customerVisit.edit');
    Route::patch('customer-visit/plan/{visit}/update', [ECustomerVisit::class, 'update'])->name('customerVisit.update');
    Route::get('employee/{employee}/customers/ajax', [ECustomerVisit::class, 'selectEmployeeCustomer'])->name('selectEmployeeCustomer');
    Route::get('employee/customers/company-office', [ECustomerVisit::class, 'getCustomerOffice'])->name('getCustomerOffice');
    Route::post('customer-visit/plan/', [ECustomerVisit::class, 'store'])->name('customerVisit.store');
    Route::get('customer-all-ajax', [ECustomerVisit::class, 'customerAllAjax'])->name('customerAllAjax');


    Route::get('all-of-my/team/members/visits/type', [VisitController::class, 'allOfMyTeamMemberVisits'])->name('allOfMyTeamMemberVisits');
    Route::get('customer/visits/{visit_plan}', [VisitController::class, 'customerVisits'])->name('customerVisits');
    Route::get('customer/visits/of-invoice/{invoice}', [VisitController::class, 'customerVisitPlansOfInvoice'])->name('customerVisitPlansOfInvoice');
    Route::get('customer/visits/{visit_plan}/create', [VisitController::class, 'customerVisitCreate'])->name('customerVisitCreate');
    Route::get('customer/visits/{visit_plan}/sales/item/add/ajax', [VisitController::class, 'tempSalesItemAjax'])->name('tempSalesItemAjax');
    Route::get('customer/visits/{visit_plan}/sales/item/{item}/delete/ajax', [VisitController::class, 'tempSalesItemDeleteAjax'])->name('tempSalesItemDeleteAjax');
    Route::get('customer/visits/{visit_plan}/sales/item/{item}/update/ajax', [VisitController::class, 'tempSalesItemUpdateAjax'])->name('tempSalesItemUpdateAjax');
    Route::get('customer/visits/{visit_plan}/service/item/add/ajax', [VisitController::class, 'ServiceProductAjax'])->name('ServiceProductAjax');
    Route::get('customer/visits/{visit_plan}/service/item/{item}/delete/ajax', [VisitController::class, 'serviceProductDeleteAjax'])->name('serviceProductDeleteAjax');
    Route::get('customer/visits/{visit_plan}/service/item/{item}/update/ajax', [VisitController::class, 'serviceProductUpdateAjax'])->name('serviceProductUpdateAjax');

    // Requirements of Batteries/Spare parts to solve the problems
    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/add/ajax', [VisitController::class, 'addRequirementsOfBattAndSpearPartAjax'])->name('addRequirementsOfBattAndSpearPartAjax');
    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/{item}/delete/ajax', [VisitController::class, 'deleteRequirementsOfBattAndSpearPartAjax'])->name('deleteRequirementsOfBattAndSpearPartAjax');
    Route::get('customer/visits/{visit_plan}/req/of-batt-spare-parts/solve/item/{item}/update/ajax', [VisitController::class, 'updateRequirementsOfBattAndSpearPartAjax'])->name('updateRequirementsOfBattAndSpearPartAjax');

    Route::post('customer/visits/{visit_plan}/store', [VisitController::class, 'customerVisitStore'])->name('customerVisitStore');
    Route::get('customer/visits/{visit_plan}/visit/{visit}/view', [VisitController::class, 'customerVisitview'])->name('customerVisitview');
    Route::get('customer/visits/{visit_plan}/visit/{visit}/edit', [VisitController::class, 'customerVisitEdit'])->name('customerVisitEdit');
    Route::post('customer/visits/{visit_plan}/visit/{visit}/update', [VisitController::class, 'customerVisitUpdate'])->name('customerVisitUpdate');
    Route::get('customer/visits/{visit_plan}/visit/{visit}/status/{status}/update', [VisitController::class, 'customerVisitStatusUpdate'])->name('customerVisitStatusUpdate');

    Route::get('customer/visit_plan/{visit_plan}/category/to/service/product/visit/{visit?}', [VisitController::class, 'categoryToServiceProduct'])->name('categoryToServiceProduct');
    Route::get('customer/visit_plan/{visit_plan}/category/to/sale/product/visit/{visit?}', [VisitController::class, 'categoryToSaleProduct'])->name('categoryToSaleProduct');

    Route::get('customer/visit_plan/{visit_plan}/checked', [VisitController::class, 'customerVisited'])->name('customerVisited');

    Route::post('my/customer/{customer}/offer/{offer}/final-save', [VisitController::class, 'customerOfferFinalSave'])->name('customerOfferFinalSave2');


    Route::get('convayances/bill/type/{type}', [ConvayanceController::class, 'allConvayances'])->name('allConvayances');
    Route::get('convayances/{convayance}/bill/details', [ConvayanceController::class, 'convayancesDetails'])->name('convayancesDetails');

    // Convayance Bill Payment
    Route::get('convayances/bill/payment/type/', [ConvayanceController::class, 'convayancesBillPayment'])->name('convayancesBillPayment');
    Route::get('convayances/{convayance}/bill/payment/details', [ConvayanceController::class, 'convayancesBillPaymentDetails'])->name('convayancesBillPaymentDetails');
    Route::post('convayances/{convayance}/bill/paid', [ConvayanceController::class, 'convayancesBillPaid'])->name('convayancesBillPaid');



    Route::get('customer/visit/{visit}/convayances', [ConvayanceController::class, 'convayances'])->name('convayances');
    Route::get('customer/visit/{visit}/convayances/{convayances}/item/add/ajax', [ConvayanceController::class, 'convayancesAdd'])->name('convayances.add');
    Route::get('convayances/{convayances}/item/{item}/delete/ajax', [ConvayanceController::class, 'convayancesDelete'])->name('convayances.delete');
    Route::get('convayances/{convayances}/item/{item}/change/ajax/', [ConvayanceController::class, 'convayancesChangeAjax'])->name('convayancesChangeAjax');
    Route::get('customer/visit/{visit}/convayances/{convayances}/submit', [ConvayanceController::class, 'convayancesSubmit'])->name('convayancesSubmit');

    Route::get('requisition/type/{type}/status/{status}', [EmpRequisition::class, 'requisitionIndex'])->name('requisitionIndex');
    Route::get('requisition/{requisition}/type/{type}/status/{status}', [EmpRequisition::class, 'requisitionDetails'])->name('requisitionDetails');

    Route::get('requisition/of/visit/{visit}/type/{type}', [EmpRequisition::class, 'requisition'])->name('requisition');
    Route::get('requisition/add-of/visit/{visit}/type/{type}', [EmpRequisition::class, 'addRequisition'])->name('addRequisition');
    Route::post('requisition/update-of/visit/{visit?}/type/{type}/requisition/{requisition}', [EmpRequisition::class, 'updateRequisition'])->name('updateRequisition');
    Route::get('requisition/edit-of/visit/{visit}/type/{type}/requisition/{requisition}', [EmpRequisition::class, 'editRequisition'])->name('editRequisition');

    Route::get('inventory-maintains/type/{type}', [EmpRequisition::class, 'inventoryMaintain'])->name('inventoryMaintain');
    Route::get('inventory-maintain-details/type/{type}/requisition/{requisition}', [EmpRequisition::class, 'inventoryMaintainDetails'])->name('inventoryMaintainDetails');
    Route::get('inventory-update-details/type/{type}/requisition/{requisition}/item/{item}', [EmpRequisition::class, 'inventoryMaintainUpdate'])->name('inventoryMaintainUpdate');

    Route::get('requisition/item/return/maintain', [EmpRequisition::class, 'returnMaintainUpdate'])->name('returnMaintainUpdate');
    Route::post('send-to-received/requisition/{requisition}', [EmpRequisition::class, 'sendToReceived'])->name('sendToReceived');


    Route::get('visit/{visit}/status/{status}/update', [EmpRequisition::class, 'inventoryStatusUpdateOfVisit'])->name('inventoryStatusUpdateOfVisit');
    Route::get('requisition/product/item/{item}/status/update', [EmpRequisition::class, 'requisitionProductStatusUpdate'])->name('requisitionProductStatusUpdate');

    // Route::get('requisition/for/warrenty-claim/visit/{visit}',[EmpRequisition::class,'requisitionProductUsedUnused'])->name('requisitionProductUsedUnused');

    //Ready To Receive
    Route::get('recevie-product-from-requisition/type/{type}', [EmpRequisition::class, 'readyToReceiveProduct'])->name('readyToReceiveProduct');
    Route::get('recevie-product-from-requisition/{requisition}/type/{type}', [EmpRequisition::class, 'readyToReceiveProductDetails'])->name('readyToReceiveProductDetails');
    Route::get('recevie-product-from-requisition/{requisition}/status/{status}', [EmpRequisition::class, 'readyToReceiveProductDetailsUpdate'])->name('readyToReceiveProductDetailsUpdate');

    //Product Received ->> Service TH, Store TH
    Route::get('recevie-product-from-for-inventory-stock-manage/type/{type}', [EmpRequisition::class, 'receiveProductForStockManage'])->name('receiveProductForStockManage');
    Route::get('recevie-product-from-for-inventory-stock-manage/add', [EmpRequisition::class, 'addReceiveProductForStockManage'])->name('addReceiveProductForStockManage');
    Route::get('recevie-product-from-for-inventory-stock-manage/item/{item}/delete', [EmpRequisition::class, 'updateReceiveProductForStockManage'])->name('updateReceiveProductForStockManage');
    Route::get('temp-add-receive=product-for-stock-manage', [EmpRequisition::class, 'tempAddReceiveProductForStockManage'])->name('tempAddReceiveProductForStockManage');
    Route::post('recevie-product-from-for-inventory-stock-manage/store', [EmpRequisition::class, 'storeReceiveProductForStockManage'])->name('storeReceiveProductForStockManage');
    Route::get('ready-for-approve-recevie-product-from-for-inventory-stock/status/change', [EmpRequisition::class, 'readyForApproveReceiveProductForStockManage'])->name('readyForApproveReceiveProductForStockManage');
    Route::get('approve-recevie-product-from-for-inventory-stock/status/approved/item/{item}', [EmpRequisition::class, 'changeStatusReceiveProductForStockManage'])->name('changeStatusReceiveProductForStockManage');
    //Unused Product
    Route::get('unused-product-from-requisition/type/{type}', [EmpRequisition::class, 'unUsedProduct'])->name('unUsedProduct');
    Route::get('unused-product/item/{item}/send/to/the/store-head', [EmpRequisition::class, 'unUsedProductSendToStoreHead'])->name('unUsedProductSendToStoreHead');
    Route::get('receive-unused/product/type/{type}', [EmpRequisition::class, 'receiveUnusedProducts'])->name('receiveUnusedProducts');
    Route::get('receive/unused/{unused}/product/status/{status}/sent-to-team-member', [EmpRequisition::class, 'sendReceiveUnusedProductToTeamMember'])->name('sendReceiveUnusedProductToTeamMember');
    Route::get('show-received-product-in-service-th', [EmpRequisition::class, 'showReceivedProductInServiceTeamHead'])->name('showReceivedProductInServiceTeamHead');

    Route::get('products-of/status/{status}', [EmpRequisition::class, 'ProductOfRepairRecharge'])->name('ProductOfRepairRecharge');
    Route::post('receive-unused/product/item/{item}/status/{status?}/update', [EmpRequisition::class, 'receiveUnusedProductStatusUpdate'])->name('receiveUnusedProductStatusUpdate');
    Route::post('receive/unused/{unused}/product/status/{status}/sent-to-team-member', [EmpRequisition::class, 'sendReceiveUnusedProductToTeamMemberPost'])->name('sendReceiveUnusedProductToTeamMemberPost');


    Route::get('warranty-claim-of-requisition/status/{status}', [EmpRequisition::class, 'warrantyClaim'])->name('warrantyClaim');
    Route::get('warranty-claim-of-requisition/{requisition}', [EmpRequisition::class, 'warrantyClaimDetails'])->name('warrantyClaimDetails');
    Route::post('warranty-claim-of-requisition/{requisition}/update', [EmpRequisition::class, 'warrantyClaimUpdate'])->name('warrantyClaimUpdate');

    Route::get('employee/customers/check/ajax', [ECustomerVisit::class, 'employeeCustomerCheckAjas'])->name('employeeCustomerCheckAjas');
    Route::get('visti/to/product/itemAjax', [EmpRequisition::class, 'visitToProductItemAjax'])->name('visitToProductItemAjax');

    Route::get('old-stock-product/status/{status}', [EmpRequisition::class, 'oldStockProduct'])->name('oldStockProduct');
    Route::get('challan-invoice/type/{type}', [EmpRequisition::class, 'chalanAndInvoice'])->name('chalanAndInvoice');
    Route::get('challan-invoice/{id}/type/{type}', [EmpRequisition::class, 'chalanAndInvoiceDetails'])->name('chalanAndInvoiceDetails');

    Route::get('bill-collection/type/{type}', [EmAccountCompanyController::class, 'billCollection'])->name('billCollection');
    Route::get('assign-to-bill-collection/for/invoice/{invoice}', [EmAccountCompanyController::class, 'assignToBillCollection'])->name('assignToBillCollection');
    Route::get('collection/list/type/{type}', [EmAccountCompanyController::class, 'collectionList'])->name('collectionList');
    Route::get('transection/history', [EmAccountCompanyController::class, 'transectionHistory'])->name('transectionHistory');
});

Route::group(['middleware' => ['myrole:customer', 'auth'], 'as' => 'customer.', 'prefix' => 'customer'], function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('my/profile', [CustomerDashboardController::class, 'myProfile'])->name('myProfile');
    Route::get('edit/my/profile', [CustomerDashboardController::class, 'editMyProfile'])->name('editMyProfile');
    Route::post('edit/my/profile/{customer}', [CustomerDashboardController::class, 'update'])->name('update');

    Route::get('calls', [CustomerDashboardController::class, 'calls'])->name('calls');
    Route::match(['get', 'post'], 'calls/add', [CustomerDashboardController::class, 'addCalls'])->name('addCalls');
    Route::match(['get', 'post'], 'calls/{call}/edit-update', [CustomerDashboardController::class, 'updateCalls'])->name('updateCalls');
    Route::get('offers', [CustomerDashboardController::class, 'offers'])->name('offers');
    Route::get('offer/{offer}/view', [CustomerDashboardController::class, 'offerDetails'])->name('offerDetails');
    Route::post('offer/{offer}/update', [CustomerDashboardController::class, 'offerUpdate'])->name('offerUpdate');


    Route::get('send-the-product-inhouse/type/{type}', [CustomerDashboardController::class, 'sendTheProductInhouse'])->name('sendTheProductInhouse');
    Route::get('send-the-product-inhouse/ite/{item}', [CustomerDashboardController::class, 'sendTheProductItemInhouse'])->name('sendTheProductItemInhouse');

    Route::get('challan-invoice/type/{type}', [CustomerDashboardController::class, 'chalanAndInvoice'])->name('chalanAndInvoice');
    Route::get('challan-invoice/{id}/type/{type}', [CustomerDashboardController::class, 'chalanAndInvoiceDetails'])->name('chalanAndInvoiceDetails');
    Route::get('challan/{challan}/received', [CustomerDashboardController::class, 'chalanProductReceived'])->name('chalanProductReceived');
    Route::get('transaction-history', [CustomerDashboardController::class, 'transactionHistory'])->name('transactionHistory');
});

// Route::group(['middleware' => ['myrole:employee', 'auth'], 'as' => 'employee.', 'prefix' => 'employee'], function () {
//     Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');

//     // Route::get('/user/{user}/dashboard/', [UserDashboardController::class, 'userDashboard'])->name('dashboard');
//     // Route::get('/view/user/{user}', [UserDashboardController::class, 'viewUser'])->name('viewUser');
//     // Route::get('/edit/user/{user}', [UserDashboardController::class, 'editUser'])->name('editUser');
//     // Route::post('/user/{user}/update/', [UserDashboardController::class, 'updateUser'])->name('updateUser');

// });

Route::group(['middleware' => ['auth'], 'as' => 'user.', 'prefix' => 'mypanel'], function () {

    Route::get('/profile', [UserDashboardController::class, 'userDashboard'])->name('dashboard');
    Route::get('/location/set/', [UserDashboardController::class, 'locationSet'])->name('locationSet');
    Route::get('/edit/profile', [UserDashboardController::class, 'editUser'])->name('editUser');
    Route::post('/user/{user}/update/', [UserDashboardController::class, 'updateUser'])->name('updateUser');

    Route::post('/select/customer/ajax', [UserDashboardController::class, 'selectCustomer'])->name('selectCustomer');


    Route::get('/view/user/', [UserDashboardController::class, 'viewUser'])->name('viewUser');
});

Route::get('/location/set/{lat}/{lng}', [UserDashboardController::class, 'locationNameSet2'])->name('locationNameSet2');


Route::get('g-location', [LocationController::class, 'index'])->name('glocation');
Route::get('g-location/create', [LocationController::class, 'create'])->name('gCreate');
Route::post('g-location/store', [LocationController::class, 'store'])->name('gStore');
Route::get('g-location/{location}/edit', [LocationController::class, 'edit'])->name('gEdit');
Route::patch('g-location/{location}/update', [LocationController::class, 'update'])->name('gUpdate');
Route::delete('g-location/{location}/destroy', [LocationController::class, 'destroy'])->name('gDestroy');


Route::get('location/test', [LocationController::class, 'locationTest'])->name('locationTest');

Route::get('add-or-edit-customer-ajax/{customer?}', [GlobalController::class, 'addOrEditCustomer'])->name('global.addOrEditCustomer');
Route::get('product/all/Ajax', [GlobalController::class, 'productAllAjax'])->name('global.productAllAjax');
Route::get('customer/all/global/Ajax', [GlobalController::class, 'productAllGlobalAjax'])->name('global.productAllGlobalAjax');
Route::get('customer/office/ajax', [GlobalController::class, 'getCustomerOffice'])->name('global.getCustomerOffice');
Route::post('file/store', [GlobalImageController::class, 'store'])->name('global.fileStore');
Route::get('file/{file}/delete', [GlobalImageController::class, 'delete'])->name('global.fileDelete');
Route::get('my/employees/Ajax', [GlobalImageController::class, 'myEmployees'])->name('global.myEmployees');

Route::get(
    '/clear',
    function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');


        return redirect()->back();
    }

)->name('clear_cache');

Route::get('attandance/test', [GlobalImageController::class, 'attandanceTest'])->name('attandanceTest');
Route::get('send-mail', [GlobalImageController::class, 'sendMail'])->name('sendMail');
Route::get('test', [GlobalImageController::class, 'test'])->name('test');

