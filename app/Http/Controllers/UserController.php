<?php

namespace App\Http\Controllers;

use Auth;
use Mail;
use QrCode;
use DataTables;
use App\Models\User;
use App\Model\OQCStamp;
use App\Models\Department;
use App\Models\RapidxUser;
use App\Models\HRISDetails;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
// use App\Jobs\SendUserPasswordJob;
use App\Imports\CSVUserImport;
use App\Models\RapidXUserAccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function rapidx_sign_in_admin(Request $request)
    {
        $user_data = $request->all();
        $validator = Validator::make($request->all(), [
            'username' => 'required',
        ]);

        if ($validator->passes()) {
            $user_info = RapidxUser::where('username', $request->username)->first();
            // return $user_info;
            if ($user_info != null) {
                session_start();
                $_SESSION["rapidx_user_id"] = $user_info->id;
                $_SESSION["rapidx_user_level_id"] = $user_info->user_level_id;
                $_SESSION["rapidx_username"] = $user_info->username;
                $_SESSION["rapidx_name"] = $user_info->name;
                $_SESSION["rapidx_email"] = $user_info->email;
                $_SESSION["rapidx_department_id"] = $user_info->department_id;
                $_SESSION["rapidx_employee_number"] =  $user_info->employee_number;

                $user_accesses = RapidXUserAccess::on('rapidx')->where('user_id', $user_info->id)
                    ->where('user_access_stat', 1)
                    ->get();

                $arr_user_accesses = [];
                for ($index = 0; $index < count($user_accesses); $index++) {
                    array_push($arr_user_accesses, array(
                        'module_id' => $user_accesses[$index]->module_id,
                        'user_level_id' => $user_accesses[$index]->user_level_id
                    ));
                }

                $_SESSION["rapidx_user_accesses"] = $arr_user_accesses;

                return response()->json([
                    'result' => "1",
                ]);
            } else {
                return response()->json(['result' => "0", 'error' => 'Login Failed!']);
            }
        } else {
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Sign In
    public function sign_in(Request $request){
        // return "qwe";
        $user_data = array(
            'username' => $request->get('username'),
            'password' => $request->get('password'),
            'status' => "1"
        );
        // return $user_data['username'];
        $validator = Validator::make($user_data, [
            'username' => 'required',
            // 'password' => 'required|alphaNum|min:8'
            'password' => 'required|alphaNum|min:6'
        ]);
        if($validator->passes()){

            if(Auth::attempt($user_data)){
                session_start();
                $_SESSION["user_id"] = Auth::user()->id;
                $_SESSION["user_level_id"] = Auth::user()->user_level_id;
                $_SESSION["username"] = Auth::user()->username;
                $_SESSION["email"] = Auth::user()->email;
                $_SESSION["position"] = Auth::user()->position;
                $_SESSION["employee_id"] =  Auth::user()->employee_id;

                $request->session()->put('user_id', Auth::user()->id);
                $request->session()->put('position', Auth::user()->position);
                $request->session()->put('employee_id', Auth::user()->employee_id);
                $request->session()->put('email', Auth::user()->email);

                if(Auth::user()->is_password_changed == 0){
                    return response()->json(['result' => "2"]);
                }
                else{
                    return response()->json(['result' => "1", 'username' => $user_data['username']]);
                }
            }
            else{
                return response()->json(['result' => "0", 'error_message' => 'Login Failed!', 'error' => $validator->messages()]);
            }
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Sign Out
    public function sign_out(Request $request){
        // session_start();
        // session_unset();
        // session_destroy();
        $request->session()->forget('user_id');
        $request->session()->forget('position');
        $request->session()->forget('employee_id');
        $request->session()->forget('email');
        Auth::logout();
        return response()->json(['result' => "1"]);
    }

    // Change Password
    public function change_pass(Request $request){
        date_default_timezone_set('Asia/Manila');
        $user_data = array(
            'username' => $request->username,
            'password' => $request->password,
            'new_password' => $request->new_password,
            'confirm_password' => $request->confirm_password,
        );

        $validator = Validator::make($user_data, [
            'username' => 'required',
            'password' => 'required|min:8',
            // 'new_password' => 'required|min:8|required_with:confirm_password|same:confirm_password',
            // 'confirm_password' => 'required|min:8'
            'new_password' => 'required|min:6|required_with:confirm_password|same:confirm_password', //10022023 by nessa
            'confirm_password' => 'required|min:6'
        ]);

        if($validator->passes()){

            if(Auth::attempt($user_data)){
                try{
                    User::where('id', Auth::user()->id)
                        ->increment('update_version', 1,
                            [
                                'is_password_changed' => 1,
                                'password' => Hash::make($request->new_password),
                                'last_updated_by' => Auth::user()->id,
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]
                        );
                    DB::commit();
                    return response()->json(['result' => "1"]);
                }
                catch(\Exception $e) {
                    DB::rollback();
                    // throw $e;
                    return response()->json(['result' => "0"]);
                }

                return response()->json(['result' => 1]);
            }
            else{
                return response()->json(['result' => "0", 'error' => 'Login Failed!']);
            }
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Change User Status
    public function change_user_stat(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            try{
                User::where('id', $request->user_id)
                    ->increment('update_version', 1,
                        [
                            'status' => $request->status,
                            'last_updated_by' => Auth::user()->id,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]
                    );
                DB::commit();
                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }

            return response()->json(['result' => 1]);
        }
        else{
            return response()->json(['result' => "0", 'error' => $validator->messages()]);
        }
    }

    // Reset Password
    public function reset_password(Request $request){
        date_default_timezone_set('Asia/Manila');

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        try{
            User::where('id', $request->user_id)
                ->increment('update_version', 1,
                    [
                        'is_password_changed' => 0,
                        'password' => Hash::make($password),
                        'last_updated_by' => Auth::user()->id,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]
                );

            $has_email = 0;
            $user = User::where('id', $request->user_id)->get();

            // if(count($user) > 0 && $user[0]->email != ""){
            //     $has_email = 1;
            //     // $has_email = 0;
            //     $subject = 'PATS User Reset Password';
            //     $email = $user[0]->email;
            //     $message = 'This is a notification from PATS. Your PATS user password account was successfully reset.';

            //     dispatch(new SendUserPasswordJob($subject, $message, $user[0]->username, $password, $email));
            // }
            DB::commit();
            return response()->json(['result' => "1", 'user' => $user, 'has_email' => $has_email, 'password' => $password]);
        }
        catch(\Exception $e) {
            DB::rollback();
            // throw $e;
            return response()->json(['result' => "0"]);
        }
    }

    //View Users
	public function view_users(){
    	$users = User::with([
                    'user_level',
                    // 'oqc_stamps' => function($query) {
                    //     $query->where('status', 1);
                    //     $query->orderBy('id', 'desc');
                    // }
                ])
                ->get();

        return DataTables::of($users)
            ->addColumn('label1', function($user){
                $result = "";

                if($user->status == 1){
                    $result .= '<span class="badge badge-pill badge-success">Active</span>';
                }
                else{
                    $result .= '<span class="badge badge-pill badge-danger">Inactive</span>';
                }

                return $result;
            })
            ->addColumn('action1', function($user){
                $result = '<center><div class="btn-group">
                          <button type="button" class="btn btn-dark dropdown-toggle btn-xs" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Action">
                            <i class="fa fa-cog"></i>
                          </button>
                          <div class="dropdown-menu dropdown-menu-right">';
                if($user->status == 1){
                	$result .= '<button class="dropdown-item aEditUser" type="button" user-id="' . $user->id . '" style="padding: 1px 1px; text-align: center;" data-bs-toggle="modal" data-bs-target="#modalEditUser" data-bs-keyboard="false">Edit</button>';

                    $result .= '<button class="dropdown-item aChangeUserStat" type="button" user-id="' . $user->id . '" status="2" style="padding: 1px 1px; text-align: center;" data-bs-toggle="modal" data-bs-target="#modalChangeUserStat" data-bs-keyboard="false">Deactivate</button>';

                    $result .= '<button class="dropdown-item aResetUserPass" user-id="' . $user->id . '" type="button" style="padding: 1px 1px; text-align: center;" data-bs-toggle="modal" data-bs-target="#modalResetUserPass" data-bs-keyboard="false">Reset Password</button>';

                    $result .= '<button class="dropdown-item aGenUserBarcode" user-id="' . $user->id . '" employee-id="' . $user->employee_id . '" type="button" style="padding: 1px 1px; text-align: center;" data-bs-toggle="modal" data-bs-target="#modalGenUserBarcode">Generate Barcode</button>';
                }
                else{
                    $result .= '<button class="dropdown-item aChangeUserStat" type="button" style="padding: 1px 1px; text-align: center;" user-id="' . $user->id . '" status="1" data-bs-toggle="modal" data-bs-target="#modalChangeUserStat" data-bs-keyboard="false">Activate</button>';
                }

                $result .= '</div>
                        </div></center>';

                return $result;
            })
            ->addColumn('checkbox', function($user){
                return '<center><input type="checkbox" class="chkUser" user-id="' . $user->id . '"></center>';
            })
            ->addColumn('fullname', function($user){
                $result = "";

                $result = "$user->firstname $user->middlename $user->lastname";
                return $result;
            })
            ->rawColumns(['label1', 'action1', 'checkbox', 'fullname'])
            ->make(true);
    }

    // Add User
    public function add_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        $email = '';
        $has_email = 0;
        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        $rules = [
            // 'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'employee_id' => 'required|string|max:255|unique:users',
            'user_level_id' => 'required|string|max:255',
            'position' => 'required',
        ];

        if(isset($request->with_email)){
            $rules['email'] = 'required|string|max:255|unique:users';
            $has_email = 1;
            // $has_email = 0;
        }

        if(isset($request->with_oqc_stamp)){
            $rules['oqc_stamp'] = 'required|string|max:255|unique:oqc_stamps,status,2';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                $user_id = User::insertGetId([
                    // 'name' => $request->name,
                    'firstname' => $request->fname,
                    'middlename' => $request->mname,
                    'lastname' => $request->lname,
                    'username' => $request->username,
                    'email' => $request->email,
                    'employee_id' => $request->employee_id,
                    'position' => $request->position,
                    'section' => $request->section,
                    'password' => Hash::make($password),
                    'is_password_changed' => 0,
                    'status' => 1,
                    'user_level_id' => $request->user_level_id,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                if(isset($request->oqc_stamp)){
                    OQCStamp::insert([
                        'user_id' => $user_id,
                        'oqc_stamp' => $request->oqc_stamp,
                        'created_by' => Auth::user()->id,
                        'last_updated_by' => Auth::user()->id,
                        'update_version' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                DB::commit();

                return response()->json(['result' => "1", 'password' => $password, 'has_email' => $has_email, 'username' => $request->username]);
            }
            catch(\Exception $e) {
                DB::rollback();
                // throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    // Get User By Id
    public function get_user_by_id(Request $request){
        $user = User::with([
                            // 'oqc_stamps' => function($query) {
                            //     $query->where('status', 1);
                            //     $query->orderBy('id', 'desc');
                            // }
                        ])->where('id', $request->user_id)->get();

        $qrcode = QrCode::format('png')
                            ->size(200)->errorCorrection('H')
                            ->generate($user[0]->employee_id);

        return response()->json(['user' => $user, 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
    }

    public function get_user_list(Request $request){
        $users = User::all();

        return response()->json(['users' => $users]);
    }

    public function get_user_by_en(Request $request){
        $users = User::where('employee_id', $request->employee_id)->first();

        return response()->json(['users' => $users]);
    }

    // Get User By Batch
    public function get_user_by_batch(Request $request){
        $users;

        if($request->user_id == 0){
            $users = User::all();
        }
        else{
            $users = User::whereIn('id', $request->user_id)->get();
        }
        $qrcode = [];

        if($users->count() > 0){
            for($index = 0; $index < $users->count(); $index++){
                $qrcode[] = "data:image/png;base64," . base64_encode(QrCode::format('png')
                                    ->size(200)->errorCorrection('H')
                                    ->generate($users[$index]->employee_id));
            }
        }

        return response()->json(['users' => $users, 'qrcode' => $qrcode]);
    }

    // Get User By Status
    public function get_user_by_stat(Request $request){
        $user = User::where('status', $request->status)->get();
        return response()->json(['user' => $user]);
    }

    // Edit User
    public function edit_user(Request $request){
        date_default_timezone_set('Asia/Manila');

        $data = $request->all();

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';

        if(isset($request->with_email)){
            $validator = Validator::make($data, [
                // 'name' => 'required|string|max:255|unique:users,name,'. $request->user_id,
                'username' => 'required|string|max:255|unique:users,username,'. $request->user_id,
                'employee_id' => 'required|string|max:255|unique:users,employee_id,'. $request->user_id,
                'email' => 'required|string|max:255|unique:users,email,'. $request->user_id,
                'user_level_id' => 'required|string|max:255|',
                'position' => 'required|',
            ]);
        }
        else{
            $validator = Validator::make($data, [
                // 'name' => 'required|string|max:255|unique:users,name,'. $request->user_id,
                'username' => 'required|string|max:255|unique:users,username,'. $request->user_id,
                'employee_id' => 'required|string|max:255|unique:users,employee_id,'. $request->user_id,
                'user_level_id' => 'required|string|max:255|',
                'position' => 'required|',
            ]);
        }

        if ($validator->fails()) {
            return response()->json(['result' => '0', 'error' => $validator->messages()]);
        }
        else{
            DB::beginTransaction();

            try{
                $edit_array = array(
                    // 'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'employee_id' => $request->employee_id,
                    'user_level_id' => $request->user_level_id,
                    'position' => $request->position,
                    'section' => $request->section,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                if(isset($request->oqc_stamp)){
                    $edit_array['oqc_stamp'] = $request->oqc_stamp;
                }
                else{
                    $edit_array['oqc_stamp'] = null;

                }

                User::where('id', $request->user_id)
                ->increment('update_version', 1,$edit_array);

                // if(isset($request->oqc_stamp)){
                //     $oqc_stamps = OQCStamp::where('user_id', $request->user_id)
                //                         ->where('status', 1)
                //                         ->orderBy('id', 'desc')
                //                         ->limit(1)
                //                         ->get();

                //     if($oqc_stamps->count() > 0){
                //         if($request->oqc_stamp != $oqc_stamps[0]->oqc_stamp){
                //             OQCStamp::where('id', $oqc_stamps[0]->id)
                //                 ->increment('update_version', 1,
                //                 [
                //                     'status' => 2,
                //                     'last_updated_by' => Auth::user()->id,
                //                     'updated_at' => date('Y-m-d H:i:s'),
                //                 ]);

                //             OQCStamp::insert([
                //                 'user_id' => $request->user_id,
                //                 'oqc_stamp' => $request->oqc_stamp,
                //                 'created_by' => Auth::user()->id,
                //                 'last_updated_by' => Auth::user()->id,
                //                 'update_version' => 1,
                //                 'updated_at' => date('Y-m-d H:i:s'),
                //                 'created_at' => date('Y-m-d H:i:s')
                //             ]);
                //         }
                //     }
                //     else{
                //         OQCStamp::insert([
                //             'user_id' => $request->user_id,
                //             'oqc_stamp' => $request->oqc_stamp,
                //             'created_by' => Auth::user()->id,
                //             'last_updated_by' => Auth::user()->id,
                //             'update_version' => 1,
                //             'updated_at' => date('Y-m-d H:i:s'),
                //             'created_at' => date('Y-m-d H:i:s')
                //         ]);
                //     }

                // }

                DB::commit();

                return response()->json(['result' => "1"]);
            }
            catch(\Exception $e) {
                DB::rollback();
                throw $e;
                return response()->json(['result' => "0"]);
            }
        }
    }

    public function generate_user_qrcode(Request $request){
        // action: 1-Add, 2-Edit, 3-Generate Only

        // $user = [];
        // if($request->action == "1" || $request->action == "3"){
        //     $user = User::where('employee_id', $request->qrcode)->get();
        // }
        // else if($request->action == "2"){
        //     $user = User::where('employee_id', $request->qrcode)
        //                 ->where('id', '!=', $request->user_id)
        //                 ->get();
        // }

        // $user = User::where('id', $request->user_id)->get();

        // $qrcode = $user[0]->barcode;

        try{
            if(isset($request->qrcode)){
                $user = User::where('employee_id', $request->qrcode)->get();

                $qrcode = QrCode::format('png')
                        ->size(200)->errorCorrection('H')
                        ->generate($request->qrcode);

                return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode), 'user' => $user]);
            }
            else{
                return response()->json(['result' => "0"]);
            }
        }
        catch(\Exception $e){
            return response()->json(['result' => "0"]);
        }

        // if(count($user) <= 0){
        //     try{
        //         if(isset($request->qrcode)){
        //             $qrcode = QrCode::format('png')
        //                     ->size(200)->errorCorrection('H')
        //                     ->generate($request->qrcode);

        //             return response()->json(['result' => "1", 'qrcode' => "data:image/png;base64," . base64_encode($qrcode)]);
        //         }
        //         else{
        //             return response()->json(['result' => "0"]);
        //         }
        //     }
        //     catch(\Exception $e){
        //         return response()->json(['result' => "0"]);
        //     }
        // }
        // else{
        //     return response()->json(['result' => "2"]);
        // }
    }

    public function import_user(Request $request)
    {
        $collections = Excel::toCollection(new CSVUserImport, request()->file('import_file'));

        // $password = 'pmi1234' . Str::random(10);
        $password = 'pmi12345';
        $user_level_id = 3;

        DB::beginTransaction();
        try{
            for($index = 1; $index < count($collections[0]); $index++){
                if($collections[0][$index][4] == 0){
                    $user_level_id = 2;
                }
                else{
                    $user_level_id = 3;
                }

                $user_id = User::insertGetId([
                    'name' => $collections[0][$index][0],
                    'username' => $collections[0][$index][1],
                    'email' => $collections[0][$index][2],
                    'employee_id' => $collections[0][$index][3],
                    'password' => Hash::make($password),
                    'position' => $collections[0][$index][4],
                    'user_level_id' => $user_level_id,
                    'is_password_changed' => 0,
                    'status' => 1,
                    'created_by' => Auth::user()->id,
                    'last_updated_by' => Auth::user()->id,
                    'update_version' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                if(trim($collections[0][$index][5]) != ""){
                    OQCStamp::insert([
                        'user_id' => $user_id,
                        'oqc_stamp' => $collections[0][$index][5],
                        'created_by' => Auth::user()->id,
                        'last_updated_by' => Auth::user()->id,
                        'update_version' => 1,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }

            DB::commit();

            return response()->json(['result' => "1"]);
        }
        catch(\Exception $e) {
            DB::rollback();
            return response()->json(['result' => $e]);
        }
    }

    public function get_emp_details_by_id(Request $request){

        $hris_data = DB::connection('mysql_systemone_hris')
        ->select("SELECT * FROM tbl_EmployeeInfo WHERE EmpNo = '$request->empId'");

        if(count($hris_data) > 0){
            return response()->json(['empInfo' => $hris_data]);
        }
        else{
            $subcon_data = DB::connection('mysql_systemone_subcon')
            ->select("SELECT * FROM tbl_EmployeeInfo WHERE EmpNo = '$request->empId'");

            return response()->json(['empInfo' => $subcon_data]);

        }

    }
    public function check_department(Request $request){
        try {
            $get_department_by_id = Department::where('department_id',session('rapidx_department_id'))->get(['department_group']);
            $department = ($get_department_by_id[0]['department_group'] == "PPS" )? "PPD": $get_department_by_id[0]['department_group'];
            return response()->json(['is_success' => 'true','department' => $department,'dept_code' => session('rapidx_department_id') ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
}
