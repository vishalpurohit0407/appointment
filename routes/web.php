<?php

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

Route::get('/', function () {
    return redirect(route('login'));
});

Auth::routes();

// Add new route for 'user' middleware
Route::group(['middleware' => ['auth']],function(){

	// Super Admin Module Routes
	Route::get('/dashboard', [App\Http\Controllers\clinic_user\ClinicUserController::class, 'index'])->name('dashboard');

	// Change Profile routes
	Route::get('/edit-profile',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'profile'])->name('editprofile');
	Route::post('/update-profile',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'updateProfile'])->name('updateprofile');

	// Change password routes
	Route::get('/change-password',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'getChangePass'])->name('changepass');
	Route::post('/change-password',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'changePass'])->name('updatechangepass');

	// Reporting routes
	Route::get('/reporting/list',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'reportingList'])->name('reporting.list');
	Route::get('/get-patient',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'reportingGetPatient'])->name('reporting.get.patient');
	Route::post('/reporting/list-data',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'listData'])->name('reporting.list.data');
	Route::get('/reporting/product-list/{session_id}/{cust_id?}/{start_date?}/{end_date?}',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'productList'])->name('reporting.product.list');
	Route::post('/reporting/product-list-data',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'productListData'])->name('reporting.product.list.data');
	Route::get('/reporting/product-details/{product_id}',[App\Http\Controllers\clinic_user\ClinicUserController::class, 'productDetails'])->name('reporting.product.details');

	// Patient Module Routes
	Route::post('/patient/list/data',[App\Http\Controllers\clinic_user\PatientController::class, 'listdata'])->name('patient.listdata');
	Route::get('/patient/import',[App\Http\Controllers\clinic_user\PatientController::class, 'import'])->name('patient.import');
	Route::post('/patient/importdata',[App\Http\Controllers\clinic_user\PatientController::class, 'importdata'])->name('patient.importdata');
	Route::post('/patient/save-appointment',[App\Http\Controllers\clinic_user\PatientController::class, 'saveAppointment'])->name('patient.save-appointment');

	Route::get('/patient',[App\Http\Controllers\clinic_user\PatientController::class, 'index'])->name('patient.list');
	Route::get('/patient/create',[App\Http\Controllers\clinic_user\PatientController::class, 'create'])->name('patient.create');
	Route::post('/patient/store',[App\Http\Controllers\clinic_user\PatientController::class, 'store'])->name('patient.store');
	Route::get('/patient/edit/{id}',[App\Http\Controllers\clinic_user\PatientController::class, 'edit'])->name('patient.edit');
	Route::put('/patient/update/{id}',[App\Http\Controllers\clinic_user\PatientController::class, 'update'])->name('patient.update');
	Route::delete('/patient/destroy/{id}',[App\Http\Controllers\clinic_user\PatientController::class, 'destroy'])->name('patient.destroy');
	Route::get('/patient/show/{id}',[App\Http\Controllers\clinic_user\PatientController::class, 'show'])->name('patient.show');

	// Patient Report
	Route::get('/patient-report',[App\Http\Controllers\clinic_user\PatientReportController::class, 'index'])->name('patient-report.list');
	Route::post('/patient-report/list/data',[App\Http\Controllers\clinic_user\PatientReportController::class, 'listdata'])->name('patient-report.listdata');
	Route::delete('/patient-report/destroy/{id}',[App\Http\Controllers\clinic_user\PatientReportController::class, 'destroy'])->name('patient-report.destroy');
	Route::get('/patient-report/show/{id}',[App\Http\Controllers\clinic_user\PatientReportController::class, 'show'])->name('patient-report.show');
	Route::post('/patient/save-report',[App\Http\Controllers\clinic_user\PatientReportController::class, 'saveReport'])->name('patient.save-report');
	Route::post('/patient/send-report',[App\Http\Controllers\clinic_user\PatientReportController::class, 'sendReport'])->name('patient.send-report');

	// Appointments
	Route::post('/appointment/list/data',[App\Http\Controllers\clinic_user\PatientReportController::class, 'appointmentlistdata'])->name('appointment.listdata');
	Route::delete('/appointment/destroy/{id}',[App\Http\Controllers\clinic_user\PatientReportController::class, 'appointmentdestroy'])->name('appointment.destroy');
	Route::get('/appointment/show/{id}',[App\Http\Controllers\clinic_user\PatientReportController::class, 'appointmentshow'])->name('appointment.show');
});


//Script Route
Route::get('/api/script/cust', [App\Http\Controllers\Admin\CronController::class, 'scriptForCustInfoTransSummary']);
Route::get('/api/script/cust-part-summary', [App\Http\Controllers\Admin\CronController::class, 'scriptForCustPartSummaryPriceBook']);
Route::get('/api/script/part', [App\Http\Controllers\Admin\CronController::class, 'scriptForPart']);
Route::get('/api/script/part-image', [App\Http\Controllers\Admin\CronController::class, 'scriptForPartImage']);
Route::get('/api/script/export_database', [App\Http\Controllers\Admin\CronController::class, 'export_json_database_to_storage']);
Route::get('/api/script/server-time', [App\Http\Controllers\Admin\CronController::class, 'scriptForServerTime']);
Route::get('/api/script/status', [App\Http\Controllers\Admin\CronController::class, 'scriptRunningStatus']);
Route::get('/api/script/check', [App\Http\Controllers\Admin\CronController::class, 'scriptCheck']);
