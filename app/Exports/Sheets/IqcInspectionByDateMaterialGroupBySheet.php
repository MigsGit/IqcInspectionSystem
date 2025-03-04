<?php

namespace App\Exports\Sheets;

use App\Models\IqcInspection;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IqcInspectionByDateMaterialGroupBySheet implements
    FromCollection,
    WithTitle,
    WithMapping,
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
        $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        with('user_iqc')
        ->where("iqc_category_material_id", "=", 38)
        ->whereBetween('date_inspected', ['2025-02-01', '2025-02-27'])
        ->get();
        return $getIqcInspectionByMaterialCategoryDate;
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
    public function map($data): array
    {
        return [
            $data->partcode,
            $data->partname,
            $data->supplier,
            $data->lot_no,
            $data->total_lot_qty,
            $data->user_iqc->name, //Get Name by Id from User Database
            $data->submission,
            $data->judgement,
            $data->lot_inspected,
            $data->accepted,
            $data->sampling_size,
            $data->no_of_defects,
            $data->remarks,
            $data->classification,
        ];
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
        $sheet->setCellValue('A6', 'Part Code');
        $sheet->setCellValue('B6', 'Part Name');
        $sheet->setCellValue('C6', 'Supplier');
        $sheet->setCellValue('D6', 'Lot No.');
        $sheet->setCellValue('E6', 'Lot Qty');
        $sheet->setCellValue('F6', 'Inspector');
        $sheet->setCellValue('G6', 'Submission');
        $sheet->setCellValue('H6', 'Judgment');
        $sheet->setCellValue('I6', 'Lot Inspected');
        $sheet->setCellValue('J6', 'Lot Accepted');
        $sheet->setCellValue('K6', 'Sample Size');
        $sheet->setCellValue('L6', 'No. of Defects');
        $sheet->setCellValue('M6', 'Remarks');
        $sheet->setCellValue('N6', 'Classification');

        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]], // Headers
            'A6:N6' => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
        ];
    }


}
