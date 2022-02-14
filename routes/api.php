<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\LoanController;
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

Route::post('login', [LoginController::class, 'login'])->name('login');

Route::group(['namespace' => "API", 'middleware' => 'auth:api'], function(){
    
    // Store Loan Request and return loan application Id
    Route::post('/loan-request', [LoanController::class, 'addLoanRequest'])->name('addLoanRequest'); 
    
    // Approve Loan Request and fund disbursed by Admin
    Route::post('/loan-approval', [LoanController::class, 'approveLoanRequest'])->name('approveLoanRequest'); 
    
    //Repayment By Borrower
    Route::post('/loan-repay', [LoanController::class, 'repayLoanAmount'])->name('repayLoanAmount');
});
