<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PpdIqcInspectionRequest;

use App\Models\User;
use App\Models\PpdIqcInspection;
use App\Models\VwPpdListOfReceived;
use App\Models\PpdIqcInspectionsMod;
use App\Interfaces\CommonInterface;
use App\Interfaces\FileInterface;
use App\Interfaces\ResourceInterface;

class PpdIqcInspectionController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    protected $fileInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface,FileInterface $fileInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
        $this->fileInterface = $fileInterface;
    }

    public function loadPpdIqcInspection(Request $request)
    {
        try {
        /*  Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            NOTE: If the data exist to iqc_inspections it means the data is already inspected
        */

            if( isset( $request->lotNum ) ){
                $tbl_iqc_inspected = DB::connection('mysql')
                ->select(' SELECT *
                    FROM ppd_iqc_inspections
                    WHERE 1=1
                    AND iqc_category_material_id = "'.$request->category_material.'"
                    AND deleted_at IS NULL AND judgement >= 1
                    AND lot_no = "'.$request->lotNum.'"
                    ORDER BY created_at DESC
                ');
            }else{
                $tbl_iqc_inspected = DB::connection('mysql')
                ->select('SELECT *
                    FROM ppd_iqc_inspections
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
            return $e;
        }
    }
    public function loadWhsTransaction(Request $request)
    { //RAPID WHS Whs Transaction
        /*  Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
            Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
        */
        // InvoiceNo
        // AND tbl_itemList.is_iqc_inspection = 1
        //                   AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A"
        //                   AND tbl_received.invoiceno = "'.$request->invoiceNo.'"
        //                   AND tbl_itemList.partcode = "'.$request->partCode.'")
        //                   AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "N/A" AND tbl_received.lot_no != "")
        //                   '.$whereWhsTransactionId.'
        if( isset( $request->invoiceNo ) && isset($request->partCode) ){
           $tbl_whs_trasanction = DB::connection('mysql_rapid_pps')
            ->select(' SELECT  whs.*,whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.inspection_class
                FROM tbl_WarehouseTransaction whs_transaction
                INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
                WHERE 1=1
                AND whs.Factory = 3
                AND whs_transaction.InvoiceNo = "'.$request->invoiceNo.'"
                AND whs.PartNumber = "'.$request->partCode.'"
                AND  whs_transaction.inspection_class = 1
                AND whs_transaction.Lot_number IS NOT NULL
                ORDER BY whs.PartNumber DESC
            ');
        }else{
            $tbl_whs_trasanction = DB::connection('mysql_rapid_pps')
            ->select('SELECT  whs.*,whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.inspection_class
                FROM tbl_WarehouseTransaction whs_transaction
                INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
                WHERE 1=1
                AND whs.Factory = 3
                AND whs_transaction.inspection_class = 1
                AND whs_transaction.Lot_number IS NOT NULL
                ORDER BY whs.PartNumber DESC
            ');
        }

        return DataTables::of($tbl_whs_trasanction)
        ->addColumn('rawBulkCheckBox', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<input class='checkBulkIqcInspection' type='checkbox' pkid-received='".$row->pkid."' id='checkBulkIqcInspection'>";
            $result .= '</center>';
            return $result;
        })
        ->addColumn('rawAction', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-outline-info btn-sm mr-1' whs-trasaction-id='".$row->pkid."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
        ->rawColumns(['rawAction','rawStatus','rawBulkCheckBox'])
        ->make(true);
    }
    public function loadPpdWhsPackaging(Request $request)
    {
        try {

            // Read IqcInspection (Material already Inspected) then do not
            // display it to the ON-GOING status
            $categoryMaterial = $request->categoryMaterial;
            $whereWhsTransactionId =   $this->commonInterface->readIqcInspectionByMaterialCategory(PpdIqcInspection::class,$categoryMaterial);
            if( isset( $request->invoiceNo ) && isset($request->partCode) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ppd_whs_packaging')
                ->select('SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    receivedate as "ReceivedDate",rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    AND tbl_itemList.is_iqc_inspection = 1
                    '.$whereWhsTransactionId.'


                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ppd_whs_packaging')
                ->select('SELECT tbl_received.pkid_received as "receiving_detail_id",tbl_received.supplier as "Supplier",tbl_itemList.partcode as "PartNumber",
                    tbl_itemList.partname as "MaterialType",tbl_received.lot_no as "Lot_number",tbl_received.invoiceno as "InvoiceNo",
                    receivedate as "ReceivedDate",rcvqty as "TotalLotQty"
                    FROM  tbl_received tbl_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.pkid_itemlist = tbl_received.fkid_itemlist
                    WHERE 1=1
                    '.$whereWhsTransactionId.'


                ');
                // AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A")
                // AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "N/A" AND tbl_received.lot_no != "")
                //'.$whereWhsTransactionId.'


                //  AND tbl_itemList.is_iqc_inspection = 1
                //  AND (tbl_received.invoiceno IS NOT NULL AND tbl_received.invoiceno != "N/A")
                //  AND (tbl_received.lot_no IS NOT NULL AND tbl_received.lot_no != "N/A" AND tbl_received.lot_no != "")
                //  '.$whereWhsTransactionId.'
            }
            return DataTables::of($tbl_whs_trasanction)
            ->addColumn('rawBulkCheckBox', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<input class='checkBulkPpdIqcInspection' type='checkbox' pkid-received='".$row->receiving_detail_id."' id='checkBulkPpdIqcInspection'>";
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
            ->rawColumns(['rawAction','rawStatus','rawBulkCheckBox'])
            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getWhsReceivingById(Request $request)
    {

        $generateControlNumber = $this->commonInterface->generateControlNumber(PpdIqcInspection::class,$request->iqc_category_material_id);
        if( isset($request->arr_pkid_received)  ){
            $implodeArrPkidReceived = implode(',',$request->arr_pkid_received);

           $tblWhsTrasanction = DB::connection('mysql_rapid_pps')
            ->select('
                SELECT whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.Username as "whs_transaction_username",
                whs_transaction.LastUpdate as "whs_transaction_lastupdate",whs_transaction.inspection_class,
                whs_transaction.InvoiceNo as "invoice_no",whs_transaction.Lot_number as "lot_no",whs_transaction.In as "total_lot_qty",
                whs.PartNumber as "partcode",whs.MaterialType as "partname",whs.Supplier as supplier,
                whs.*,whs.id as "whs_id",whs.Username as "whs_username",whs.LastUpdate as "whs_lastupdate"
                FROM tbl_WarehouseTransaction whs_transaction
                INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
                WHERE whs_transaction.pkid IN ('.$implodeArrPkidReceived.')
            ');
            $tblWhsTrasanction = collect($tblWhsTrasanction);
            $sumTotalLotQty = $tblWhsTrasanction->sum('total_lot_qty');
            $qtyPerLot = $tblWhsTrasanction->pluck('total_lot_qty')->implode(', ');
            $lotNo = $tblWhsTrasanction->pluck('lot_no')->implode(', ');
            $whsTransactionId = $tblWhsTrasanction->pluck('whs_transaction_id')->implode(', ');
            $tblWhsTrasanction = $tblWhsTrasanction->map(function($row) use($sumTotalLotQty,$lotNo,$whsTransactionId,$qtyPerLot){

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
                'tblWhsTrasanction' => $tblWhsTrasanction[0],
                'generateControlNumber' => $generateControlNumber
            ]);
        }else{
            $tblWhsTrasanction = DB::connection('mysql_rapid_pps')
            ->select('
                SELECT whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.Username as "whs_transaction_username",
                whs_transaction.LastUpdate as "whs_transaction_lastupdate",whs_transaction.inspection_class,
                whs_transaction.InvoiceNo as "invoice_no",whs_transaction.Lot_number as "lot_no",whs_transaction.In as "total_lot_qty",
                whs.PartNumber as "partcode",whs.MaterialType as "partname",whs.Supplier as supplier,
                whs.*,whs.id as "whs_id",whs.Username as "whs_username",whs.LastUpdate as "whs_lastupdate"
                FROM tbl_WarehouseTransaction whs_transaction
                INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
                WHERE whs_transaction.pkid = '.$request->whs_transaction_id.'
                LIMIT 0,1
            ');
            return response()->json(['is_success' => 'true',
                'tblWhsTrasanction' => $tblWhsTrasanction[0],
                'generateControlNumber' => $generateControlNumber
            ]);

        }

    }
    public function getPpdWhsPackagingById(Request $request)
    {
        try {
            //Get Batch Lot Number, Foreign Key , Total Qty
            $generateControlNumber = $this->commonInterface->generateControlNumber(PpdIqcInspection::class,$request->iqc_category_material_id);
            if( isset($request->arr_pkid_received)  ){
                $vwListOfReceived =  VwPpdListOfReceived::select(
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
                $qtyPerLot = $vwListOfReceived->pluck('total_lot_qty')->implode(', ');
                $lotNo = $vwListOfReceived->pluck('lot_no')->implode(', ');
                $whsTransactionId = $vwListOfReceived->pluck('whs_transaction_id')->implode(', ');
                $ppdWhsReceivedPackaging = $vwListOfReceived->map(function($row) use($sumTotalLotQty,$lotNo,$whsTransactionId,$qtyPerLot){
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
                    'ppdWhsReceivedPackaging' => $ppdWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }else{
                $query = $this->resourceInterface->readCustomEloquent( VwPpdListOfReceived::class);
                $ppdWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get([
                    'pkid_received as whs_transaction_id',
                    'invoiceno as invoice_no',
                    'lot_no as lot_no',
                    'partcode as partcode',
                    'partname as partname',
                    'supplier as supplier',
                    'rcvqty as total_lot_qty',
                ]);

                return response()->json(['is_success' => 'true',
                    'ppdWhsReceivedPackaging' => $ppdWhsReceivedPackaging[0],
                    'generateControlNumber' => $generateControlNumber
                ]);
            }
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getPpdIqcInspectionById(Request $request)
    {
        // return 'true';
        try {
            $tbl_whs_trasanction = PpdIqcInspection::with('ppd_iqc_inspections_mods','ppd_iqc_inspections_mods.iqc_dropdown_detail','user_iqc')
            ->where('id',$request->iqc_inspection_id)
            // ->get();
            ->get(['ppd_iqc_inspections.id as iqc_inspection_id','ppd_iqc_inspections.*']);
            return response()->json(['is_success' => 'true','tbl_whs_trasanction'=>$tbl_whs_trasanction]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function savePpdIqcInspection(PpdIqcInspectionRequest $request)
    {
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $iqcInspectionShift = $this->commonInterface->getIqcInspectionShift();
            $mod_lot_no = explode(',',$request->lotNo);
            $mod_defects = explode(',',$request->modeOfDefects);
            $mod_lot_qty = explode(',',$request->lotQty);
            $arr_sum_mod_lot_qty = array_sum($mod_lot_qty);
            $generateControlNumber = $this->commonInterface->generateControlNumber(PpdIqcInspection::class,$request->iqc_category_material_id);
            $appNoExtension = $generateControlNumber['app_no_extension'];

            if(isset($request->iqc_inspection_id)){ //Edit
                PpdIqcInspection::where('id', $request->iqc_inspection_id)->update($request->validated()); //PO and packinglist number

                PpdIqcInspection::where('id', $request->iqc_inspection_id)
                ->update([
                    // 'app_no_extension' => $appNoExtension,
                    'invoice_no' => $request->invoice_no,
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'shift' => $iqcInspectionShift,

                ]);

                $iqc_inspections_id = $request->iqc_inspection_id;
            }else{ //Add
                /* All required fields is the $request validated, check the column is IqcInspectionRequest
                    NOTE: the name of fields must be match in column name
                */
                $create_iqc_inspection_id = PpdIqcInspection::insertGetId($request->validated());
                /*  All not required fields should to be inside the update method below
                    NOTE: the name of fields must be match in column name
                */
                PpdIqcInspection::where('id', $create_iqc_inspection_id)
                ->update([
                    // 'app_no_extension' => $appNoExtension,
                    'invoice_no' => $request->invoice_no,
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'shift' => $iqcInspectionShift,


                ]);

                /* Update rapid/db_pps TblWarehouseTransaction, set inspection_class to 3 */
                if($request->category_material == '48'){
                    if($request->whs_transaction_id != 0){
                        TblWarehouseTransaction::where('pkid', $request->whs_transaction_id)
                        ->update([
                            'inspection_class' => 3,
                        ]);
                    }
                    /* Update status ReceivingDetails into 2*/
                    if($request->receiving_detail_id != 0){
                        ReceivingDetails::where('id', $request->receiving_detail_id)
                        ->update([
                            'rawStatus' => 2,
                        ]);
                    }
                }

                $iqc_inspections_id = $create_iqc_inspection_id;
            }
            /* Uploading of file if checked & iqc_coc_file is exist*/
            if(isset($request->iqc_coc_file) ){
                $original_filename = $request->file('iqc_coc_file')->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = $this->fileInterface->Slug($original_filename, '_', '.');
                Storage::putFileAs('public/ppd_iqc_inspection_coc', $request->iqc_coc_file,  $iqc_inspections_id .'_'. $filtered_filename);


                PpdIqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'iqc_coc_file' => $filtered_filename,
                    'iqc_coc_file_name' => $original_filename
                ]);
            }

            /* Get iqc_inspections_id, delete the previous MOD then  save new MOD*/
            if($request->accepted == 1){
                // return 'true';
                PpdIqcInspectionsMod::where('ppd_iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                PpdIqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'no_of_defects' => 0,
                ]);
            }
            if(isset($request->modeOfDefects)  && $request->accepted == 0){
                PpdIqcInspectionsMod::where('ppd_iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                foreach ( $mod_lot_no as $key => $value) {
                    PpdIqcInspectionsMod::insert([
                        'ppd_iqc_inspection_id'    => $iqc_inspections_id,
                        'lot_no'                => $mod_lot_no[$key],
                        'mode_of_defects'       => $mod_defects[$key],
                        'quantity'              => $mod_lot_qty[$key],
                        'created_at'            => date('Y-m-d H:i:s')
                    ]);
                }
            }else{
                if(PpdIqcInspectionsMod::where('ppd_iqc_inspection_id', $iqc_inspections_id)->exists()){
                    PpdIqcInspectionsMod::where('ppd_iqc_inspection_id', $iqc_inspections_id)->update([
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
