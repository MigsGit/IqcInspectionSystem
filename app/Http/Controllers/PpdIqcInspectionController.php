<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\IqcInspection;

use App\Models\PpdIqcInspection;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Models\VwPpdListOfReceived;
use App\Interfaces\ResourceInterface;

class PpdIqcInspectionController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
    }

    public function loadPpdIqcInspection(Request $request){
        // return 'true' ;
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
                    $result .= "<button class='btn btn-info btn-sm mr-1' iqc-inspection-id='".$row->id."'id='btnEditIqcInspection' inspector='".$row->inspector."'><i class='fa-solid fa-pen-to-square'></i></button>";
                // }
                $result .= '</center>';
                return $result;
            })

            ->addColumn('rawStatus', function($row){
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
        ->addColumn('rawAction', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1 d-none' whs-trasaction-id='".$row->pkid."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
    public function loadPpdWhsPackaging(Request $request){
        try {
            /*
                TODO: Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
                Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            */
            if( isset( $request->lotNum ) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ppd_whs_packaging')
                ->select('SELECT pkid_received as "receiving_detail_id",supplier as "Supplier",partcode as "PartNumber",
                    partname as "MaterialType",date as "Lot_number",invoiceno as "InvoiceNo" FROM  vw_list_of_received
                    WHERE 1=1
                    AND date = "'.$request->lotNum.'"
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_ppd_whs_packaging')
                ->select('SELECT pkid_received as "receiving_detail_id",supplier as "Supplier",partcode as "PartNumber",
                        partname as "MaterialType",date as "Lot_number",invoiceno as "InvoiceNo"  FROM  vw_list_of_received
                ');
            }
            return DataTables::of($tbl_whs_trasanction)
            ->addColumn('rawAction', function($row){
                $result = '';
                $result .= '<center>';
                $result .= "<button class='btn btn-info btn-sm mr-1 d-none' pkid-received='".$row->receiving_detail_id."'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'></i></button>";
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
    public function getWhsReceivingById(Request $request)
    {
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
        $generateControlNumber = $this->commonInterface->generateControlNumber(IqcInspection::class);
        return response()->json(['is_success' => 'true',
        'tblWhsTrasanction' => $tblWhsTrasanction[0],
        'generateControlNumber' => $generateControlNumber
    ]);
    }
    public function getPpdWhsPackagingById(Request $request){
        try {

            $query = $this->resourceInterface->readCustomEloquent( VwPpdListOfReceived::class);
            $ppdWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get([
                'pkid_received as whs_transaction_id',
                'invoiceno as invoice_no',
                'date as lot_no',
                'partcode as partcode',
                'partname as partname',
                'supplier as supplier',
            ]);
            $generateControlNumber = $this->commonInterface->generateControlNumber(IqcInspection::class);

            return response()->json(['is_success' => 'true',
                'ppdWhsReceivedPackaging' => $ppdWhsReceivedPackaging[0],
                'generateControlNumber' => $generateControlNumber
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    //get_ppd_iqc_inspection_by_id
    public function getPpdIqcInspectionById(Request $request){
        // return 'true';
        try {
            $tbl_whs_trasanction = PpdIqcInspection::with('ppd_iqc_inspections_mods','user_iqc')
            ->where('id',$request->iqc_inspection_id)
            // ->get();
            ->get(['ppd_iqc_inspections.id as iqc_inspection_id','ppd_iqc_inspections.*']);
            return response()->json(['is_success' => 'true','tbl_whs_trasanction'=>$tbl_whs_trasanction]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }


}
