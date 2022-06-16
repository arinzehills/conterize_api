<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\FreelancersController;
use App\Http\Controllers\PaymentController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user',[UserController::class, 'getCurrentUser']);
Route::post('/update', [UserController::class, 'update']);
Route::get('/logout', [UserController::class, 'logout']);
Route::post('/updatePassword',[UserController::class, 'updatePassword']);
Route::post('/forgotPassword',[UserController::class, 'forgotPassword']);
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->
                                            name('password.reset');

/* for company api */
Route::post('/addCompany', [CompanyController::class, 'addCompany']);
Route::post('/deleteCompany', [CompanyController::class, 'deleteCompany']);
Route::post('/getUserCompanies', [CompanyController::class, 'getUserCompanies']);
Route::post('/getUserCompanyDetail', [CompanyController::class, 'getUserCompanyDetail']);
Route::post('/getAllCompanies', [CompanyController::class, 'getAllCompanies']);

/* these is for client request for content */
Route::post('/addRequest', [RequestController::class, 'addRequest']);
Route::post('/getAllRequest', [RequestController::class, 'getAllRequest']);
Route::post('/getUserRequests', [RequestController::class, 'getUserRequests']);
Route::post('/getUserRequestDetail', [RequestController::class, 'getUserRequestDetail']);

/*admin correct request */
Route::post('/assignFreelancer', [FreelancersController::class, 'assignFreelancer']);


// this is for freelancers 
Route::post('/getAllFreelancers', [FreelancersController::class, 'getAllFreelancers']);
//for subscription
Route::post('/subscribe', [PaymentController::class, 'subscribe']);
Route::post('/createPlan', [PaymentController::class, 'createPlan']);
Route::post('/retrievePlans', [PaymentController::class, 'retrievePlans']);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});