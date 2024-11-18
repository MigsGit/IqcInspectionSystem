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
            .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
                font-size: .75rem;
                padding: .0em 0.55vmax;
                margin-bottom: 0px;
            }

            .select2-container--bootstrap-5 .select2-selection--multiple{
                pointer-events: none;
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
                                                <div class="col-sm-2">
                                                  <label>Device Name</label>
                                                  <div class="input-group">
                                                    <select class="form-select form-control" id="global_device_name" name="global_device_name" >
                                                    </select>
                                                  </div>
                                                </div>
                                                <div class="col-sm-2 d-none">
                                                  <label>Input Device Name</label>
                                                    <input type="text" class="form-control" id="global_input_device_name" name="global_input_device_name" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                  <label>Contact Name</label>
                                                    <input type="text" class="form-control" id="global_contact_name" name="global_contact_name" readonly>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>PO Number</label>
                                                    <div class="input-group">
                                                      <input type="text" class="form-control" id="global_po_no" name="global_po_no">
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>PO Qty</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="global_po_qty" name="global_po_qty" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Target Qty</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="global_target_qty" name="global_target_qty" readonly>
                                                    </div>
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
                                                    <th>PMI PO No.</th>
                                                    <th>PO No.</th>
                                                    <th>Device Name</th>
                                                    <th>Contact Name</th>
                                                    <th>Contact Lot No.</th>
                                                    <th>Production Lot No.</th>
                                                    <th>Shipment Output</th>
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

    {{-- @include('component.modal') --}}
    <div class="modal fade" id="modalFirstMolding" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-xl-custom">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fa fa-plus"></i> Add Data</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formFirstMolding" autocomplete="off">
                    @csrf
                    <div class="modal-body">


                        <div class="row">
                            <div class="col-sm-6 border px-4">
                                <div class="py-3">
                                    <span class="badge badge-secondary">1.</span> Runcard Details
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="radioIQC" value="0" disabled>
                                    <label class="form-check-label" for="radioIQC">For Quali</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="radioMassProd" value="1" disabled>
                                    <label class="form-check-label" for="radioMassProd">For Mass Production</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="radioResetup" value="2" disabled>
                                    <label class="form-check-label" for="radioResetup">For Re-quali</label>
                                </div>
                                <div class="input-group input-group-sm mb-3 d-none">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">First Molding Id </span>
                                    </div>
                                    <input class="form-control form-control-sm" type="text" id="first_molding_id" name="first_molding_id">
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Device Name</span>
                                            </div>
                                            <select class="form-select form-control-sm" id="first_molding_device_id" name="first_molding_device_id" >
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-30">
                                                <span class="input-group-text w-100" id="basic-addon1">Production Lot</span>
                                            </div>
                                            <input value="2E240130-" type="text" class="form-control form-control-sm" id="production_lot" name="production_lot">
                                            <input value="M-7:30-11:30" type="text" class="form-control form-control-sm" id="production_lot_extension" name="production_lot_extension" placeholder="7:30-11:30">
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Contact Name: </span>
                                            </div>
                                            <input value="25" type="text" class="form-control form-control-sm" id="contact_name" name="contact_name" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-30">
                                                <span class="input-group-text w-100" id="basic-addon1">Drawing No</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="drawing_no" name="drawing_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Contact Lot #</span>
                                            </div>
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="contact_lot_number" name="contact_lot_number" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Contact Lot Qty</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="contact_lot_qty" name="contact_lot_qty" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Dieset No.: </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="dieset_no" name="dieset_no">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-30">
                                                <span class="input-group-text w-100" id="basic-addon1">Revision No.</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="revision_no" name="revision_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Machine #</span>
                                            </div>
                                            <select type="text" class="form-control form-control-sm" id="machine_no" name="machine_no" placeholder="Machine #" sytle="width:100%">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Shift </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="shift" name="shift" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">25 Shots </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm sumTotalMachineOutput" id="target_shots" name="target_shots" value="25" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Adjustment Shots</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm sumTotalMachineOutput" id="adjustment_shots" name="adjustment_shots" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">QC Samples</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm sumTotalMachineOutput" id="qc_samples" name="qc_samples" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Prod Samples</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm sumTotalMachineOutput" id="prod_samples" name="prod_samples" min="0" step="0.01">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">NG Count</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm sumTotalMachineOutput" id="ng_count" name="ng_count" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Total Machine Output</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" id="total_machine_output" name="total_machine_output" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                            <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Shipment Output:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm sumTotalMachineOutput" id="shipment_output" name="shipment_output" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Material Yield
                                                    &nbsp; <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Auto Compute &#013;(Shipment Output / Total Machine Output) * 100"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="material_yield" name="material_yield" onkeypress="return event.charCode >= 48 && event.charCode <= 57" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">PMI PO Number</span>
                                            </div>
                                            <input value="PR2310089320" type="text" class="form-control form-control-sm" id="pmi_po_no" name="pmi_po_no" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Target Output
                                                    &nbsp; <i class="fa-solid fa-circle-question" data-bs-toggle="tooltip" data-bs-html="true" title="Auto Compute &#013;(PO Qty * Usage)"></i>
                                                </span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" id="required_output" name="required_output" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">PO Number</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="po_no" name="po_no" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">PO Qty</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" id="po_qty" name="po_qty" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Item Code</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="item_code" name="item_code" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Series Name</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="item_name" name="item_name" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6 d-none">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Target For S/O</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" id="po_target" name="po_target" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Variance</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" id="po_balance" name="po_balance" min="0" step="0.01" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                            </div>
                                            <textarea class="form-control form-control-sm" id="remarks" name="remarks" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="input-group input-group-sm mb-3">
                                            <div class="input-group-prepend w-50">
                                                <span class="input-group-text w-100" id="basic-addon1">Created At</span>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" id="created_at" name="created_at" readonly="true" placeholder="Auto generated">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <div class="d-flex justify-content-end">
                                                {{-- <button type="button" id="btnAddFirstMoldingMaterial" class="btn btn-sm btn-info" title="Add Material"><i class="fa fa-plus"></i> Add Material</button> --}}
                                            </div>
                                            <br>
                                            <table class="table table-sm" id="tblFirstMoldingMaterial">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 35%;">Virgin Material</th>
                                                        <th style="width: 10%;">Virgin Qty</th>
                                                        {{-- <th style="width: 35%;">Recycled Material</th> --}}
                                                        <th style="width: 10%;">Recycled Qty</th>
                                                        {{-- <th style="width: 10%;">Action</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="input-group input-group-sm mb-3">
                                                                <div class="input-group-prepend">
                                                                    <button type="button" class="btn btn-dark" id="btnScanQrFirstMoldingVirginMaterial"><i class="fa fa-qrcode w-100"></i></button>
                                                                </div>
                                                                <input type="text" class="form-control form-control-sm" id="virgin_material" name="virgin_material[]" required min=1 step="0.01">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm mb-3">
                                                                <input min=1 step="0.01" type="number" class="form-control form-control-sm inputVirginQty" id="virgin_qty" name="virgin_qty[]" required min=1 step="0.01">
                                                            </div>
                                                        </td>
                                                        <td class="d-none">
                                                            <div class="input-group input-group-sm mb-3">
                                                                    <div class="input-group-prepend">
                                                                        <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                                                                    </div>
                                                                    <input type="text" class="form-control form-control-sm" id="recycle_material" name="recycle_material[]">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group input-group-sm mb-3">
                                                                <input min=1 step="0.01" type="number" class="form-control form-control-sm" id="recycle_qty" name="recycle_qty[]" required>
                                                            </div>
                                                        </td>
                                                        {{-- <td>
                                                            <center><button class="btn btn-danger buttonRemoveMaterial" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                                                        </td> --}}
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-sm btn-success" type="submit" id="btnRuncardDetails">
                                                <i class="fa-solid fa-floppy-disk"></i> Save
                                            </button>
                                            {{-- <button type="button" id="btnAddFirstMoldingMaterial" class="btn btn-sm btn-info" title="Add Material"><i class="fa fa-plus"></i> Add Material</button> --}}
                                        </div>
                                        <br>
                                    </div>
                                </div>
            </form>

                            </div>
                            <div class="col-sm-6">
                                <div class="col border px-4 border">
                                    <div class="py-3">
                                        <div style="float: left;">
                                            <span class="badge badge-secondary">2.</span> Stations
                                        </div>
                                        <div style="float: right;">
                                            <button class="btn btn-primary btn-sm" type="button" id="btnFirstMoldingStation" disabled>
                                                <i class="fa fa-plus" ></i> Add Station
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm small table-bordered table-hover" id="tblFirstMoldingStationDetails" style="width: 100%;">
                                                <thead>
                                                    <tr class="bg-light">
                                                        <th></th>
                                                        <!-- <th></th> -->
                                                        <th>Station</th>
                                                        <th>Date</th>
                                                        <th>Name</th>
                                                        <th>Size</th>
                                                        <th>Input</th>
                                                        <th>NG Qty</th>
                                                        <th>Output</th>
                                                        <th>Remarks</th>
                                                        <th>Created Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                {{-- <tfoot>
                                                    <tr>
                                                        <th style="border-top: 1px solid #dee2e6"></th>
                                                        <th style="border-top: 1px solid #dee2e6"></th>
                                                        <th style="border-top: 1px solid #dee2e6"></th>
                                                        <th style="border-top: 1px solid #dee2e6"></th>
                                                        <th style="border-top: 1px solid #dee2e6; white-space: nowrap;">Total Count:</th>
                                                        <th style="border-top: 1px solid #dee2e6" title="Total NG Count of Station" class="text-danger"></th>
                                                        <th style="border-top: 1px solid #dee2e6" title="Total Visual Inspection" class="text-success"></th>
                                                        <th style="border-top: 1px solid #dee2e6"></th>
                                                    </tr>
                                                </tfoot> --}}
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="btnSubmitFirstMoldingStation" class="btn btn-primary" disabled><i class="fa fa-check"></i> Submit</button>
                    </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modalFirstMoldingStation" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-object-group text-info"></i> Stations</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formFirstMoldingStation">
                        @csrf
                        <div class="row d">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">First Molding Id</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="first_molding_id" name="first_molding_id">
                                </div>
                            </div>
                        </div>
                        <div class="row d">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">First Molding Detail Id</span>
                                    </div>
                                    <input type="text" class="form-control form-control-sm" id="first_molding_detail_id" name="first_molding_detail_id">
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Step</span>
                                    </div>
                                    <select type="text" class="form-control form-control-sm" id="step" name="step" placeholder="Station">
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Station</span>
                                    </div>
                                    <select type="text" class="form-control form-control-sm" id="station" name="station" placeholder="Station">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Date</span>
                                    </div>
                                    <input type="date" class="form-control form-control-sm" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Operator Name</span>
                                    </div>
                                    <select type="text" class="form-control form-control-sm" id="operator_name" name="operator_name" placeholder="Station">
                                    {{-- <option value="{{ Auth::user()->id }}">{{ Auth::user()->firstname  .' '. Auth::user()->lastname }}</option> --}}
                                    </select>
                                    {{-- <input type="text" class="form-control form-control-sm" id="operator_name" name="operator_name"> --}}
                                </div>
                            </div>
                        </div>
                        <div class="row d-none" id="isSelectCameraInspection">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Size</span>
                                    </div>
                                    <select type="text" class="form-control form-control-sm" id="size_category" name="size_category" placeholder="Station">
                                        <option value="" selected disabled>--Select--</option>
                                        <option value="Small">Small</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Large">Large</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100" id="basic-addon1">Input</span>
                                    </div>
                                    <input type="number" class="form-control form-control-sm" id="input" name="input" min="0" step="0.01" readonly>
                                    <div class="input-group-text">
                                        <input type="checkbox" value="1" id="is_partial" name="is_partial">
                                     </div>
                                    <input type="text" class="form-control form-control-sm" placeholder="Partial?" id="input" name="input" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">NG Qty</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="ng_qty" name="ng_qty" min="0" value="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Output</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="output" name="output" min="0" step="0.01" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Yield</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="station_yield" name="station_yield" step="0.01" onkeypress="return event.charCode >= 48 && event.charCode <= 57" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="remarks" name="remarks">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="table-responsive">
                                    <div class="d-flex justify-content-between">

                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <label>Total No. of NG: <span id="labelTotalNumberOfNG" style="color: red;">0</span>
                                            <label>
                                                &nbsp;<li class="fa-solid fa-thumbs-down" id="labelIsTally" style="color: red;"></li>
                                            </label>
                                        </label>
                                        <button type="button" id="buttonAddFirstMoldingModeOfDefect" class="btn btn-sm btn-info" title="Add MOD" disabled><i class="fa fa-plus"></i> Add MOD</button>
                                    </div>
                                    <br>
                                    <table class="table table-sm" id="tableFirstMoldingStationMOD">
                                        <thead>
                                            <tr>
                                                <th style="width: 55%;">Mode of Defect</th>
                                                <th style="width: 15%;">QTY</th>
                                                <th style="width: 10%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="buttonFirstMoldingStation">Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Start Scan QR mdlScanQrCodeFirstMolding -->
    <div class="modal fade" id="mdlScanQrCodeFirstMolding" data-formid="" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pt-0">
                    {{-- hidden_scanner_input --}}
                    <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanQrCodeFirstMolding" name="scan_qr_code" autocomplete="off">
                    <div class="text-center text-secondary">Please scan the code.<br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.End Scan QR Modal -->

    <!-- Start Scan QR MmdlScanQrCodeFirstMoldingMaterial -->
    <div class="modal fade" id="mdlScanQrCodeFirstMoldingMaterial" data-formid="" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body pt-0">
                    {{-- hidden_scanner_input --}}
                    <input type="text" class="scanner w-100 hidden_scanner_input" id="txtScanQrCodeFirstMolding" name="scan_qr_code" autocomplete="off">
                    <div class="text-center text-secondary">Please scan the Material QR code.<br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.End Scan QR Modal -->

    {{-- Modal Scan modalMaterialLotNum --}}
    <div class="modal fade" id="modalMaterialLotNum" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-top" role="document">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    {{-- <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
                </div>
                <div class="modal-body pt-2">
                    {{-- hidden_scanner_input --}}
                    <input type="text" class="w-100 hidden_scanner_input" id="txtLotNum"  autocomplete="off" is_inspected="false">
                    {{-- <input type="text" class="scanner w-100" id="txtScanPO"  autocomplete="off"> --}}
                    {{-- <input type="text" class="scanner w-100" id="txtScanQrCode" name="scan_qr_code" autocomplete="off"> --}}
                    <div class="text-center text-secondary">Please scan Lot Number.<br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                </div>
            </div>
        </div>
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
{{-- end  --}}
    @endsection

    @section('js_content')
        <script>
            $(document).ready(function () {

                getFirstModlingDevices();
                // $('#production_lot_extension').mask('00:00-00:00', {reverse: false});
                $('#modalFirstMolding').on('hidden.bs.modal', function() {
                    formModal.firstMolding.find('#first_molding_id').val('');
                    formModal.firstMoldingStation.find('#first_molding_id').val('');
                    formModal.firstMolding.find('#contact_lot_number').val('');
                    // formModal.firstMolding.find('#production_lot').val('');
                    formModal.firstMolding.find('#shift').val('');
                    formModal.firstMolding.find('#remarks').val('');
                    formModal.firstMolding.find('#dieset_no').val('');
                    formModal.firstMolding.find('#production_lot_extension').val('');
                    formModal.firstMolding.find('#pmi_po_no').val('');
                    formModal.firstMolding.find('#machine_no').val('');
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
                    formModal.firstMolding.find('#virgin_material').val('');
                    formModal.firstMolding.find('#material_yield').val('0%');
                    formModal.firstMolding.find('[type="number"]').val(0);
                    $('#global_po_no').val('');
                    $('#global_po_qty').val('');
                    $('#global_target_qty').val('');

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
                    formModal.firstMoldingStation.find('#isSelectCameraInspection').addClass('d-none',true);
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
                $('#modalMaterialLotNum').on('shown.bs.modal', function () {
                    $('#txtLotNum').focus();
                    const mdlScanQrCode = document.querySelector("#modalMaterialLotNum");
                    const inptQrCode = document.querySelector("#txtLotNum");
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

                //txtLotNum mdlScanQrCodeFirstMoldingMaterial
                dt.firstMolding = table.FirstMoldingDetails.DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "ajax" : {
                        url: "load_first_molding_details",
                        data: function (param){
                            param.first_molding_device_id = $("#global_device_name").val();
                            param.global_po_no = $("#global_po_no").val();
                        }
                    },
                    fixedHeader: true,
                    "columns":[
                        { "data" : "action", orderable:false, searchable:false },
                        { "data" : "status" },
                        { "data" : "pmi_po_no" },
                        { "data" : "po_no" },
                        { "data" : "device_name" },
                        { "data" : "contact_name" },
                        { "data" : "contact_lot_number" },
                        { "data" : "prodn_lot_number" },
                        { "data" : "prodn_output" },
                        { "data" : "remarks" },
                        { "data" : "date_created"},
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
                        { "data" : "stations" },
                        { "data" : "date" },
                        { "data" : "operator_names" },
                        { "data" : "size_category" },
                        { "data" : "input" },
                        { "data" : "ng_qty" },
                        { "data" : "output" },
                        { "data" : "remarks" },
                        { "data" : "date_created" },
                    ],
                    // footerCallback: function (row, data, start, end, display) {
                    //     let api = this.api();

                    //     let countNGQuantity = 0;
                    //     let countVisualInspectionQuantity = 0;
                    //     if(data.length > 0){
                    //         for (let index = 0; index < data.length; index++) {
                    //             console.log('station', data[index].station);
                    //             countNGQuantity += parseInt(data[index].ng_qty);
                    //             if(data[index].station == 5){ //nmodify Station is equal Visual Inspection || Camera Inspection
                    //                 countVisualInspectionQuantity += parseInt(data[index].output);
                    //                 console.log('visual output ', data[index].output);
                    //             }
                    //         }
                    //     }
                    //     $(api.column(5).footer()).html(`${countNGQuantity}`)
                    //     $(api.column(6).footer()).html(`${countVisualInspectionQuantity}`)
                    // }
                });

                table.FirstMoldingDetails.on('click','#btnEditFirstMolding', editFirstMolding);
                table.FirstMoldingDetails.on('click','#btnViewFirstMolding', editFirstMolding);
                table.FirstMoldingStationDetails.on('click','#btnEditFirstMoldingStation', editFirstMoldingStation);
                table.FirstMoldingStationDetails.on('click','#btnViewFirstMoldingStation', editFirstMoldingStation);

                table.FirstMoldingStationDetails.on('click','#btnDeleteFirstMoldingStation', function (){
                    let first_molding_detail_id = $(this).attr('first-molding-station-id');

                    Swal.fire({
                        // title: "Are you sure?",
                        text: "Are you sure you want to delete this process?",
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

                table.FirstMoldingStationDetails.on('click', '#btnPrintFirstMoldingStation', function(e){
                    e.preventDefault();
                    let firstMoldingStationId = $(this).attr('first-molding-station-id');
                    $.ajax({
                        type: "get",
                        url: "get_first_molding_station_qr_code",
                        data: {"first_molding_detail_id" : firstMoldingStationId},
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
                    let global_po_no = $('#global_po_no').val();
                    let global_po_qty = $('#global_po_qty').val();
                    let global_target_qty = $('#global_target_qty').val();
;

                    if(global_target_qty == '' || global_po_no == '' || global_target_qty == ''){
                        toastr.error('PO not Found. Please check this PO Number to MIMF Module !');
                        return;
                    }
                    // $.ajax({
                    //     type: "GET",
                    //     url: "validate_total_target_output_by_po",
                    //     data: "data",
                    //     dataType: "json",
                    //     success: function (response) {
                    //         console.log(response);
                    //     }
                    // });

                    dt.firstMoldingStation.draw()
                    $('#modalFirstMolding').modal('show');
                    $('#btnFirstMoldingStation').prop('disabled',true);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                    $('#btnRuncardDetails').removeClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);

                    arr.Ctr = 0;
                    getDiesetDetailsByDeviceName(device_name);

                    // OPERATOR SHIFT
                    $time_now = moment().format('HH:mm:ss');
                    if($time_now >= '7:30:00' || $time_now <= '19:29:00'){
                        $('#shift').val('A');
                    }
                    else{
                        $('#shift').val('B');
                    }
                });

                $('#btnFirstMoldingStation').click(function (e) {
                    e.preventDefault();
                    let elementId = formModal.firstMoldingStation.find('#operator_name');

                    $('#buttonFirstMoldingStation').prop('disabled',false);

                    formModal.firstMoldingStation.find('#buttonAddFirstMoldingModeOfDefect').prop('disabled',true);
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
                        text: "Are you sure you want to submit this process ?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            /*
                            TODO : Scan Emp ID
                            */
                            firstMoldingUpdateStatus();
                        }
                    });
                });

                formModal.firstMoldingStation.find('#is_partial').change(function (e) {
                    e.preventDefault();
                    if ($(this).prop('checked')) {
                        formModal.firstMoldingStation.find('#input').prop('readonly',false);
                    }else{
                        formModal.firstMoldingStation.find('#input').prop('readonly',true);
                    }
                });

                $('#tableFirstMoldingStationMOD').on('keyup','.textMODQuantity', function (e) {
                    let totalNumberOfMOD = 0;
                    let ngQty = formModal.firstMoldingStation.find('#ng_qty').val();

                    $('#tableFirstMoldingStationMOD .textMODQuantity').each(function() {
                        if($(this).val() === null || $(this).val() === ""){
                            $("#labelTotalNumberOfNG").text(parseInt(0));
                        }
                        totalNumberOfMOD += parseInt($(this).val());
                    });
                    getValidateTotalNgQty (ngQty,totalNumberOfMOD);
                });

                // formModal.firstMolding.find('#pmi_po_no').on('keydown',function (e) {
                //     if(e.keyCode == 13){
                //         e.preventDefault();
                //         let  deviceId = formModal.firstMolding.find('#first_molding_device_id').val();
                //         getPmiPoReceivedDetails( $(this).val(),deviceId);
                //     }
                // });

                $('#global_po_no').on('keydown',function (e) { //nmodify
                    if(e.keyCode == 13){
                        e.preventDefault();
                        let  deviceId = formModal.firstMolding.find('#first_molding_device_id').val();
                        getPmiPoReceivedDetails( $(this).val(),deviceId);
                        dt.firstMolding.draw();
                    }
                });

                $('#btnScanQrFirstMolding').click(function (e) {
                    $('#mdlScanQrCodeFirstMolding').modal('show');
                    $('#mdlScanQrCodeFirstMolding').on('shown.bs.modal');
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

                            // $('#tblFirstMoldingDetails').empty();
                            dt.firstMolding.draw();
                            $('#btnAddFirstMolding').prop('disabled',false);
                            $('#global_contact_name').val(contact_name);
                            $('#global_input_device_name').val(device_name);
                            formModal.firstMolding.find('#first_molding_device_id').html(`<option value="${first_molding_device_id}">${device_name}</option>`);
                            formModal.firstMolding.find('#contact_name').val(contact_name);

                            $('#global_po_no').val('');
                            $('#global_target_qty').val('');
                            $('#global_po_no').val('');
                            dt.firstMolding.draw();

                            // getDiesetDetailsByDeviceName(device_name);
                            getMachineFromMaterialProcess(formModal.firstMolding.find('#machine_no'),device_name);
                            getStation (formModal.firstMoldingStation.find('#station'),device_name)
                        }
                    });
                });

                $('#txtScanQrCodeFirstMolding').on('keyup', function(e){
                    let scanFirstMoldingContactLotNo = $(this).val()
                    let firstMoldingDeviceId = formModal.firstMolding.find('#first_molding_device_id').val()
                    try {
                        if(e.keyCode == 13){
                            validateScanFirstMoldingContactLotNum(scanFirstMoldingContactLotNo,firstMoldingDeviceId);
                        }
                    } catch (error) {
                        // console.log(error);
                        toastr.error(`${scanFirstMoldingContactLotNo} Invalid Prodn Lot Number. Please Check to 2nd Stamping Module !`)
                        $('#txtScanQrCodeFirstMolding').val('');
                        $('#mdlScanQrCodeFirstMolding').modal('hide');
                    }
                });

                $('#txtLotNum').on('keyup', function(e){
                    try {
                            if(e.keyCode == 13){
                            let scanFirstMoldingMaterialLotNo = $(this).val()
                            let arrFirstMoldingMaterialLotNo = scanFirstMoldingMaterialLotNo.split("|");

                            $('#virgin_material').val(arrFirstMoldingMaterialLotNo[0]);
                            $(this).val('');
                            $('#modalMaterialLotNum').modal('hide');
                        }
                    }catch (error) {
                        console.log(error);
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

                formModal.firstMolding.find('.sumTotalMachineOutput').keyup(function (e) {
                    let arr = document.getElementsByClassName('sumTotalMachineOutput');
                    inputTotalMachineOuput=0;
                    for(let i=0;i<arr.length;i++){
                        if(parseFloat(arr[i].value))
                            inputTotalMachineOuput += parseFloat(arr[i].value);
                    }
                    formModal.firstMolding.find('#total_machine_output').val(inputTotalMachineOuput);
                });

                $('#txtScanUserId').on('keyup', function(e){
                    if(e.keyCode == 13){
                        // console.log($(this).val());
                        validateUser($(this).val(), [0], function(result){
                            if(result == true){
                                saveFirstMolding();
                            }
                            else{ // Error Handler
                                toastr.error('User not authorize!');
                            }
                        });
                        $(this).val('');
                    }
                });

                formModal.firstMolding.submit(function (e) {
                    e.preventDefault();
                    $('#modalScanQRSave').modal('show');

                });

                formModal.firstMoldingStation.submit(function (e) {
                    e.preventDefault();
                    savefirstMoldingStation();
                });

                $('#btnScanQrFirstMoldingVirginMaterial').click(function (e){
                    e.preventDefault();
                    $('#modalMaterialLotNum').modal('show');
                });

                formModal.firstMolding.on('keydown', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                    }
                });

                formModal.firstMoldingStation.find('#station').change(function (e) {
                    e.preventDefault();
                    let stationId = $(this).val();
                    fnIsSelectCameraInspection(stationId);
                    // alert('dsad')
                    console.log(stationId);
                });

            });
        </script>
    @endsection
@endauth
