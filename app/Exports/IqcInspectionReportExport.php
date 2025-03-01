<?php

namespace App\Exports;

use App\Models\IqcInspection;
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
    protected $category;
    protected $arr_group_by1;
    protected $arr_group_by2;
    public function __construct($from_date, $to_date, $category, $arr_group_by1, $arr_group_by2)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->category = $category;
        $this->arr_group_by1 = $arr_group_by1;
        $this->arr_group_by2 = $arr_group_by2;
    }

    public function sheets(): array{
        $sheets = [];
        $sheets[] = new IqcInspectionRawSheet($this->from_date, $this->to_date, $this->category, $this->arr_group_by1, $this->arr_group_by2);
        $sheets[] = new IqcInspectionByDateMaterialGroupBySheet($this->from_date, $this->to_date, $this->category, $this->arr_group_by1, $this->arr_group_by2);
        return $sheets;
    }

    /**
     * Collection of IqcInspection Data By Material Category and Date
    */
    // public function collection()
    // {
    //     return $this->from_date;
    //     $getIqcInspectionByMaterialCategoryDate = IqcInspection::
    //     with('user_iqc')
    //     ->where("iqc_category_material_id", "=", 38)
    //     ->whereBetween('date_inspected', ['2025-02-01', '2025-02-27'])
    //     ->get();
    //     return $getIqcInspectionByMaterialCategoryDate;
    // }
}
