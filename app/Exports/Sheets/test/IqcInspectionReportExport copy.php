<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\IqcInspection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Exports\Sheets\IqcInspectionRawSheet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\IqcInspectionByDateMaterialGroupBySheet;



class IqcInspectionReportExport implements WithMultipleSheets
// FromCollection,
{
    // use Exportable;
    protected $from_date;
    protected $to_date;
    protected $material_category;
    protected $arr_merge_group;
    public function __construct($from_date, $to_date, $material_category, $arr_merge_group)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->material_category = $material_category;
        $this->arr_merge_group = $arr_merge_group;
    }

    public function sheets(): array{
        $sheets = [];
        $sheets[] = new IqcInspectionRawSheet($this->from_date, $this->to_date, $this->material_category, $this->arr_merge_group);
        $sheets[] = new IqcInspectionByDateMaterialGroupBySheet($this->from_date, $this->to_date, $this->material_category, $this->arr_merge_group);
        return $sheets;
    }

    /**
        * Collection of IqcInspection Data By Material Category and Date
        * Query to validate data group by supplier and specific date

        // public function collection()
        // {
        //     //Select columns from the array and With Relationship needed to include the inspector foreign key
        //     return $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        //     select($this->arr_merge_group)
        //     ->addSelect([
        //         // DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
        //         DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
        //         DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
        //         DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
        //         DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
        //         DB::raw("date_inspected"),
        //         DB::raw("iqc_category_material_id"),
        //     ])
        //     ->where("iqc_category_material_id", "=", "$this->material_category")
        //     ->whereBetween('date_inspected', ["$this->from_date", "$this->to_date"])
        //     ->groupBy('supplier')
        //     ->get();

        //     // ->whereBetween('date_inspected', [
        //     //     Carbon::parse($this->from_date)->startOfMonth(),
        //     //     Carbon::parse($this->to_date)->endOfMonth()
        //     // ])
        //     // ->groupBy([
        //     //     DB::raw("WEEK(date_inspected)"),
        //     // ])
        // }
    */
    //Query to validate data group by supplier and specific date
    public function collection()
    {

        // Get the start and end of the month
        $startOfMonth = Carbon::parse($this->from_date)->startOfMonth();
        $endOfMonth = Carbon::parse($this->to_date)->endOfMonth();

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
        $iqcInspectionCollection = collect($weekRanges)->map(function ($week) {
            return IqcInspection::
            select($this->arr_merge_group)
            ->addSelect(
                DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
            )
            ->where("iqc_category_material_id", "=", "$this->material_category")
            ->whereBetween('date_inspected', [$week['start'], $week['end']])
            // ->groupBy('supplier')
            ->groupBy($this->arr_merge_group)
            ->get();
        })->filter(); // Remove empty records
        // return $iqcInspectionCollection =  collect($iqcInspectionCollection)->map(function ($value) {
        //     return $value;
        // });
        return $iqcInspectionCollection =  collect($iqcInspectionCollection)->map(function ($row)  {
            return [
                // 'work_range' => $row->pluck('week_range')->unique()->toArray(), //display one array only
                'week2' => $row ?? [], // Fetch all objects for week2,
            ] ;

        });
        return $groupedData = collect($weekRanges)->map(function ($week) use ($iqcInspectionCollection) {
            return $iqcInspectionCollection->filter(function ($row) use ($week) {
                return $week;
                // return Carbon::parse($row->date_inspected)->between($week['start'], $week['end']);
            });
            // ->map(function ($row) use ($week) {
            //     return [
            //         'supplier' => $row->supplier ?? '',
            //         'lot_inspected' => 1, // Adjust based on logic
            //         'accepted' => $row->accepted_count ?? 0,
            //         'week_range' => Carbon::parse($week['start'])->format('M j') . " - " . Carbon::parse($week['end'])->format('j'),
            //         'accepted_count' => $row->accepted_count ?? 0,
            //         'rejected_count' => $row->rejected_count ?? 0,
            //         'sampling_size_sum' => $row->sampling_size_sum ?? 0,
            //         'no_of_defects_sum' => $row->no_of_defects_sum ?? 0,
            //     ];
            // });
        })->flatten(1);

        $iqcInspectionFlatten = $iqcInspectionCollection->flatten();
        return new Collection([
            'work_range' => $iqcInspectionFlatten->pluck('week_range')->unique()->toArray(), //display one array only
            'week2' => $iqcInspectionCollection[2] ?? [], // Fetch all objects for week2,
            // 'week1_accepted_count' => $iqcInspectionCollection[2][0]->accepted_count,
            // 'week1_rejected_count' => $iqcInspectionCollection[2][0]->rejected_count,
            // 'week1_sampling_size_sum' => $iqcInspectionCollection[2][0]->sampling_size_sum,
            // 'week1_no_of_defects_sum' => $iqcInspectionCollection[2][0]->no_of_defects_sum,
            // 'week2' => $iqcInspectionCollection[1],
            // 'week3' => $iqcInspectionCollection[2],
            // 'week4' => $iqcInspectionCollection[3],
            // 'week5' => $iqcInspectionCollection[4],
        ]);
    }
}
