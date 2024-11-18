<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use App\Exports\Sheets\export_sg;

class Export implements WithMultipleSheets
{
    public function __construct(

        // $Month,
        // $Energy_PastFyActual_Average,
        // $Energy_Target_Average,
        // $Energy_Actual_Average,
        // $Energy_Target_Months,
        // $Energy_Actual_Months,
        // $Water_PastFyActual_Average,
        // $Water_Target_Average,
        // $Water_Target_Sum,
        // $Water_Actual_mp_ave, //6.1
        // $Water_Actual_consumption_ave, //6.2
        // $Water_Actual_Average, //6.3
        // $Water_Target_Months,
        // $Water_Actual_Months,
        // $Water_MPCount_Months,
        // $Water_Actualpermp_Months,
        // $Paper_PastFyActual_Prod_Ream,
        // $Paper_PastActual_SG_Ream,
        // $TotalPaper_Past_FY_Actual,
        // $Paper_Target_Prod_Ream,
        // $Paper_Target_SG_Ream,
        // $TotalPaper_Current_FY_Target,
        // $Paper_Target_Months_Prod_Ream,
        // $Paper_Target_Months_SG_Ream,
        // $Paper_Actual_Months_Prod_Ream,
        // $Paper_Actual_Months_SG_Ream,
        // $Ink_Past_Actual,
        // $Ink_Current_Target,
        // $Ink_Target_Months,
        // $Ink_Actual_Months,
        // $CurrentFY_year,
        // $pastFy_year,
        // $Paper_PastFyTarget_Prod_Ream,
        // $Paper_PastFyTarget_SG_Ream,
        // $Energy_PastFyTarget_Average,
        // $Water_PastFyTarget_Average,
        // $ActionPlan_array
    )
    {
    //    $this->selected_month = $Month;
    //    $this->energy_pastfyactual_ave = $Energy_PastFyActual_Average;
    //    $this->energy_target_ave = $Energy_Target_Average;
    //    $this->energy_actual_ave = $Energy_Actual_Average;
    //    $this->energy_target_months = $Energy_Target_Months;
    //    $this->energy_actual_months = $Energy_Actual_Months;
    //    $this->water_pasftyactual_ave = $Water_PastFyActual_Average;
    //    $this->water_target_ave = $Water_Target_Average;
    //    $this->water_target_sum = $Water_Target_Sum;
    //    $this->water_actualmp_ave = $Water_Actual_mp_ave; //6.1
    //    $this->water_actual_consumption_ave = $Water_Actual_consumption_ave; //6.2
    //    $this->water_actual_ave = $Water_Actual_Average; //6.3
    //    $this->water_target_months = $Water_Target_Months;
    //    $this->water_actual_months = $Water_Actual_Months;
    //    $this->water_mpcount_months = $Water_MPCount_Months;
    //    $this->water_actualpermp_months = $Water_Actualpermp_Months;
    //    $this->paper_pastfyactual_prod = $Paper_PastFyActual_Prod_Ream;
    //    $this->paper_pastfyactual_sg = $Paper_PastActual_SG_Ream;
    //    $this->total_paper_pastfyactual = $TotalPaper_Past_FY_Actual;
    //    $this->paper_target_prod = $Paper_Target_Prod_Ream;
    //    $this->paper_target_sg = $Paper_Target_SG_Ream;
    //    $this->total_paper_currentfytarget = $TotalPaper_Current_FY_Target;
    //    $this->paper_target_months_prod = $Paper_Target_Months_Prod_Ream;
    //    $this->paper_target_months_sg = $Paper_Target_Months_SG_Ream;
    //    $this->paper_actual_months_prod = $Paper_Actual_Months_Prod_Ream;
    //    $this->paper_actual_months_sg = $Paper_Actual_Months_SG_Ream;
    //    $this->ink_past_actual = $Ink_Past_Actual;
    //    $this->ink_current_target = $Ink_Current_Target;
    //    $this->ink_target_months = $Ink_Target_Months;
    //    $this->ink_actual_months = $Ink_Actual_Months;
    //    $this->current_fy_year = $CurrentFY_year;
    //    $this->past_fy_year = $pastFy_year;
    //    $this->paper_pastfytarget_prod = $Paper_PastFyTarget_Prod_Ream;
    //    $this->paper_pastfytarget_sg = $Paper_PastFyTarget_SG_Ream;
    //    $this->energy_pastfytarget_ave = $Energy_PastFyTarget_Average;
    //    $this->water_pastfytarget_ave = $Water_PastFyTarget_Average;
    //    $this->action_plan = $ActionPlan_array;
    }

    public function sheets(): array
    {
            //  FOR MONITORING SHEET
            //  FOR SG SHEET
        $sheets[] = new export_sg(

            // $this->energy_pastfyactual_ave,
            // $this->energy_target_ave,
            // $this->energy_target_months,
            // $this->energy_actual_months,

            // $this->water_pasftyactual_ave,// pastfy target
            // $this->water_target_ave,// current fy target
            // // $this->water_actual_ave,// current fy actual total
            // $this->water_target_months,// current fy target monthly
            // $this->water_actualpermp_months,// current fy actualpermp monthly

            // $this->paper_pastfyactual_sg,
            // $this->paper_target_sg,
            // $this->paper_target_months_sg,
            // $this->paper_actual_months_sg,

            // $this->current_fy_year,
            // $this->past_fy_year,
            // $this->paper_pastfytarget_sg,
            // $this->energy_pastfytarget_ave,
            // $this->water_pastfytarget_ave

            );

        return $sheets;
    }
}
