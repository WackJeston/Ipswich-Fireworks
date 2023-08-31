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
use App\Http\Controllers\FindUsController;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\SupportersController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedbackController;

// ADMIN
use App\Http\Controllers\AdminTestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminProgrammeController;
use App\Http\Controllers\AdminSupportersController;
use App\Http\Controllers\AdminHomePageController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminCustomerProfileController;
use App\Http\Controllers\AdminEnquiriesController;
use App\Http\Controllers\AdminEnquiryProfileController;
use App\Http\Controllers\AdminFeedbackController;
use App\Http\Controllers\AdminFeedbackProfileController;



// DataTable -----------------------------------------------------------------------------------
Route::get('/dataTable-toggleButton/{table}/{column}/{primaryColumn}/{primaryValue}', [DataTableController::class, 'toggleButton']);
Route::get('/dataTable-setPrimary/{table}/{column}/{primaryColumn}/{primaryValue}/{parent}/{parentId}', [DataTableController::class, 'setPrimary']);


// SYSTEM -----------------------------------------------------------------------------------
Route::controller(TestController::class)->group(function () {
  Route::get('/test', 'show');
});


// PUBLIC -----------------------------------------------------------------------------------
Route::get('/', [HomeController::class, 'show']);

Route::controller(FindUsController::class)->group(function () {
	Route::get('/find-us', 'show');
});

Route::controller(ProgrammeController::class)->group(function () {
	Route::get('/programme', 'show');
});

Route::controller(SupportersController::class)->group(function () {
	Route::get('/supporters', 'show');
});

Route::controller(ContactController::class)->group(function () {
  Route::get('/contact', 'show');
  Route::get('/contactCreateEnquiry', 'createEnquiry');
});

Route::controller(FeedbackController::class)->group(function () {
	Route::get('/feedback', 'show');
	Route::get('/feedbackCreateEnquiry', 'createEnquiry');
});

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
    Route::post('/contactCreateUrl', 'createUrl');
    Route::get('/contactDeleteUrl/{id}', 'deleteUrl');
  });

  Route::controller(AdminHomePageController::class)->group(function () {
    Route::get('/admin/home-page', 'show');
		Route::post('/admin-home-pageAddLandingZoneBanner', 'addLandingZoneBanner');
		Route::get('/admin-home-pageDeleteLandingZoneBanner/{id}', 'deleteLandingZoneBanner');
		Route::post('/admin-home-pageUpdatePrimaryInfo', 'updatePrimaryInfo');
		Route::post('/admin-home-pageUpdateAboutUs', 'updateAboutUs');
  });

	Route::controller(AdminProgrammeController::class)->group(function () {
		Route::get('/admin/programme', 'show');
		Route::post('/programmeCreateStandard', 'createStandard');
		Route::post('/programmeCreateMusic', 'createMusic');
		Route::get('/programmeDelete/{id}', 'delete');
	});

	Route::controller(AdminSupportersController::class)->group(function () {
		Route::get('/admin/supporters', 'show');
		Route::post('/supportersCreate', 'create');
		Route::get('/supportersDelete/{id}', 'delete');
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

	Route::controller(AdminEnquiriesController::class)->group(function () {
		Route::get('/admin/enquiries', 'show');
	});

	Route::controller(AdminEnquiryProfileController::class)->group(function () {
		Route::get('/admin/enquiry-profile/{id}', 'show');
	});

	Route::controller(AdminFeedbackController::class)->group(function () {
		Route::get('/admin/feedback', 'show');
	});

	Route::controller(AdminFeedbackProfileController::class)->group(function () {
		Route::get('/admin/feedback-profile/{id}', 'show');
	});
});
