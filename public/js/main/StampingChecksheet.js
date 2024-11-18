const saveChecksheet = (scannedId) => {
    let data = $.param({'scanned_id': scannedId}) + "&" + $('#formAddChecksheet').serialize();

    $.ajax({
        type: "post",
        url: "save_checksheet",
        data: data,
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                $('#modalScanQRSave').modal('hide');
                $('#modalAddChecksheet').modal('hide');
                dtDatatableChecksheet.draw();
                toastr.success('Transaction Successful!');
            }
        },
        error: function(data, xhr, status){
            toastr.error(`${data.responseJSON.msg}`);
        }
    });
}

const getMachineForChecksheet = (cboElement) => {
    $.ajax({
        type: "get",
        url: "get_machine_dropdown",
        // data: "",
        dataType: "json",
        success: function (response) {
            let result;
            console.log('response', response);

            result += `<option value="0" selected disabled>-- Select --</option>`;
            for(let x = 0; x< response.length; x++){
                result += `<option value="${response[x]['id']}">${response[x]['machine_name']}</option>`;
            }

            cboElement.html(result);
        }
    });
}

const changeStatusChecksheet = (status, checksheetId, token, remarks) => { // Status => 1-approved, 2-disapproved
    $.ajax({
        type: "post",
        url: "change_status",
        data: {
            "_token" : token,
            "status" : status,
            "id"     : checksheetId,
            "remarks": remarks
        },
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                toastr.success('Successfully Saved!');
                dtDatatableChecksheet.draw();
            }
        },
        error: function(data, xhr, status){
            alert('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getChecksheet = (id, checkFunction) => {
    $.ajax({
        type: "get",
        url: "get_checksheet_data",
        data: {
            "id" : id,
            "fn" : checkFunction
        },
        dataType: "json",
        beforeSend: function (){
            getMachineForChecksheet($('#selMachine'));
            if(checkFunction == 0){
                $('#btnSave').hide();
                $('input', $('#formAddChecksheet')).prop('disabled', true);
                $('#txtRemarks', $('#formAddChecksheet')).prop('disabled', true);
                $('select', $('#formAddChecksheet')).prop('disabled', true);
            }
        },
        success: function (response) {
            $('#modalAddChecksheet').modal('show');

            $('#txtCheckId').val(`${response['id']}`);
            // $('#txtCheckBy').val(`${response['checked_by']}`);

            if(response['checked_by'] != null){
                $('#txtCheckBy').val(`${response['checkedby_fname']} ${response['checkedby_lname']}`);

            }
            $('#txtCheckDate').val(response['date']);
            $('#txtCheckCondBy').val(response['conducted_by']);
            $('#txtShift').val(response['shift']);
            setTimeout(() => {
                
                $('#selMachine').val(response['machine_id']).trigger('change');
            }, 500);
            $('#txtRemarks').val(response['remarks']);
            // $("input[name=checkA1_1]").val();
            $(`input:radio[name="checkA1_1"][value="${response['checksheet_A_1_1']}"]`).prop('checked', true);
            $(`input:radio[name="checkA1_2"][value="${response['checksheet_A_1_2']}"]`).prop('checked', true);
            $(`input:radio[name="checkA1_3"][value="${response['checksheet_A_1_3']}"]`).prop('checked', true);
            $(`input:radio[name="checkA1_4"][value="${response['checksheet_A_1_4']}"]`).prop('checked', true);
            $(`input:radio[name="checkA1_5"][value="${response['checksheet_A_1_5']}"]`).prop('checked', true);
            $(`input:radio[name="checkA1_6"][value="${response['checksheet_A_1_6']}"]`).prop('checked', true);

            $(`input:radio[name="checkA2_1"][value="${response['checksheet_A_2_1']}"]`).prop('checked', true);
            $(`input:radio[name="checkA2_2"][value="${response['checksheet_A_2_2']}"]`).prop('checked', true);
            $(`input:radio[name="checkA2_3"][value="${response['checksheet_A_2_3']}"]`).prop('checked', true);
            $(`input:radio[name="checkA2_4"][value="${response['checksheet_A_2_4']}"]`).prop('checked', true);

            $(`input:radio[name="checkA3_1"][value="${response['checksheet_A_3_1']}"]`).prop('checked', true);
            
        },
        error: function(data, xhr, status){
            alert('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}