<?php

namespace App\Exports;

use App\Models\IqcInspection;
use Illuminate\Support\Facades\DB;
use App\Exports\Sheets\IqcInspectionRawSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\IqcInspectionByDateMaterialGroupBySheet;



class IqcInspectionReportExport implements WithMultipleSheets
// FromCollection,
{
    // use Exportable;
    protected $from_date;
    protected $to_date;
    protected $material_category;
    protected $arr_merge_group;
    public function __construct($from_date, $to_date, $material_category, $arr_merge_group)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->material_category = $material_category;
        $this->arr_merge_group = $arr_merge_group;
    }

    public function sheets(): array{
        $sheets = [];
        $sheets[] = new IqcInspectionRawSheet($this->from_date, $this->to_date, $this->material_category, $this->arr_merge_group);
        $sheets[] = new IqcInspectionByDateMaterialGroupBySheet($this->from_date, $this->to_date, $this->material_category, $this->arr_merge_group);
        return $sheets;
    }

    /**
     * Collection of IqcInspection Data By Material Category and Date
    */
    public function collection()
    {
        //Select columns from the array and With Relationship needed to include the inspector foreign key
        return $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        select($this->arr_merge_group)
        ->with('user_iqc:id,name')
        ->where("iqc_category_material_id", "=", '38')
        ->whereBetween('date_inspected', ['2025-02-01', '2025-02-27'])
        ->get();
    }

}
