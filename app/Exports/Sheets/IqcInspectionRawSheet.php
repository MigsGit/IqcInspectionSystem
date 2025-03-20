<?php

namespace App\Exports\Sheets;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IqcInspectionRawSheet implements
    FromCollection,
    WithTitle,
    WithMapping,
    WithStyles,
    WithCustomStartCell,
    ShouldAutoSize,
    WithStrictNullComparison
{
    protected $iqcInspectionRawSheet;
    public function __construct($iqcInspectionRawSheet)
    {
        $this->iqcInspectionRawSheet = $iqcInspectionRawSheet;
    }


    /**
     * Title of the Excel Sheet
     * @return string
     */
    public function title(): string
    {
        return 'Iqc Inspection Report';
    }
    /**
     * Collection of IqcInspection Data By Material Category and Date
    */
    public function collection()
    {
        $this->iqcInspectionRawSheet->chunk(40, function ($inspections) use(&$results) {
            foreach ($inspections as $inspection) {
                $results[] =  $inspection->toArray();;

            }
        });
        return collect($results); // Convert array to collection
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
            $data['invoice_no'],
            $data['partcode'],
            $data['partname'],
            $data['supplier'],
            $data['vw_list_of_received']['date'], //
            $data['lot_no'],
            $data['total_lot_qty'],
            $data['type_of_inspection'], //Single Double Level Check
            $data['iqc_dropdown_detail_severity_of_inspection']['dropdown_details'],
            $data['iqc_dropdown_detail_inspection_lvl']['dropdown_details'],
            $data['aql'],
            $data['accept'],
            $data['reject'],
            $data['date_inspected'],
            $data['shift'], //1-A 2-B
            $data['user_iqc']['name'], //Get Name by Id from User Database
            $data['submission'],
            $data['judgement'],
            $data['lot_inspected'],
            $data['accepted'],
            $data['sampling_size'],
            $data['no_of_defects'],
            $data['remarks'],
            $data['classification'],
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
        $sheet->setCellValue('A6', 'Invoice No'); //A
        $sheet->setCellValue('B6', 'Part Code');
        $sheet->setCellValue('C6', 'Part Name');
        $sheet->setCellValue('D6', 'Supplier');
        $sheet->setCellValue('E6', 'WHS Received Date'); //
        $sheet->setCellValue('F6', 'Lot No.');
        $sheet->setCellValue('G6', 'Lot Qty');
        $sheet->setCellValue('H6', 'Type of Inspection'); //
        $sheet->setCellValue('I6', 'Severity of Inspection'); //
        $sheet->setCellValue('J6', 'Inspection Level'); //
        $sheet->setCellValue('K6', 'AQL'); //
        $sheet->setCellValue('L6', 'Accept'); //
        $sheet->setCellValue('M6', 'Reject'); //
        $sheet->setCellValue('N6', 'Date Inspected'); //
        $sheet->setCellValue('O6', 'Shift'); //
        $sheet->setCellValue('P6', 'Inspector');
        $sheet->setCellValue('Q6', 'Submission');
        $sheet->setCellValue('R6', 'Judgment');
        $sheet->setCellValue('S6', 'Lot Inspected');
        $sheet->setCellValue('T6', 'Lot Accepted');
        $sheet->setCellValue('U6', 'Sample Size');
        $sheet->setCellValue('V6', 'No. of Defects');
        $sheet->setCellValue('W6', 'Remarks');
        $sheet->setCellValue('X6', 'Classification');

        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]], // Headers
            'A6:N6' => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
        ];
    }


}
