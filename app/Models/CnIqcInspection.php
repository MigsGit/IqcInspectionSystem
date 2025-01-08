<?php

namespace App\Models;

use App\Models\User;
use App\Models\CnIqcInspectionsMod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CnIqcInspection extends Model
{
    protected $connection = "mysql";
    protected $table = "cn_iqc_inspections";

    public function cn_iqc_inspections_mods(){
        return $this->hasMany(CnIqcInspectionsMod::class)->whereNull('deleted_at'); //cn_iqc_inspections_mods
    }

    public function user_iqc(){
        return $this->hasOne(User::class, 'id', 'inspector');
    }

    public function cn_iqc_inspection_mods_info(){
        return $this->hasMany(CnIqcInspectionsMod::class, 'iqc_inspection_id', 'id')->whereNull('deleted_at');
    }
}
