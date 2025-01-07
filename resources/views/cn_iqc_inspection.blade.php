
@php $layout = 'layouts.admin_layout'; @endphp
{{-- @auth --}}
    @extends($layout)

    @section('title', 'CN IQC Inspection')

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
                            <h1>CN IQC Inspection</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">CN IQC Inspection</li>
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
                                            <a class="nav-link active .menuTab" id="Completed-tab" data-bs-toggle="tab" href="#menu2" role="tab" aria-controls="menu2" aria-selected="false">Rapid PPD WHS Packaging V3</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-5" id="myTabContent">
                                        <div class="row justify-content-end">
                                            <div class="col-sm-2">
                                                <label class="form-label">Lot Number</label>
                                                <div class="input-group mb-3">
                                                    <button class="btn btn-primary" id="btnModalLotNum" el-btn-attr="ppdWhsDatabase"><i class="fa-solid fa-qrcode"></i></button>
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
                                        <div class="tab-pane fade show active" id="menu2" role="tabpanel" aria-labelledby="menu2-tab">
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
                                                                <!-- style="max-height: 600px; overflow-y: auto;" nmodify-->
                                                                <table id="tblIqcCnWhsPackaging" class="table table-sm table-bordered table-striped table-hover"
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
                                                                <table id="tblIqcCnWhsPackagingInspected" class="table table-sm table-bordered table-striped table-hover"
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

        <!--- Modal modalSaveIqcInspection formSaveIqcInspection-->
        <!--- Modal modalLotNum-->
        @include('component.modal')

        <div class="modal fade" id="modalModeOfDefect" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-edit"></i> Mode of Defects Details</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 mt-2">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Lot No.</span>
                                    </div>
                                    <select class="form-control select2bs4" name="mod_lot_no" id="mod_lot_no" style="width: 50%;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Mode of Defect</span>
                                    </div>
                                    <select class="form-control select2bs4" name="mode_of_defect" id="mode_of_defect" style="width: 50%;">
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Quantity</span>
                                    </div>
                                    <input class="form-control" type="number" name="mod_quantity" id="mod_quantity" value="0" min =0>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-sm-3">
                                <button type="button" class="btn btn-sm btn-danger" id="btnRemoveModLotNumber" disabled><i class="fas fa-trash-alt"></i> Remove </a></button>
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-sm btn-primary" id="btnAddModLotNumber"><i class="fas fa-plus"></i>Add</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 mt-3">
                                <table id="tblModeOfDefect" class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Counter</th>
                                            <th>Lot No.</th>
                                            <th>Mode of Defects</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        {{-- <button type="button" class="btn btn-sm btn-primary" id="btnSaveComputation"><i class="fas fa-save"></i> Compute</button> --}}
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    @endsection

    @section('js_content')
        <script type="text/javascript">
            getDropdownDetailsByOptValue('CN',$('#txtCategoryMaterial'),'iqc_category_material_id','46');

            $(document).ready(function () {
                
                tbl = {
                    iqcInspection:'#tblIqcInspection',
                    iqcCnWhsPackaging:'#tblIqcCnWhsPackaging',
                    iqcWhsDetails :'#tblWhsDetails',
                    iqcInspected:'#tblIqcInspected',
                    iqcCnWhsPackagingInspected:'#tblIqcCnWhsPackagingInspected',

                };

                dataTable = {
                    iqcInspection:'', //PPS Database
                    iqcCnWhsPackaging: '',
                    iqcYeuDetails: '',
                    iqcInspected: '',
                    iqcCnWhsPackagingInspected: '',

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

                dataTable.iqcCnWhsPackaging = $(tbl.iqcCnWhsPackaging).DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_cn_whs_packaging", //Rapid PPS WHS Transaction
                        data: function (param){
                            param.lotNum = $('#txtSearchLotNum').val()
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

                // dataTable.iqcCnWhsPackagingInspected = $(tbl.iqcCnWhsPackagingInspected).DataTable({
                //     "processing" : true,
                //     "serverSide" : true,
                //     "ajax" : {
                //         url: "load_cn_iqc_inspection",
                //         data: function (param){
                //             param.lotNum = $('#txtSearchLotNum').val()
                //             // param.categoryMaterial = $('#txtCategoryMaterial').val()
                //         },
                //     },
                //     fixedHeader: true,
                //     "columns":[
                //         { "data" : "rawAction", orderable:false, searchable:false },
                //         { "data" : "rawStatus", orderable:false, searchable:false },
                //         { "data" : "date_inspected" },
                //         { "data" : "time_inspected" },
                //         { "data" : "app_ctrl_no" },
                //         { "data": "supplier" },
                //         // { "data" : "classification" },//
                //         // { "data" : "family" },//
                //         // { "data" : "category" },//
                //         { "data" : "partcode" },
                //         { "data" : "partname" },
                //         { "data" : "lot_no" },
                //         { "data" : "total_lot_qty" },
                //         // { "data" : "aql" }, //
                //         { "data" : "qc_inspector" }, //
                //         { "data" : "created_at" },
                //         { "data" : "updated_at" },
                //     ],
                // });
                // const savePpdIqcInspection = function (){ //amodify
                //     let serialized_data = new FormData(form.iqcInspection[0]);
                //         serialized_data.append('lotNo',arrTableMod.lotNo);
                //         serialized_data.append('modeOfDefects',arrTableMod.modeOfDefects);
                //         serialized_data.append('lotQty',arrTableMod.lotQty);
                //     $.ajax({
                //         type: "POST",
                //         url: "save_ppd_iqc_inspection",
                //         data: serialized_data,
                //         dataType: "json",
                //         cache: false,
                //         contentType: false,
                //         processData: false,
                //         beforeSend: function(){
                //             $('#modal-loading').modal('show');
                //         },
                //         success: function (response) {
                //             $('#modal-loading').modal('hide');
                //             if (response['result'] === 1){
                //                 $('#modalSaveIqcInspection').modal('hide');
                //                 dataTable.iqcInspection.draw();
                //                 dataTable.iqcCnWhsPackagingInspected.draw();
                //                 Swal.fire({
                //                     position: "center",
                //                     icon: "success",
                //                     title: "Your work has been saved",
                //                     showConfirmButton: false,
                //                     timer: 1500
                //                 });
                //                 $('#modalScanQRSave').modal('hide');
                //                 form.iqcInspection[0].reset();
                //             }
                //         },error: function (data, xhr, status){
                //             let errors = data.responseJSON.errors ;
                //             toastr.error(`Saving Failed, Please fill up all required fields`);
                //             $('#modal-loading').modal('hide');
                //             if(data.status === 422){
                //                 errorHandler(errors.whs_transaction_id,form.iqcInspection.find('#whs_transaction_id'));
                //                 errorHandler(errors.iqc_category_material_id,form.iqcInspection.find('#iqc_category_material_id'));
                //                 errorHandler(errors.app_no,form.iqcInspection.find('#app_no'));
                //                 errorHandler(errors.partcode,form.iqcInspection.find('#partcode'));
                //                 errorHandler(errors.partname,form.iqcInspection.find('#partname'));
                //                 errorHandler(errors.supplier,form.iqcInspection.find('#supplier'));
                //                 errorHandler(errors.total_lot_qty,form.iqcInspection.find('#total_lot_qty'));
                //                 errorHandler(errors.accept,form.iqcInspection.find('#accept'));
                //                 errorHandler(errors.family,form.iqcInspection.find('#family'));
                //                 errorHandler(errors.app_no_extension,form.iqcInspection.find('#app_no_extension'));
                //                 errorHandler(errors.die_no,form.iqcInspection.find('#die_no'));
                //                 errorHandler(errors.lot_no,form.iqcInspection.find('#lot_no'));
                //                 errorHandler(errors.classification,form.iqcInspection.find('#classification'));
                //                 errorHandler(errors.type_of_inspection,form.iqcInspection.find('#type_of_inspection'));
                //                 errorHandler(errors.severity_of_inspection,form.iqcInspection.find('#severity_of_inspection'));
                //                 errorHandler(errors.inspection_lvl,form.iqcInspection.find('#inspection_lvl'));
                //                 errorHandler(errors.aql,form.iqcInspection.find('#aql'));
                //                 errorHandler(errors.accept,form.iqcInspection.find('#accept'));
                //                 errorHandler(errors.reject,form.iqcInspection.find('#reject'));
                //                 errorHandler(errors.shift,form.iqcInspection.find('#shift'));
                //                 errorHandler(errors.date_inspected,form.iqcInspection.find('#date_inspected'));
                //                 errorHandler(errors.time_ins_from,form.iqcInspection.find('#time_ins_from'));
                //                 errorHandler(errors.time_ins_to,form.iqcInspection.find('#time_ins_to'));
                //                 errorHandler(errors.inspector,form.iqcInspection.find('#inspector'));
                //                 errorHandler(errors.submission,form.iqcInspection.find('#submission'));
                //                 errorHandler(errors.category,form.iqcInspection.find('#category'));
                //                 errorHandler(errors.sampling_size,form.iqcInspection.find('#sampling_size'));
                //                 errorHandler(errors.lot_inspected,form.iqcInspection.find('#lot_inspected'));
                //                 errorHandler(errors.accepted,form.iqcInspection.find('#accepted'));
                //                 errorHandler(errors.judgement,form.iqcInspection.find('#judgement'));
                //             }else{
                //                 toastr.error(`Error: ${data.status}`);
                //             }
                //         }
                //     });
                // }


                // $(tbl.iqcInspection).on('click','#btnEditIqcInspection', editReceivingDetails);
                // $(tbl.iqcInspected).on('click','#btnEditIqcInspection', function(){
                //     let iqcInspectionId = ($(this).attr('iqc-inspection-id') != undefined) ?  $(this).attr('iqc-inspection-id') : 0;
                //     let iqcCategoryMaterialId = $('#txtCategoryMaterial').val();
                //     getPpdIqcInspectionById (iqcInspectionId,iqcCategoryMaterialId);
                // });

                $(tbl.iqcCnWhsPackaging).on('click','#btnEditIqcInspection', getCnWhsPackagingById);
                // $(tbl.iqcCnWhsPackagingInspected).on('click','#btnEditIqcInspection', editIqcInspected);

                // $('#btnLotNo').click(function (e) {
                //     e.preventDefault();
                //     $('#modalLotNo').modal('show');
                // });

                // $('#btnMod').click(function (e) {
                //     e.preventDefault();
                //     $('#modalModeOfDefect').modal('show');
                // });

                // $('#btnAddModLotNumber').click(function (e) {
                //     e.preventDefault();

                //     /* Selected Value */
                //     let selectedLotNo = $('#mod_lot_no').val();
                //     let selectedMod = $('#mode_of_defect').val();
                //     let selectedLotQty = $('#mod_quantity').val();

                //     if(selectedLotNo === null || selectedMod === null || selectedLotQty <= 0){
                //         toastr.error('Error: Please Fill up all fields !');
                //         return false;
                //     }

                //     /* Counter and Disabled Removed Button */
                //     arrCounter.ctr++;
                //     disabledEnabledButton(arrCounter.ctr)

                //     /* Get selected array to the table */
                //     var html_body  = '<tr>';
                //         html_body += '<td>'+arrCounter.ctr+'</td>';
                //         html_body += '<td>'+selectedLotNo+'</td>';
                //         html_body += '<td>'+selectedMod+'</td>';
                //         html_body += '<td>'+selectedLotQty+'</td>';
                //         html_body += '</tr>';
                //     $('#tblModeOfDefect tbody').append(html_body);

                //     arrTableMod.lotNo.push(selectedLotNo);
                //     arrTableMod.modeOfDefects.push(selectedMod);
                //     arrTableMod.lotQty.push(selectedLotQty);
                //     console.log('click',arrTableMod.lotQty);
                //     // console.log('check',arrTableMod);
                // });

                // btn.saveComputation.click(function (e) {
                //     e.preventDefault();
                //     $('#modalModeOfDefect').modal('hide');
                //     form.iqcInspection.find('#no_of_defects').val(arrTableMod.lotQty.reduce(getSum, 0));
                // });

                // btn.removeModLotNumber.click(function() {
                //     arrCounter.ctr --;
                //     disabledEnabledButton(arrCounter.ctr)

                //     $('#tblModeOfDefect tr:last').remove();
                //     arrTableMod.lotNo.splice(arrCounter.ctr, 1);
                //     arrTableMod.modeOfDefects.splice(arrCounter.ctr, 1);
                //     arrTableMod.lotQty.splice(arrCounter.ctr, 1);
                //     console.log('deleted',arrTableMod.lotQty);
                //     // console.log(arrTableMod);
                // });

                // $('#btnModalLotNum').click(function (e) {
                //     e.preventDefault();
                //     let elModalAttr = $(this).attr('el-btn-attr');
                //     $('#modalLotNum').attr('el-modal-attr',elModalAttr).modal('show')
                // });


                // $('a[href="#menu2"]').click(function (e) {
                //     e.preventDefault();
                //     $('#btnModalLotNum').attr('el-btn-attr','ppdWhsPackaging')
                //     $('#txtSearchLotNum').val('');
                //     let categoryMaterial = '46';

                //     dataTable.iqcCnWhsPackaging.draw();
                //     dataTable.iqcCnWhsPackagingInspected.ajax.url("load_cn_iqc_inspection?category_material="+categoryMaterial).draw();
                //     getDropdownDetailsByOptValue('CN',$('#txtCategoryMaterial'),'iqc_category_material_id',categoryMaterial)
                // });


                // $('a[href="#menu1_2"]').click(function (e) {
                //     e.preventDefault();
                //     $('#txtSearchLotNum').val('');
                //     dataTable.iqcCnWhsPackaging.draw();

                // });

                // $('a[href="#menu2_2"]').click(function (e) {
                //     e.preventDefault();
                //     $('#txtSearchLotNum').val('');
                //     console.log('menu2_2');
                //     let categoryMaterial = '45';
                //     dataTable.iqcCnWhsPackagingInspected.ajax.url("load_cn_iqc_inspection?category_material="+categoryMaterial).draw();
                // });

                // $('#modalLotNum').on('shown.bs.modal', function () {
                //     $('#txtLotNum').focus();
                //     const mdlScanLotNum = document.querySelector("#modalLotNum");
                //     const inptScanLotNum = document.querySelector("#txtLotNum");
                //     let focus = false

                //     mdlScanLotNum.addEventListener("mouseover", () => {
                //         if (inptScanLotNum === document.activeElement) {
                //             focus = true
                //         } else {
                //             focus = false
                //         }
                //     });

                //     mdlScanLotNum.addEventListener("click", () => {
                //         if (focus) {
                //             inptScanLotNum.focus()
                //         }
                //     });
                // });

                // $('#txtLotNum').on('keyup', function(e){
                //     if(e.keyCode == 13){
                //         $('#modalLotNum').modal('hide');
                //         let modalId = $("#modalLotNum").attr('el-modal-attr');
                //         let categoryMaterial = $('#txtCategoryMaterial').val();

                //         if ( ( modalId ).indexOf('#') > -1){
                //             $( modalId ).submit();
                //         }else{
                //             switch (modalId) {
                //                 case 'ppdWhsDatabase':
                //                         $('#txtSearchLotNum').val($(this).val());
                //                         dataTable.iqcInspection.draw();
                //                         dataTable.iqcInspected.ajax.url("load_cn_iqc_inspection?category_material="+categoryMaterial).draw();
                //                     break;
                //                 case 'ppdWhsPackaging':
                //                         $('#txtSearchLotNum').val($(this).val());
                //                         dataTable.iqcCnWhsPackaging.draw();
                //                         dataTable.iqcCnWhsPackagingInspected.ajax.url("load_cn_iqc_inspection?category_material="+categoryMaterial).draw();
                //                     break;

                //                 default:
                //                     alert(modalId)
                //                     break;
                //             }
                //         }
                //         $('#txtLotNum').val('');
                //         $('#modalLotNum').modal('hide');
                //     }
                // });


                // dataTable.iqcInspection.on('draw', function () {
                //     if($('#txtSearchLotNum').val() != ""){
                //         $('#tblIqcInspection tbody #btnEditIqcInspection').each(function(index, tr){
                //             $(this).removeClass('d-none');
                //         })
                //     }
                // });

                // dataTable.iqcCnWhsPackaging.on('draw', function () {
                //     if($('#txtSearchLotNum').val() != ""){
                //         $('#tbliqcCnWhsPackaging tbody #btnEditIqcInspection').each(function(index, tr){
                //             $(this).removeClass('d-none');
                //         })
                //     }
                // });


                form.iqcInspection.find('#accepted').keyup(function() {
                    divDisplayNoneClass(form.iqcInspection,$(this).val());
                });

                form.iqcInspection.find('#iqc_coc_file_download').click(function (e) {
                    e.preventDefault();
                    let iqc_inspection_id = form.iqcInspection.find('#iqc_inspection_id').val();
                    window.open('view_coc_file_attachment/'+iqc_inspection_id);

                });

                form.iqcInspection.find('#isUploadCoc').change(function (e) {
                    e.preventDefault();
                    $('#iqc_coc_file').val('');
                    if ($(this).is(':checked')) {
                        form.iqcInspection.find('#iqc_coc_file').prop('required',true);
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

                $('#txtScanUserId').on('keyup', function(e){
                    if(e.keyCode == 13){
                        // console.log($(this).val());
                        validateUser($(this).val(), [2,5], function(result){
                            if(result == true){
                                // console.log('true');
                                // submitProdData($(this).val());
                                // console.log('', $('#txtKeepSample1').val());
                                savePpdIqcInspection();
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
                    savePpdIqcInspection();
                    // $('#modalScanQRSave').modal('show');
                });
                form.iqcInspection.find('select').val(1);
            });

        </script>
    @endsection
{{-- @endauth --}}
