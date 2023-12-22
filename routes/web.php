<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('website/homes/index');
})->name('home.index');

Route::prefix('themes')->group(function () {
    
    Route::get('/employer', function () {
        return view('website/employer/index');
    })->name('employer.index');

    Route::get('/contacts', function () {
        return view('website/contacts/index');
    })->name('contacts.index');

    Route::get('/prices', function () {
        return view('website/prices/index');
    })->name('prices.index');

    Route::get('/dashboards', function () {
        return view('website/dashboards/index');
    })->name('dashboards.index');

    Route::get('/post-job', function () {
        return view('website/dashboards/postjob/index');
    })->name('postjob.index');

    Route::get('/manage-job', function () {
        return view('website/dashboards/managejob/index');
    })->name('managejob.index');

    Route::get('/aplicants', function () {
        return view('website/dashboards/aplicants/index');
    })->name('aplicants.index');

    Route::get('/Shortlisteds', function () {
        return view('website/dashboards/Shortlisteds/index');
    })->name('Shortlisteds.index');

    Route::get('/pakages', function () {
        return view('website/dashboards/pakages/index');
    })->name('pakages.index');

    Route::get('/messages', function () {
        return view('website/dashboards/messages/index');
    })->name('messages.index');

    Route::get('/cv-manager', function () {
        return view('website/dashboards/cv-manager/index');
    })->name('cv-manager.index');

    Route::get('/resume-alerts', function () {
        return view('website/dashboards/resume-alerts/index');
    })->name('resume-alerts.index');

    Route::get('/change-password', function () {
        return view('website/dashboards/change-password/index');
    })->name('change-password.index');

    Route::get('/profile', function () {
        return view('website/dashboards/profile/index');
    })->name('profile.index');
});