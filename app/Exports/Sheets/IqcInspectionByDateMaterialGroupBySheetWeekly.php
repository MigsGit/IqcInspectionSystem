<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IqcInspectionByDateMaterialGroupBySheetWeekly implements
WithEvents,
WithTitle,
ShouldAutoSize,
WithStrictNullComparison
{
    protected $iqcInspectionByDateMaterialGroupBySheet;

    public function __construct($iqcInspectionByDateMaterialGroupBySheet)
    {
        $this->iqcInspectionByDateMaterialGroupBySheet = $iqcInspectionByDateMaterialGroupBySheet;
    }
    /**
     * ✅ Set the sheet title
     */
    public function title(): string
    {
        return 'Weekly Summary';
    }

    /**
     * ✅ Provide data for export (IMPORTANT!)
     */
    public function collection()
    {
        // return $this->iqcInspectionByDateMaterialGroupBySheet;
        $startRow = 7; // Start inserting data from row 7
        foreach ($this->iqcInspectionByDateMaterialGroupBySheet as $weekIndex => $weekData) {
            if (!isset($weekData)) {
                continue; // Skip if no data
            }

            $row = $startRow;
            foreach ($weekData as $index => $item) {
                switch ($weekIndex) {
                    case 0:
                        $mapping["A{$row}"] = $item->supplier;
                        $mapping["B{$row}"] = $item->week_range;
                        break;
                    case 1:
                        $mapping["E{$row}"] = $item->supplier;
                        $mapping["F{$row}"] = $item->week_range;
                        break;
                    case 2:
                        $mapping["K{$row}"] = $item->supplier;
                        $mapping["L{$row}"] = $item->week_range;
                        break;
                    case 3:
                        $mapping["O{$row}"] = $item->supplier;
                        $mapping["P{$row}"] = $item->week_range;
                        break;
                    case 4:
                        $mapping["S{$row}"] = $item->supplier;
                        $mapping["T{$row}"] = $item->week_range;
                        break;

                    default:
                        # code...
                        break;
                }
                $row++; // Move to next row
            }
        }
        return $mapping;
    }
    /**
     * ✅ Format and Style Sheet After Data Is Inserted
     */
    public function registerEvents(): array
    {
        $iqcInspectionByDateMaterialGroupBySheet = $this->iqcInspectionByDateMaterialGroupBySheet;
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        return [
            AfterSheet::class => function (AfterSheet $event) use ($styleBorderAll){
                $sheet = $event->sheet;

                //🔹Merge header cells
                $sheet->mergeCells("A2:C2");
                $sheet->mergeCells("A1:E1");
                // 🔹 Set Custom Header
                $sheet->setCellValue("A1", "TS IQC Performance");
                $sheet->setCellValue("A2", "FY 2024");
                $setRowValue = 6;
                // $sheet->setCellValue("A{$setRowValue}", "Date Covered");
                $sheet->setCellValue("B{$setRowValue}", "Supplier");
                $sheet->setCellValue("C{$setRowValue}", "Lot Inspected");
                $sheet->setCellValue("D{$setRowValue}", "Lot rejected");
                $sheet->setCellValue("E{$setRowValue}", "Lot OK");
                $sheet->setCellValue("F{$setRowValue}", "Samples");
                $sheet->setCellValue("G{$setRowValue}", "NG Qty");
                // $sheet->setCellValue("H{$setRowValue}", "Target LAR");
                // $sheet->setCellValue("I{$setRowValue}", "Actual LAR");
                // $sheet->setCellValue("J{$setRowValue}", "Target DPPM");
                // $sheet->setCellValue("K{$setRowValue}", "Actual DPPM");

                $sheet->setCellValue("N{$setRowValue}", "Supplier");
                $sheet->setCellValue("O{$setRowValue}", "Lot Inspected");
                $sheet->setCellValue("P{$setRowValue}", "Lot rejected");
                $sheet->setCellValue("Q{$setRowValue}", "Lot OK");
                $sheet->setCellValue("R{$setRowValue}", "Samples");
                $sheet->setCellValue("S{$setRowValue}", "NG Qty");
                // $sheet->setCellValue("T{$setRowValue}", "Target LAR");
                // $sheet->setCellValue("U{$setRowValue}", "Actual LAR");
                // $sheet->setCellValue("V{$setRowValue}", "Target DPPM");
                // $sheet->setCellValue("W{$setRowValue}", "Actual DPPM");

                $sheet->setCellValue("AA{$setRowValue}", "Supplier");
                $sheet->setCellValue("AB{$setRowValue}", "Lot Inspected");
                $sheet->setCellValue("AC{$setRowValue}", "Lot rejected");
                $sheet->setCellValue("AD{$setRowValue}", "Lot OK");
                $sheet->setCellValue("AE{$setRowValue}", "Samples");
                $sheet->setCellValue("AF{$setRowValue}", "NG Qty");
                // $sheet->setCellValue("AG{$setRowValue}", "Target LAR");
                // $sheet->setCellValue("AH{$setRowValue}", "Actual LAR");
                // $sheet->setCellValue("AI{$setRowValue}", "Target DPPM");
                // $sheet->setCellValue("AJ{$setRowValue}", "Actual DPPM");

                $sheet->setCellValue("AM{$setRowValue}", "Supplier");
                $sheet->setCellValue("AN{$setRowValue}", "Lot Inspected");
                $sheet->setCellValue("AO{$setRowValue}", "Lot rejected");
                $sheet->setCellValue("AP{$setRowValue}", "Lot OK");
                $sheet->setCellValue("AQ{$setRowValue}", "Samples");
                $sheet->setCellValue("AR{$setRowValue}", "NG Qty");
                // $sheet->setCellValue("AS{$setRowValue}", "Target LAR");
                // $sheet->setCellValue("AT{$setRowValue}", "Actual LAR");
                // $sheet->setCellValue("AU{$setRowValue}", "Target DPPM");
                // $sheet->setCellValue("AV{$setRowValue}", "Actual DPPM");

                $sheet->setCellValue("AY{$setRowValue}", "Supplier");
                $sheet->setCellValue("AZ{$setRowValue}", "Lot Inspected");
                $sheet->setCellValue("BA{$setRowValue}", "Lot rejected");
                $sheet->setCellValue("BB{$setRowValue}", "Lot OK");
                $sheet->setCellValue("BC{$setRowValue}", "Samples");
                $sheet->setCellValue("BD{$setRowValue}", "NG Qty");
                // $sheet->setCellValue("BE{$setRowValue}", "Target LAR");
                // $sheet->setCellValue("BF{$setRowValue}", "Actual LAR");
                // $sheet->setCellValue("BG{$setRowValue}", "Target DPPM");
                // $sheet->setCellValue("BH{$setRowValue}", "Actual DPPM");

                // 🔹 Style Headers
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FFD700'], // Gold background
                    ],
                ]);
                $sheet->getDelegate()->getStyle('A1:A2')->applyFromArray($styleBorderAll);

                // 🔹 Auto-size columns
                foreach (range('A', 'E') as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                $startRow = 7; // Start inserting data from row 7
                foreach ($this->iqcInspectionByDateMaterialGroupBySheet as $weekIndex => $weekData) {
                    if (!isset($weekData)) {
                        continue; // Skip if no data
                    }
                    // dd($weekData);
                    // exit;
                    $row = $startRow;
                    foreach ($weekData as $index => $item) {
                        switch ($weekIndex) {
                            case 0:
                                // $sheet->setCellValue("B{$row}", $item->week_range);
                                $sheet->setCellValue("B{$row}", $item->supplier);
                                $sheet->setCellValue("C{$row}", $item->lot_inspected_sum);
                                $sheet->setCellValue("D{$row}", $item->rejected_count);
                                $sheet->setCellValue("E{$row}", $item->accepted_count);
                                $sheet->setCellValue("F{$row}", $item->sampling_size_sum);
                                $sheet->setCellValue("G{$row}", $item->no_of_defects_sum);
                                // $sheet->setCellValue("H{$row}", "100");
                                // $sheet->setCellValue("I{$row}", $item->actual_lar);
                                // $sheet->setCellValue("J{$row}", "448");
                                // $sheet->setCellValue("K{$row}", $item->actual_dppm);
                                break;
                            case 1:
                                $sheet->setCellValue("N{$row}", $item->supplier);
                                $sheet->setCellValue("O{$row}", $item->lot_inspected_sum);
                                $sheet->setCellValue("P{$row}", $item->rejected_count);
                                $sheet->setCellValue("Q{$row}", $item->accepted_count);
                                $sheet->setCellValue("R{$row}", $item->sampling_size_sum);
                                $sheet->setCellValue("S{$row}", $item->no_of_defects_sum);
                                // $sheet->setCellValue("T{$row}", "100");
                                // $sheet->setCellValue("U{$row}", $item->actual_lar);
                                // $sheet->setCellValue("V{$row}",  "448");
                                // $sheet->setCellValue("W{$row}", $item->actual_dppm);
                                break;
                            case 2:
                                $sheet->setCellValue("AA{$row}", $item->supplier);
                                $sheet->setCellValue("AB{$row}", $item->lot_inspected_sum);
                                $sheet->setCellValue("AC{$row}", $item->rejected_count);
                                $sheet->setCellValue("AD{$row}", $item->accepted_count);
                                $sheet->setCellValue("AE{$row}", $item->sampling_size_sum);
                                // $sheet->setCellValue("AF{$row}", $item->no_of_defects_sum);
                                // $sheet->setCellValue("AG{$row}", "100");
                                // $sheet->setCellValue("AH{$row}", $item->actual_lar);
                                // $sheet->setCellValue("AI{$row}", "448");
                                // $sheet->setCellValue("AJ{$row}", $item->actual_dppm);
                                break;
                            case 3:
                                $sheet->setCellValue("AM{$row}", $item->supplier);
                                $sheet->setCellValue("AN{$row}", $item->lot_inspected_sum);
                                $sheet->setCellValue("AO{$row}", $item->rejected_count);
                                $sheet->setCellValue("AP{$row}", $item->accepted_count);
                                $sheet->setCellValue("AQ{$row}", $item->sampling_size_sum);
                                $sheet->setCellValue("AR{$row}", $item->no_of_defects_sum);
                                // $sheet->setCellValue("AS{$row}", "100");
                                // $sheet->setCellValue("AT{$row}", $item->actual_lar);
                                // $sheet->setCellValue("AU{$row}", "448");
                                // $sheet->setCellValue("AV{$row}", $item->actual_dppm);
                                break;
                            case 4:
                                $sheet->setCellValue("AY{$row}", $item->supplier);
                                $sheet->setCellValue("AZ{$row}", $item->lot_inspected_sum);
                                $sheet->setCellValue("BA{$row}", $item->rejected_count);
                                $sheet->setCellValue("BB{$row}", $item->accepted_count);
                                $sheet->setCellValue("BC{$row}", $item->sampling_size_sum);
                                $sheet->setCellValue("BD{$row}", $item->no_of_defects_su);
                                // $sheet->setCellValue("BE{$row}", "100");
                                // $sheet->setCellValue("BF{$row}", $item->actual_lar);
                                // $sheet->setCellValue("BG{$row}", "448");
                                // $sheet->setCellValue("BH{$row}", $item->actual_dppm);
                                break;

                            default:
                                break;
                        }
                        $row++; // Move to next row
                    }
                }
            }
        ];
    }
}
