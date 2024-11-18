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

use App\Models\MoldingAssyIpqcInspection;
use App\Models\FirstMolding;
use App\Models\FirstMoldingDetail;
use App\Models\FirstMoldingDetailMod;

class IpqcFirstMoldingController extends Controller
{   
    //================================= GET FIRST MOLDINNG DEVICE FOR FILTERING =========================
    public function get_device_from_first_molding(Request $request){
        $first_molding_devices = FirstMolding::select('first_molding_device_id')->with('firstMoldingDevice')
                                        ->whereNull('deleted_at')
                                        ->distinct()
                                        ->get();

        return response()->json(['first_molding_devices' => $first_molding_devices]);
    }

    //================================= GET IPQC BY MATERIAL NAME/ID DATA ==================================
    public function get_ipqc_data(Request $request){
        $ipqc_data = MoldingAssyIpqcInspection::with('ipqc_insp_name')
                                            ->when($request->material_name, function ($query) use ($request){
                                                return $query ->where('material_name', $request->material_name);
                                            })
                                            ->when($request->ipqc_id, function ($query) use ($request){
                                                return $query ->where('id', $request->ipqc_id);
                                            })
                                            ->where('logdel', 0)
                                            ->get();

        return response()->json(['ipqc_data' => $ipqc_data]);
    }
    
    //================================== VIEW IPQC DATA IN DATATABLES =====================================
    public function view_ipqc_fmold_data(Request $request){
            $view_ipqc_data = MoldingAssyIpqcInspection::with('ipqc_insp_name')
                                    ->where('material_name', $request->material_name)
                                    ->whereIn('status', $request->ipqc_status)
                                    ->where('process_category', $request->process_category)
                                    ->where('logdel', 0)
                                    ->get();

            return DataTables::of($view_ipqc_data)
            ->addColumn('action', function($view_ipqc_data){
                $result = "";
                $result .= "<center>";
                $result .= "<button class='btn btn-info btn-sm btnViewIPQCData' ipqc_data-id='$view_ipqc_data->id'>
                            <i class='fa-solid fa-eye' data-bs-html='true' title='View IPQC Inspection'></i></button>";

                if($view_ipqc_data->status < 3){ //Not Exsisting IPQC ID or Status less than 3(0 - Pending, 1,2 - Updated): Enabled Updating
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-primary btn-sm btnUpdateIPQCData' ipqc_data-status='$view_ipqc_data->status' ipqc_data-id='$view_ipqc_data->id'>
                                <i class='fa-solid fa-microscope' data-bs-html='true' title='Proceed to IPQC Inspection'></i></button>";
                }else if($view_ipqc_data->status == 5){ //Exsisting IPQC ID & Status 5(For Resetup): Enabled Updating
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-primary btn-sm btnUpdateIPQCData' ipqc_data-status='$view_ipqc_data->status' ipqc_data-id='$view_ipqc_data->id'>
                                <i class='fa-solid fa-microscope' data-bs-html='true' title='Proceed to Re-Inspection'></i></button>";
                }

                if($view_ipqc_data->status == 1 ){ //Exsisting IPQC ID & Status 1(Accepted): Ready to Submit
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-success btn-sm btnSubmitIPQCData' ipqc_data-status='$view_ipqc_data->status' ipqc_data-id='$view_ipqc_data->id'>
                                <i class='fa-solid fa-circle-check' data-bs-html='true' title='Proceed to Mass Production'></i></button>";
                }
                else if($view_ipqc_data->status == 2){ //Exsisting IPQC ID & Status 2(Rejected): Ready to Submit
                    $result .= "&nbsp";
                    $result .= "<button class='btn btn-warning btn-sm btnSubmitIPQCData' ipqc_data-status='$view_ipqc_data->status' ipqc_data-id='$view_ipqc_data->id' ipqc_data-prod_lot='$view_ipqc_data->production_lot'>
                                <i class='fa-solid fa-triangle-exclamation' data-bs-html='true' title='Save Rejected QC Sample'></i></button>";
                }
                $result .= "</center>";
                return $result;
            })
            ->addColumn('ipqc_status', function ($view_ipqc_data) {
                $result = "";
                
                switch($view_ipqc_data->status){
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
            ->addColumn('request_created_at', function ($view_ipqc_data) {
                $result = "";
                $result = Carbon::parse($view_ipqc_data->created_at);
                return $result;
            })
            ->addColumn('ipqc_judgement', function ($view_ipqc_data) {
                $result = "";
                    if($view_ipqc_data->judgement == 'Accepted'){
                        $result .= "<center><span class='badge badge-pill badge-success'>$view_ipqc_data->judgement</span></center>";
                    }else if($view_ipqc_data->judgement == 'Rejected'){
                        $result .= "<center><span class='badge badge-pill badge-warning'>$view_ipqc_data->judgement</span></center>";
                    }
                return $result;
            })
            ->addColumn('ipqc_inspector_name', function ($view_ipqc_data) {
                $result = "";
                    $result = $view_ipqc_data->ipqc_insp_name->firstname.' '.$view_ipqc_data->ipqc_insp_name->lastname;
                return $result;
            })
            ->addColumn('ipqc_document_no', function ($view_ipqc_data) {
                $result = "";
                    $result = $view_ipqc_data->document_no;
                return $result;
            })
            ->addColumn('ipqc_measdata_attachment', function ($view_ipqc_data) {
                $result = "";
                    $result = $view_ipqc_data->measdata_attachment;
                return $result;
            })
            ->addColumn('ipqc_inspected_date', function ($view_ipqc_data) {
                $result = "";
                    $result = $view_ipqc_data->updated_at;
                return $result;
            })
            ->rawColumns(['action','ipqc_status','request_created_at','ipqc_judgement','ipqc_inspector_name','ipqc_document_no','ipqc_measdata_attachment','ipqc_inspected_date'])
            ->make(true);
    }

    //====================================== ADD/UPDATE IPQC DATA FOR FIRST MOLDING =========================
    public function add_first_molding_ipqc_inspection(Request $request){
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
                                                'production_lot'          => $request->production_lot,
                                                'judgement'               => $request->judgement,
                                                'qc_samples'              => $request->qc_samples,
                                                'ok_samples'              => $request->ok_samples,
                                                'ipqc_inspector_name'     => $request->inspector_id,
                                                'keep_sample'             => $request->keep_sample,
                                                'doc_no_b_drawing'        => $request->doc_no_b_drawing,
                                                'doc_no_insp_standard'    => $request->doc_no_inspection_standard,
                                                'doc_no_urgent_direction' => $request->doc_no_ud,
                                                'measdata_attachment'     => $original_filename,
                                                'status'                  => $status,
                                                'remarks'                 => $request->remarks,
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
                        'po_number'               => $request->po_number,
                        'part_code'               => $request->part_code,
                        'material_name'           => $request->material_name,
                        'production_lot'          => $request->production_lot,
                        'judgement'               => $request->judgement,
                        'qc_samples'              => $request->qc_samples,
                        'ok_samples'              => $request->ok_samples,
                        'ipqc_inspector_name'     => $request->inspector_id,
                        'keep_sample'             => $request->keep_sample,
                        'doc_no_b_drawing'        => $request->doc_no_b_drawing,
                        'doc_no_insp_standard'    => $request->doc_no_inspection_standard,
                        'doc_no_urgent_direction' => $request->doc_no_ud,
                        'measdata_attachment'     => $original_filename,
                        'status'                  => $status,
                        'remarks'                 => $request->remarks,
                        'last_updated_by'         => Auth::user()->id,
                        'updated_at'              => date('Y-m-d H:i:s'),
                    ]);

            DB::commit();
            return response()->json(['result' => 'Update Successful']);
        }
    }

