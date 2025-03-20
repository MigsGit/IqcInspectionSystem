<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\IqcInspection;
use App\Models\CnIqcInspection;
use App\Models\YfIqcInspection;
use App\Models\PpdIqcInspection;
use App\Models\IqcDropdownDetail;
use App\Models\IqcDropdownCategory;
use App\Interfaces\ResourceInterface;
use App\Interfaces\CommonInterface;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\IqcInspectionReportExport;

class CommonController extends Controller
{
    protected $resourceInterface;
    protected $commonInterface;
    public function __construct(ResourceInterface $resourceInterface,CommonInterface $commonInterface) {
        $this->resourceInterface = $resourceInterface;
        $this->commonInterface = $commonInterface;
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
            $from_date = $request->from_date;
            $to_date = $request->to_date;
            $material_category = $request->material_category;

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
                $iqcInspectionByDateMaterialGroupBySheet =  $this->commonInterface->iqcInspectionByDateMaterialGroupBySupplierChart(
                    $from_date,
                    $to_date,
                    $material_category,
                );
                return response()->json($iqcInspectionByDateMaterialGroupBySheet);
            }

            $iqcInspectionByDateMaterialGroupBySheet =  CommonService::iqcInspectionByDateMaterialGroupBySheet(
                $from_date,
                $to_date,
                $material_category,
                $arr_merge_group
            );
            $iqcInspectionRawSheet =  CommonService::iqcInspectionRawSheet(
                $from_date,
                $to_date,
                $material_category,
            );

            $export = new IqcInspectionReportExport(
                $iqcInspectionByDateMaterialGroupBySheet,
                $iqcInspectionRawSheet
            );
            // Debug Function
            // return $export->collection();

            return Excel::download(new IqcInspectionReportExport(
                $iqcInspectionByDateMaterialGroupBySheet,
                $iqcInspectionRawSheet
            ),
            'report.xlsx');

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
class CommonService
{

    public function iqcInspectionRawSheet(
        $from_date,
        $to_date,
        $material_category
    ){

        /*
            dropdown_details

        */
        return IqcInspection::with([
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
