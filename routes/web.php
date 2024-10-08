<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


// PUBLIC -----------------------------------------------------------------------------------
Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'show']);

Route::controller(App\Http\Controllers\Public\ContactController::class)->group(function () {
  Route::get('/contact', 'show');
  Route::get('/contactCreateEnquiry', 'createEnquiry');
});

Route::controller(App\Http\Controllers\Public\FeedbackController::class)->group(function () {
	Route::get('/feedback', 'show');
	Route::get('/feedbackCreateEnquiry', 'createEnquiry');
});

Route::controller(App\Http\Controllers\Public\FindUsController::class)->group(function () {
	Route::get('/find-us', 'show');
});

Route::controller(App\Http\Controllers\Public\MapController::class)->group(function () {
	Route::get('/map', 'show');
});

Route::controller(App\Http\Controllers\Public\ProgrammeController::class)->group(function () {
	Route::get('/programme', 'show');
});

Route::controller(App\Http\Controllers\Public\ScoutsController::class)->group(function () {
	Route::get('/scouts', 'show');
});

Route::controller(App\Http\Controllers\Public\SupportersController::class)->group(function () {
	Route::get('/supporters', 'show');
	Route::get('/supportersCreateEnquiry', 'createEnquiry');
});

Route::controller(App\Http\Controllers\Public\SitemapController::class)->group(function () {
	Route::get('/site-map', 'show');
});

// ADMIN -----------------------------------------------------------------------------------
Route::group( ['middleware' => 'auth' ], function()
{
	Route::controller(App\Http\Controllers\Admin\AuthController::class)->group(function () {
    Route::get('/admin', 'show');
    Route::get('/admin/forgot-password', 'forgotPassword');
		Route::post('/adminForgotPasswordEmail', 'forgotPasswordEmail');
    Route::get('/admin/reset-password/{email}/{token}', 'resetPassword');
		Route::post('/adminResetPassword/{email}/{token}', 'resetPassword2');
    Route::get('/adminLogin', 'authenticate');
    Route::get('/adminLogout', 'logout');
  });

  Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'show']);

	Route::controller(App\Http\Controllers\Admin\EnquiriesController::class)->group(function () {
		Route::get('/admin/enquiries', 'show');
    Route::post('/enquiriesSearch', 'search');
	});

	Route::controller(App\Http\Controllers\Admin\EnquiryProfileController::class)->group(function () {
		Route::get('/admin/enquiry-profile/{id}', 'show');
	});

  Route::controller(App\Http\Controllers\Admin\SettingsController::class)->group(function () {
    Route::get('/admin/settings', 'show');
    Route::post('/settingsUpdate', 'update');
  });

	// SYSTEM
	Route::controller(App\Http\Controllers\Admin\AccessLevelsController::class)->group(function () {
    Route::get('/admin/access-levels', 'show');
		Route::post('/accessLevelCreate', 'create');
		Route::get('/access-levelsDelete/{id}', 'delete');
  });
	Route::controller(App\Http\Controllers\Admin\AccessLevelProfileController::class)->group(function () {
    Route::get('/admin/access-level-profile/{id}', 'show');
  });

	Route::controller(App\Http\Controllers\Admin\CronJobsController::class)->group(function () {
    Route::get('/admin/cron-jobs', 'show');
  });

	Route::controller(App\Http\Controllers\Admin\CacheController::class)->group(function () {
    Route::get('/admin/cache', 'show');
    Route::get('/settingsClearCache/{key}', 'clearCache');
  });
	
	Route::controller(App\Http\Controllers\Admin\SecretsController::class)->group(function () {
    Route::get('/admin/secrets', 'show');
    Route::get('/admin/secrets/{secret}', 'show');
		Route::post('/admin-secretsSelect', 'select');
		Route::post('/admin-secretsUpdate/{secret}', 'update');
		Route::get('/admin-secretsDeployEnv', 'deployEnv');
  });

	Route::controller(App\Http\Controllers\Admin\UsersController::class)->group(function () {
    Route::get('/admin/users', 'show');
    Route::post('/usersCreate', 'create');
  });

  Route::controller(App\Http\Controllers\Admin\UserProfileController::class)->group(function () {
    Route::get('/admin/user-profile/{id}', 'show');
    Route::post('/user-profileUpdate/{id}', 'update');
    Route::get('/user-profileDelete/{id}', 'delete');
  });

	// WEBSITE
  Route::controller(App\Http\Controllers\Admin\HomePageController::class)->group(function () {
    Route::get('/admin/home-page', 'show');
		Route::post('/admin-home-pageAddLandingZoneBanner', 'addLandingZoneBanner');
		Route::get('/admin-home-pageDeleteLandingZoneBanner/{id}', 'deleteLandingZoneBanner');
		Route::post('/admin-home-pageUpdatePrimaryInfo', 'updatePrimaryInfo');
		Route::post('/admin-home-pageAddBottomBanner', 'addBottomBanner');
		Route::get('/admin-home-pageDeleteBottomBanner/{id}', 'deleteBottomBanner');
  });

  Route::controller(App\Http\Controllers\Admin\FindUsController::class)->group(function () {
    Route::get('/admin/find-us', 'show');
		Route::post('/admin-find-usAddGate', 'addGate');
		Route::get('/admin-find-usDeleteGate/{id}', 'deleteGate');
  });

  Route::controller(App\Http\Controllers\Admin\ContactController::class)->group(function () {
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

	Route::controller(App\Http\Controllers\Admin\MapController::class)->group(function () {
    Route::get('/admin/map', 'show');
		Route::get('/admin-mapToggleMap', 'toggleMap');
		Route::post('/admin-mapUploadMap', 'uploadMap');
		Route::post('/admin-mapAddIcon', 'addIcon');
		Route::get('/admin-mapDeleteIcon/{id}', 'deleteIcon');
		Route::post('/admin-mapSave', 'saveMap');
  });

	Route::controller(App\Http\Controllers\Admin\ProgrammeController::class)->group(function () {
		Route::get('/admin/programme', 'show');
		Route::post('/programmeCreateStandard', 'createStandard');
		Route::post('/programmeCreateMusic', 'createMusic');
		Route::get('/programmeDelete/{id}', 'delete');
	});

  Route::controller(App\Http\Controllers\Admin\ScoutsController::class)->group(function () {
    Route::get('/admin/scouts', 'show');
		Route::post('/admin-scoutsUpdateAboutUs', 'updateAboutUs');
  });

	Route::controller(App\Http\Controllers\Admin\SupportersController::class)->group(function () {
		Route::get('/admin/supporters', 'show');
		Route::post('/supportersCreate', 'create');
		Route::get('/supportersDelete/{id}', 'delete');
	});
	
	// API -----------------------------------------------------------------------------------
	Route::get('/header-toggleNotification/{id}/{notificationUserId}/{type}', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'toggleNotification']);
	Route::get('/header-reloadNotifications', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'reloadNotifications']);
	Route::get('/header-deleteNotification/{id}', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'deleteNotification']);
	Route::get('/header-deleteAllNotifications', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'deleteAllNotifications']);
	Route::get('/header-setNewNotificationVariable/{toggle}', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'setNewNotificationVariable']);
	Route::get('/header-setNotificationCount/{count}', [App\Http\Controllers\Admin\Api\HeaderApi::class, 'setNotificationCount']);
});


