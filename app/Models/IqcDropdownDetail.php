<?php

namespace App\Models;

use App\Models\IqcDropdownCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IqcDropdownDetail extends Model
{
    use HasFactory;

    public function iqc_dropdown_category()
    {
        return $this->hasOne(IqcDropdownCategory::class,'id','iqc_dropdown_categories_id')->whereNull('deleted_at')->orderBy('id', 'DESC');
    }
}
