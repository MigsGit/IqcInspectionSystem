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

    @section('title', 'Material Process')

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
                            <h1>First Molding</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">First Molding</li>
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
                            <!-- general form elements -->
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title">First Molding</h3>
                                </div>
                                <!-- Start Page Content -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                          <div class="card card-primary">
                                            <div class="card-body">
                                              <div class="row">
                                                <div class="col-sm-3">
                                                  <label>Device Name</label>
                                                  <div class="input-group">
                                                    <select class="form-select form-control" id="global_device_name" name="global_device_name" >
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="col-sm-3 d-none">
                                                  <label>Input Device Name</label>
                                                    <input type="text" class="form-control" id="global_input_device_name" name="global_input_device_name" readonly>
                                                </div>
                                                <div class="col-sm-3">
                                                  <label>Contact Name</label>
                                                    <input type="text" class="form-control" id="global_contact_name" name="global_contact_name" readonly>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    <div style="float: right;">
                                        {{-- @if(Auth::user()->user_level_id == 1)
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                                        @else
                                        @if(Auth::user()->position == 7 || Auth::user()->position == 8)
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                                        @endif
                                        @endif --}}
                                        <button class="btn btn-primary" id="btnAddFirstMolding" disabled>
                                            <i class="fa-solid fa-plus"></i> Add</button>
                                    </div> <br><br>
                                    <div class="table-responsive">
                                        <!-- style="max-height: 600px; overflow-y: auto;" -->
                                        <table id="tblFirstMoldingDetails" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>Status</th>
                                                    <th>PO No.</th>
                                                    <th>Device Name</th>
                                                    <th>Contact Name</th>
                                                    <th>Contact Lot No.</th>
                                                    <th>Production Lot No.</th>
                                                    <th>Remarks</th>
                                                    <th>Created At</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- MODAL FOR PRINTING  --}}
        <div class="modal fade" id="modalFirstMoldingPrintQr">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"> Production - QR Code</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- PO 1 -->
                            <div class="col-sm-12">
                                <center><img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->errorCorrection('H')->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;"><br></center>
                                <label id="img_barcode_PO_text"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="btnFirstMoldingPrintQrCode" class="btn btn-primary btn-sm"><i class="fa fa-print fa-xs"></i> Print</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

    @include('component.modal')
    <!--- Go to component/modal.blade.php -->
    <!--- modalSaveIqcInspection formSaveIqcInspection -->
    <!--- modalFirstMolding formFirstMolding -->
    <!--- modalFirstMoldingStation formFirstMoldingStation -->

    @endsection

    @section('js_content')
        <script>
            $(document).ready(function () {

                getFirstModlingDevices();

                // const getFirstMoldingOperationNames = function (){
                //     $.ajax({
                //         type: "GET",
                //         url: "get_operation_ames",
                //         data: "data",
                //         dataType: "json",
                //         success: function (response) {

                //         }
                //     });
                // }


                $('#modalFirstMolding').on('hidden.bs.modal', function() {
                    formModal.firstMolding.find('#first_molding_id').val('');
                    formModal.firstMoldingStation.find('#first_molding_id').val('');
                    formModal.firstMolding.find('#contact_lot_number').val('');
                    // formModal.firstMolding.find('#production_lot').val('');
                    formModal.firstMolding.find('#remarks').val('');
                    formModal.firstMolding.find('#dieset_no').val('');
                    formModal.firstMolding.find('#production_lot_extension').val('');
                    formModal.firstMolding.find('#pmi_po_no').val('');
                    formModal.firstMolding.find('#machine_no').val('');
                    formModal.firstMolding.find('#pmi_po_no').val('');
                    formModal.firstMolding.find('#po_no').val('');
                    formModal.firstMolding.find('#item_code').val('');
                    formModal.firstMolding.find('#item_name').val('');
                    formModal.firstMolding.find('#po_qty').val('');
                    formModal.firstMolding.find('#material_yield').val('');
                    // formModal.firstMolding.find('#drawing_no').val('');
                    // formModal.firstMolding.find('#revision_no').val('');
                    formModal.firstMolding.find('#dieset_no').val('');
                    formModal.firstMolding.find('#required_output').val('');
                    formModal.firstMolding.find('[type="number"]').val(0)
                    formModal.firstMolding.find('.form-control').removeClass('is-valid')
                    formModal.firstMolding.find('.form-control').removeClass('is-invalid');
                    formModal.firstMolding.find('.form-control').attr('title', '');
                    $("#tblFirstMoldingMaterial tbody").empty();
                })

                $('#modalFirstMoldingStation').on('hidden.bs.modal', function() {
                    formModal.firstMoldingStation.find('#first_molding_detail_id').val('');
                    formModal.firstMoldingStation.find('#operator_name').val('');
                    formModal.firstMoldingStation.find('#remarks').val('');
                    // formModal.firstMoldingStation.find('#station_yield').val('0%');
                    // formModal.firstMoldingStation.find('[type="number"]').val(0);
                    formModal.firstMoldingStation.find('.form-control').removeClass('is-valid')
                    formModal.firstMoldingStation.find('.form-control').removeClass('is-invalid');
                    formModal.firstMoldingStation.find('.form-control').attr('title', '');
                    resetTotalNgQty();
                    $("#tableFirstMoldingStationMOD tbody").empty();
                })

                $('#modalFirstMolding').on('shown.bs.modal', function () {
                });

                $('#mdlScanQrCodeFirstMolding').on('shown.bs.modal', function () {
                    $('#txtScanQrCodeFirstMolding').focus();
                    const mdlScanQrCode = document.querySelector("#mdlScanQrCodeFirstMolding");
                    const inptQrCode = document.querySelector("#txtScanQrCodeFirstMolding");
                    let focus = false;

                    mdlScanQrCode.addEventListener("mouseover", () => {
                        if (inptQrCode === document.activeElement) {
                            focus = true;
                        } else {
                            focus = false;
                        }
                    });

                    mdlScanQrCode.addEventListener("click", () => {
                        if (focus) {
                            inptQrCode.focus()
                        }
                    });
                });

                dt.firstMolding = table.FirstMoldingDetails.DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_first_molding_details",
                        data: function (param){
                            param.first_molding_device_id = $("#global_device_name").val();
                        }
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "status" },
                        { "data" : "po_no" },
                        { "data" : "device_name" },
                        { "data" : "contact_name" },
                        { "data" : "contact_lot_number" },
                        { "data" : "prodn_lot_number" },
                        { "data" : "remarks" },
                        { "data" : "created_at" },
                    ]
                });

                dt.firstMoldingStation = table.FirstMoldingStationDetails.DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_first_molding_station_details",
                        data: function (param){
                            param.first_molding_id = formModal.firstMolding.find("#first_molding_id").val();
                        }
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        // { "data" : "status" },
                        { "data" : "stations" },
                        { "data" : "date" },
                        { "data" : "operator_names" },
                        { "data" : "input" },
                        { "data" : "ng_qty" },
                        { "data" : "output" },
                        { "data" : "remarks" },
                        { "data" : "created_at" },
                    ]
                });

                table.FirstMoldingDetails.on('click','#btnEditFirstMolding', editFirstMolding);
                table.FirstMoldingDetails.on('click','#btnViewFirstMolding', editFirstMolding);
                table.FirstMoldingStationDetails.on('click','#btnEditFirstMoldingStation', editFirstMoldingStation);
                table.FirstMoldingStationDetails.on('click','#btnViewFirstMoldingStation', editFirstMoldingStation);

                table.FirstMoldingStationDetails.on('click','#btnDeleteFirstMoldingStation', function (){
                    let first_molding_detail_id = $(this).attr('first-molding-station-id');

                    Swal.fire({
                        // title: "Are you sure?",
                        text: "Are you sure you want to submit this process",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: "delete_first_molding_detail",
                                data: {
                                    "first_molding_detail_id" : first_molding_detail_id,
                                },
                                dataType: "json",
                                success: function (response) {
                                    console.log(response);
                                    if(response['result'] === 1){
                                        $('#modalFirstMoldingStation').modal('hide');
                                        dt.firstMoldingStation.draw();
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Deleted Successfully !",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                },error: function (data, xhr, status){
                                    toastr.error(`Error: ${data.status}`);
                                }
                            });
                        }
                    });

                });

                table.FirstMoldingDetails.on('click', '#btnPrintFirstMolding', function(e){
                    e.preventDefault();
                    let firstMoldingId = $(this).attr('first-molding-id');
                    // $('#hiddenPreview').append(dataToAppend)
                    $.ajax({
                        type: "get",
                        url: "get_first_molding_qr_code",
                        data: {"first_molding_id" : firstMoldingId},
                        dataType: "json",
                        success: function (response) {
                            console.log(response);
                            // response['label_hidden'][0]['id'] = id;
                            // console.log(response['label_hidden']);
                            // for(let x = 0; x < response['label_hidden'].length; x++){
                            //     let dataToAppend = `
                            //     <img src="${response['label_hidden'][x]['img']}" style="max-width: 200px;"></img>
                            //     `;
                            //     $('#hiddenPreview').append(dataToAppend)
                            // }


                            $("#img_barcode_PO").attr('src', response['qr_code']);
                            $("#img_barcode_PO_text").html(response['label']);
                            img_barcode_PO_text_hidden = response['label_hidden'];
                            $('#modalFirstMoldingPrintQr').modal('show');
                        }
                    });

                });

                $('#btnFirstMoldingPrintQrCode').on('click', function(){
                    popup = window.open();
                    let content = '';
                    content += '<html>';
                    content += '<head>';
                    content += '<title></title>';
                    content += '<style type="text/css">';
                    content += '@media print { .pagebreak { page-break-before: always; } }';
                    content += '</style>';
                    content += '</head>';
                    content += '<body>';
                    // for (let i = 0; i < img_barcode_PO_text_hidden.length; i++) {
                        content += '<table style="margin-left: -5px; margin-top: 18px;">';
                            content += '<tr style="width: 290px;">';
                                content += '<td style="vertical-align: bottom;">';
                                    content += '<img src="' + img_barcode_PO_text_hidden[0]['img'] + '" style="min-width: 75px; max-width: 75px;">';
                                content += '</td>';
                                content += '<td style="font-size: 10px; font-family: Calibri;">' + img_barcode_PO_text_hidden[0]['text'] + '</td>';
                            content += '</tr>';
                        content += '</table>';
                        content += '<br>';
                        // if( i < img_barcode_PO_text_hidden.length-1 ){
                        //     content += '<div class="pagebreak"> </div>';
                        // }
                    // }
                    content += '</body>';
                    content += '</html>';
                    popup.document.write(content);

                    popup.focus(); //required for IE
                    popup.print();

                    /*
                        * this event will trigger after closing the tab of printing
                    */
                    popup.addEventListener("beforeunload", function (e) {
                        changePrintCount(img_barcode_PO_text_hidden[0]['id']);
                    });

                    popup.close();

                    });



                    $('#btnAddFirstMolding').click(function (e) {
                    e.preventDefault();
                    let device_name = $('#global_input_device_name').val();
                    dt.firstMoldingStation.draw()
                    $('#modalFirstMolding').modal('show');
                    $('#btnFirstMoldingStation').prop('disabled',true);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                    $('#btnRuncardDetails').removeClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);

                    formModal.firstMolding.find('#material_yield').val('0%');
                    formModal.firstMolding.find('[type="number"]').val(0);
                    arr.Ctr = 0;
                    let rowFirstMoldingMaterial = `
                        <tr>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-dark" id="btnScanQrFirstMoldingVirginMaterial" btn-counter = "${arr.Ctr}"><i class="fa fa-qrcode w-100"></i></button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="virgin_material_${arr.Ctr}" input-counter="${arr.Ctr}" name="virgin_material[]" required min=1 step="0.01">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="0" type="number" class="form-control form-control-sm inputVirginQty" id="virgin_qty_0" name="virgin_qty[]" required min=1 step="0.01">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="recycle_material_0" name="recycle_material[]" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="0" type="number" class="form-control form-control-sm" id="recycle_qty_0" name="recycle_qty[]" required>
                                </div>
                            </td>
                            <td>
                                <center><button class="btn btn-danger buttonRemoveMaterial" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tblFirstMoldingMaterial tbody").append(rowFirstMoldingMaterial);

                    getDiesetDetailsByDeviceName(device_name);
                });

                $('#btnFirstMoldingStation').click(function (e) {
                    e.preventDefault();
                    let elementId = formModal.firstMoldingStation.find('#operator_name');

                    $('#buttonFirstMoldingStation').prop('disabled',false);
                    $('#buttonAddFirstMoldingModeOfDefect').prop('disabled',false);

                    formModal.firstMoldingStation.find('#first_molding_id').val( formModal.firstMolding.find('#first_molding_id').val() );
                    formModal.firstMoldingStation.find('[type="number"]').val(0)
                    formModal.firstMoldingStation.find('#station_yield').val('0%');
                    getFirstMoldingStationLastOuput(formModal.firstMolding.find('#first_molding_id').val());

                    getFirstMoldingOperationNames(elementId);

                    $('#modalFirstMoldingStation').modal('show');

                });

                $('#btnSubmitFirstMoldingStation').click(function (e) {
                    e.preventDefault();
                    Swal.fire({
                        // title: "Are you sure?",
                        text: "Are you sure you want to submit this process",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "GET",
                                url: "first_molding_update_status",
                                data: {
                                    "first_molding_id" : formModal.firstMolding.find("#first_molding_id").val(),
                                },
                                dataType: "json",
                                success: function (response) {
                                    if(response['result'] === 1){
                                        $('#modalFirstMolding').modal('hide');
                                        dt.firstMolding.draw();
                                        Swal.fire({
                                            position: "center",
                                            icon: "success",
                                            title: "Submitted Successfully !",
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                },error: function (data, xhr, status){
                                    toastr.error(`Error: ${data.status}`);
                                }
                            });
                        }
                    });
                });

                $('#tableFirstMoldingStationMOD').on('keyup','.textMODQuantity', function (e) {
                    let totalNumberOfMOD = 0;
                    let ngQty = formModal.firstMoldingStation.find('#ng_qty').val();

                    $('#tableFirstMoldingStationMOD .textMODQuantity').each(function() {
                        if($(this).val() === null || $(this).val() === ""){
                            $("#tableFirstMoldingStationMOD tbody").empty();
                            $("#labelTotalNumberOfNG").text(parseInt(0));
                        }
                        totalNumberOfMOD += parseInt($(this).val());
                    });
                    getValidateTotalNgQty (ngQty,totalNumberOfMOD);
                });

                formModal.firstMolding.find('#pmi_po_no').on('keyup',function (e) {
                    e.preventDefault();
                    let  deviceId = formModal.firstMolding.find('#first_molding_device_id').val();

                    getPmiPoReceivedDetails( $(this).val(),deviceId);

                });

                $('#btnScanQrFirstMolding').click(function (e) {
                    $('#mdlScanQrCodeFirstMolding').modal('show');
                    $('#mdlScanQrCodeFirstMolding').on('shown.bs.modal');
                });

                $('#btnAddFirstMoldingMaterial').click(function (e) {
                    e.preventDefault();
                    arr.Ctr ++;
                    let rowFirstMoldingMaterial = `
                        <tr>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend">
                                        <button type="button" class="btn btn-dark" id="btnScanQrFirstMoldingVirginMaterial_${arr.Ctr}" btn-counter = "${arr.Ctr}"><i class="fa fa-qrcode w-100"></i></button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="virgin_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_material[]" required min=1 step="0.01">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="0" type="number" class="form-control form-control-sm inputVirginQty" id="virgin_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_qty[]" required min=1 step="0.01">
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="recycle_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_material[]" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="0" type="number" class="form-control form-control-sm" id="recycle_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_qty[]" required>
                                </div>
                            </td>
                            <td>
                                <center><button class="btn btn-danger buttonRemoveMaterial" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tblFirstMoldingMaterial tbody").append(rowFirstMoldingMaterial);
                });

                /**
                 * Add Mode Of Defect
                */
                $("#buttonAddFirstMoldingModeOfDefect").click(function(){
                    let totalNumberOfMOD = 0;
                    let ngQty = formModal.firstMoldingStation.find('#ng_qty').val();
                    let rowModeOfDefect = `
                        <tr>
                            <td>
                                <select class="form-control select2bs4 selectMOD" name="mod_id[]">
                                    <option value="0">N/A</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control textMODQuantity" name="mod_quantity[]" value="1" min="1">
                            </td>
                            <td>
                                <center><button class="btn btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    $("#tableFirstMoldingStationMOD tbody").append(rowModeOfDefect);
                    getModeOfDefectForFirstMolding($("#tableFirstMoldingStationMOD tr:last").find('.selectMOD'));

                    $('.select2bs4').each(function () {
                        $(this).select2({
                            theme: 'bootstrap-5',
                            dropdownParent: $(this).parent(),
                        });
                    });
                    $(this).on('select2:open', function(e) {
                        document.querySelector('input.select2-search__field').focus();
                    });

                    $('#tableFirstMoldingStationMOD .textMODQuantity').each(function() {
                        if($(this).val() === null || $(this).val() === ""){
                            $("#tableFirstMoldingStationMOD tbody").empty();
                            $("#labelTotalNumberOfNG").text(parseInt(0));
                        }
                        totalNumberOfMOD += parseInt($(this).val());
                    });
                    getValidateTotalNgQty (ngQty,totalNumberOfMOD);

                });

                $("#tableFirstMoldingStationMOD").on('click', '.buttonRemoveMOD', function(){
                    let totalNumberOfMOD = 0;
                    let ngQty = formModal.firstMoldingStation.find('#ng_qty').val();

                    $(this).closest ('tr').remove();

                    $('#tableFirstMoldingStationMOD .textMODQuantity').each(function() {
                        if($(this).val() === null || $(this).val() === ""){
                            // $("#tableFirstMoldingStationMOD tbody").empty();
                            $("#labelTotalNumberOfNG").text(parseInt(0));
                        }
                        totalNumberOfMOD += parseInt($(this).val());
                    });
                    getValidateTotalNgQty (ngQty,totalNumberOfMOD);
                });

                $('#global_device_name').change(function (e) {
                    e.preventDefault();
                    $.ajax({
                        type: "GET",
                        url: "get_first_molding_devices_by_id",
                        data: {"first_molding_device_id" : $(this).val()},
                        dataType: "json",
                        success: function (response) {
                            let first_molding_device_id = response[0].id
                            let contact_name = response[0].contact_name
                            let device_name = response[0].device_name

                            $('#btnAddFirstMolding').prop('disabled',false);
                            $('#global_contact_name').val(contact_name);
                            $('#global_input_device_name').val(device_name);
                            formModal.firstMolding.find('#first_molding_device_id').html(`<option value="${first_molding_device_id}">${device_name}</option>`);
                            formModal.firstMolding.find('#contact_name').val(contact_name);

                            dt.firstMolding.draw();
                            // getDiesetDetailsByDeviceName(device_name);
                            getMachineFromMaterialProcess(formModal.firstMolding.find('#machine_no'),device_name);
                            getStation (formModal.firstMoldingStation.find('#station'),device_name)
                            //nmodify
                        }
                    });
                });

                $('#txtScanQrCodeFirstMolding').on('keyup', function(e){
                    if(e.keyCode == 13){
                        let scanFirstMoldingContactLotNo = $(this).val()
                        console.log((scanFirstMoldingContactLotNo));
                        // let explodedMat = scanFirstMoldingContactLotNo.split(' $|| ');
                        // $('#txtMaterialLot_0').val(explodedMat[0]);
                        // $('#txtMaterialLotQty').val(explodedMat[1]);

                        // // console.log(explodedMat);
                        validateScanFirstMoldingContactLotNum(scanFirstMoldingContactLotNo);
                        $(this).val('');
                        $('#mdlScanQrCodeFirstMolding').modal('hide');
                    }
                });

                formModal.firstMoldingStation.find('#input').keyup(function (e) {
                    totalOutput($(this).val(),formModal.firstMoldingStation.find("#ng_qty").val());
                    totalStationYield($(this).val(),formModal.firstMoldingStation.find("#output").val());
                });

                formModal.firstMoldingStation.find('#ng_qty').keyup(function (e) {
                    let ngQty = $(this).val();
                    let totalNumberOfMOD = 0;
                    let totalShipmentOutput = formModal.firstMolding.find('#total_machine_output').val();
                    totalOutput(formModal.firstMoldingStation.find("#input").val(),ngQty);
                    totalStationYield(formModal.firstMoldingStation.find("#input").val(),formModal.firstMoldingStation.find("#output").val());

                    if(parseInt(ngQty) > 0){
                        $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', false);
                    }
                    else{
                        $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', true);
                    }

                    if(parseInt(ngQty) === parseInt($('#labelTotalNumberOfNG').text())){
                        $('#labelTotalNumberOfNG').css({color: 'green'})
                        $('#labelIsTally').css({color: 'green'})
                        $('#labelIsTally').addClass('fa-thumbs-up')
                        $('#labelIsTally').removeClass('fa-thumbs-down')
                        $("#buttonFirstMoldingStation").prop('disabled', false);
                        $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', true);
                    }else{
                        $('#labelTotalNumberOfNG').css({color: 'red'})
                        $('#labelIsTally').css({color: 'red'})
                        $('#labelIsTally').addClass('fa-thumbs-down')
                        $('#labelIsTally').removeClass('fa-thumbs-up')
                        $("#buttonFirstMoldingStation").prop('disabled', true);
                        $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', false);
                    }

                });

                /* */
                formModal.firstMolding.find('.sumTotalMachineOutput').keyup(function (e) {
                    let arr = document.getElementsByClassName('sumTotalMachineOutput');
                    inputTotalMachineOuput=0;
                    for(let i=0;i<arr.length;i++){
                        if(parseFloat(arr[i].value))
                            inputTotalMachineOuput += parseFloat(arr[i].value);
                    }
                    formModal.firstMolding.find('#total_machine_output').val(inputTotalMachineOuput);
                });

                // formModal.firstMolding.find('#total_machine_output').keyup(function (e) {
                //     let inputTotalMachineOuput = $(this).val();
                //     console.log(inputTotalMachineOuput);
                //     let differenceOfTotalShipmentOutput = parseFloat(inputTotalMachineOuput) - total;
                //     let ngCount = formModal.firstMolding.find("#ng_count").val();


                //     if( inputTotalMachineOuput == '' | inputTotalMachineOuput < 0 ){
                //         formModal.firstMolding.find('#shipment_output').val(0);
                //         formModal.firstMolding.find("#material_yield").val('0%');
                //         return;
                //     }

                //     formModal.firstMolding.find('#shipment_output').val(differenceOfTotalShipmentOutput);
                //     calculateTotalMaterialYield(inputTotalMachineOuput,formModal.firstMolding.find('#shipment_output').val());
                // });

                formModal.firstMolding.find('.inputVirginQty').keyup(function (e) {
                    alert('inputVirginQty')

                });

                formModal.firstMolding.submit(function (e) {
                    e.preventDefault();
                    saveFirstMolding();
                });

                formModal.firstMoldingStation.submit(function (e) {
                    e.preventDefault();
                    savefirstMoldingStation();
                });



                $("#tblFirstMoldingMaterial").on('click', '.buttonRemoveMaterial', function(){
                    $(this).closest ('tr').remove();
                    arr.Ctr --;
                });

                $('#mdlScanQrCodeFirstMoldingMaterial').click(function (e) {
                    e.preventDefault();
                    // mdlScanQrCodeFirstMoldingMaterial
                });






            });
        </script>
    @endsection
@endauth
