<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\IqcInspection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Exports\Sheets\IqcInspectionRawSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\IqcInspectionByDateMaterialGroupBySheet;



class IqcInspectionReportExport implements WithMultipleSheets
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
        $sheets[] = new IqcInspectionByDateMaterialGroupBySheet($this->iqcInspectionByDateMaterialGroupBySheet);
        $sheets[] = new IqcInspectionRawSheet($this->iqcInspectionRawSheet);
        return $sheets;
    }


    //Query to validate data group by supplier and specific date
    public function collection()
    {

        // foreach ($headers as $index => $header) {
        //     $columnLetter = chr(65 + $index); // Convert index to column letter (A, B, C, ...)
        //     $mapping["{$columnLetter}2"] = $header;
        //     $mapping["{$columnLetter}2"] = $header;
        //     $mapping[chr(75 + $index) . "2"] = $header; // Shift for Week 2 (starts at column K)
        // }

        $startRow = 7; // Start inserting data from row 7

        foreach ($this->iqcInspectionByDateMaterialGroupBySheet as $weekIndex => $weekData) {
            if (!isset($weekData)) {
                continue; // Skip if no data
            }

            $row = $startRow;
            foreach ($weekData as $index => $item) {
                if ($weekIndex == 0) {
                    $mapping["A{$row}"] = $item->supplier;
                    $mapping["B{$row}"] = $item->week_range;
                } elseif ($weekIndex == 1) {
                    $mapping["E{$row}"] = $item->supplier;
                    $mapping["F{$row}"] = $item->week_range;
                } elseif ($weekIndex == 2) {
                    $mapping["K{$row}"] = $item->supplier;
                    $mapping["L{$row}"] = $item->week_range;
                } elseif ($weekIndex == 3) {
                    $mapping["O{$row}"] = $item->supplier;
                    $mapping["P{$row}"] = $item->week_range;
                } elseif ($weekIndex == 4) {
                    $mapping["S{$row}"] = $item->supplier;
                    $mapping["T{$row}"] = $item->week_range;
                }

                $row++; // Move to next row
            }
        }

        return $mapping;
        // return $this->iqcInspectionByDateMaterialGroupBySheet;
        // return $this->iqcInspectionRawSheet;
        $startRow = 7; // Start inserting data from row 7

        foreach ([0, 1, 2, 3, 4] as $weekIndex) {
            if (!isset($this->iqcInspectionByDateMaterialGroupBySheet[$weekIndex])) {
                continue; // Skip if no data
            }

            foreach ($this->iqcInspectionByDateMaterialGroupBySheet[$weekIndex] as $index => $item) {
                $row = $startRow + $index; // Adjust row dynamically
                $rowData = array_fill(0, 20, ''); // Initialize an array with 20 empty elements

                if ($weekIndex == 0) {
                    $rowData[0] = $item->supplier;
                    $rowData[1] = $item->week_range;
                } elseif ($weekIndex == 1) {
                    $rowData[4] = $item->supplier;
                    $rowData[5] = $item->week_range;
                } elseif ($weekIndex == 2) {
                    $rowData[10] = $item->supplier;
                    $rowData[11] = $item->week_range;
                } elseif ($weekIndex == 3) {
                    $rowData[14] = $item->supplier;
                    $rowData[15] = $item->week_range;
                } elseif ($weekIndex == 4) {
                    $rowData[18] = $item->supplier;
                    $rowData[19] = $item->week_range;
                }

                $data[] = $rowData;
                $startRow = 7; // Start inserting data from row 7
            }
        }
        return collect($data);
    }
}
