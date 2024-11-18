@php $layout = 'layouts.admin_layout'; @endphp
{{-- @auth
  @php
    if(Auth::user()->user_level_id == 1){
      $layout = 'layouts.super_user_layout';
    }
    else if(Auth::user()->user_level_id == 2){
      $layout = 'layouts.admin_layout';
    }
    else if(Auth::user()->user_level_id == 3){
      $layout = 'layouts.user_layout';
    }
  @endphp
@endauth --}}

@auth
    @extends($layout)

    @section('title', 'Second Molding')

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
        </style>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>In-Process Quality Control (Second Molding)</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item">Second Molding</li>
                                <li class="breadcrumb-item active">In-Process Quality Control</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-2">
                                                <label class="form-label">Device Name:</label>
                                            <div class="input-group mb-3">
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Select Device Name"></i>
                                                <select class="form-control select2bs5" id="txtSelectSecondMoldingDevice" name="sel_device_name" placeholder="Select Device Name"></select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <label class="form-label">Part Code</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Product Code" aria-label="Partcode" id="txtSearchPartCode" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="form-label">Material Name</label>
                                            <div class="input-group mb-3">
                                                <input type="text" class="form-control" placeholder="Material Name" aria-label="Materialname" id="txtSearchMaterialName" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- left column -->
                        <div class="col-12">
                            <!-- general form elements -->
                            {{-- <div class="card card-primary"> --}}
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">In-Process Quality Control</h3>
                                </div>
                                <!-- Start Page Content -->
                                <div class="card-body">
                                    {{-- TABS --}}
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="Pending-tab" data-bs-toggle="tab" href="#Pending" role="tab" aria-controls="Pending" aria-selected="true">Pending</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Completed-tab" data-bs-toggle="tab" href="#Completed" role="tab" aria-controls="Completed" aria-selected="false">Completed</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="Resetup-tab" data-bs-toggle="tab" href="#Resetup" role="tab" aria-controls="Resetup" aria-selected="false">For Re-Setup</a>
                                        </li>
                                    </ul>
                                    <br>
                                    <div class="tab-content" id="myTabContent">
                                        {{-- Pending Tab --}}
                                        <div class="tab-pane fade show active" id="Pending" role="tabpanel" aria-labelledby="Pending-tab">
                                            <div class="table-responsive">
                                                <table id="tbl2ndMoldingIpqcInspPending" class="table table-sm table-bordered table-striped table-hover text-center"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>IPQC Status</th>
                                                            <th>Created At</th>
                                                            <th>PO Number</th>
                                                            <th>Production Lot#</th>
                                                            <th>Judgement</th>
                                                            <th>QC Sample</th>
                                                            <th>Inspected At</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- Completed Tab --}}
                                        <div class="tab-pane fade" id="Completed" role="tabpanel" aria-labelledby="Completed-tab">
                                            <div class="table-responsive">
                                                <table id="tbl2ndMoldingIpqcInspCompleted" class="table table-sm table-bordered table-striped table-hover text-center"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>IPQC Status</th>
                                                            <th>Created At</th>
                                                            <th>PO Number</th>
                                                            <th>Production Lot#</th>
                                                            <th>Judgement</th>
                                                            <th>QC Sample</th>
                                                            <th>Inspected At</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        {{-- For Re-Setup Tab --}}
                                        <div class="tab-pane fade" id="Resetup" role="tabpanel" aria-labelledby="Resetup-tab">
                                            <div class="table-responsive">
                                                <table id="tbl2ndMoldingIpqcInspResetup" class="table table-sm table-bordered table-striped table-hover text-center"
                                                    style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>Action</th>
                                                            <th>IPQC Status</th>
                                                            <th>Created At</th>
                                                            <th>PO Number</th>
                                                            <th>Production Lot#</th>
                                                            <th>Judgement</th>
                                                            <th>QC Sample</th>
                                                            <th>Inspected At</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- TABS END --}}
                                </div>
                                <!-- !-- End Page Content -->

                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- CONFIRM SUBMIT MODAL START -->
        <div class="modal fade" id="modalConfirmSubmitIPQCInspection">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary">
                        <h4 class="modal-title"><i class="fa-solid fa-file-circle-check"></i>&nbsp;&nbsp;Confirmation</h4>
                        <button type="button" style="color: #fff" class="close" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="FrmConfirmSubmitIPQCInspection">
                        @csrf
                        <div class="modal-body">
                            <div class="row d-flex justify-content-center">
                                <label class="text-secondary mt-2">Are you sure you want to proceed?</label>
                                <input type="hidden" class="form-control" name="cnfrm_second_molding_id" id="cnfrmtxtFirstMoldingId">
                                <input type="hidden" class="form-control" name="cnfrm_ipqc_id" id="cnfrmtxtIPQCId">
                                <input type="hidden" class="form-control" name="cnfrm_ipqc_status" id="cnfrmtxtIPQCStatus">
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnConfirmSubmitIPQCInspection" class="btn btn-primary"><i id="ConfirmSubmitIPQCInspectionIcon"
                                    class="fa fa-check"></i> Proceed</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- CONFIRM SUBMIT MODAL END -->

        <!-- MODALS -->
        {{-- * ADD --}}
        <div class="modal fade" id="modalIpqcInspection" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-plus"></i> Add IPQC Inspection Data</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formIPQCInspectionData" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" id="txtIpqcId" name="ipqc_id">
                            <input type="hidden" id="txtFirstMoldingId" name="second_molding_id">
                            <input type="hidden" id="txtProcessCategory" name="process_category">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">PO Number:</label>
                                                <input type="text" class="form-control form-control-sm" name="po_number" id="txtPoNumber" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Part Code:</label>
                                                <input type="text" class="form-control form-control-sm" name="part_code" id="txtPartCode" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Material Name:</label>
                                                <input type="text" class="form-control form-control-sm" name="material_name" id="txtMaterialName" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Production Lot #:</label>
                                                <input type="text" class="form-control form-control-sm" name="production_lot" id="txtProductionLot" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">QC Sample:</label>
                                                <i class="fa-solid fa-circle-info fa-lg mt-2 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="QC Sample Qty"></i>
                                                <input type="text" class="form-control form-control-sm" name="input" id="txtInput" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">OK:</label>
                                                <input type="text" class="form-control form-control-sm" name="output" id="txtOutput"
                                                            onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">NG:</label>
                                                <i class="fa-solid fa-circle-info fa-lg mt-3 mr-2" data-bs-toggle="tooltip" data-bs-html="true" title="Input - Output = NG Qty"></i>
                                                <input type="text" class="form-control form-control-sm" name="ng_qty" id="txtNGQty" readonly>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-check-label"> Keep Sample:</label>
                                                <div class="form-check form-check-inline ml-1">
                                                    <input class="form-check-input" type="radio" value="1" name="keep_sample" id="txtKeepSample1">
                                                    <label class="form-check-label" for="txtKeepSample1"> Yes</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" value="2" name="keep_sample" id="txtKeepSample2">
                                                    <label class="form-check-label" for="txtKeepSample2"> No</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label class="form-label">Judgement:</label>
                                                {{-- <input type="text" class="form-control form-control-sm" name="judgement" id="txtJudgement"> --}}
                                                <select class="form-control form-control-sm" type="text" name="judgement" id="txtJudgement" required>
                                                    <option value="" disabled selected>Select Judgement</option>
                                                    <option value="Accepted" style="color:#008000">Accepted</option>
                                                    <option value="Rejected" style="color:#ff0000">Rejected</option>
                                                </select>
                                            </div>
                                            <div class="form-group mt-1">
                                                <label class="form-label">Inspector Name:</label>
                                                <input type="hidden" class="form-control form-control-sm" name="inspector_id" id="txtInspectorID" readonly value="@php echo Auth::user()->id; @endphp" readonly>
                                                {{-- `${let name = response['users'][index].rapidx_user_details.firstname + response['users'][index].rapidx_user_details.lastname}` --}}
                                                <input type="text" class="form-control form-control-sm" name="inspector_name" id="txtInspectorName" value="@php echo Auth::user()->firstname.' '.Auth::user()->lastname; @endphp" readonly>
                                            </div>
                                            {{-- DROPDOWN --}}
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(B Drawing):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="BDrawingDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoBDrawing" name="doc_no_b_drawing">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(Inspection Standard):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="InspStandardDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoInspStandard" name="doc_no_inspection_standard">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                    <label class="form-label">Doc No.(UD):</label>
                                                <div class="input-group input-group-sm" style="width: 100%;">
                                                    <div id="UDDiv" class="input-group-prepend">
                                                    </div>
                                                    <select class="form-control form-control-sm" id="txtSelectDocNoUD" name="doc_no_ud">
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="form-group">
                                                <label class="form-label">MeasData Attachment:</label>
                                                <input type="text" class="form-control form-control-sm" name="drawing_no" id="txtDrawingNo" readonly>
                                            </div> --}}
                                            {{-- ATTACHMENT --}}
                                            <div class="form-group">
                                                <div id="AttachmentDiv">
                                                    <label class="form-control-label">MeasData Attachment:</label>
                                                </div>
                                                    <input type="file" class="form-control form-control-sm" id="txtAddFile" name="uploaded_file" accept=".xlsx, .xls, .csv" style="width:100%;" required>
                                                    <input type="text" class="form-control form-control-sm d-none" name="re_uploaded_file" id="txtEditUploadedFile" readonly>
                                                <div class="form-group form-check d-none m-0" id="btnReuploadTriggerDiv">
                                                    <input type="checkbox" class="form-check-input d-none" id="btnReuploadTrigger">
                                                    <label class="d-none" id="btnReuploadTriggerLabel"> Re-upload Attachment</label>
                                                </div>
                                            </div>
                                            {{-- ATTACHMENT --}}
                                            <div class="form-group">
                                                <label class="form-label">Remarks:</label>
                                                <textarea class="form-control form-control-sm" name="remarks" id="txtRemarks"></textarea>
                                            </div>
                                            <div class="form-group text-center">
                                                {{-- <label class="form-label">ILQCM Link:</label> --}}
                                                {{-- <a href="{{ route('ilqcm') }}" target="_blank"> --}}
                                                <a href="http://rapidx/ilqcm/dashboard" target="_blank">
                                                {{-- <a href="http://rapidx/cash_advance/" target="_blank"> --}}
                                                    <button type="button" class="btn btn-primary" id="btnilqcmlink">
                                                        <i class="fa-solid fa-pen"></i> Update In-Line QC Monitoring
                                                    </button>
                                                </a>
                                                <i class="fa-solid fa-circle-info fa-lg" data-bs-toggle="tooltip" data-bs-html="true" title="Update In-Line QC Monitoring Thru our ILQCM System in RapidX"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="frmSaveBtn"><i
                                    class="fa fa-check"></i> Save</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->

    @endsection

    @section('js_content')
        <script type="text/javascript">
            var prodData;
            $(document).ready(function(){
                let frmIPQCInspectionData = $('#formIPQCInspectionData');

                $( '.select2bs5' ).select2( {
                    theme: 'bootstrap-5'
                } );

                // NEW CODE CLARK 02042024
                GetDeviceNameFromSecondMolding($("#txtSelectSecondMoldingDevice"));

                let dt2ndMoldingIpqcInspPending = $("#tbl2ndMoldingIpqcInspPending").DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "view_second_molding_ipqc_data",
                        data: function(param){
                        param.device_id =  $("#txtSelectSecondMoldingDevice").val();
                        param.ipqc_status =  [0,1,2,5]; //Status Pending, Updated (A) or (B), For Re-inspection
                        param.second_molding_status = [0]; //First Molding Status : For IPQC
                        param.process_category = 2; //Process Category : Second Molding
                        }
                    },
                    fixedHeader: true,
                    "order":[[7, 'asc']],
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "ipqc_status" },
                        { "data" : "first_molding_created_at" },
                        { "data" : "pmi_po_number" },
                        { "data" : "production_lot" },
                        { "data" : "ipqc_judgement" },
                        // { "data" : "qc_samples" }, COMMENT FOR NOW 02042024 waiting data
                        { "data" : "po_quantity" },
                        { "data" : "ipqc_inspected_date" },
                    ],
                });

                let dt2ndMoldingIpqcInspCompleted = $("#tbl2ndMoldingIpqcInspCompleted").DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "view_second_molding_ipqc_data",
                        data: function(param){
                        param.device_id =  $("#txtSelectSecondMoldingDevice").val();
                        param.ipqc_status = [3]; //Status 3 = Submitted: Judgement - Accepted
                        param.second_molding_status = [1, 3]; //First Molding Status : For Mass Prod, Done
                        param.process_category = 2; //Process Category : Second Molding
                        }
                    },
                    fixedHeader: true,
                    "order":[[7, 'desc']],
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "ipqc_status" },
                        { "data" : "first_molding_created_at" },
                        { "data" : "pmi_po_number" },
                        { "data" : "production_lot" },
                        { "data" : "ipqc_judgement" },
                        // { "data" : "qc_samples" }, COMMENT FOR NOW 02042024 waiting data
                        { "data" : "po_quantity" },
                        { "data" : "ipqc_inspected_date" },
                    ],
                });

                let dt2ndMoldingIpqcInspResetup = $("#tbl2ndMoldingIpqcInspResetup").DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "view_second_molding_ipqc_data",
                        data: function(param){
                        param.device_id =  $("#txtSelectSecondMoldingDevice").val();
                        param.ipqc_status = [4]; //Status 4 = Submitted: Judgement - Rejected
                        param.second_molding_status = [2]; //First Molding Status : For Resetup
                        param.process_category = 2; //Process Category : Second Molding
                        }
                    },
                    fixedHeader: true,
                    "order":[[7, 'desc']],
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "ipqc_status" },
                        { "data" : "first_molding_created_at" },
                        { "data" : "pmi_po_number" },
                        { "data" : "production_lot" },
                        { "data" : "ipqc_judgement" },
                        // { "data" : "qc_samples" }, COMMENT FOR NOW 02042024 waiting data
                        { "data" : "po_quantity" },
                        { "data" : "ipqc_inspected_date" },
                    ],
                });

                function GetDeviceNameFromSecondMolding(cboElement){
                    let result = '<option value="" disabled selected>-- Select Device Name --</option>';

                    $.ajax({
                            type: "get",
                            url: "get_device_from_second_molding",
                            // data: {
                            //     "stamping_cat" : 1
                            // },
                            dataType: "json",
                            beforeSend: function() {
                                result = '<option value="0" disabled selected> -- Loading -- </option>';
                                cboElement.html(result);
                            },
                            success: function(response) {
                                let fMoldingDevice = response['second_molding_devices'];
                                if (fMoldingDevice.length > 0) {
                                        result = '<option value="" disabled selected>-- Select Device Name --</option>';
                                    for(let index = 0; index < fMoldingDevice.length; index++) {
                                        result += '<option value="' + fMoldingDevice[index].device_name + '">'+fMoldingDevice[index].device_name+'</option>';
                                    }
                                } else {
                                    result = '<option value="0" selected disabled> -- No record found -- </option>';
                                }
                                cboElement.html(result);
                            },
                            error: function(data, xhr, status) {
                                result = '<option value="0" selected disabled> -- Reload Again -- </option>';
                                cboElement.html(result);
                                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                            }
                    });
                }
                // DITO KANA CLARK
                $('#txtSelectSecondMoldingDevice').on('change', function(e){
                        let searchDeviceIdFrmSecondMolding = $('#txtSelectSecondMoldingDevice').val();
                        $.ajax({
                            type: "get",
                            url: "get_second_molding_data",
                            data: {
                                "device_id" : searchDeviceIdFrmSecondMolding
                            },
                            dataType: "json",
                            success: function (response) {
                                const sMoldingData = response['second_molding_data'];

                                if(searchDeviceIdFrmSecondMolding == 'CN171P-02#IN-VE'){
                                    const ContactNameOne = response['second_molding_data'][0].contact_name_lot_number_one;
                                    const ContactNameSecond = response['second_molding_data'][0].contact_name_lot_number_second;
                                    let ContactNames = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                    $('#txtSearchMaterialName').val(ContactNames);
                                    console.log(ContactNames);
                                }else if(searchDeviceIdFrmSecondMolding == 'CN171S-07#IN-VE'){
                                    const fMoldingContactLotEight = response['second_molding_data'][0].fmolding_lot_eight_id.first_molding_device.contact_name;
                                    const fMoldingContactLotNine = response['second_molding_data'][0].fmolding_lot_nine_id.first_molding_device.contact_name;
                                    const fMoldingContactLotTen = response['second_molding_data'][0].fmolding_lot_ten_id.first_molding_device.contact_name;
                                    let fMoldingContactLots = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                    $('#txtSearchMaterialName').val(fMoldingContactLots);
                                    console.log(fMoldingContactLots);
                                }

                                $('#txtSearchPartCode').val(sMoldingData[0].parts_code);
                                $('#txtSelectSecondMoldingDevice').val(searchDeviceIdFrmSecondMolding);
                                // console.log('id', sMoldingData[0].device_id.id);

                                let mat_name = sMoldingData[0].device_name;
                                    mat_name = mat_name.replace(/ /g,'');

                                GetBDrawingFromACDCS(mat_name, 'B Drawing', $("#txtSelectDocNoBDrawing"));
                                GetInspStandardFromACDCS(mat_name, 'Inspection Standard', $("#txtSelectDocNoInspStandard"));
                                GetUDFromACDCS(mat_name, 'Urgent Direction', $("#txtSelectDocNoUD"));

                                dt2ndMoldingIpqcInspPending.draw();
                                dt2ndMoldingIpqcInspCompleted.draw();
                                dt2ndMoldingIpqcInspResetup.draw();
                            }
                        });
                    // }
                });

                $('input[name="keep_sample"]').click('click', function(e){
                    if(frmIPQCInspectionData.find('#txtKeepSample1').prop('checked')){
                        frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', false);
                    }else if(frmIPQCInspectionData.find('#txtKeepSample2').prop('checked')){
                        frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', false);
                    }else{
                        frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', true);
                    }
                });

                $(document).on('click', '.btnUpdateIPQCData',function(e){
                    // console.log('view');
                    e.preventDefault();
                    let ipqc_id = $(this).attr('ipqc_data-id');
                    let second_molding_id = $(this).attr('second_molding_data-id');

                    $.ajax({
                        url: "get_second_molding_data",
                        type: "get",
                        data: {
                            ipqc_id: ipqc_id,
                            second_molding_id: second_molding_id
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $('#formIPQCInspectionData')[0].reset();
                        },
                        success: function(response){
                            $('#formIPQCInspectionData input[name="_token"]').val('{{ csrf_token() }}');
                            let second_molding_data = response['second_molding_data'];

                            frmIPQCInspectionData.find('#txtIpqcId').val(ipqc_id);
                            frmIPQCInspectionData.find('#txtFirstMoldingId').val(second_molding_id);
                            frmIPQCInspectionData.find('#txtProcessCategory').val(2);// Second Molding : STATIC VALUE

                            frmIPQCInspectionData.find('#txtPoNumber').val(second_molding_data[0].pmi_po_number);
                            frmIPQCInspectionData.find('#txtPartCode').val(second_molding_data[0].parts_code);

                            // frmIPQCInspectionData.find('#txtMaterialName').val(second_molding_data[0].first_molding_device.contact_name);

                            if(second_molding_data[0]['device_name'] == 'CN171P-02#IN-VE'){
                                const ContactNameOne = second_molding_data[0].contact_name_lot_number_one;
                                const ContactNameSecond = second_molding_data[0].contact_name_lot_number_second;
                                let ContactNames = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                $('#txtMaterialName').val(ContactNames);
                                console.log(ContactNames);
                            }else if(second_molding_data[0]['device_name'] == 'CN171S-07#IN-VE'){
                                const fMoldingContactLotEight = second_molding_data[0].fmolding_lot_eight_id.first_molding_device.contact_name;
                                const fMoldingContactLotNine = second_molding_data[0].fmolding_lot_nine_id.first_molding_device.contact_name;
                                const fMoldingContactLotTen = second_molding_data[0].fmolding_lot_ten_id.first_molding_device.contact_name;
                                let fMoldingContactLots = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                $('#txtMaterialName').val(fMoldingContactLots);
                                console.log(fMoldingContactLots);
                            }

                            frmIPQCInspectionData.find('#txtProductionLot').val(second_molding_data[0]['production_lot']);
                            // frmIPQCInspectionData.find('#txtInput').val(second_molding_data[0]['qc_samples']); //CLARK COMMENT FOR NOW

                            //disabled and readonly
                            frmIPQCInspectionData.find("#frmSaveBtn").prop('hidden', false);
                            frmIPQCInspectionData.find("#txtOutput").prop('disabled', false);
                            frmIPQCInspectionData.find("#txtJudgement").prop('disabled', false);
                            frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").prop({hidden:false, disabled:false});
                            frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").prop({hidden:false, disabled:false});
                            frmIPQCInspectionData.find("#txtSelectDocNoUD").prop({hidden:false, disabled:false});
                            frmIPQCInspectionData.find("#btnilqcmlink").prop('disabled', false);
                            frmIPQCInspectionData.find('input[name="keep_sample"]').prop('hidden', false);

                            // frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").prop('disabled', false);
                            // frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").prop('disabled', false);
                            // frmIPQCInspectionData.find("#txtSelectDocNoUD").prop('disabled', false);

                            frmIPQCInspectionData.find("#btnViewBDrawings").prop('disabled', true);
                            frmIPQCInspectionData.find("#btnViewInspStdDrawings").prop('disabled', true);
                            frmIPQCInspectionData.find("#btnViewUdDrawings").prop('disabled', true);

                            if(second_molding_data[0]['ipqc_data'] == 0){ //when second_molding_id && ipqc_id is not existing in MoldingAssyIpqc Table //For Insert to MoldingAssyIpqc Table
                                frmIPQCInspectionData.find('#txtInput').val(5); //STATIC VALUE
                                
                                frmIPQCInspectionData.find("#txtInspectorID").val(second_molding_data[0]['ipqc_inspector_id']);
                                frmIPQCInspectionData.find("#txtInspectorName").val(second_molding_data[0]['ipqc_inspector_name']);
                                frmIPQCInspectionData.find("#btnReuploadTriggerDiv").addClass("d-none");
                                frmIPQCInspectionData.find("#btnPartsDrawingAddRow").addClass("d-none");

                                frmIPQCInspectionData.find("#txtAddFile").removeClass('d-none');
                                frmIPQCInspectionData.find("#txtAddFile").attr('required', true);
                                frmIPQCInspectionData.find("#txtEditUploadedFile").addClass('d-none');
                                frmIPQCInspectionData.find("#download_file").addClass('d-none');

                                frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").prop('required', true);
                                frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").prop('required', true);
                                frmIPQCInspectionData.find("#txtSelectDocNoUD").prop('required', true);

                                if(frmIPQCInspectionData.find('#txtKeepSample1').prop('checked')){
                                    frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', false);
                                }else if(frmIPQCInspectionData.find('#txtKeepSample2').prop('checked')){
                                    frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', false);
                                }else{
                                    frmIPQCInspectionData.find('input[name="keep_sample"]').prop('required', true);
                                }

                            }else{//For Update to MoldingAssyIpqc Table
                                let ipqc_data = second_molding_data[0]['second_molding_ipqc'];
                                console.log('edit ipqc',ipqc_data);

                                frmIPQCInspectionData.find('#txtInput').val(ipqc_data['input']);
                                frmIPQCInspectionData.find('#txtOutput').val(ipqc_data['output']);
                                frmIPQCInspectionData.find('#txtJudgement').val(ipqc_data['judgement']);
                                frmIPQCInspectionData.find('#txtInspectorID').val(ipqc_data['ipqc_insp_name']['id']);
                                frmIPQCInspectionData.find('#txtRemarks').val(ipqc_data['remarks']);

                                if(ipqc_data['keep_sample'] == 1){
                                    frmIPQCInspectionData.find('#txtKeepSample1').prop('checked', true);
                                }else if(ipqc_data['keep_sample'] == 2){
                                    frmIPQCInspectionData.find('#txtKeepSample2').prop('checked', true);
                                }else{
                                    frmIPQCInspectionData.find('input[name="keep_sample"]').prop('checked', false);
                                }

                                frmIPQCInspectionData.find('#txtInspectorName').val(ipqc_data['ipqc_insp_name']['firstname'] +' '+ ipqc_data['ipqc_insp_name']['lastname']);

                                let ng_value = frmIPQCInspectionData.find('#txtInput').val() - frmIPQCInspectionData.find('#txtOutput').val();
                                frmIPQCInspectionData.find('#txtNGQty').val(ng_value);

                                frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").val(ipqc_data['doc_no_b_drawing']).trigger('change');
                                frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").val(ipqc_data['doc_no_insp_standard']).trigger('change');
                                frmIPQCInspectionData.find("#txtSelectDocNoUD").val(ipqc_data['doc_no_urgent_direction']).trigger('change');

                                frmIPQCInspectionData.find('input[name="keep_sample"]').attr('disabled', false);
                                frmIPQCInspectionData.find("#btnReuploadTriggerDiv").removeClass('d-none');
                                frmIPQCInspectionData.find("#btnReuploadTrigger").removeClass('d-none');
                                frmIPQCInspectionData.find("#btnReuploadTrigger").prop('checked', false);
                                frmIPQCInspectionData.find("#btnReuploadTriggerLabel").removeClass('d-none');
                                // }
                                frmIPQCInspectionData.find("#txtEditUploadedFile").removeClass('d-none');
                                frmIPQCInspectionData.find("#txtAddFile").addClass('d-none');
                                frmIPQCInspectionData.find("#txtAddFile").removeAttr('required');
                                frmIPQCInspectionData.find("#txtSelectDocumentNo").removeAttr('required');
                                frmIPQCInspectionData.find("#txtEditUploadedFile").removeAttr('required');
                                frmIPQCInspectionData.find('#txtEditUploadedFile').val(ipqc_data['measdata_attachment']);

                                frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").prop('required', false);
                                frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").prop('required', false);
                                frmIPQCInspectionData.find("#txtSelectDocNoUD").prop('required', false);

                                let download ='<a href="second_molding_download_file/'+ipqc_data['id']+'">';
                                    download +='<button type="button" id="download_file" name="download_file" class="btn btn-primary btn-sm d-none">';
                                    download +=     '<i class="fa-solid fa-file-arrow-down"></i>';
                                    download +=         '&nbsp;';
                                    download +=         'See Attachment';
                                    download +='</button>';
                                    download +='</a>';

                                frmIPQCInspectionData.find('#AttachmentDiv').append(download);
                                frmIPQCInspectionData.find("#download_file").removeClass('d-none');
                            }
                            $('#modalIpqcInspection').modal('show');
                        },
                        error: function(data, xhr, status){
                            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                        }
                    });
                });

                $('#txtSelectDocNoBDrawing').on('change', function() {
                    if($('#txtSelectDocNoBDrawing').val() === null || $('#txtSelectDocNoBDrawing').val() === undefined){
                        console.log('b drawing', 'disabled');
                        $("#btnViewBDrawings").prop('disabled', true);
                    }else{
                        console.log('b drawing', 'enabled');
                        $("#btnViewBDrawings").prop('disabled', false);
                    }
                });

                $('#txtSelectDocNoInspStandard').on('change', function() {
                    if($('#txtSelectDocNoInspStandard').val() === null || $('#txtSelectDocNoInspStandard').val() === undefined){
                        console.log('b drawing', 'disabled');
                        $("#btnViewInspStdDrawings").prop('disabled', true);
                    }else{
                        console.log('b drawing', 'enabled');
                        $("#btnViewInspStdDrawings").prop('disabled', false);
                    }
                });

                $('#txtSelectDocNoUD').on('change', function() {
                    if($('#txtSelectDocNoUD').val() === null || $('#txtSelectDocNoUD').val() === undefined){
                        console.log('b drawing', 'disabled');
                        $("#btnViewUdDrawings").prop('disabled', true);
                    }else{
                        console.log('b drawing', 'enabled');
                        $("#btnViewUdDrawings").prop('disabled', false);
                    }
                });

                ViewDocument($('#txtSelectDocNoBDrawing').val(), $('#BDrawingDiv'), 'btnViewBDrawings');
                ViewDocument($('#txtSelectDocNoInspStandard').val(), $('#InspStandardDiv'), 'btnViewInspStdDrawings');
                ViewDocument($('#txtSelectDocNoUD').val(), $('#UDDiv'), 'btnViewUdDrawings');

                function GetBDrawingFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
                    GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
                };

                function GetInspStandardFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
                    GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
                };

                function GetUDFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
                    GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo);
                };

                $('#btnViewBDrawings').on('click', function(){
                    redirect_to_drawing($('#txtSelectDocNoBDrawing').val());
                });
                $('#btnViewInspStdDrawings').on('click', function(){
                    redirect_to_drawing($('#txtSelectDocNoInspStandard').val());
                });
                $('#btnViewUdDrawings').on('click', function(){
                    redirect_to_drawing($('#txtSelectDocNoUD').val());
                });

                function ViewDocument(document_no, div_id, btn_id){
                    let doc_no ='<button type="button" id="'+btn_id+'" class="btn btn-primary">';
                        doc_no +=     '<i class="fa fa-file" data-bs-toggle="tooltip" data-bs-html="true" title="See Document in ACDCS"></i>';
                        doc_no +='</button>';
                    div_id.append(doc_no);
                }

                function redirect_to_drawing(drawing) {
                    console.log('Drawing No.:',drawing)
                    if( drawing  == 'N/A'){
                        alert('Document No is Not Existing')
                    }
                    else{
                        window.open("http://rapid/ACDCS/prdn_home_pats_ppd?doc_no="+drawing)
                    }
                }

                function GetDocumentNoFromACDCS(doc_title, doc_type, cboElement, IpqcDocumentNo){
                    let result = '<option value="" disabled selected>--Select Document No.--</option>';

                    $.ajax({
                        url: 'get_data_from_acdcs',
                        method: 'get',
                        data: {
                            'doc_title': doc_title,
                            'doc_type': doc_type
                        },
                        dataType: 'json',
                        beforeSend: function() {
                                result = '<option value="0" disabled selected>--Loading--</option>';
                                cboElement.html(result);
                        },
                        success: function(response) {
                            if (response['acdcs_data'].length > 0) {
                                    result = '<option value="" disabled selected>--Select Document No.--</option>';
                                if(response['acdcs_data'][0].doc_type != 'B Drawing'){
                                    result += '<option value="N/A"> N/A </option>';
                                }
                                for (let index = 0; index < response['acdcs_data'].length; index++) {
                                    result += '<option value="' + response['acdcs_data'][index].doc_no + '">' + response['acdcs_data'][index].doc_no + '</option>';
                                }
                            } else {
                                result = '<option value="N/A"> N/A </option>';
                                result += '<option value="0" selected disabled> -- No record found -- </option>';
                            }
                            cboElement.html(result);
                        },
                        error: function(data, xhr, status) {
                            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
                            cboElement.html(result);
                            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                        }
                    });
                }

                $('#txtOutput').keyup(function(e){
                    let ng_value = $('#txtInput').val() - $('#txtOutput').val();
                    $('#txtNGQty').val(ng_value);
                });

                // UPDATE STATUS OF DIESET REQUEST
                $(document).on('click', '.btnSubmitIPQCData', function(e){
                    let ipqc_id = $(this).attr('ipqc_data-id');
                    let second_molding_id = $(this).attr('second_molding_data-id');
                    let ipqc_status = $(this).attr('ipqc_data-status');

                    $("#cnfrmtxtFirstMoldingId").val(second_molding_id);
                    $("#cnfrmtxtIPQCId").val(ipqc_id);
                    $("#cnfrmtxtIPQCStatus").val(ipqc_status);
                    $("#modalConfirmSubmitIPQCInspection").modal('show');
                });

                $("#FrmConfirmSubmitIPQCInspection").submit(function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "update_second_molding_ipqc_inspection_status",
                        method: "post",
                        data: $('#FrmConfirmSubmitIPQCInspection').serialize(),
                        dataType: "json",
                        success: function (response) {
                            let result = response['result'];
                            if (result == 'Successful') {
                                dt2ndMoldingIpqcInspPending.draw();
                                dt2ndMoldingIpqcInspCompleted.draw();
                                dt2ndMoldingIpqcInspResetup.draw();
                                toastr.success('Successful!');
                                $("#modalConfirmSubmitIPQCInspection").modal('hide');
                            }else{
                                toastr.error('Error!, Please Contanct ISS Local 208');
                            }
                        }
                    });
                });

                $('#formIPQCInspectionData').submit(function(e){
                    e.preventDefault();
                    $('#modalScanQRSave').modal('show');
                });

                $(document).on('keyup','#txtScanUserId', function(e){
                    if(e.keyCode == 13){
                        validateUser($(this).val(), [0, 2, 5], function(result){
                            if(result == true){
                                AddIpqcInspection();
                            }
                            else{ // Error Handler
                                toastr.error('User not authorize!');
                            }
                        });
                        $(this).val('');
                    }
                });

                function AddIpqcInspection(){
                    let formData = new FormData($('#formIPQCInspectionData')[0]);
                    console.log('formdata', formData);
                    $.ajax({
                        url: "add_second_molding_ipqc_inspection",
                        method: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        beforeSend: function(){
                        },
                        success: function (response) {
                            let result = response['result'];
                            if (result == 'Insert Successful' || result == 'Update Successful') {
                                toastr.success('Successful!');
                                $('#modalIpqcInspection').modal('hide');
                                $('#modalScanQRSave').modal('hide');
                                dt2ndMoldingIpqcInspPending.draw();
                                dt2ndMoldingIpqcInspCompleted.draw();
                                dt2ndMoldingIpqcInspResetup.draw();
                            }
                            else if(result == 'Duplicate'){
                                toastr.error('Request Already Submitted!');
                            }
                            else if(result == 'Session Expired') {
                                toastr.error('Session Expired!, Please Log-in again');
                            }else if(result == 'Error'){
                                toastr.error('Error!, Please Contanct ISS Local 208');
                            }
                        },
                        error: function(data, xhr, status){
                            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                        }
                    });
                };
                // NEW CODE CLARK 02042024 END

                // btnViewIPQCData
                $(document).on('click', '.btnViewIPQCData',function(e){
                    e.preventDefault();
                    let ipqc_id = $(this).attr('ipqc_data-id');
                    let second_molding_id = $(this).attr('second_molding_data-id');
                    // frmIPQCInspectionData
                    $.ajax({
                        url: "get_second_molding_data",
                        type: "get",
                        data: {
                            ipqc_id: ipqc_id,
                            second_molding_id: second_molding_id
                        },
                        dataType: "json",
                        beforeSend: function(){
                            $('#formIPQCInspectionData')[0].reset();
                        },
                        success: function(response){
                            $('#formIPQCInspectionData input[name="_token"]').val('{{ csrf_token() }}');
                            let second_molding_data = response['second_molding_data'];
                            let ipqc_data = response['second_molding_data'][0]['second_molding_ipqc'];

                            frmIPQCInspectionData.find('#txtIpqcId').val(ipqc_id);
                            frmIPQCInspectionData.find('#txtFirstMoldingId').val(second_molding_id);
                            frmIPQCInspectionData.find('#txtPoNumber').val(second_molding_data[0]['pmi_po_number']);
                            frmIPQCInspectionData.find('#txtPartCode').val(second_molding_data[0]['parts_code']);

                            if(second_molding_data[0]['device_name'] == 'CN171P-02#IN-VE'){
                                const ContactNameOne = second_molding_data[0].contact_name_lot_number_one;
                                const ContactNameSecond = second_molding_data[0].contact_name_lot_number_second;
                                let ContactNames = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                $('#txtMaterialName').val(ContactNames);
                                console.log(ContactNames);
                            }else if(second_molding_data[0]['device_name'] == 'CN171S-07#IN-VE'){
                                const fMoldingContactLotEight = second_molding_data[0].fmolding_lot_eight_id.first_molding_device.contact_name;
                                const fMoldingContactLotNine = second_molding_data[0].fmolding_lot_nine_id.first_molding_device.contact_name;
                                const fMoldingContactLotTen = second_molding_data[0].fmolding_lot_ten_id.first_molding_device.contact_name;
                                let fMoldingContactLots = fMoldingContactLotEight +','+ fMoldingContactLotNine +','+ fMoldingContactLotTen;
                                $('#txtMaterialName').val(fMoldingContactLots);
                                console.log(fMoldingContactLots);
                            }
                            // frmIPQCInspectionData.find('#txtMaterialName').val(second_molding_data[0].first_molding_device.contact_name);

                            frmIPQCInspectionData.find('#txtProductionLot').val(second_molding_data[0]['production_lot']);

                            frmIPQCInspectionData.find('#txtInput').val(ipqc_data['input']);
                            frmIPQCInspectionData.find('#txtOutput').val(ipqc_data['output']);
                            frmIPQCInspectionData.find('#txtJudgement').val(ipqc_data['judgement']);
                            frmIPQCInspectionData.find('#txtInspectorID').val(ipqc_data['ipqc_insp_name']['id']);
                            frmIPQCInspectionData.find('#txtInspectorName').val(ipqc_data['ipqc_insp_name']['firstname'] +' '+ ipqc_data['ipqc_insp_name']['lastname']);

                            if(ipqc_data['keep_sample'] == 1){
                                frmIPQCInspectionData.find('#txtKeepSample1').prop('checked', true);
                            }else if(ipqc_data['keep_sample'] == 2){
                                frmIPQCInspectionData.find('#txtKeepSample2').prop('checked', true);
                            }else{
                                frmIPQCInspectionData.find('input[name="keep_sample"]').prop('checked', false);
                            }

                            let ng_value = frmIPQCInspectionData.find('#txtInput').val() - frmIPQCInspectionData.find('#txtOutput').val();
                            frmIPQCInspectionData.find('#txtNGQty').val(ng_value);

                            let mat_name = second_molding_data[0].device_name;
                                mat_name = mat_name.replace(/ /g,'');

                            frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").val(ipqc_data['doc_no_b_drawing']).trigger('change');
                            frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").val(ipqc_data['doc_no_insp_standard']).trigger('change');
                            frmIPQCInspectionData.find("#txtSelectDocNoUD").val(ipqc_data['doc_no_urgent_direction']).trigger('change');

                            //disabled and readonly
                            frmIPQCInspectionData.find("#frmSaveBtn").prop('hidden', true);
                            frmIPQCInspectionData.find("#txtOutput").prop('disabled', true);
                            frmIPQCInspectionData.find("#txtJudgement").prop('disabled', true);
                            frmIPQCInspectionData.find("#txtSelectDocNoBDrawing").prop('disabled', true);
                            frmIPQCInspectionData.find("#txtSelectDocNoInspStandard").prop('disabled', true);
                            frmIPQCInspectionData.find("#txtSelectDocNoUD").prop('disabled', true);
                            frmIPQCInspectionData.find("#btnilqcmlink").prop('disabled', true);
                            frmIPQCInspectionData.find('input[name="keep_sample"]').attr('disabled', true);

                            frmIPQCInspectionData.find("#txtEditUploadedFile").removeClass('d-none');
                            frmIPQCInspectionData.find("#txtAddFile").addClass('d-none');
                            frmIPQCInspectionData.find("#txtAddFile").removeAttr('required');
                            frmIPQCInspectionData.find('#txtEditUploadedFile').val(ipqc_data['measdata_attachment']);

                            let download ='<a href="second_molding_download_file/'+ipqc_data['id']+'">';
                                download +='<button type="button" id="download_file" name="download_file" class="btn btn-primary btn-sm d-none">';
                                download +=     '<i class="fa-solid fa-file-arrow-down"></i>';
                                download +=         '&nbsp;';
                                download +=         'See Attachment';
                                download +='</button>';
                                download +='</a>';

                            frmIPQCInspectionData.find('#AttachmentDiv').append(download);
                            frmIPQCInspectionData.find("#download_file").removeClass('d-none');
                            $('#modalIpqcInspection').modal('show');
                        },
                        error: function(data, xhr, status){
                            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
                        }

                    });
                });

                // ================================= RE-UPLOAD FILE =================================
                $('#btnReuploadTrigger').on('click', function() {
                    $('#btnReuploadTrigger').attr('checked', 'checked');
                    if($(this).is(":checked")){
                        $("#txtAddFile").removeClass('d-none');
                        $("#txtAddFile").attr('required', true);
                        $("#txtEditUploadedFile").addClass('d-none');
                        $("#download_file").addClass('d-none');
                    }
                    else{
                        $("#txtAddFile").addClass('d-none');
                        $("#txtAddFile").removeAttr('required');
                        $("#txtAddFile").val('');
                        $("#txtEditUploadedFile").removeClass('d-none');
                        $("#download_file").removeClass('d-none');
                    }
                });
            });
        </script>
    @endsection
@endauth
