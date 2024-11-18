<?php

namespace App\Models;

use App\Models\UserLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable // Authenticatable this will allow the use of Auth::user()
{
    use HasFactory;

    protected $table = "users";
    protected $connection = "mysql";

    public function user_level(){
        return $this->hasOne(UserLevel::class, 'id', 'user_level_id');
    }
}
