<?php

namespace App\Http\Controllers;

use Helpers;
use DataTables;
use App\Models\User;
use App\Models\TblWarehouse;
use Illuminate\Http\Request;
use App\Models\IqcInspection;
use App\Models\DropdownIqcAql;
use App\Models\ReceivingDetails;
use App\Models\DropdownIqcFamily;
use App\Models\IqcDropdownDetail;
use App\Models\IqcInspectionsMod;
use Illuminate\Support\Facades\DB;
use App\Models\IqcDropdownCategory;
use App\Models\DropdownIqcTargetLar;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\ResourceInterface;
use App\Models\DropdownIqcTargetDppm;
use App\Models\DropdownIqcModeOfDefect;
use App\Models\TblWarehouseTransaction;
use Illuminate\Support\Facades\Storage;
use App\Models\DropdownIqcInspectionLevel;
use App\Http\Requests\IqcInspectionRequest;

class IqcInspectionController extends Controller
{
    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface) {
        $this->resourceInterface = $resourceInterface;
    }
    public function getIqcInspectionByJudgement(Request $request)
    {
        return $iqc_inspection_by = IqcInspection::where('judgement',1)->get();
    }

    public function loadWhsTransaction(Request $request)
    { //RAPID WHS Whs Transaction
        /*  Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
            Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
        */
        // return 'true';
        if( isset( $request->lotNum ) ){
            $tbl_whs_trasanction = DB::connection('mysql_rapid_pps')
            ->select(' SELECT  whs.*,whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.inspection_class
                FROM tbl_WarehouseTransaction whs_transaction
                INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
                WHERE 1=1
                AND whs.Factory = 3
                AND  whs_transaction.inspection_class = 1 AND whs_transaction.Lot_number = "'.$request->lotNum.'"
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
                ORDER BY whs.PartNumber DESC
            ');
        }

        return DataTables::of($tbl_whs_trasanction)
        ->addColumn('action', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1 d-none' whs-trasaction-id='".$row->whs_transaction_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            return $result;
        })
        ->addColumn('status', function($row){
            $result = '';
            $result .= '<center>';
            $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
            $result .= '</center>';
            return $result;
        })
        ->rawColumns(['action','status'])
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
    public function loadYeuDetails(Request $request)
    {   //RAPID WHS Whs Transaction
        /*  Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
            Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
        */

        if( isset( $request->lotNum ) ){
            $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            ->select('SELECT *  FROM yeu_receives
                WHERE 1=1
                ORDER BY item_code DESC
            ');
            // ->select(' SELECT  whs.*,whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.inspection_class
            //     FROM tbl_WarehouseTransaction whs_transaction
            //     INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
            //     WHERE 1=1
            //     AND whs.Factory = 3
            //     AND  whs_transaction.inspection_class = 1 AND whs_transaction.Lot_number = "'.$request->lotNum.'"
            //     ORDER BY whs.PartNumber DESC
            // ');
        }else{
            $tbl_whs_trasanction = DB::connection('mysql_rapidx_yeu')
            ->select('SELECT *  FROM yeu_receives
                WHERE 1=1
                ORDER BY item_code DESC

            ');
            // ->select('SELECT  whs.*,whs_transaction.*,whs_transaction.pkid as "whs_transaction_id",whs_transaction.inspection_class
            //     FROM tbl_WarehouseTransaction whs_transaction
            //     INNER JOIN tbl_Warehouse whs on whs.id = whs_transaction.fkid
            //     WHERE 1=1
            //     AND whs.Factory = 3
            //     AND whs_transaction.inspection_class = 1
            //     ORDER BY whs.PartNumber DESC
            // ');
        }

        return DataTables::of($tbl_whs_trasanction)
        ->addColumn('action', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1 d-none' whs-trasaction-id='".$row->id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            return $result;
        })
        ->addColumn('status', function($row){
            $result = '';
            $result .= '<center>';
            $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
            $result .= '</center>';
            return $result;
        })
        ->rawColumns(['action','status'])
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
        ->addColumn('action', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1' style='display: none;' receiving-detail-id='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            return $result;
        })
        ->addColumn('status', function($row){
            $result = '';
            $result .= '<center>';
            $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
            $result .= '</center>';
            return $result;
        })
        ->rawColumns(['action','status'])
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
        ->addColumn('action', function($row){
            $result = '';
            $result .= '<center>';
            // if($row->inspector == Auth::user()->id || Auth::user()->username =='mclegaspi'){ //nmodify
                $result .= "<button class='btn btn-info btn-sm mr-1' iqc-inspection-id='".$row->id."'id='btnEditIqcInspection' inspector='".$row->inspector."'><i class='fa-solid fa-pen-to-square'></i></button>";
            // }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('status', function($row){
            $iqc_inspection_by_whs_trasaction_id = IqcInspection::where('whs_transaction_id',$row->whs_transaction_id)->get();
            $result = '';
            $backgound = '';
            $judgement = '';
            $result .= '<center>';

            if( count($iqc_inspection_by_whs_trasaction_id) != 0 ){
                foreach ($iqc_inspection_by_whs_trasaction_id as $key => $value){
                    switch ($value['judgement']) {
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
                }
                $result .= '<span class="badge rounded-pill '.$backgound.' ">'.$judgement.'</span>';
            }else{
                $result .= '<span class="badge rounded-pill bg-primary"> On-going </span>';
            }
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
        ->rawColumns(['action','status','app_ctrl_no','qc_inspector','time_inspected',])
        ->make(true);

    }

    public function getIqcInspectionById(Request $request)
    {
        $tbl_whs_trasanction = IqcInspection::with('IqcInspectionsMods','user_iqc')
        ->where('id',$request->iqc_inspection_id)
        ->get(['ts_iqc_inspections.id as iqc_inspection_id','ts_iqc_inspections.*']);
        return response()->json(['tbl_whs_trasanction'=>$tbl_whs_trasanction]);
    }

    public function getWhsReceivingById(Request $request)
    {
        if($request->whs_transaction_id != 0){
            return $tbl_whs_trasanction = DB::connection('mysql_rapid_pps')
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
        }
        if($request->receiving_detail_id != 0){
            return $tbl_whs_trasanction = ReceivingDetails::where('id',$request->receiving_detail_id)->get([
                'id as receiving_detail_id','supplier_pmi_lot_no as lot_no','supplier_quantity as total_lot_qty','part_code as partcode',
                'mat_name as partname','supplier_name as supplier',
            ]);
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
    {
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $mod_lot_no = explode(',',$request->lotNo);
            $mod_defects = explode(',',$request->modeOfDefects);
            $mod_lot_qty = explode(',',$request->lotQty);
            $arr_sum_mod_lot_qty = array_sum($mod_lot_qty);

            if(isset($request->iqc_inspection_id)){ //Edit

                $update_iqc_inspection = IqcInspection::where('id', $request->iqc_inspection_id)->update($request->validated()); //PO and packinglist number

                IqcInspection::where('id', $request->iqc_inspection_id)
                ->update([
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
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
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => 1,
                    // 'inspector' => Auth::user()->id,
                ]);

                /* Update rapid/db_pps TblWarehouseTransaction, set inspection_class to 3 */
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
                //         'status' => 2,
                //     ]);
                // }
                $iqc_inspections_id = $create_iqc_inspection_id;
            }
            /* Uploading of file if checked & iqc_coc_file is exist*/
            if(isset($request->iqc_coc_file) ){
                $original_filename = $request->file('iqc_coc_file')->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = '_'.$this->Slug($original_filename, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf
                Storage::putFileAs('public/iqc_inspection_coc', $request->iqc_coc_file,  $iqc_inspections_id . $filtered_filename);

                IqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'iqc_coc_file' => $filtered_filename,
                    'iqc_coc_file_name' => $original_filename
                ]);
            }

            /* Get iqc_inspections_id, delete the previous MOD then  save new MOD*/
            if(isset($request->modeOfDefects)){
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

    public function viewCocFileAttachment(Request $request,$iqc_inspection_id)
    {
        $iqc_coc_file_name = IqcInspection::where('id',$iqc_inspection_id)->get('iqc_coc_file');
        return Storage::response( 'public/iqc_inspection_coc/' . $iqc_inspection_id . $iqc_coc_file_name[0][ 'iqc_coc_file' ] );
    }

    public function getDropdownDetailsByOptValue(Request $request){
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
                'status' => 1,
            ];

            $iqcDropdownDetail = $this->resourceInterface->readAllRelationsAndConditions(IqcDropdownCategory::class,$relations,$conditions);
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
    //categoryMaterial

    public function Slug($string, $slug = '-', $extra = null)
	{
		return strtolower(trim(preg_replace('~[^0-9a-z' . preg_quote($extra, '~') . ']+~i', $slug, $this->Unaccent($string)), $slug));
	}

	public function Unaccent($string) // normalizes (romanization) accented chars
	{
		if (strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false)
		{
			$string = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string), ENT_QUOTES, 'UTF-8');
		}
		return $string;
	}

}
