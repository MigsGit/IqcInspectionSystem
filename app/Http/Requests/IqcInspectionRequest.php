<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IqcInspectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // 'iqc_inspection_id'=>'required',
        // 'no_of_defects'=>'required',
        // 'remarks'=>'required',
        return [
            'whs_transaction_id'=>'required',
            'receiving_detail_id'=>'required',
            // 'invoice_no'=>'required',
            'partcode'=>'required',
            'partname'=>'required',
            'supplier'=>'required',
            'family'=>'required',
            'app_no'=>'required',
            'app_no_extension'=>'required',
            'die_no'=>'required',
            'total_lot_qty'=>'required',
            'lot_no'=>'required',
            'classification'=>'required',
            'type_of_inspection'=>'required',
            'severity_of_inspection'=>'required',
            'inspection_lvl'=>'required',
            'aql'=>'required',
            'accept'=>'required',
            'reject'=>'required',
            'shift'=>'required',
            'date_inspected'=>'required',
            'time_ins_from'=>'required',
            'time_ins_to'=>'required',
            // 'inspector'=>'required',
            'submission'=>'required',
            'category'=>'required',
            'target_lar'=>'required',
            'target_dppm'=>'required',
            'sampling_size'=>'required',
            'lot_inspected'=>'required',
            'accepted'=>'required',
            'judgement'=>'required',
        ];
    }
}
