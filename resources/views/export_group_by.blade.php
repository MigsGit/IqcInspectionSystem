
@php $layout = 'layouts.admin_layout'; @endphp
{{-- @auth --}}
    @extends($layout)

    @section('title', 'TS IQC Inspection')

    @section('content_page')

        <style type="text/css">
            .hidden_scanner_input{
                position: absolute;
                opacity: 0;
            }
            textarea{
                resize: none;
            }

            #colDevice, #colMaterialProcess{
                transition: .5s;
            }

            .checked-ok { background: #5cec4c!important; }

        </style>


        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Export Group By</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Export Group By</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                            <!-- left column -->
                            <div class="col-12">
                                    <!-- Start Page Content -->
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active .menuTab" id="Pending-tab" data-bs-toggle="tab" href="#menu1" role="tab" aria-controls="menu1" aria-selected="true">TS</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2" role="tab" aria-controls="menu2" aria-selected="false">CN</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu3" role="tab" aria-controls="menu3" aria-selected="false">PPD</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu4" role="tab" aria-controls="menu4" aria-selected="false">YF</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-4" id="myTabContent">
                                        <div class="row justify-content-end">

                                        </div>
                                        <div class="tab-pane fade show active" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Test Socket (TS)</h3>
                                                </div>
                                                <div class="card-body">
                                                    {{-- TS GRAPH --}}
                                                    <div class="card">
                                                        <div class="card-header" id="headingFour">
                                                          <h5 class="mb-0">
                                                            <button id="" class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                              SAMPLE
                                                            </button>
                                                          </h5>
                                                        </div>
                                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-bs-parent="#accordionMain">
                                                          <div class="card-body">
                                                            <div class="row overflow-auto">
                                                                <div id="echart" style="width: 1000px; height: 800px;"></div>
                                                            </div>
                                                          </div> <!--end card-body-->
                                                        </div> <!--end collapseOne-->
                                                      </div> <!--end card-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Connectors (CN)</h3>
                                                </div>
                                                <div class="card-body">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="menu3" role="tabpanel" aria-labelledby="menu3-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Part Preparation Department (PPD)</h3>
                                                </div>
                                                <div class="card-body">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="menu4" role="tabpanel" aria-labelledby="menu4-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Y-Flex (YF)</h3>
                                                </div>
                                                <div class="card-body">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
        @include('component.modal')

        <!--- Modal modalSaveIqcInspection formSaveIqcInspection modalModeOfDefect modalLotNum-->

    @endsection

    @section('js_content')
        <script type="text/javascript">
            const getSearchGroupBy = function (){
                let columns = [
                    // { id:`iqc_category_material_id`, label:"Iqc Category Material"},
                    { id:`supplier`, label:"Supplier"},
                    { id:`lot_inspected`, label:"Lot Inspected"},
                    { id:`accepted`, label:"Lot Accepted"},
                    { id:`invoice_no`, label:"Invoice No"},
                    { id:`partcode`, label:"Partcode"},
                    { id:`partname`, label:"Partname"},
                    { id:`family`, label:"Family"},
                    { id:`app_no`, label:"App Ctrl Number"}, // { id:`app_no_extension`, label:"AppNoExtension"},
                    { id:`die_no`, label:"Die Number"},
                    { id:`lot_no`, label:"Lot Number"},
                    { id:`total_lot_qty`, label:"Total Lot Qty"},
                    { id:`classification`, label:"Classification"},
                    { id:`type_of_inspection`, label:"Type Of Inspection"},
                    { id:`severity_of_inspection`, label:"Severity Of Inspection"},
                    { id:`inspection_lvl`, label:"InspectionLvl"},
                    { id:`aql`, label:"Aql"},
                    { id:`accept`, label:"Accept"},
                    { id:`reject`, label:"Reject"},
                    { id:`shift`, label:"Shift"},
                    { id:`date_inspected`, label:"Date Inspected"},
                    { id:`inspector`, label:"Inspector"},
                    { id:`submission`, label:"Submission"},
                    { id:`category`, label:"Category"},
                    { id:`sampling_size`, label:"Sampling Size"},
                    { id:`no_of_defects`, label:"No Of Defects"},
                    { id:`judgement`, label:"Judgement"},
                ];
                // Populate the <select> using .html()
                let optionsHtml = "";
                optionsHtml += `<option value="N/A" selected disabled>-Select-</option>`;
                $('.searchGroupBy').empty().append(optionsHtml);
                columns.forEach(column => {
                    optionsHtml += `<option value="${column.id}" data-id="${column.id}">${column.label}</option>`;
                });
                $('.searchGroupBy').append(optionsHtml);
            }


            getDropdownDetailsByOptValue('TS',$('#txtSearchMaterialName'),'iqc_category_material_id');
            $(document).ready(function () {

                var chart = echarts.init(document.getElementById('echart'));
                options = {
                    legend: {},
                    xAxis: {
                        type: 'category',
                        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [
                        {
                        name: 'Step Start',
                        type: 'bar',
                        label: {
                            show: true,
                            position: 'top',
                            valueAnimation: true
                        },
                        data: [120, 132, 101, 134, 90, 230, 210]
                        },
                        {
                        name: 'Step Middle',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top',
                            valueAnimation: true
                        },
                        data: [220, 282, 201, 234, 290, 430, 410]
                        },
                        {
                        name: 'Step End',
                        type: 'line',
                        label: {
                            show: true,
                            position: 'top',
                            valueAnimation: true
                        },
                        data: [450, 432, 401, 454, 590, 530, 510]
                        }
                    ]
                };
                chart.setOption(options);
                $('#modalExportIqcInspectionRecord').modal('show');

                let search_group_html = ``;
                for (let index = 1; index <= 3; index++) {
                        search_group_html += `
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Group by</span>
                                </div>
                                <select class="form-control select2bs5 searchGroupBy" name="group_by_1_${[index]}" id="txtSearchGroupBy1"></select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100">Group by</span>
                                </div>
                                <select class="form-control select2bs5 searchGroupBy" name="group_by_2_${[index]}" id="txtSearchGroupBy2"></select>
                            </div>
                        </div>
                    `;
                }
                $('.rowGroupBy').html(search_group_html);
                getSearchGroupBy();
            });
            //TODO:Get group by LAR DDPM like WBS

            let allcolumns = [
                    // { id:`iqc_category_material_id`, label:"Firsname"},
                    { id:`invoice_no`, label:"Invoice No"},
                    { id:`partcode`, label:"partcode"},
                    { id:`partname`, label:"partname"},
                    { id:`supplier`, label:"supplier"},
                    { id:`family`, label:"family"},
                    { id:`app_no`, label:"app_no"},
                    { id:`app_no_extension`, label:"app_no_extension"},
                    { id:`die_no`, label:"die_no"},
                    { id:`lot_no`, label:"lot_no"},
                    { id:`total_lot_qty`, label:"total_lot_qty"},
                    { id:`classification`, label:"classification"},
                    { id:`type_of_inspection`, label:"type_of_inspection"},
                    { id:`severity_of_inspection`, label:"severity_of_inspection"},
                    { id:`inspection_lvl`, label:"inspection_lvl"},
                    { id:`aql`, label:"aql"},
                    { id:`accept`, label:"accept"},
                    { id:`reject`, label:"reject"},
                    { id:`shift`, label:"shift"},
                    { id:`date_inspected`, label:"date_inspected"},
                    { id:`time_ins_from`, label:"time_ins_from"},
                    { id:`time_ins_to`, label:"time_ins_to"},
                    { id:`inspector`, label:"inspector"},
                    { id:`submission`, label:"submission"},
                    { id:`category`, label:"category"},
                    { id:`target_lar`, label:"target_lar"},
                    { id:`target_dppm`, label:"target_dppm"},
                    { id:`sampling_size`, label:"sampling_size"},
                    { id:`lot_inspected`, label:"lot_inspected"},
                    { id:`accepted`, label:"accepted"},
                    { id:`no_of_defects`, label:"no_of_defects"},
                    { id:`judgement`, label:"judgement"},
                ];
            $('#btnExportIqcInspectionRecord').click(function () {
                let arr_group_by1 =[];
                let arr_group_by2 =[];
                for (let index = 1; index <= 3; index++) {
                    let groupBy1 = $(`select[name="group_by_1_${index}"]`).val();
                    let groupBy2 = $(`select[name="group_by_2_${index}"]`).val();
                    arr_group_by1.push(groupBy1);
                    arr_group_by2.push(groupBy2);
                }
                let params = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    material_category: $('select[name="material_category"]').val(),
                    arr_group_by1: arr_group_by1,
                    arr_group_by2: arr_group_by2,
                };
                var queryString = $.param(params);
                window.location.href = "{{ route('download.export_iqc_inspection_report') }}?" + queryString;
            });
            $('#btnChartIqcInspectionRecord').click(function () {
                let arr_group_by1 =[];
                let arr_group_by2 =[];
                for (let index = 1; index <= 3; index++) {
                    let groupBy1 = $(`select[name="group_by_1_${index}"]`).val();
                    let groupBy2 = $(`select[name="group_by_2_${index}"]`).val();
                    arr_group_by1.push(groupBy1);
                    arr_group_by2.push(groupBy2);
                }
                let params = {
                    from_date: $('input[name="from_date"]').val(),
                    to_date: $('input[name="to_date"]').val(),
                    material_category: $('select[name="material_category"]').val(),
                    arr_group_by1: arr_group_by1,
                    arr_group_by2: arr_group_by2,
                    generate_type: 'chart',
                };
                // var queryString = $.param(params);
                console.log('params',params);
                call_ajax(params,'export_iqc_inspection_report',function(response){
                    
                })
                // window.location.href = "{{ route('download.export_iqc_inspection_report') }}?" + queryString;
            });
        </script>
    @endsection
{{-- @endauth --}}
