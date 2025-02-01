
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
                            <h1>TS IQC Inspection</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">TS IQC Inspection</li>
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
                                            <a class="nav-link active .menuTab" id="Pending-tab" data-bs-toggle="tab" href="#menu1" role="tab" aria-controls="menu1" aria-selected="true">WHS Packaging</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2" role="tab" aria-controls="menu2" aria-selected="false">YEU</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-4" id="myTabContent">
                                        <div class="row justify-content-end">
                                            <div class="col-sm-2 d-none">
                                                <label class="form-label">Lot Number</label>
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-primary" id="btnModalLotNum" el-btn-attr="whseTransaction"><i class="fa-solid fa-qrcode"></i></button>
                                                    <input type="search" class="form-control" placeholder="Lot Number" id="txtSearchLotNum" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="form-label">Material Category</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control" id="txtCategoryMaterial" disabled>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade show active" id="menu1" role="tabpanel" aria-labelledby="menu1-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">Rapid Whse Receiving</h3>
                                                </div>
                                                <div class="card-body">
                                                    {{-- <br><br> --}}
                                                    {{-- TABS --}}
                                                    {{-- <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-lg btn-outline-info float-end"><i class="fa fa-users" aria-hidden="true"></i>  Group by</button>
                                                        </div>
                                                    </div> txtScanVerifyData modalVerifyData --}}
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active .menuTab" id="Pending-tab" data-bs-toggle="tab" href="#menu1_1" role="tab" aria-controls="menu1_1" aria-selected="true">On-going</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2_1" role="tab" aria-controls="menu2_1" aria-selected="false">Inspected</a>
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="menu1_1" role="tabpanel" aria-labelledby="menu1_1-tab">
                                                            <div class="table-responsive">
                                                                <!-- style="max-height: 600px; overflow-y: auto;" -->
                                                                <table id="tblIqcWhsReceivingPackaging" class="table table-sm table-bordered table-striped table-hover"
                                                                    style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><center><i  class="fa fa-cog"></i></center></th>
                                                                            <th>Status</th>
                                                                            <th>Invoice</th>
                                                                            {{-- <th>Date Inspected</th> --}}
                                                                            {{-- <th>Time Inspected</th> --}}
                                                                            {{-- <th>App Ctrl No.</th> --}}
                                                                            {{-- <th>Classification</th> --}}
                                                                            {{-- <th>Family</th> --}}
                                                                            {{-- <th>Category</th> --}}
                                                                            <th>Supplier</th>
                                                                            <th>Part Code</th>
                                                                            <th>Part Name</th>
                                                                            <th>Lot No.</th>
                                                                            {{-- <th>Lot Qty.</th> --}}
                                                                            {{-- <th>Total Lot Size</th> --}}
                                                                            {{-- <th>AQL</th> --}}
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="menu2_1" role="tabpanel" aria-labelledby="menu2_1-tab">
                                                            <div class="table-responsive">
                                                                <!-- style="max-height: 600px; overflow-y: auto;" -->
                                                                <table id="tblIqcInspected" class="table table-sm table-bordered table-striped table-hover"
                                                                    style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><center><i  class="fa fa-cog"></i></center></th>
                                                                            <th>Status</th>
                                                                            <th>Date Inspected</th>
                                                                            <th>Time Inspected</th>
                                                                            <th>Supplier</th>
                                                                            <th>App Ctrl No.</th>
                                                                            {{-- <th>Classification</th> --}}
                                                                            {{-- <th>Family</th> --}}
                                                                            {{-- <th>Category</th> --}}
                                                                            <th>Part Code</th>
                                                                            <th>Part Name</th>
                                                                            <th>Lot No.</th>
                                                                            <th>Lot Qty.</th>
                                                                            {{-- <th>AQL</th> --}}
                                                                            <th>Inspector</th>
                                                                            <th>Date Created</th>
                                                                            <th>Date Updated</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
                                            <div class="card card-primary">
                                                <div class="card-header">
                                                    <h3 class="card-title">YEU Receiving</h3>
                                                </div>
                                                <div class="card-body">
                                                    {{-- <br><br> --}}
                                                    {{-- TABS --}}
                                                    {{-- <div class="row">
                                                        <div class="col-12">
                                                            <button class="btn btn-lg btn-outline-info float-end"><i class="fa fa-users" aria-hidden="true"></i>  Group by</button>
                                                        </div>
                                                    </div> txtScanVerifyData modalVerifyData --}}
                                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                        <li class="nav-item">
                                                            <a class="nav-link active .menuTab" id="Pending-tab" data-bs-toggle="tab" href="#menu1_2" role="tab" aria-controls="menu1_2" aria-selected="true">On-going</a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2_2" role="tab" aria-controls="menu2_2" aria-selected="false">Inspected</a>
                                                        </li>
                                                    </ul>
                                                    <br>
                                                    <div class="tab-content" id="myTabContent">
                                                        <div class="tab-pane fade show active" id="menu1_2" role="tabpanel" aria-labelledby="menu1_2-tab">
                                                            <div class="table-responsive">
                                                                <!-- style="max-height: 600px; overflow-y: auto;" -->
                                                                <table id="tblIqcYeuDetails" class="table table-sm table-bordered table-striped table-hover"
                                                                    style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><center><i  class="fa fa-cog"></i></center></th>
                                                                            <th>Status</th>
                                                                            <th>Invoice</th>
                                                                            {{-- <th>Date Inspected</th> --}}
                                                                            {{-- <th>Time Inspected</th> --}}
                                                                            {{-- <th>App Ctrl No.</th> --}}
                                                                            {{-- <th>Classification</th> --}}
                                                                            {{-- <th>Family</th> --}}
                                                                            {{-- <th>Category</th> --}}
                                                                            <th>Supplier</th>
                                                                            <th>Part Code</th>
                                                                            <th>Part Name</th>
                                                                            <th>Lot No.</th>
                                                                            {{-- <th>Lot Qty.</th> --}}
                                                                            {{-- <th>Total Lot Size</th> --}}
                                                                            {{-- <th>AQL</th> --}}
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="tab-pane fade" id="menu2_2" role="tabpanel" aria-labelledby="menu2_2-tab">
                                                            <div class="table-responsive">
                                                                <table id="tblIqcYeuInspected" class="table table-sm table-bordered table-striped table-hover"
                                                                    style="width: 100%;">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><center><i  class="fa fa-cog"></i></center></th>
                                                                            <th>Status</th>
                                                                            <th>Date Inspected</th>
                                                                            <th>Time Inspected</th>
                                                                            <th>Supplier</th>
                                                                            <th>App Ctrl No.</th>
                                                                            {{-- <th>Classification</th> --}}
                                                                            {{-- <th>Family</th> --}}
                                                                            {{-- <th>Category</th> --}}
                                                                            <th>Part Code</th>
                                                                            <th>Part Name</th>
                                                                            <th>Lot No.</th>
                                                                            <th>Lot Qty.</th>
                                                                            {{-- <th>AQL</th> --}}
                                                                            <th>Inspector</th>
                                                                            <th>Date Created</th>
                                                                            <th>Date Updated</th>
                                                                        </tr>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
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

        <!--- Modal modalSaveIqcInspection formSaveIqcInspection modalModeOfDefect modalLotNum-->
        @include('component.modal')

    @endsection

    @section('js_content')
        <script type="text/javascript">

            //form.iqcInspection.find('#iqc_coc_file').prop('required',true);
            //form.iqcInspection.find('#isUploadCoc').prop('required',true);
            $(document).ready(function () {
                globalVar = {
                    modeOfDefectsById: "",
                    section: "TS",
                    categoryMaterialPackaging: "37", //Rapid TS Whs Packaging V3
                    categoryMaterialYeu: "38", //YEU Receiving

                }

                tbl = {
                    iqcInspection:'#tblIqcInspection',
                    iqcWhsReceivingPackaging:'#tblIqcWhsReceivingPackaging',
                    iqcWhsDetails :'#tblWhsDetails',
                    iqcInspected:'#tblIqcInspected',
                    iqcYeuDetails:'#tblIqcYeuDetails',
                    iqcYeuInspected:'#tblIqcYeuInspected',

                };

                dataTable = {
                    iqcTsWhsPackaging: '',
                    iqcTsWhsPackagingInspected: '',
                    iqcYeuDetails: '',
                    iqcYeuInspected: '',

                };

                form = {
                    iqcInspection : $('#formSaveIqcInspection')
                };

                strDatTime = {
                    dateToday : new Date(), // By default Date empty constructor give you Date.now
                    currentDate : new Date().toJSON().slice(0, 10),
                    currentTime : new Date().toLocaleTimeString('en-GB', { hour: "numeric",minute: "numeric"}),
                    currentHours : new Date().getHours(),
                    currentMinutes : new Date().getMinutes(),
                }

                arrCounter= {
                    ctr : 0
                }

                btn = {
                    removeModLotNumber : $('#btnRemoveModLotNumber'),
                    saveComputation : $('#btnSaveComputation')
                }

                arrTableMod = {
                    lotNo : [],
                    modeOfDefects : [],
                    lotQty : []
                };

                getDropdownDetailsByOptValue(globalVar.section,$('#txtCategoryMaterial'),'iqc_category_material_id',globalVar.categoryMaterialPackaging);

                dataTable.iqcTsWhsPackaging = $(tbl.iqcWhsReceivingPackaging).DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_whs_packaging", //Rapid Ts Warehouse Packaging
                        data: function (param){
                            param.lotNum = $('#txtSearchLotNum').val()
                            param.categoryMaterial = globalVar.categoryMaterialPackaging;
                        },
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "rawAction", orderable:false, searchable:false },
                        { "data" : "rawStatus", orderable:false, searchable:false },
                        { "data" : "InvoiceNo" },
                        { "data" : "Supplier" },
                        { "data" : "PartNumber" },
                        { "data" : "MaterialType" },
                        { "data" : "Lot_number" },
                    ],
                });

                dataTable.iqcYeuDetails = $(tbl.iqcYeuDetails).DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_yeu_details", //Rapidx TS YEU Receiving
                        data: function (param){
                            param.lotNum = $('#txtSearchLotNum').val()
                            param.categoryMaterial = globalVar.categoryMaterialYeu;
                        },
                    },
                    fixedHeader: true,
                    "columns":[

                        { "data" : "rawAction", orderable:false, searchable:false },
                        { "data" : "rawStatus",orderable:false, searchable:false },
                        { "data" : "invoice_no" },
                        { "data" : "supplier" },
                        { "data" : "item_code" },
                        { "data" : "item_name" },
                        { "data" : "lot_no" },

                    ],
                });

                dataTable.iqcTsWhsPackagingInspected = $(tbl.iqcInspected).DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_iqc_inspection",
                        data: function (param){
                            param.lotNum = $('#txtSearchLotNum').val()
                            // param.categoryMaterial = $('#txtCategoryMaterial').val()
                        },
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "rawAction", orderable:false, searchable:false },
                        { "data" : "rawStatus",orderable:false, searchable:false },
                        { "data" : "date_inspected" },
                        { "data" : "time_inspected" }, //
                        { "data" : "app_ctrl_no" }, //
                        { "data": "supplier" },
                        // { "data" : "classification" },//
                        // { "data" : "family" },//
                        // { "data" : "category" },//
                        { "data" : "partcode" },
                        { "data" : "partname" },
                        { "data" : "lot_no" },
                        { "data" : "total_lot_qty" },
                        // { "data" : "aql" }, //
                        { "data" : "qc_inspector" }, //
                        { "data" : "created_at" },
                        { "data" : "updated_at" },
                    ],
                });

                dataTable.iqcYeuInspected = $(tbl.iqcYeuInspected).DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_iqc_inspection",
                        data: function (param){
                            param.lotNum = $('#txtSearchLotNum').val()
                            // param.categoryMaterial = $('#txtCategoryMaterial').val()
                        },
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "rawAction", orderable:false, searchable:false },
                        { "data" : "rawStatus",orderable:false, searchable:false },
                        { "data" : "date_inspected" },
                        { "data" : "time_inspected" }, //
                        { "data" : "app_ctrl_no" }, //
                        { "data": "supplier" },
                        // { "data" : "classification" },//
                        // { "data" : "family" },//
                        // { "data" : "category" },//
                        { "data" : "partcode" },
                        { "data" : "partname" },
                        { "data" : "lot_no" },
                        { "data" : "total_lot_qty" },
                        // { "data" : "aql" }, //
                        { "data" : "qc_inspector" }, //
                        { "data" : "created_at" },
                        { "data" : "updated_at" },
                    ],
                });

                $(tbl.iqcWhsReceivingPackaging).on('click','#btnEditIqcInspection', getTsWhsPackagingById);
                $(tbl.iqcInspected).on('click','#btnEditIqcInspection', editIqcInspected);
                $(tbl.iqcYeuDetails).on('click','#btnEditIqcInspection', editYeuIqcDetails);
                $(tbl.iqcYeuInspected).on('click','#btnEditIqcInspection', editIqcInspected);

                $('#btnLotNo').click(function (e) {
                    e.preventDefault();
                    $('#modalLotNo').modal('show');
                });

                $('#btnMod').click(function (e) {
                    e.preventDefault();
                    $('#modalModeOfDefect').modal('show');
                });


                $('#btnAddModLotNumber').click(function (e) {
                    e.preventDefault();

                    /* Selected Value */
                    let selectedLotNo = $('#mod_lot_no').val();
                    let selectedMod = $('#mode_of_defect').val();
                    let selectedLotQty = $('#mod_quantity').val();

                    if(selectedLotNo === null || selectedMod === null || selectedLotQty <= 0){
                        toastr.error('Error: Please Fill up all fields !');
                        return false;
                    }
                    /* Counter and Disabled Removed Button */
                    arrCounter.ctr++;
                    disabledEnabledButton(arrCounter.ctr)
                    /* Get selected array to the table */
                    var html_body  = '<tr>';
                        html_body += '<td>'+arrCounter.ctr+'</td>';
                        html_body += '<td>'+selectedLotNo+'</td>';
                        html_body += '<td>'+selectedMod+'</td>';
                        html_body += '<td>'+selectedLotQty+'</td>';
                        html_body += '</tr>';
                    $('#tblModeOfDefect tbody').append(html_body);

                    arrTableMod.lotNo.push(selectedLotNo);
                    arrTableMod.modeOfDefects.push(selectedMod);
                    arrTableMod.lotQty.push(selectedLotQty);
                });

                btn.saveComputation.click(function (e) {
                    e.preventDefault();
                    $('#modalModeOfDefect').modal('hide');
                    form.iqcInspection.find('#no_of_defects').val(arrTableMod.lotQty.reduce(getSum, 0));
                });

                btn.removeModLotNumber.click(function() {
                    arrCounter.ctr --;
                    disabledEnabledButton(arrCounter.ctr)

                    $('#tblModeOfDefect tr:last').remove();
                    arrTableMod.lotNo.splice(arrCounter.ctr, 1);
                    arrTableMod.modeOfDefects.splice(arrCounter.ctr, 1);
                    arrTableMod.lotQty.splice(arrCounter.ctr, 1);
                    console.log('deleted',arrTableMod.lotQty);
                    // console.log(arrTableMod);
                });

                $('#btnModalLotNum').click(function (e) {
                    e.preventDefault();
                    let elModalAttr = $(this).attr('el-btn-attr');
                    $('#modalLotNum').attr('el-modal-attr',elModalAttr).modal('show')
                });

                $('a[href="#menu1"]').click(function (e) {
                    e.preventDefault();
                    $('#btnModalLotNum').attr('el-btn-attr','whseTransaction')
                    $('#txtSearchLotNum').val('');
                    let categoryMaterial = globalVar.categoryMaterialPackaging;
                    dataTable.iqcTsWhsPackaging.draw();
                    dataTable.iqcTsWhsPackagingInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                    getDropdownDetailsByOptValue(globalVar.section,$('#txtCategoryMaterial'),'iqc_category_material_id',categoryMaterial)
                });

                $('a[href="#menu2"]').click(function (e) {
                    e.preventDefault();
                    $('#btnModalLotNum').attr('el-btn-attr','yeu')
                    $('#txtSearchLotNum').val('');
                    let categoryMaterial = globalVar.categoryMaterialYeu;

                    dataTable.iqcYeuDetails.draw();
                    dataTable.iqcYeuInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                    getDropdownDetailsByOptValue(globalVar.section,$('#txtCategoryMaterial'),'iqc_category_material_id',categoryMaterial)
                });

                $('a[href="#menu1_1"]').click(function (e) {
                    e.preventDefault();
                    $('#txtSearchLotNum').val('');
                    dataTable.iqcTsWhsPackaging.draw();
                });

                $('a[href="#menu2_1"]').click(function (e) {
                    e.preventDefault();
                    $('#txtSearchLotNum').val('');
                    let categoryMaterial = globalVar.categoryMaterialPackaging;
                    dataTable.iqcTsWhsPackagingInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                });

                $('a[href="#menu1_2"]').click(function (e) {
                    e.preventDefault();
                    $('#txtSearchLotNum').val('');
                    dataTable.iqcYeuDetails.draw();
                });

                $('a[href="#menu2_2"]').click(function (e) {
                    e.preventDefault();
                    $('#txtSearchLotNum').val('');
                    console.log('menu2_2');
                    let categoryMaterial = globalVar.categoryMaterialYeu;
                    dataTable.iqcYeuInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                });

                $('#modalLotNum').on('shown.bs.modal', function () {
                    $('#txtLotNum').focus();
                    const mdlScanLotNum = document.querySelector("#modalLotNum");
                    const inptScanLotNum = document.querySelector("#txtLotNum");
                    let focus = false

                    mdlScanLotNum.addEventListener("mouseover", () => {
                        if (inptScanLotNum === document.activeElement) {
                            focus = true
                        } else {
                            focus = false
                        }
                    });

                    mdlScanLotNum.addEventListener("click", () => {
                        if (focus) {
                            inptScanLotNum.focus()
                        }
                    });
                });

                $('#txtLotNum').on('keyup', function(e){
                    if(e.keyCode == 13){
                        $('#modalLotNum').modal('hide');
                        let modalId = $("#modalLotNum").attr('el-modal-attr');
                        let categoryMaterial = $('#txtCategoryMaterial').val();

                        if ( ( modalId ).indexOf('#') > -1){
                            $( modalId ).submit();
                        }else{
                            switch (modalId) {
                                case 'whseTransaction':
                                        $('#txtSearchLotNum').val($(this).val());
                                        dataTable.iqcTsWhsPackaging.draw();
                                        dataTable.iqcTsWhsPackagingInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                                    break;
                                case 'yeu':
                                        // alert('yeu')
                                        $('#txtSearchLotNum').val($(this).val());
                                        dataTable.iqcYeuDetails.draw();
                                        dataTable.iqcYeuInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterial).draw();
                                    break;

                                default:
                                    break;
                            }
                        }
                        $('#txtLotNum').val('');
                        $('#modalLotNum').modal('hide');
                    }
                });


                dataTable.iqcTsWhsPackaging.on('draw', function () {
                    if($('#txtSearchLotNum').val() != ""){
                        $('#tblIqcWhsReceivingPackaging tbody #btnEditIqcInspection').each(function(index, tr){
                            $(this).removeClass('d-none');
                        })
                    }
                });

                dataTable.iqcYeuDetails.on('draw', function () {
                    if($('#txtSearchLotNum').val() != ""){
                        $('#tblIqcYeuDetails tbody #btnEditIqcInspection').each(function(index, tr){
                            $(this).removeClass('d-none');
                        })
                    }
                });


                form.iqcInspection.find('#accepted').keyup(function() {
                    divDisplayNoneClass(form.iqcInspection,$(this).val());
                });

                form.iqcInspection.find('#iqc_coc_file_download').click(function (e) {
                    e.preventDefault();
                    let iqc_inspection_id = form.iqcInspection.find('#iqc_inspection_id').val();
                    window.open(`view_coc_file_attachment/${globalVar.section}/${iqc_inspection_id}`);
                });

                form.iqcInspection.find('#isUploadCoc').change(function (e) {
                    e.preventDefault();
                    $('#iqc_coc_file').val('');
                    if ($(this).is(':checked')) {
                        // form.iqcInspection.find('#iqc_coc_file').prop('required',true);
                        form.iqcInspection.find('#fileIqcCocUpload').removeClass('d-none',true);
                        form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                    }else{
                        form.iqcInspection.find('#iqc_coc_file').prop('required',false);
                        form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none',true);
                        form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                    }
                });

                form.iqcInspection.find('#severity_of_inspection').change(function (e) {
                    e.preventDefault();
                    let severityOfInspection = form.iqcInspection.find(this).val();
                    let inspectionLvl = form.iqcInspection.find('#inspection_lvl').val();
                    let aql = form.iqcInspection.find('#aql').val();
                    let totalLotQty = form.iqcInspection.find('#total_lot_qty').val();

                    getSamplingSizeBySamplingPlan (severityOfInspection,inspectionLvl,aql,totalLotQty)
                });

                form.iqcInspection.find('#inspection_lvl').change(function (e) {
                    e.preventDefault();
                    let severityOfInspection = form.iqcInspection.find('#severity_of_inspection').val();
                    let inspectionLvl = form.iqcInspection.find(this).val();
                    let aql = form.iqcInspection.find('#aql').val();
                    let totalLotQty = form.iqcInspection.find('#total_lot_qty').val();

                    getSamplingSizeBySamplingPlan (severityOfInspection,inspectionLvl,aql,totalLotQty)
                });

                form.iqcInspection.find('#aql').change(function (e) {
                    e.preventDefault();
                    let severityOfInspection = form.iqcInspection.find('#severity_of_inspection').val();
                    let inspectionLvl = form.iqcInspection.find('#inspection_lvl').val();
                    let aql = form.iqcInspection.find(this).val();
                    let totalLotQty = form.iqcInspection.find('#total_lot_qty').val();

                    getSamplingSizeBySamplingPlan (severityOfInspection,inspectionLvl,aql,totalLotQty)
                });

                // severity_of_inspection

                $('#txtScanUserId').on('keyup', function(e){
                    if(e.keyCode == 13){
                        // console.log($(this).val());
                        validateUser($(this).val(), [2,5], function(result){
                            if(result == true){
                                // console.log('true');
                                // submitProdData($(this).val());
                                // console.log('', $('#txtKeepSample1').val());
                                saveIqcInspection();
                            }
                            else{ // Error Handler
                                toastr.error('User not authorize!');
                            }
                        });
                        $(this).val('');
                    }
                });

                /*Submit*/
                $(form.iqcInspection).submit(function (e) {
                    e.preventDefault();
                    let categoryMaterialId = $('#txtCategoryMaterial').val();
                    form.iqcInspection.find('#shift').attr('disabled',false);
                    form.iqcInspection.find('#judgement').attr('disabled',false);
                    saveIqcInspection(categoryMaterialId);
                    // $('#modalScanQRSave').modal('show');
                });
            });

        </script>
    @endsection
{{-- @endauth --}}
