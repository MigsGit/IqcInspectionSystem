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
                            <h1>IQC Inspection</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">IQC Inspection</li>
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
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">IQC Inspection</h3>
                                </div>

                                <!-- Start Page Content -->
                                <div class="card-body">
                                    <div style="float: right;">
                                        {{-- @if(Auth::user()->user_level_id == 1)
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                                        @else
                                        @if(Auth::user()->position == 7 || Auth::user()->position == 8)
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                                        @endif
                                        @endif --}}

                                        {{-- <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#modalAddProcess" id="btnShowAddDevic"><i
                                                class="fa fa-initial-icon"></i> Add Device
                                        </button> --}}
                                    </div> <br><br>
                                    <div class="table-responsive">
                                        <!-- style="max-height: 600px; overflow-y: auto;" -->
                                        <table id="tblIqcInspection" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th><i  class="fa fa-cog"></i></th>
                                                    <th>Invoice No.</th>
                                                    <th>Inspector</th>
                                                    <th>Inspection Date</th>
                                                    <th>Inspection Times</th>
                                                    <th>Application Ctrl. No</th>
                                                    <th>FY#</th>
                                                    <th>WW#</th>
                                                    <th>Sub</th>
                                                    <th>Part Code</th>
                                                    <th>Part Name</th>
                                                    <th>Supplier</th>
                                                    <th>Lot No.</th>
                                                    <th>Lot Qty.</th>
                                                    <th>Date Created</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
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

        <!-- MODALS -->
        {{-- * ADD --}}
        <div class="modal fade" id="modalEditInspection">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fa fa-edit"></i> IQC Inspection</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="post" id="formProcess" autocomplete="off">
                        @csrf
                        <div class="modal-body">
                            {{-- <div class="row">
                                <div class="col-sm-12">
                                    <input type="hidden" id="txtProcessId" name="id">

                                    <div class="form-group">
                                        <label>Process Name</label>
                                        <input type="text" class="form-control" name="process_name" id="txtProcessName">
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row" id="Visual Inspection">
                                <div class="row">
                                    <hr>
                                    <div class="col-sm-12">
                                        <strong>Visual Inspection</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Invoice No.</span>
                                        </div>
                                            {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                            <input type="text" class="form-control form-control-sm" id="txtInput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Part Code</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Part Name</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Supplier</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Application Date</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtInput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Application Time</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Application Ctrl. No.</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Lot No.</span>
                                        </div>
                                            <button type="button" class="form-control form-control-sm bg-info" id="txtOutput">Lot Number</button>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Lot Quantity</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                </div>

                            </div>
                            <div class="row mt" id="Sampling Plan">
                                <div class="row">
                                    <hr>
                                    <div class="col-sm-12">
                                        <strong>Sampling Plan</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Type of Inspection</span>
                                        </div>
                                            {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                            <select class="form-control form-control-sm" id="txtInput"></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Severity of Inspection</span>
                                        </div>
                                            <select class="form-control form-control-sm" id="txtOutput"></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Inspection Level</span>
                                        </div>
                                            <select class="form-control form-control-sm" id="txtOutput"></select>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">AQL</span>
                                        </div>
                                            {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                            <select class="form-control form-control-sm" id="txtInput"></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Accept</span>
                                        </div>
                                            <select class="form-control form-control-sm" id="txtOutput"></select>
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Reject</span>
                                        </div>
                                            <select class="form-control form-control-sm" id="txtOutput"></select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt" id="Sampling Plan">
                                <div class="row">
                                    <hr>
                                    <div class="col-sm-12">
                                        <strong>Visual Inspection Result</strong>
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Date Inspected</span>
                                        </div>
                                            {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                            <input type="text" class="form-control form-control-sm" id="txtInput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-30">
                                            <span class="input-group-text w-100" id="basic-addon1">WW#</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                        <div class="input-group-prepend w-30">
                                            <span class="input-group-text w-100" id="basic-addon1">FY#</span>
                                        </div>
                                        <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Time Inspected</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Shift</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Inspector</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Submission</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Judgement</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Lot Inspected</span>
                                        </div>
                                            {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                            <input type="text" class="form-control form-control-sm" id="txtInput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Lot Accepted</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Sampling Size</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                        </div>
                                            <input type="text" class="form-control form-control-sm" id="txtOutput">
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <div class="col">
                                  <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                      <span class="input-group-text w-100" id="basic-addon1">Final Visual Operator</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="txt_operator_name" name="txt_operator_name" readonly="true">
                                    <input type="text" class="form-control form-control-sm" id="txtOperatorId" name="operator_id" readonly="" style="display: none;">
                                    <button class="btn btn-xs btn-primary input-group-append btnScanOperator" type="button" style="padding: 5px 8px; padding-top: 8px;"><i class="fa fa-qrcode"></i></button>
                                  </div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="btnProcess" class="btn btn-primary"><i
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
            // var dtIqcInspection = $("#tblIqcInspection").DataTable({
            //     "processing" : true,
            //     "serverSide" : true,
            //     "ajax" : {
            //         url: "",
            //         // data: function (param){
            //         //     param.status = $("#selEmpStat").val();
            //         // }
            //     },
            //     fixedHeader: true,
            //     "columns":[

            //         { "data" : "action", orderable:false, searchable:false },
            //         { "data" : "label" },
            //         { "data" : "process_name" }
            //     ],
            // });

        $.ajax({
            type: "GET",
            url: "load_whs_transaction",
            data: "data",
            dataType: "json",
            success: function (response) {
                console.log(response);
                $('#modalEditInspection').modal('show');

            }
        });

        </script>
        {{-- <script type="text/javascript">
            let datatableProcesss;

            datatableProcesss = $("#tblProcess").DataTable({
                "processing" : true,
                "serverSide" : true,
                "ajax" : {
                    url: "view_process",
                    // data: function (param){
                    //     param.status = $("#selEmpStat").val();
                    // }
                },
            fixedHeader: true,
            "columns":[

                { "data" : "action", orderable:false, searchable:false },
                { "data" : "label" },
                { "data" : "process_name" }
            ],
            });//end of dataTableDevices

            $('#formProcess').submit(function(e){
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: "add_process",
                    data: $('#formProcess').serialize(),
                    dataType: "json",
                    success: function (response) {
                        if(response['result'] == 1){
                            datatableProcesss.draw();
                            $('#modalAddProcess').modal('hide');
                        }
                    }
                });
            });

            $(document).on('click', '.btnEdit', function(e){
                let pId = $(this).data('id');
                $.ajax({
                    type: "get",
                    url: "get_process_by_id",
                    data: {
                        "id" : pId
                    },
                    dataType: "json",
                    success: function (response) {


                        $('#txtProcessId').val(response['id']);
                        $('#txtProcessName').val(response['process_name']);

                        $('#modalAddProcess').modal('show');

                    }
                });
            });

            $(document).on('click', '.btnDisable', function(e){
                let pId = $(this).data('id');

                Swal.fire({
                    // title: "Are you sure?",
                    text: "Are you sure you want to disable this process",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: "update_status",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id" : pId,
                            },
                            dataType: "json",
                            success: function (response) {
                            }
                        });
                    }
                });

            })

            function resetFormValues() {
                // Reset values
                $("#formProcess")[0].reset();


            }

            $("#modalAddProcess").on('hidden.bs.modal', function () {
                console.log('hidden.bs.modal');
                resetFormValues();
            });


        </script> --}}
    @endsection
@endauth
