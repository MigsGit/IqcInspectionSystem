const submitProdData = async (scannedId, form, stampCat) => {
    $('input[name="status"]').prop('disabled', false);
    $('#selOperator').prop('disabled', false);

    let data = $.param({'scanned_id': scannedId, 'stamp_cat': stampCat }) + "&" + form.serialize();
    await $.ajax({
        type: "post",
        url: "save_prod_data",
        // data: $('#formProdData').serialize(),
        data: data,
        dataType: "json",
        beforeSend: function(){
        },
        success: function (response) {
            if(response['result'] == 1){
                $('#modalScanQRSave').modal('hide');

                if(stampCat == 1){
                    dtDatatableProd.draw();
                    $('#modalProdData').modal('hide');
                }
                else{
                    dtDatatableProdSecondStamp.draw();
                    $('#modalProdSecondStamp').modal('hide');
                }
                toastr.success(`${response['msg']}`);
                
            }
            else{ // ! ERROR HANDLER
                toastr.error('Please input required fields.');
                if(response['error']['mat_lot_no'] === undefined){
                    $('#txtMatLotNo', form).removeClass('is-invalid');
                    $('#txtMatLotNo', form).attr('title', '');
                }
                else{
                    $('#txtMatLotNo', form).addClass('is-invalid');
                    $('#txtMatLotNo', form).attr('title', response['error']['mat_lot_no']);
                }
                if(response['error']['opt_shift'] === undefined){
                    $('#txtOptShift', form).removeClass('is-invalid');
                    $('#txtOptShift', form).attr('title', '');
                }
                else{
                    $('#txtOptShift', form).addClass('is-invalid');
                    $('#txtOptShift', form).attr('title', response['error']['opt_shift']);
                }
                if(response['error']['prod_date'] === undefined){
                    $('#txtProdDate', form).removeClass('is-invalid');
                    $('#txtProdDate', form).attr('title', '');
                }
                else{
                    $('#txtProdDate', form).addClass('is-invalid');
                    $('#txtProdDate', form).attr('title', response['error']['prod_date']);
                }
                if(response['error']['prod_lot_no'] === undefined){
                    $('#txtProdLotNo', form).removeClass('is-invalid');
                    $('#txtProdLotNo', form).attr('title', '');
                }
                else{
                    $('#txtProdLotNo', form).addClass('is-invalid');
                    $('#txtProdLotNo', form).attr('title', response['error']['prod_lot_no']);
                }
                if(response['error']['inpt_coil_weight'] === undefined){
                    $('#txtInptCoilWeight', form).removeClass('is-invalid');
                    $('#txtInptCoilWeight', form).attr('title', '');
                }
                else{
                    $('#txtInptCoilWeight', form).addClass('is-invalid');
                    $('#txtInptCoilWeight', form).attr('title', response['error']['inpt_coil_weight']);
                }
                if(response['error']['setup_pins'] === undefined){
                    $('#txtSetupPin', form).removeClass('is-invalid');
                    $('#txtSetupPin', form).attr('title', '');
                }
                else{
                    $('#txtSetupPin', form).addClass('is-invalid');
                    $('#txtSetupPin', form).attr('title', response['error']['setup_pins']);
                }
                if(response['error']['adj_pins'] === undefined){
                    $('#txtAdjPin', form).removeClass('is-invalid');
                    $('#txtAdjPin', form).attr('title', '');
                }
                else{
                    $('#txtAdjPin', form).addClass('is-invalid');
                    $('#txtAdjPin', form).attr('title', response['error']['adj_pins']);
                }
                if(response['error']['qc_samp'] === undefined){
                    $('#txtQcSamp', form).removeClass('is-invalid');
                    $('#txtQcSamp', form).attr('title', '');
                }
                else{
                    $('#txtQcSamp', form).addClass('is-invalid');
                    $('#txtQcSamp', form).attr('title', response['error']['qc_samp']);
                }
                if(response['error']['prod_samp'] === undefined){
                    $('#txtProdSamp', form).removeClass('is-invalid');
                    $('#txtProdSamp', form).attr('title', '');
                }
                else{
                    $('#txtProdSamp', form).addClass('is-invalid');
                    $('#txtProdSamp', form).attr('title', response['error']['prod_samp']);
                }
                if(response['error']['ttl_mach_output'] === undefined){
                    $('#txtTtlMachOutput', form).removeClass('is-invalid');
                    $('#txtTtlMachOutput', form).attr('title', '');
                }
                else{
                    $('#txtTtlMachOutput', form).addClass('is-invalid');
                    $('#txtTtlMachOutput', form).attr('title', response['error']['ttl_mach_output']);
                }
                if(response['error']['ng_count'] === undefined){
                    $('#txtNGCount', form).removeClass('is-invalid');
                    $('#txtNGCount', form).attr('title', '');
                }
                else{
                    $('#txtNGCount', form).addClass('is-invalid');
                    $('#txtNGCount', form).attr('title', response['error']['ng_count']);
                }
                if(response['error']['target_output'] === undefined){
                    $('#txtTargetOutput', form).removeClass('is-invalid');
                    $('#txtTargetOutput', form).attr('title', '');
                }
                else{
                    $('#txtTargetOutput', form).addClass('is-invalid');
                    $('#txtTargetOutput', form).attr('title', response['error']['target_output']);
                }


                if(response['error']['inpt_pins'] === undefined){
                    $('#txtInptPins', form).removeClass('is-invalid');
                    $('#txtInptPins', form).attr('title', '');
                }
                else{
                    $('#txtInptPins', form).addClass('is-invalid');
                    $('#txtInptPins', form).attr('title', response['error']['inpt_pins']);
                }
                if(response['error']['act_qty'] === undefined){
                    $('#txtActQty', form).removeClass('is-invalid');
                    $('#txtActQty', form).attr('title', '');
                }
                else{
                    $('#txtActQty', form).addClass('is-invalid');
                    $('#txtActQty', form).attr('title', response['error']['act_qty']);
                }
                if(response['error']['material_no'] === undefined){
                    $('#txtMaterialLot', form).removeClass('is-invalid');
                    $('#txtMaterialLot', form).attr('title', '');
                }
                else{
                    $('#txtMaterialLot', form).addClass('is-invalid');
                    $('#txtMaterialLot', form).attr('title', response['error']['material_no']);
                }
                if(response['error']['remarks'] === undefined){
                    $('#txtRemarks', form).removeClass('is-invalid');
                    $('#txtRemarks', form).attr('title', '');
                }
                else{
                    $('#txtRemarks', form).addClass('is-invalid');
                    $('#txtRemarks', form).attr('title', response['error']['remarks']);
                }
                
            }
            setTimeout(() => {
                $('input[name="status"]').prop('disabled', true);
                $('#selOperator').prop('disabled', true);
    
            }, 500);

        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getProdDataById = async (id, btnFunction, stampCat) => {
    await $.ajax({
        type: "get",
        url: "get_prod_data_view",
        data: {
            "id" : id,
            "stamp_cat" : stampCat
        },
        beforeSend: function(){
            $('#divProdLotView').removeClass('d-none');
            $('#divProdLotInput').addClass('d-none');
            getOperatorList($('.selOpName'));
            $('#button-addon2').prop('disabled', true);
            $('#btnScanOperator').prop('disabled', true);


        },
        dataType: "json",
        success: function (response) {
            var counter = 0;
            $('#txtProdDataId').val(response['id'])
            $('#txtPoNumber').val(response['po_num'])
            $('#txtPoQty').val(response['po_qty'])
            $('#txtPartCode').val(response['part_code'])
            $('#txtMatName').val(response['material_name'])
            // $('#txtMatLotNo').val(response['material_lot_no'])
            $('#txtDrawingNo').val(response['drawing_no'])
            $('#txtDrawingRev').val(response['drawing_rev'])
            // $('#txtOptName').val(response['user']['firstname']+" "+response['user']['lastname'])
            $('#txtOptShift').val(response['shift'])
            $('#txtProdDate').val(response['prod_date'])
            // $('#txtProdLotNo').val(response['prod_lot_no'])
            $('#txtPlannedLoss').val(response['planned_loss'])
            $('#txtSetupPin').val(response['set_up_pins'])
            $('#txtAdjPin').val(response['adj_pins'])
            $('#txtQcSamp').val(response['qc_samp'])
            $('#txtProdSamp').val(response['prod_samp'])
            $('#txtTtlMachOutput').val(response['total_mach_output'])
            $('#txtShipOutput').val(response['ship_output'])

            $('#txtMatYield').val(response['mat_yield'])

            // // * GET VALUE OF YIELD FROM OQC INSPECTION
            // if(response['oqc_details'] != null){
                // $('#txtMatYield').val(response['oqc_details']['yield'])
            // }

            $('#txtProdLotView').val(response['prod_lot_no']);
            $('#txtNGCount').val(response['ng_count']);

            let arrayOperators = response['operator'].split(", ");

       
            $('#selOperator').val(arrayOperators).trigger('change');

            if(response['status'] == 0){
                $('#radioIQC').prop('checked', true);
                $('#radioMassProd').prop('checked', false);
                $('#radioResetup').prop('checked', false);
                $('.matNo').prop('readonly', true);
                
                
            }
            else if(response['status'] == 3){
                $('#radioIQC').prop('checked', false);
                $('#radioMassProd').prop('checked', false);
                $('#radioResetup').prop('checked', true);
            }
            else{
                $('#radioIQC').prop('checked', false);
                $('#radioMassProd').prop('checked', true);
                $('#radioResetup').prop('checked', false);

                $('.matNo').prop('readonly', true);

            }
            $('input[name="cut_point"]').prop('disabled', true);

            if(response['cut_off_point'] == 0){ // without cutpoints
                $('#radioCutPointWithout').prop('checked', true);
                // $('#radioCutPointWith').prop('checked', false);

            }else{ // with cutpoints
                // $('#radioCutPointWithout').prop('checked', false);
                $('#radioCutPointWith').prop('checked', true);
                $('#txtNoCut').val(response['no_of_cuts'])
            }

            $(`#txtMaterialLot`).val(response['material_lot_no']);

            if(btnFunction == 0){ // Viewing
                $('#saveProdData').hide();

                $('#formProdData :input').attr('readonly','readonly');
                $('#formProdDataSecondStamp :input').attr('readonly','readonly');
                $('#selOperator').prop('disabled', true);
                
            }
            else if(btnFunction == 1){ // FOR MASS PROD INPUTTING
                $('#selOperator').prop('disabled', true);
                $('#txtOptShift').prop('readonly', true);
                $('#txtInptCoilWeight').prop('readonly', true);
                $('#txtSetupPin').prop('readonly', true);
                $('#txtAdjPin').prop('readonly', true);
                $('#txtQcSamp').prop('readonly', true);
                $('#txtTargetOutput').prop('readonly', true);
                $('#txtNGCount').prop('readonly', false);

                $('#txtInptPins', $('#formProdDataSecondStamp')).prop('readonly', true);
                $('#txtActQty', $('#formProdDataSecondStamp')).prop('readonly', true);
                $('input[name="tray"]',  $('#formProdDataSecondStamp')).prop('disabled', false);

                $('#saveProdData').show();
            }
            else if(btnFunction == 2){ // For Resetup
                $('#formProdData').find('input').prop('readonly', true);
                $('#formProdDataSecondStamp').find('input').prop('readonly', true);

                $('#txtSetupPin').prop('readonly', false);
                $('#txtAdjPin').prop('readonly', false);
                $('#txtQcSamp').prop('readonly', false);
    
                $('#selOperator').prop('disabled', true);
                $('#txtNGCount').prop('readonly', false);
                $('#divRemarks').removeClass('d-none');
                
                
            }
            else if(btnFunction == 3){ // For edit to resetup
                $('#formProdData').find('input').prop('readonly', true);
                $('#formProdDataSecondStamp').find('input').prop('readonly', true);

                // setTimeout(() => {
                    $('#txtSetupPin').prop('readonly', false);
                    $('#txtAdjPin').prop('readonly', false);
                    $('#txtQcSamp').prop('readonly', false);
    
                // }, 200);
                $('#selOperator').prop('disabled', true);
                $('#txtNGCount').prop('readonly', false);

                $('#radioIQC').prop('checked', false);
                $('#radioMassProd').prop('checked', false);
                $('#radioResetup').prop('checked', true);
                $('#divRemarks').removeClass('d-none');

            }

            if(stampCat == 1){
                $('#modalProdData').modal('show');
                $('#txtTargetOutput').val(response['ppc_target_output'])
                $('#txtInptCoilWeight').val(response['input_coil_weight'])

                
            }
            else{
                $('#modalProdSecondStamp').modal('show');
                $('#txtTargetOutput').val(response['target_output'])
                $('#txtInptPins').val(response['input_pins'])
                $('#txtActQty').val(response['actual_qty'])

            }

            
        }
    });
}

const printProdData = async (id, stampCat) => {
    await $.ajax({
        type: "get",
        url: "print_qr_code",
        data: {
            "id"       : id,
            "stamp_cat": stampCat
        },
        dataType: "json",
        success: function (response) {
            response['label_hidden'][0]['id'] = id;
          
            console.log(response['label_hidden'][0]);
            for(let x = 0; x < response['label_hidden'].length; x++){
                let dataToAppend = `
                <img class='hiddnQr' src="${response['label_hidden'][x]['img']}" style="max-width: 200px;"></img>
                `;
                $('#hiddenPreview').append(dataToAppend)
            }
          
            // <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->margin(5)->errorCorrection('H')->generate('0')) !!}" id="img_barcode_PO" style="max-width: 200px;"></img>
            $("#img_barcode_PO").attr('src', response['qrCode']);
            $("#img_barcode_PO_text").html(response['label']);
            img_barcode_PO_text_hidden = response['label_hidden'];
            $('#modalPrintQr').modal('show');
        }
    });
}

const checkMatrix = async (code, name, process) => {
    await $.ajax({
        type: "get",
        url: "check_matrix",
        data: {
            "code"   : code,
            "name"   : name,
            'process': process
        },
        dataType: "json",
        beforeSend: function(){
            getOperatorList($('.selOpName'));
            
        },
        success: function (response) {
            if(response['result'] == 1){ // FIRST STAMPING
                $('#txtPoNumber').val(prodData['poReceiveData']['OrderNo']);
                $('#txtPoQty').val(prodData['poReceiveData']['OrderQty']);
                $('#txtPartCode').val(prodData['poReceiveData']['ItemCode']);
                $('#txtMatName').val(prodData['poReceiveData']['ItemName']);
                $('#txtDrawingNo').val(prodData['drawings']['drawing_no']);
                $('#txtDrawingRev').val(prodData['drawings']['rev']);
                // $('#txtOptName').val($('#globalSessionName').val());
                $('#modalProdData').modal('show');
            }
            else if(response['result'] == 2){ // SECOND STAMPING
                console.log(poDetails);
                $('#txtPoNumber').val(poDetails['po_num']);
                $('#txtPoQty').val(poDetails['po_qty']);
                $('#txtPartCode').val(poDetails['part_code']);
                $('#txtMatName').val(poDetails['material_name']);
                $('#txtDrawingNo').val(poDetails['drawing_no']);
                $('#txtDrawingRev').val(poDetails['drawing_rev']);
                $('#modalProdSecondStamp').modal('show');
            }
            else{
                toastr.error(`${response['msg']}`);

            }
            
        }
    });
}

const getProdLotNoCtrl = (po) => {
    $.ajax({
        type: "get",
        url: "get_prod_lot_no_ctrl",
        data: {
            "po" : po
        },
        dataType: "json",
        beforeSend: function(){
            getOperatorList($('.selOpName'));

        },
        success: function (response) {
            $('#txtCtrlCounter').val(response['ctrl']);
            $('#prodLotNoAuto').val(`${prodData['drawings']['rev']}${response['year']}${response['month']}${response['day']}-${response['ctrl']}`)
        }
    });
}

const getOperatorList = (cboElement) => {
    $.ajax({
        type: "get",
        url: "get_operator_list",
        data: "",
        dataType: "json",
        success: function (response) {
            let result = "";
            for(let x = 0; x<response.length; x++){
                result += `<option value="${response[x]['employee_id']}">${response[x]['firstname']} ${response[x]['lastname']}</option>`;
            }

            cboElement.html(result);
        }
    });
}

const changePrintCount = (printedId) => {
    $.ajax({
        type: "get",
        url: "change_print_count",
        data: {
            id: printedId
        },
        dataType: "dataType",
        success: function (response) {
            
        }
    });
}

const getSecondStampReq = (params) => {
    $.ajax({
        type: "get",
        url: "get_2_stamp_reqs",
        data: {
            "params" : params
        },
        dataType: "json",
        beforeSend: function(){
            getOperatorList($('.selOpName'));
        },
        success: function (response) {
            
            $('#txtSearchPONum').val(response['poDetails'][0]['po_num'])
            $('#txtSearchMatName').val(response['poDetails'][0]['material_name'])
            $('#txtSearchMatCode').val(response['poDetails'][0]['part_code'])
            $('#txtSearchPO').val(response['poDetails'][0]['po_qty'])

            poDetails = response['poDetails'][0];
            $('#txtCtrlCounter').val(response['ctrl']);
            autogenLotNum = `${response['poDetails'][0]['drawing_rev']}${response['year']}${response['month']}${response['day']}-${response['ctrl']}`
            // $('#prodLotNoAuto').val(`${response['poDetails'][0]['drawing_rev']}${response['year']}${response['month']}${response['day']}-${response['ctrl']}`)
            dtDatatableProdSecondStamp.draw();
            $('#modalScanPO').modal('hide');

        },
        error: function(data, xhr, status){
            toastr.error('PO Not Found');
            $('#txtSearchMatName').val('');
            $('#txtSearchPO').val('');
            $('#modalScanPO').modal('hide');

        }
    });
}
// const getHistoryDetails = async (id) => {
//     // await $.ajax({
//     //     type: "get",
//     //     url: "get_history_details",
//     //     data: {
//     //         "id" : id,
//     //     },
//     //     dataType: "json",
//     //     success: function (response) {
//         // dtDatatableHistory.draw();


//             // $('#modalHistory').modal('show');
//     //     }
//     // });
// }

const saveSublot = () => {
    $.ajax({
        type: "post",
        url: "save_sublot",
        data: $('#formSublot').serialize(),
        dataType: "json",
        success: function (response) {
            if(response['result'] == 1){
                toastr.success('Successfully Updated!');
                $('#modalMultipleSublot').modal('hide');
                dtDatatableProdSecondStamp.draw();
            }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getSublotById = (id) => {
    $.ajax({
        type: "get",
        url: "get_sublot_by_id",
        data: {
            'id' : id
        },
        dataType: "json",
        beforeSend: function(){
            $('#btnSaveSublot').hide();
            $('#buttons').hide();
        },
        success: function (response) {
            for(let x = 0; x < response['stampSubLot']['second_stamping_sublots'].length; x++){
                let counter = x + 1;
                console.log('counter',counter);
                console.log('x',x);
                if($('#txtSublotMultipleCounter').val() != counter){

                    $('#btnAddSublot').click();
                }
                $(`#txtSublotNo_${counter}`).val(response['stampSubLot']['second_stamping_sublots'][x]['counter'])
                $(`#txtSublotQty_${counter}`).val(response['stampSubLot']['second_stamping_sublots'][x]['batch_qty'])

            }

            $('#txtSubLotPoNumber', $('#formSublot')).val(response['stampSubLot']['po_num'])
            $('#txtSubLotLotNo', $('#formSublot')).val(response['stampSubLot']['prod_lot_no'])
            $('#txtSubLotShipOutput', $('#formSublot')).val(response['stampSubLot']['ship_output'])
            $('#modalMultipleSublot').modal('show');

        },
        error: function(data, xhr, status){
            // toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            toastr.error('No Data Exist')
        }
    });
}

const validateScannedMaterial = (deviceName, MaterialName, process, callback) => {
    $.ajax({
        type: "get",
        url: "get_matrix_for_mat_validation",
        data: {
            'device_name' : deviceName,
            'material_name' : MaterialName,
            'process_name' : process
        },
        dataType: "json",
        success: function (response) {
            let value
            if(response['data'].length > 0){
                value = true;
            }
            else{
                value = false;
            }

            callback(value);
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const printQrForIPQC = (id, stampCat) => {
    $.ajax({
        type: "get",
        url: "print_qr_for_ipqc",
        data: {
            "id" : id,
            "stamp_cat" : stampCat
        },
        dataType: "json",
        success: function (response) {
            response['label_hidden'][0]['id'] = id;
            response['label_hidden'][0]['stampCat'] = stampCat;
            $("#img_barcode_PO").attr('src', response['qrCode']);
            $("#img_barcode_PO_text").html(response['label']);
            img_barcode_PO_text_hidden = response['label_hidden'];
            $('#modalPrintQr').modal('show');
        }
    });
}

/*
    * This is common script for first and second stamping
    * For Scanning of operator ID for valueing in operator name field
*/
let operatorArray = [];
$('#btnScanOperator').on('click', function(){
    $('#modalScanSelOp').modal('show');
    operatorArray = [];
    $('#selOperator').val(operatorArray).trigger('change');

});

$('#modalScanSelOp').on('shown.bs.modal', function () {
    $('#txtScanOpId').focus();
});

$('#txtScanOpId').on('keyup', function(e){
    if(e.keyCode == 13){
        operatorArray.push($(this).val());
        console.log(operatorArray);
        $('#selOperator').val(operatorArray).trigger('change');

        $(this).val('');
    }
});


$(document).on('click', '.btnPrintIPQC', function(e){
    let id = $(this).data('id');
    let stampCat = $(this).data('stampcat');

    printQrForIPQC(id, stampCat)
});
