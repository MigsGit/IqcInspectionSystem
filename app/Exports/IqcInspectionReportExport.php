<?php

namespace App\Exports;
use App\Models\IqcInspection;
// use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Exports\Sheets\IqcInspectionReportSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class IqcInspectionReportExport implements FromCollection
// WithMultipleSheets
{
    // protected $from_data;
    // protected $to_data;
    // protected $material_category;
    // protected $group_by1;
    // protected $group_by2;
    // public function __construct($from_data, $to_data, $material_category, $group_by1,$group_by2)
    // {
    //     $this->from_data = $from_data;
    //     $this->to_data = $to_data;
    //     $this->material_category = $material_category;
    //     $this->group_by1 = $group_by1;
    //     $this->group_by2 = $group_by2;

    // }
    // public function sheets(): array
    // {
    //     $sheets = [];
    //     $sheets[] = new IqcInspectionReportSheet($this->search_material_name, $this->stamping);
    //     return $sheets;
    // }
    // public function collection (){
    //     return "test";
    // }
    public function collection()
    {
        $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        with('user_iqc')
        ->where("iqc_category_material_id", "=", 38)
        ->whereBetween('date_inspected', ['2025-02-01', '2025-02-27'])
        ->get();
        return $getIqcInspectionByMaterialCategoryDate;
    }
}
