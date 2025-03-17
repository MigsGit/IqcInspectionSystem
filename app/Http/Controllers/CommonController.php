<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\IqcInspection;
use App\Models\CnIqcInspection;
use App\Models\YfIqcInspection;
use App\Models\PpdIqcInspection;
use App\Models\IqcDropdownDetail;
use Illuminate\Support\Facades\DB;
use App\Models\IqcDropdownCategory;
use Maatwebsite\Excel\Facades\Excel;
use App\Interfaces\ResourceInterface;
use Illuminate\Support\Facades\Storage;
use App\Exports\IqcInspectionReportExport;

class CommonController extends Controller
{
    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface) {
        $this->resourceInterface = $resourceInterface;
    }
    public function getSamplingSizeBySamplingPlan(Request $request)
    { //TS
        $arr_conditions = [
            $request->severity_of_inspection,
            $request->inspection_lvl,
            $request->aql,
            // $request->total_lot_qty,
        ];
        $model = IqcDropdownDetail::class;
        foreach ($arr_conditions as $key => $value) {
            $readIqcDropdownDetail [] = $this->resourceInterface->readCustomEloquent($model)->whereNull('deleted_at')->where('id',$value)->get();
        }
        // return $readIqcDropdownDetail[0][0];

        // return $readIqcDropdownDetail[1];
        $severityOfInspection = ( count($readIqcDropdownDetail[0]) == 1 ) ? $readIqcDropdownDetail[0][0]['dropdown_details']: '';
        $inspectionLvl = ( count($readIqcDropdownDetail[1]) == 1 ) ? $readIqcDropdownDetail[1][0]['dropdown_details'] : '';
        $aql = ( count($readIqcDropdownDetail[2]) == 1 ) ?$readIqcDropdownDetail[2][0]['dropdown_details'] : '';
        $total_lot_qty = $request->total_lot_qty;

        $size = 0;
        $accept = 0;
        $reject = 0;

        if ($severityOfInspection == 'Normal') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 20) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 20) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }
            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 200) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty <= 32) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 200) {
                        $size = 50;
                        $accept = 1;
                        $reject = 2;
                    }

                    elseif ($total_lot_qty >= 13 && $total_lot_qty <= 199) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }


                }

                if ($aql == 0.25) {
                    if ($total_lot_qty <= 80) {
                        $size = $total_lot_qty;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 100;
                    }
                    $accept = 0;
                    $reject = 1;
                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 && $total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 && $total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 && $total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 && $total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        } elseif($severityOfInspection == 'Reduced') {

            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty <= 12) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 13) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                }
                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 5;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty < 35000 && $total_lot_qty >= 13) {
                        $size = 20;
                        $accept = 1;
                        $reject = 2;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 20;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 20;
                    }
                        $accept = 0;
                        $reject = 1;
                }

            }

            if ($inspectionLvl == 'II') {
                if ($aql == 0.15) {
                    if ($total_lot_qty <= 125) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 && $total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 50) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 51 && $total_lot_qty <= 500) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 8;
                        $reject = 9;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 200) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 201 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 2;
                        $reject = 3;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 500) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 3200) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }
        if ($severityOfInspection == 'Tightened') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 51) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty <= 50) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;

                    }

                    elseif ($total_lot_qty < 35000 && $total_lot_qty >= 13) {
                        $size = 80;
                        $accept = 1;
                        $reject = 2;

                    }
                    elseif ($total_lot_qty <= 12) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;

                    }

                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 80;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 80;
                    }
                        $accept = 0;
                        $reject = 1;

                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 && $total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 && $total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 && $total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 && $total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }

        return $data = [
            'sample_size' => $size,
            'accept' => $accept,
            'reject' => $reject,
            'date_inspected' => date('Y-m-d'),
            // 'inspector' =>session('rapidx_'),
            //'workweek' =>$this->getWorkWeek()
        ];
    }
    public function getSamplingSizeBySamplingPlanCn(Request $request)
    { //CN
        $arr_conditions = [
            $request->severity_of_inspection,
            $request->inspection_lvl,
            $request->aql,
            // $request->total_lot_qty,
        ];
        $model = IqcDropdownDetail::class;
        foreach ($arr_conditions as $key => $value) {
            $readIqcDropdownDetail [] = $this->resourceInterface->readCustomEloquent($model)->whereNull('deleted_at')->where('id',$value)->get();
        }
        // return $readIqcDropdownDetail[0][0];

        // return $readIqcDropdownDetail[1];
        $severityOfInspection = ( count($readIqcDropdownDetail[0]) == 1 ) ? $readIqcDropdownDetail[0][0]['dropdown_details']: '';
        $inspectionLvl = ( count($readIqcDropdownDetail[1]) == 1 ) ? $readIqcDropdownDetail[1][0]['dropdown_details'] : '';
        $aql = ( count($readIqcDropdownDetail[2]) == 1 ) ?$readIqcDropdownDetail[2][0]['dropdown_details'] : '';
        $total_lot_qty = $request->total_lot_qty;
        $size = 0;
        $accept = 0;
        $reject = 0;

        if ($severityOfInspection == 'Normal') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 20) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 20) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }
            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 200) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 200) {
                        $size = 50;
                        $accept = 1;
                        $reject = 2;
                    }

                    elseif ($total_lot_qty >= 13 &&$total_lot_qty <= 199) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }


                }

                if ($aql == 0.25) {
                    if ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                    }

                    if ($total_lot_qty > 50) {
                        $size = 50;
                    }
                    $accept = 0;
                    $reject = 1;
                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 &&$total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 &&$total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 &&$total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        } elseif($severityOfInspection == 'Reduced') {

            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 13) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                }
                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 5;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty < 35000 &&$total_lot_qty >= 13) {
                        $size = 20;
                        $accept = 1;
                        $reject = 2;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 20;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 20;
                    }
                        $accept = 0;
                        $reject = 1;
                }

            }

            if ($inspectionLvl == 'II') {
                if ($aql == 0.15) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 51 &&$total_lot_qty <= 500) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 8;
                        $reject = 9;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 200) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 2;
                        $reject = 3;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 500) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }
        if ($severityOfInspection == 'Tightened') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 51) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;

                    }

                    elseif ($total_lot_qty < 35000 &&$total_lot_qty >= 13) {
                        $size = 80;
                        $accept = 1;
                        $reject = 2;

                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;

                    }

                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 80;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 80;
                    }
                        $accept = 0;
                        $reject = 1;

                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 &&$total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 &&$total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 &&$total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }

        return $data = [
            'sample_size' => $size,
            'accept' => $accept,
            'reject' => $reject,
            'date_inspected' => date('Y-m-d'),
            // 'inspector' =>Auth::user()->user_id,
            //'workweek' =>$this->getWorkWeek()
        ];
    }
    public function getSamplingSizeBySamplingPlanYf(Request $request)
    {
        $arr_conditions = [
            $request->severity_of_inspection,
            $request->inspection_lvl,
            $request->aql,
            // $request->total_lot_qty,
        ];
        $model = IqcDropdownDetail::class;
        foreach ($arr_conditions as $key => $value) {
            $readIqcDropdownDetail [] = $this->resourceInterface->readCustomEloquent($model)->whereNull('deleted_at')->where('id',$value)->get();
        }
        // return $readIqcDropdownDetail[0][0];

        // return $readIqcDropdownDetail[1];
        $severityOfInspection = ( count($readIqcDropdownDetail[0]) == 1 ) ? $readIqcDropdownDetail[0][0]['dropdown_details']: '';
        $inspectionLvl = ( count($readIqcDropdownDetail[1]) == 1 ) ? $readIqcDropdownDetail[1][0]['dropdown_details'] : '';
        $aql = ( count($readIqcDropdownDetail[2]) == 1 ) ?$readIqcDropdownDetail[2][0]['dropdown_details'] : '';
        $total_lot_qty = $request->total_lot_qty;
        $size = 0;
        $accept = 0;
        $reject = 0;
        if ($severityOfInspection == 'Normal') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 20) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 20) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty <= 13) {
                        $size = $total_lot_qty;
                    }

                    if ($total_lot_qty > 13) {
                        $size = 13;
                    }
                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 50) {
                        $size = $total_lot_qty;
                    }

                    if ($total_lot_qty > 50) {
                        $size = 50;
                    }
                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 && $total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 && $total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 && $total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 && $total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        } else {

            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty <= 50) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 50) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = $total_lot_qty;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 80;
                    }
                }
            }

            if ($inspectionLvl == 'II') {
                if ($aql == 0.15) {
                    if ($total_lot_qty <= 125) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 && $total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 50) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 51 && $total_lot_qty <= 500) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 8;
                        $reject = 9;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 200) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 201 && $total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 && $total_lot_qty <= 35000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 2;
                        $reject = 3;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 500) {
                        $size = $total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 && $total_lot_qty <= 3200) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 && $total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 && $total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 && $total_lot_qty <= 500000) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }
        return $data = [
            'sample_size' => $size,
            'accept' => $accept,
            'reject' => $reject,
            'date_inspected' => date('Y-m-d'),
            // 'inspector' =>Auth::user()->user_id,
            //'workweek' =>$this->getWorkWeek()
        ];
    }
    public function getSamplingSizeBySamplingPlanPpd(Request $request)
    { //PPD from CN Sampling Plan
        $arr_conditions = [
            $request->severity_of_inspection,
            $request->inspection_lvl,
            $request->aql,
            // $request->total_lot_qty,
        ];
        $model = IqcDropdownDetail::class;
        foreach ($arr_conditions as $key => $value) {
            $readIqcDropdownDetail [] = $this->resourceInterface->readCustomEloquent($model)->whereNull('deleted_at')->where('id',$value)->get();
        }
        // return $readIqcDropdownDetail[0][0];

        // return $readIqcDropdownDetail[1];
        $severityOfInspection = ( count($readIqcDropdownDetail[0]) == 1 ) ? $readIqcDropdownDetail[0][0]['dropdown_details']: '';
        $inspectionLvl = ( count($readIqcDropdownDetail[1]) == 1 ) ? $readIqcDropdownDetail[1][0]['dropdown_details'] : '';
        $aql = ( count($readIqcDropdownDetail[2]) == 1 ) ?$readIqcDropdownDetail[2][0]['dropdown_details'] : '';
        $total_lot_qty = $request->total_lot_qty;
        $size = 0;
        $accept = 0;
        $reject = 0;

        if ($severityOfInspection == 'Normal') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 20) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 20) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }
            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 200) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 200) {
                        $size = 50;
                        $accept = 1;
                        $reject = 2;
                    }

                    elseif ($total_lot_qty >= 13 &&$total_lot_qty <= 199) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }


                }

                if ($aql == 0.25) {
                    if ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                    }

                    if ($total_lot_qty > 50) {
                        $size = 50;
                    }
                    $accept = 0;
                    $reject = 1;
                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 &&$total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 &&$total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 &&$total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        } elseif($severityOfInspection == 'Reduced') {

            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty > 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 13) {
                        $size = 13;
                        $accept = 0;
                        $reject = 1;
                    }
                }
                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 5;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty < 35000 &&$total_lot_qty >= 13) {
                        $size = 20;
                        $accept = 1;
                        $reject = 2;
                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 20;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 20;
                    }
                        $accept = 0;
                        $reject = 1;
                }

            }

            if ($inspectionLvl == 'II') {
                if ($aql == 0.15) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 51 &&$total_lot_qty <= 500) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 8;
                        $reject = 9;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 200) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 2;
                        $reject = 3;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 500) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 2000;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }
        if ($severityOfInspection == 'Tightened') {
            if ($inspectionLvl == 'S2') {
                if ($aql == 0.65) {
                    if ($total_lot_qty <= 31) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }

                    if ($total_lot_qty >= 32) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    }
                }
            }

            if ($inspectionLvl == 'S3') {
                if ($aql == 0.40) {
                    if ($total_lot_qty >= 51) {
                        $size = 50;
                        $accept = 0;
                        $reject = 1;
                    }

                    elseif ($total_lot_qty <= 50) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    }
                }

                if ($aql == 1.00) {
                    if ($total_lot_qty >= 35000) {
                        $size = 20;
                        $accept = 0;
                        $reject = 1;

                    }

                    elseif ($total_lot_qty < 35000 &&$total_lot_qty >= 13) {
                        $size = 80;
                        $accept = 1;
                        $reject = 2;

                    }
                    elseif ($total_lot_qty <= 12) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;

                    }

                }

                if ($aql == 0.25) {
                    if ($total_lot_qty < 80) {
                        $size = 80;
                    }

                    if ($total_lot_qty > 80) {
                        $size = 80;
                    }
                        $accept = 0;
                        $reject = 1;

                }
            }
            if ($inspectionLvl == 'II') {

                if ($aql == 0.15) {
                    if ($total_lot_qty <= 80) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 81 &&$total_lot_qty <= 3200) {
                        $size = 80;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 5;
                        $reject = 6;
                    }
                }

                if ($aql == 0.40) {
                    if ($total_lot_qty <= 32) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 33 &&$total_lot_qty <= 500) {
                        $size = 32;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 501 &&$total_lot_qty <= 3200) {
                        $size = 125;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 10000) {
                        $size = 200;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 3;
                        $reject = 4;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 5;
                        $reject = 6;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 7;
                        $reject = 8;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 10;
                        $reject = 11;
                    }
                }

                if ($aql == 0.10) {
                    if ($total_lot_qty <= 125) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 126 &&$total_lot_qty <= 10000) {
                        $size = 125;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 10001 &&$total_lot_qty <= 35000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 500;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 800;
                        $accept = 2;
                        $reject = 3;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 3;
                        $reject = 4;
                    }
                }

                if ($aql == 0.04) {
                    if ($total_lot_qty <= 315) {
                        $size =$total_lot_qty;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 316 &&$total_lot_qty <= 3200) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 3201 &&$total_lot_qty <= 35000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 35001 &&$total_lot_qty <= 150000) {
                        $size = 315;
                        $accept = 0;
                        $reject = 1;
                    } elseif ($total_lot_qty >= 150001 &&$total_lot_qty <= 500000) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    } elseif ($total_lot_qty >= 500001) {
                        $size = 1250;
                        $accept = 1;
                        $reject = 2;
                    }
                }
            }
        }

        return $data = [
            'sample_size' => $size,
            'accept' => $accept,
            'reject' => $reject,
            'date_inspected' => date('Y-m-d'),
            // 'inspector' =>Auth::user()->user_id,
            //'workweek' =>$this->getWorkWeek()
        ];
    }
    public function viewCocFileAttachment(Request $request,$section,$iqc_inspection_id)
    {
        if($section == "TS"){
            $iqc_coc_file_name = IqcInspection::where('id',$iqc_inspection_id)->get('iqc_coc_file');
            return Storage::response( 'public/ts_iqc_inspection_coc/' . $iqc_inspection_id .'_'. $iqc_coc_file_name[0][ 'iqc_coc_file' ] );
        }
        if($section == "CN"){
            $iqc_coc_file_name = CnIqcInspection::where('id',$iqc_inspection_id)->get('iqc_coc_file');
            return Storage::response( 'public/cn_iqc_inspection_coc/' . $iqc_inspection_id .'_'. $iqc_coc_file_name[0][ 'iqc_coc_file' ] );
        }
        if($section == "YF"){
            $iqc_coc_file_name = YfIqcInspection::where('id',$iqc_inspection_id)->get('iqc_coc_file');
            return Storage::response( 'public/yf_iqc_inspection_coc/' . $iqc_inspection_id .'_'. $iqc_coc_file_name[0][ 'iqc_coc_file' ] );
        }
        if($section == "PPD"){
            $iqc_coc_file_name = PpdIqcInspection::where('id',$iqc_inspection_id)->get('iqc_coc_file');
            return Storage::response( 'public/ppd_iqc_inspection_coc/' . $iqc_inspection_id .'_'. $iqc_coc_file_name[0][ 'iqc_coc_file' ] );
        }

    }
    public function getSearchGroupBy(Request $request){
        $conditions = [
            'section' =>  $request->section
        ];
        return $readDropdownCategoryById = $this->resourceInterface->readOnlyRelationsAndConditions(IqcDropdownCategory::class,[],[],$conditions);

    }
    public function exportIqcInspectionReport(Request $request){
        try {
            // return  $request->all();
            $arr_filtered_arr_group_by1 = [];
            $arr_filtered_arr_group_by2 = [];
            $arr_group_by1 = $request->arr_group_by1;
            $arr_group_by2 = $request->arr_group_by2;

            foreach ($arr_group_by1 as $key => $value) {
                if($arr_group_by1[$key] != null){
                    $arr_filtered_arr_group_by1[] =$arr_group_by1[$key];
                }
            }
            foreach ($arr_group_by2 as $key => $value) {
                if($arr_group_by2[$key] != null){
                    $arr_filtered_arr_group_by2[] = $arr_group_by2[$key];
                }
            }

            //Check if $arr_filtered_arr_group_by2 is empty before using implode()
            if (!empty($arr_filtered_arr_group_by2)) {
                array_push($arr_group_by1, $arr_filtered_arr_group_by2);
                array_push($arr_group_by1);
            }
            //Array merge array group by 1 and array group by 2
            $arr_merge_group = array_merge(...array_map(function($item) {
                return (array) $item;
            }, $arr_group_by1));

            $validColumns = ['id','partcode', 'partname', 'supplier', 'lot_no', 'total_lot_qty', 'inspector', 'submission', 'judgement', 'lot_inspected', 'accepted', 'sampling_size', 'defects', 'remarks', 'classification','family'];
            foreach ($arr_merge_group as $column) {
                if (!in_array($column, $validColumns)) {
                    throw new \Exception("Unknown column '$column'");
                }
            }
            if($request->generate_type == "chart"){
                return $iqcInspectionByDateMaterialGroupBySheet =  CommonService::iqcInspectionByDateMaterialGroupBySupplierChart(
                    $request->from_date,
                    $request->to_date,
                    $request->material_category,
                    $arr_merge_group
                );
            }

            $iqcInspectionByDateMaterialGroupBySheet =  CommonService::iqcInspectionByDateMaterialGroupBySheet(
                $request->from_date,
                $request->to_date,
                $request->material_category,
                $arr_merge_group
            );
            $iqcInspectionRawSheet =  CommonService::iqcInspectionRawSheet(
                $request->from_date,
                $request->to_date,
                $request->material_category,
                $arr_merge_group
            );

            $export = new IqcInspectionReportExport(
                $iqcInspectionByDateMaterialGroupBySheet,
                $iqcInspectionRawSheet
            ); //Debug Function $export->coillection();

            return Excel::download(new IqcInspectionReportExport(
                $iqcInspectionByDateMaterialGroupBySheet,
                $iqcInspectionRawSheet
            ),
            'report.xlsx');

        } catch (\Throwable $th) {
            throw $th;
        }
    }
    // public function getChartIqcInspectionRecord(Request $request){
    //     try {
    //         return response()->json(['is_success' => 'true']);
    //     } catch (Exception $e) {
    //         return response()->json(['is_success' => 'false', 'exceptionError' => $e->getMessage()]);
    //     }
    // }
}
class CommonService{
    public function iqcInspectionRawSheet(
        $from_date,
        $to_date,
        $material_category
    ){

        $getIqcInspectionByMaterialCategoryDate = IqcInspection::
        with('user_iqc')
        ->where("iqc_category_material_id", "=", $material_category)
        // ->whereBetween('date_inspected', ["".$from_date."", "".$to_date.""])
        ->whereBetween('date_inspected', [$from_date, $to_date])
        ->get();
        return $getIqcInspectionByMaterialCategoryDate;
    }
    public function iqcInspectionByDateMaterialGroupBySheet(
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
        return $iqcInspectionCollection = collect($weekRanges)->map(function ($week)use($material_category,$arr_merge_group) {
            return IqcInspection::
            select('supplier')
            ->addSelect(
                DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
            )
            ->where("iqc_category_material_id", "=", "$material_category")
            ->whereBetween('date_inspected', [$week['start'], $week['end']])
            // ->groupBy('supplier')
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
    public function iqcInspectionByDateMaterialGroupBySupplierChart(
        $from_date,
        $to_date,
        $material_category,
        $arr_merge_group
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
        $iqcInspectionSupplier = IqcInspection::
        select('supplier')
        ->where("iqc_category_material_id", "=", "$material_category")
        ->whereBetween('date_inspected', [$startOfMonth, $endOfMonth])
        ->groupBy('supplier')
        ->get();

        // $targetLarDppm = ;

        // Fetch inspection data per week
        $iqcInspectionCollection = collect($weekRanges)->map(function ($week)use($material_category) {
            return $iqcInspectionPerSupplierCollection = IqcInspection::
                select(['supplier'])
                ->addSelect(
                    DB::raw("'".Carbon::parse($week['start'])->format('M j')." - ".Carbon::parse($week['end'])->format('j')."' as week_range"), // Display week range
                    // DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                    // DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                    // DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                    // DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                    // DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
                    DB::raw("ROUND( COUNT( CASE WHEN judgement = 1 THEN 1 END ) / ( SUM(lot_inspected) ) * 100,2) as 'actual_lar' "),
                    DB::raw("ROUND( SUM(no_of_defects)  / SUM(sampling_size) * 1000000,0) as 'actual_dppm' "),
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

            // lot_ok = lot_inspected - lot_rejected
            // Actual LAR = (lot inspected/lot_ok)
            // Actual DPPM = (total sampling/ng_qty) *1000000
        return response()->json(['iqcInspectionCollection' => $iqcInspectionCollection, 'iqcInspectionSupplier' => $iqcInspectionSupplier]);

        // ->filter(); // Remove empty records
        // return $iqcInspectionCollection->map(function ($rowIqcInspectionCollection){
        //     return $rowIqcInspectionCollection;
        // });
        // if(){

        // }

        // return $iqcInspectionCollection;
        foreach ($iqcInspectionSupplier as $key => $value) {
            if($value->supplier == $iqcInspectionCollection[$value->supplier]){
                return $iqcInspectionCollection[$value->supplier];
            }

            // return $iqcInspectionSupplier[0]->supplier;
            // return $key;
            // return $iqcInspectionCollection[$key]['sampling_size_sum'];
            // return $iqcInspectionCollection[$key]['week_end'];
            return $iqcInspectionCollection[$key];
        }
    }
    public function iqcInspectionByDateMaterialGroupBySupplier(
        $from_date,
        $to_date,
        $material_category
    ){
        // Get the start and end of the month
        // $startOfMonth = Carbon::parse($from_date);
        // $endOfMonth = Carbon::parse($to_date);

        $startOfDate = Carbon::parse($from_date);
        $endOfDate = Carbon::parse($to_date);
        return IqcInspection::
        select('supplier')
        ->addSelect(
            DB::raw("'".Carbon::parse($startOfDate)->format('M j')." - ".Carbon::parse($endOfDate)->format('j')."' as week_range"), // Display week range
            DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
            DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
            DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
            DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
            DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
        )
        ->where("iqc_category_material_id", "=", "$material_category")
        ->whereBetween('date_inspected', [$startOfDate, $endOfDate])
        ->groupBy('supplier')
        ->get()->filter();

    }
    /*
        $iqcInspectionByDateMaterialGroupBySupplier =  IqcInspection::
            select('supplier')
            ->addSelect(
                DB::raw("'".Carbon::parse($startOfMonth)->format('M j')." - ".Carbon::parse($endOfMonth)->format('j')."' as week_range"), // Display week range
                DB::raw("DATE_FORMAT(DATE_ADD(date_inspected, INTERVAL (7 - WEEKDAY(date_inspected)) DAY), '%e') AS week_end"),
                DB::raw("COUNT(CASE WHEN lot_inspected = 1 THEN 1 END) as inspected_count"),
                // DB::raw("COUNT(CASE WHEN judgement = 1 THEN 1 END) as accepted_count"),
                DB::raw("COUNT(CASE WHEN judgement = 2 THEN 1 END) as rejected_count"),
                DB::raw("SUM(sampling_size) as 'sampling_size_sum'"),
                DB::raw("SUM(no_of_defects) as 'no_of_defects_sum'"),
            )
            ->where("iqc_category_material_id", "=", "$material_category")
            ->whereBetween('date_inspected', [$startOfMonth, $endOfMonth ])
            // ->groupBy('supplier')
            ->groupBy('supplier')
            ->get()->filter(); // Remove empty records


    */
    //Chart Series


}
