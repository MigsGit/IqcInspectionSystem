
const getDocumentRequirement = (deviceName) => {
    $.ajax({
        type: "get",
        url: "get_fvi_doc",
        data: {
            "device_name" : deviceName
        },
        dataType: "json",
        success: function (response) {
            let aDrawing, gDrawing;
            if(response['aDrawing'].length == 1){
                aDrawing += `<option value='${response['aDrawing'][0]['doc_no']}' selected>${response['aDrawing'][0]['doc_no']}</option>`;
                $('#txtARevNo').val(response['aDrawing'][0]['rev_no']);
            }
            else{
                for (let index = 0; index < response['aDrawing'].length; index++) {
                    aDrawing += `<option value='${response['aDrawing'][index]['doc_no']}' selected>${response['aDrawing'][index]['doc_no']}</option>`;
                }
            }

            if(response['gDrawing'].length == 1){
                gDrawing += `<option value='${response['gDrawing'][0]['doc_no']}' selected>${response['gDrawing'][0]['doc_no']}</option>`;
                $('#txtGRevNo').val(response['gDrawing'][0]['rev_no']);

            }
            else{
                for (let index = 0; index < response['gDrawing'].length; index++) {
                    gDrawing += `<option value='${response['gDrawing'][index]['doc_no']}' selected>${response['gDrawing'][index]['doc_no']}</option>`;
                }
            }


            $('#selADrawing', $('#formEditFVIDetails')).html(aDrawing)
            $('#selGDrawing', $('#formEditFVIDetails')).html(gDrawing)

        }
    });
}

const getAssemblyLine = () => {
    $.ajax({
        type: "get",
        url: "get_assembly_line",
        // data: "data",
        dataType: "json",
        success: function (response) {
            let option;
            option += `<option selected disabled>--SELECT--</option>`
            for (let index = 0; index < response['details'].length; index++) {
                option += `<option value="${response['details'][index]['id']}">${response['details'][index]['station_name']}</option>`;
            }

            $('#selFVIAssLine').html(option);
        }
    });
}

const saveVisualDetails = (form) => {
    $.ajax({
        type: "post",
        url: "save_visual_details",
        data: form.serialize(),
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                toastr.success(`${response['msg']}`);
                $('#txtHiddenFviId').val(`${response['id']}`)
                $('#txtFVILotNo').val(`${response['lot_no']}`)
                $('#txtFVIBundleNo').val(`${response['bundle_no']}`)
                let dt = moment(response['created_at']).format('MM/DD/YYYY HH:mm');
                $('#txtFVICreatedAt').val(`${dt}`)
                $('#txtFVIAppDT').val(`${dt}`)


                $('#divBtnVisualDetails').addClass('d-none');
                $('#btnEditFviDetails').prop('disabled', true);
                $('#txtFVIRemarks').prop('readonly', true);
                $('#selFVIAssLine').prop('disabled', true);

                $('#btnAddFVIRuncard').prop('disabled', false);
                dtVisualInspection.draw();
            }
        },
        error: function(data, xhr, status){
            if(data.status == 422){
                let errors = data.responseJSON ;
                console.log(errors['error']['sel_assembly_line']);
                errorHandler( errors['error']['txt_remarks'], form.find('#txtFVIRemarks') );
                errorHandler( errors['error']['sel_assembly_line'], form.find('#selFVIAssLine') );
            }
            else{
                toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            }
        }
    });
}

