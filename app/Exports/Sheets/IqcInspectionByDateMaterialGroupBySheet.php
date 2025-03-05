<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use App\Models\IqcInspection;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IqcInspectionByDateMaterialGroupBySheet implements
    WithMappedCells,
    // WithMapping,
    FromCollection,
    WithTitle,
    WithStyles,
    WithCustomStartCell,
    ShouldAutoSize,
    WithStrictNullComparison
{
    protected $iqcInspectionCollection;
    public function __construct($iqcInspectionCollection )
    {
        $this->iqcInspectionCollection  = $iqcInspectionCollection ;
    }


    /**
     * Title of the Excel Sheet
     * @return string
     */
    public function title(): string
    {
        return 'Weekly Summary';
    }
    /**
     * Collection of IqcInspection Data By Material Category and Date
    */
    public function collection()
    {
        return $this->iqcInspectionCollection ;
    }
    /**
     * Start Cell
     * @return string
     */
    public function startCell(): string
    {
        return 'A7';  // This ensures headings start from A1
    }
    /**
     * Summary of map
     * @param mixed $data from the collection
     * @return array
     */
    public function mapping(): array
    {
        $mapping = [];
        $startRow = 7; // Start inserting data from row 7
        foreach ([0,1,2,3,4] as $weekIndex) {
            if (!isset($this->iqcInspectionCollection[$weekIndex])) {
                continue; // Skip if no data
            }

            foreach ($this->iqcInspectionCollection[$weekIndex] as $index => $data) {
                $row = $startRow + $index; // Adjust row dynamically
                if ($weekIndex == 0 ) {
                    $mapping["A{$row}"] = $data->supplier;
                    $mapping["D{$row}"] = $data->week_range;
                }
                elseif ($weekIndex == 1) {
                    $mapping["E{$row}"] = $data->supplier;
                    $mapping["F{$row}"] = $data->week_range;
                }
                elseif ($weekIndex == 2) {
                    $mapping["K{$row}"] = $data->supplier;
                    $mapping["L{$row}"] = $data->week_range;
                } elseif ($weekIndex == 3) {
                    $mapping["O{$row}"] = $data->supplier;
                    $mapping["P{$row}"] = $data->week_range;
                }
                 elseif ($weekIndex == 4) {
                    $mapping["S{$row}"] = $data->supplier;
                    $mapping["T{$row}"] = $data->week_range;
                }
            }
            $startRow = 7;
        }
        return $mapping;
    }

    /**
     * Excel design styles
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        // ✅ Insert custom header manually
        $sheet->setCellValue('G1', 'Pricon Microelectronics, Inc.');
        $sheet->setCellValue('G2', '#14 Ampere St., Light Industry and Science Park 1, Cabuyao, Laguna');
        $sheet->setCellValue('G4', 'IQC INSPECTION SUMMARY');
        // $sheet->setCellValue('A6', 'Part Code');
        // $sheet->setCellValue('B6', 'Part Name');
        // $sheet->setCellValue('C6', 'Supplier');
        // $sheet->setCellValue('D6', 'Lot No.');
        // $sheet->setCellValue('E6', 'Lot Qty');
        // $sheet->setCellValue('F6', 'Inspector');
        // $sheet->setCellValue('G6', 'Submission');
        // $sheet->setCellValue('H6', 'Judgment');
        // $sheet->setCellValue('I6', 'Lot Inspected');
        // $sheet->setCellValue('J6', 'Lot Accepted');
        // $sheet->setCellValue('K6', 'Sample Size');
        // $sheet->setCellValue('L6', 'No. of Defects');
        // $sheet->setCellValue('M6', 'Remarks');
        // $sheet->setCellValue('N6', 'Classification');

        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]], // Headers
            'A6:N6' => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
        ];
    }


}
