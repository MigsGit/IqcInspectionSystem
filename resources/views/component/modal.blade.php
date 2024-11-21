<!-- MODALS -->
<div class="modal fade" id="modalSaveIqcInspection" tabindex="-1" role="dialog" aria-hidden="true"  data-bs-backdrop="static">
    <div class="modal-dialog modal-xl-custom">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fa fa-edit"></i> IQC Inspection</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formSaveIqcInspection" autocomplete="off">
                @csrf
                <div class="modal-body modal-body-custom">
                    <div class="row d-none">
                        <div class="col-sm-6 mt-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">WHS TRANSACTION ID (Rapid)</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="whs_transaction_id" name="whs_transaction_id">
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Receiving Details ID (Rapidx)</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="receiving_detail_id" name="receiving_detail_id">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">IQC Inspection ID</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="iqc_inspection_id" name="iqc_inspection_id">
                            </div>
                        </div>
                    </div>
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
                                <input type="text" class="form-control form-control-sm" id="invoice_no" name="invoice_no" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Part Code</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="partcode" name="partcode" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Part Name</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="partname" name="partname" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Supplier</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="supplier" name="supplier" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Family</span>
                                </div>
                                <select class="form-select form-control" id="family" name="family" >
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Application Ctrl. No.</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="app_no" name="app_no">
                                <input type="text" class="form-control form-control-sm" id="app_no_extension" name="app_no_extension">
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Die No.</span>
                                </div>
                                {{-- <input type="text" class="form-control form-control-sm" id="die_no" name="die_no"> --}}
                                <select class="form-select form-control-sm" id="die_no" name="die_no">

                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Quantity</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="total_lot_qty" name="total_lot_qty"  min="0" step="0.01" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Lot No.</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="lot_no" name="lot_no" readonly>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Inspection Classification</span>
                                </div>
                                <!--NOTE: Get all classification in Rapid/Warehouse Transaction, this field must be the same-->
                                <select class="form-select form-control-sm" id="classification" name="classification">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">PPD-Molding Plastic Resin</option>
                                    <option value="2">PPD-Molding Metal Parts</option>
                                    <option value="3">For grinding</option>
                                    <option value="4">PPD-Stamping</option>
                                    <option value="5">YEC - Stock</option>
                                </select>
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
                                    <select class="form-select form-control-sm" id="type_of_inspection" name="type_of_inspection">
                                        <option value="" selected disabled>-Select-</option>
                                        <option value="1">Single</option>
                                        <option value="2">Double</option>
                                        <option value="3">Label Check</option>
                                    </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Severity of Inspection</span>
                                </div>
                                <select class="form-select form-control-sm" id="severity_of_inspection" name="severity_of_inspection">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">Normal</option>
                                    <option value="2">Tightened</option>
                                    <option value="3">Label Check</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Inspection Level</span>
                                </div>
                                <select class="form-select form-control-sm" id="inspection_lvl" name="inspection_lvl"></select>

                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">AQL</span>
                                </div>
                                    {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                <select class="form-select form-control-sm" id="aql" name="aql"></select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Accept</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="accept" name="accept" min="0" step="0.01">

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Reject</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="reject" name="reject" min="0" step="0.01">
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
                                <input type="date" class="form-control form-control-sm" id="date_inspected" name="date_inspected" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Shift</span>
                                </div>
                                <select class="form-select form-control-sm" id="shift" name="shift">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">A</option>
                                    <option value="2">B</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-30">
                                    <span class="input-group-text w-100" id="basic-addon1">Time Inspected</span>
                                </div>
                                <input type="time" class="form-control form-control-sm" id="time_ins_from" name="time_ins_from" readonly>
                                <div class="input-group-prepend w-30">
                                    <span class="input-group-text w-100" id="basic-addon1">-</span>
                                </div>
                                <input type="time" class="form-control form-control-sm" id="time_ins_to" name="time_ins_to">
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Inspector</span>
                                </div>
                                {{-- @php echo $_SESSION['rapidx_name']; @endphp --}}
                                <input class="form-control" value= "<?php echo htmlspecialchars($_SESSION['rapidx_user_id']); ?>" type="hidden" name="inspector" id="inspector">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="inspector_name"><?php echo htmlspecialchars($_SESSION['rapidx_name']); ?></span>
                                </div>
                            
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Submission</span>
                                </div>
                                <select class="form-select form-control-sm" id="submission" name="submission">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">1st</option>
                                    <option value="2">2nd</option>
                                    <option value="3">3rd</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Category</span>
                                </div>
                                <select class="form-select form-control-sm" id="category" name="category">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">Old</option>
                                    <option value="2">New</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Target LAR</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="target_lar" name="target_lar" min="0" min="0" readonly>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Target DPPM</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="target_dppm" name="target_dppm" min="0" min="0" readonly>

                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Remarks</span>
                                </div>
                                <input type="text" class="form-control form-control-sm" id="remarks" name="remarks">
                            </div>
                        </div>
                        <div class="col-sm-6 mt-3">

                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Lot Inspected</span>
                                </div>
                                    {{-- <input type="text" class="form-control form-control-sm" id="txtInput" name="input" min="0" value="0"> --}}
                                <input type="number" class="form-control form-control-sm" id="lot_inspected" name="lot_inspected" min="0" step="0.01" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Lot Accepted</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="accepted" name="accepted" min="0" max="1" step="0.01">
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Sampling Size</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="sampling_size" name="sampling_size" min="0" step="0.01">
                            </div>
                            <div class="input-group input-group-sm mb-3 d-none divMod">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">No. of Defectives</span>
                                </div>
                                <input type="number" class="form-control form-control-sm" id="no_of_defects" name="no_of_defects" min="0" step="0.01" placeholder="auto-compute" readonly>
                            </div>
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Judgement</span>
                                </div>
                                <select class="form-select form-control-sm" id="judgement" name="judgement">
                                    <option value="" selected disabled>-Select-</option>
                                    <option value="1">Accept</option>
                                    <option value="2">Reject</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm mb-3 d-none divMod">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Mode of Defect</span>
                                </div>
                                <button type="button" class="form-control form-control-sm bg-warning" id="btnMod">Mode of Defects</button>
                            </div>
                            <div class="input-group input-group-sm mb-3 none" id="fileIqcCocUpload">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">COC File</span>
                                </div>
                                <input type="file" class="form-control form-control-sm" id="iqc_coc_file" name="iqc_coc_file" accept=".pdf">
                                {{-- &nbsp;&nbsp; <a href="#" id="iqc_coc_file_download" class="link-primary"> <i class="fas fa-file"></i> Click to download attachment</a> --}}
                            </div>
                            <div class="input-group input-group-sm mb-3 none" id="fileIqcCocDownload">
                                <div class="input-group-prepend w-50">
                                    <span class="input-group-text w-100" id="basic-addon1">Attachment</span>
                                </div>
                                &nbsp;&nbsp; <a href="#" id="iqc_coc_file_download" class="btn btn-primary disabled"> <i class="fas fa-file"></i> Click to download attachment</a>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="isUploadCoc" name="isUploadCoc">
                                <label class="form-check-label" for="isUploadCoc">
                                    Click to upload new attachment
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="row">
                        <div class="col">
                          <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-50">
                              <span class="input-group-text w-100" id="basic-addon1">Final Visual Operator</span>
                            </div>
                            <input type="text" class="form-control form-control-sm" id="operator_name" name="operator_name" readonly="true">
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
    </div>
</div>

<div class="modal" id="modal-loading" data-bs-keyboard="false" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
        <div class="modal-body text-center">
            <div class="loading-spinner mb-2"></div>
            <div>Loading</div>
        </div>
        </div>
    </div>
</div>

{{-- Modal Scan Lot Number --}}
<div class="modal fade" id="modalLotNum" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
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