const loadRuncardInfo = (scannedData) => {
    $.ajax({
        type: "get",
        url: "get_runcard_details",
        data: scannedData,
        dataType: "json",
        beforeSend: function(){
            runcardModList = [];
        },
        success: function (response) {
            console.log(scannedData);
            // console.log($('#txtFVIPoNum').val());
            let runcardNg = 0;
            console.log("cannedData['po_number']", scannedData['po_number']);
            console.log("$('#txtFVIPoNum').val()", $('#txtFVIPoNum').val());
            // if($('#txtFVIPoNum').val() != response['runcard']['po_number']){
            if($('#txtFVIPoNum').val() != scannedData['po_number']){
                toastr.error('Runcard is not for this PO.');
                return false;
            }

            $('#txtRuncardNumber').val(`${response['runcard']['runcard_no']}`)
            $('#txtRuncardOperatorName').val(scannedData['operator_name'])
            $('#txtRuncardInput').val(`${response['runcard']['fk_station_input_quantity']}`)
            $('#txtRuncardOutput').val(`${response['runcard']['fk_station_output_quantity']}`)
            for (let index = 0; index < response['runcardMod'].length; index++) {
                runcardNg = Number(runcardNg) + Number(response['runcardMod'][index]['mod_quantity'])
                runcardModList.push(
                    {
                        "mod": response['runcardMod'][index]['fk_defect_name'] ,
				        "qty": response['runcardMod'][index]['mod_quantity'] ,
                    }
                )
            }
            $('#txtRuncardNg').val(runcardNg);
            $('#pRCStatTotNoOfNG').html(runcardNg);


            dtRuncardStationMod.rows.add(
                runcardModList
            ).draw();

            $('#txtRuncardId').val(`${response['runcard']['id']}`)
            $('#txtRuncardStationId').val(`${response['runcard']['fk_station_id']}`)

            $('#modalScanning').modal('hide');
        }
    });
}

