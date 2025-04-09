<?php

namespace App\Models;

use App\Models\User;
use App\Models\VwYfListOfReceived;
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
    public function iqc_inspection_mods_info(){
        return $this->hasMany(IqcInspectionsMod::class, 'iqc_inspection_id', 'id')->whereNull('deleted_at');
    }
    public function iqc_dropdown_detail($column)
    {
        return $this->hasOne(IqcDropdownDetail::class, 'id', $column);
    }
    public function iqc_dropdown_detail_family(){
        return $this->iqc_dropdown_detail('family');
    }
    public function iqc_dropdown_detail_severity_of_inspection(){
        return $this->iqc_dropdown_detail('severity_of_inspection');
    }
    public function iqc_dropdown_detail_inspection_lvl(){
        return $this->iqc_dropdown_detail('inspection_lvl');
    }
    public function iqc_dropdown_detail_aql(){
        return $this->iqc_dropdown_detail('aql');
    }
    public function vw_yf_list_of_received(){
        return $this->hasOne(VwYfListOfReceived::class, 'pkid_received', 'whs_transaction_id');
    }
}
