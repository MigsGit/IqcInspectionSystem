<?php

namespace App\Http\Controllers;

use App\Models\UserLevel;
use Illuminate\Http\Request;

class UserLevelController extends Controller
{
    //
    public function get_user_levels(Request $request){
    	$user_levels = UserLevel::all();

    	return response()->json(['user_levels' => $user_levels]);
    }
}
