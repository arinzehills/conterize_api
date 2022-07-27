<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\FreelancersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Teamwork\TeamController;
use App\Http\Controllers\MyTeamMemberController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\DeliveriesController;

Route::get('/getAllUsers', [UserController::class, 'index']);
Route::get('/getCurrentUser', [UserController::class, 'getCurrentUser'])->middleware('last-seen');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login'])->middleware('last-seen');
Route::get('/user',[UserController::class, 'getCurrentUser']);
Route::post('/update', [UserController::class, 'update']);
Route::post('/updatePaymentStatus', [UserController::class, 'updatePaymentStatus']);
Route::get('/logout', [UserController::class, 'logout']);
Route::post('/updatePassword',[UserController::class, 'updatePassword']);
Route::post('/forgotPassword',[UserController::class, 'forgotPassword']);
Route::post('/resetPassword', [UserController::class, 'resetPassword'])->
                                            name('password.reset');
//this is for user teams
Route::post('/getUserTeams', [TeamController::class, 'index']);
Route::post('/addTeam', [TeamController::class, 'store']);
Route::post('/members', [MyTeamMemberController::class, 'show']);
Route::post('/deleteMember', [MyTeamMemberController::class, 'destroy']);
Route::post('/sendInvite', [MyTeamMemberController::class, 'invite']);
Route::post('/resendInvite', [MyTeamMemberController::class, 'resendInvite']);
Route::post('/deleteInvite', [MyTeamMemberController::class, 'denyInvite']);
Route::post('acceptInvite', [MyTeamMemberController::class, 'acceptInvite'])->middleware('last-seen')->name('accept_invite');

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
Route::post('/getUserTotalRequests', [RequestController::class, 'getUserTotalRequests']);
Route::post('/getUserRequestDetail', [RequestController::class, 'getUserRequestDetail']);

// deliveries apis
Route::post('/deliver', [DeliveriesController::class, 'deliver']);
Route::post('/getRequestDeliveries', [DeliveriesController::class,'getRequestDeliveries']);
Route::post('/acceptDelivery', [DeliveriesController::class,'acceptDelivery']);
Route::post('/requestRevision', [DeliveriesController::class,'requestRevision']);

/*admin correct request */
Route::post('/assignFreelancer', [FreelancersController::class, 'assignFreelancer']);


// this is for freelancers 
Route::post('/getAFreelancer', [FreelancersController::class, 'getAFreelancer']);
Route::post('/addFreelancer', [FreelancersController::class, 'addFreelancer'])->middleware('admin-activity');
Route::post('/approveFreelancer', [FreelancersController::class, 'approveFreelancer'])->middleware('admin-activity');
Route::post('/getAllFreelancers', [FreelancersController::class, 'getAllFreelancers']);
Route::post('/getApprovedFreelancers', [FreelancersController::class, 'getApprovedFreelancers']);
Route::post('/getUnApprovedFreelancers', [FreelancersController::class, 'getUnApprovedFreelancers']);
//for subscription
Route::post('/subscribe', [PaymentController::class, 'subscribe']);
Route::post('/createPlan', [PaymentController::class, 'createPlan']);
Route::post('/retrievePlans', [PaymentController::class, 'retrievePlans']);

Route::get("send-email", [EmailController::class, "sendEmail"]);
//this is for 
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