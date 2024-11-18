const resetFormValuesOnModalClose = (modalId, formId) => {
    $(`#${modalId}`).on('hidden.bs.modal', function () {
        $(`#${formId}`).find('#selMachineNumber').val(0).trigger('change');  // chris to reset the select machine

        // Remove invalid & title validation
        $('div').find('input').removeClass('is-invalid');
        $('div').find('input').attr('title', '');

        $('#tableSecondMoldingStationMOD tbody').html(''); // Clear Mode of Defect table
        $('#textStation').prop('disabled', false);
        $('#textDate').prop('disabled', false);
        $('#textOperatorName').prop('disabled', false);
        $('#textInputQuantity').prop('disabled', false);
        $('#textOutputQuantity').prop('disabled', false);
        $('#textRemarks').prop('disabled', false);
        $('#buttonAddModeOfDefect').prop('disabled', false);
        $('#buttonSaveSecondMoldingStation').prop('disabled', false);

        /* Reset dynamic Lot Numbers */
        let rowCounter = parseInt($('body').find($('#divLotNumberEightRow')).attr('row-count'));
        while (rowCounter != 1) {
            rowCounter--;
            $('#divLotNumberEightRow div:last-child').remove();
            console.log('rowCounter ', rowCounter);
        }
        $('body').find($('#divLotNumberEightRow')).attr('row-count', rowCounter)
        $('#divLotNumberEightRow').attr('camera-inspection-count', 0);
        // $('#buttonAddLotNumber').prop('disabled', false);

        // Reset form values
        $(`#${formId}`)[0].reset();
        console.log(`modalId ${modalId}`);
        console.log(`formId ${formId}`);
    });
}

const isResponseError = (elementId, boolean) => {
    if(boolean == true){
        $(`#${elementId}`).addClass('is-invalid');
        $(`#${elementId}`).attr('title', '');
    }else{
        $(`#${elementId}`).removeClass('is-invalid');
        $(`#${elementId}`).attr('title', '');
    }
}

const delay = (fn, ms) => {
    let timer = 0
    return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}

const redirectToACDCSDrawing = (docNo, docTitle, docType)  => {
    if (docTitle == '' )
        alert('No Document')
    else{
        window.open(`http://rapid/ACDCS/prdn_home_pats_ppd_molding?doc_no=${docNo}&doc_title=${docTitle}&doc_type=${docType}`)
    }
}

