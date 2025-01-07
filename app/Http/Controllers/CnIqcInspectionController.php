<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class CnIqcInspectionController extends Controller
{
    //TODO: Lagyan ng dept ang getDropdown
    //FORMAT OF DEVELOPMENT
    //FILES NEED FOR ME
    public function loadCnWhsPackaging(Request $request){
        try {
            /*
                TODO: Get the data only with whs_transaction.inspection_class = 1 - For Inspection, while
                Transfer the data with whs_transaction.inspection_class = 3 to Inspected Tab
            */
            if( isset( $request->lotNum ) ){
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_whs_packaging')
                ->select('SELECT pkid_received as "receiving_detail_id",supplier as "Supplier",partcode as "PartNumber",
                    partname as "MaterialType",date as "Lot_number",invoiceno as "InvoiceNo" FROM  vw_list_of_received
                    WHERE 1=1
                    AND date = "'.$request->lotNum.'"
                ');
            }else{
                $tbl_whs_trasanction = DB::connection('mysql_rapid_cn_whs_packaging')
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

    public function CnIqcInspectionController(Request $request){
        return 'true' ;
        try {
            return response()->json(['is_success' => 'true']);
        } catch (Exception $e) {
            return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
}
