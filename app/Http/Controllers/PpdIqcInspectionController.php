<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\VwPpdListOfReceived;
use App\Interfaces\ResourceInterface;

class PpdIqcInspectionController extends Controller
{
    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface) {
        $this->resourceInterface = $resourceInterface;
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
    public function getPpdWhsPackagingById(Request $request){
        try {

            $query = $this->resourceInterface->readCustomEloquent( VwPpdListOfReceived::class);
            $ppdWhsReceivedPackaging = $query->where('pkid_received',$request->pkid_received)->get();
            $generateControlNumber = $this->generateControlNumber();

            return response()->json(['is_success' => 'true',
                'ppdWhsReceivedPackaging' => $ppdWhsReceivedPackaging[0],
                'generateControlNumber' => $generateControlNumber
            ]);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }


}
