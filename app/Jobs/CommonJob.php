<?php

namespace App\Jobs;

use Carbon\Carbon;
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
        //Rapidx User
        //TODO: I cannot access the systemone/db_subcon, so I came up with rapidx user DB
        $rapidx_user = DB::connection('mysql_rapidx')
        ->select(" SELECT department_group
            FROM departments
            WHERE department_id = '".session('rapidx_department_id')."'
        ");

        $division = ($rapidx_user[0]->department_group == "PPS" || $rapidx_user[0]->department_group == "PPD") ? "PPD" :  $rapidx_user[0]->department_group;
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

    public function iqcInspectionByDateMaterialGroupBySupplierChart(
        $model,
        $from_date,
        $to_date,
        $material_category
    )
    {
        // Get the start and end of the month
        $startOfMonth = Carbon::parse($from_date)->startOfMonth();
        $endOfMonth = Carbon::parse($to_date)->endOfMonth();

        // Determine the first Thursday of the month
        $firstThursday = $startOfMonth->copy()->next(Carbon::THURSDAY);

        $weekRanges = [];
        $startDate = $startOfMonth;

        // Generate week ranges ending on Thursday
        while ($startDate <= $endOfMonth) {
            // If first week, set end date to first Thursday
            $endDate = ($startDate->equalTo($firstThursday))
                ? $firstThursday
                : $startDate->copy()->next(Carbon::THURSDAY);

            // Ensure end date does not exceed end of month
            if ($endDate > $endOfMonth) {
                $endDate = $endOfMonth;
            }

            // Store week range
            $weekRanges[] = [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ];

            // Move to next week's start date
            $startDate = $endDate->copy()->addDay();
        }
        $iqcInspectionSupplier = $model::select('supplier')
        ->where("iqc_category_material_id", "=", "$material_category")
        ->whereBetween('date_inspected', [$startOfMonth, $endOfMonth])
        ->groupBy('supplier')
        ->get();

        // $targetLarDppm = ;

        // Fetch inspection data per week
        $iqcInspectionCollection = collect($weekRanges)->map(function ($week)use($material_category,$model) {
            return $iqcInspectionPerSupplierCollection = $model::select(['supplier'])
                ->addSelect(
                    DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                    // DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                    // DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                    // DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                    // DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                    // DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
                    DB::raw("ROUND( COUNT( CASE WHEN judgement = 1 THEN 1 END ) / ( SUM(lot_inspected) ) * 100,2) as 'actual_lar' "), //lot accepted / lot inspected * 100 - ROUND OF 2
                    DB::raw("ROUND( SUM(no_of_defects)  / SUM(sampling_size) * 1000000,0) as 'actual_dppm' "), //ng qty / sampling_size * 1000000 - ROUND OF 0
                    // DB::raw("(SUM(lot_inspected)) / (SUM(lot_inspected) - COUNT(CASE WHEN judgement = 2 THEN 1 END)) as 'actual_lar' "),
                )
                ->where("iqc_category_material_id", "=", "$material_category")
                ->whereBetween('date_inspected', [$week['start'], $week['end']])
                // ->groupBy('supplier')
                ->groupBy('supplier')
                ->get();
        })
        ->flatten(1) //Flata as 1 array
        ->groupBy('supplier') //Array group by specific object
        ->toArray();

        $totalIqcInspectionByDateMaterialGroupBySupplier = $this->totalIqcInspectionByDateMaterialGroupBySupplier($model,$from_date,
        $to_date,
        $material_category);

        return [
            'iqcInspectionCollection' => $iqcInspectionCollection,
            'iqcInspectionSupplier' => $iqcInspectionSupplier,
            'totalIqcInspectionByDateMaterialGroupBySupplier' => $totalIqcInspectionByDateMaterialGroupBySupplier
        ];
        // return response()->json([
        //     'iqcInspectionCollection' => $iqcInspectionCollection,
        //     'iqcInspectionSupplier' => $iqcInspectionSupplier,
        //     'totalIqcInspectionByDateMaterialGroupBySupplier' => $totalIqcInspectionByDateMaterialGroupBySupplier
        // ]);
    }
    public function totalIqcInspectionByDateMaterialGroupBySupplier(
        $model,
        $from_date,
        $to_date,
        $material_category
    ){
        // Get the start and end of the month
        // $startOfMonth = Carbon::parse($from_date);
        // $endOfMonth = Carbon::parse($to_date);

        $startOfDate = Carbon::parse($from_date);
        $endOfDate = Carbon::parse($to_date);
        return $model::select('supplier')
        ->addSelect(
            DB::raw("'".Carbon::parse($startOfDate)->format('M j')." - ".Carbon::parse($endOfDate)->format('j')."' as week_range"), // Display week range
            DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
            DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
            DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
            DB::raw("SUM(lot_inspected) as lot_inspected_sum"),
            DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
            DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
            DB::raw("ROUND( COUNT( CASE WHEN judgement = 1 THEN 1 END ) / ( SUM(lot_inspected) ) * 100,2) as 'actual_lar' "), //lot accepted / lot inspected * 100 - ROUND OF 2
            DB::raw("ROUND( SUM(no_of_defects)  / SUM(sampling_size) * 1000000,0) as 'actual_dppm' "), //ng qty / sampling_size * 1000000 - ROUND OF 0
        )
        ->where("iqc_category_material_id", "=", "$material_category")
        ->whereBetween('date_inspected', [$startOfDate, $endOfDate])
        ->groupBy('supplier')
        ->get()->filter();
    }
    public function iqcInspectionRawSheet(
        $model,
        $from_date,
        $to_date,
        $material_category
    ){
        // return $model;
        /*
            dropdown_details

        */
        return $model::with([
            'user_iqc',
            'iqc_dropdown_detail_family',
            // 'iqc_dropdown_detail_type_of_inspection',
            'iqc_dropdown_detail_severity_of_inspection',
            'iqc_dropdown_detail_inspection_lvl',
            'iqc_dropdown_detail_aql',
            'vw_list_of_received'
        ])->where("iqc_category_material_id", "=", $material_category)
        ->whereBetween('date_inspected', [$from_date, $to_date]);
    }
    public function iqcInspectionByDateMaterialGroupBySheet(
        $model,
        $from_date,
        $to_date,
        $material_category,
        $arr_merge_group
    ){

        // Get the start and end of the month
        $startOfMonth = Carbon::parse($from_date)->startOfMonth();
        $endOfMonth = Carbon::parse($to_date)->endOfMonth();

        // Determine the first Thursday of the month
        $firstThursday = $startOfMonth->copy()->next(Carbon::THURSDAY);

        $weekRanges = [];
        $startDate = $startOfMonth;

        // Generate week ranges ending on Thursday
        while ($startDate <= $endOfMonth) {
            // If first week, set end date to first Thursday
            $endDate = ($startDate->equalTo($firstThursday))
                ? $firstThursday
                : $startDate->copy()->next(Carbon::THURSDAY);

            // Ensure end date does not exceed end of month
            if ($endDate > $endOfMonth) {
                $endDate = $endOfMonth;
            }

            // Store week range
            $weekRanges[] = [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d')
            ];

            // Move to next week's start date
            $startDate = $endDate->copy()->addDay();
        }

        // Fetch inspection data per week
        return $iqcInspectionCollection = collect($weekRanges)->map(function ($week)use($material_category,$arr_merge_group,$model) {
            return $model::select('supplier')
            ->addSelect(
                DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                DB::raw("SUM(lot_inspected) as 'lot_inspected_sum'"),
                DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
                DB::raw("ROUND( COUNT( CASE WHEN judgement = 1 THEN 1 END ) / ( SUM(lot_inspected) ) * 100,2) as 'actual_lar' "), //lot accepted / lot inspected * 100 - ROUND OF 2
                DB::raw("ROUND( SUM(no_of_defects)  / SUM(sampling_size) * 1000000,0) as 'actual_dppm' "), //ng qty / sampling_size * 1000000 - ROUND OF 0
            )
            ->where("iqc_category_material_id", "=", "$material_category")
            ->whereBetween('date_inspected', [$week['start'], $week['end']])
            ->groupBy('supplier')
            ->get();
        })->filter(); // Remove empty records


        $mapping = [];
        $startRow = 7; // Start inserting data from row 7
        foreach ([0,1,2,3,4] as $weekIndex) {
            if (!isset($iqcInspectionCollection[$weekIndex])) {
                continue; // Skip if no data
            }

            foreach ($iqcInspectionCollection[$weekIndex] as $index => $data) {
                $row = $startRow + $index; // Adjust row dynamically
                if ($weekIndex == 0 ) {
                    $mapping["A{$row}"] = $data->supplier;
                    $mapping["D{$row}"] = $data->week_range;
                }
                elseif ($weekIndex == 1) {
                    $mapping["E{$row}"] = $data->supplier;
                    $mapping["F{$row}"] = $data->week_range;
                }
                elseif ($weekIndex == 2) {
                    $mapping["K{$row}"] = $data->supplier;
                    $mapping["L{$row}"] = $data->week_range;
                } elseif ($weekIndex == 3) {
                    $mapping["O{$row}"] = $data->supplier;
                    $mapping["P{$row}"] = $data->week_range;
                }
                 elseif ($weekIndex == 4) {
                    $mapping["S{$row}"] = $data->supplier;
                    $mapping["T{$row}"] = $data->week_range;
                }
            }
            $startRow = 7;
        }
        return $mapping;
    }
}
