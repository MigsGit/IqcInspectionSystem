<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YPICS extends Model
{
    use HasFactory;

    // protected $table = 'tbl_active_docs';
    protected $connection = 'sqlsrv_1';
}