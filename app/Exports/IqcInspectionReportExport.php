<?php

namespace App\Exports;
use App\Models\IqcInspection;
// use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class IqcInspectionReportExport implements
    FromCollection,
    WithTitle,
    WithMapping,
    WithStyles,
    WithCustomStartCell,
    ShouldAutoSize
{
    public function collection()
    {
        $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        select([
            'partcode', 'partname', 'supplier', 'lot_no', 'total_lot_qty',
            'inspector', 'submission', 'judgement', 'lot_inspected',
            'accepted', 'sampling_size', 'no_of_defects', 'remarks', 'classification'
        ])
        ->where("iqc_category_material_id", "=", 38)
        ->whereBetween('date_inspected', ['2025-02-01', '2025-02-27'])
        ->get();
        return $getIqcInspectionByMaterialCategoryDate;
    }

    public function startCell(): string
    {
        return 'A7';  // This ensures headings start from A1
    }

    public function map($data): array
    {
        return [
            $data->partcode,
            $data->partname,
            $data->supplier,
            $data->lot_no,
            $data->total_lot_qty,
            $data->inspector,
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

    public function styles(Worksheet $sheet)
    {
          // ✅ Insert custom header manually at G1
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

    public function title(): string
    {
        return 'Iqc Inspection Report';
    }
}
