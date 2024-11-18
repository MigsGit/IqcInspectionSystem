<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\TblWarehouseTransaction;

class TblWarehouse extends Model
{
    use HasFactory;

    protected $table = 'tbl_Warehouse';
    protected $connection = 'mysql_rapid_pps';

    public function pps_warehouse_transaction_info(){
        return $this->hasMany(TblWarehouseTransaction::class, 'fkid','id')->orderBy('pkid', 'DESC');
    }

}
