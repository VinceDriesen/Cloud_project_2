<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\user\restaurant\ReserverenController;
use App\Http\Controllers\user\restaurant\RestaurantHomeController;
use App\Http\Controllers\user\calendar\doctor\AppointmentController;
use App\Http\Controllers\user\HospitalDataController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Softonic\GraphQL\ClientBuilder;

Route::get('/', function () {
    return view('home');
});

Route::post('/change-theme', [App\Http\Controllers\ThemeController::class, 'changeTheme']);

Route::get('register', [RegisterController::class, 'showRegistrationForm']);
Route::post('register', [RegisterController::class, 'register']);
Route::get('login', [LoginController::class, 'showLoginForm']);
Route::post('login', [LoginController::class, 'login']);

Route::get('/signout', [App\Http\Controllers\Auth\SignoutController::class, 'index']);
Route::post('/signout', [App\Http\Controllers\Auth\SignoutController::class, 'signout']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/profileSettings', [\App\Http\Controllers\user\ProfileSettingsController::class, 'index']);
Route::get('/dashboard', [\App\Http\Controllers\user\DashboardController::class, 'index']);
Route::post('/updateProfile', [\App\Http\Controllers\user\ProfileSettingsController::class, 'updateProfile']);

Route::get('/doctor/login', [\App\Http\Controllers\Auth\DoctorLoginController::class, 'index']);
Route::post('/doctor/login', [\App\Http\Controllers\Auth\DoctorLoginController::class, 'login']);

Route::get('/doctor/register', [\App\Http\Controllers\Auth\DoctorRegisterController::class, 'index']);
Route::post('/doctor/register', [\App\Http\Controllers\Auth\DoctorRegisterController::class, 'register']);

Route::get('/doctor/calendar/newAppointment', [\App\Http\Controllers\user\calendar\doctor\NewAppointmentController::class, 'index']);
Route::post('/doctor/calendar/newAppointment', [\App\Http\Controllers\user\calendar\doctor\NewAppointmentController::class, 'createNewAppointment']);


Route::get('/calendar', [\App\Http\Controllers\user\calendar\CalendarController::class, 'index'])->name('calendar');
Route::post('/fullcalenderAjax', [\App\Http\Controllers\user\calendar\CalendarController::class, 'ajax']);
Route::get('/calendar/editevent', [\App\Http\Controllers\user\calendar\EditEventController::class, 'index']);
Route::post('/calendar/editevent', [\App\Http\Controllers\user\calendar\EditEventController::class, 'editEvent']);
Route::get('/calendar/newevent', [\App\Http\Controllers\user\calendar\NewEventController::class, 'index']);
Route::post('/calendar/newevent', [\App\Http\Controllers\user\calendar\NewEventController::class, 'saveEvent']);
Route::get('/calendar/scheduleAppointment', [\App\Http\Controllers\user\calendar\ScheduleAppointmentController::class, 'index']);
Route::post('/calendar/scheduleAppointment', [\App\Http\Controllers\user\calendar\ScheduleAppointmentController::class, 'scheduleAppointment']);

// Haal de beschikbare dagen op voor de geselecteerde dokter
Route::get('appointments/doctor/{doctorId}', [AppointmentController::class, 'getAppointmentsFromDoctor']);

Route::get('/hospital-data', [HospitalDataController::class, 'index']);

//Hier komt alles van het restaurant
Route::get('/restaurant/reserveren', [ReserverenController::class, 'index']);
Route::get('/restaurant', [RestaurantHomeController::class, 'index']);
Route::get('/fetchTables', [ReserverenController::class, 'fetchTables'])->name('fetchTables');
Route::post('/restaurant/reserveTable', [ReserverenController::class, 'reserveTable'])->name('reserveTable');
