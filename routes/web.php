<?php

use Illuminate\Support\Facades\Route;

// Controllers

use App\Http\Controllers\UserController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\IqcInspectionController;



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

// Route::get('/link', function () {
//     return 'link';
// })->name('link');

Route::view('/','index')->name('login');
Route::view('/dashboard','dashboard')->name('dashboard');

// * ADMIN VIEW
Route::view('/user','user')->name('user');
Route::view('/ts_iqc_inspection','ts_iqc_inspection')->name('ts_iqc_inspection');

// Route::middleware('CheckSessionExist')->group(function(){
// });

Route::controller(IqcInspectionController::class)->group(function () {
    Route::get('/load_iqc_inspection', 'loadIqcInspection')->name('load_iqc_inspection');
    Route::get('/get_iqc_inspection_by_judgement', 'getIqcInspectionByJudgement')->name('get_iqc_inspection_by_judgement');
    Route::get('/load_whs_transaction', 'loadWhsTransaction')->name('load_whs_transaction');
    Route::get('/load_whs_details', 'loadWhsDetails')->name('load_whs_details');
    Route::get('/get_iqc_inspection_by_id', 'getIqcInspectionById')->name('get_iqc_inspection_by_id');
    Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    Route::get('/get_family', 'getFamily')->name('get_family');
    Route::get('/get_inspection_level', 'getInspectionLevel')->name('get_inspection_level');
    Route::get('/get_aql', 'getAql')->name('get_aql');
    Route::get('/get_lar_dppm', 'getLarDppm')->name('get_lar_dppm');
    Route::get('/get_mode_of_defect', 'getModeOfDefect')->name('get_mode_of_defect');
    Route::get('/view_coc_file_attachment/{id}', 'viewCocFileAttachment')->name('view_coc_file_attachment');

    Route::post('/save_iqc_inspection', 'saveIqcInspection')->name('save_iqc_inspection');
});


// USER CONTROLLER
Route::controller(UserController::class)->group(function () {
    // Route::get('/load_whs_transaction', 'loadWhsTransaction')->name('load_whs_transaction');
    Route::post('/sign_in', 'sign_in')->name('sign_in');
    Route::post('/rapidx_sign_in_admin', 'rapidx_sign_in_admin')->name('rapidx_sign_in_admin');
    Route::post('/sign_out', 'sign_out')->name('sign_out');
    Route::post('/change_pass', 'change_pass')->name('change_pass');
    Route::post('/change_user_stat', 'change_user_stat')->name('change_user_stat');
    Route::get('/view_users', 'view_users');
    Route::post('/add_user', 'add_user');
    Route::get('/get_user_by_id', 'get_user_by_id');
    Route::get('/get_user_by_en', 'get_user_by_en');
    Route::get('/get_user_list', 'get_user_list');
    Route::get('/get_user_by_batch', 'get_user_by_batch');
    Route::get('/get_user_by_stat', 'get_user_by_stat');
    Route::post('/edit_user', 'edit_user');
    Route::post('/reset_password', 'reset_password');
    Route::get('/generate_user_qrcode', 'generate_user_qrcode');
    Route::post('/import_user', 'import_user');

    Route::get('/get_emp_details_by_id', 'get_emp_details_by_id')->name('get_emp_details_by_id');
});
