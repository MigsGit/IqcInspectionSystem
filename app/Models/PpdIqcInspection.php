<?php

namespace App\Models;

use App\Models\User;
use App\Models\IqcInspectionsMod;
use App\Models\PpdIqcInspectionsMod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PpdIqcInspection extends Model
{

    protected $connection = 'mysql';
    protected $table = 'ppd_iqc_inspections';

    public function ppd_iqc_inspections_mods(){
        return $this->hasMany(PpdIqcInspectionsMod::class)->whereNull('deleted_at');
    }

    public function user_iqc(){
        return $this->hasOne(User::class, 'id', 'inspector');
    }

    public function ppd_iqc_inspection_mods_info(){
        return $this->hasMany(PpdIqcInspectionsMod::class, 'iqc_inspection_id', 'id')->whereNull('deleted_at');
    }


}
