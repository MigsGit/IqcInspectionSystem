<?php

use Illuminate\Support\Facades\Route;

// Controllers

use App\Http\Controllers\UserController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\IqcInspectionController;
use App\Http\Controllers\PpdIqcInspectionController;




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

Route::view('/','dashboard')->name('dashboard');
Route::middleware('CheckSessionExist')->group(function(){
    Route::view('/dashboard','dashboard')->name('dashboard');
    // * ADMIN VIEW
    Route::view('/user','user')->name('user');
    Route::view('/ts_iqc_inspection','ts_iqc_inspection')->name('ts_iqc_inspection');
    Route::view('/dropdown_maintenance','dropdown_maintenance')->name('dropdown_maintenance');
});
// Route::middleware('CheckSessionExist')->group(function(){
// });

Route::get('check_user', function (Request $request) {
    session_start();
    if($_SESSION){
        session([
            'rapidx_user_id' => $_SESSION["rapidx_user_id"],
            'rapidx_name' => $_SESSION["rapidx_name"],
            'rapidx_username' => $_SESSION["rapidx_username"],
            'rapidx_user_level_id' => $_SESSION["rapidx_user_level_id"],
            'rapidx_email' => $_SESSION["rapidx_email"],
            'rapidx_department_id' => $_SESSION["rapidx_department_id"],
            'rapidx_employee_number' => $_SESSION["rapidx_employee_number"],

        ]);
        // return session()->all();
        // return session('rapidx_employee_number');
        return true;
    }
    else{
        return false;
    }
});

Route::controller(IqcInspectionController::class)->group(function () {
    Route::get('/load_iqc_inspection', 'loadIqcInspection')->name('load_iqc_inspection');
    Route::get('/load_whs_packaging', 'loadWhsPackaging')->name('load_whs_packaging');
    Route::get('/load_whs_details', 'loadWhsDetails')->name('load_whs_details');
    Route::get('/load_yeu_details', 'loadYeuDetails')->name('load_yeu_details');

    Route::get('/get_ts_whs_receving_packaging_by_id', 'getTsWhsRecevingPackagingById')->name('get_ts_whs_receving_packaging_by_id');
    Route::get('/get_iqc_inspection_by_judgement', 'getIqcInspectionByJudgement')->name('get_iqc_inspection_by_judgement');
    Route::get('/get_iqc_inspection_by_id', 'getIqcInspectionById')->name('get_iqc_inspection_by_id');
    Route::get('/view_coc_file_attachment/{id}', 'viewCocFileAttachment')->name('view_coc_file_attachment');
    Route::get('/get_dropdown_details_by_opt_value', 'getDropdownDetailsByOptValue')->name('get_dropdown_details_by_opt_value');
    Route::get('/get_yeu_receiving_by_id', 'getYeuReceivingById')->name('get_yeu_receiving_by_id');
    
    Route::post('/save_iqc_inspection', 'saveIqcInspection')->name('save_iqc_inspection');
});
Route::controller(PpdIqcInspectionController::class)->group(function () {
    Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    Route::get('/load_whs_transaction', 'loadWhsTransaction')->name('load_whs_transaction');
    // Route::get('/load_iqc_inspection', 'loadIqcInspection')->name('load_iqc_inspection');
    // Route::get('/load_whs_details', 'loadWhsDetails')->name('load_whs_details');
    // Route::get('/load_yeu_details', 'loadYeuDetails')->name('load_yeu_details');
    // Route::get('/get_iqc_inspection_by_judgement', 'getIqcInspectionByJudgement')->name('get_iqc_inspection_by_judgement');
    // Route::get('/get_iqc_inspection_by_id', 'getIqcInspectionById')->name('get_iqc_inspection_by_id');
    // Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    // Route::get('/view_coc_file_attachment/{id}', 'viewCocFileAttachment')->name('view_coc_file_attachment');
    // Route::get('/get_dropdown_details_by_opt_value', 'getDropdownDetailsByOptValue')->name('get_dropdown_details_by_opt_value');
    // Route::get('/get_yeu_receiving_by_id', 'getYeuReceivingById')->name('get_yeu_receiving_by_id');

    // Route::post('/save_iqc_inspection', 'saveIqcInspection')->name('save_iqc_inspection');
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
Route::controller(SettingController::class)->group(function () {
    Route::get('/read_dropdown_category', 'readDropdownCategory')->name('read_dropdown_category');
    Route::get('/read_dropdown_details_by_category', 'readDropdownDetailsByCategory')->name('read_dropdown_details_by_category');
    Route::get('/read_dropdown_category_by_id', 'readDropdownCategoryById')->name('read_dropdown_category_by_id');
    Route::get('/read_dropdown_details_by_id', 'readDropdownDetailsById')->name('read_dropdown_details_by_id');

    Route::post('/save_dropdown_category_by_id', 'saveDropdownCategoryById')->name('save_dropdown_category_by_id');
    Route::post('/save_dropdown_details_by_id', 'saveDropdownDetailsById')->name('save_dropdown_details_by_id');
});

Route::controller(CommonController::class)->group(function () {
    Route::get('/get_sampling_size_by_sampling_plan', 'getSamplingSizeBySamplingPlan')->name('get_sampling_size_by_sampling_plan');
});

//readDropdownDetailsByCategory

