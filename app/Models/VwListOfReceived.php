<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VwListOfReceived extends Model
{
    protected $connection = 'mysql_rapid_ts_whs_packaging';
    protected $table = 'vw_list_of_received';
}
