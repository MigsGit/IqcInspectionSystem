<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;

use PhpOffice\PhpSpreadsheet\Style\Alignment;

Use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\IqcInspectionSheet;

class ExportIqcInspectionData implements WithMultipleSheets
{
    use Exportable;
    protected $search_material_name;
    protected $stamping;

    function __construct(
        $search_material_name,
        $stamping
    ){
        $this->search_material_name = $search_material_name;
        $this->stamping = $stamping;
    }

    public function sheets(): array{
        $sheets = [];
        $sheets[] = new IqcInspectionSheet($this->search_material_name, $this->stamping);

        return $sheets;
    }
}
