<?php

namespace App\Http\Controllers;

use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\IqcInspectionRequest;

use App\Models\User;
use App\Models\YeuReceive;
use App\Models\IqcInspection;
use App\Models\DropdownIqcAql;
use App\Models\VwListOfReceived;
use App\Models\IqcDropdownDetail;
use App\Models\IqcInspectionsMod;
use App\Models\IqcDropdownCategory;
use App\Models\DropdownIqcModeOfDefect;
use App\Interfaces\FileInterface;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;




class IqcInspectionController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    protected $fileInterface;
    public function __construct(
        ResourceInterface $resourceInterface,
        CommonInterface $commonInterface,
        FileInterface $fileInterface
    ){
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
        $this->fileInterface = $fileInterface;
    }

    //getTsWhsPackagingById
    public function getIqcInspectionByJudgement(Request $request)
    {
        return $iqc_inspection_by = IqcInspection::where('judgement',1)->get();
    }
    public function loadWhsPackaging(Request $request)
    {
        try {
            // Read IqcInspection (Material already Inspected) then do not
            // display it to the ON-GOING status
            $categoryMaterial = $request->categoryMaterial;
            $whereWhsTransactionId =   $this->commonInterface->readIqcInspectionByMaterialCategory(IqcInspection::class,$categoryMaterial);

            if( isset( $request->lotNum ) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ts_whs_packaging')
                ->select('SELECT vw_list_of_received.pkid_received as "receiving_detail_id",vw_list_of_received.supplier as "Supplier",vw_list_of_received.partcode as "PartNumber",
                    vw_list_of_received.partname as "MaterialType",vw_list_of_received.date as "Lot_number",vw_list_of_received.invoiceno as "InvoiceNo"
                    FROM  vw_list_of_received vw_list_of_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.partcode = vw_list_of_received.partcode
                    WHERE 1=1
                    AND tbl_itemList.is_iqc_inspection = 1
                    AND date = "'.$request->lotNum.'"
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ts_whs_packaging')
                ->select('SELECT vw_list_of_received.pkid_received as "receiving_detail_id",vw_list_of_received.supplier as "Supplier",vw_list_of_received.partcode as "PartNumber",
                    vw_list_of_received.partname as "MaterialType",vw_list_of_received.date as "Lot_number",vw_list_of_received.invoiceno as "InvoiceNo"
                    FROM  vw_list_of_received vw_list_of_received
                    LEFT JOIN tbl_itemList tbl_itemList ON tbl_itemList.partcode = vw_list_of_received.partcode
                    WHERE 1=1
                    '.$whereWhsTransactionId.'
                ');
                    // AND tbl_itemList.is_iqc_inspection = 1
            }
            // if( isset( $request->lotNum ) ){
            //     $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            //     ->select('SELECT yeu_receives.*  FROM yeu_receives yeu_receives
            //         RIGHT JOIN item_masters item_masters ON yeu_receives.item_code = item_masters.part_code
            //         WHERE 1=1
            //         AND yeu_receives.lot_no = "'.$request->lotNum.'"
            //         AND item_masters.for_iqc = 1
            //         ORDER BY item_code DESC
            //     ');
            // }else{
            //     $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            //     ->select('SELECT yeu_receives.*,item_masters.part_code  FROM yeu_receives yeu_receives
            //         RIGHT JOIN item_masters item_masters ON yeu_receives.item_code = item_masters.part_code
            //         WHERE 1=1
            //         AND item_masters.for_iqc = 1
            //         ORDER BY item_code DESC
            //     ');
            // }
            return DataTables::of($tbl_whs_trasanction)
            ->addColumn('rawAction', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-info btn-sm mr-1' pkid-received='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
            ->rawColumns(['rawAction','rawStatus'])
            ->make(true);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function loadYeuDetails(Request $request)
    {
        $categoryMaterial = $request->categoryMaterial;
        $whereWhsTransactionId =   $this->commonInterface->readIqcInspectionByMaterialCategory(IqcInspection::class,$categoryMaterial);

        if( isset( $request->lotNum ) ){
            $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            ->select('SELECT yeu_receives.*  FROM yeu_receives yeu_receives
                RIGHT JOIN item_masters item_masters ON yeu_receives.item_code = item_masters.part_code
                WHERE 1=1
                '.$whereWhsTransactionId.'
                AND yeu_receives.lot_no = "'.$request->lotNum.'"
                AND yeu_receives.item_code IS NOT NULL
                AND yeu_receives.item_name IS NOT NULL
                AND item_masters.for_iqc = 1
                ORDER BY item_code DESC
            ');
        }else{
            $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            ->select('SELECT yeu_receives.*,item_masters.part_code  FROM yeu_receives yeu_receives
                RIGHT JOIN item_masters item_masters ON yeu_receives.item_code = item_masters.part_code
                WHERE 1=1
                AND yeu_receives.item_code IS NOT NULL
                AND yeu_receives.item_name IS NOT NULL
                AND item_masters.for_iqc = 1
                ORDER BY item_code DESC
            ');

            // '.$whereWhsTransactionId.'
        }
        // if( isset( $request->lotNum ) ){
        //     $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
        //     ->select('SELECT *  FROM yeu_receives
        //         WHERE 1=1
        //         AND lot_no = "'.$request->lotNum.'"
        //         ORDER BY item_code DESC
        //     ');
        // }else{
        //     $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
        //     ->select('SELECT *  FROM yeu_receives
        //         WHERE 1=1
        //         ORDER BY item_code DESC
        //     ');
        // }

        return DataTables::of($tbl_whs_trasanction)
        ->addColumn('rawAction', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1' yeu-receives-id='".$row->id."' id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
        ->rawColumns(['rawAction','rawStatus'])
        ->make(true);
        /*
            InvoiceNo
            whs_transaction_username,whs_username
            whs_transaction_lastupdate,whs_lastupdate
            whs_transaction_lastupdate,whs_lastupdate
            *Inspection Times*
            *Application Ctrl. No*
            *FY#*
            *WW#*
            *Sub*
            PartNumber
            ProductLine,MaterialType
            Supplier
            Lot_number
        */
    }
    public function loadWhsDetails(Request $request)
    { //PATS PPD WHS Receiving
        if( isset( $request->lotNum ) ){
            $tbl_whs_trasanction = DB::connection('mysql')
            ->select(' SELECT id as "receiving_detail_id",supplier_name as "Supplier",part_code as "PartNumber",
                        mat_name as"MaterialType",supplier_pmi_lot_no as "Lot_number",po_no
                FROM receiving_details
                WHERE 1=1
                AND status = 1 AND supplier_pmi_lot_no = "'.$request->lotNum.'"
                ORDER BY created_at DESC
            ');
        }else{
            $tbl_whs_trasanction = DB::connection('mysql')
            ->select(' SELECT id as "receiving_detail_id",supplier_name as "Supplier",part_code as "PartNumber",
                        mat_name as"MaterialType",supplier_pmi_lot_no as "Lot_number",po_no
                FROM receiving_details
                WHERE 1=1
                AND status = 1
                ORDER BY created_at DESC
            ');
        }
        return DataTables::of($tbl_whs_trasanction)
        ->addColumn('rawAction', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1' style='display: none;' receiving-detail-id='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
        ->rawColumns(['rawAction','rawStatus'])
        ->make(true);
    }
    public function loadIqcInspection(Request $request)
    {
        /*  Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            NOTE: If the data exist to iqc_inspections it means the data is already inspected
        */

        if( isset( $request->lotNum ) ){
            $tbl_iqc_inspected = DB::connection('mysql')
            ->select(' SELECT *
                FROM ts_iqc_inspections
                WHERE 1=1
                AND iqc_category_material_id = "'.$request->category_material.'"
                AND deleted_at IS NULL AND judgement >= 1
                AND lot_no = "'.$request->lotNum.'"
                ORDER BY created_at DESC
            ');
        }else{
            $tbl_iqc_inspected = DB::connection('mysql')
            ->select('SELECT *
                FROM ts_iqc_inspections
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
                $result .= "<button class='btn btn-info btn-sm mr-1' iqc-inspection-id='".$row->id."'id='btnEditIqcInspection' inspector='".$row->inspector."'><i class='fa-solid fa-pen-to-square'></i></button>";
            // }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('rawStatus', function($row){
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

    }
    public function getYeuReceivingById(Request $request)
    {
        try {
            /**
             * Add Conditions as many as you want
             *
             * @param array $conditions
            */
            $conditions = [
                'logdel' => 0,
                'id' => $request->yeuReceivesId,
            ];
            $query = $this->resourceInterface->readAllWithConditions(YeuReceive::class,$conditions);
            $iqcInspection = $query->get();
            $generateControlNumber = $this->commonInterface->generateControlNumber(IqcInspection::class);
            return response()->json(['is_success' => 'true','iqcInspection' => $iqcInspection,'generateControlNumber' => $generateControlNumber]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getIqcInspectionById(Request $request)
    {
        $tbl_whs_trasanction = IqcInspection::with('iqc_inspections_mods','iqc_inspections_mods.iqc_dropdown_detail','user_iqc')
        ->where('id',$request->iqc_inspection_id)
        ->get(['ts_iqc_inspections.id as iqc_inspection_id','ts_iqc_inspections.*']);
        return response()->json(['tbl_whs_trasanction'=>$tbl_whs_trasanction]);
    }
    public function getTsWhsPackagingById(Request $request)
    {
        try {

            $query = $this->resourceInterface->readCustomEloquent( VwListOfReceived::class);
            $tsWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get();
            $generateControlNumber = $this->commonInterface->generateControlNumber(IqcInspection::class);

            return response()->json(['is_success' => 'true',
                'tsWhsReceivedPackaging' => $tsWhsReceivedPackaging[0],
                'generateControlNumber' => $generateControlNumber
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getLotNumberByWhsTransactionId()
    {
        $dropdown_aql =  DropdownIqcAql::get();
        foreach ($dropdown_aql as $key => $value_dropdown_aql) {
            $arr_dropdown_aql_id[] =$value_dropdown_aql['id'];
            $arr_dropdown_aql_value[] =$value_dropdown_aql['aql_percentage'];
        }
        return response()->json([
            'id'    =>  $arr_dropdown_aql_id,
            'value' =>  $arr_dropdown_aql_value
        ]);
    }
    public function saveIqcInspection(IqcInspectionRequest $request)
    // public function saveIqcInspection(Request $request)
    {

        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $iqcInspectionShift = $this->commonInterface->getIqcInspectionShift();
            $mod_lot_no = explode(',',$request->lotNo);
            $mod_defects = explode(',',$request->modeOfDefects);
            $mod_lot_qty = explode(',',$request->lotQty);
            $arr_sum_mod_lot_qty = array_sum($mod_lot_qty);

            if(isset($request->iqc_inspection_id)){ //Edit

                IqcInspection::where('id', $request->iqc_inspection_id)->update($request->validated()); //PO and packinglist number

                IqcInspection::where('id', $request->iqc_inspection_id)
                ->update([
                    'invoice_no' => $request->invoice_no,
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'shift' => $iqcInspectionShift
                ]);

                $iqc_inspections_id = $request->iqc_inspection_id;
            }else{ //Add
                /* All required fields is the $request validated, check the column is IqcInspectionRequest
                    NOTE: the name of fields must be match in column name
                */
                $create_iqc_inspection_id = IqcInspection::insertGetId($request->validated());
                /*  All not required fields should to be inside the update method below
                    NOTE: the name of fields must be match in column name
                */
                IqcInspection::where('id', $create_iqc_inspection_id)
                ->update([
                    'invoice_no' => $request->invoice_no,
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'shift' => $iqcInspectionShift
                ]);

                /* TODO: Update rapid/db_pps TblWarehouseTransaction, set inspection_class to 3 */
                // if($request->whs_transaction_id != 0){
                //     TblWarehouseTransaction::where('pkid', $request->whs_transaction_id)
                //     ->update([
                //         'inspection_class' => 3,
                //     ]);
                // }
                // /* Update status ReceivingDetails into 2*/
                // if($request->receiving_detail_id != 0){
                //     ReceivingDetails::where('id', $request->receiving_detail_id)
                //     ->update([
                //         'rawStatus' => 2,
                //     ]);
                // }
                $iqc_inspections_id = $create_iqc_inspection_id;
            }
            /* Uploading of file if checked & iqc_coc_file is exist*/
            if(isset($request->iqc_coc_file) ){
                $original_filename = $request->file('iqc_coc_file')->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = '_'.$this->fileInterface->Slug($original_filename, '_', '.');
                Storage::putFileAs('public/ts_iqc_inspection_coc', $request->iqc_coc_file,  $iqc_inspections_id .'_'. $filtered_filename);


                IqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'iqc_coc_file' => $filtered_filename,
                    'iqc_coc_file_name' => $original_filename
                ]);
            }

            /* Get iqc_inspections_id, delete the previous MOD then  save new MOD*/
            if($request->accepted == 1){
                // return 'true';
                IqcInspectionsMod::where('iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                IqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'no_of_defects' => 0,
                ]);
            }
            if(isset($request->modeOfDefects)  && $request->accepted == 0){
                IqcInspectionsMod::where('iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                foreach ( $mod_lot_no as $key => $value) {
                    IqcInspectionsMod::insert([
                        'iqc_inspection_id'    => $iqc_inspections_id,
                        'lot_no'                => $mod_lot_no[$key],
                        'mode_of_defects'       => $mod_defects[$key],
                        'quantity'              => $mod_lot_qty[$key],
                        'created_at'            => date('Y-m-d H:i:s')
                    ]);
                }
            }else{
                if(IqcInspectionsMod::where('iqc_inspection_id', $iqc_inspections_id)->exists()){
                    IqcInspectionsMod::where('iqc_inspection_id', $iqc_inspections_id)->update([
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
    public function getModeOfDefect(){
        // return 'true';
        $dropdown_iqc_mode_of_defect = DropdownIqcModeOfDefect::get();
        foreach ($dropdown_iqc_mode_of_defect as $key => $value_dropdown_iqc_mode_of_defect) {
            $arr_dropdown_iqc_mode_of_defect_id[] = $value_dropdown_iqc_mode_of_defect['id'];
            $arr_dropdown_iqc_mode_of_defect_value[] = $value_dropdown_iqc_mode_of_defect['mode_of_defects'];
        }
        return response()->json([
            'id'    =>  $arr_dropdown_iqc_mode_of_defect_id,
            'value' =>  $arr_dropdown_iqc_mode_of_defect_value
        ]);
    }

    public function getDropdownDetailsByOptValue(Request $request)
    {
        try {
            /**
             * Add Relations as many as you want
             *
             * @param array $relations
            */
            $relations = [
                'iqc_dropdown_details',
            ];

            /**
             * Add Conditions as many as you want
             *
             * @param array $conditions
            */
            $conditions = [
                'iqc_inspection_column_ref' => $request->iqc_inspection_column_ref,
                'section' => $request->section,
                'status' => 1,
            ];

            // Ascending order of specific value (like "N/A") appears first
            $iqcDropdownDetail = $this->resourceInterface->readCustomEloquent(IqcDropdownCategory::class)
            ->with('iqc_dropdown_details')
            ->where('iqc_inspection_column_ref' , $request->iqc_inspection_column_ref)
            ->where('section' , $request->section)
            ->where('status', 1)
            ->whereHas('iqc_dropdown_details',
               function($query) use ($request){
                    $query->orderByRaw("CASE WHEN  dropdown_details = 'N/A' THEN 0 ELSE 1 END, dropdown_details ASC");
                },
            )
            ->get();
            // $results = Model::orderByRaw("CASE WHEN name = 'N/A' THEN 0 ELSE 1 END, name ASC")->get();
            $iqcDropdownDetail = $iqcDropdownDetail[0]->iqc_dropdown_details;

            foreach ($iqcDropdownDetail as $key => $valueIqcDropdownDetail) {
                $arrIqcDropdownDetailId[] =$valueIqcDropdownDetail['id'];
                $arrIqcDropdownDetailValue[] =$valueIqcDropdownDetail['dropdown_details'];
            }
            return response()->json([
                'id'    =>  $arrIqcDropdownDetailId,
                'value' =>  $arrIqcDropdownDetailValue
            ]);
            // return response()->json(['is_success' => 'true','iqcDropdownDetail'=>$iqcDropdownDetail]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function getModeOfDefectsById(Request $request){
        try {
            $get_mode_of_defects_by_id = $this->resourceInterface->readById(IqcDropdownDetail::class,$request->selectedMod);
            return response()->json(['is_success' => 'true','get_mode_of_defects_by_id'=> $get_mode_of_defects_by_id[0]->dropdown_details]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }


}
