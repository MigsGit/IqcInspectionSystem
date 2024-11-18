<?php

namespace App\Models;

use App\Models\IqcInspection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TblWarehouseTransaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_WarehouseTransaction';
    protected $connection = 'mysql_rapid_pps';
}
