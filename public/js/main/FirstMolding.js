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

    var input = {
        inputTotalMachineOuput : 0
    }

    var inputTotalMachineOuput = 0;

    const getFirstMoldingOperationNames = (elementId, valueId = null) => {
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            url: 'get_operation_names',
            method: 'get',
            dataType: 'json',
            beforeSend: function(){
                result = `<option value="0" selected disabled> - Select - </option>`;
                elementId.html(result);
            },
            success: function(response){
                // console.log('getFirstMoldingOperationNames',response);
                // return;
                result = '';
                if(response.id.length > 0){
                    for(let index = 0; index < response.id.length; index++){

                        result += `<option value="${response.id[index]}">${response.value[index]}</option>`;
                    }
                }
                else{
                    result = `<option value="0" selected disabled> - No data found - </option>`;
                }
                elementId.append(result);

                if(valueId != null){
                    console.log(valueId)
                    elementId.val(valueId).trigger('change');
                }
            },
            error: function(data, xhr, status){
                result = `<option value="0" selected disabled> - Reload Again - </option>`;
                elementId.html(result);
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
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
                let first_molding_material_list = data.first_molding_material_lists[0];

                formModal.firstMolding.find('#first_molding_id').val(data.id);
                formModal.firstMolding.find('#contact_lot_number').val(data.contact_lot_number);
                formModal.firstMolding.find('#contact_lot_qty').val(data.contact_lot_qty);
                formModal.firstMolding.find('#production_lot').val(data.production_lot);
                formModal.firstMolding.find('#production_lot_extension').val(data.production_lot_extension);
                formModal.firstMolding.find('#shift').val(data.shift);
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
                formModal.firstMolding.find('#shipment_output').val(data.shipment_output);
                formModal.firstMolding.find('#item_code').val(data.item_code);
                formModal.firstMolding.find('#total_machine_output').val(data.total_machine_output);
                formModal.firstMolding.find('#item_name').val(data.item_name);
                formModal.firstMolding.find('#po_qty').val(data.po_qty);
                formModal.firstMolding.find('#required_output').val(data.required_output);
                formModal.firstMolding.find('#created_at').val(response['created_at']);
                formModal.firstMolding.find('#remarks').val(data.remarks);

                formModal.firstMolding.find('#virgin_material').val(first_molding_material_list.virgin_material);
                formModal.firstMolding.find('#virgin_qty').val(first_molding_material_list.virgin_qty);
                formModal.firstMolding.find('#recycle_qty').val(first_molding_material_list.recycle_qty);

                // console.log('status',data.status)
                // console.log('view_data',view_data);

                if(view_data != undefined){
                    $('#btnFirstMoldingStation').prop('disabled',true);
                    $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                    $('#btnRuncardDetails').addClass('d-none',true);
                    $('#btnAddFirstMoldingMaterial').addClass('d-none',true);
                }else{
                    if( data.status === 0 || data.status === 3 ){
                        $('#btnFirstMoldingStation').prop('disabled',true);
                        $('#btnSubmitFirstMoldingStation').prop('disabled',true);
                        $('#btnRuncardDetails').addClass('d-none',true);
                        $('#btnAddFirstMoldingMaterial').addClass('d-none',true);
                    }else if( data.status === 1 ){
                        $('#btnFirstMoldingStation').prop('disabled',false);
                        $('#btnSubmitFirstMoldingStation').prop('disabled',false);
                        $('#btnRuncardDetails').addClass('d-none',true);
                        $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);
                    }else{
                        $('#btnFirstMoldingStation').prop('disabled',false);
                        $('#btnSubmitFirstMoldingStation').prop('disabled',false);
                        $('#btnRuncardDetails').removeClass('d-none',true);
                        $('#btnAddFirstMoldingMaterial').removeClass('d-none',true);
                    }
                }

                /* Calculate the Material Yield */
                calculateTotalMaterialYield(data.total_machine_output,data.shipment_output);
                dt.firstMoldingStation.draw();
                $('#modalFirstMolding').modal('show');

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
                result = `<option value="0" selected disabled> - Select - </option>`;
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
        let view_data = $(this).attr('view-data');
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
                                    <center><button class="btn btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
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

                let station = first_molding_station_detail.station;

                formModal.firstMoldingStation.find('#first_molding_id').val(first_molding_station_detail.id);
                formModal.firstMoldingStation.find('#first_molding_id').val(first_molding_station_detail.first_molding_id);
                formModal.firstMoldingStation.find('#first_molding_detail_id').val(first_molding_station_detail.id);
                formModal.firstMoldingStation.find('#date').val(first_molding_station_detail.date);
                // formModal.firstMoldingStation.find('#operator_name').val(first_molding_station_detail.operator_name);
                formModal.firstMoldingStation.find('#size_category').val(first_molding_station_detail.size_category);
                formModal.firstMoldingStation.find('#input').val(first_molding_station_detail.input);
                formModal.firstMoldingStation.find('#ng_qty').val(first_molding_station_detail.ng_qty);
                formModal.firstMoldingStation.find('#output').val(first_molding_station_detail.output);
                formModal.firstMoldingStation.find('#station_yield').val(first_molding_station_detail.yield);
                formModal.firstMoldingStation.find('#remarks').val(first_molding_station_detail.remarks);

                setTimeout(() => {
                    formModal.firstMoldingStation.find('#station').val(station);
                }, 300);
                // getStation(formModal.firstMoldingStation.find('#station'));

                if(view_data != undefined){
                    $('#buttonFirstMoldingStation').prop('disabled',true);
                    $('#buttonAddFirstMoldingModeOfDefect').prop('disabled',true);
                }else{
                    $('#buttonFirstMoldingStation').prop('disabled',false);
                    $('#buttonAddFirstMoldingModeOfDefect').prop('disabled',false);
                }
                $('#modalFirstMoldingStation').modal('show');
                getFirstMoldingOperationNames(formModal.firstMoldingStation.find('#operator_name'),first_molding_station_detail.operator_name);
                fnIsSelectCameraInspection(station);
                // getStation(formModal.firstMoldingStation.find('#station'),"",station)
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
            beforeSend: function(){
                $("#btnRuncardDetailsIcon").addClass('fa fa-spinner fa-pulse');
                $("#btnRuncardDetails").prop('disabled', 'disabled');
            },
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
                    $('#modalScanQRSave').modal('hide');
                }
            },error: function (data, xhr, status){
                $("#btnRuncardDetailsIcon").removeClass('fa fa-spinner fa-pulse');
                $("#btnRuncardDetails").removeAttr('disabled');
                $("#btnRuncardDetailsIcon").addClass('fa fa-floppy-disk');
                if(data.status === 422){
                    // error
                    let errors = data.responseJSON.errors ;
                    toastr.error(`Saving Failed, Please fill up all required fields`);
                    errorHandler( errors.first_molding_device_id,formModal.firstMolding.find('#first_molding_device_id') );
                    errorHandler( errors.contact_lot_number,formModal.firstMolding.find('#contact_lot_number') );
                    errorHandler( errors.contact_lot_qty,formModal.firstMolding.find('#contact_lot_qty') );
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

                    $("#btnRuncardDetailsIcon").removeClass('fa fa-spinner fa-pulse');
                    $("#btnRuncardDetails").removeAttr('disabled');
                    $("#btnRuncardDetailsIcon").addClass('fa fa-floppy-disk');
                }else{
                    if(data.responseJSON.result == 0){
                        toastr.error(`Error: ${data.responseJSON.error}`);
                        return;
                    }
                    toastr.error(`Error: ${data.status}`);
                }
            }
        });
    }

    const updateFirstMoldingShipmentMachineOuput = function (firstMoldingId,shipmentOutput,ngCount){
        let data = $.param({
            "first_molding_id": firstMoldingId,
            "shipment_output": shipmentOutput,
            "ng_count": ngCount,
        });
        $.ajax({
            type: "GET",
            url: "update_first_molding_shipment_machine_ouput",
            data: data,
            dataType: "json",
            success: function (response) {
                let total_machine_output = response.total_machine_output;
                let shipment_output = response.shipment_output;

                formModal.firstMolding.find('#shipment_output').val(shipment_output);
                formModal.firstMolding.find('#ng_count').val(response.ng_count);
                formModal.firstMolding.find('#total_machine_output').val(total_machine_output);
                /* Calculate the Material Yield */
                calculateTotalMaterialYield (total_machine_output,shipment_output);
            },error: function (data, xhr, status){
                toastr.error(`Error: ${data.status}`);
            }

        });
    }

    const savefirstMoldingStation = function () {
        // let data = $.param({ 'shipment_output': input.inputTotalMachineOuput }) + "&" + formModal.firstMoldingStation.serialize();

        $.ajax({
            type: "POST",
            url: "save_first_molding_station",
            data: formModal.firstMoldingStation.serialize(),
            dataType: "json",
            success: function (response) {
                if (response["result"] === 1) {
                    let total_machine_output = response.total_machine_output;
                    let shipment_output = response.shipment_output;
                    let step = response.step;

                    if (step > 1) {
                        // nmodify
                        formModal.firstMolding
                            .find("#shipment_output")
                            .val(shipment_output);
                        formModal.firstMolding
                            .find("#ng_count")
                            .val(response.ng_count);
                        formModal.firstMolding
                            .find("#total_machine_output")
                            .val(total_machine_output);
                        calculateTotalMaterialYield(
                            total_machine_output,
                            shipment_output
                        );
                    }

                    $("#modalFirstMoldingStation").modal("hide");
                    formModal.firstMoldingStation[0].reset();
                    dt.firstMoldingStation.draw();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Saved Successfully !",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                } else {
                    $("#modalFirstMoldingStation").modal("hide");
                    formModal.firstMoldingStation[0].reset();
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: response.error_msg,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            },
            error: function (data, xhr, status) {
                let errors = data.responseJSON.errors;
                if (data.status === 422) {
                    toastr.error(
                        `Saving Failed, Please fill up all required fields`
                    );
                    errorHandler(
                        errors.first_molding_id,
                        formModal.firstMoldingStation.find("#first_molding_id")
                    );
                    errorHandler(
                        errors.station,
                        formModal.firstMoldingStation.find("#station")
                    );
                    errorHandler(
                        errors.date,
                        formModal.firstMoldingStation.find("#date")
                    );
                    errorHandler(
                        errors.operator_name,
                        formModal.firstMoldingStation.find("#operator_name")
                    );
                    // errorHandler(
                    //     errors.size_category,
                    //     formModal.firstMoldingStation.find("#size_category")
                    // );
                    errorHandler(
                        errors.input,
                        formModal.firstMoldingStation.find("#input")
                    );
                    errorHandler(
                        errors.ng_qty,
                        formModal.firstMoldingStation.find("#ng_qty")
                    );
                    errorHandler(
                        errors.output,
                        formModal.firstMoldingStation.find("#output")
                    );
                    errorHandler(
                        errors.size_category,
                        formModal.firstMoldingStation.find("#size_category")
                    );
                } else if (data.status === 409) {
                    // toastr.error(`Error: ${data.status}`);
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Warning: The Station is already exists !",
                        showConfirmButton: false,
                        timer: 3000,
                    });
                    $("#modalFirstMoldingStation").modal("hide");
                } else {
                    toastr.error(`Error: ${data.status}`);
                }
            },
        });
    };

    const getStation = function (elementId,device_name =null,valueId=null){ //nmodify
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            type: "GET",
            url: "get_stations",
            data: {"device_name" : device_name},
            dataType: "json",
            success: function (response) {
                // return response;
                let id = response['id'];
                let value = response['value'];
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
                elementId.append(result);

                if(valueId != null){
                    console.log('dasd',valueId)
                    elementId.val(valueId).trigger('change');
                }
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
        console.log('calculateTotalMaterialYield');
        console.log('machineOutput',machineOutput);
        console.log('shipmentOutput',machineOutput);
        if( shipmentOutput == "" || machineOutput == "" || shipmentOutput == 0 || machineOutput == 0 ){
            formModal.firstMolding.find("#material_yield").val('0%');
            return;
        }
        let totalMaterialYield = ( parseFloat( shipmentOutput ) / parseFloat( machineOutput ) )*100;
        formModal.firstMolding.find('#material_yield').val(`${totalMaterialYield.toFixed(2)}%`);
    }

    const getPmiPoReceivedDetails = function (pmiPoNo,deviceId){
        $.ajax({
            type: "GET",
            url: "get_pmi_po_received_details",
            data: {"pmi_po_no" : pmiPoNo},
            dataType: "json",
            success: function (response) {
                if( response.result_count === 1 ){
                    let poQty = parseFloat(response.order_qty);
                    let multiplier = 0;
                    if( deviceId == 1 ){ //CN171S-08#IN-VE, id table first molding devices
                        multiplier = 5 ;
                    }else{
                        multiplier = 1 ;
                    }
                    let productOfPoNumber =  poQty * multiplier ;
                    console.log('multiplier',productOfPoNumber);
                    formModal.firstMolding.find('#po_no').val(response.po_no);
                    formModal.firstMolding.find('#po_qty').val(response.order_qty);
                    formModal.firstMolding.find('#po_target').val(response.order_qty);
                    formModal.firstMolding.find('#po_balance').val(response.po_balance);
                    formModal.firstMolding.find('#item_code').val(response.item_code);
                    formModal.firstMolding.find('#item_name').val(response.item_name);
                    formModal.firstMolding.find('#required_output').val(productOfPoNumber);
                    formModal.firstMolding.find('#virgin_qty').val(response.virgin_qty);
                    formModal.firstMolding.find('#recycle_qty').val(response.recycled_qty);
                    formModal.firstMolding.find('#pmi_po_no').val(response.pmi_po_no);
                    $('#global_po_qty').val(poQty);
                    $('#global_target_qty').val(productOfPoNumber);
                    $('#global_series_name').val(response.item_name);
                }else{
                    toastr.error(response.error_msg)
                    formModal.firstMolding.find('#po_no').val('');
                    formModal.firstMolding.find('#po_qty').val('');
                    formModal.firstMolding.find('#po_target').val('');
                    formModal.firstMolding.find('#po_balance').val('');
                    formModal.firstMolding.find('#po_balance').val('');
                    formModal.firstMolding.find('#item_code').val('');
                    formModal.firstMolding.find('#item_name').val('');
                    formModal.firstMolding.find('#required_output').val('');
                    formModal.firstMolding.find('#virgin_qty').val('');
                    formModal.firstMolding.find('#recycle_qty').val('');
                    $('#global_target_qty').val('');
                    $('#global_po_qty').val('');
                    $('#global_series_name').val('');
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

    const getMachineFromMaterialProcess = function (cboElement,material_name){
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            type: "GET",
            url: "get_machine",
            data: {"material_name":material_name},
            dataType: "json",
            beforeSend: function(){
                result = `<option value="0" selected disabled> - Select - </option>`;
                cboElement.html(result);
            },
            success: function (response) {
                console.log('machine',response['machine']);
                let result = '';
                    if(response['machine'].length > 0){
                        for(let index = 0; index < response['machine'].length; index++){
                            result += `<option value="${response['machine'][index].id}">${response['machine'][index].machine_name}</option>`;
                        }
                    }else{
                        result = `<option value="0" selected disabled> - No data found - </option>`;
                    }

                cboElement.append(result);
            },error: function(data, xhr, status){
                result = `<option value="0" selected disabled> - Reload Again - </option>`;
                cboElement.html(result);
                console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        });
    }

    const getFirstMoldingStationLastOuput = function (first_molding_station_last_ouput){
        $.ajax({
            type: "GET",
            url: "get_first_molding_station_last_ouput",
            data: {"first_molding_station_last_ouput":first_molding_station_last_ouput},
            dataType: "json",
            success: function (response) {
                let station_input_qty = response['first_molding_station_last_output'];
                let first_molding_detail_count = response.first_molding_detail_count;
                let is_partial = response.is_partial;
                console.log('response.first_molding_detail_count',response.first_molding_detail_count);
                if(first_molding_detail_count){
                    formModal.firstMoldingStation.find('#input').prop('readonly',true);
                }else{
                    formModal.firstMoldingStation.find('#input').prop('readonly',false);
                }
                //first_molding_detail_count
                let station_ng_qty = formModal.firstMoldingStation.find('#ng_qty').val();
                formModal.firstMoldingStation.find('#input').val(station_input_qty);
                totalOutput(station_input_qty,station_ng_qty);

                /* Get initialized totalOutput before the Total Yield*/
                let station_output_qty = parseFloat(formModal.firstMoldingStation.find('#output').val());
                totalStationYield(station_input_qty,station_output_qty);
            }
        });
    }

    const getDiesetDetailsByDeviceName = function (deviceName){
        $.ajax({
            type: "GET",
            url: "get_dieset_details_by_device_name",
            data: {"device_name" : deviceName},
            dataType: "json",
            success: function (response) {
                let twoDigitYear = strDatTime.dateToday.getFullYear().toString().substr(-2);
                let twoDigitMonth = (strDatTime.dateToday.getMonth() + 1).toString().padStart(2, "0");
                let twoDigitDay = String(strDatTime.dateToday.getDate()).padStart(2, '0');
                // let diesetNo = response['dieset_no'];
                let drawingNo = response['drawing_no'];
                let revNo = response['rev_no'];

                formModal.firstMolding.find('#drawing_no').val(drawingNo);
                formModal.firstMolding.find('#revision_no').val(revNo);
                //Auto generated production lot number: DiesetRevisionNumberYYMMDD
                formModal.firstMolding.find('#production_lot').val(`${revNo}${twoDigitYear}${twoDigitMonth}${twoDigitDay}-`);
            }
        });
    }

    const validateScanFirstMoldingContactLotNum = function (scanFirstMoldingContactLotNo,firstMoldingDeviceId){ //Sticker From WHS
        let firstMoldingMaterialLotNo = scanFirstMoldingContactLotNo.split("|");
        $.ajax({
            type: "GET",
            url: "validate_material_lot_no",
            data: {
                "first_molding_material_lot_no": firstMoldingMaterialLotNo[0],
                "first_molding_device_id": firstMoldingDeviceId
            },
            dataType: "json",
            success: function(response) {
                if (response.is_valid_first_molding_device === 'true') {
                    formModal.firstMolding.find('#contact_lot_number').val(response.tbl_whs_trasanction[0].lot_num);
                    formModal.firstMolding.find('#contact_lot_qty').val(response.tbl_whs_trasanction[0].whs_transaction_qty);
                    $('#mdlScanQrCodeFirstMolding').modal('hide');
                }
                if (response.is_valid_first_molding_device === 'false'){
                    toastr.error(`Error: Invalid Material Lot Number,Matrix Material Name & Sticker Material Name is not match ! Please the matrix !`);
                    formModal.firstMolding.find('#contact_lot_number').val('');
                    formModal.firstMolding.find('#contact_lot_qty').val('');
                }
                if (response.is_exist_lot_no === 'false'){
                    toastr.error(`Error: Material Lot Number Not Exist, Please check to Rapid Issuance Module`);
                    formModal.firstMolding.find('#contact_lot_number').val('');
                    formModal.firstMolding.find('#contact_lot_qty').val('');
                }
            },
            error: function(data, xhr, status) {
                toastr.error(`Error: ${data.status}`);
                formModal.firstMolding.find('#contact_lot_number').val('');
                formModal.firstMolding.find('#contact_lot_qty').val('');
            }
        });
        $('#txtScanQrCodeFirstMolding').val('');
    }

    // const validateScanFirstMoldingContactLotNum = function (scanFirstMoldingContactLotNo,firstMoldingDeviceId){

    //     if(firstMoldingDeviceId === "6"){ //CN171S-02#MO-VE from Rapid PPD DB
    //         let firstMoldingMaterialLotNo = scanFirstMoldingContactLotNo.split("|");
    //         $.ajax({
    //             type: "GET",
    //             url: "validate_material_lot_no",
    //             data: {
    //                 "first_molding_material_lot_no": firstMoldingMaterialLotNo[0]
    //             },
    //             dataType: "json",
    //             success: function(response) {
    //                 let is_exist_lot_no = (response['is_exist_lot_no'] > 0) ? 'true' : 'false';
    //                 if (is_exist_lot_no === 'true') {
    //                     formModal.firstMolding.find('#contact_lot_number').val(firstMoldingMaterialLotNo[0]);
    //                     formModal.firstMolding.find('#contact_lot_qty').val(firstMoldingMaterialLotNo[1]);
    //                     $('#mdlScanQrCodeFirstMolding').modal('hide');
    //                 } else {
    //                     toastr.error(
    //                         `Error: Invalid Material Lot Number,Please check to Rapid Issuance Module`
    //                     );
    //                     formModal.firstMolding.find('#contact_lot_number').val('');
    //                     formModal.firstMolding.find('#contact_lot_qty').val('');
    //                 }

    //             },
    //             error: function(data, xhr, status) {
    //                 toastr.error(`Error: ${data.status}`);
    //                 formModal.firstMolding.find('#contact_lot_number').val('');
    //                 formModal.firstMolding.find('#contact_lot_qty').val('');
    //             }
    //         });
    //         $('#txtScanQrCodeFirstMolding').val('');

    //     }else{ //From 2nd Stamping
    //         // TODO: Validate Contact Lot Num
    //         let contactLotNo = JSON.parse(scanFirstMoldingContactLotNo).production_lot_no;
    //         let outputQty = JSON.parse(scanFirstMoldingContactLotNo).output_qty;
    //         $.ajax({
    //             type: "GET",
    //             url: "validate_scan_first_molding_contact_lot_num", //nmodify
    //             data: {"contact_lot_num" :contactLotNo ,"first_molding_device_id": firstMoldingDeviceId},
    //             dataType: "json",
    //             success: function (response) {
    //                 console.log(response);
    //                 if(response.result == 1){
    //                     formModal.firstMolding.find('#contact_lot_number').val(contactLotNo);
    //                     formModal.firstMolding.find('#contact_lot_qty').val(outputQty);
    //                     toastr.success('Scanned Successfully !')
    //                 }else{
    //                     Swal.fire({
    //                         position: "center",
    //                         icon: "warning",
    //                         title: `${contactLotNo}: This Prodn Lot is not yet DONE. Please Check to 2nd Stamping Module !`,
    //                         showConfirmButton: false,
    //                         timer: 3000
    //                     });
    //                     formModal.firstMolding.find('#contact_lot_number').val('');
    //                     formModal.firstMolding.find('#contact_lot_qty').val('');
    //                 }
    //                 $('#txtScanQrCodeFirstMolding').val('');
    //                 $('#mdlScanQrCodeFirstMolding').modal('hide');
    //             },error: function (data, xhr, status){
    //                 let errors = data.responseJSON.errors ;
    //                 Swal.fire({
    //                     position: "center",
    //                     icon: "error",
    //                     title: `${contactLotNo}: Invalid Prodn Lot Number. Please Check to 2nd Stamping Module ! !`,
    //                     showConfirmButton: false,
    //                     timer: 3000
    //                 });
    //                 formModal.firstMolding.find('#contact_lot_number').val('');
    //                 formModal.firstMolding.find('#contact_lot_qty').val('');
    //                 $('#txtScanQrCodeFirstMolding').val('');
    //                 $('#mdlScanQrCodeFirstMolding').modal('hide');

    //             }
    //         });
    //     }

    // }

    const firstMoldingUpdateStatus = function (){
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
                    $('#modalScanQRSave').modal('hide');
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

    const fnIsSelectCameraInspection = function (stationId) {
        // TODO: 5 = LIVE 7-TEST
        // TODO: firstMoldingDeviceId === 1
        console.log('first_molding_device_id',formModal.firstMolding.find("#first_molding_device_id").val());
        let firstMoldingDeviceId = formModal.firstMolding.find("#first_molding_device_id").val()
        console.log('firstMoldingDeviceId',firstMoldingDeviceId)
        if(firstMoldingDeviceId === "1"){ // Camera Inspection
            if( stationId  === "5"){
                formModal.firstMoldingStation.find('#isSelectCameraInspection').removeClass('d-none',false);
            }
            if( stationId === "12"){ //Oqc Inspection
                //TODO: Judgement if any
                formModal.firstMoldingStation.find('#isSelectCameraInspection').removeClass('d-none',false);
            }
        }else{
            formModal.firstMoldingStation.find('#isSelectCameraInspection').addClass('d-none',true);
        }
    }

    const fnGetDatalistMimfPoNum = function (globalPoNo){
        $.ajax({
            type: "GET",
            url: "get_datalist_mimf_po_num",
            data: {"pmi_po_no" : globalPoNo},
            dataType: "json",
            success: function (response) {
                $('#datalist_mimf_po_num').empty();
                for(i=0;i<response['pmi_po_num'].length;i++){
                    $('#datalist_mimf_po_num').append('<option>'+response['pmi_po_num'][i]+'</option>');
                }
            }
        });
    }

    const validateMaterialLotNo = function(firstMoldingMaterialLotNo) {
        $.ajax({
            type: "GET",
            url: "validate_material_lot_no",
            data: {
                "first_molding_material_lot_no": firstMoldingMaterialLotNo
            },
            dataType: "json",
            success: function(response) {
                if (response.is_exist_lot_no === 'true') {
                    toastr.success(`Scan Successfully`);
                    $('#virgin_material').val(firstMoldingMaterialLotNo);
                    $('#modalMaterialLotNum').modal('hide');
                } else {
                    toastr.error(
                        `Error: Invalid Material Lot Number,Please check to Rapid Issuance Module`
                    );
                }
            },
            error: function(data, xhr, status) {
                toastr.error(`Error: ${data.status}`);
            }
        });
        $('#txtLotNum').val('');
    }
