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
            $iqc_dropdown_category = IqcDropdownDetail::with('iqc_dropdown_category')->where('id',$request->material_category)->get();
            $iqc_dropdown_category_section = $iqc_dropdown_category[0]->iqc_dropdown_category->section;
            switch ($iqc_dropdown_category_section) {
                case 'TS':
                    $model = IqcInspection::class;
                    break;
                case 'CN':
                    $model = CnIqcInspection::class;
                    break;
                case 'PPD':
                    $model = PpdIqcInspection::class;
                    break;
                case 'YF':
                    $model = YfIqcInspection::class;
                    break;
                case 'IIS':
                    $model = IqcInspection::class;
                    break;
                default:
                    return response()->json([
                        'is_sucess'=> 'false',
                        'err_msg'=> 'Unknown Section',
                    ]);
                    break;
            }
            // return  $model;
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
                    $model,
                    $from_date,
                    $to_date,
                    $material_category,
                );
                return response()->json($iqcInspectionByDateMaterialGroupBySheet);
            }

            $iqcInspectionByDateMaterialGroupBySheet =  $this->commonInterface->iqcInspectionByDateMaterialGroupBySheet(
                $model,
                $from_date,
                $to_date,
                $material_category,
                $arr_merge_group
            );
            $iqcInspectionRawSheet =  $this->commonInterface->iqcInspectionRawSheet(
                $model,

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

