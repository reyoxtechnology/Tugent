<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Agent\AgentController;
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

Route::middleware('auth:api')->group(function () {
    
    Route::get('/user',                [LoginController::class,'authenticatedUser']);
    Route::post('/Picture',            [LoginController::class,'Picture']);
    Route::get('/logout',              [LoginController::class,'logout']);
    Route::post('/editProfile/{id}',   [LoginController::class,'editProfile']);
    Route::get('/onlineAgent',         [UserController::class,'listOfOnlineAgent']);
    Route::get('/userChat/{id}',       [UserController::class,'attachmentRole']);
    Route::get('/ViewQuotation',       [UserController::class,'checkQuoatation']);
    Route::get('/acceptQuote',         [UserController::class,'acceptQuotation']);
    Route::post('/getUserById',        [UserController::class,'getUserById']);


    //agent endpoint
    Route::get('/user',                [LoginController::class,'authenticatedUser']);
    Route::get('/logout',              [LoginController::class,'logout']);
    Route::post('/editProfile/{id}',   [LoginController::class,'editProfile']);
    Route::post('/Picture/{id}',       [LoginController::class,'Picture']);
    Route::get('/active',              [AgentController::class,'ComeOnline']);
    Route::post('/quotation',          [AgentController::class, 'generateQuote']);
    Route::post('/takeLoan',           [AgentController::class,'takeLoan']);
    Route::post('/loanPayback',        [AgentController::class,'loanPayback']);

    


    // end point dashbord
    Route::get('/users',              [UserController::class,'getAlluser']); 
    Route::get('/agent',              [AgentController::class,'getAllAgent']);
    Route::get('/admin',              [AgentController::class,'getAllAgent']);
});



//login api configuration
Route::post('/login',      [LoginController::class,'login']);

//this api handles users registration 
Route::post('/user_registration',[RegisterController::class,'registerUser']);
// this handles agent api 
Route::post('/agent_registration',[RegisterController::class,'registerAgent']);
//this handles admin Dashbaord registration  on the web 
Route::post('/admin_registration',[RegisterController::class,'registerAdmin']);