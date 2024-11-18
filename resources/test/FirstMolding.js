// $(document).ready(function () {
    var dt = {
        firstMolding : "",
        firstMoldingStation : "",
    }

    var formModal = {
        firstMolding  : $("#formFirstMolding"),
        firstMoldingStation  : $("#formFirstMoldingStation"),
    }

    var table = {
        FirstMoldingDetails: $("#tblFirstMoldingDetails"),
        FirstMoldingStationDetails: $("#tblFirstMoldingStationDetails"),
    }

    var arr = {
        Ctr : 0,
    }

    const getFirstModlingDevices = function (){

        $.ajax({
            type: "GET",
            url: "get_first_molding_devices",
            data: "data",
            dataType: "json",
            success: function (response) {
                let first_molding_device_id = response['id'];
                let device_name = response['value'];
                let result = '';

                if(response['id'].length > 0){
                    result = '<option selected disabled> --- Select --- </option>';
                    for(let index = 0; index < first_molding_device_id.length; index++){
                        result += '<option value="' +first_molding_device_id[index]+'">'+device_name[index]+'</option>';
                    }
                }
                else{
                    result = '<option value="0" selected disabled> No record found </option>';
                }
                $('select[name="global_device_name"]').html(result);
            }
        });
    }

    const editFirstMolding = function (){
        let first_molding_id = $(this).attr('first-molding-id');
        let view_data = $(this).attr('view-data');
        $.ajax({
            type: "GET",
            url: "get_molding_details",
            data: {"first_molding_id" : first_molding_id},
            dataType: "json",
            success: function (response) {

                let data = response['first_molding'][0];
                let first_molding_material_list = data.first_molding_material_lists;

                for (let i = 0; i < first_molding_material_list.length; i++) {
                    console.log(first_molding_material_list[i].virgin_material);
                    let rowFirstMoldingMaterial = `
                        <tr>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-dark" id="btnScanQrFirstMoldingVirginMaterial_${arr.Ctr}" btn-counter = "${arr.Ctr}"><i class="fa fa-qrcode w-100"></i></button>
                                        </div>
                                        <input value="${first_molding_material_list[i].virgin_material}" type="text" class="form-control form-control-sm" id="virgin_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_material[]" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="${first_molding_material_list[i].virgin_qty}" type="number" class="form-control form-control-sm inputVirginQty" id="virgin_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_qty[]" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                                        </div>
                                        <input value="${first_molding_material_list[i].recycle_material}" type="text" class="form-control form-control-sm" id="recycle_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_material[]" required>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm mb-3">
                                    <input value="${first_molding_material_list[i].recycle_qty}" type="number" class="form-control form-control-sm" id="recycle_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_qty[]" required>
                                </div>
                            </td>
                            <td>
                                <center><button class="btn btn-danger buttonRemoveMaterial" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                            </td>
                        </tr>
                    `;
                    // let rowFirstMoldingMaterial = `
                    //     <tr>
                    //         <td>
                    //             <div class="input-group input-group-sm mb-3">
                    //                     <input value="${first_molding_material_list[i].virgin_material}" type="text" class="form-control form-control-sm" id="virgin_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_material[]" required>
                    //                     <div class="input-group-prepend">
                    //                         <button type="button" class="btn btn-dark" id="btnScanQrFirstMoldingVirginMaterial_${arr.Ctr}" btn-counter = "${arr.Ctr}"><i class="fa fa-qrcode w-100"></i></button>
                    //                     </div>
                    //                     <input value="${first_molding_material_list[i].virgin_material}" type="text" class="form-control form-control-sm" id="virgin_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_material[]" required>
                    //             </div>
                    //         </td>
                    //         <td>
                    //             <div class="input-group input-group-sm mb-3">
                    //                 <input value="${first_molding_material_list[i].virgin_qty}" type="number" class="form-control form-control-sm inputVirginQty" id="virgin_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="virgin_qty[]" required>
                    //             </div>
                    //         </td>
                    //         <td>
                    //             <div class="input-group input-group-sm mb-3">
                    //                     <div class="input-group-prepend">
                    //                         <button type="button" class="btn btn-dark" id="btnScanQrFirstMolding"><i class="fa fa-qrcode w-100"></i></button>
                    //                     </div>
                    //                     <input value="${first_molding_material_list[i].recycle_material}" type="text" class="form-control form-control-sm" id="recycle_material_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_material[]" required>
                    //             </div>
                    //         </td>
                    //         <td>
                    //             <div class="input-group input-group-sm mb-3">
                    //                 <input value="${first_molding_material_list[i].recycle_qty}" type="number" class="form-control form-control-sm" id="recycle_qty_${arr.Ctr}" input-counter ="${arr.Ctr}" name="recycle_qty[]" required>
                    //             </div>
                    //         </td>
                    //         <td>
                    //             <center><button class="btn btn-danger buttonRemoveMaterial" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                    //         </td>
                    //     </tr>
                    // `;
                    $("#tblFirstMoldingMaterial tbody").append(rowFirstMoldingMaterial);
                }


                formModal.firstMolding.find('#first_molding_id').val(data.id);
                formModal.firstMolding.find('#contact_lot_number').val(data.contact_lot_number);
                formModal.firstMolding.find('#production_lot').val(data.production_lot);
                formModal.firstMolding.find('#production_lot_extension').val(data.production_lot_extension);
                formModal.firstMolding.find('#machine_no').val(data.machine_no);
                formModal.firstMolding.find('#dieset_no').val(data.dieset_no);
                formModal.firstMolding.find('#drawing_no').val(data.drawing_no);
                formModal.firstMolding.find('#revision_no').val(data.revision_no);
                formModal.firstMolding.find('#target_shots').val(data.target_shots);
                formModal.firstMolding.find('#adjustment_shots').val(data.adjustment_shots);
                formModal.firstMolding.find('#qc_samples').val(data.qc_samples);
                formModal.firstMolding.find('#pmi_po_no').val(data.pmi_po_no);
                formModal.firstMolding.find('#po_no').val(data.po_no);
                formModal.firstMolding.find('#prod_samples').val(data.prod_samples);
                formModal.firstMolding.find('#ng_count').val(data.ng_count);
                formModal.firstMolding.find('#item_code').val(data.item_code);
                formModal.firstMolding.find('#total_machine_output').val(data.total_machine_output);
                formModal.firstMolding.find('#item_name').val(data.item_name);
                formModal.firstMolding.find('#po_qty').val(data.po_qty);
                formModal.firstMolding.find('#required_output').val(data.required_output);
                formModal.firstMolding.find('#created_at').val(data.created_at);
                formModal.firstMolding.find('#remarks').val(data.remarks);
                console.log(data.status)
                console.log(view_data);

                if(data.status === 0 || data.status === 3){
                    $('#btnFirstMoldingStation').prop('disabled',true);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                    $('#btnRuncardDetails').addClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').addClass('d-none',true);
                }else{
                    $('#btnFirstMoldingStation').prop('disabled',false);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',false);
                    $('#btnRuncardDetails').removeClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);
                }

                if(data.status === 1 || data.status === 3){
                    formModal.firstMolding.find('#shipment_output').val(data.shipment_output);
                    formModal.firstMolding.find('#material_yield').val(data.material_yield);
                }else{
                    formModal.firstMolding.find('#shipment_output').val(0);
                    formModal.firstMolding.find('#material_yield').val('0%');
                }

                if(view_data != undefined){
                    $('#btnFirstMoldingStation').prop('disabled',true);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                    $('#btnRuncardDetails').addClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').addClass('d-none',true);
                }else{
                    $('#btnFirstMoldingStation').prop('disabled',false);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',false);
                    $('#btnRuncardDetails').removeClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);
                }

                dt.firstMoldingStation.draw();
                $('#modalFirstMolding').modal('show');

                // tblFirstMoldingMaterial
            },error: function (data, xhr, status){
                toastr.error(`Error: ${data.status}`);
            }
        });
    }

    const getModeOfDefectForFirstMolding = (elementId, modeOfDefectId = null) => {
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            url: 'get_mode_of_defect_for_second_molding',
            method: 'get',
            dataType: 'json',
            beforeSend: function(){
                result = `<option value="0" selected disabled> - Loading - </option>`;
                elementId.html(result);
            },
            success: function(response){
                result = '';
                if(response['data'].length > 0){
                    for(let index = 0; index < response['data'].length; index++){

                        result += `<option value="${response['data'][index].id}">${response['data'][index].defects}</option>`;
                    }
                }
                else{
                    result = `<option value="0" selected disabled> - No data found - </option>`;
                }
                elementId.append(result);
                if(modeOfDefectId != null){
                    console.log(modeOfDefectId)
                    elementId.val(modeOfDefectId).trigger('change');
                }
            },
            error: function(data, xhr, status){
                result = `<option value="0" selected disabled> - Reload Again - </option>`;
                elementId.html(result);
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }

    const editFirstMoldingStation = function (){
        let first_molding_station_id = $(this).attr('first-molding-station-id');
        $.ajax({
            type: "GET",
            url: "get_first_molding_station_details",
            data: {"first_molding_station_id" : first_molding_station_id},
            dataType: "json",
            success: function (response) {
                let is_data_first_molding_detail_mod = response['first_molding_detail_mod'];
                let first_molding_station_detail = '';  

                /* When data first_molding_detail_mod exist read the multiple Mode Of Defects  */
                if( is_data_first_molding_detail_mod === undefined ){
                    first_molding_station_detail = response['first_molding_detail'][0];
                }else{
                    first_molding_station_detail = response['first_molding_detail_mod'][0].belongs_to_first_molding_detail;
                    let first_molding_detail_mod = response['first_molding_detail_mod'];
                    
                    for(let i = 0; i < first_molding_detail_mod.length; i++){
                        // console.log(first_molding_detail_mod[i]);
                        let defects = first_molding_detail_mod[i].defects_info.defects;
                        let info_defects_id = first_molding_detail_mod[i].defects_info.id;
                        let mod_quantity = first_molding_detail_mod[i].mod_quantity;
                        let rowModeOfDefect = `
                            <tr>
                                <td>
                                    <select class="form-control select2 select2bs4 selectMOD" name="mod_id[]">
                                        <option value="${info_defects_id}">${defects}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" class="form-control textMODQuantity" name="mod_quantity[]" value="${mod_quantity}" min="1">
                                </td>
                                <td>
                                    <center><button class="btn btn-xs btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                                </td>
                            </tr>
                        `;
                        console.log('dsad',info_defects_id);
                        // getModeOfDefectForFirstMolding($("#tableFirstMoldingStationMOD tr:last").find('.selectMOD'));
                        $("#tableFirstMoldingStationMOD tbody").append(rowModeOfDefect);
                    }

                    let totalNumberOfMOD = 0;

                    $("#labelTotalNumberOfNG").empty();

                    $('#tableFirstMoldingStationMOD .textMODQuantity').each(function() {
                        if($(this).val() === null || $(this).val() === ""){
                            $("#tableFirstMoldingStationMOD tbody").empty();
                            $("#labelTotalNumberOfNG").text(parseInt(0));
                        }
                        totalNumberOfMOD += parseInt($(this).val());
                    });

                    getValidateTotalNgQty(first_molding_station_detail.ng_qty,totalNumberOfMOD);
                }
                formModal.firstMoldingStation.find('#first_molding_id').val(first_molding_station_detail.id);
                formModal.firstMoldingStation.find('#first_molding_id').val(first_molding_station_detail.first_molding_id);
                formModal.firstMoldingStation.find('#first_molding_detail_id').val(first_molding_station_detail.id);
                formModal.firstMoldingStation.find('#date').val(first_molding_station_detail.date);
                formModal.firstMoldingStation.find('#operator_name').val(first_molding_station_detail.operator_name);
                formModal.firstMoldingStation.find('#input').val(first_molding_station_detail.input);
                formModal.firstMoldingStation.find('#ng_qty').val(first_molding_station_detail.ng_qty);
                formModal.firstMoldingStation.find('#output').val(first_molding_station_detail.output);
                formModal.firstMoldingStation.find('#station_yield').val(first_molding_station_detail.yield);
                formModal.firstMoldingStation.find('#remarks').val(first_molding_station_detail.remarks);

                setTimeout(() => {
                    formModal.firstMoldingStation.find('#station').val(first_molding_station_detail.station);
                }, 300);
                getStation();

                $('#modalFirstMoldingStation').modal('show');

            },error: function (data, xhr, status){
                toastr.error(`Error: ${data.status}`);
            }
        });
    }

    function saveFirstMolding(){
        $.ajax({
            type: "POST",
            url: "save_first_molding",
            data: formModal.firstMolding.serialize(),
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response['result'] === 1){
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Saved Successfully !",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    dt.firstMolding.draw();
                    $('#modalFirstMolding').modal('hide');
                }
            },error: function (data, xhr, status){
                if(data.status === 422){
                    let errors = data.responseJSON.errors ;
                    toastr.error(`Saving Failed, Please fill up all required fields`);
                    errorHandler( errors.first_molding_device_id,formModal.firstMolding.find('#first_molding_device_id') );
                    errorHandler( errors.contact_lot_number,formModal.firstMolding.find('#contact_lot_number') );
                    errorHandler( errors.production_lot,formModal.firstMolding.find('#production_lot') );
                    errorHandler( errors.production_lot_extension,formModal.firstMolding.find('#production_lot_extension') );
                    errorHandler( errors.machine_no,formModal.firstMolding.find('#machine_no') );
                    errorHandler( errors.dieset_no,formModal.firstMolding.find('#dieset_no') );
                    errorHandler( errors.drawing_no,formModal.firstMolding.find('#drawing_no') );
                    errorHandler( errors.revision_no,formModal.firstMolding.find('#revision_no') );
                    errorHandler( errors.target_shots,formModal.firstMolding.find('#target_shots') );
                    errorHandler( errors.adjustment_shots,formModal.firstMolding.find('#adjustment_shots') );
                    errorHandler( errors.qc_samples,formModal.firstMolding.find('#qc_samples') );
                    errorHandler( errors.prod_samples,formModal.firstMolding.find('#prod_samples') );
                    errorHandler( errors.pmi_po_no,formModal.firstMolding.find('#pmi_po_no') );
                    errorHandler( errors.po_no,formModal.firstMolding.find('#po_no') );
                    errorHandler( errors.ng_count,formModal.firstMolding.find('#ng_count') );
                    errorHandler( errors.item_code,formModal.firstMolding.find('#item_code') );
                    errorHandler( errors.total_machine_output,formModal.firstMolding.find('#total_machine_output') );
                    errorHandler( errors.item_name,formModal.firstMolding.find('#item_name') );
                    errorHandler( errors.shipment_output,formModal.firstMolding.find('#shipment_output') );
                    errorHandler( errors.shipment_output,formModal.firstMolding.find('#shipment_output') );
                    errorHandler( errors.po_qty,formModal.firstMolding.find('#po_qty') );
                    errorHandler( errors.material_yield,formModal.firstMolding.find('#material_yield') );
                    errorHandler( errors.required_output,formModal.firstMolding.find('#required_output') );
                }else{
                    toastr.error(`Error: ${data.status}`);
                }

            }
        });
    }

    const savefirstMoldingStation = function (){
        $.ajax({
            type: "POST",
            url: "save_first_molding_station",
            data: formModal.firstMoldingStation.serialize(),
            dataType: "json",
            success: function (response) {
                if(response['result'] === 1){
                    $('#modalFirstMoldingStation').modal('hide');
                    formModal.firstMoldingStation[0].reset();
                    dt.firstMoldingStation.draw();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Saved Successfully !",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },error: function (data, xhr, status){
                let errors = data.responseJSON.errors ;
                if(data.status === 422){
                    toastr.error(`Saving Failed, Please fill up all required fields`);
                    errorHandler( errors.first_molding_id,formModal.firstMoldingStation.find('#first_molding_id') );
                    errorHandler( errors.station,formModal.firstMoldingStation.find('#station') );
                    errorHandler( errors.date,formModal.firstMoldingStation.find('#date') );
                    errorHandler( errors.operator_name,formModal.firstMoldingStation.find('#operator_name') );
                    errorHandler( errors.input,formModal.firstMoldingStation.find('#input') );
                    errorHandler( errors.ng_qty,formModal.firstMoldingStation.find('#ng_qty') );
                    errorHandler( errors.output,formModal.firstMoldingStation.find('#output') );
                }else{
                    toastr.error(`Error: ${data.status}`);
                }

            }
        });
    }

    const getStation = function (){
        $.ajax({
            type: "GET",
            url: "get_stations",
            data: "data",
            dataType: "json",
            success: function (response) {
                let id = response['id'];
                let value = response['value'];
                let result = '';
                console.log(response)
                if(response['id'].length > 0){
                    result = '<option selected disabled> --- Select --- </option>';
                    for(let index = 0; index < id.length; index++){
                        result += '<option value="' +id[index]+'">'+value[index]+'</option>';
                    }
                }
                else{
                    result = '<option value="0" selected disabled> No record found </option>';
                }
                $('select[name="station"]').html(result);
            }
        });
    }

    const totalOutput = function (input_qty,ng_qty){
        let totalOutputQty = input_qty - ng_qty;
        formModal.firstMoldingStation.find('#output').val(totalOutputQty);
    }

    const totalStationYield = function (station_input,station_output){
        let stationYield = (station_output/station_input)*100;
        console.log('station_input',station_input);
        console.log('stationYield_station_output',station_output);
        console.log('stationYield',stationYield);
        if(station_input == "" || station_output == ""){
            formModal.firstMoldingStation.find("#station_yield").val('0%');
            return;
        }
        formModal.firstMoldingStation.find("#station_yield").val(stationYield.toFixed(2)+'%');
    }

    const calculateTotalMaterialYield = function (machineOutput,shipmentOutput){
        if( shipmentOutput == "" || machineOutput == "" || shipmentOutput == 0 || machineOutput == 0 ){
            formModal.firstMolding.find("#material_yield").val('0%');
            return;
        }
        let totalMaterialYield = ( parseFloat( shipmentOutput ) / parseFloat( machineOutput ) )*100;
        formModal.firstMolding.find('#material_yield').val(`${totalMaterialYield.toFixed(2)}%`);
    }

    const getPmiPoReceivedDetails = function (pmiPoNo){
        $.ajax({
            type: "GET",
            url: "get_pmi_po_received_details",
            data: {"pmi_po_no" : pmiPoNo},
            dataType: "json",
            success: function (response) {
                if( response.result_count === 1 ){
                    let poQty = parseFloat(response.order_qty);
                    let productOfPoPercentage =  poQty * 5 * 0.05;
                    let sumOfPoPercentagePoQty = poQty + productOfPoPercentage;

                    formModal.firstMolding.find('#po_no').val(response.po_no);
                    formModal.firstMolding.find('#po_qty').val(response.order_qty);
                    formModal.firstMolding.find('#po_target').val(response.order_qty);
                    formModal.firstMolding.find('#po_balance').val(response.po_balance);
                    formModal.firstMolding.find('#item_code').val(response.item_code);
                    formModal.firstMolding.find('#item_name').val(response.item_name);
                    formModal.firstMolding.find('#required_output').val(sumOfPoPercentagePoQty);
                }else{
                    formModal.firstMolding.find('#po_no').val('');
                    formModal.firstMolding.find('#po_qty').val('');
                    formModal.firstMolding.find('#po_target').val('');
                    formModal.firstMolding.find('#po_balance').val('');
                    formModal.firstMolding.find('#po_balance').val('');
                    formModal.firstMolding.find('#item_code').val('');
                    formModal.firstMolding.find('#item_name').val('');

                }
            }
        });
    }

    const getValidateTotalNgQty = function (ngQty,totalNumberOfMOD){
        if(parseInt(ngQty) === totalNumberOfMOD){
            $('#labelTotalNumberOfNG').css({color: 'green'})
            $('#labelIsTally').css({color: 'green'})
            $('#labelIsTally').addClass('fa-thumbs-up')
            $('#labelIsTally').removeClass('fa-thumbs-down')
            $('#labelIsTally').attr('title','')
            $("#buttonFirstMoldingStation").prop('disabled', false);
            $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', true);
        }else{
            console.log('Mode of Defect & NG Qty not tally!');
            $('#labelTotalNumberOfNG').css({color: 'red'})
            $('#labelIsTally').css({color: 'red'})
            $('#labelIsTally').addClass('fa-thumbs-down')
            $('#labelIsTally').removeClass('fa-thumbs-up')
            $('#labelIsTally').attr('title','Mode of Defect & NG Qty are not tally!')
            $("#buttonFirstMoldingStation").prop('disabled', true);
            $("#buttonAddFirstMoldingModeOfDefect").prop('disabled', false);
        }
        $("#labelTotalNumberOfNG").text(totalNumberOfMOD);
    }

    const resetTotalNgQty = function() {
        let totalNumberOfMOD = 0;
        $('#labelTotalNumberOfNG').css({color: 'red'})
        $('#labelIsTally').css({color: 'red'})
        $('#labelIsTally').addClass('fa-thumbs-down')
        $('#labelIsTally').removeClass('fa-thumbs-up')
        $("#labelTotalNumberOfNG").text(totalNumberOfMOD);
    }
// })
