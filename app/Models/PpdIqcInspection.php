<?php

namespace App\Models;

use App\Models\User;
use App\Models\IqcDropdownDetail;
use App\Models\IqcInspectionsMod;
use App\Models\VwPpdListOfReceived;
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
    public function vw_ppd_list_of_received(){
        return $this->hasOne(VwPpdListOfReceived::class, 'pkid_received', 'whs_transaction_id');
    }


}
