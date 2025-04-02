<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Exports\Sheets\IqcInspectionRawSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\IqcInspectionByDateMaterialGroupBySheetWeekly;



class IqcInspectionReportExport implements WithMultipleSheets
// FromCollection,
{
    protected $iqcInspectionByDateMaterialGroupBySheet;
    protected $iqcInspectionRawSheet;
    public function __construct($iqcInspectionByDateMaterialGroupBySheet, $iqcInspectionRawSheet)
    {
        $this->iqcInspectionRawSheet = $iqcInspectionRawSheet;
        $this->iqcInspectionByDateMaterialGroupBySheet = $iqcInspectionByDateMaterialGroupBySheet;
    }
    public function sheets(): array{
        $sheets = [];
        $sheets['Daily'] = new IqcInspectionRawSheet($this->iqcInspectionRawSheet);
        $sheets['Weekly'] = new IqcInspectionByDateMaterialGroupBySheetWeekly($this->iqcInspectionByDateMaterialGroupBySheet);
        return $sheets;
    }


    //Query to validate data group by supplier and specific date
    public function collection()
    {
        $daily = new IqcInspectionRawSheet($this->iqcInspectionRawSheet);
        $weekly = new IqcInspectionByDateMaterialGroupBySheetWeekly($this->iqcInspectionByDateMaterialGroupBySheet);
        return $daily->collection();
    }
}
