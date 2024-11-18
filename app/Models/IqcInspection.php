<?php

namespace App\Models;

use App\Models\User;
use App\Models\DropdownIqcAql;
use App\Models\IqcInspectionsMod;
use App\Models\TblWarehouseTransaction;
use App\Models\DropdownIqcInspectionLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IqcInspection extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'iqc_inspections';

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

    public function IqcInspectionsMods(){
        return $this->hasMany(IqcInspectionsMod::class)->whereNull('deleted_at');
    }

    public function user_iqc(){
        return $this->hasOne(User::class, 'id', 'inspector');
    }

    public function iqc_inspection_mods_info(){
        return $this->hasMany(IqcInspectionsMod::class, 'iqc_inspection_id', 'id')->whereNull('deleted_at');
    }

    public function iqc_inspection_level_info(){
        return $this->hasOne(DropdownIqcInspectionLevel::class, 'id', 'inspection_lvl')->whereNull('deleted_at');
    }
}
