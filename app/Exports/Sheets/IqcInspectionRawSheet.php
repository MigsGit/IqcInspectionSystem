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
                $results[] =  $inspection->toArray();
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

        if($data['type_of_inspection'] == "1"){
            $type_of_inspection = "Single";
        }else if($data['type_of_inspection'] == "2"){
            $type_of_inspection = "Double";
        }else if($data['type_of_inspection'] == "3"){
            $type_of_inspection = "Level Check";
        }else{
            $type_of_inspection = "N/A";
        }
        switch ($data['classification']) {
            case 'N/A':
                $classification = 'N/A';
                break;
            case '1':
                $classification = 'PPD-Molding Plastic Resin';
                break;
            case '2':
                $classification = 'PPD-Molding Metal';
                break;
            case '3':
                $classification = 'Parts For grinding';
                break;
            case '4':
                $classification = 'PPD-Stamping';
                break;
            case '5':
                $classification = 'YEC - Stock';
                break;
            default:
                $classification = 'N/A';
                break;
        }
        return [
            $data['invoice_no'],
            $data['partcode'],
            $data['partname'],
            $data['supplier'],
            $data['vw_list_of_received']['date'],
            $data['lot_no'],
            $data['total_lot_qty'],
            $type_of_inspection,//E
            $data['iqc_dropdown_detail_severity_of_inspection']['dropdown_details'], //I
            $data['iqc_dropdown_detail_inspection_lvl']['dropdown_details'], //I
            $data['iqc_dropdown_detail_aql']['dropdown_details'], //I
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
            $data['classification'],
            $data['remarks'],
            // $classification,
        ];
    }
    /**
     * Excel design styles
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Apply borders to all cells containing data
        $cellRange = 'A6:' . $highestColumn . $highestRow;

        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'], // Black border
                ],
            ],
        ]);

        //Merge Cell
        $sheet->mergeCells('E1:H1');
        $sheet->mergeCells('E2:H2');
        $sheet->mergeCells('E4:H4');//Start of the Cell Value

        // ✅ Insert custom header manually
        $sheet->setCellValue('E1', 'Pricon Microelectronics, Inc.');
        $sheet->setCellValue('E2', '#14 Ampere St., Light Industry and Science Park 1, Cabuyao, Laguna');
        $sheet->setCellValue('E4', 'IQC INSPECTION SUMMARY');
        $sheet->setCellValue('A6', 'Invoice No');
        $sheet->setCellValue('B6', 'Part Code');
        $sheet->setCellValue('C6', 'Part Name');
        $sheet->setCellValue('D6', 'Supplier');
        $sheet->setCellValue('E6', 'WHS Received Date');
        $sheet->setCellValue('F6', 'Lot No.');
        $sheet->setCellValue('G6', 'Lot Qty');
        $sheet->setCellValue('H6', 'Type of Inspection');
        $sheet->setCellValue('I6', 'Severity of Inspection');
        $sheet->setCellValue('J6', 'Inspection Level');
        $sheet->setCellValue('K6', 'AQL');
        $sheet->setCellValue('L6', 'Accept');
        $sheet->setCellValue('M6', 'Reject');
        $sheet->setCellValue('N6', 'Date Inspected');
        $sheet->setCellValue('O6', 'Shift');
        $sheet->setCellValue('P6', 'Inspector');
        $sheet->setCellValue('Q6', 'Submission');
        $sheet->setCellValue('R6', 'Judgment');
        $sheet->setCellValue('S6', 'Lot Inspected');
        $sheet->setCellValue('T6', 'Lot Accepted');
        $sheet->setCellValue('U6', 'Sample Size');
        $sheet->setCellValue('V6', 'No. of Defects');
        $sheet->setCellValue('W6', 'Classification');
        $sheet->setCellValue('X6', 'Remarks');




        return [
            1 => ['font' => ['bold' => true]],
            2 => ['font' => ['bold' => true]],
            4 => ['font' => ['bold' => true]],
            6 => ['font' => ['bold' => true]], // Headers
            'A6:N6' => ['font' => ['bold' => true, 'size' => 12], 'alignment' => ['horizontal' => 'center']],
        ];
    }


}
