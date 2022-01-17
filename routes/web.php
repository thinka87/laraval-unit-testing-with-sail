<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ViewsController;

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
// TODO 1: test_home_screen_shows_welcome
// point the main "/" URL to the HomeController method "index"
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index']);

// TODO 2: test_about_page_is_loaded
// point the GET URL "/about" to the view
// resources/views/pages/about.blade.php - without any controller
// Also, assign the route name "about"
// Put one code line here below
Route::view('/about', 'pages.about')->name('about');

// TODO 3.1: test_task_crud_is_working
// group the following route sentences below in Route::group()
// Assign middleware "auth"
// Put one Route Group code line here below
Route::group(['middleware' => 'auth'], function () {
    // Tasks inside that Authenticated group:
    // TODO 3.2: test_task_crud_is_working
    // /app group within a group
    // Add another group for routes with prefix "app"
    // Put one Route Group code line here below
    Route::group(['prefix' => 'app'], function () {
        // Tasks inside that /app group:
        // TODO 3.3: test_task_crud_is_working
        // point URL /app/dashboard to a "Single Action" DashboardController
        // Assign the route name "dashboard"
        // Put one Route Group code line here below
        Route::get('dashboard', \App\Http\Controllers\DashboardController::class);
        
        // TODO 3.4: test_task_crud_is_working
        // Add ONE line to assign 7 resource routes to TaskController
        // Put one code line here below
        Route::resource('tasks', \App\Http\Controllers\TaskController::class);

    // End of the /app Route Group
    });
});

    // TODO 5.1: test_is_admin_middleware_is_working
    // /admin group within a group
    // Add a group for routes with URL prefix "admin"
    // Assign middleware called "is_admin" to them
    // Put one Route Group code line here below
    Route::group(['middleware' => 'is_admin','prefix' => '/admin'], function () {
        // Tasks inside that /admin group:

        // TODO 5.2: test_is_admin_middleware_is_working
        // point URL /admin/dashboard to a "Single Action" Admin/DashboardController
        // Put one code line here below
        Route::get('dashboard', \App\Http\Controllers\Admin\DashboardController::class);
        // TODO 5.3: test_is_admin_middleware_is_working
        // point URL /admin/stats to a "Single Action" Admin/StatsController
        // Put one code line here below
        Route::get('stats', \App\Http\Controllers\Admin\StatsController::class);
        // End of the /admin Route Group
// End of the main Authenticated Route Group
    });
// TODO 6: test_user_page_existing_user_found
// point the GET URL "/user/[name]" to the UserController method "show"
// It doesn't use Route Model Binding, it expects $name as a parameter
// Put one code line here below
Route::get('/user/{name}', [\App\Http\Controllers\UserController::class, 'show']);

// TODO 7.1: test_email_can_be_verified
// Task: this "/secretpage" URL should be visible only for those who VERIFIED their email
// Add some middleware here, and change some code in app/Models/User.php to enable this
Route::view('/secretpage', 'secretpage')->middleware('is_email_verified')
->name('secretpage');

// TODO 8: test_password_confirmation_page
// Task: this "/verysecretpage" URL should ask user for verifying their password once again
// You need to add some middleware here
Route::view('/verysecretpage', 'verysecretpage')
    ->name('verysecretpage')->middleware('password.confirm');




Route::get('tasks', [TaskController::class, 'index']);
Route::post('tasks', [TaskController::class, 'storeRelationship'])->middleware('auth');

Route::get('usersRel', [\App\Http\Controllers\UserController::class, 'index']);
Route::get('usersRel/{user}', [\App\Http\Controllers\UserController::class, 'showRelationship']);

Route::get('roles', [\App\Http\Controllers\RoleController::class, 'index']);

Route::get('teams', [\App\Http\Controllers\TeamController::class, 'index']);

Route::get('countries', [\App\Http\Controllers\CountryController::class, 'index']);

Route::get('attachments', [\App\Http\Controllers\AttachmentController::class, 'index']);

Route::post('projects', [\App\Http\Controllers\ProjectController::class, 'store'])->middleware('auth');


Route::prefix('views')->group(function () {
    Route::get('/alert', [ViewsController::class, 'alert'])->name('alert');
    Route::get('/table', [ViewsController::class, 'table'])->name('table');
    Route::get('/rows', [ViewsController::class, 'rows'])->name('rows');
    Route::view('/authenticated', 'authenticated')->name('authenticated');
});


require __DIR__.'/auth.php';
