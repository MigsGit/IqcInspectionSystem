<?php

namespace App\Jobs;

use App\Models\IqcInspection;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;


class CommonJob implements CommonInterface
{

    protected $resourceInterface;
    /**
     * __construct
     * @param \App\Interfaces\ResourceInterface $resourceInterface
     */
    public function __construct(ResourceInterface $resourceInterface){
        $this->resourceInterface = $resourceInterface;
    }
    /**
     * generateControlNumber
     * @param mixed $model
     * @return array
     */
    public function generateControlNumber($model){
        date_default_timezone_set('Asia/Manila');
        $query = $this->resourceInterface->readCustomEloquent($model);
        $iqc_inspection = $query->orderBy('created_at','desc')->whereNull('deleted_at')->limit(1)->get(['app_no_extension','created_at']);

        $rapidx_employee_number = session('rapidx_employee_number');

        $hris_data = DB::connection('mysql_systemone_hris')
        ->select(" SELECT division.Division
            FROM tbl_EmployeeInfo employee_info
            LEFT JOIN tbl_Division division on division.pkid = employee_info.fkDivision
            WHERE EmpNo = '$rapidx_employee_number'
        ");


        if(count($hris_data) > 0){
            $division = ($hris_data[0]->Division == "PPS" ||  $hris_data[0]->Division == "PPD") ? "PPD" :  $hris_data[0]->Division;
        }else{
            $subcon_data = DB::connection('mysql_systemone_subcon')
            ->select("SELECT division.Division
                FROM tbl_EmployeeInfo employee_info
                LEFT JOIN tbl_Division division on division.pkid = employee_info.fkDivision
                WHERE EmpNo = '$rapidx_employee_number'
             ");
            $division = ($subcon_data[0]->Division == "PPS" || $subcon_data[0]->Division == "PPD") ? "PPD" :  $hris_data[0]->Division;
        }
        if(count( $iqc_inspection ) == 0 ||  $iqc_inspection[0]->created_at == null ){
            return [
                'app_no' => $division."-".date('y').date('m').'-',
                'app_no_extension'=> "001",
            ];
        }
        if(date_format($iqc_inspection[0]->created_at,'Y-m-d') != date('Y-m-d')){
            return [
                'app_no' => $division."-".date('y').date('m').'-',
                'app_no_extension'=>"001",
                'id'=>$iqc_inspection[0]->created_at,
                'created_at'=> $iqc_inspection[0]->created_at,
                'today' =>  date('Y-m-d')

            ];
        }
        return [
            'app_no' => $division."-".date('y').date('m').'-',
            'app_no_extension'=> sprintf("%03d", $iqc_inspection[0]->app_no_extension + 1),
            // 'created_at'=> $iqc_inspection[0]->created_at,
            // 'today' =>  date('Y-m-d')
        ];
    }
    /**
     * readIqcInspectionByMaterialCategory
     * Get the array where condition of IqcInspection
     * Resulting to load only on going inpection
     * Remove materials that is already inspected
     * @param mixed $model
     * @param mixed $categoryMaterial
     * @return string
     */
    public function readIqcInspectionByMaterialCategory($model,$categoryMaterial){
        // return 'true' ;
        $iqcInspection = $this->resourceInterface->readCustomEloquent($model)
        ->where('iqc_category_material_id',$categoryMaterial)
        ->whereNull('deleted_at')
        ->get();
        $whereWhsTransactionId = "";
        if($categoryMaterial == "37" || $categoryMaterial == "46"
            || $categoryMaterial == "123" || $categoryMaterial == "47"
            || $categoryMaterial == "49" ) //Packaging Material Category
        {
            if(count ($iqcInspection) > 0){
                foreach ($iqcInspection as $key => $valIqcInspection) {
                    $arrWhsTransactionId[] = "AND tbl_received.pkid_received != '".$valIqcInspection->whs_transaction_id."' ";
                }
                $whereWhsTransactionId = implode(' ',$arrWhsTransactionId);
            }
        }else{ //YEU Material Category
            if(count ($iqcInspection) > 0){
                foreach ($iqcInspection as $key => $valIqcInspection) {
                    $arrWhsTransactionId[] = "AND yeu_receives.id != '".$valIqcInspection->whs_transaction_id."' ";
                }
                $whereWhsTransactionId = implode(' ',$arrWhsTransactionId);
            }
        }
        return $whereWhsTransactionId;
    }

    public function getIqcInspectionShift() {
        date_default_timezone_set('Asia/Manila');
        $time_now = date('H:i:s');
        // Check if the current time is within the first shift range
        if ($time_now >= '07:30:00' && $time_now <= '19:29:00') {
            // Set the shift to 1
            $shift = '1';
        } else {
            // Set the shift to 2
            $shift = '2';
        }
        return $shift;
    }

}
