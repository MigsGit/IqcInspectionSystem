<?php
namespace App\Exports;

// use App\Model\ShippingInspector;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Facades\DB;
use App\Model\WBSSakidashiIssuance;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Sheet;
Use \Maatwebsite\Excel\Sheet;
use App\Model\WBSSakidashiIssuanceItem;
// use Maatwebsite\Excel\Cell\DataType;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class ExportMoldingTraceabilityReport implements FromView, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ShippingInspector::all();
    // }
    use Exportable;

    protected $material;
    protected $secondMoldingData;
    protected $secondMoldingInitialData;
    protected $secondMoldingCameraData;
    protected $secondMoldingVisualData;
    protected $secondMoldingFirstOqcData;
    protected $assemblyMarkingData;
    protected $assemblyMOData;
    protected $assemblyVisualData;
    protected $assemblyData;

    // protected $device_name;

    function __construct(
    $material,
    $secondMoldingData,
    $secondMoldingInitialData,
    $secondMoldingCameraData,
    $secondMoldingVisualData,
    $secondMoldingFirstOqcData,
    $assemblyMarkingData,
    $assemblyMOData,
    $assemblyVisualData,
    $assemblyData
    ){
        $this->material = $material;
        $this->secondMoldingData = $secondMoldingData;
        $this->secondMoldingInitialData = $secondMoldingInitialData;
        $this->secondMoldingCameraData = $secondMoldingCameraData;
        $this->secondMoldingVisualData = $secondMoldingVisualData;
        $this->secondMoldingFirstOqcData = $secondMoldingFirstOqcData;
        $this->assemblyMarkingData = $assemblyMarkingData;
        $this->assemblyMOData = $assemblyMOData;
        $this->assemblyVisualData = $assemblyVisualData;
        $this->assemblyData = $assemblyData;
    }

    public function view(): View
    {
        return view('exports.export_molding_traceability_report', [
        ]);
    }
    public function title(): string{
        return "Traceability Report";
    }

    public function registerEvents(): array
    {
        $material = $this->material;
        $secondMoldingData = $this->secondMoldingData;
        $secondMoldingInitialData = $this->secondMoldingInitialData;
        $secondMoldingCameraData = $this->secondMoldingCameraData;
        $secondMoldingVisualData = $this->secondMoldingVisualData;
        $secondMoldingFirstOqcData = $this->secondMoldingFirstOqcData;
        $assemblyMarkingData = $this->assemblyMarkingData;
        $assemblyMOData = $this->assemblyMOData;
        $assemblyVisualData = $this->assemblyVisualData;
        $assemblyData = $this->assemblyData;

        $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font12 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                // 'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font20 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  20,
                // 'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $arial_font8_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  8,
                'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $hv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hlv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hrv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        );
        $styleBorderBottomThin= [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $hlv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                // 'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );

        $hcv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );



        return [
            AfterSheet::class => function(AfterSheet $event) use(
                $arial_font12_bold,
                $arial_font12,
                $hv_center,
                $hlv_center,
                $hrv_center,
                $styleBorderBottomThin,
                $styleBorderAll,
                $hlv_top,
                $hcv_top,
                $arial_font20,
                $arial_font8_bold,
                $material,
                $secondMoldingData,
                $secondMoldingInitialData,
                $secondMoldingCameraData,
                $secondMoldingVisualData,
                $secondMoldingFirstOqcData,
                $assemblyMarkingData,
                $assemblyMOData,
                $assemblyVisualData,
                $assemblyData
            ) {
                if($material == 'CN171S-07#IN-VE'){ // CN171S
                    $event->sheet->getColumnDimension('A')->setWidth(15);
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getColumnDimension('C')->setWidth(15);
                    $event->sheet->getColumnDimension('D')->setWidth(15);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getColumnDimension('G')->setWidth(15);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getColumnDimension('K')->setWidth(15);
                    $event->sheet->getColumnDimension('L')->setWidth(15);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getColumnDimension('Q')->setWidth(15);
                    $event->sheet->getColumnDimension('R')->setWidth(15);
                    $event->sheet->getColumnDimension('S')->setWidth(15);
                    $event->sheet->getColumnDimension('T')->setWidth(15);
                    $event->sheet->getColumnDimension('U')->setWidth(15);
                    $event->sheet->getColumnDimension('V')->setWidth(15);
                    $event->sheet->getColumnDimension('W')->setWidth(15);
                    $event->sheet->getColumnDimension('X')->setWidth(15);
                    $event->sheet->getColumnDimension('Y')->setWidth(15);
                    $event->sheet->getColumnDimension('Z')->setWidth(15);
                    $event->sheet->getColumnDimension('AA')->setWidth(15);
                    $event->sheet->getColumnDimension('AB')->setWidth(15);
                    $event->sheet->getColumnDimension('AC')->setWidth(15);
                    $event->sheet->getColumnDimension('AD')->setWidth(15);
                    $event->sheet->getColumnDimension('AE')->setWidth(15);
                    $event->sheet->getColumnDimension('AF')->setWidth(15);
                    $event->sheet->getColumnDimension('AG')->setWidth(15);
                    $event->sheet->getColumnDimension('AH')->setWidth(15);
                    $event->sheet->getColumnDimension('AI')->setWidth(15);
                    $event->sheet->getColumnDimension('AJ')->setWidth(15);
                    $event->sheet->getColumnDimension('AK')->setWidth(15);
                    $event->sheet->getColumnDimension('AL')->setWidth(15);
                    $event->sheet->getColumnDimension('AM')->setWidth(15);
                    $event->sheet->getColumnDimension('AN')->setWidth(15);



                    $event->sheet->setCellValue('A1', $material.'Parts Lot Management Record');
                    $event->sheet->getDelegate()->mergeCells('A1:V1');
                    $event->sheet->getDelegate()->getStyle('A1:V1')->applyFromArray($arial_font12);

                    $event->sheet->getDelegate()->mergeCells('A2:A3');
                    $event->sheet->getDelegate()->mergeCells('B2:B3');
                    $event->sheet->getDelegate()->mergeCells('C2:C3');
                    $event->sheet->getDelegate()->mergeCells('D2:D3');
                    $event->sheet->getDelegate()->mergeCells('E2:E3');
                    $event->sheet->getDelegate()->mergeCells('F2:F3');
                    $event->sheet->getDelegate()->mergeCells('G2:G3');
                    $event->sheet->getDelegate()->mergeCells('H2:H3');
                    $event->sheet->getDelegate()->mergeCells('I2:I3');
                    $event->sheet->getDelegate()->mergeCells('J2:J3');
                    $event->sheet->getDelegate()->mergeCells('K2:K3');
                    $event->sheet->getDelegate()->mergeCells('L2:L3');
                    $event->sheet->getDelegate()->mergeCells('M2:M3');
                    $event->sheet->getDelegate()->mergeCells('N2:N3');
                    $event->sheet->getDelegate()->mergeCells('O2:O3');
                    $event->sheet->getDelegate()->mergeCells('P2:P3');
                    $event->sheet->getDelegate()->mergeCells('Q2:Q3');
                    $event->sheet->getDelegate()->mergeCells('R2:R3');
                    $event->sheet->getDelegate()->mergeCells('S2:S3');
                    $event->sheet->getDelegate()->mergeCells('T2:T3');
                    $event->sheet->getDelegate()->mergeCells('U2:U3');
                    $event->sheet->getDelegate()->mergeCells('V2:V3');
                    $event->sheet->getDelegate()->mergeCells('W2:W3');
                    $event->sheet->getDelegate()->mergeCells('X2:X3');
                    $event->sheet->getDelegate()->mergeCells('Y2:Y3');
                    $event->sheet->getDelegate()->mergeCells('Z2:Z3');
                    $event->sheet->getDelegate()->mergeCells('AA2:AA3');
                    $event->sheet->getDelegate()->mergeCells('AB2:AB3');
                    $event->sheet->getDelegate()->mergeCells('AB2:AB3');
                    $event->sheet->getDelegate()->mergeCells('AC2:AC3');
                    $event->sheet->getDelegate()->mergeCells('AD2:AH2');
                    $event->sheet->getDelegate()->mergeCells('AI2:AI3');
                    $event->sheet->getDelegate()->mergeCells('AJ2:AN2');


                    $event->sheet->setCellValue('A2',"投入日 Production Date");
                    $event->sheet->setCellValue('B2',"Shift");
                    $event->sheet->setCellValue('C2',"Production Lot #");
                    $event->sheet->setCellValue('D2',"数量 Prodn Qty");
                    $event->sheet->setCellValue('E2',"Camera Inspection");
                    $event->sheet->setCellValue('F2',"Yield");
                    $event->sheet->setCellValue('G2',"Visual Inspection");
                    $event->sheet->setCellValue('H2',"Yield");
                    $event->sheet->setCellValue('I2',"Over-all Yield");
                    $event->sheet->setCellValue('J2',"1st OQC");
                    $event->sheet->setCellValue('K2',"DPPM");
                    $event->sheet->setCellValue('L2',"LAR");
                    $event->sheet->setCellValue('M2',"Lot Marking");
                    $event->sheet->setCellValue('N2',"Yield");
                    $event->sheet->setCellValue('O2',"MO Assembly");
                    $event->sheet->setCellValue('P2',"Yield");
                    $event->sheet->setCellValue('Q2',"Visual Inspection");
                    $event->sheet->setCellValue('R2',"Yield");
                    $event->sheet->setCellValue('S2',"Over-all Yield");
                    $event->sheet->setCellValue('T2',"Final OQC");
                    $event->sheet->setCellValue('U2',"DPPM");
                    $event->sheet->setCellValue('V2',"LAR");
                    $event->sheet->setCellValue('W2',"Material Name");
                    $event->sheet->setCellValue('X2',"Material Lot # (Resin Lot #)");
                    $event->sheet->setCellValue('Y2',"Product Drawing");
                    $event->sheet->setCellValue('Z2',"Product Drawing Rev.");
                    $event->sheet->setCellValue('AA2',"CAV");
                    $event->sheet->setCellValue('AB2',"识别表示 Special adoption document or any special instruction");
                    $event->sheet->setCellValue('AC2',"Remarks");

                    $event->sheet->setCellValue('AD2',"Operator Name");
                    $event->sheet->setCellValue('AD3',"Rotary Machine");
                    $event->sheet->setCellValue('AE3',"Camera Inspection");
                    $event->sheet->setCellValue('AF3',"Visual Inspection");

                    $event->sheet->setCellValue('AG2',"Lot Marking");
                    $event->sheet->setCellValue('AG3',"MO Assembly");
                    $event->sheet->setCellValue('AH3',"Visual Inspection");
                    $event->sheet->setCellValue('AI2',"QC Inspector Name");

                    $event->sheet->setCellValue('AJ2',"Parts Name");
                    $event->sheet->setCellValue('AJ3',"CN171S-08");
                    $event->sheet->setCellValue('AK3',"CN171S-09");
                    $event->sheet->setCellValue('AL3',"CN171S-10");
                    $event->sheet->setCellValue('AM3',"CN171S-03#ME-VE");
                    $event->sheet->setCellValue('AN3',"CN171S-05#ME-VE");

                    $start_col = 4;
                    $assemblyMarkingSum = '';
                    $assemblyMarkingYield = '';
                    $assemblyMOSum = '';
                    $assemblyMOYield = '';
                    $assemblyVisualSum = '';
                    $assemblyVisualYield = '';
                    for ($i=0; $i < count($secondMoldingData); $i++) {
                        $created_at = substr($secondMoldingData[$i]->created_at,0,10);

                        $event->sheet->setCellValue('A'.$start_col, $created_at);
                        $event->sheet->setCellValue('C'.$start_col, $secondMoldingData[$i]->production_lot);
                        $event->sheet->setCellValue('W'.$start_col, $secondMoldingData[$i]->material_name);
                        $event->sheet->setCellValue('X'.$start_col, $secondMoldingData[$i]->material_lot_number);
                        $event->sheet->setCellValue('Y'.$start_col, $secondMoldingData[$i]->drawing_number);
                        $event->sheet->setCellValue('Z'.$start_col, $secondMoldingData[$i]->revision_number);
                        $event->sheet->setCellValue('AD'.$start_col, $secondMoldingData[$i]->r_machine_operator);
                        $event->sheet->setCellValue('AJ'.$start_col, $secondMoldingData[$i]->lot_number_eight);
                        $event->sheet->setCellValue('AK'.$start_col, $secondMoldingData[$i]->lot_number_nine);
                        $event->sheet->setCellValue('AL'.$start_col, $secondMoldingData[$i]->lot_number_ten);
                        $event->sheet->setCellValue('AM'.$start_col, $secondMoldingData[$i]->me_name_lot_number_one);
                        $event->sheet->setCellValue('AN'.$start_col, $secondMoldingData[$i]->me_name_lot_number_second);



                        for ($p=0; $p <count($secondMoldingInitialData); $p++) {
                            if ($secondMoldingData[$i]->id == $secondMoldingInitialData[$p]->sec_molding_runcard_id) {
                                $event->sheet->setCellValue('D'.$start_col, $secondMoldingInitialData[$p]->initial_sum);
                            }
                        }

                        for ($u=0; $u < count($secondMoldingCameraData); $u++) {
                            if ($secondMoldingData[$i]->id == $secondMoldingCameraData[$u]->sec_molding_runcard_id) {
                                    $yield = $secondMoldingCameraData[$u]->camera_yield;

                                    $event->sheet->setCellValue('E'.$start_col, $secondMoldingCameraData[$u]->camera_sum);
                                    $event->sheet->setCellValue('F'.$start_col, $yield.'%');
                                    $event->sheet->setCellValue('AE'.$start_col, $secondMoldingCameraData[$u]->camera_operator);
                            }
                        }

                        for ($o=0; $o <count($secondMoldingVisualData); $o++) {
                            if ($secondMoldingData[$i]->id == $secondMoldingVisualData[$o]->sec_molding_runcard_id) {
                                    $event->sheet->setCellValue('G'.$start_col, $secondMoldingVisualData[$o]->visual_sum);
                                    $event->sheet->setCellValue('H'.$start_col, $secondMoldingVisualData[$o]->visual_yield/100);
                                    $event->sheet->setCellValue('AF'.$start_col, $secondMoldingVisualData[$o]->visual_operator);
                            }
                        }

                        if(isset($secondMoldingFirstOqcData)){
                            for ($v=0; $v <count($secondMoldingFirstOqcData); $v++) {
                                if ($secondMoldingData[$i]->id == $secondMoldingFirstOqcData[$v]->sec_molding_runcard_id){

                                    $defects = $secondMoldingFirstOqcData[$v]->no_of_defects;
                                    

                                    if($defects != 0 ){
                                        $dppm = ($defects / $secondMoldingFirstOqcData[$v]->sample_size) * 1000000;
                                        $event->sheet->setCellValue('K'.$start_col, $dppm);
                                    }else{
                                        $event->sheet->setCellValue('K'.$start_col, 0);

                                    }

                                    
                                    $lar = ($secondMoldingFirstOqcData[$v]->lot_accepted / $secondMoldingFirstOqcData[$v]->lot_inspected) * 100;
    
                                    $event->sheet->setCellValue('J'.$start_col, $secondMoldingFirstOqcData[$v]->first_oqc_sum);
                                    $event->sheet->setCellValue('L'.$start_col, $lar.'%');
    
                                }
                            }
                        }

                        // dd($assemblyMarkingData);
                        
                        if(isset($assemblyMarkingData)){
                            // dd($assemblyMarkingSum);
                            for ($z=0; $z <count($assemblyMarkingData); $z++) {
                                if($assemblyMarkingData[$z]->s_lot_no != null && $assemblyMarkingData[$z]->s_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyMarkingSum = $assemblyMarkingData[$z]->marking_sum;
                                    $assemblyMarkingYield = $assemblyMarkingData[$z]->marking_yield;


                                    $event->sheet->setCellValue('M'.$start_col, $assemblyMarkingSum);
                                    $event->sheet->setCellValue('N'.$start_col, $assemblyMarkingYield.'%');
                                    $event->sheet->setCellValue('AG'.$start_col, $assemblyMarkingData[$z]->marking_operator);
                                }
                                else if($assemblyMarkingData[$z]->p_lot_no != null && $assemblyMarkingData[$z]->p_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyMarkingSum = $assemblyMarkingData[$z]->marking_sum;
                                    $assemblyMarkingYield = $assemblyMarkingData[$z]->marking_yield;

                                    $event->sheet->setCellValue('M'.$start_col, $assemblyMarkingSum);
                                    $event->sheet->setCellValue('N'.$start_col, $assemblyMarkingYield.'%');
                                    $event->sheet->setCellValue('AG'.$start_col, $assemblyMarkingData[$z]->marking_operator);
                                }
                            }
                        }

                        if(isset($assemblyMOData)){
                            for ($x=0; $x <count($assemblyMOData); $x++) {
                                if($assemblyMOData[$x]->s_lot_no != null && $assemblyMOData[$x]->s_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyMOSum = $assemblyMOData[$x]->mo_assembly_sum;
                                    $assemblyMOYield = $assemblyMOData[$x]->mo_assembly_yield;

                                    $event->sheet->setCellValue('O'.$start_col, $assemblyMOSum);
                                    $event->sheet->setCellValue('P'.$start_col, $assemblyMOYield.'%');
                                    $event->sheet->setCellValue('AG'.$start_col, $assemblyMOData[$x]->mo_operator);
                                }
                                else if($assemblyMOData[$x]->p_lot_no != null && $assemblyMOData[$x]->p_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyMOSum = $assemblyMOData[$x]->mo_assembly_sum;
                                    $assemblyMOYield = $assemblyMOData[$x]->mo_assembly_yield;

                                    $event->sheet->setCellValue('O'.$start_col, $assemblyMOSum);
                                    $event->sheet->setCellValue('P'.$start_col, $assemblyMOYield.'%');
                                    $event->sheet->setCellValue('AG'.$start_col, $assemblyMOData[$x]->mo_operator);
                                }
                            }

                        }

                        if(isset($assemblyVisualData)){
                            for ($c=0; $c <count($assemblyVisualData); $c++) {
                                if($assemblyVisualData[$c]->s_lot_no != null && $assemblyVisualData[$c]->s_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyVisualSum = $assemblyVisualData[$c]->visual_sum;
                                    $assemblyVisualYield = $assemblyVisualData[$c]->visual_yield;


                                    // $event->sheet->setCellValue('K'.$start_col, $assemblyVisualData[$c]->ttl_ng);
                                    $event->sheet->setCellValue('Q'.$start_col, $assemblyVisualSum);
                                    $event->sheet->setCellValue('R'.$start_col, $assemblyVisualYield.'%');
                                    $event->sheet->setCellValue('AH'.$start_col, $assemblyVisualData[$c]->visual_operator);
                                }
                                else if($assemblyVisualData[$c]->p_lot_no != null && $assemblyVisualData[$c]->p_lot_no == $secondMoldingData[$i]->production_lot){
                                    $assemblyVisualSum = $assemblyVisualData[$c]->visual_sum;
                                    $assemblyVisualYield = $assemblyVisualData[$c]->visual_yield;

                                    // $event->sheet->setCellValue('K'.$start_col, $assemblyVisualData[$c]->ttl_ng);
                                    $event->sheet->setCellValue('Q'.$start_col, $assemblyVisualSum);
                                    $event->sheet->setCellValue('R'.$start_col, $assemblyVisualYield.'%');
                                    $event->sheet->setCellValue('AH'.$start_col, $assemblyVisualData[$c]->visual_operator);
                                }
                            }
                        }

                        //KULANG 3/10/24

                        // $event->sheet->setCellValue('J'.$start_col, $assemblyVisualSum); // 1ST OQC
                        // $event->sheet->setCellValue('K'.$start_col, $assemblyVisualSum); // DPPM (NO. OF DEFECTS / SAMPLES * 1000000)
                        // $event->sheet->setCellValue('L'.$start_col, $assemblyVisualSum); // LAR (ACCEPTED LOT / TOTAL LOT INSPECTED * 100%)

                        // $event->sheet->setCellValue('J'.$start_col, $assemblyVisualSum); // FINAL OQC
                        // $event->sheet->setCellValue('K'.$start_col, $assemblyVisualSum); // DPPM (NO. OF DEFECTS / SAMPLES * 1000000)
                        // $event->sheet->setCellValue('L'.$start_col, $assemblyVisualSum); // LAR (ACCEPTED LOT / TOTAL LOT INSPECTED * 100%)

                        $event->sheet->setCellValue('I'.$start_col, ("=(F".$start_col."+H".$start_col.")")."/2");
                        $event->sheet->setCellValue('S'.$start_col, ("=(N".$start_col."+P".$start_col."+R".$start_col.")")."/3");

                        $event->sheet->getDelegate()->getStyle('A4'.':'.'AN'.$start_col)->applyFromArray($styleBorderAll);
                        $start_col++;
                    }

                    $event->sheet->getDelegate()->getStyle('H4'.':'.'H'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    $event->sheet->getDelegate()->getStyle('I4'.':'.'I'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    $event->sheet->getDelegate()->getStyle('S4'.':'.'S'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    $event->sheet->getDelegate()->getStyle('A4'.':'.'AN'.$start_col)->getAlignment()->setWrapText(true);
                    $event->sheet->getDelegate()->getStyle('A2:AN3')->applyFromArray($hv_center);
                    $event->sheet->getDelegate()->getStyle('A4'.':'.'AN'.$start_col)->applyFromArray($hv_center);
                    $event->sheet->getDelegate()->getStyle('A2:AN3')->getAlignment()->setWrapText(true);
                    $event->sheet->getDelegate()->getStyle('A2:AN3')->applyFromArray($styleBorderAll);
                }

                else{ // CN171P
                    $start_col = 4;
                    $event->sheet->getColumnDimension('A')->setWidth(15);
                    $event->sheet->getColumnDimension('B')->setWidth(15);
                    $event->sheet->getColumnDimension('C')->setWidth(15);
                    $event->sheet->getColumnDimension('D')->setWidth(15);
                    $event->sheet->getColumnDimension('E')->setWidth(15);
                    $event->sheet->getColumnDimension('F')->setWidth(15);
                    $event->sheet->getColumnDimension('G')->setWidth(15);
                    $event->sheet->getColumnDimension('H')->setWidth(15);
                    $event->sheet->getColumnDimension('I')->setWidth(15);
                    $event->sheet->getColumnDimension('J')->setWidth(15);
                    $event->sheet->getColumnDimension('K')->setWidth(15);
                    $event->sheet->getColumnDimension('L')->setWidth(15);
                    $event->sheet->getColumnDimension('M')->setWidth(15);
                    $event->sheet->getColumnDimension('N')->setWidth(15);
                    $event->sheet->getColumnDimension('O')->setWidth(15);
                    $event->sheet->getColumnDimension('P')->setWidth(15);
                    $event->sheet->getColumnDimension('Q')->setWidth(15);
                    $event->sheet->getColumnDimension('R')->setWidth(15);
                    $event->sheet->getColumnDimension('S')->setWidth(30);
                    $event->sheet->getColumnDimension('T')->setWidth(15);
                    $event->sheet->getColumnDimension('U')->setWidth(15);
                    $event->sheet->getColumnDimension('V')->setWidth(15);
                    $event->sheet->getColumnDimension('W')->setWidth(15);
                    $event->sheet->getColumnDimension('X')->setWidth(15);
                    $event->sheet->getColumnDimension('Y')->setWidth(15);
                    $event->sheet->getColumnDimension('Z')->setWidth(15);
                    $event->sheet->getColumnDimension('AA')->setWidth(15);
                    $event->sheet->getColumnDimension('AB')->setWidth(15);
                    $event->sheet->getColumnDimension('AC')->setWidth(15);
                    $event->sheet->getColumnDimension('AD')->setWidth(15);

                    $event->sheet->setCellValue('A1', $material.' Parts Lot Management Record');
                    $event->sheet->getDelegate()->mergeCells('A1:V1');
                    $event->sheet->getDelegate()->getStyle('A1:V1')->applyFromArray($arial_font12);

                    $event->sheet->getDelegate()->mergeCells('A2:A3');
                    $event->sheet->getDelegate()->mergeCells('B2:B3');
                    $event->sheet->getDelegate()->mergeCells('C2:C3');
                    $event->sheet->getDelegate()->mergeCells('D2:D3');
                    $event->sheet->getDelegate()->mergeCells('E2:E3');
                    $event->sheet->getDelegate()->mergeCells('F2:F3');
                    $event->sheet->getDelegate()->mergeCells('G2:G3');
                    $event->sheet->getDelegate()->mergeCells('H2:H3');
                    $event->sheet->getDelegate()->mergeCells('I2:I3');
                    $event->sheet->getDelegate()->mergeCells('J2:J3');
                    $event->sheet->getDelegate()->mergeCells('K2:K3');
                    $event->sheet->getDelegate()->mergeCells('L2:L3');
                    $event->sheet->getDelegate()->mergeCells('M2:M3');
                    $event->sheet->getDelegate()->mergeCells('N2:N3');
                    $event->sheet->getDelegate()->mergeCells('O2:O3');
                    $event->sheet->getDelegate()->mergeCells('P2:P3');
                    $event->sheet->getDelegate()->mergeCells('Q2:Q3');
                    $event->sheet->getDelegate()->mergeCells('R2:R3');
                    $event->sheet->getDelegate()->mergeCells('S2:S3');
                    $event->sheet->getDelegate()->mergeCells('T2:T3');
                    $event->sheet->getDelegate()->mergeCells('U2:U3');
                    $event->sheet->getDelegate()->mergeCells('V2:V3');
                    $event->sheet->getDelegate()->mergeCells('W2:W3');
                    $event->sheet->getDelegate()->mergeCells('X2:X3');
                    $event->sheet->getDelegate()->mergeCells('Y2:AA2');
                    // $event->sheet->getDelegate()->mergeCells('Z2:Z3');
                    $event->sheet->getDelegate()->mergeCells('AB2:AE2');
                    // $event->sheet->getDelegate()->mergeCells('W2:W3');
                    // $event->sheet->getDelegate()->mergeCells('AD2:AD3');
                    // $event->sheet->getDelegate()->mergeCells('AE2:AG2');


                    $event->sheet->setCellValue('A2',"投入日 Production Date");
                    $event->sheet->setCellValue('B2',"Shift");
                    $event->sheet->setCellValue('C2',"Production Lot #");
                    $event->sheet->setCellValue('D2',"数量 Prodn Qty");
                    $event->sheet->setCellValue('E2',"投入日 Shipment Date");
                    $event->sheet->setCellValue('F2',"投入日 Shipment Date QTY");
                    $event->sheet->setCellValue('G2',"Accum");
                    $event->sheet->setCellValue('H2',"Camera NG");
                    $event->sheet->setCellValue('I2',"Lot Marking NG");
                    $event->sheet->setCellValue('J2',"PRINT CODE");
                    $event->sheet->setCellValue('K2',"Visual NG");
                    $event->sheet->setCellValue('L2',"Assesment #");
                    $event->sheet->setCellValue('M2',"Bundle #");
                    $event->sheet->setCellValue('N2',"Runcard #");
                    $event->sheet->setCellValue('O2',"Machine #");
                    $event->sheet->setCellValue('P2',"FVI Operator");
                    $event->sheet->setCellValue('Q2',"Material Name");
                    $event->sheet->setCellValue('R2',"Material Lot # (Resin Lot #)");
                    $event->sheet->setCellValue('S2',"Product Drawing");
                    $event->sheet->setCellValue('T2',"Product Drawing Rev.");
                    $event->sheet->setCellValue('U2',"CAV");
                    $event->sheet->setCellValue('V2',"识别表示 Special adoption document or any special instruction");
                    $event->sheet->setCellValue('W2',"Remarks");
                    $event->sheet->setCellValue('X2',"QC Inspector Name");


                    $event->sheet->setCellValue('Y2',"Operator Name");
                    $event->sheet->setCellValue('Y3',"Rotary Machine");
                    $event->sheet->setCellValue('Z3',"Camera Inspection");
                    $event->sheet->setCellValue('AA3',"Visual Inspection");

                    $event->sheet->setCellValue('AB2',"LOT NUMBER");

                    $event->sheet->setCellValue('AB3',"CN171P-02#IN");
                    $event->sheet->setCellValue('AC3',"CN171P-02#ME");
                    $event->sheet->setCellValue('AD3',"CT5869");
                    $event->sheet->setCellValue('AE3',"CT5870");

                    // dd($secondMoldingData);
                    for ($i=0; $i < count($secondMoldingData); $i++) {
                        $created_at = substr($secondMoldingData[$i]->created_at,0,10);

                        $event->sheet->setCellValue('A'.$start_col, $created_at);
                        $event->sheet->setCellValue('C'.$start_col, $secondMoldingData[$i]->production_lot);
                        $event->sheet->setCellValue('D'.$start_col, $secondMoldingData[$i]->po_quantity);
                        $event->sheet->setCellValue('G'.$start_col, $secondMoldingData[$i]->sec_molding_total_machine_output); // Darren
                        $event->sheet->setCellValue('O'.$start_col, $secondMoldingData[$i]->machine_number);
                        $event->sheet->setCellValue('Q'.$start_col, $secondMoldingData[$i]->material_name);
                        $event->sheet->setCellValue('R'.$start_col, $secondMoldingData[$i]->material_lot_number);
                        $event->sheet->setCellValue('S'.$start_col, $secondMoldingData[$i]->drawing_number);
                        $event->sheet->setCellValue('T'.$start_col, $secondMoldingData[$i]->revision_number);

                        $molding_ipqc_details = $this->get_ipqc_details($secondMoldingData[$i]->production_lot);// Darren

                        $event->sheet->setCellValue('V'.$start_col, $molding_ipqc_details->doc_no_urgent_direction); // Darren
                        $event->sheet->setCellValue('W'.$start_col, $molding_ipqc_details->remarks); // Darren
                        $event->sheet->setCellValue('X'.$start_col, $molding_ipqc_details->fullname); // Darren
                        if(str_contains($secondMoldingData[$i]->machine_number, '3')){
                            $event->sheet->setCellValue('U'.$start_col, 'AB');
                        }else{
                            $event->sheet->setCellValue('U'.$start_col, 'CD');
                        }

                        $event->sheet->setCellValue('Y'.$start_col, $secondMoldingData[$i]->r_machine_operator);

                        for ($u=0; $u < count($secondMoldingCameraData); $u++) {
                            if ($secondMoldingData[$i]->id == $secondMoldingCameraData[$u]->sec_molding_runcard_id) {

                                $event->sheet->setCellValue('H'.$start_col, $secondMoldingCameraData[$u]->camera_ng);
                                $event->sheet->setCellValue('Z'.$start_col, $secondMoldingCameraData[$u]->camera_operator);
                            }
                        }

                        for ($o=0; $o <count($secondMoldingVisualData); $o++) {
                            if ($secondMoldingData[$i]->id == $secondMoldingVisualData[$o]->sec_molding_runcard_id) {
                                $event->sheet->setCellValue('AA'.$start_col, $secondMoldingVisualData[$o]->visual_operator);
                            }
                        }

                        for ($y=0; $y <count($assemblyData); $y++) { 
                            if ($secondMoldingData[$i]->production_lot == $assemblyData[$y]->p_zero_two_prod_lot) {
                                $shipconData = $this->shipcon_data($assemblyData[$y]->fvi_lot_no, $assemblyData[$y]->fvi_po_no);

                                // dd($shipconData);
                                $event->sheet->setCellValue('E'.$start_col, $shipconData->shipment_date);
                                $event->sheet->setCellValue('F'.$start_col, $shipconData->shipment_qty);
                                $event->sheet->setCellValue('K'.$start_col, $this->total_ng_qty($assemblyData[$y]->asmbly_runcard_id));
                                $event->sheet->setCellValue('I'.$start_col, $this->get_lot_marking_ng($assemblyData[$y]->asmbly_runcard_id));
                                // dd($this->get_lot_marking_ng($assemblyData[$y]->asmbly_runcard_id));

                                $event->sheet->setCellValue('N'.$start_col, $assemblyData[$y]->runcard_no);
                                $event->sheet->setCellValue('M'.$start_col, $assemblyData[$y]->bundle_no);
                                $event->sheet->setCellValue('P'.$start_col, $assemblyData[$y]->operator_name);
                                $event->sheet->setCellValue('J'.$start_col, $assemblyData[$y]->assembly_oqc_lot_apps_print_lot);
                            }
                        }

                        $event->sheet->getDelegate()->getStyle('A4'.':'.'AE'.$start_col)->applyFromArray($styleBorderAll);
                        $start_col++;
                    }
                    
                    // $event->sheet->getDelegate()->getStyle('H4'.':'.'H'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    // $event->sheet->getDelegate()->getStyle('I4'.':'.'I'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    // $event->sheet->getDelegate()->getStyle('S4'.':'.'S'.$start_col)->getNumberFormat()->setFormatCode('0.00%');
                    $event->sheet->getDelegate()->getStyle('A4'.':'.'AE'.$start_col)->getAlignment()->setWrapText(true);
                    $event->sheet->getDelegate()->getStyle('A2:AE3')->applyFromArray($hv_center);
                    $event->sheet->getDelegate()->getStyle('A4'.':'.'AE'.$start_col)->applyFromArray($hv_center);
                    $event->sheet->getDelegate()->getStyle('A2:AE3')->getAlignment()->setWrapText(true);
                    $event->sheet->getDelegate()->getStyle('A2:AE3')->applyFromArray($styleBorderAll);

                }
            },
        ];
    }

    public function shipcon_data($lot_no, $po){
        $oqc_data = DB::connection('mysql_cn_ppts')
        ->table('oqc_lot_apps')
        ->join('oqc_inspections', 'oqc_inspections.oqc_lot_app_id', '=', 'oqc_lot_apps.id')
        ->where('oqc_lot_apps.logdel', 0)
        ->where('oqc_lot_apps.lot_no', $lot_no)
        ->where('oqc_lot_apps.po_no',$po)
        ->first();

        $shipcon = DB::connection('mysql_cn_ppts')
        ->table('d_label_histories')
        ->join('d_labels', 'd_label_histories.d_label_id', '=', 'd_labels.id')
        ->where('d_label_histories.packing_code', "{$oqc_data->packing_code}-{$oqc_data->packing_code_ext}")
        ->whereNull('d_label_histories.deleted_at')
        ->select('d_label_histories.*', 'd_labels.shipment_qty')
        ->first();

        return $shipcon;
    }

    public function total_ng_qty($assmbly_runcard_id){
        $total_ng_qty = DB::connection('mysql')
        ->table('assembly_runcard_stations')
        ->whereNull('deleted_at')
        ->where('assembly_runcards_id', $assmbly_runcard_id)
        ->sum('ng_quantity');

        return $total_ng_qty;

    }

    public function get_lot_marking_ng($assmbly_runcard_id){
        $total_ng_qty = DB::connection('mysql')
        ->table('assembly_runcard_stations as a')
        ->join('stations','stations.id','=','a.station' )
        ->whereNull('a.deleted_at')
        ->where('a.assembly_runcards_id', 195)
        ->select('a.*', 'stations.station_name', 'stations.id')
        ->get();

        $collection = collect($total_ng_qty)->filter(function ($total_ng_qty) {
            return false !== stristr($total_ng_qty->station_name, 'lot marking');
        });
        $ng = 0;
        if($collection){
            $ng = $collection[0]->ng_quantity;
        }

        return $ng;
    }

    public function get_ipqc_details($prod_lot_no){
        $ipqc_details = DB::connection('mysql')
        ->table('molding_assy_ipqc_inspections as a')
        ->join('users', 'users.id', 'a.ipqc_inspector_name')
        ->where('a.production_lot', trim($prod_lot_no))
        ->where('a.logdel', 0)
        ->where('a.process_category', 2)
        ->select('a.*', DB::raw("CONCAT(users.firstname,' ',users.lastname) AS fullname") )
        ->first();

        return $ipqc_details;
    }

}


