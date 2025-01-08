<?php

namespace App\Models;

use App\Models\IqcDropdownDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CnIqcInspectionsMod extends Model
{
    protected $connection ='mysql';
    protected $table='cn_iqc_inspections_mods';

    /**
     * Get the user associated with the CnIqcInspectionsMod
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function iqc_dropdown_detail()
    {
            return $this->hasOne(IqcDropdownDetail::class, 'id', 'mode_of_defects');
    }
}
