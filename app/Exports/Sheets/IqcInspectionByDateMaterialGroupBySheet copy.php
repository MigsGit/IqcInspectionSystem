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
        // week_range
    // Get the start and end of the month
    $startOfMonth = Carbon::parse($this->from_date)->startOfMonth();
    $endOfMonth = Carbon::parse($this->to_date)->endOfMonth();

    // Determine the first Thursday of the month
    $firstThursday = $startOfMonth->copy()->next(Carbon::THURSDAY);

    $weekRanges = [];
    $startDate = $startOfMonth;

    // Generate week ranges ending on Thursday
    while ($startDate <= $endOfMonth) {
        // If first week, set end date to first Thursday
        $endDate = ($startDate->equalTo($firstThursday))
            ? $firstThursday
            : $startDate->copy()->next(Carbon::THURSDAY);

        // Ensure end date does not exceed end of month
        if ($endDate > $endOfMonth) {
            $endDate = $endOfMonth;
        }

        // Store week range
        $weekRanges[] = [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d')
        ];

        // Move to next week's start date
        $startDate = $endDate->copy()->addDay();
    }
    // Fetch inspection data per week

        $iqcInspectionCollection =  collect($weekRanges)->map(function ($week) {
            return IqcInspection::
                select($this->arr_merge_group)
                ->addSelect(
                    DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                    // DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                    DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                    DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                    DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                    DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
                )
                ->where("iqc_category_material_id", "=", "$this->material_category")
                ->whereBetween('date_inspected', [$week['start'], $week['end']])
                // ->groupBy('supplier')
                ->groupBy($this->arr_merge_group)
                ->get();
        })->filter(); // Remove empty records
        $iqcInspectionCollection =  collect($iqcInspectionCollection)->map(function ($value) {
            return $value;
        });
        return new Collection([
            'data' => $iqcInspectionCollection,
            'data2' => $iqcInspectionCollection
            //display one array only
        ]);
        // $iqcInspectionFlatten = $iqcInspectionCollection->flatten();
        // return new Collection([
        //     [
        //         // 'work_range' => $iqcInspectionFlatten->pluck('week_range')->unique()->toArray(),
        //         'work_range' => $iqcInspectionFlatten,
        //     ]
        // ]);
        $iqcInspectionFlatten = $iqcInspectionCollection->flatten();
        return new Collection([
            [
                'work_range' => $iqcInspectionFlatten->pluck('week_range')->unique()->toArray(), //display unique date range only
            ]
        ]);
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

        $row = 2; // Start at row 2
        foreach (data as $index => $data) {
            $mapping["A{$row}"] = $data['supplier'];
            $mapping["B{$row}"] = $data['lot_inspected'];
            $row++; // Move to the next row
        }

        return $mapping;
        // return [
        //     'team1'=>[
        //         'data' => 'B1'
        //     ],
        //     'team2'=> [
        //         'data2' => 'B2'
        //     ],
        // ];
        // return [
        //     $data->week_range,
        //     $data->accepted_count,
        //     $data->rejected_count,
        //     $data->sampling_size_sum,
        //     $data->no_of_defects_sum,
        // ];
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
