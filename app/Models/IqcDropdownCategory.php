<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IqcDropdownCategory extends Model
{
    public function iqc_dropdown_details(){
        return $this->hasMany(IqcDropdownDetail::class, 'iqc_dropdown_categories_id','id')
            ->whereNull('deleted_at')->orderBy('id', 'DESC');
    }
}
