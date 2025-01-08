<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwCnListOfReceived extends Model
{
    protected $connection = 'mysql_rapid_cn_whs_packaging';
    protected $table = 'vw_list_of_received';
}
