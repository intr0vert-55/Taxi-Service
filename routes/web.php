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


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;


Route::get('/', function () {
    return redirect() -> route('home');
});

Auth::routes();

Route::get('/driver',[LoginController::class,'showDriverLoginForm'])->name('driver.login-view');
Route::post('/driver',[LoginController::class,'driverLogin'])->name('driver.login');

Route::get('/driver/register',[RegisterController::class,'showDriverRegisterForm'])->name('driver.register-view');
Route::post('/driver/register',[RegisterController::class,'createDriver'])->name('driver.register');




// Route::get('/driver/dashboard',function(Request $request){
//     return view('driver');
// })->middleware('auth:driver');

Route::group(['prefix' => 'driver', 'middleware' => 'auth:driver'], function(){
    Route::get('dashboard', [DriverController::class, 'index']) -> name('driver.dashboard');
    Route::get('/rides', [DriverController::class, 'allRides']) -> name('driver.rides');
    Route::get('/ride/{id}',[DriverController::class, 'rideDetails'])->name('driver.ride');
    Route::get('/takeride/{id}',[DriverController::class, 'takeRide'])->name('driver.takeride');
    Route::get('/profile',[DriverController::class, 'profile'])->name('driver.profile');
    Route::get('/profile/edit',[DriverController::class, 'profileEditPage'])->name('driver.editProfile');
    Route::post('/profile/edit',[DriverController::class, 'edit'])->name('driver.edit');
});

Route::get('/home', [UserController::class, 'index'])->name('home') -> middleware('auth');

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function(){
    Route::post('/ride',[UserController::class, 'ride'])->name('user.ride');
    Route::get('/ride/{id}',[UserController::class, 'rideInfo'])->name('user.rideinfo');
    Route::get('/rides',[UserController::class, 'ridesList'])->name('user.rideslist');
    Route::post('/ride/review',[UserController::class, 'review'])->name('user.review');
    Route::post('/ride/pay', [UserController::class, 'pay'])->name('user.pay');
    Route::get('/profile',[UserController::class, 'profile'])->name('user.profile');
    Route::get('/profile/edit', [UserController::class, 'profileEditPage'])->name('user.editProfile');
    Route::post('/profile/edit', [UserController::class, 'edit'])->name('user.edit');
});

// Route::get('/mail', function(){
//     $ride = App\Models\Ride::with('driverInfo','userInfo')->find(1);
//     // return view('newride', compact('ride'));
//     return view('rideaccepted', compact('ride'));
// });

// Route::get('rides',[DriverController::class, 'rides']);
