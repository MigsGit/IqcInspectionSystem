<?php

namespace App\Jobs;

use App\Models\IqcInspection;
use Illuminate\Support\Facades\DB;
use App\Interfaces\CommonInterface;
use App\Interfaces\ResourceInterface;


class CommonJob implements CommonInterface
{

    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface){
        $this->resourceInterface = $resourceInterface;
    }
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

        if(date_format($iqc_inspection[0]->created_at,'Y-m-d') != date('Y-m-d')){
            return [
                'app_no' => $division."-".date('y').date('m').'-',
                'app_no_extension'=>"001"
            ];
        }
        return [
            'app_no' => $division."-".date('y').date('m').'-',
            'app_no_extension'=> sprintf("%03d", $iqc_inspection[0]->app_no_extension + 1)
        ];
    }
}
