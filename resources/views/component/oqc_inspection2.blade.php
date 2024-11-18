@php $layout = 'layouts.admin_layout'; @endphp
@auth
    @extends($layout)
    @section('title', 'Material Process')
    @section('content_page')
        <style type="text/css">
            table.table tbody td{
                padding: 4px 4px;
                margin: 1px 1px;
                font-size: 16px;
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

        <div class="content-wrapper"> <!-- Content Wrapper. Contains page content -->
            <section class="content-header"> <!-- Content Header (Page header) -->
                <div class="container-fluid"><!-- Container-fluid -->
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>OQC Inspection</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">OQC Inspection</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.Container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content"><!-- Content -->
                <div class="container-fluid"><!-- Container-fluid -->
                    <div class="row"><!-- Row -->
                        <div class="col-12"><!-- Col -->
                            <div class="card card-dark"><!-- General form elements -->
                                <div class="card-header">
                                    <h3 class="card-title">OQC Table</h3>
                                </div>

                                <!-- Start Search PO No. -->
                                <div class="row p-3">
                                    <div class="col-3">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <button type="button" class="btn btn-dark" id="btnScanPo" data-toggle="modal" data-target="#mdlScanQrCode"><i class="fa fa-qrcode w-100"></i></button>
                                            </div>
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>PO No.:</strong></span>
                                            </div>
                                            <input type="search" class="form-control" id="txtPoNumber" placeholder="---------------" readonly>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><Strong>Device Name:</Strong></span>
                                            </div>
                                            <input type="text" class="form-control" id="txtDeviceName" placeholder="---------------" readonly>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="input-group input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><strong>Po Qty:</strong></span>
                                            </div>
                                            <input type="text" class="form-control" id="txtPoQuantity" placeholder="---------------" readonly>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Search PO No. -->

                                <div class="card-body"><!-- Start Page Content -->
                                    <div style="float: right;">
                                    </div>
                                    <div class="table-responsive"><!-- Table responsive -->
                                        <table id="tblOqcInspection" class="table table-sm table-bordered table-striped table-hover"
                                            style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Action</th>
                                                    <th>P.O No.</th>
                                                    <th>FY-WW</th>
                                                    <th>Date Inspected</th>
                                                    <th>Device Name</th>
                                                    <th>From</th>
                                                    <th>To</th>
                                                    <th># of Sub</th>
                                                    <th>Lot Size</th>
                                                    <th>Sample Size</th>
                                                    <th>No. of Detective</th>
                                                    <th>Lot No.</th>
                                                    <th>Mode of Defects</th>
                                                    <th>Qty</th>
                                                    <th>Judgement</th>
                                                    <th>Inspector</th>
                                                    <th>Remarks</th>
                                                    <th>Family</th>
                                                    <th>Updated By</th>
                                                    <th>Update Date</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div><!-- /.Table responsive -->
                                </div><!-- /.End Page Content -->
                            </div><!-- /.Card -->
                        </div><!-- /.Col -->
                    </div><!-- /.Row -->
                </div><!-- /.Container-fluid -->
            </section><!-- /.Content -->
        </div><!-- /.Content-wrapper -->

        <!-- Start Scan QR Modal -->
        <div class="modal fade" id="mdlScanQrCode" data-formid="" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 pb-0">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body pt-0">
                        <input type="text" class="scanner w-100" id="txtScanQrCode" name="scan_qr_code" autocomplete="off">
                        <div class="text-center text-secondary">Please scan the code.<br><br><h1><i class="fa fa-qrcode fa-lg"></i></h1></div>
                    </div>
                </div>
            </div>
        </div><!-- /.End Scan QR Modal -->
    @endsection

    @section('js_content')
        <script type="text/javascript">
            let dataTableOQCInspection;

            $(document).ready(function() {
                $('#btnScanPo').on('click', function(e){
                    e.preventDefault();
                    $('#mdlScanQrCode').modal('show');
                    $('#mdlScanQrCode').on('shown.bs.modal', function () {
                        $('#txtScanQrCode').focus();
                        const mdlScanQrCodeOqcInspection = document.querySelector("#mdlScanQrCode");
                        const inptQrCodeOqcInspection = document.querySelector("#txtScanQrCode");
                        let focus = false;

                        mdlScanQrCodeOqcInspection.addEventListener("mouseover", () => {
                            if (inptQrCodeOqcInspection === document.activeElement) {
                                focus = true;
                            } else {
                                focus = false;
                            }
                        });

                        mdlScanQrCodeOqcInspection.addEventListener("click", () => {
                            if (focus) {
                                inptQrCodeOqcInspection.focus()
                            }
                        });
                    });
                });

                $('#txtScanQrCode').on('keypress',function(e){
                    if( e.keyCode == 13 ){
                        let scanQrCode = $('#txtScanQrCode').val();
                            splitQrCodeData = scanQrCode.split(' ').filter(Boolean);
                            console.log('Get QR Code Data:', splitQrCodeData[0]);
                            // searchPoDetails(splitQrCodeData[0]);
                        $('#mdlScanQrCode').modal('hide');

                        // ======================= START DLABEL DATA TABLE =======================
                        dataTableOQCInspection = $("#tblOqcInspection").DataTable({
                            "processing"    : false,
                            "serverSide"    : true,
                            "destroy"       : true,
                            "ajax" : {
                                url: "view_oqc_inspection",
                                data: function (pamparam){
                                    pamparam.poNo = splitQrCodeData[0];
                                },
                            },

                            "columns":[
                                { "data" : "action", orderable:false, searchable:false },
                                { "data" : "po_number" },
                                { "data" : "fy_ww" },
                                { "data" : "date_inspected" },
                                { "data" : "device_name" },
                                { "data" : "time_ins_from" },
                                { "data" : "time_ins_to" },
                                { "data" : "submission" },
                                { "data" : "lot_qty" },
                                { "data" : "sample_size" },
                                { "data" : "num_of_defects" },
                                { "data" : "lot_no" },
                                { "data" : "mod" },
                                { "data" : "po_qty" },
                                { "data" : "judgement" },
                                { "data" : "inspector" },
                                { "data" : "remarks" },
                                { "data" : "family" },
                                { "data" : "update_user" },
                                { "data" : "updated_at" }
                            ],
                            "columnDefs": [
                                // { className: "align-center", targets: [1, 2] },
                            ],
                        });// END DLABEL DATA TABLE
                    }
                });
            });

        </script>
    @endsection
@endauth
