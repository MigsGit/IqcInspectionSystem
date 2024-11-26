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
@extends($layout)

@section('title', 'Matrix')

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

table.table tbody td{
    padding: 4px 4px;
    margin: 1px 1px;
    font-size: 14px;
    /* text-align: center; */
    vertical-align: middle;
}

table.table thead th{
    padding: 4px 4px;
    margin: 1px 1px;
    font-size: 15px;
    text-align: center;
    vertical-align: middle;
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dropdown Maintenance</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Dropdown Maintenance</li>
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
                <div class="col-md-6" id="colDropdownCategory">
                    <!-- general form elements -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <h3 class="card-title">Dropdown Category</h3>
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

                                <button class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#modalAddDropdownCategory" id="btnShowAddDropdownCategoryModal"><i
                                        class="fa fa-initial-icon"></i> Add Dropdown Category</button>
                            </div> <br><br>
                            <div class="table-responsive">
                                <!-- style="max-height: 600px; overflow-y: auto;" -->
                                <table id="dropdownCategory" class="table table-sm table-bordered table-striped table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Dropdown Category</th>
                                            <th>Section</th>
                                            <th>Updated By</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- !-- End Page Content -->

                    </div>
                    <!-- /.card -->
                </div>

                <div class="col-md-6" id="colDropdownDetails">
                    <!-- general form elements -->
                    <div class="card card-dark">
                        <div class="card-header">
                            <button class="btn btn-sm btn-secondary float-right ml-3 py-0 px-1 " title="Maximize"
                                id="btnMaximizeColMatProc"><i class="fas fa-arrows-alt-h"></i></button>
                            <button class="btn btn-sm btn-secondary float-right ml-3 py-0 px-1 " title="Minimize"
                                id="btnMinimizeColMatProc" style="display: none;"><i
                                    class="fas fa-arrows-alt-h"></i></button>
                            <h3 class="card-title">Process</h3>
                        </div>

                        <!-- Start Page Content -->
                        <div class="card-body">
                            <div style="float: right;">
                                <button class="btn btn-dark" id="btnShowAddMatProcModal"><i
                                        class="fa fa-initial-icon"></i> Add Dropdown Details</button>
                            </div>
                            <div style="float: left;">
                                <label>Category: <u id="uSelectedCategoryName">No Selected Category</u></label>
                            </div>
                            <br><br>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend w-50">
                                            <span class="input-group-text w-100" id="basic-addon1">Status</span>
                                        </div>
                                        <select class="form-control select2 select2bs4 selectUser"
                                            id="selFilterMatProcStat">
                                            <option value="0"> Active </option>
                                            <option value="1"> Inactive </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dropdownDetails"
                                    class="table table-sm table-bordered table-striped table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Status</th>
                                            <th>Dropdown Details</th>
                                            <th>Updated By</th>
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
<div class="modal fade" id="modalAddDevice">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add Device</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddDevice">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="txtDeviceId" name="id">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" class="form-control" name="code" id="txtAddDeviceCode">
                            </div>

                            <div class="form-group">
                                <label>Material Name</label>
                                <input type="text" class="form-control" name="name" id="txtAddDeviceName">
                            </div>

                            <div class="form-group">
                                <label>Qty per Reel/ Bundle of Trays</label>
                                <input type="number" class="form-control" name="qty_reel" id="txtAddQty" value="0">
                            </div>

                            <div class="form-group">
                                <label>Qty per Box</label>
                                <input type="number" class="form-control" name="qty_box" id="txtAddQtyBox" value="0">
                            </div>

                            <div class="form-group">

                            <label>Process</label>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="process" id="stamping" value="0">
                                <label class="form-check-label" for="stamping">
                                Stamping
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="process" id="molding" value="1">
                                <label class="form-check-label" for="molding">
                                Molding/Assy
                                </label>
                            </div>
                            </div>

                            <div class="form-group">
                            <label>Percentage needed</label>
                            <div class="row">
                                <div class="col-6">
                                    <label>Virgin Material</label>
                                    <input type="number" class="form-control" name="virgin" id="txtVirginPerc">
                                </div>
                                <div class="col-6">
                                    <label>Recycled Material</label>
                                    <input type="number" class="form-control" name="recycled" id="txtRecycledPerc">

                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnAddDevice" class="btn btn-dark"><i id="iBtnAddDeviceIcon"
                            class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalChangeDeviceStat">
    <div class="modal-dialog">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h4 class="modal-title" id="h4ChangeDeviceTitle"><i class="fa fa-user"></i> Change Status</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formChangeDeviceStat">
                @csrf
                <div class="modal-body">
                    <label id="lblChangeDeviceStatLabel">Are you sure to ?</label>
                    <input type="hidden" name="device_id" placeholder="Device Id" id="txtChangeDeviceStatDeviceId">
                    <input type="hidden" name="status" placeholder="Status" id="txtChangeDeviceStatDeviceStat">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">No</button>
                    <button type="submit" id="btnChangeDeviceStat" class="btn btn-dark"><i
                            id="iBtnChangeDeviceStatIcon" class="fa fa-check"></i> Yes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!-- MATERIAL PROCESS MODALS -->
<div class="modal fade" id="modalAddMatProc">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-initial-icon"></i> Process</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddMatProc" autocomplete="off">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" name="device_id" id="txtAddMatProcDevId">
                            <input type="hidden" name="mat_proc_id" id="txtAddMatProcId">

                            <div class="form-group">
                                <label>Device Name</label>
                                <input type="text" class="form-control" name="device_name"
                                    id="txtAddMatProcDeviceName" readonly>
                            </div>

                            <div class="form-group">
                            <label>Step</label>
                            <input type="number" class="form-control" name="step"
                                id="txtAddMatProcStep" readonly>
                            </div>

                            <div class="form-group">
                            <label>Process</label>
                            {{-- <input type="text" class="form-control" name="process"
                                id="txtAddMatProcProcess"> --}}
                                <select class="form-control select2bs4" id="selAddMatProcProcess" name="process">

                                </select>
                            </div>
                            <div class="form-group">
                            <label>Machine</label>
                            <select class="form-control select2bs4" id="selAddMatProcMachine" name="machine[]" multiple>

                            </select>
                            </div>
                            <div class="form-group">
                            <label>Station</label>
                            <select class="form-control select2bs44" id="selAddMatStation" name="">
                            </select>
                            </div>
                            <div class="form-group">
                            <label>Material Name</label>
                            <select class="form-control select2bs4" id="selAddMatProcMatName" name="material_name[]" multiple>
                            </select>
                            {{-- <select class="form-control select2bs4" id="selAddMatProcMatName" name="" multiple="">

                            </select> --}}
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnAddMatProc" class="btn btn-dark"><i id="iBtnAddMatProcIcon"
                            class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalImportPackingMatrix">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-file-excel"></i> Import Packing Matrix</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formImportPackingMatrix" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>File</label>
                                <input type="file" class="form-control" name="import_file"
                                    id="fileImportPackingMatrix">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tblImportResult">
                                    <thead>
                                        <tr>
                                            <th>No. of Inserted</th>
                                            <th class="thNoOfInserted">0</th>
                                        </tr>
                                        <tr>
                                            <th>No. of Updated</th>
                                            <th class="thNoOfUpdated">0</th>
                                        </tr>
                                        <tr>
                                            <th>No. of Failed</th>
                                            <th class="thNoOfFailed">0</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">
                                                <center>List of Failed Product Code</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" id="btnImportPackingMatrix" class="btn btn-dark"><i
                            id="iBtnImportPackingMatrixIcon" class="fa fa-check"></i> Import</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="modalChangeMatProcStat">
    <div class="modal-dialog">
        <div class="modal-content modal-sm">
            <div class="modal-header">
                <h4 class="modal-title" id="h4ChangeMatProcTitle"><i class="fa fa-default"></i> Change Status</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formChangeMatProcStat">
                @csrf
                <div class="modal-body">
                    <label id="lblChangeMatProcStatLabel">Are you sure to ?</label>
                    <input type="hidden" name="material_process_id" placeholder="Material Process Id"
                        id="txtChangeMatProcStatMatProcId">
                    <input type="hidden" name="status" placeholder="Status" id="txtChangeMatProcStatMatProcStat">
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">No</button>
                    <button type="submit" id="btnChangeMatProcStat" class="btn btn-dark"><i
                            id="iBtnChangeMatProcStatIcon" class="fa fa-check"></i> Yes</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Modal -->
<div class="modal fade" id="mdl_qrcode_scanner" data-formid="" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <!-- <h5 class="modal-title" id="exampleModalLongTitle"></h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center text-secondary">
                    Please scan the code.
                    <br>
                    <br>
                    <h1><i class="fa fa-qrcode fa-lg"></i></h1>
                </div>
                <input type="text" id="txt_qrcode_scanner" class="hidden_scanner_input">
            </div>
        </div>
    </div>
</div>
<!-- /.Modal -->
@endsection

@section('js_content')
<script type="text/javascript">
$(document).ready(function () {

    settingTbl = {
        dropdownCategory:'#dropdownCategory',
        dropdownDetails:'#dropdownDetails',
    };

    settingDataTable = {
        dropdownCategory:'',
        dropdownDetails:'',
    };

    settingDataTable.dropdownCategory = $(settingTbl.dropdownCategory).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "read_dropdown_details_by_category",
        },
        fixedHeader: true,
        "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "dropdown_category" },
            { "data" : "section" },
            { "data" : "updated_by" },
        ],
    });
    // settingDataTable.dropdownDetails = $(settingTbl.dropdownDetails).DataTable({
    //     "processing" : true,
    //     "serverSide" : true,
    //     "ajax" : {
    //         url: "read_dropdown_details_by_category",
    //     },
    //     fixedHeader: true,
    //     "columns":[
    //         { "data" : "action", orderable:false, searchable:false },
    //         { "data" : "status", orderable:false, searchable:false },
    //         { "data" : "InvoiceNo" },
    //         { "data" : "Supplier" },
    //         { "data" : "PartNumber" },
    //         { "data" : "MaterialType" },
    //         { "data" : "Lot_number" },
    //     ],
    // });

    $(document).on('click', `${settingTbl.dropdownCategory} tbody tr`, function (e) {
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');

        let dropdownCategoryId = $(this).find('#btnEditIqcInspection').attr('dropdownCategoryId');
        let dropdownCategory = $(this).find('#tdDropdownCategory').val();
        console.log(dropdownCategory);


        // console.log( selectedProcess);
        $("#uSelectedCategoryName").text(dropdownCategory);
        // readDropdownDetailsByCategory(receivingDetailId);

    });
});
</script>
@endsection