const getPOReceivedByPONumber = (poNumber) => {
    $.ajax({
        async: false,
        type: "get",
        url: "get_po_received_by_po_number",
        data: {
            "po_number" : poNumber
        },
        dataType: "json",
        beforeSend: function(){},
        success: function (response) {
            if(response.length > 0){
                $('#textSearchMaterialName').val(response[0]['ItemName']);
                $('#textSearchPOQuantity').val(response[0]['OrderQty']);
                $('#textDeviceName', $('#formSecondMolding')).val(response[0]['ItemName']);
                $('#textPartsCode', $('#formSecondMolding')).val(response[0]['ItemCode']);
                $('#textPMIPONumber', $('#formSecondMolding')).val(response[0]['OrderNo']);
                $('#textPONumber', $('#formSecondMolding')).val(response[0]['ProductPONo']);
                $('#textPoQuantity', $('#formSecondMolding')).val(response[0]['OrderQty']);

                let poQuantity = parseFloat(response[0]['OrderQty']);
                let usage = 1;
                let poQuantityPercentage = parseFloat(poQuantity * usage * 0.05);
                let requiredOutput = (poQuantity * usage) + poQuantityPercentage;
                $('#textRequiredOutput').val(requiredOutput.toFixed(2));

                /**
                 * Computation of Target Output with Usage allowance
                 */
                // let poQuantity = parseFloat(response[0]['OrderQty']);
                // let poQuantityPercentage = parseFloat(poQuantity * 5 * 0.05);
                // let requiredOutput = (poQuantity * 5) + poQuantityPercentage;
                // $('#textRequiredOutput', $('#formSecondMolding')).val(requiredOutput.toFixed(2));
            }
            else{
                toastr.error('No PO Found')
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

/**
 *
 * Original Code
 * Commented as of 04-11-2024
 */
// const checkMaterialLotNumber = (qrScannerValue) => {
//     $.ajax({
//         type: "get",
//         url: "check_material_lot_number",
//         data: {
//             material_lot_number: qrScannerValue,
//         },
//         dataType: "json",
//         success: function (response) {
//             $('#textMaterialLotNumber').val('');
//             $('#textMaterialName').val('');
//             if(response[0] != undefined){
//                 $('#textMaterialLotNumber').val(response[0].material_lot_number);
//                 $('#textMaterialName').val(response[0].material_name);
//                 $('#modalQrScanner').modal('hide');
//             }else{
//                 toastr.error('Incorrect material lot number.')
//             }
//         }
//     });
// }

const checkMaterialLotNumber = (qrScannerValue) => {
    let splittedQrScannerValue = qrScannerValue.split(" | ");
    console.log('splittedQrScannerValue ', splittedQrScannerValue);
    console.log('splittedQrScannerValue ', splittedQrScannerValue[0]);
    console.log('splittedQrScannerValue ', splittedQrScannerValue[3]);


    $('#textMaterialLotNumber').val('');
    $('#textMaterialName').val('');
    $('#textMaterialLotNumber').val(splittedQrScannerValue[0]);
    $('#textMaterialName').val(splittedQrScannerValue[3]);
    $('#modalQrScanner').modal('hide');

}

const checkProductionLotNumberOfFirstMolding = (qrScannerValue, formValue, scannerRow = null) => {
    let qrScannerValueToJSON = JSON.parse(qrScannerValue);
    let lotNumber = qrScannerValueToJSON.lot_no;
    let lotNumberExtension = qrScannerValueToJSON.lot_no_ext;
    let lotNumberSize;
    console.log(`lotNumber ${lotNumber}`);
    console.log(`lotNumberExtension ${lotNumberExtension}`);

    let textLotNumberValue= '';
    let textLotNumberIdValue = '';
    let firstMoldingDeviceId;
    if(formValue == 'formProductionLotNumberEight'){
        // textLotNumberValue = 'textLotNumberEight';
        // textLotNumberIdValue = 'textLotNumberEightFirstMoldingId';
        firstMoldingDeviceId = 1;
        lotNumberSize = qrScannerValueToJSON.size;
        console.log('lotNumberSize ', lotNumberSize);
    }else if(formValue == 'formProductionLotNumberNine'){
        textLotNumberValue = 'textLotNumberNine';
        textLotNumberIdValue = 'textLotNumberNineFirstMoldingId';
        firstMoldingDeviceId = 2;
    }else if(formValue == 'formProductionLotNumberTen'){
        textLotNumberValue = 'textLotNumberTen';
        textLotNumberIdValue = 'textLotNumberTenFirstMoldingId';
        firstMoldingDeviceId = 3;
    }

    $.ajax({
        type: "get",
        url: "check_material_lot_number_of_first_molding",
        data: {
            production_lot_number: lotNumber,
            production_lot_number_extension: lotNumberExtension,
            production_lot_number_size: lotNumberSize,
        },
        dataType: "json",
        success: function (response) {
            let data = response['data'];
            let cameraInspectionCountResult = response['cameraInspectionCountResult'];
            if(data.length > 0){
                if(data[0].first_molding_device_id == firstMoldingDeviceId){
                    if(firstMoldingDeviceId == 1){
                        console.log('scannerRow ', scannerRow);
                        $(scannerRow).closest('div').find('input[name="lot_number_eight[]"').val(data[0].production_lot)
                        $(scannerRow).closest('div').find('input[name="lot_number_eight_first_molding_id"').val(data[0].first_molding_id)
                        $(scannerRow).closest('div').find('input[name="lot_number_eight_size_category[]"').val(data[0].first_molding_size_category)
                        $(scannerRow).closest('div').find('input[name="lot_number_eight_quantity[]"').val(data[0].first_molding_output)
                        $('#divLotNumberEightRow').attr('camera-inspection-count', cameraInspectionCountResult[0].camera_inspection_count);

                        /**
                         * Validation for Add Lot #(button)
                         */
                        let rowCounter = parseInt($('body').find($('#divLotNumberEightRow')).attr('row-count'));
                        let cameraInspectionCount = parseInt($('#divLotNumberEightRow').attr('camera-inspection-count'));
                        console.log('rowCounter ', rowCounter);
                        if(cameraInspectionCount != 0){
                            if(rowCounter == cameraInspectionCount){
                                $('#buttonAddLotNumber').prop('disabled', true);
                            }else{
                                $('#buttonAddLotNumber').prop('disabled', false);
                            }
                        }
                    }else{
                        $(`#${textLotNumberValue}`).val('');
                        $(`#${textLotNumberIdValue}`).val('');
                        $(`#${textLotNumberValue}`).val(data[0].production_lot);
                        $(`#${textLotNumberIdValue}`).val(data[0].first_molding_id);
                    }

                    $('#modalQrScanner').modal('hide');
                }else{
                    toastr.error('Incorrect material lot number.')
                }
            }else{
                toastr.error('Incorrect material lot number.')
            }
        }
    });
}

const getMaterialProcessStation = () => {
    let result = `<option value="0" selected disabled> - Please select one - </option>`;
    $.ajax({
        type: "get",
        url: "get_material_process_station",
        async: false,
        data: {
            device_name: $('#textSearchMaterialName').val(),
        },
        dataType: "json",
        success: function (response) {

            if(response['data'].length > 0){
                for (let i = 0; i < response['data'].length; i++) {
                    result += `<option value="${response['data'][i].station_id}" step="${response['data'][i].step}">${response['data'][i].station_name}</option>`;
                }
            }else{
                result =+ `<option value="0" selected disabled> - No data found - </option>`;
            }
            $('#textStation').html(result);
        }
    });
}

const getModeOfDefectForSecondMolding = (elementId, modeOfDefectId = null) => {
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
            elementId.html(result);
            if(modeOfDefectId != null){
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

const getMachineDropdown = (cboElement, materialName) => {  // chris
    $.ajax({
        type: "get",
        url: "get_machine",
        data: {
            "material_name" : materialName
        },
        dataType: "json",
        success: function (response) {
            let result = '';
            // console.log(response['machine']);
            if(response['machine'].length > 0){
                for(let index = 0; index < response['machine'].length; index++){
                    result += `<option value="${response['machine'][index].machine_name}">${response['machine'][index].machine_name}</option>`;
                }
            }

            cboElement.html(result);

        }
    });
}

const getUser = (elementId) => {
    let result = `<option value="0" selected> N/A </option>`;
    $.ajax({
        url: 'get_user_for_second_molding',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
            result = `<option value="0" selected disabled> - Loading - </option>`;
            elementId.html(result);
        },
        success: function(response){
            result = '';
            // console.log('object ', response['data']);
            if(response['data'].length > 0){
                for(let index = 0; index < response['data'].length; index++){
                    result += `<option value="${response['data'][index].id}">${response['data'][index].operator}</option>`;
                }
            }
            else{
                result = `<option value="0" selected disabled> - No data found - </option>`;
            }
            elementId.html(result);
        },
        error: function(data, xhr, status){
            result = `<option value="0" selected disabled> - Reload Again - </option>`;
            elementId.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function setDisabledSecondMoldingRuncard(boolean) {
    $('#buttonQrScanMaterialLotNumber').prop('disabled', boolean);
    $('#buttonViewBDrawing').prop('disabled', boolean);
    $('#textProductionLot').prop('disabled', boolean);
    $('#buttonQrScanMaterialLotNumberEight').prop('disabled', boolean);
    $('#buttonQrScanMaterialLotNumberNine').prop('disabled', boolean);
    $('#buttonQrScanMaterialLotNumberTen').prop('disabled', boolean);
    $('#buttonQrScanContactLotNumberOne').prop('disabled', boolean);
    $('#buttonQrScanContactLotNumberSecond').prop('disabled', boolean);
    $('#buttonQrScanMELotNumberOne').prop('disabled', boolean);
    $('#buttonQrScanMELotNumberSecond').prop('disabled', boolean);
    $('#buttonSaveSecondMoldingData').prop('disabled', boolean);
    $('#adjustment_shots').prop('disabled', boolean);
    $('#qc_samples').prop('disabled', boolean);
    $('#prod_samples').prop('disabled', boolean);
    $('#ng_count').prop('disabled', boolean);
    $('#total_machine_output').prop('disabled', boolean);
    $('#shipment_output').prop('disabled', boolean);
    $('#buttonSubmitSecondMolding').prop('disabled', boolean);
}

const getDiesetDetailsByDeviceNameSecondMolding = (deviceName) => {
    $.ajax({
        type: "GET",
        url: "get_dieset_details_by_device_name_second_molding",
        data: {
            "device_name" : deviceName
        },
        dataType: "json",
        success: function (response) {
            if(response['is_success'] == "false"){
                $('#modalSecondMolding').modal('hide');
                alert('Invalid device name. Please check the RAPID/DMCMS');
                return;
            }
            let dateNow = new Date();
            let twoDigitYear = dateNow.getFullYear().toString().substr(-2);
            // console.log(`twoDigitYear ${twoDigitYear}`);

            let twoDigitMonth = (dateNow.getMonth() + 1).toString().padStart(2, "0");
            // console.log(`twoDigitMonth ${twoDigitMonth}`);

            let twoDigitDay = String(dateNow.getDate()).padStart(2, '0');
            // console.log(`twoDigitDay ${twoDigitDay}`);

            let revNo = response['rev_no'];
            $('#textProductionLot', $('#formSecondMolding')).val(`${revNo}${twoDigitYear}${twoDigitMonth}${twoDigitDay}`);
        }
    });
}

function getSublotQty(subLotId){
    $.ajax({
        type: "get",
        url: "get_sublot_qty",
        data: {
            "sublot_id" : subLotId,
        },
        dataType: "json",
        success: function (response) {

			if(response['sublotDetails'] != null){
                    $('#txtSublotQty').val(response['sublotDetails'][0]['batch_qty'])
			}else{
                toastr.warning('warning messages');
            }

        }
    });
}
