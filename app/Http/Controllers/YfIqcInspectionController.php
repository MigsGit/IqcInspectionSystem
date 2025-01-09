<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\YfIqcInspection;
use Yajra\DataTables\DataTables;

use App\Interfaces\FileInterface;
use App\Models\VwYfListOfReceived;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;

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
}
