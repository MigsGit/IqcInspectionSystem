<?php

namespace App\Models;

use App\Models\User;
use App\Models\YfIqcInspectionsMod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class YfIqcInspection extends Model
{
    protected $connection = "mysql";
    protected $table = "yf_iqc_inspections";

    public function yf_iqc_inspections_mods(){
        return $this->hasMany(YfIqcInspectionsMod::class)->whereNull('deleted_at'); //yf_iqc_inspections_mods
    }
    public function user_iqc(){
        return $this->hasOne(User::class, 'id', 'inspector');
    }
    public function yf_iqc_inspection_mods_info(){
        return $this->hasMany(YfIqcInspectionsMod::class, 'iqc_inspection_id', 'id')->whereNull('deleted_at');
    }
}