const saveRuncard = (form) => {
    let data = $.param({'fvi_id': $('#txtHiddenFviId').val() }) + "&" + form.serialize();
    $.ajax({
        type: "post",
        url: "save_runcard",
        data: data,
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                toastr.success(`${response['msg']}`);
                $('#modalFVIRuncard').modal('hide');
                dtFVIRuncards.draw();
            }
        },
        error: function(data, xhr, status){
            if(data.status == 422){
                toastr.error(data['responseJSON']['msg']);
                return;
            }
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getFviDetailsById = (id, status) => {
    $.ajax({
        type: "get",
        url: "get_fvi_details_by_id",
        data: {
            "id" : id
        },
        dataType: "json",
        beforeSend: function(){
            getDocumentRequirement($('#txtDeviceName').val());
        },
        success: function (response) {
            $('#txtHiddenFviId').val(response['id']);

            $('#txtFVIPoNum', $('#formEditFVIDetails')).val(response['po_no']);
            $('#txtFVIPoQTy', $('#formEditFVIDetails')).val(response['po_qty']);
            $('#txtFVIDevName', $('#formEditFVIDetails')).val(response['device_name']);
            $('#txtFVIDevCode', $('#formEditFVIDetails')).val(response['device_code']);
            $('#txtFVILotNo', $('#formEditFVIDetails')).val(response['lot_no']);
            $('#txtFVIBundleNo', $('#formEditFVIDetails')).val(response['bundle_no']);
            $('#txtFVIRemarks', $('#formEditFVIDetails')).val(response['remarks']);
            $('#selFVIAssLine', $('#formEditFVIDetails')).val(response['assembly_line']).trigger('change');

            $('#selADrawing', $('#formEditFVIDetails')).val(response['a_drawing_no']).trigger('change');
            $('#txtARevNo', $('#formEditFVIDetails')).val(response['a_drawing_rev']);
            $('#selGDrawing', $('#formEditFVIDetails')).val(response['g_drawing_no']).trigger('change');
            $('#txtGRevNo', $('#formEditFVIDetails')).val(response['g_drawing_rev']);

            $('#txtFVICreatedAt', $('#formEditFVIDetails')).val(response['created_at']);
            $('#txtFVIAppDT', $('#formEditFVIDetails')).val(response['created_at']);

            dtFVIRuncards.draw();
            $('#modalFVI').modal('show');

            $('#btnEditFviDetails').prop('disabled', true);
            $('#btnAddFVIRuncard').prop('disabled', false);
            // setTimeout(() => {
            //     $('#selFVIAssLine', $('#formEditFVIDetails')).prop('disabled', true);

            // }, 500);


            if(status == 1){
                $('#btnAddFVIRuncard').prop('disabled', true);
                $('#btnSubmitToLotApp').prop('disabled', true);
            }
        }
    });
}

const redirect_to_req_drawing = (inputId, selVal ) => {
    if ( selVal == 'N/A' || selVal == '' )
        alert('No Document Required')
    else{
        window.open("http://rapid/ACDCS/prdn_home_cnppts?doc_no="+selVal)
        checked_draw_count[inputId] = true

    }

    console.log(checked_draw_count);
}

const validateRuncardOutput = (devName, devCode, fn) => {
    $.ajax({
        type: "get",
        url: "validate_runcard_output",
        data: {
            "device_name" : devName,
            "device_code" : devCode
        },
        dataType: "json",
        success: function (response) {
            console.log(response['device']);
            if(fn == "btnAdd"){
                if(outputQty == response['device']['qty_per_box']){
                    console.log('equal na');
                    Swal.fire({
                        title: "Invalid",
                        text: "Max quantity was already met",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
                else{
                    // console.log('proceed');
                    $('#modalFVIRuncard').modal('show');

                }
            }
            else if (fn == "btnSaveRuncard"){
                let newOutput = Number(outputQty) + Number($('#txtRuncardOutput').val());
                if(newOutput > response['device']['qty_per_box']){
                    console.log('new output', newOutput);
                    console.log('matrix qty per box', response['device']['qty_per_box']);

                    Swal.fire({
                        title: "Invalid",
                        text: "Total output will be greater than box quantity on matrix.",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });

                }
                else{
                    // $('#modalFVIRuncard').modal('show');
                    saveRuncard($('#formFVIRuncard'))
                }
            }
            else if(fn == "btnSubmitToLotApp"){
                if(outputQty == 0){
                    Swal.fire({
                        title: "Invalid",
                        text: "Output quantity is 0",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
                else if(outputQty < response['device']['qty_per_box']){
                    Swal.fire({
                        title: "Warning",
                        html: "Output is not equal to matrix quantity. <br> Is this partial?",
                        icon: "warning",
                        confirmButtonColor: "#3085d6"
                    });
                    Swal.fire({
                        title: "Warning",
                        html: "Output is not equal to matrix quantity. <br> Is this partial?",
                        showDenyButton: true,
                        confirmButtonText: "Yes",
                        confirmButtonColor: "#2aa332",
                        denyButtonText: `No`,
                        icon: "warning",

                      }).then((result) => {
                        if (result.isConfirmed) {
                            scanningFunction = "partialSupervisor";
                            $('#modalScanQRSaveText').html('Please Scan Supervisor ID.')
                            $('#modalScanQRSave').modal('show');

                        }
                      });

                }
                else{
                    $('#modalScanQRSave').modal('show');

                }
            }

        }
    });

}

const SubmitToLotApp = (idNum) => {
    // console.log(idNum);
    $.ajax({
        type: "post",
        url: "submit_to_oqc_lot_app",
        data: {
            "_token" : token,
            "id" : $('#txtHiddenFviId').val(),
            "scanned_id" : idNum
        },
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                toastr.success('Succesfully Submitted!');
                $('#modalFVI').modal('hide');
                $('#modalScanQRSave').modal('hide');
                dtVisualInspection.draw();
            }
        }
    });
}

const loadSearchPo = (poNumber) => {
    $.ajax({
        type: "get",
        url: "search_po",
        data: {
            "po_number" : poNumber
        },
        dataType: "json",
        success: function (response) {
            if(response['details'] != null){
                console.log('response', response);
                $('#txtSearchPO').val(response['details']['po_number'])
                $('#txtDeviceName').val(response['details']['device_name'])
                $('#txtDeviceCode').val(response['details']['part_code'])
                $('#txtPoQty').val(response['details']['po_quantity'])
                dtVisualInspection.draw();
            }
            else{
                toastr.error('PO Not Found!');
            }


        }
    });
}
