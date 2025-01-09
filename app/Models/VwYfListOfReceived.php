<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwYfListOfReceived extends Model
{
    protected $connection = 'mysql_rapid_yf_whs_packaging';
    protected $table = 'vw_list_of_received';
}
