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
    public function generateControlNumber($model,$categoryMaterial){
        date_default_timezone_set('Asia/Manila');
        $query = $this->resourceInterface->readCustomEloquent($model);

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
        // Check if the Created At & App No / Division / Material Category is exisiting
        // Example:TS-F1-250211-
        $current_app_no = $division."-".date('y').date('m').date('d').'-';
        $iqc_inspection = $query->orderBy('created_at','desc')->where('app_no',$current_app_no)
            ->where('iqc_category_material_id',$categoryMaterial)
            ->whereNull('deleted_at')
            ->whereNotNull('created_at')
            ->limit(1)->get(['app_no_extension','created_at']);

        //If not exist reset the app_no_extension to 1
        if(count( $iqc_inspection ) == 0){
            return [
                'app_no' => $current_app_no,
                'app_no_extension'=> "001",
            ];
        }
        //If last data created by month not equal to current month reset the app_no_extension to 1
        if(date_format($iqc_inspection[0]->created_at,'m') != date('m')){
            return [
                'app_no' => $current_app_no,
                'app_no_extension'=>"001",
                'month_created_at'=> date_format($iqc_inspection[0]->created_at,'m'),
                'current_month' =>  date('m')
            ];
        }
        //Return increment app_no_extension
        return [
            'app_no' => $current_app_no,
            'app_no_extension'=> sprintf("%03d", $iqc_inspection[0]->app_no_extension + 1),
            'month_created_at'=> date_format($iqc_inspection[0]->created_at,'m'),
            'current_month' =>  date('m')
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
