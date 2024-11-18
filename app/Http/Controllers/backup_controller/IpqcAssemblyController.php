<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DataTables;
use Carbon\Carbon;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\MoldingAssyIpqcInspection;
use App\Models\AssemblyRuncard;
use App\Models\AssemblyRuncardStation;
use App\Models\AssemblyRuncardStationsMods;

class IpqcAssemblyController extends Controller
{
    // NEW CODE CLARK 02042024
    public function get_devices_from_assembly(Request $request){
        $assembly_devices = AssemblyRuncard::select('device_name')->with('device_details')
                                        ->whereNull('deleted_at')
                                        ->distinct()
                                        ->get();

        return response()->json(['assembly_devices' => $assembly_devices]);
    }

    public function get_first_molding_data(Request $request){
        $first_molding_data = AssemblyRuncard::with('firstMoldingDevice')->whereNull('deleted_at')
                                        ->when($request->device_id, function ($query) use ($request){
                                            return $query ->where('first_molding_device_id', $request->device_id);
                                        })
                                        ->when($request->first_molding_id, function ($query) use ($request){
                                                return $query ->where('id', $request->first_molding_id);
                                        })
                                        ->when($request->ipqc_id, function ($query) use ($request){
                                            return $query ->with(['first_molding_ipqc.ipqc_insp_name' => function($query) { $query->select('id', 'firstname', 'lastname', 'username'); }]);
                                        })
                                        ->get();

        // mapping
        if($request->ipqc_id == 0){
            $first_molding_data_mapped = $first_molding_data->map(function ($item){
                $item->ipqc_data = 0;
                $item->ipqc_inspector_id = Auth::user()->id;
                $item->ipqc_inspector_name = Auth::user()->firstname.' '.Auth::user()->lastname;
                return $item;
            });
        }else{
            $first_molding_data_mapped = $first_molding_data;
        }

        return response()->json(['first_molding_data' => $first_molding_data_mapped]);
    }
    
