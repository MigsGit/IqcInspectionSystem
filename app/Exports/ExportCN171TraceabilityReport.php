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


class ExportCN171TraceabilityReport implements FromView, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ShippingInspector::all();
    // }
    use Exportable;

    protected $stamping_data_1;
    protected $tbl_whs_transaction;
    protected $stamping_data_2;
    // protected $receiving_data;

    // protected $device_name;

    function __construct(
    $stamping_data_1,
    $tbl_whs_transaction,
    $stamping_data_2
    ){
        $this->stamping_data_1 = $stamping_data_1;
        $this->tbl_whs_transaction = $tbl_whs_transaction;
        $this->stamping_data_2 = $stamping_data_2;
        // $this->receiving_data = $receiving_data;
    }

    public function view(): View
    {
        return view('exports.export_cn171_traceability_report', [
        ]);
    }
    public function title(): string{
        return "CN171";
    }

    public function registerEvents(): array
    {
        $stamping_data_1 = $this->stamping_data_1;
        $tbl_whs_transaction = $this->tbl_whs_transaction;
        $stamping_data_2 = $this->stamping_data_2;
        // $receiving_data = $this->receiving_data;

        $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                'bold'      =>  true,
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
                $hv_center,
                $hlv_center,
                $hrv_center,
                $styleBorderBottomThin,
                $styleBorderAll,
                $hlv_top,
                $hcv_top,
                $arial_font20,
                $arial_font8_bold,
                $stamping_data_1,
                $tbl_whs_transaction,
                $stamping_data_2
            ) {
                // code here
                $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(30);
                $event->sheet->getDelegate()->getRowDimension('2')->setRowHeight(35);
                $event->sheet->getDelegate()->getRowDimension('3')->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension('4')->setRowHeight(50);
                $event->sheet->getColumnDimension('A')->setWidth(10);
                $event->sheet->getColumnDimension('B')->setWidth(10);
                $event->sheet->getColumnDimension('C')->setWidth(20);
                $event->sheet->getColumnDimension('D')->setWidth(10);
                $event->sheet->getColumnDimension('E')->setWidth(10);
                $event->sheet->getColumnDimension('F')->setWidth(10);
                $event->sheet->getColumnDimension('G')->setWidth(10);
                $event->sheet->getColumnDimension('H')->setWidth(10);
                $event->sheet->getColumnDimension('I')->setWidth(10);
                $event->sheet->getColumnDimension('J')->setWidth(10);
                $event->sheet->getColumnDimension('K')->setWidth(10);
                $event->sheet->getColumnDimension('L')->setWidth(10);
                $event->sheet->getColumnDimension('M')->setWidth(10);
                $event->sheet->getColumnDimension('N')->setWidth(10);
                $event->sheet->getColumnDimension('O')->setWidth(10);
                $event->sheet->getColumnDimension('P')->setWidth(10);
                $event->sheet->getColumnDimension('Q')->setWidth(10);
                $event->sheet->getColumnDimension('R')->setWidth(10);
                $event->sheet->getColumnDimension('S')->setWidth(10);
                $event->sheet->getColumnDimension('T')->setWidth(10);
                $event->sheet->getColumnDimension('U')->setWidth(10);
                $event->sheet->getColumnDimension('V')->setWidth(10);
                $event->sheet->getColumnDimension('W')->setWidth(18);
                $event->sheet->getColumnDimension('X')->setWidth(10);
                $event->sheet->getColumnDimension('Y')->setWidth(10);
                $event->sheet->getColumnDimension('Z')->setWidth(10);
                $event->sheet->getColumnDimension('AA')->setWidth(10);
                $event->sheet->getColumnDimension('AB')->setWidth(10);
                $event->sheet->getColumnDimension('AC')->setWidth(10);
                $event->sheet->getColumnDimension('AD')->setWidth(10);
                $event->sheet->getColumnDimension('AE')->setWidth(10);
                $event->sheet->getColumnDimension('AF')->setWidth(10);
                $event->sheet->getColumnDimension('AG')->setWidth(10);
                $event->sheet->getColumnDimension('AH')->setWidth(10);
                $event->sheet->getColumnDimension('AI')->setWidth(10);
                $event->sheet->getColumnDimension('AJ')->setWidth(10);
                $event->sheet->getColumnDimension('AK')->setWidth(10);
                $event->sheet->getColumnDimension('AL')->setWidth(10);
                $event->sheet->getColumnDimension('AM')->setWidth(10);
                $event->sheet->getColumnDimension('AN')->setWidth(10);
                $event->sheet->getColumnDimension('AO')->setWidth(10);
                $event->sheet->getColumnDimension('AP')->setWidth(10);
                $event->sheet->getColumnDimension('AQ')->setWidth(10);
                $event->sheet->getColumnDimension('AR')->setWidth(10);
                $event->sheet->getColumnDimension('AS')->setWidth(10);
                $event->sheet->getColumnDimension('AT')->setWidth(10);
                $event->sheet->getColumnDimension('AU')->setWidth(10);
                $event->sheet->getColumnDimension('AV')->setWidth(10);
                $event->sheet->getColumnDimension('AW')->setWidth(10);
                $event->sheet->getColumnDimension('AX')->setWidth(10);
                $event->sheet->getColumnDimension('AY')->setWidth(10);
                $event->sheet->getColumnDimension('AZ')->setWidth(10);
                $event->sheet->getColumnDimension('BA')->setWidth(10);
                $event->sheet->getColumnDimension('BB')->setWidth(10);
                $event->sheet->getColumnDimension('BC')->setWidth(18);
                $event->sheet->getColumnDimension('BD')->setWidth(10);
                $event->sheet->getColumnDimension('BE')->setWidth(10);
                $event->sheet->getColumnDimension('BF')->setWidth(10);
                $event->sheet->getColumnDimension('BG')->setWidth(10);
                $event->sheet->getColumnDimension('BH')->setWidth(10);
                $event->sheet->getColumnDimension('BI')->setWidth(10);
                $event->sheet->getColumnDimension('BJ')->setWidth(10);
                $event->sheet->getColumnDimension('BK')->setWidth(10);
                // $event->sheet->getColumnDimension('BL')->setWidth(10);

                if(isset($stamping_data_1[0]->material_name)){
                    $event->sheet->setCellValue('A1', $stamping_data_1[0]->material_name .' Parts Lot Management Record');
                    $event->sheet->getDelegate()->mergeCells('A1:BK1');
                    $event->sheet->getDelegate()->getStyle('A1:BK1')->applyFromArray($arial_font20);
                }else{
                    // $event->sheet->setCellValue('A1', $stamping_data_1[0]->material_name .' Parts Lot Management Record');
                    $event->sheet->getDelegate()->mergeCells('A1:BK1');
                    $event->sheet->getDelegate()->getStyle('A1:BK1')->applyFromArray($arial_font20);
                }

                

                $event->sheet->getDelegate()->mergeCells('A2:AE2');
                $event->sheet->setCellValue('A2',"1ST STAMPING");
                $event->sheet->getDelegate()->getStyle('A2:AE2')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A2:AE2')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AF2:AM2');
                $event->sheet->setCellValue('AF2',"SPMC PLATING");
                $event->sheet->getDelegate()->getStyle('AF2:AM2')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AF2:AM2')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AN2:BK2');
                $event->sheet->setCellValue('AN2',"2ND STAMPING");
                $event->sheet->getDelegate()->getStyle('AN2:BK2')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AN2:BK2')->applyFromArray($arial_font12_bold);



                $event->sheet->getDelegate()->mergeCells('A3:U3');
                $event->sheet->setCellValue('A3',"MACHINE OPERATOR");
                $event->sheet->getDelegate()->getStyle('A3:U3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A3:U3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('V3:W3');
                $event->sheet->setCellValue('V3',"IPQC");
                $event->sheet->getDelegate()->getStyle('V3:W3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('V3:W3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('X3:Z3');
                $event->sheet->setCellValue('X3',"OQC");
                $event->sheet->getDelegate()->getStyle('X3:Z3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('X3:Z3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AA3:AE3');
                $event->sheet->setCellValue('AA3',"PACKING");
                $event->sheet->getDelegate()->getStyle('AA3:AE3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AA3:AE3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AF3:AI3');
                $event->sheet->setCellValue('AF3',"WAREHOUSE");
                $event->sheet->getDelegate()->getStyle('AF3:AI3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AF3:AI3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AJ3:AM3');
                $event->sheet->setCellValue('AJ3',"IQC");
                $event->sheet->getDelegate()->getStyle('AJ3:AM3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AJ3:AM3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('AN3:BA3');
                $event->sheet->setCellValue('AN3',"MACHINE OPERATOR");
                $event->sheet->getDelegate()->getStyle('AN3:BA3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('AN3:BA3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('BB3:BC3');
                $event->sheet->setCellValue('BB3',"IPQC");
                $event->sheet->getDelegate()->getStyle('BB3:BC3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('BB3:BC3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('BD3:BF3');
                $event->sheet->setCellValue('BD3',"OQC");
                $event->sheet->getDelegate()->getStyle('BD3:BF3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('BD3:BF3')->applyFromArray($arial_font12_bold);

                $event->sheet->getDelegate()->mergeCells('BG3:BK3');
                $event->sheet->setCellValue('BG3',"PACKING");
                $event->sheet->getDelegate()->getStyle('BG3:BK3')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('BG3:BK3')->applyFromArray($arial_font12_bold);


                $event->sheet->setCellValue('A4',"Operator Name");
                $event->sheet->setCellValue('B4',"Shift");
                $event->sheet->setCellValue('C4',"P.O Number");
                $event->sheet->setCellValue('D4',"Parts Code");
                $event->sheet->setCellValue('E4',"P.O Qty");
                $event->sheet->setCellValue('F4',"Drawing No.");
                $event->sheet->setCellValue('G4',"Revisions");
                $event->sheet->setCellValue('H4',"Material Name");
                $event->sheet->setCellValue('I4',"Material Lot #");
                $event->sheet->setCellValue('J4',"投入日 Production Date");
                $event->sheet->setCellValue('K4',"Production Lot #");
                $event->sheet->setCellValue('L4',"Input Coil Weight (Kg)");
                $event->sheet->setCellValue('M4',"PPC Target Ouput (Pins)");
                $event->sheet->setCellValue('N4',"Planned Loss (10%)");
                $event->sheet->setCellValue('O4',"Set-up pins ");
                $event->sheet->setCellValue('P4',"Adjustment Pins");
                $event->sheet->setCellValue('Q4',"QC samples");
                $event->sheet->setCellValue('R4',"Prod. Samples");
                $event->sheet->setCellValue('S4',"Total Machine Output");
                $event->sheet->setCellValue('T4',"Shipment Output");
                $event->sheet->setCellValue('U4',"Material Yield");
                $event->sheet->setCellValue('V4',"IPQC Inspector Name");
                $event->sheet->setCellValue('W4',"识别表示 Special adoption document or any special instruction");
                $event->sheet->setCellValue('X4',"OQC Inspector Name");
                $event->sheet->setCellValue('Y4',"Inspection Date");
                $event->sheet->setCellValue('Z4',"NG Sample");
                $event->sheet->setCellValue('AA4',"1st Press Yield");
                $event->sheet->setCellValue('AB4',"Packer");
                $event->sheet->setCellValue('AC4',"Packing Date");
                $event->sheet->setCellValue('AD4',"Total Shipment Output");
                $event->sheet->setCellValue('AE4',"Shipment Date");
                $event->sheet->setCellValue('AF4',"Received Date");
                $event->sheet->setCellValue('AG4',"SANNO Plating Lot #");
                $event->sheet->setCellValue('AH4',"Delivery Quantity");
                $event->sheet->setCellValue('AI4',"Plating Yield");
                $event->sheet->setCellValue('AJ4',"IQC Inspector Name");
                $event->sheet->setCellValue('AK4',"Keep Sample (Pins)");
                $event->sheet->setCellValue('AL4',"Inspection Date");
                $event->sheet->setCellValue('AM4',"Total Plated Pins");
                $event->sheet->setCellValue('AN4',"Operator name");
                $event->sheet->setCellValue('AO4',"Shift");
                $event->sheet->setCellValue('AP4',"投入日 Production Date");
                $event->sheet->setCellValue('AQ4',"Production Lot #");
                $event->sheet->setCellValue('AR4',"Input Pins");
                $event->sheet->setCellValue('AS4',"Target Output");
                $event->sheet->setCellValue('AT4',"Planned Loss(10%)");
                $event->sheet->setCellValue('AU4',"Set-up pins");
                $event->sheet->setCellValue('AV4',"Adjustment Pins");
                $event->sheet->setCellValue('AW4',"QC samples");
                $event->sheet->setCellValue('AX4',"Prod. Samples");
                $event->sheet->setCellValue('AY4',"Total Machine Output");
                $event->sheet->setCellValue('AZ4',"Shipment Output");
                $event->sheet->setCellValue('BA4',"Material Yield");
                $event->sheet->setCellValue('BB4',"IPQC Inspector Name");
                $event->sheet->setCellValue('BC4',"识别表示 Special adoption document or any special instruction");
                $event->sheet->setCellValue('BD4',"OQC Inspector Name");
                $event->sheet->setCellValue('BE4',"Inspection Date");
                $event->sheet->setCellValue('BF4',"NG Sample");
                $event->sheet->setCellValue('BG4',"2nd Press Yield");
                $event->sheet->setCellValue('BH4',"Packer");
                $event->sheet->setCellValue('BI4',"Packing Date");
                // $event->sheet->setCellValue('BJ4',"Discrepancy");
                $event->sheet->setCellValue('BJ4',"Total Shipment Output");
                $event->sheet->setCellValue('BK4',"Shipment Date");


                
                $event->sheet->getDelegate()->getStyle('A4:BK4')->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('A4:BK4')->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A4:BK4')->applyFromArray($arial_font8_bold);
                $event->sheet->getDelegate()->getStyle('A2:BK4')->applyFromArray($styleBorderAll);

                $start_col = 5;
                for ($i=0; $i < count($stamping_data_1); $i++) {
                    // if($stamping_data_1[$i]->stamping_cat == 1){
                        $event->sheet->getDelegate()->getStyle('A'.$start_col.':'.'BK'.$start_col)->applyFromArray($styleBorderAll);
                        $event->sheet->setCellValue('A'.$start_col, $stamping_data_1[$i]->user->firstname);
                        $event->sheet->setCellValue('B'.$start_col, $stamping_data_1[$i]->shift);
                        $event->sheet->setCellValue('C'.$start_col, $stamping_data_1[$i]->po_num);
                        $event->sheet->setCellValue('D'.$start_col, $stamping_data_1[$i]->part_code);
                        $event->sheet->setCellValue('E'.$start_col, $stamping_data_1[$i]->po_qty);
                        $event->sheet->setCellValue('F'.$start_col, $stamping_data_1[$i]->drawing_no);
                        $event->sheet->setCellValue('G'.$start_col, $stamping_data_1[$i]->drawing_rev);
                        $event->sheet->setCellValue('H'.$start_col, $stamping_data_1[$i]->material_name);
                        $event->sheet->setCellValue('I'.$start_col, $stamping_data_1[$i]->material_lot_no);
                        $event->sheet->setCellValue('J'.$start_col, $stamping_data_1[$i]->prod_date);
                        $event->sheet->setCellValue('K'.$start_col, $stamping_data_1[$i]->prod_lot_no);
                        $event->sheet->setCellValue('L'.$start_col, $stamping_data_1[$i]->input_coil_weight);
                        $event->sheet->setCellValue('M'.$start_col, "=L".$start_col."/0.005");
                        $event->sheet->setCellValue('N'.$start_col, "=M".$start_col."*0.1");
                        $event->sheet->setCellValue('O'.$start_col, $stamping_data_1[$i]->set_up_pins);
                        $event->sheet->setCellValue('P'.$start_col, $stamping_data_1[$i]->adj_pins);
                        $event->sheet->setCellValue('Q'.$start_col, $stamping_data_1[$i]->qc_samp);
                        $event->sheet->setCellValue('R'.$start_col, $stamping_data_1[$i]->prod_samp);
                        $event->sheet->setCellValue('S'.$start_col, $stamping_data_1[$i]->total_mach_output);
                        $event->sheet->setCellValue('T'.$start_col,"=S".$start_col."-(SUM(O".$start_col.":R".$start_col."))");
                        $event->sheet->setCellValue('U'.$start_col, ("=T".$start_col."/S".$start_col));


                        if (isset($stamping_data_1[$i]->stamping_ipqc)) {
                            $event->sheet->setCellValue('V'.$start_col, $stamping_data_1[$i]->stamping_ipqc->ipqc_insp_name->firstname);
                        }

                        if (isset($stamping_data_1[$i]->oqc_details)) {
                            $event->sheet->setCellValue('X'.$start_col, $stamping_data_1[$i]->oqc_details->inspector);
                            $event->sheet->setCellValue('Y'.$start_col, $stamping_data_1[$i]->oqc_details->date_inspected);
                            $event->sheet->setCellValue('Z'.$start_col, $stamping_data_1[$i]->oqc_details->num_of_defects);
                            $event->sheet->setCellValue('AA'.$start_col, ("=(T".$start_col."-Z".$start_col.")/T".$start_col.""));
                        }

                        if (isset($stamping_data_1[$i]->oqc_details->packing_info)) {
                            $packing_date = substr($stamping_data_1[$i]->oqc_details->packing_info->validated_date_packer,0,10);
                            if(isset($stamping_data_1[$i]->oqc_details->packing_info->user_validated_by_info)){

                                $event->sheet->setCellValue('AB'.$start_col, $stamping_data_1[$i]->oqc_details->packing_info->user_validated_by_info->firstname);
                            }else{
                                $event->sheet->setCellValue('AB'.$start_col, '');

                            }
                            $event->sheet->setCellValue('AC'.$start_col, $packing_date);
                            $event->sheet->setCellValue('AD'.$start_col, ("=T".$start_col."-Z".$start_col));
                        }else{
                            $event->sheet->setCellValue('AB'.$start_col, '');

                        }

                        for ($j=0; $j <count($stamping_data_1[$i]->packing_list_details) ; $j++) { 
                            $event->sheet->setCellValue('AE'.$start_col, $stamping_data_1[$i]->packing_list_details[$j]->pick_up_date);
                        }

                        for ($u=0; $u <count($stamping_data_1[$i]->receiving_info) ; $u++) { 
                            $receiving_date = substr($stamping_data_1[$i]->receiving_info[$u]->updated_at,0,10);
                            $event->sheet->setCellValue('AF'.$start_col, $receiving_date);
                            $event->sheet->setCellValue('AG'.$start_col, $stamping_data_1[$i]->receiving_info[$u]->supplier_lot_no);
                            $event->sheet->setCellValue('AH'.$start_col, $stamping_data_1[$i]->receiving_info[$u]->supplier_quantity);

                            if (isset($stamping_data_1[$i]->receiving_info[$u]->iqc_info->user_iqc)) {
                                $event->sheet->setCellValue('AJ'.$start_col, $stamping_data_1[$i]->receiving_info[$u]->iqc_info->user_iqc->firstname);
                            }
                            
                            if (isset($stamping_data_1[$i]->receiving_info[$u]->iqc_info)) {
                                $event->sheet->setCellValue('AK'.$start_col, $stamping_data_1[$i]->receiving_info[$u]->iqc_info->sampling_size);
                                $event->sheet->setCellValue('AL'.$start_col, $stamping_data_1[$i]->receiving_info[$u]->iqc_info->date_inspected);
                            }
                        }
                        $event->sheet->setCellValue('AI'.$start_col, ("=AH".$start_col."/AD".$start_col));
                        $event->sheet->setCellValue('AM'.$start_col, ("=AH".$start_col."-AK".$start_col));

                        $event->sheet->setCellValue('AI'.$start_col, ("=AH".$start_col."/AD".$start_col));
                        $event->sheet->setCellValue('AM'.$start_col, ("=AH".$start_col."-AK".$start_col));       

                        for ($u=0; $u <count($stamping_data_2); $u++) { 
                            $stamping2_material_lot = substr($stamping_data_2[$u]->material_lot_no, 0, strpos($stamping_data_2[$u]->material_lot_no, "/"));
                            if($stamping_data_1[$i]->prod_lot_no == $stamping2_material_lot){
                                $event->sheet->setCellValue('AN'.$start_col, $stamping_data_2[$u]->user->firstname);
                                $event->sheet->setCellValue('AO'.$start_col, $stamping_data_2[$u]->shift);
                                $event->sheet->setCellValue('AP'.$start_col, $stamping_data_2[$u]->prod_date);
                                $event->sheet->setCellValue('AQ'.$start_col, $stamping_data_2[$u]->prod_lot_no);
                                $event->sheet->setCellValue('AR'.$start_col, ("=AM".$start_col));
                                $event->sheet->setCellValue('AS'.$start_col, ("=AR".$start_col."-AT".$start_col));
                                $event->sheet->setCellValue('AT'.$start_col, "=AR".$start_col."*0.1");
                                $event->sheet->setCellValue('AU'.$start_col, $stamping_data_2[$u]->set_up_pins);
                                $event->sheet->setCellValue('AV'.$start_col, $stamping_data_2[$u]->adj_pins);
                                $event->sheet->setCellValue('AW'.$start_col, $stamping_data_2[$u]->qc_samp);
                                $event->sheet->setCellValue('AX'.$start_col, $stamping_data_2[$u]->prod_samp);
                                $event->sheet->setCellValue('AY'.$start_col, $stamping_data_2[$u]->total_mach_output);
                                $event->sheet->setCellValue('AZ'.$start_col,"=AY".$start_col."-(SUM(AU".$start_col.":AX".$start_col."))");
                                $event->sheet->setCellValue('BA'.$start_col, ("=AZ".$start_col."/AY".$start_col));

                                if (isset($stamping_data_2[$u]->stamping_ipqc)) {
                                    $event->sheet->setCellValue('BB'.$start_col, $stamping_data_2[$u]->stamping_ipqc->ipqc_insp_name->firstname);
                                }
            
                                if (isset($stamping_data_2[$u]->oqc_details)) {
                                    $event->sheet->setCellValue('BD'.$start_col, $stamping_data_2[$u]->oqc_details->inspector);
                                    $event->sheet->setCellValue('BE'.$start_col, $stamping_data_2[$u]->oqc_details->date_inspected);
                                    $event->sheet->setCellValue('BF'.$start_col, $stamping_data_2[$u]->oqc_details->num_of_defects);
                                    $event->sheet->setCellValue('BG'.$start_col, ("=(AZ".$start_col."-BF".$start_col.")/AZ".$start_col.""));
            
                                    if (isset($stamping_data_2[$u]->oqc_details->first_molding_info)){
                                        $second_molding_packing_date = substr($stamping_data_2[$u]->oqc_details->first_molding_info->date_counted,0,10);
            
                                        $event->sheet->setCellValue('BH'.$start_col, $stamping_data_2[$u]->oqc_details->first_molding_info->user_validated_by_info->firstname);
                                        $event->sheet->setCellValue('BI'.$start_col, $second_molding_packing_date);
                                        $event->sheet->setCellValue('BJ'.$start_col, $stamping_data_2[$u]->oqc_details->first_molding_info->shipment_output);
                                    }
                                }

                                for ($y=0; $y < count($tbl_whs_transaction); $y++) { 
                                    if($tbl_whs_transaction[$y]->Lot_number == $stamping_data_2[$u]->prod_lot_no){
                                        $event->sheet->setCellValue('BK'.$start_col, substr($tbl_whs_transaction[$y]->LastUpdate,0,10));
                                    }
                                }
                            }
                        }
                        
                    // }

                    $start_col++;
                }

                $event->sheet->getDelegate()->getStyle('U5'.':'.'U'.$start_col)->getNumberFormat()->setFormatCode('0.00%'); 
                $event->sheet->getDelegate()->getStyle('AA5'.':'.'AA'.$start_col)->getNumberFormat()->setFormatCode('0.00%'); 
                $event->sheet->getDelegate()->getStyle('AI5'.':'.'AI'.$start_col)->getNumberFormat()->setFormatCode('0.00%'); 
                $event->sheet->getDelegate()->getStyle('BA5'.':'.'BA'.$start_col)->getNumberFormat()->setFormatCode('0.00%'); 
                $event->sheet->getDelegate()->getStyle('BG5'.':'.'BG'.$start_col)->getNumberFormat()->setFormatCode('0.00%'); 
                $event->sheet->getDelegate()->getStyle('A5'.':'.'BK'.$start_col)->getAlignment()->setWrapText(true);
                $event->sheet->getDelegate()->getStyle('A5'.':'.'BK'.$start_col)->applyFromArray($hv_center);
                $event->sheet->getDelegate()->getStyle('C5'.':'.'C'.$start_col)->getNumberFormat()->setFormatCode('000000000000000');
                
            },
        ];
    }

}