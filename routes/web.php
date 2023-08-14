<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\AuthController;

//DataTable
use App\Http\Controllers\DataTableController;

// SYSTEM
use App\Http\Controllers\TestController;

// PUBLIC
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;

// ADMIN
use App\Http\Controllers\AdminTestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminCustomerProfileController;
use App\Http\Controllers\AdminHomePageController;


// DataTable -----------------------------------------------------------------------------------
Route::get('/dataTable-toggleButton/{table}/{column}/{primaryColumn}/{primaryValue}', [DataTableController::class, 'toggleButton']);
Route::get('/dataTable-setPrimary/{table}/{column}/{primaryColumn}/{primaryValue}/{parent}/{parentId}', [DataTableController::class, 'setPrimary']);


// SYSTEM -----------------------------------------------------------------------------------
Route::controller(TestController::class)->group(function () {
  Route::get('/test', 'show');
});


// PUBLIC -----------------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'show']);
Route::get('/contact', [ContactController::class, 'show']);

// ADMIN -----------------------------------------------------------------------------------
Route::group( ['middleware' => 'auth' ], function()
{
	Route::view('/admin', 'admin/auth/login');
  Route::controller(AuthController::class)->group(function () {
    Route::get('/adminLogin', 'authenticateAdmin');
    Route::get('/adminLogout', 'logoutAdmin');
  });

	Route::get('/admin/test', [AdminTestController::class, 'show']);

  Route::get('/admin/dashboard', [AdminDashboardController::class, 'show']);

  Route::controller(AdminContactController::class)->group(function () {
    Route::get('/admin/contact', 'show');
    Route::post('/contactUpdateAddress', 'updateAddress');
    Route::get('/contactUploadLatLng/{lat}/{lng}', 'uploadLatLng');
    Route::post('/contactCreateEmail', 'createEmail');
    Route::get('/contactDeleteEmail/{id}', 'deleteEmail');
    Route::post('/contactCreatePhone', 'createPhone');
    Route::get('/contactDeletePhone/{id}', 'deletePhone');
  });

  Route::controller(AdminUsersController::class)->group(function () {
    Route::get('/admin/users', 'show');
    Route::post('/usersCreate', 'create');
  });

  Route::controller(AdminUserProfileController::class)->group(function () {
    Route::get('/admin/user-profile/{id}', 'show');
    Route::post('/user-profileUpdate/{id}', 'update');
    Route::get('/user-profileDelete/{id}', 'delete');
  });

  Route::controller(AdminCustomersController::class)->group(function () {
    Route::get('/admin/customers', 'show');
    Route::post('/customersCreate', 'create');
  });

  Route::controller(AdminCustomerProfileController::class)->group(function () {
    Route::get('/admin/customer-profile/{id}', 'show');
    Route::post('/customer-profileUpdate/{id}', 'update');
    Route::get('/customer-profileDelete/{id}', 'delete');
  });

  Route::controller(AdminHomePageController::class)->group(function () {
    Route::get('/admin/home-page', 'show');
		Route::post('/admin-home-pageAddLandingZoneBanner', 'addLandingZoneBanner');
		Route::get('/admin-home-pageDeleteLandingZoneBanner/{id}', 'deleteLandingZoneBanner');
  });
});
