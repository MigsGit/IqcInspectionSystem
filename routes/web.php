<?php

use App\Models\Department;

// Controllers

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\IqcInspectionController;
use App\Http\Controllers\CnIqcInspectionController;
use App\Http\Controllers\YfIqcInspectionController;
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

Route::view('/','index')->name('index');
// Route::get('/{any}', function (Request $request) {
//     return view('dashboard');
// })->where('any', '.*');
Route::middleware('CheckSessionExist')->group(function(){
    Route::view('/dashboard','dashboard')->name('dashboard');
    // * ADMIN VIEW
    Route::view('/user','user')->name('user');
    Route::view('/ts_iqc_inspection','ts_iqc_inspection')->name('ts_iqc_inspection');
    Route::view('/ppd_iqc_inspection','ppd_iqc_inspection')->name('ppd_iqc_inspection');
    Route::view('/cn_iqc_inspection','cn_iqc_inspection')->name('cn_iqc_inspection');
    Route::view('/yf_iqc_inspection','yf_iqc_inspection')->name('yf_iqc_inspection');
    Route::view('/dropdown_maintenance','dropdown_maintenance')->name('dropdown_maintenance');
});

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
    }else{
        return false;
    }
});


Route::controller(IqcInspectionController::class)->group(function () {
    Route::get('/load_iqc_inspection', 'loadIqcInspection')->name('load_iqc_inspection');
    Route::get('/load_whs_packaging', 'loadWhsPackaging')->name('load_whs_packaging');
    Route::get('/load_whs_details', 'loadWhsDetails')->name('load_whs_details');
    Route::get('/load_yeu_details', 'loadYeuDetails')->name('load_yeu_details');

    Route::get('/get_ts_whs_packaging_by_id', 'getTsWhsPackagingById')->name('get_ts_whs_packaging_by_id');
    Route::get('/get_iqc_inspection_by_judgement', 'getIqcInspectionByJudgement')->name('get_iqc_inspection_by_judgement');
    Route::get('/get_iqc_inspection_by_id', 'getIqcInspectionById')->name('get_iqc_inspection_by_id');
    Route::get('/get_dropdown_details_by_opt_value', 'getDropdownDetailsByOptValue')->name('get_dropdown_details_by_opt_value');
    Route::get('/get_yeu_receiving_by_id', 'getYeuReceivingById')->name('get_yeu_receiving_by_id');

    Route::get('/get_mode_of_defects_by_id', 'getModeOfDefectsById')->name('get_mode_of_defects_by_id');

    Route::post('/save_iqc_inspection', 'saveIqcInspection')->name('save_iqc_inspection');
});
Route::controller(CnIqcInspectionController::class)->group(function () {
    Route::get('/load_cn_whs_packaging', 'loadCnWhsPackaging')->name('load_cn_whs_packaging');
    Route::get('/load_cn_iqc_inspection', 'loadCnIqcInspection')->name('load_cn_iqc_inspection');
    Route::get('/get_cn_whs_packaging_by_id', 'getCnWhsPackagingById')->name('get_cn_whs_packaging_by_id');
    Route::get('/get_cn_iqc_inspection_by_id', 'getCnIqcInspectionById')->name('get_cn_iqc_inspection_by_id');

    Route::post('/save_cn_iqc_inspection', 'saveCnIqcInspection')->name('save_cn_iqc_inspection');
});
Route::controller(PpdIqcInspectionController::class)->group(function () {
    Route::get('/load_whs_transaction', 'loadWhsTransaction')->name('load_whs_transaction');
    Route::get('/load_ppd_whs_packaging', 'loadPpdWhsPackaging')->name('load_ppd_whs_packaging');
    Route::get('/load_ppd_iqc_inspection', 'loadPpdIqcInspection')->name('load_ppd_iqc_inspection');
    Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    Route::get('/get_ppd_whs_packaging_by_id', 'getPpdWhsPackagingById')->name('get_ppd_whs_packaging_by_id');
    Route::get('/get_ppd_iqc_inspection_by_id', 'getPpdIqcInspectionById')->name('get_ppd_iqc_inspection_by_id');

    Route::post('/save_ppd_iqc_inspection', 'savePpdIqcInspection')->name('save_ppd_iqc_inspection');
});
Route::controller(YfIqcInspectionController::class)->group(function () {
    Route::get('/load_yf_whs_packaging', 'loadYfWhsPackaging')->name('load_yf_whs_packaging');
    Route::get('/load_yf_iqc_inspection', 'loadYfIqcInspection')->name('load_yf_iqc_inspection');
    Route::get('/get_yf_whs_packaging_by_id', 'getYfWhsPackagingById')->name('get_yf_whs_packaging_by_id');
    Route::get('/get_yf_iqc_inspection_by_id', 'getYfIqcInspectionById')->name('get_yf_iqc_inspection_by_id');

    Route::post('/save_yf_iqc_inspection', 'saveYfIqcInspection')->name('save_yf_iqc_inspection');
    // Route::get('/load_ppd_iqc_inspection', 'loadPpdIqcInspection')->name('load_ppd_iqc_inspection');
    // Route::get('/get_whs_receiving_by_id', 'getWhsReceivingById')->name('get_whs_receiving_by_id');
    // Route::get('/get_ppd_whs_packaging_by_id', 'getPpdWhsPackagingById')->name('get_ppd_whs_packaging_by_id');
    // Route::get('/get_ppd_iqc_inspection_by_id', 'getPpdIqcInspectionById')->name('get_ppd_iqc_inspection_by_id');
});


// USER CONTROLLER
Route::controller(UserController::class)->group(function () {
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
    Route::get('/check_department', 'check_department')->name('check_department');
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
    Route::get('/view_coc_file_attachment/{section}/{iqc_inspection_id}', 'viewCocFileAttachment')->name('view_coc_file_attachment');
});

//readDropdownDetailsByCategory