// API -----------------------------------------------------------------------------------
Route::get('/dataTable-toggleButton/{table}/{column}/{primaryColumn}/{primaryValue}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'toggleButton']);
Route::get('/dataTable-setPrimary/{table}/{column}/{primaryColumn}/{primaryValue}/{parent}/{parentId}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setPrimary']);
Route::get('/dataTable-selectDropdown/{table}/{column}/{primaryColumn}/{primaryValue}/{value}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'selectDropdown']);
Route::get('/dataTable-resetTableSequence/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'resetTableSequence']);
Route::get('/dataTable-moveSequence/{id}/{direction}/{tabelName}/{sequenceColumn}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'moveSequence']);

Route::get('/dataTable-setOrderColumn/{name}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setOrderColumn']);
Route::get('/dataTable-setOrderDirection/{direction}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'setOrderDirection']);

Route::get('/dataTable-changeLimit/{limit}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'changeLimit']);
Route::get('/dataTable-changePage/{offset}/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'changePage']);

Route::get('/dataTable-resetTableSequence/{sessionVariable}', [App\Http\Controllers\Common\Api\DataTableApi::class, 'resetTableSequence']);


// FUNCTIONS -----------------------------------------------------------------------------------
Route::get("sitemap-xml" , function () {
	return Illuminate\Support\Facades\Redirect::to('https://ipswich-fireworks.s3.eu-west-2.amazonaws.com/public-assets/sitemap.xml');
});

Route::get("/functions-setShowMarker/{section}" , function ($section = false) {
	if ($section == false || $section == session()->get('pageShowMarker', $section)) {
		session()->put('pageShowMarker', false);
		session()->put('pageShowMarkerPrevious', false);
	} else {
		session()->put('pageShowMarker', $section);
		session()->put('pageShowMarkerPrevious', session()->get('_previous')['url']);
	}
});