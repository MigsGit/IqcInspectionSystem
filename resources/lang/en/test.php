<div id="modalEditInspection" class="modal fade" role="dialog" data-backdrop="static">
            <div class="modal-dialog gray-gallery modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">IQC Inspection Result</h4>
                    </div>
                    <form class=form-horizontal id="frm_iqc_inspection">
                        {{ csrf_field() }}
                         <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="classification_manual" style="display:none">
                                        <label class="control-label col-sm-3">Classification</label>
                                        <div class="col-sm-9">
                                            <select class="form-control input-sm clear" id="classification" name="classification">
                                                <option value=""></option>
                                                <option value="Visual Inspection" selected>Visual Inspection (Temporary Invoice)</option>
                                                <option value="Pkg. & Raw Material">Packaging & Raw Material</option>
                                                <option value="Material Qualification">Material Qualification</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Invoice No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required input-sm clear" id="invoice_no" name="invoice_no">
                                            <input type="hidden" class="form-control input-sm clear" id="iqc_result_id" name="iqc_result_id">
                                            {{-- <input type="hidden" class="form-control input-sm clear" id="classification" name="classification" value="Visual Inspection"> --}}
                                            <div id="er_invoice_no" style="color: #f24848; font-weight: 900"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Part Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear" id="partcodelbl" name="partcodelbl" style="display: none">
                                            <select id="partcode" name="partcode" class="form-control input-sm clear partcode clearselect">
                                            </select>
                                            {{-- <input type="text" id="partcode" name="partcode" class="form-control input-sm clear clearselect"> --}}
                                            <!-- <select class="form-control required select2me input-sm clear" id="partcode" name="partcode">
                                            </select> -->
                                            <div id="er_partcode"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Part Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear" id="partname" name="partname">
                                            <div id="er_partname"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Supplier</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear" id="supplier" name="supplier" >
                                            <div id="er_supplier"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Application Date</label>
                                        <div class="col-sm-9">
                                            <input class="form-control input-sm clear" type="text" name="app_date" id="app_date" value="{{date('m/d/Y')}}"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Application Time</label>
                                        <div class="col-sm-9">
                                            <input type="text" data-format="h:m A" class="form-control input-sm clear" name="app_time" id="app_time" value="{{date('h:i A')}}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Application Ctrl No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required input-sm clear" id="app_no" name="app_no">
                                            <div id="er_app_no"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Lot No.</label>
                                        <div class="col-sm-9">
                                            <select id="lot_no" name="lot_no[]" multiple="multiple" class="form-control required input-sm clear lot_no clearselect">
                                            </select>
                                            {{-- <input type="text" name="lot_no" id="lot_no" class="form-control required input-sm lot_no clear clearselect"> --}}
                                            <!-- </select> -->
                                            <div id="er_lot_no"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Lot Quantity</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear" id="lot_qty" name="lot_qty">
                                            <div id="er_lot_qty"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-sm-12">
                                    <strong>Sampling Plan</strong>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Type of Inspection</label>
                                        <div class="col-sm-9">
                                            <select id="type_of_inspection" name="type_of_inspection" class="form-control required input-sm clear type_of_inspection clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="type_of_inspection" id="type_of_inspection"> --}}
                                            <div id="er_type_of_inspection"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Severity of Inspection</label>
                                        <div class="col-sm-9">
                                            <select id="severity_of_inspection" name="severity_of_inspection" class="form-control required input-sm clear severity_of_inspection clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="severity_of_inspection" id="severity_of_inspection"> --}}
                                            <div id="er_severity_of_inspection"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Inspection Level</label>
                                        <div class="col-sm-9">
                                            <select id="inspection_lvl" name="inspection_lvl" class="form-control required input-sm clear clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="inspection_lvl" id="inspection_lvl"> --}}
                                            <div id="er_inspection_lvl"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">AQL</label>
                                        <div class="col-sm-9">
                                            <select id="aql" name="aql" class="form-control required input-sm clear aql clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="aql" id="aql"> --}}
                                            <div id="er_aql"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Accept</label>
                                        <div class="col-sm-9">
                                            <input type="number" min="0" max="1" class="form-control input-sm clear actual" id="accept" name="accept">
                                            <div id="er_accept"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Reject</label>
                                        <div class="col-sm-9">
                                            <input type="number" min="0" max="1" class="form-control input-sm clear actual" id="reject" name="reject">
                                            <div id="er_reject"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-sm-12">
                                    <strong>Visual Inspection</strong>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Date Inspected</label>
                                        <div class="col-sm-9">
                                            <input class="form-control required input-sm clear date-picker actual" type="text" name="date_inspected" id="date_inspected" data-date-format='yyyy-mm-dd'/>
                                            <div id="er_date_ispected"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">WW#</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control input-sm clear actual" id="ww" name="ww">
                                            <div id="er_ww"></div>
                                        </div>
                                        <label class="control-label col-sm-3">FY#</label>
                                        <div class="col-sm-3">
                                            <input type="text" class="form-control input-sm clear actual" id="fy" name="fy" readonly>
                                            <div id="er_fy"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Time Inspected</label>
                                        <div class="col-sm-4">
                                            <input type="text" data-format="hh:mm A" class="form-control required input-sm actual" name="time_ins_from" id="time_ins_from" value="{{date('H:i A')}}"/> {{-- timepicker timepicker-no-seconds --}}
                                            <div id="er_time_ins_from"></div>
                                        </div>
                                        <div class="col-sm-1"></div>
                                        <div class="col-sm-4">
                                            <input type="text" data-format="hh:mm A" class="form-control required input-sm  actual" name="time_ins_to" id="time_ins_to" value="{{date('H:i A')}}"/> {{-- timepicker timepicker-no-seconds --}}
                                            <div id="er_time_ins_to"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Shift</label>
                                        <div class="col-sm-9">
                                            <select id="shift" name="shift" class="form-control required input-sm clear shift clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="shift" id="shift"> --}}
                                            <div id="er_shift"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Inspector</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required input-sm actual" id="inspector" name="inspector" value="{{ Auth::user()->user_id }}">
                                            <div id="er_inspector"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Submission</label>
                                        <div class="col-sm-9">
                                            <select id="submission" name="submission" class="form-control required input-sm clear submission clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required input-sm clearselect show-tick actual" name="submission" id="submission"> --}}
                                            <div id="er_submission"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Judgement</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear actual" id="judgement" name="judgement" readonly>
                                            <div id="er_judgement"></div>
                                            <!-- <label class="text-success" id="msg_special_accept" style="margin-top:10px;" hidden>Special Accept</label> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Lot Inspected</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required input-sm clear actual" id="lot_inspected" name="lot_inspected">
                                            <div id="er_lot_inspected"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Lot Accepted</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control required input-sm clear actual" id="lot_accepted" name="lot_accepted">
                                            <div id="er_lot_accepted"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Sample Size</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear actual" id="sample_size" name="sample_size" readonly>
                                            <div id="er_sample_size"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" id="no_defects_label">No. of Defectives</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear actual" id="no_of_defects" name="no_of_defects">
                                            <div id="er_no_of_defects"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">Remarks</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear actual" id="remarks" name="remarks">
                                            <input type="hidden" class="form-control input-sm clear" id="inspectionstatus" name="inspectionstatus">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-3" id="mode_defects_label">Mode of Defects</label>
                                        <div class="col-sm-4">
                                            <button type="button" class="btn blue btn_mod_ins" id="btn_mod_ins">
                                                <i class="fa fa-plus-circle"></i> Add Mode of Defects
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <input type="hidden" name="save_status" id="save_status">
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <hr>

                            <div class="row ngr_details">
                                <div class="col-sm-12">
                                    <strong>NGR Details</strong>
                                </div>
                            </div>

                            <div class="row ngr_details">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-3">NGR Status</label>
                                        <div class="col-sm-9">
                                            <select id="status_NGR" name="status_NGR" class="form-control required_ngr input-sm clear status_NGR clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required_ngr input-sm clearselect show-tick actual" name="status_NGR" id="status_NGR"> --}}
                                            <div id="er_status_NGR"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3">NGR Disposition</label>
                                        <div class="col-sm-9">
                                            <select id="disposition_NGR" name="disposition_NGR" class="form-control required_ngr input-sm clear disposition_NGR clearselect actual">
                                            </select>
                                            {{-- <input type="text" class="form-control required_ngr input-sm clearselect show-tick actual" name="disposition_NGR" id="disposition_NGR"> --}}
                                            <div id="er_disposition_NGR"></div>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label col-sm-3" id="no_defects_label">NGR Control No.</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control input-sm clear required_ngr actual" id="control_no_NGR" name="control_no_NGR">
                                            <div id="er_control_no_NGR"></div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-3">NGR Issued Date</label>
                                        <div class="col-sm-9">
                                            <input class="form-control required_ngr input-sm clear date-picker actual" type="text" name="date_NGR" id="date_NGR" data-date-format='yyyy-mm-dd'/>
                                            <div id="er_date_NGR"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="inv_id" id="inv_id">
                            <input type="hidden" name="mr_id" id="mr_id">

                            <button type="button" class="btn btn-primary ngr_buttons" id="btn_special_accept" style="background-color:#00ff00; display: none;"><i class="fa fa-check-circle-o"></i>Special Accept</button>
                            <button type="button" class="btn btn-primary ngr_buttons" id="btn_sorting" style="background-color:#ff9933; display: none;"><i class="fa fa-sort"></i>Sorting</button>
                            <button type="button" class="btn btn-primary" id="btn_sorting_details" style="background-color:#ff9933; display: none;"><i class="fa fa-sort"></i>Sorting Details</button>
                            <button type="button" class="btn btn-primary ngr_buttons" id="btn_rework" style="background-color:#ff33cc; display: none;"><i class="fa fa-recycle"></i>Rework</button>
                            <button type="button" class="btn btn-primary" id="btn_rework_details" style="background-color:#ff33cc; display: none;"><i class="fa fa-recycle"></i>Rework Details</button>
                            <button type="button" class="btn btn-primary ngr_buttons" id="btn_rtv" style="background-color:#ff0000; display: none;"><i class="fa fa-truck"></i>RTV</button>
                            <button type="button" class="btn btn-primary" id="btn_rtv_details" style="background-color:#ff0000; display: none;"><i class="fa fa-truck"></i>RTV</button>
                            <button type="button" onclick="javascript:saveInspection();" class="btn btn-success" id="btn_savemodal"><i class="fa fa-floppy-disk-o"></i>Save</button>
                            <button type="button" class="btn grey-gallery" id="btn_clearmodal"><i class="fa fa-eraser"></i>Clear</button>
                            <a href="javascript:;" data-dismiss="modal"  class="btn btn-danger btn_backModal"><i class="fa fa-reply"></i>Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
