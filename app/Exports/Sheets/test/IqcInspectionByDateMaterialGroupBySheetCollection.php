<?php

namespace App\Exports\Sheets;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IqcInspectionByDateMaterialGroupBySheet implements
    WithEvents,
    WithTitle,
    ShouldAutoSize,
    WithStrictNullComparison
{
    use Exportable;
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
        return $this->iqcInspectionByDateMaterialGroupBySheet;
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
        return [
            AfterSheet::class => function (AfterSheet $event) use ($iqcInspectionByDateMaterialGroupBySheet){
                $sheet = $event->sheet;

                // 🔹 Set Custom Header
                $sheet->setCellValue('A1', 'Pricon Microelectronics, Inc.');
                $sheet->setCellValue('A2', 'IQC Inspection Summary');
                $sheet->mergeCells('A1:E1'); // Merge header cells
                $sheet->mergeCells('A2:E2');

                // 🔹 Style Headers
                $sheet->getStyle('A1:A2')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center'],
                    'fill' => [
                        'fillType' => 'solid',
                        'startColor' => ['rgb' => 'FFD700'], // Gold background
                    ],
                ]);


                // 🔹 Auto-size columns
                foreach (range('A', 'E') as $col) {
                    $sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }

                $startRow = 7; // Start inserting data from row 7
                foreach ($this->iqcInspectionByDateMaterialGroupBySheet as $weekIndex => $weekData) {
                    if (!isset($weekData)) {
                        continue; // Skip if no data
                    }

                    $row = $startRow;
                    foreach ($weekData as $index => $item) {
                        switch ($weekIndex) {
                            case 0:
                                $sheet->setCellValue("A{$row}", $item->supplier);
                                $sheet->setCellValue("B{$row}", $item->week_range);
                                break;
                            case 1:
                                $sheet->setCellValue("E{$row}", $item->supplier);
                                $sheet->setCellValue("F{$row}", $item->week_range);
                                break;
                            case 2:
                                $sheet->setCellValue("K{$row}", $item->supplier);
                                $sheet->setCellValue("L{$row}", $item->week_range);
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
            }
        ];
    }


}
