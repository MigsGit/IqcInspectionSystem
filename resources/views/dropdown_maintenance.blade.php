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
                            {{-- @if(Auth::user()->user_level_id == 1)
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                            @else
                                @if(Auth::user()->position == 7 || Auth::user()->position == 8)
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalImportPackingMatrix" id="btnShowImport" title="Import Packing Matrix"><i class="fa fa-file-excel"></i> Import</button>
                                @endif
                            @endif --}}
                            @if(session('rapidx_department_id') == 1)
                                <div style="float: right;">
                                    <button class="btn btn-dark" data-bs-toggle="modal"
                                        data-bs-target="#modalCreateDropdownCategory" id="btnShowAddDropdownCategoryModal"><i
                                            class="fa fa-initial-icon"></i> Add Dropdown Category
                                    </button>
                                </div> <br><br>
                            @endif
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
                                <button class="btn btn-dark" data-bs-toggle="modal"
                                    data-bs-target="#modalCreateDropdownDetails" id="btnShowAddDropdownCategoryModal"><i
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
                    </div>
                </div>
    </section>
</div>

<!-- MODALS -->
{{-- * ADD --}}
<div class="modal fade" id="modalCreateDropdownCategory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add DropdownCategory</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formSaveDropdownCategory">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="dropdown_category_id" name="dropdown_category_id">
                            <div class="form-group">
                                <label>Dropdown Category</label>
                                <input type="text" class="form-control" name="dropdown_category" id="dropdown_category">
                            </div>
                            <div class="form-group">
                                <label>Section</label>
                                <select class="form-control" id="section" name="section">
                                    <option value="" selected disabled>Select</option>
                                    <option value="TS">TS</option>
                                    <option value="CN">CN</option>
                                    <option value="PPD">PPD</option>
                                    <option value="YF">YF</option>
                                </select>
                            </div>
                            {{-- nmodify --}}
                            <div class="form-group">
                                <label>Iqc Inspection Column Reference</label>
                                <input type="text" class="form-control" name="iqc_inspection_column_ref" id="iqc_inspection_column_ref">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnAddDropdownCategory" class="btn btn-dark"><i id="iBtnAddDropdownCategoryIcon"
                            class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreateDropdownDetails">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add DropdownDetails</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formSaveDropdownDetails">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="dropdown_details_id" name="dropdown_details_id">
                            <input type="hidden" id="iqc_dropdown_categories_id" name="iqc_dropdown_categories_id">
                            <div class="form-group">
                                <label>Dropdown  category</label>
                                <input type="text" class="form-control" name="dropdown_details" id="dropdown_details">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="btnAddDropdownCategory" class="btn btn-dark"><i id="iBtnAddDropdownCategoryIcon"
                            class="fa fa-check"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- modal-loading --}}
@include('component.modal')

@endsection

@section('js_content')
<script type="text/javascript">
$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    form = {
        saveDropdownCategory : $('#formSaveDropdownCategory'),
        saveDropdownDetails : $('#formSaveDropdownDetails')
    }
    settingTbl = {
        dropdownCategory:'#dropdownCategory',
        dropdownDetails:'#dropdownDetails',
    };

    settingDataTable = {
        dropdownCategory:'',
        dropdownDetails:'',
    };
    if(globalVar.department === 'ISS'){
        $('#btnDropdownCategory').removeClass('d-none',true)
        console.log('dept',globalVar.department);
    }

    settingDataTable.dropdownCategory = $(settingTbl.dropdownCategory).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "read_dropdown_category",
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
    settingDataTable.dropdownDetails = $(settingTbl.dropdownDetails).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "read_dropdown_details_by_category",
        },
        fixedHeader: true,
        "columns":[
            { "data" : "raw_action", orderable:false, searchable:false },
            { "data" : "raw_status", orderable:false, searchable:false },
            { "data" : "dropdown_details" },
            { "data" : "updated_by" },
        ],
    });

    $('#modalCreateDropdownDetails').on('hidden.bs.modal', function (e) {
        form.saveDropdownDetails.find('#dropdown_details_id').val('');
        form.saveDropdownDetails.find('#dropdown_details').val('');
    });
    $('#modalCreateDropdownCategory').on('hidden.bs.modal', function (e) {
        form.saveDropdownCategory[0].reset()
    });



    $(document).on('click', `${settingTbl.dropdownCategory} tbody tr`, function (e) {
        $(this).closest('tbody').find('tr').removeClass('table-active');
        $(this).closest('tr').addClass('table-active');

        let dropdownCategoryId = $(this).find('#tdDropdownCategoryId').val();
        let dropdownCategory = $(this).find('#tdDropdownCategory').val();

        $("#uSelectedCategoryName").text(dropdownCategory);
        form.saveDropdownDetails.find('#iqc_dropdown_categories_id').val(dropdownCategoryId);
        settingDataTable.dropdownDetails.ajax.url("read_dropdown_details_by_category?iqc_dropdown_category_id="+dropdownCategoryId).draw();
    });

    $(settingTbl.dropdownCategory).on('click','#btnDropdownCategory',`tr`, function () {
        $('#modalCreateDropdownCategory').modal('show');
        let dropdownCategoryId = $(this).attr('dropdown-category-id');
        readDropdownCategoryById(dropdownCategoryId);
    });
    $(settingTbl.dropdownDetails).on('click','#btnDropdownCategory',`tr`, function () {
        $('#modalCreateDropdownDetails').modal('show');
        let dropdownDetailsId = $(this).attr('dropdown-details-id');
        readDropdownDetailsById(dropdownDetailsId)
    });

    form.saveDropdownCategory.submit(function (e) {
        e.preventDefault();
        let data = {}
        let serializedData = $(this).serialize();
        let elFormId = $(this);
        call_ajax_serialize(data,serializedData , 'save_dropdown_category_by_id', function(response){
            if(response.isSuccess === 'true'){
                settingDataTable.dropdownCategory.draw();
                $('#modalCreateDropdownCategory').modal('hide');
            }
        })
    });
    form.saveDropdownDetails.submit(function (e) {
        e.preventDefault();
        let data = {}
        let serializedData = $(this).serialize();
        let elFormId = $(this);
        // call_ajax_serialize(elFormId,data,serializedData , 'save_dropdown_details_by_id', function(response){
        call_ajax_serialize(data,serializedData , 'save_dropdown_details_by_id', function(response){
            if(response.isSuccess === 'true'){
                settingDataTable.dropdownDetails.draw();
                $('#modalCreateDropdownDetails').modal('hide');
            }
        })
    });
});
</script>
@endsection