    public function view_first_molding_ipqc_data(Request $request){

        // if(!isset($request->device_id)){
        //     return [];
        // }else{
            $first_molding_data = AssemblyRuncard::whereNull('deleted_at')
                                    ->where('first_molding_device_id', $request->device_id)
                                    ->whereIn('status', $request->first_molding_status)
                                    ->with(['first_molding_ipqc.ipqc_insp_name' => function($query) { $query->select('id', 'firstname', 'lastname', 'username'); },
                                            'first_molding_ipqc' => function($query) use ($request) { $query->whereIn('status', $request->ipqc_status); }])
                                    ->get();

            return DataTables::of($first_molding_data)
            ->addColumn('action', function($first_molding_data){
                if(!isset($first_molding_data->first_molding_ipqc)){
                    $ipqc_id = 0;
                    $ipqc_status = 0;
                }else{
                    $ipqc_id = $first_molding_data->first_molding_ipqc->id;
                    $ipqc_status = $first_molding_data->first_molding_ipqc->status;
                }
                $result = "";
                $result .= "<center>";
                if($ipqc_id != 0){ //Exsisting IPQC ID: Ready to view
                    $result .= "<button class='btn btn-info btn-sm btnViewIPQCData' first_molding_data-id='$first_molding_data->id' ipqc_data-id='$ipqc_id'>
                                <i class='fa-solid fa-eye' data-bs-html='true' title='View IPQC Inspection'></i></button>";
                }

                if($ipqc_id == 0 || $ipqc_status < 3){ //Not Exsisting IPQC ID or Status less than 3(0 - Pending, 1,2 - Updated): Enabled Updating
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-primary btn-sm btnUpdateIPQCData' ipqc_data-status='$ipqc_status' first_molding_data-id='$first_molding_data->id' ipqc_data-id='$ipqc_id'>
                                <i class='fa-solid fa-microscope' data-bs-html='true' title='Proceed to IPQC Inspection'></i></button>";
                }else if($ipqc_id != 0 && $ipqc_status == 5){ //Exsisting IPQC ID & Status 5(For Resetup): Enabled Updating
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-primary btn-sm btnUpdateIPQCData' ipqc_data-status='$ipqc_status' first_molding_data-id='$first_molding_data->id' ipqc_data-id='$ipqc_id'>
                                <i class='fa-solid fa-microscope' data-bs-html='true' title='Proceed to Re-Inspection'></i></button>";
                }

                if($ipqc_id != 0 && $ipqc_status == 1 ){ //Exsisting IPQC ID & Status 1(Accepted): Ready to Submit
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-success btn-sm btnSubmitIPQCData' ipqc_data-status='$ipqc_status' first_molding_data-id='$first_molding_data->id' ipqc_data-id='$ipqc_id'>
                                <i class='fa-solid fa-circle-check' data-bs-html='true' title='Proceed to Mass Production'></i></button>";
                }
                else if($ipqc_id != 0 && $ipqc_status == 2){ //Exsisting IPQC ID & Status 2(Rejected): Ready to Submit
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-warning btn-sm btnSubmitIPQCData' ipqc_data-status='$ipqc_status' first_molding_data-id='$first_molding_data->id' ipqc_data-id='$ipqc_id'>
                                <i class='fa-solid fa-triangle-exclamation' data-bs-html='true' title='Save Rejected QC Sample'></i></button>";
                }
                $result .= "</center>";
                return $result;
            })
            ->addColumn('ipqc_status', function ($first_molding_data) {
                $result = "";
                if(!isset($first_molding_data->first_molding_ipqc->status)){
                    $status = 0;
                }else{
                    $status = $first_molding_data->first_molding_ipqc->status;
                }
                switch($status){
                    case 0: //Default Value: Not Yet Inpected or Inserted Data But Not Updated = Not Ready
                        $result .= '<center><span class="badge badge-pill badge-info">For IPQC Inspection</span></center>';
                        break;
                    case 1: //Updated:(J)Accepted
                        $result .= '<center><span class="badge badge-pill badge-primary">Accepted QC Sample</span></center>';
                        break;
                    case 2: //Updated:(J)Rejected
                        $result .= '<center><span class="badge badge-pill badge-warning">Rejected QC Sample</span></center>';
                        break;
                    case 3: //Completed IPQC Inspection
                        $result .= '<center><span class="badge badge-pill badge-success">Done IPQC Inspection</span></center>';
                        break;
                    case 4: //Completed IPQC Inspection
                        $result .= '<center><span class="badge badge-pill badge-warning">For Re-Setup</span></center>';
                        break;
                    case 5: //Completed IPQC Inspection
                        $result .= '<center><span class="badge badge-pill badge-info">For Re-Inspection</span></center>';
                        break;
                }
                return $result;
            })
            ->addColumn('first_molding_created_at', function ($first_molding_data) {
                $result = "";
                $result = Carbon::parse($first_molding_data->created_at);
                return $result;
            })
            ->addColumn('ipqc_judgement', function ($first_molding_data) {
                $result = "";
                if(isset($first_molding_data->first_molding_ipqc->judgement)){
                    $first_molding_ipqc = $first_molding_data->first_molding_ipqc;
                    if($first_molding_ipqc->judgement == 'Accepted'){
                        $result .= "<center><span class='badge badge-pill badge-success'>$first_molding_ipqc->judgement</span></center>";
                    }else if($first_molding_ipqc->judgement == 'Rejected'){
                        $result .= "<center><span class='badge badge-pill badge-warning'>$first_molding_ipqc->judgement</span></center>";
                    }
                }else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Not Yet Inspected</span></center>';
                }
                return $result;
            })
            ->addColumn('ipqc_inspector_name', function ($first_molding_data) {
                $result = "";
                if(isset($first_molding_data->first_molding_ipqc->ipqc_insp_name)){
                    $result = $first_molding_data->first_molding_ipqc->ipqc_insp_name->firstname.' '.$first_molding_data->first_molding_ipqc->ipqc_insp_name->lastname;
                }else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Not Yet Inspected</span></center>';
                }
                return $result;
            })
            ->addColumn('ipqc_document_no', function ($first_molding_data) {
                $result = "";
                if(isset($first_molding_data->first_molding_ipqc->ipqc_insp_name)){
                    $result = $first_molding_data->first_molding_ipqc->document_no;
                }else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Not Yet Inspected</span></center>';
                }
                return $result;
            })
            ->addColumn('ipqc_measdata_attachment', function ($first_molding_data) {
                $result = "";
                if(isset($first_molding_data->first_molding_ipqc->ipqc_insp_name)){
                    $result = $first_molding_data->first_molding_ipqc->measdata_attachment;
                }else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Not Yet Inspected</span></center>';
                }
                return $result;
            })
            ->addColumn('ipqc_inspected_date', function ($first_molding_data) {
                $result = "";
                if(isset($first_molding_data->first_molding_ipqc->updated_at)){
                    $result = $first_molding_data->first_molding_ipqc->updated_at;
                }else{
                    $result .= '<center><span class="badge badge-pill badge-secondary">Not Yet Inspected</span></center>';
                }
                return $result;
            })
            ->rawColumns(['action','ipqc_status','first_molding_created_at','ipqc_judgement','ipqc_inspector_name','ipqc_document_no','ipqc_measdata_attachment','ipqc_inspected_date'])
            ->make(true);
        // }
    }

    // NEW CODE CLARK 02042024 END

    public function add_assembly_ipqc_inspection(Request $request){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $data = $request->all();
        if($request->ipqc_id == 0){
            $validator = Validator::make($data, [
                'doc_no_b_drawing' => 'required',
                'doc_no_inspection_standard' => 'required',
                'doc_no_ud' => 'required',
                'uploaded_file' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['validation' => 'hasError', 'error' => $validator->messages()]);
            }else {
                $original_filename = $request->file('uploaded_file')->getClientOriginalName();
                    if($request->judgement == "Accepted"){
                        $status = 1;
                    }else if($request->judgement == "Rejected"){
                        $status = 2;
                    }
                    Storage::putFileAs('public/molding_assy_ipqc_insp_files', $request->uploaded_file,  $original_filename);
                    MoldingAssyIpqcInspection::insert(['fk_molding_assy_id'     => $request->first_molding_id,
                                                    'process_category'        => $request->process_category,
                                                    'po_number'               => $request->po_number,
                                                    'part_code'               => $request->part_code,
                                                    'material_name'           => $request->material_name,
                                                    'prod_lot_no'             => $request->production_lot,
                                                    'judgement'               => $request->judgement,
                                                    'input'                   => $request->input,
                                                    'output'                  => $request->output,
                                                    'ipqc_inspector_name'     => $request->inspector_id,
                                                    'keep_sample'             => $request->keep_sample,
                                                    'doc_no_b_drawing'        => $request->doc_no_b_drawing,
                                                    'doc_no_insp_standard'    => $request->doc_no_inspection_standard,
                                                    'doc_no_urgent_direction' => $request->doc_no_ud,
                                                    'measdata_attachment'     => $original_filename,
                                                    'remarks'                 => $request->remarks,
                                                    'status'                  => $status,
                                                    'created_by'              => Auth::user()->id,
                                                    'last_updated_by'         => Auth::user()->id,
                                                    'created_at'              => date('Y-m-d H:i:s'),
                                                    'updated_at'              => date('Y-m-d H:i:s'),
                    ]);

                    DB::commit();
                    return response()->json(['result' => 'Insert Successful']);
            }
        }else{
            if(isset($request->uploaded_file)){
                $original_filename = $request->file('uploaded_file')->getClientOriginalName();
                    Storage::putFileAs('public/molding_assy_ipqc_insp_files', $request->uploaded_file,  $original_filename);
            }else{
                $original_filename = $request->re_uploaded_file;
            }

            if($request->judgement == "Accepted"){
                $status = 1;
            }else if($request->judgement == "Rejected"){
                $status = 2;
            }

            MoldingAssyIpqcInspection::where('id', $request->ipqc_id)
                    ->update([
                        'judgement'               => $request->judgement,
                        'input'                   => $request->input,
                        'output'                  => $request->output,
                        'ipqc_inspector_name'     => $request->inspector_id,
                        'keep_sample'             => $request->keep_sample,
                        'doc_no_b_drawing'        => $request->doc_no_b_drawing,
                        'doc_no_insp_standard'    => $request->doc_no_inspection_standard,
                        'doc_no_urgent_direction' => $request->doc_no_ud,
                        'measdata_attachment'     => $original_filename,
                        'remarks'                 => $request->remarks,
                        'status'                  => $status,
                        'last_updated_by'         => Auth::user()->id,
                        'updated_at'              => date('Y-m-d H:i:s'),
                    ]);

                DB::commit();
                return response()->json(['result' => 'Update Successful']);
                // }
        }
    }

    public function update_assembly_ipqc_inspection_status(Request $request){
        date_default_timezone_set('Asia/Manila');
            if($request->cnfrm_ipqc_status == 1){
                //For Mass Production
                $first_molding_status = 1;
                $ipqc_status = 3;

            }else if($request->cnfrm_ipqc_status == 2){
                //For Re-Setup
                $first_molding_status = 3;
                $ipqc_status = 4;
            }

            MoldingAssyIpqcInspection::where('id', $request->cnfrm_ipqc_id)
                    ->update([
                        'status'              => $ipqc_status,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);

            AssemblyRuncard::where('id', $request->cnfrm_first_molding_id)
            ->update(['status' => $first_molding_status]);
        
            DB::commit();
        return response()->json(['result' => 'Successful']);
    }

    //====================================== DOWNLOAD FILE ======================================
    public function assembly_download_file(Request $request, $id){
        $ipqc_data_for_download = MoldingAssyIpqcInspection::where('id', $id)->first();
        $file =  storage_path() . "/app/public/molding_assy_ipqc_insp_files/" . $ipqc_data_for_download->measdata_attachment;
        return Response::download($file, $ipqc_data_for_download->measdata_attachment);
    }

    public function excel(Request $month)
    {
      return Excel::download(new Export(

          ),
          'Packing List.xlsx');
    }

}
