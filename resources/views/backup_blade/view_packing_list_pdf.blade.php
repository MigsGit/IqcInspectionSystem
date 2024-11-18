<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Packing List (PDF)</title>
        @php
            $created_at = explode(' ', $packing_list_details[0]->created_at);
        @endphp
        <style>
            .container {
                position: relative;
            }

            *{
                margin: 0;
                padding: 0;
            }

            body{
                font:12px Georgia, serif;
            }

            .control_no {
                position: absolute;
                top: 80px;
                left: 500px;
                font:18px Georgia, serif;
            }

            .date_created {
                position: absolute;
                top: 165px;
                left: 550px;
            }

            .pu_time{
                position: absolute;
                top: 195px;
                left: 550px;
            }

            .sanno{
                position: absolute;
                top: 270px;
                left: 25px;
                right: 0;
                bottom: 0;
                margin: auto;
            }

            .sanno_details{
                display: inline-block !important;
                height: 50px;
                width: 340px;
                margin: 20px;
            }

            .sanno_details .data{
                text-align: center;
            }

            .doc_header{
                position: absolute;
                top: 358px;
                left: 80px;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 5%;
                margin: 10px;
            }

            .table_content{
                position: absolute;
                top: 465px;
                left: 25px;
                right: 0;
                bottom: 0;
                width: 80%;
                height: 380px;
                font:14px Georgia, serif;
            }

            .table_content .data{
                text-align: center;
            }

            /* .table_content tr{
                border: 1 solid black;
            }

            .table_content th{
                border: 1 solid black;
            }

            .table_content td{
                border: 1 solid black;
            } */

            .doc_footer{
                position: absolute;
                top: 650px;
                left: 0;
                right: 0;
                bottom: 0;

                width: 100%;
                height: 5%;
                margin: auto;
            }

            .doc_footer_div{
                display: inline-block !important;
                height: 50px;
                width: 190px;
                margin: 0.1px;
                /* border: 1 solid black; */
            }

            .doc_footer_div .data{
                text-align: center;
            }

        </style>
    </head>
    <body>
        <?php
            $final_grand_total = [];
            $loop = 1;
            // if(count($packing_list_details) > 10){
                 if(count($packing_list_details) > 7 && count($packing_list_details) <= 14){
                        $loop = 2;
                 }else if(count($packing_list_details) > 14 && count($packing_list_details) <= 21){
                        $loop = 3;
                 }else if(count($packing_list_details) > 21 && count($packing_list_details) <= 28){
                        $loop = 4;
                 }else if(count($packing_list_details) > 28){
                        $loop = 5;
                 }

                for($index = 1; $index <= $loop; $index++){
        ?>
                <div class="container">
                    {{-- COMMENT BACKGROUND IMAGE --}}
                    {{-- <img src="<?php //echo $base64 ?>" width="100%" height="100%"/> --}}

                    <div class="control_no">
                        <b>
                            <span class="label">CTRL #:</span>
                            <span>{{ $packing_list_details[0]->control_no }}</span>
                        </b>
                    </div>
                    <div class="date_created">
                        <span class="data">{{ $created_at[0] }}</span>
                    </div>
                    <div class="pu_time">
                        <div class="">
                            <span class="data">P/U Time:</span>
                            <span class="data">{{ $packing_list_details[0]->pick_up_time }}</span>
                        </div>
                        <div class="">
                            <span class="data">P/U Date:</span>
                            <span class="data">{{ $packing_list_details[0]->pick_up_date }}</span>
                        </div>
                    </div>
                    <div class="sanno">
                        <div class="sanno_details">
                            <div class="data">SANNO PHILS. MANUFACTURING CORP.</div>
                            <div class="data">Special Export Processing Zone, Gateway Business Park,</div>
                            <div class="data">Javalera, Gen. Trias, Cavite, Philippines</div>
                            <!--
                            <div class="data">PRICON MICROELECTRONICS, INC.</div>
                            <div class="data">Lot 1-2 Block 5 Elysan Road Light Industry and Science Park IV</div>
                            <div class="data">Bulihan 4233, Malvar, Batangas, Philippines</div>
                            -->
                        </div>
                        <div class="sanno_details">
                            <div class="data">SANNO PHILS. MANUFACTURING CORP.</div>
                            <div class="data">Special Export Processing Zone, Gateway Business Park,</div>
                            <div class="data">Javalera, Gen. Trias, Cavite, Philippines</div>
                        </div>
                    </div>
                    <div class="doc_header">
                            <div style="margin-bottom:25px">
                                <span class="data" style="margin-right:20%">{{ $packing_list_details[0]->carrier }}</span>
                                <span class="data" style="margin-right:22%">{{ $created_at[0] }}</span>
                                <span class="data" style="text-align: left;">FROM: {{ $packing_list_details[0]->product_from }}</span>
                            </div>
                            <div>
                                <span class="data" style="margin-right:20%">{{ $packing_list_details[0]->port_of_loading }}</span>
                                <span class="data" style="margin-right:20%">{{ $packing_list_details[0]->port_of_destination }}</span>
                                <span class="data">TO: {{ $packing_list_details[0]->product_to }}</span>
                            </div>
                    </div>

                    <table class="table_content">
                        <tr>
                            <th style="width:90px;"></th>
                            <th style="width:135px;">PO #</th>
                            <th style="width:150px;">Various Contact for Plating Raw Materials</th>
                            <th style="width:130px;" colspan="2">LOT NO</th>
                            <th style="width:130px;" colspan="2">QUANTITY</th>
                        </tr>
                        @php
                                $grand_total_qty = [];
                            for($ii = 0; $ii < count($packing_list_details_mat_count); $ii++){
                                $total_qty = [];
                                for ($i = 0; $i < count($packing_list_details); $i++){
                                    if($packing_list_details_mat_count[$ii]->mat_name == $packing_list_details[$i]->mat_name){
                                        echo    '<tr>';
                                        echo        '<td class="data">C/NO.'.$packing_list_details[$i]->box_no.'</td>';
                                        echo        '<td></td>';
                                        // echo        '<td class="data">'.$packing_list_details[$i]->po_no.'</td>';
                                        echo        '<td class="data" style="text-align:left !important;">'.$packing_list_details[$i]->mat_name.'</td>';
                                        echo        '<td colspan="2"  style="text-align:left !important;" class="data">'.$packing_list_details[$i]->lot_no.'</td>';
                                        echo        '<td colspan="2"  style="text-align:right !important;" class="data">'.$packing_list_details[$i]->quantity.' pcs.</td>';
                                        // echo        '<td class="data" style="text-align:left !important;">pcs.</td>';
                                        echo    '</tr>';
                                        $total_qty[] = $packing_list_details[$i]->quantity;
                                    }
                                }
                                    echo '<tr>
                                            <td colspan="3"></td>
                                            <td colspan="2" style="text-align:right !important;" class="data"><strong>Total ===><strong></td>
                                            <td colspan="2 class="data" style="text-align:right !important;"><strong>'.array_sum($total_qty).'pcs.</strong></td>
                                        </tr><br>';

                                    $grand_total_qty[] = array_sum($total_qty);
                            }
                                    $final_grand_total[] = array_sum($grand_total_qty);

                            if($index == $loop){
                                echo '<tr>
                                        <td colspan="3"></td>
                                        <td colspan="2" style="text-align:right !important;" class="data"><strong>Grand Total ===><strong></td>
                                        <td colspan="2 class="data" style="text-align:right !important;"><strong>'.array_sum($final_grand_total).'pcs.</strong></td>
                                    </tr>';
                            }

                        @endphp
                    </table>

                    <div class="doc_footer">
                        <div class="doc_footer_div" style="margin-left: 5px; width: 24%;">
                            <div class="data"><b>Prepared By:</b></div>
                            @php
                                $prepared_by_arr = [];
                                if($packing_list_details[0]->checked_by == null){
                                    echo '<div class="data">&nbsp;</div>';
                                }else{
                                    $prepared_by = explode(' ', $packing_list_details[0]->prepared_by);
                                    $prepared_by_arr[] = $prepared_by[0];
                                    for($cbi = 0; $cbi < count($prepared_by); $cbi++){
                                        if($cbi == (count($prepared_by) - 1)){
                                            $prepared_by_arr[] = $prepared_by[$cbi][0];
                                        }
                                    }
                                }
                                echo '<div class="data">'.implode(' ',$prepared_by_arr).'.'.'</div>';
                            @endphp
                            <!-- <div class="data">{{ 'PPS-PPC CLERK' }}</div> -->
                            <div class="data">{{ 'PPD-PPC Clerk / Jr. Planner' }}</div> <!-- 04192024 by nessa -->
                        </div>
                        <div class="doc_footer_div" style="width: 23%;">
                            <div class="data"><b>Checked By:</b></div>
                            @php
                                $checked_by_arr = [];
                                if($packing_list_details[0]->checked_by == null){
                                    echo '<div class="data">&nbsp;</div>';
                                }else{
                                    $checked_by = explode(' ', $packing_list_details[0]->checked_by);
                                    $checked_by_arr[] = $checked_by[0];
                                    for($cbi = 0; $cbi < count($checked_by); $cbi++){
                                        if($cbi == (count($checked_by) - 1)){
                                            $checked_by_arr[] = $checked_by[$cbi][0];
                                        }
                                    }
                                }
                                echo '<div class="data">'.implode(' ',$checked_by_arr).'.'.'</div>';
                            @endphp
                            <!-- <div class="data">{{ 'PPS-PPC Sr. Planner' }}</div> -->
                            <div class="data">{{ 'Manager' }}</div> <!-- 04192024 by nessa -->
                        </div>

                        <!-- <div class="doc_footer_div" style="width: 20%;">
                            <div class="data">&nbsp;</div>
                            <div class="data">&nbsp;</div>
                            <div class="data">{{ 'Whse Supvr / Manager' }}</div>
                        </div> --> <!-- 04192024 by nessa -->

                        <div class="doc_footer_div" style="width: 30%;">
                            <div class="data" style="text-align: left;"><b>Cc:</b></div>
                            @php
                                $cc_personnel_arr = [];
                                if($packing_list_details[0]->cc_personnel == null){
                                    echo '<div class="data">&nbsp;</div>';
                                }else{
                                    $cc_personnel = explode(',', $packing_list_details[0]->cc_personnel);
                                    for($cci = 0; $cci < count($cc_personnel); $cci++){
                                        $cc_personnel1 = explode(' ', $cc_personnel[$cci]);
                                        $cc_personnel_arr[] = $cc_personnel1[0].' '.$cc_personnel1[1][0].'.';
                                    }
                                }
                                echo '<div class="data">Cris M. /'.implode(' / ',$cc_personnel_arr).'</div>';
                            @endphp
                            <div class="data">{{ 'Traffic / Prod`n / QC' }}</div>
                        </div>
                    </div>
                </div>
        <?php
                }

            // }
        ?>
    </body>
</html>
