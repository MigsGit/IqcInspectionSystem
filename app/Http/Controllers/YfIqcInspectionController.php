<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\YfIqcInspection;
use Yajra\DataTables\DataTables;

use App\Interfaces\FileInterface;
use App\Models\VwYfListOfReceived;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Models\YfIqcInspectionsMod;
use App\Interfaces\ResourceInterface;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\YfIqcInspectionRequest;

class YfIqcInspectionController extends Controller
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
    public function loadYfWhsPackaging(Request $request)
    {
        try {
            /*
                TODO: Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
                Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            */
            if( isset( $request->lotNum ) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_yf_whs_packaging')
                ->select('SELECT pkid_received as "receiving_detail_id",supplier as "Supplier",partcode as "PartNumber",
                    partname as "MaterialType",date as "Lot_number",invoiceno as "InvoiceNo" FROM  vw_list_of_received
                    WHERE 1=1
                    AND date = "'.$request->lotNum.'"
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_yf_whs_packaging')
                ->select('SELECT pkid_received as "receiving_detail_id",supplier as "Supplier",partcode as "PartNumber",
                        partname as "MaterialType",date as "Lot_number",invoiceno as "InvoiceNo"  FROM  vw_list_of_received
                ');
            }
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
    public function getYfWhsPackagingById(Request $request)
    {
        // return '1';
        try {
            $query = $this->resourceInterface->readCustomEloquent( VwYfListOfReceived::class);
            $yfWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get(
                [
                    'pkid_received as whs_transaction_id',
                    'invoiceno as invoice_no',
                    'date as lot_no',
                    'partcode as partcode',
                    'partname as partname',
                    'supplier as supplier',
                    'rcvqty as total_lot_qty',
                ]
            );
            $generateControlNumber = $this->commonInterface->generateControlNumber(YfIqcInspection::class);

            return response()->json(['is_success' => 'true',
                'yfWhsReceivedPackaging' => $yfWhsReceivedPackaging[0],
                'generateControlNumber' => $generateControlNumber
            ]);
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    
    public function saveYfIqcInspection(YfIqcInspectionRequest $request)
    {
        // return '1';
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $mod_lot_no = explode(',',$request->lotNo);
            $mod_defects = explode(',',$request->modeOfDefects);
            $mod_lot_qty = explode(',',$request->lotQty);
            $arr_sum_mod_lot_qty = array_sum($mod_lot_qty);

            if(isset($request->iqc_inspection_id)){ //Edit
                YfIqcInspection::where('id', $request->iqc_inspection_id)->update($request->validated()); //PO and packinglist number

                YfIqcInspection::where('id', $request->iqc_inspection_id)
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
                $create_iqc_inspection_id = YfIqcInspection::insertGetId($request->validated());
                /*  All not required fields should to be inside the update method below
                    NOTE: the name of fields must be match in column name
                */
                YfIqcInspection::where('id', $create_iqc_inspection_id)
                ->update([
                    'no_of_defects' => $arr_sum_mod_lot_qty,
                    'remarks' => $request->remarks,
                    'inspector' => session('rapidx_user_id'),
                    'created_at' => date('Y-m-d H:i:s')
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
                //         'rawStatus' => 2,
                //     ]);
                // }
                $iqc_inspections_id = $create_iqc_inspection_id;
            }
            /* Uploading of file if checked & iqc_coc_file is exist*/
            if(isset($request->iqc_coc_file) ){
                $original_filename = $request->file('iqc_coc_file')->getClientOriginalName(); //'/etc#hosts/@Álix Ãxel likes - beer?!.pdf';
                $filtered_filename = $this->fileInterface->Slug($original_filename, '_', '.');
                // $filtered_filename = '_'.$this->Slug($original_filename, '_', '.');	 // _etc_hosts_alix_axel_likes_beer.pdf
                Storage::putFileAs('public/yf_iqc_inspection_coc', $request->iqc_coc_file,  $iqc_inspections_id .'_'. $filtered_filename);

                YfIqcInspection::where('id', $iqc_inspections_id)
                ->update([
                    'iqc_coc_file' => $filtered_filename,
                    'iqc_coc_file_name' => $original_filename
                ]);
            }

            /* Get iqc_inspections_id, delete the previous MOD then  save new MOD*/
            if(isset($request->modeOfDefects)){
                YfIqcInspectionsMod::where('yf_iqc_inspection_id', $iqc_inspections_id)->update([
                    'deleted_at' => date('Y-m-d H:i:s')
                ]);
                foreach ( $mod_lot_no as $key => $value) {
                    YfIqcInspectionsMod::insert([
                        'yf_iqc_inspection_id'    => $iqc_inspections_id,
                        'lot_no'                => $mod_lot_no[$key],
                        'mode_of_defects'       => $mod_defects[$key],
                        'quantity'              => $mod_lot_qty[$key],
                        'created_at'            => date('Y-m-d H:i:s')
                    ]);
                }
            }else{
                if(YfIqcInspectionsMod::where('yf_iqc_inspection_id', $iqc_inspections_id)->exists()){
                    YfIqcInspectionsMod::where('yf_iqc_inspection_id', $iqc_inspections_id)->update([
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
