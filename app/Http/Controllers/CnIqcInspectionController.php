<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\CnIqcInspection;

use Yajra\DataTables\DataTables;
use App\Interfaces\FileInterface;
use App\Models\VwCnListOfReceived;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;

use App\Models\CnIqcInspectionsMod;
use App\Interfaces\ResourceInterface;
use App\Models\VwCnFixedListOfReceived;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CnIqcInspectionRequest;

class CnIqcInspectionController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    protected $fileInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface,FileInterface $fileInterface)
    {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
        $this->fileInterface = $fileInterface;
    }
    public function loadCnWhsPackaging(Request $request)
    {
        try {
            $categoryMaterial = $request->categoryMaterial;
            $whereWhsTransactionId =   $this->commonInterface->readIqcInspectionByMaterialCategory(CnIqcInspection::class,$categoryMaterial);
            // Read IqcInspection (Material already Inspected) then do not
            // display it to the ON-GOING status
            if( isset( $request->invoiceNo ) && isset($request->partCode) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_whs_packaging')
                ->select(' SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    tbl_received.receivedate as "ReceivedDate",tbl_received.rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A"
                    AND tbl_received.invoiceno = "'.$request->invoiceNo.'"
                    AND tbl_itemList.partcode = "'.$request->partCode.'")
                    AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "")
                    -- AND tbl_received.lot_no != "N/A"
                    '.$whereWhsTransactionId.'
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_whs_packaging')
                ->select('SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    tbl_received.receivedate as "ReceivedDate",tbl_received.rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    AND tbl_itemList.is_iqc_inspection = 1
                    AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A")
                    AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "")
                    -- AND tbl_received.lot_no != "N/A"
                    '.$whereWhsTransactionId.'
                ');
            }
            return DataTables::of($tbl_whs_trasanction)
            ->addColumn('rawBulkCheckBox', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<input class='checkBulkRopCnIqcInspection d-none' type='checkbox' pkid-received='".$row->receiving_detail_id."' id='checkBulkRopCnIqcInspection'>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('rawAction', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1' pkid-received='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('rawStatus', function($row){
                $result = '';
                $result .= '<center>';
                $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
                $result .= '</center>';
                return $result;
            })
            ->rawColumns(['rawBulkCheckBox','rawAction','rawStatus'])

            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadCnFixedWhsPackaging(Request $request)
    {
        try {
            $categoryMaterial = $request->categoryMaterial;
            $whereWhsTransactionId =   $this->commonInterface->readIqcInspectionByMaterialCategory(CnIqcInspection::class,$categoryMaterial);
            // Read IqcInspection (Material already Inspected) then do not
            // display it to the ON-GOING status
            if( isset( $request->invoiceNo ) && isset($request->partCode) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_fixed_whs_packaging')
                ->select('SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    tbl_received.receivedate as "ReceivedDate",tbl_received.rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    AND tbl_itemList.is_iqc_inspection = 1
                    AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A"
                    AND tbl_received.invoiceno = "'.$request->invoiceNo.'"
                    AND tbl_itemList.partcode = "'.$request->partCode.'")
                    AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "")
                    -- AND tbl_received.lot_no != "N/A"
                    '.$whereWhsTransactionId.'
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_fixed_whs_packaging')
                ->select('SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    tbl_received.receivedate as "ReceivedDate",tbl_received.rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    AND tbl_itemList.is_iqc_inspection = 1
                    AND tbl_itemList.is_iqc_inspection = 1
                    AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A")
                    AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "")
                    -- AND tbl_received.lot_no != "N/A"
                    '.$whereWhsTransactionId.'
                ');
            }
            return DataTables::of($tbl_whs_trasanction)
            ->addColumn('rawBulkCheckBox', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<input class='checkBulkFixedCnIqcInspection d-none' type='checkbox' pkid-received='".$row->receiving_detail_id."' id='checkBulkFixedCnIqcInspection'>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('rawAction', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-outline-info btn-sm mr-1' pkid-received='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
                return $result;
            })
            ->addColumn('rawStatus', function($row){
                $result = '';
                $result .= '<center>';
                $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
                $result .= '</center>';
                return $result;
            })
            ->rawColumns(['rawBulkCheckBox','rawAction','rawStatus'])

            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadCnIqcInspection(Request $request){
        try {
            /*  Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            NOTE: If the data exist to iqc_inspections it means the data is already inspected
        */
         if( isset( $request->lotNum ) ){
                $tbl_iqc_inspected = DB::connection('mysql')
                ->select(' SELECT *
                    FROM cn_iqc_inspections
                    WHERE 1=1
                    AND iqc_category_material_id = "'.$request->category_material.'"
                    AND deleted_at IS NULL AND judgement >= 1
                    AND lot_no = "'.$request->lotNum.'"
                    ORDER BY created_at DESC
                ');
            }else{
                $tbl_iqc_inspected = DB::connection('mysql')
                ->select('SELECT *
                    FROM cn_iqc_inspections
                    WHERE 1=1
                    AND iqc_category_material_id = "'.$request->category_material.'"
                    AND deleted_at IS NULL
                    AND judgement >= 1
                    ORDER BY created_at DESC
                ');
            }
            return DataTables::of($tbl_iqc_inspected)
            ->addColumn('rawAction', function($row){
                $result = '';
                $result .= '<center>';
                // if($row->inspector == Auth::user()->id || Auth::user()->username =='mclegaspi'){ //nmodify
                    $result .= "<button class='btn btn-outline-info btn-sm mr-1' iqc-inspection-id='".$row->id."'id='btnEditIqcInspection' inspector='".$row->inspector."'><i class='fa-solid fa-pen-to-square'></i></button>";
                // }
                $result .= '</center>';
                return $result;
            })

            ->addColumn('rawStatus', function($row){
                // return $row->judgement;
                $result = '';
                $backgound = '';
                $judgement = '';
                $result .= '<center>';
                switch ($row->judgement) {
                    case 1:
                        $judgement = 'Accepted';
                        $backgound = 'bg-success';

                        break;
                    case 2:
                        $judgement = 'Reject';
                        $backgound = 'bg-danger';
                        break;
                    case 3:
                        $judgement = 'Special Acceptance';
                        $backgound = 'bg-warning';
                        break;

                    default:
                        $judgement = 'On-going';
                        $backgound = 'bg-primary';
                        break;
                }
                $result .= '<span class="badge rounded-pill '.$backgound.' ">'.$judgement.'</span>';
                $result .= '</center>';
                return $result;
            })
            ->addColumn('app_ctrl_no', function($row){
                $result = '';
                $result .= $row->app_no . $row->app_no_extension;
                return $result;
            })
            ->addColumn('time_inspected', function($row){
                $result = '';
                $result .= '<center>';
                $result .= $row->time_ins_from.'-'.$row->time_ins_to;
                $result .= '</center>';
                return $result;
            })
            ->addColumn('qc_inspector', function($row){
                $qc_inspector = User::where('id',$row->inspector)->get();
                $result = '';
                $result .= $qc_inspector[0]->name;
                return $result;
            })
            ->rawColumns(['rawAction','rawStatus','app_ctrl_no','qc_inspector','time_inspected',])
            ->make(true);

        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getCnWhsPackagingById(Request $request)
    {
        try {
            // return $request->arr_pkid_received;
            $generateControlNumber = $this->commonInterface->generateControlNumber(CnIqcInspection::class,$request->iqc_category_material_id);
            if( isset($request->arr_pkid_received)  ){
                $vwListOfReceived =  VwCnListOfReceived::select(
                    [
                        'pkid_received as whs_transaction_id',
                        'invoiceno as invoice_no',
                        'lot_no as lot_no',
                        'partcode as partcode',
                        'partname as partname',
                        'supplier as supplier',
                        'rcvqty as total_lot_qty',
                    ]
                )
                ->whereIn('pkid_received',$request->arr_pkid_received)
                ->get();
                $sumTotalLotQty = $vwListOfReceived->sum('total_lot_qty');
                $lotNo = $vwListOfReceived->pluck('lot_no')->implode(', ');
                $qtyPerLot = $vwListOfReceived->pluck('qty')->implode(', ');
                $whsTransactionId = $vwListOfReceived->pluck('whs_transaction_id')->implode(', ');
                $cnWhsReceivedPackaging = $vwListOfReceived->map(function($row) use($sumTotalLotQty,$lotNo,$whsTransactionId,$qtyPerLot){
                    // return implode(',',$row->lot_no);
                    return [
                        'whs_transaction_id'    => $whsTransactionId,
                        'invoice_no' => $row->invoice_no,
                        'partcode' => $row->partcode,
                        'partname'  => $row->partname,
                        'supplier'  => $row->supplier,
                        'lot_no'    => $lotNo,
                        'total_lot_qty'    => $sumTotalLotQty,
                        'qty_per_lot'    => $qtyPerLot,

                    ];

                })->toArray();

                return response()->json([
                    'is_success' => 'true',
                    'cnWhsReceivedPackaging' => $cnWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }else{
                $query = $this->resourceInterface->readCustomEloquent( VwCnListOfReceived::class);
                $cnWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get(
                    [
                        'pkid_received as whs_transaction_id',
                        'invoiceno as invoice_no',
                        'lot_no as lot_no',
                        'partcode as partcode',
                        'partname as partname',
                        'supplier as supplier',
                        'rcvqty as total_lot_qty',
                    ]
                );

                return response()->json(['is_success' => 'true',
                    'cnWhsReceivedPackaging' => $cnWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getCnFixedWhsPackagingById(Request $request)
    {
        try {
            // return $request->arr_pkid_received;
            $generateControlNumber = $this->commonInterface->generateControlNumber(CnIqcInspection::class,$request->iqc_category_material_id);
            if( isset($request->arr_pkid_received)  ){
                $vwListOfReceived =  VwCnFixedListOfReceived::select(
                    [
                        'pkid_received as whs_transaction_id',
                        'invoiceno as invoice_no',
                        'lot_no as lot_no',
                        'partcode as partcode',
                        'partname as partname',
                        'supplier as supplier',
                        'rcvqty as total_lot_qty',
                    ]
                )
                ->whereIn('pkid_received',$request->arr_pkid_received)
                ->get();
                $sumTotalLotQty = $vwListOfReceived->sum('total_lot_qty');
                $lotNo = $vwListOfReceived->pluck('lot_no')->implode(', ');
                $qtyPerLot = $vwListOfReceived->pluck('qty')->implode(', ');
                $whsTransactionId = $vwListOfReceived->pluck('whs_transaction_id')->implode(', ');
                $cnWhsReceivedPackaging = $vwListOfReceived->map(function($row) use($sumTotalLotQty,$lotNo,$whsTransactionId,$qtyPerLot){
                    return [
                        'whs_transaction_id'    => $whsTransactionId,
                        'invoice_no' => $row->invoice_no,
                        'partcode' => $row->partcode,
                        'partname'  => $row->partname,
                        'supplier'  => $row->supplier,
                        'lot_no'    => $lotNo,
                        'total_lot_qty'    => $sumTotalLotQty,
                        'qty_per_lot'    => $qtyPerLot,

                    ];
                })->toArray();

                return response()->json([
                    'is_success' => 'true',
                    'cnWhsReceivedPackaging' => $cnWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }else{
                $query = $this->resourceInterface->readCustomEloquent( VwCnFixedListOfReceived::class);
                $cnWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get(
                    [
                        'pkid_received as whs_transaction_id',
                        'invoiceno as invoice_no',
                        'lot_no as lot_no',
                        'partcode as partcode',
                        'partname as partname',
                        'supplier as supplier',
                        'rcvqty as total_lot_qty',
                    ]
                );
                return response()->json(['is_success' => 'true',
                    'cnWhsReceivedPackaging' => $cnWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }

        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getCnIqcInspectionById(Request $request){
        try {
            $tbl_whs_trasanction = CnIqcInspection::with('cn_iqc_inspections_mods','cn_iqc_inspections_mods.iqc_dropdown_detail','user_iqc')
            ->where('id',$request->iqc_inspection_id)
            ->get(['cn_iqc_inspections.id as iqc_inspection_id','cn_iqc_inspections.*']);
            return response()->json(['is_success' => 'true','tbl_whs_trasanction'=>$tbl_whs_trasanction]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function saveCnIqcInspection(CnIqcInspectionRequest $request)
    {
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $iqcInspectionShift = $this->commonInterface->getIqcInspectionShift();
            $mod_lot_no = explode(',',$request->lotNo);
            $mod_defects = explode(',',$request->modeOfDefects);
            $mod_lot_qty = explode(',',$request->lotQty);
            $arr_sum_mod_lot_qty = array_sum($mod_lot_qty);
            $generateControlNumber = $this->commonInterface->generateControlNumber(CnIqcInspection::class,$request->iqc_category_material_id);
            $appNoExtension = $generateControlNumber['app_no_extension'];
            $requestValidated = $request->validated();
            if(isset($request->iqc_inspection_id)){ //Edit
                $iqc_inspections_id = $request->iqc_inspection_id;
                //Save Iqc Inspection from Reject to Special Acceptance Judgement
                $is_iqc_inspection = CnIqcInspection::where('id', $iqc_inspections_id)->get(['judgement']);
                if( count($is_iqc_inspection ) > 0){
                    if( $is_iqc_inspection[0]->judgement == 2){
                        $create_iqc_inspection_id = CnIqcInspection::insertGetId($requestValidated);
                        /*  All not required fields should to be inside the update method below
                            NOTE: the name of fields must be match in column name
                        */
                        CnIqcInspection::where('id', $create_iqc_inspection_id)
                        ->update([
                            'judgement' => 3,
                            'no_of_defects' => $arr_sum_mod_lot_qty,
                            'remarks' => $request->remarks,
                            'inspector' => session('rapidx_user_id'),
                            'created_at' => date('Y-m-d H:i:s'),
                            'shift' => $iqcInspectionShift,
                        ]);
                    }else{
                        //Update Iqc Inspection if Accepted
                        CnIqcInspection::where('id', $iqc_inspections_id)->update($requestValidated); //PO and packinglist number
                        CnIqcInspection::where('id', $iqc_inspections_id)
                        ->update([
                            // 'judgement' => $request->judgement,
                            'no_of_defects' => $arr_sum_mod_lot_qty,
                            'remarks' => $request->remarks,
                            'inspector' => session('rapidx_user_id'),
                            'shift' => $iqcInspectionShift
                        ]);
                    }
                }
            }else{ //Add
                /* All required fields is the $request validated, check the column is IqcInspectionRequest
                    NOTE: the name of fields must be match in column name
                */
                $create_iqc_inspection_id = CnIqcInspection::insertGetId($requestValidated);
                /*  All not required fields should to be inside the update method below
                    NOTE: the name of fields must be match in column name
                */
                CnIqcInspection::where('id', $create_iqc_inspection_id)
                ->update([
                    'app_no_extension' => $appNoExtension,
                    'invoice_no' => $request->invoice_no,
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'shift' => $iqcInspectionShift,
                    'created_at' => date('Y-m-d H:i:s'),

                ]);

                $iqc_inspections_id = $create_iqc_inspection_id;
            }
            /* Uploading of file if checked & iqc_coc_file is exist*/
            if(isset($request->iqc_coc_file) ){
                $original_filename = $request->file('iqc_coc_file')->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = $this->fileInterface->Slug($original_filename, '_', '.');
                Storage::putFileAs('public/cn_iqc_inspection_coc', $request->iqc_coc_file,  $iqc_inspections_id .'_'. $filtered_filename);


                CnIqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'iqc_coc_file' => $filtered_filename,
                    'iqc_coc_file_name' => $original_filename
                ]);
            }

            /* Get iqc_inspections_id, delete the previous MOD then  save new MOD*/
            if($request->accepted == 1){
                CnIqcInspectionsMod::where('cn_iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                CnIqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'no_of_defects' => 0,
                ]);
            }
            if(isset($request->modeOfDefects)   && $request->accepted == 0){
                CnIqcInspectionsMod::where('cn_iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                foreach ( $mod_lot_no as $key => $value) {
                    CnIqcInspectionsMod::insert([
                        'cn_iqc_inspection_id'    => $iqc_inspections_id,
                        'lot_no'                => $mod_lot_no[$key],
                        'mode_of_defects'       => $mod_defects[$key],
                        'quantity'              => $mod_lot_qty[$key],
                        'created_at'            => date('Y-m-d H:i:s')
                    ]);
                }
            }else{
                if(CnIqcInspectionsMod::where('cn_iqc_inspection_id', $iqc_inspections_id)->exists()){
                    CnIqcInspectionsMod::where('cn_iqc_inspection_id', $iqc_inspections_id)->update([
                        'deleted_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
            DB::commit();
            return response()->json( [ 'result' => 1 ] );
        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }
}
