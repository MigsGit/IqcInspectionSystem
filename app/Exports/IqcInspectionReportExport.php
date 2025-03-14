<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Exports\Sheets\IqcInspectionRawSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\IqcInspectionByDateMaterialGroupBySheet;



class IqcInspectionReportExport implements
WithMultipleSheets
// FromCollection,
{
    // use Exportable;
    protected $iqcInspectionByDateMaterialGroupBySheet;
    protected $iqcInspectionRawSheet;
    public function __construct($iqcInspectionByDateMaterialGroupBySheet, $iqcInspectionRawSheet)
    {
        $this->iqcInspectionByDateMaterialGroupBySheet = $iqcInspectionByDateMaterialGroupBySheet;
        $this->iqcInspectionRawSheet = $iqcInspectionRawSheet;
    }
    public function sheets(): array{
        $sheets = [];
        $sheets['Weekly'] = new IqcInspectionByDateMaterialGroupBySheet($this->iqcInspectionByDateMaterialGroupBySheet);
        $sheets['Daily'] = new IqcInspectionRawSheet($this->iqcInspectionRawSheet);
        return $sheets;
    }


    //Query to validate data group by supplier and specific date
    public function collection()
    {
        $iqc_inspection_by_date_material_group_by_sheet = new IqcInspectionByDateMaterialGroupBySheet($this->iqcInspectionByDateMaterialGroupBySheet);
        return $iqc_inspection_by_date_material_group_by_sheet->collection();
    }
}
