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

// Route::get('/', function () {
//     return view('welcome');
// });

  	// Add new routes for admin login, forgot and reset password
Route::get('/',[App\Http\Controllers\Admin\Auth\LoginController::class,'showLoginForm']);
Route::get('/login',[App\Http\Controllers\Admin\Auth\LoginController::class,'showLoginForm'])->name('admin.login');
Route::post('/login/check',[App\Http\Controllers\Admin\Auth\LoginController::class,'adminLogin'])->name('admin.login.check');
Route::get('/forgot/password',[App\Http\Controllers\Admin\Auth\ForgotPasswordController::class,'showForgotPasswordForm'])->name('admin.forgotpassword');
Route::post('/password/email',[App\Http\Controllers\Admin\Auth\ForgotPasswordController::class,'sendResetLinkEmail'])->name('admin.password.eamil');
Route::get('/password/reset/{token}/{email}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class,'showResetPasswordForm'])->name('admin.password.token');
Route::post('/password/reset',[App\Http\Controllers\Admin\Auth\ResetPasswordController::class,'reset'])->name('admin.password.reset');

// Add new route for 'admin' middleware
Route::group(['middleware' => ['admin']],function(){

	// Logout routes
    Route::post('/logout',[App\Http\Controllers\Admin\Auth\LoginController::class,'logout']);
	Route::get('/logout',[App\Http\Controllers\Admin\Auth\LoginController::class,'logout'])->name('admin.logout');

	// Super Admin Module Routes
	Route::get('/dashboard',[App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard');

	// Change Profile routes
	Route::get('/edit-profile',[App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('admin.editprofile');
	Route::post('/update-profile',[App\Http\Controllers\Admin\AdminController::class, 'updateProfile'])->name('admin.updateprofile');

	// Change password routes
	Route::get('/change-password',[App\Http\Controllers\Admin\AdminController::class, 'getChangePass'])->name('admin.changepass');
	Route::post('/change-password',[App\Http\Controllers\Admin\AdminController::class, 'changePass'])->name('admin.updatechangepass');

	// Settings routes
	Route::get('/settings',[App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('admin.settings');
	Route::post('/save-settings',[App\Http\Controllers\Admin\AdminController::class, 'saveSettings'])->name('admin.save.settings');

	// Clinic User Module Routes
	Route::post('/user/list/data',[App\Http\Controllers\Admin\UserController::class, 'listdata'])->name('admin.user.listdata');
	Route::get('/user/import',[App\Http\Controllers\Admin\UserController::class, 'import'])->name('admin.user.import');
	Route::post('/user/importdata',[App\Http\Controllers\Admin\UserController::class, 'importdata'])->name('admin.user.importdata');
	Route::resource('/user', App\Http\Controllers\Admin\UserController::class, [
	    'names' => [
	        'index' => 'admin.user.list',
	        'create'=> 'admin.user.create',
	        'store'=> 'admin.user.store',
	        'edit' => 'admin.user.edit',
	        'update' => 'admin.user.update',
	        'destroy' => 'admin.user.destroy',
	        'show' => 'admin.user.show'
	    ]
	]);

	// Patient Module Routes
	Route::post('/patient/list/data',[App\Http\Controllers\Admin\PatientController::class, 'listdata'])->name('admin.patient.listdata');
	Route::get('/patient/import',[App\Http\Controllers\Admin\PatientController::class, 'import'])->name('admin.patient.import');
	Route::post('/patient/importdata',[App\Http\Controllers\Admin\PatientController::class, 'importdata'])->name('admin.patient.importdata');
	Route::get('/patient',[App\Http\Controllers\Admin\PatientController::class, 'index'])->name('admin.patient.list');
	Route::get('/patient/create',[App\Http\Controllers\Admin\PatientController::class, 'create'])->name('admin.patient.create');
	Route::post('/patient/store',[App\Http\Controllers\Admin\PatientController::class, 'store'])->name('admin.patient.store');
	Route::get('/patient/edit/{id}',[App\Http\Controllers\Admin\PatientController::class, 'edit'])->name('admin.patient.edit');
	Route::put('/patient/update/{id}',[App\Http\Controllers\Admin\PatientController::class, 'update'])->name('admin.patient.update');
	Route::delete('/patient/destroy/{id}',[App\Http\Controllers\Admin\PatientController::class, 'destroy'])->name('admin.patient.destroy');
	Route::get('/patient/show/{id}',[App\Http\Controllers\Admin\PatientController::class, 'show'])->name('admin.patient.show');

	// Reporting routes
	Route::get('/reporting/list',[App\Http\Controllers\Admin\ReportingController::class, 'reportingList'])->name('admin.reporting.list');
	Route::get('/get-patient',[App\Http\Controllers\Admin\ReportingController::class, 'reportingGetPatient'])->name('admin.reporting.get.patient');
	Route::get('/get-clinic_user',[App\Http\Controllers\Admin\ReportingController::class, 'reportingGetClinicUser'])->name('admin.reporting.get.clinic_user');
	Route::post('/reporting/list-data',[App\Http\Controllers\Admin\ReportingController::class, 'listData'])->name('admin.reporting.list.data');
	Route::get('/reporting/product-list/{session_id}/{cust_id?}/{clinic_user_id?}/{start_date?}/{end_date?}',[App\Http\Controllers\Admin\ReportingController::class, 'productList'])->name('admin.reporting.product.list');
	Route::post('/reporting/product-list-data',[App\Http\Controllers\Admin\ReportingController::class, 'productListData'])->name('admin.reporting.product.list.data');
	Route::get('/reporting/product-details/{product_id}',[App\Http\Controllers\Admin\ReportingController::class, 'productDetails'])->name('admin.reporting.product.details');

});
Route::get('/reset-app',function(){
    Artisan::call("config:cache");
    Artisan::call("config:clear");
    Artisan::call("cache:clear");
    Artisan::call("route:clear");
    Artisan::call("view:clear");
    echo "<h2> Success </h2>";
});