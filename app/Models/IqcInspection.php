<?php

namespace App\Models;

use App\Models\User;
use App\Models\DropdownIqcAql;
use App\Models\VwListOfReceived;
use App\Models\IqcDropdownDetail;
use App\Models\IqcInspectionsMod;
use App\Models\TblWarehouseTransaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\DropdownIqcInspectionLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IqcInspection extends Model
{
    protected $connection = 'mysql';
    protected $table = 'ts_iqc_inspections';
    protected $fillable = [
        'whs_transaction_id',
        'receiving_detail_id',
        'invoice_no',
        'partcode',
        'partname',
        'supplier',
        'family',
        'app_no',
        'app_no_extension',
        'die_no',
        'total_lot_qty',
        'lot_no',
        'classification',
        'type_of_inspection',
        'severity_of_inspection',
        'inspection_lvl',
        'aql',
        'accept',
        'reject',
        'shift',
        'date_inspected',
        'time_ins_from',
        'time_ins_to',
        'inspector',
        'submission',
        'category',
        'target_lar',
        'target_dppm',
        'sampling_size',
        'lot_inspected',
        'accepted',
        'no_of_defects',
        'judgement',
        'remarks',
        'iqc_coc_file',
        // 'iqc_inspection_id',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function iqc_inspections_mods(){
        return $this->hasMany(IqcInspectionsMod::class)->whereNull('deleted_at');
    }

    public function user_iqc(){
        return $this->hasOne(User::class, 'id', 'inspector');
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
    public function vw_list_of_received(){
            return $this->hasOne(VwListOfReceived::class, 'pkid_received', 'whs_transaction_id');
    }
    // public function classification(){
    //     return $this->iqc_dropdown_detail('classification');
    // }
    // public function iqc_dropdown_detail_severity_of_inspection(){
    //     return $this->iqc_dropdown_detail('severity_of_inspection');
    // }
}