    //====================================== UPDATE IPQC STATUS FOR FIRST MOLDING =========================
    public function update_first_molding_ipqc_inspection_status(Request $request){
        date_default_timezone_set('Asia/Manila');
            if($request->cnfrm_ipqc_status == 1){
                //For Mass Production
                // $first_molding_status = 1;
                $ipqc_status = 3;

            }else if($request->cnfrm_ipqc_status == 2){
                //For Re-Setup
                $first_molding_status = 2;
                $ipqc_status = 4;
            }

            MoldingAssyIpqcInspection::where('id', $request->cnfrm_ipqc_id)
                    ->update([
                        'status'              => $ipqc_status,
                        'last_updated_by'     => Auth::user()->id,
                        'updated_at'          => date('Y-m-d H:i:s'),
                    ]);
        
            if($request->cnfrm_ipqc_status == 2){
                FirstMolding::where('production_lot', $request->cnfrm_ipqc_production_lot)
                ->update(['status' => $first_molding_status]);
            }

            DB::commit();
        return response()->json(['result' => 'Successful']);
    }

    //====================================== DOWNLOAD FILE ======================================
    public function first_molding_download_file(Request $request, $id){
        $ipqc_data_for_download = MoldingAssyIpqcInspection::where('id', $id)->first();
        $file =  storage_path() . "/app/public/molding_assy_ipqc_insp_files/" . $ipqc_data_for_download->measdata_attachment;
        return Response::download($file, $ipqc_data_for_download->measdata_attachment);
    }
}
