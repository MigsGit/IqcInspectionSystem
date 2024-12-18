<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class PpdIqcInspectionController extends Controller
{
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
}
