<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

use Auth;
use DataTables;

use App\Models\User;
use App\Models\FirstMolding;
use App\Models\FirstMoldingDevice;
use App\Models\MoldingIpqcInspection;

class MoldingIpqcInspectionController extends Controller
{
    public function viewFirstMoldingIpqcInspection(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $first_molding_ipqc_details = FirstMolding::
            with(['firstMoldingDevice','molding_ipqc_inspection_info'])
            ->where('pmi_po_no', $request->getPmiPo)
            ->where('status', $request->selectCategory)
            ->get();
        return DataTables::of($first_molding_ipqc_details)

        ->addColumn('action', function($first_molding_ipqc_detail){
            $get_ipqc_id_per_row = MoldingIpqcInspection::where('fk_molding_id', $first_molding_ipqc_detail->id)
                ->where('logdel', 0)
                ->orderBy('id', 'DESC')
                ->get();
            
            $result = '<center>';
            if(count($get_ipqc_id_per_row) > 0){
                $result .= '
                    <button class="btn btn-dark btn-sm text-center 
                        actionFirstMoldingIpqcInspection" 
                        first_molding-id="' . $first_molding_ipqc_detail->id . '" 
                        first_molding_ipqc-id="' . $get_ipqc_id_per_row[0]->id . '" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalMoldingIpqcInspection" 
                        data-bs-keyboard="false" 
                        title="History">
                        <i class="fa-solid fa fa-edit"></i>
                    </button>&nbsp;';
            }else{
                $result .= '
                    <button class="btn btn-dark btn-sm text-center 
                    actionFirstMoldingIpqcInspection" 
                        first_molding-id="' . $first_molding_ipqc_detail->id . '" 
                        first_molding_ipqc-id="0" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalMoldingIpqcInspection" 
                        data-bs-keyboard="false" 
                        title="View">
                        <i class="nav-icon fa fa-edit"></i>
                    </button>&nbsp;';
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('device_name', function($first_molding_ipqc_detail){
            $result = '<center>';
            $result .= $first_molding_ipqc_detail->firstMoldingDevice->device_name;
            $result .= '</center>';
            return $result;
        })
        ->addColumn('judgement', function($first_molding_ipqc_detail){
            $result = '<center>';
            $get_ipqc_id_per_row = MoldingIpqcInspection::where('fk_molding_id', $first_molding_ipqc_detail->id)
                ->where('logdel', 0)
                ->orderBy('id', 'DESC')
                ->get();
    
            if(count($get_ipqc_id_per_row) > 0){
                $result .= $first_molding_ipqc_detail[0]->judgement;
            }
            $result .= '</center>';
            return $result;
        })
        ->addColumn('inspected_at', function($first_molding_ipqc_detail){
            $result = '<center>';
            $get_ipqc_id_per_row = MoldingIpqcInspection::where('fk_molding_id', $first_molding_ipqc_detail->id)
                ->where('logdel', 0)
                ->orderBy('id', 'DESC')
                ->get();
            
            if(count($get_ipqc_id_per_row) > 0){
                $result .= $first_molding_ipqc_detail[0]->created_at;
            }

            $result .= '</center>';
            return $result;
        })

        ->rawColumns([
            'action',
            'device_name',
            'judgement',
            'inspected_at'
        ])
        ->make(true);
    }
    
    public function getMoldingPmiPo(Request $request){
        $collect_pmi_po = FirstMolding::select('pmi_po_no')->orderBy('pmi_po_no')->distinct()->get();
        $get_data_from_pmi_po = FirstMolding::with(['firstMoldingDevice'])->where('pmi_po_no', $request->getPmiPo)->first();
        return response()->json(['collectPmiPo' => $collect_pmi_po, 'getDataFromPmiPo' => $get_data_from_pmi_po]);
    }

    public function getMoldingIpqcInspectionById(Request $request){
        date_default_timezone_set('Asia/Manila');
        
        $get_molding_ipqc_inspection_by_id = FirstMolding::with(['firstMoldingDevice', 'molding_ipqc_inspection_info'])->where('id', $request->firstMoldingId)->get();
        return response()->json([
            'getMoldingIpqcInspectionByid'  => $get_molding_ipqc_inspection_by_id 
        ]);
    }

    public function scanUserId(Request $request){
        date_default_timezone_set('Asia/Manila');

        $user_details = User::where('employee_id', $request->user_id)->first();
        return response()->json(['userDetails' => $user_details]);
    }

    public function updateMoldingIpqcInspection(Request $request){
        date_default_timezone_set('Asia/Manila');
        $data = $request->all();
        $validator = Validator::make($data, [
        
        ]);

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        } else {
            DB::beginTransaction();
            try {
                $add_update_molding_ipqc_inspection = [
                    'fk_molding_id'         => $request->molding_id,
                    'molding_status'        => 1,
                    'pmi_po_no'             => $request->molding_ipqc_inspection_po_number,
                    'judgement'             => $request->molding_ipqc_inspection_judgement,
                    'output'                => $request->molding_ipqc_inspection_output,
                    'ipqc_inspector_name'   => $request->employee_no,
                    'created_at'            => date('Y-m-d H:i:s'),
                ];    

                MoldingIpqcInspection::insert(
                    $add_update_molding_ipqc_inspection
                );

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            }
        }
    }

}
