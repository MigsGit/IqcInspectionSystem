const saveDailyChecksheet = (scannedId) => {
    let data = $.param({'scanned_id': scannedId}) + "&" + $('#formAddDailyChecksheet').serialize();

    $.ajax({
        type: "post",
        url: "add_daily_checksheet",
        data: data,
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                $('#modalScanQRSave').modal('hide');
                $('#modalAddDailyChecksheet').modal('hide');
                $("#formAddDailyChecksheet")[0].reset();
                dtDailyChecksheet.draw();
                toastr.success('Transaction Successful!');
            }
        },
        error: function(data, xhr, status){
            toastr.error(`${data.responseJSON.msg}`);
        }
    });
}

const getDailyChecksheet = (dailyCheckSheetId, checkStatus) => {
    $.ajax({
        type: "get",
        url: "get_daily_checksheet_data",
        data: {
            "id" : dailyCheckSheetId,
            "stat" : checkStatus
        },
        dataType: "json",
        beforeSend: function (){
            getMachineForChecksheet($('#selMachine'));
            // console.log(checkStatus);
            if(checkStatus == 0){
                $('#btnCheck').prop('hidden', false);
                $('#btnSave').hide();
                $('#btnConform').hide();
                $('input', $('#formAddDailyChecksheet')).prop('disabled', true);
                $('select', $('#formAddDailyChecksheet')).prop('disabled', true);
            }else if(checkStatus == 1){
                $('#btnConform').prop('hidden', false);
                $('#btnSave').hide();
                $('#btnCheck').hide();
                $('input', $('#formAddDailyChecksheet')).prop('disabled', true);
                $('select', $('#formAddDailyChecksheet')).prop('disabled', true);
            }else if(checkStatus == 2){
                $('#btnSave').hide();
                $('#btnCheck').hide();
                $('#btnConform').hide();
                $('input', $('#formAddDailyChecksheet')).prop('disabled', true);
                $('select', $('#formAddDailyChecksheet')).prop('disabled', true);
            }
        },
        success: function (response) {
            $('#modalAddDailyChecksheet').modal('show');

            if(response['machine_id'] == 2 && response['unit_no'] == 4){
                    console.log('show kyori');

                    $('#KyoriHeaderId').removeClass('text-block-header')
                    $('#kyoriBodyId').removeClass('text-block-header')
                    $('#komatsuHeaderId').addClass('text-block-header')
                    $('#komatsuBodyId').addClass('text-block-header')

                if(response['checked_by_engineer'] != null){
                    $('#txtCheckedBy').val(`${response['checked_by_engineer']}`);
                }

                if(response['conformed_by_qc'] != null){
                    $('#txtConformedByQC').val(`${response['conformed_by_qc']}`);
                }

                $('#txtunitNo').val(response['unit_no']).trigger('change');
                $('#selMachine').val(response['machine_id']).trigger('change');
                $('#txtConformedBy').val(`${response['conformed_by']}`);
                $('#txtConductedBy').val(`${response['conducted_by_operator']}`);
                $('#txtCheckDate').val(`${response['date']}`);
                $('#txtCheckTime').val(`${response['time']}`);
                $('#txtMonth').val(`${response['month']}`);
                $('#txtCheckDiv').val(`${response['division']}`);
                $('#txtArea').val(`${response['area']}`);
                $('#txtActualMeasurement').val(`${response['actual_measurement_d1']}`);
                $('#txtActualMeasurement2').val(`${response['actual_measurement_d2']}`);

                $(`input:radio[name="result_1"][value="${response['result_d1']}"]`).prop('checked', true);
                $(`input:radio[name="result_2"][value="${response['result_d2']}"]`).prop('checked', true);
                $(`input:radio[name="result_3"][value="${response['result_d3']}"]`).prop('checked', true);
                $(`input:radio[name="result_4"][value="${response['result_d4']}"]`).prop('checked', true);
                $(`input:radio[name="result_5"][value="${response['result_d5']}"]`).prop('checked', true);
                $(`input:radio[name="result_6"][value="${response['result_d6']}"]`).prop('checked', true);
                $(`input:radio[name="result_7"][value="${response['result_d7']}"]`).prop('checked', true);
                $(`input:radio[name="result_8"][value="${response['result_d8']}"]`).prop('checked', true);
                $(`input:radio[name="result_9"][value="${response['result_d9']}"]`).prop('checked', true);
                $(`input:radio[name="result_10"][value="${response['result_d10']}"]`).prop('checked', true);
                $(`input:radio[name="result_11"][value="${response['result_d11']}"]`).prop('checked', true);
                $(`input:radio[name="result_12"][value="${response['result_d12']}"]`).prop('checked', true);
                $(`input:radio[name="result_13"][value="${response['result_d13']}"]`).prop('checked', true);
            }else if(response['machine_id'] == 4 && response['unit_no'] == 3){
                console.log('show komatsu');

                $('#komatsuHeaderId').removeClass('text-block-header')
                $('#komatsuBodyId').removeClass('text-block-header')
                $('#KyoriHeaderId').addClass('text-block-header')
                $('#kyoriBodyId').addClass('text-block-header')

            if(response['checked_by_engineer'] != null){
                $('#txtCheckedBy').val(`${response['checked_by_engineer']}`);
            }

            if(response['conformed_by_qc'] != null){
                $('#txtConformedByQC').val(`${response['conformed_by_qc']}`);
            }

            $('#txtunitNo').val(response['unit_no']).trigger('change');
            $('#selMachine').val(response['machine_id']).trigger('change');
            $('#txtConformedBy').val(`${response['conformed_by']}`);
            $('#txtConductedBy').val(`${response['conducted_by_operator']}`);
            $('#txtCheckDate').val(`${response['date']}`);
            $('#txtCheckTime').val(`${response['time']}`);
            $('#txtMonth').val(`${response['month']}`);
            $('#txtCheckDiv').val(`${response['division']}`);
            $('#txtArea').val(`${response['area']}`);
            $('#txtKomatsuActualMeasurement2').val(`${response['actual_measurement_d1']}`);
            $('#txtKomatsuActualMeasurement3').val(`${response['actual_measurement_d2']}`);

            $(`input:radio[name="komatsu_result_1"][value="${response['result_d1']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_2"][value="${response['result_d2']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_3"][value="${response['result_d3']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_4"][value="${response['result_d4']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_5"][value="${response['result_d5']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_6"][value="${response['result_d6']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_7"][value="${response['result_d7']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_8"][value="${response['result_d8']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_9"][value="${response['result_d9']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_10"][value="${response['result_d10']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_11"][value="${response['result_d11']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_12"][value="${response['result_d12']}"]`).prop('checked', true);
            $(`input:radio[name="komatsu_result_13"][value="${response['result_d13']}"]`).prop('checked', true);
            }

            
        },
        error: function(data, xhr, status){
            alert('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const saveWeeklyChecksheet = (scannedId) => {
    let data = $.param({'scanned_id': scannedId}) + "&" + $('#formAddWeeklyChecksheet').serialize();

    $.ajax({
        type: "post",
        url: "add_weekly_checksheet",
        data: data,
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                $('#modalScanQrSaveWeekly').modal('hide');
                $('#modalAddWeeklyChecksheet').modal('hide');
                dtWeeklyChecksheet.draw();
                toastr.success('Transaction Successful!');
            }
        },
        error: function(data, xhr, status){
            toastr.error(`${data.responseJSON.msg}`);
        }
    });
}


const getWeeklyChecksheet = (weeklyChecksheetId, weeklyChecksheetStatus) => {
    $.ajax({
        type: "get",
        url: "get_weekly_checksheet_data",
        data: {
            "id" : weeklyChecksheetId,
            "stat" : weeklyChecksheetStatus
        },
        dataType: "json",
        beforeSend: function (){
            getMachineForChecksheet($('#selWeeklyMachine'));
            // console.log(weeklyChecksheetStatus);
            if(weeklyChecksheetStatus == 0){
                $('#btnWeeklyCheck').prop('hidden', false);
                $('#btnWeeklySave').hide();
                $('#btnWeeklyConform').hide();
                $('input', $('#formAddWeeklyChecksheet')).prop('disabled', true);
                $('select', $('#formAddWeeklyChecksheet')).prop('disabled', true);
            }else if(weeklyChecksheetStatus == 1){
                $('#btnWeeklyConform').prop('hidden', false);
                $('#btnWeeklySave').hide();
                $('#btnWeeklyCheck').hide();
                $('input', $('#formAddWeeklyChecksheet')).prop('disabled', true);
                $('select', $('#formAddWeeklyChecksheet')).prop('disabled', true);
            }else{
                $('#btnWeeklySave').hide();
                $('#btnWeeklyCheck').hide();
                $('#btnWeeklyConform').hide();
                $('input', $('#formAddWeeklyChecksheet')).prop('disabled', true);
                $('select', $('#formAddWeeklyChecksheet')).prop('disabled', true);
            }
        },
        success: function (response) {
            $('#modalAddWeeklyChecksheet').modal('show');
            // console.log(response['machine_id']);

            if(response['machine_id'] == 2 && response['unit_no'] == 4){
                // console.log('show kyori');

                if(response['checked_by_engineer'] != null){
                    $('#txtWeeklyCheckedBy').val(`${response['checked_by_engineer']}`);
                }
                if(response['conformed_by_qc'] != null){
                    $('#txtWeeklyConformedByQC').val(`${response['conformed_by_qc']}`);
                }
    
                $('#txtWeeklyUnitNo').val(response['unit_no']).trigger('change');
                $('#selWeeklyMachine').val(response['machine_id']).trigger('change');
                $('#txtWeeklyConformedBy').val(`${response['conformed_by']}`);
                $('#txtWeeklyConductedBy').val(`${response['conducted_by_operator']}`);
                $('#txtWeeklyCheckDate').val(`${response['date']}`);
                $('#txtWeeklyCheckTime').val(`${response['time']}`);
                $('#txtWeeklyMonth').val(`${response['month']}`);
                $('#txtWeeklyCheckDiv').val(`${response['division']}`);
                $('#txtWeeklyArea').val(`${response['area']}`);
                $('#txtWeek').val(`${response['week']}`);
    
                $(`input:radio[name="result_w1"][value="${response['result_w1']}"]`).prop('checked', true);
                $(`input:radio[name="result_w2"][value="${response['result_w2']}"]`).prop('checked', true);
                $(`input:radio[name="result_w3"][value="${response['result_w3']}"]`).prop('checked', true);
            }else if (response['machine_id'] == 4 && response['unit_no'] == 3){
                // console.log('show komatsu');

                if(response['checked_by_engineer'] != null){
                    $('#txtWeeklyCheckedBy').val(`${response['checked_by_engineer']}`);
                }
                if(response['conformed_by_qc'] != null){
                    $('#txtWeeklyConformedByQC').val(`${response['conformed_by_qc']}`);
                }
    
                $('#txtWeeklyUnitNo').val(response['unit_no']).trigger('change');
                $('#selWeeklyMachine').val(response['machine_id']).trigger('change');
                $('#txtWeeklyConformedBy').val(`${response['conformed_by']}`);
                $('#txtWeeklyConductedBy').val(`${response['conducted_by_operator']}`);
                $('#txtWeeklyCheckDate').val(`${response['date']}`);
                $('#txtWeeklyCheckTime').val(`${response['time']}`);
                $('#txtWeeklyMonth').val(`${response['month']}`);
                $('#txtWeeklyCheckDiv').val(`${response['division']}`);
                $('#txtWeeklyArea').val(`${response['area']}`);
                $('#txtWeek').val(`${response['week']}`);
    
                $(`input:radio[name="komatsu_result_w1"][value="${response['result_w1']}"]`).prop('checked', true);
                $(`input:radio[name="komatsu_result_w2"][value="${response['result_w2']}"]`).prop('checked', true);
                $(`input:radio[name="komatsu_result_w3"][value="${response['result_w3']}"]`).prop('checked', true);
            }


            
        },
        error: function(data, xhr, status){
            alert('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const saveMonthlyChecksheet = (scannedId) => {
    let data = $.param({'scanned_id': scannedId}) + "&" + $('#formAddMonthlyChecksheet').serialize();

    $.ajax({
        type: "post",
        url: "add_monthly_checksheet",
        data: data,
        dataType: "json",
        success: function (response) {
            if(response['result'] == true){
                $('#modalScanQrSaveMonthly').modal('hide');
                $('#modalAddMonthlyChecksheet').modal('hide');
                dtMonthlyChecksheet.draw();
                toastr.success('Transaction Successful!');
            }
        },
        error: function(data, xhr, status){
            toastr.error(`${data.responseJSON.msg}`);
        }
    });
}

const getMonthlyChecksheet = (monthlyChecksheetId, monthlyChecksheetStatus) => {
    $.ajax({
        type: "get",
        url: "get_monthly_checksheet_data",
        data: {
            "id" : monthlyChecksheetId,
            "stat" : monthlyChecksheetStatus
        },
        dataType: "json",
        beforeSend: function (){
            getMachineForChecksheet($('#selMonthlyMachine'));
            // console.log(monthlyChecksheetStatus);
            if(monthlyChecksheetStatus == 0){
                $('#btnMonthlyCheck').prop('hidden', false);
                $('#btnMonthlySave').hide();
                $('#btnMonthlyConform').hide();
                $('input', $('#formAddMonthlyChecksheet')).prop('disabled', true);
                $('select', $('#formAddMonthlyChecksheet')).prop('disabled', true);
            }else if(monthlyChecksheetStatus == 1){
                $('#btnMonthlyConform').prop('hidden', false);
                $('#btnMonthlySave').hide();
                $('#btnMonthlyCheck').hide();
                $('input', $('#formAddMonthlyChecksheet')).prop('disabled', true);
                $('select', $('#formAddMonthlyChecksheet')).prop('disabled', true);
            }else{
                $('#btnMonthlySave').hide();
                $('#btnMonthlyCheck').hide();
                $('#btnMonthlyConform').hide();
                $('input', $('#formAddMonthlyChecksheet')).prop('disabled', true);
                $('select', $('#formAddMonthlyChecksheet')).prop('disabled', true);
            }
        },
        success: function (response) {
            $('#modalAddMonthlyChecksheet').modal('show');
            // console.log(response['machine_id']);

            if(response['machine_id'] == 2 && response['unit_no'] == 4){
                // console.log('show kyori');

                if(response['checked_by_engineer'] != null){
                    $('#txtMonthlyCheckedBy').val(`${response['checked_by_engineer']}`);
                }
                if(response['conformed_by_qc'] != null){
                    $('#txtMonthlyConformedByQC').val(`${response['conformed_by_qc']}`);
                }
    
                $('#txtMonthlyUnitNo').val(response['unit_no']).trigger('change');
                $('#selMonthlyMachine').val(response['machine_id']).trigger('change');
                $('#txtMonthlyConformedBy').val(`${response['conformed_by']}`);
                $('#txtMonthlyConductedBy').val(`${response['conducted_by_operator']}`);
                $('#txtMonthlyCheckDate').val(`${response['date']}`);
                $('#txtMonthlyCheckTime').val(`${response['time']}`);
                $('#txtMonthlyMonth').val(`${response['month']}`);
                $('#txtMonthlyCheckDiv').val(`${response['division']}`);
                $('#txtMonthlyArea').val(`${response['area']}`);

                if(response['result_remarks_m1'] != null){
                    $('#txtMonthlyRemarks1').val(`${response['result_remarks_m1']}`);
                }

                if(response['result_remarks_m2'] != null){
                    $('#txtMonthlyRemarks2').val(`${response['result_remarks_m2']}`);
                }

                if(response['result_remarks_m3'] != null){
                    $('#txtMonthlyRemarks3').val(`${response['result_remarks_m3']}`);
                }

                if(response['result_remarks_m4'] != null){
                    $('#txtMonthlyRemarks4').val(`${response['result_remarks_m4']}`);
                }

                if(response['result_remarks_m5'] != null){
                    $('#txtMonthlyRemarks5').val(`${response['result_remarks_m5']}`);
                }

                $(`input:radio[name="result_m1"][value="${response['result_m1']}"]`).prop('checked', true);
                $(`input:radio[name="result_m2"][value="${response['result_m2']}"]`).prop('checked', true);
                $(`input:radio[name="result_m3"][value="${response['result_m3']}"]`).prop('checked', true);
                $(`input:radio[name="result_m4"][value="${response['result_m4']}"]`).prop('checked', true);
                $(`input:radio[name="result_m5"][value="${response['result_m5']}"]`).prop('checked', true);
                

            }else if (response['machine_id'] == 4 && response['unit_no'] == 3){
                if(response['checked_by_engineer'] != null){
                    $('#txtMonthlyCheckedBy').val(`${response['checked_by_engineer']}`);
                }
                if(response['conformed_by_qc'] != null){
                    $('#txtMonthlyConformedByQC').val(`${response['conformed_by_qc']}`);
                }

                if(response['result_remarks_m1'] != null){
                    $('#txtKomatsuRemarksId1').val(`${response['result_remarks_m1']}`);
                }

                if(response['result_remarks_m2'] != null){
                    $('#txtKomatsuRemarksId2').val(`${response['result_remarks_m2']}`);
                }
    
                $('#txtMonthlyUnitNo').val(response['unit_no']).trigger('change');
                $('#selMonthlyMachine').val(response['machine_id']).trigger('change');
                $('#txtMonthlyConformedBy').val(`${response['conformed_by']}`);
                $('#txtMonthlyConductedBy').val(`${response['conducted_by_operator']}`);
                $('#txtMonthlyCheckDate').val(`${response['date']}`);
                $('#txtMonthlyCheckTime').val(`${response['time']}`);
                $('#txtMonthlyMonth').val(`${response['month']}`);
                $('#txtMonthlyCheckDiv').val(`${response['division']}`);
                $('#txtMonthlyArea').val(`${response['area']}`);
    
                $(`input:radio[name="komatsu_result_m1"][value="${response['result_m1']}"]`).prop('checked', true);
                $(`input:radio[name="komatsu_result_m2"][value="${response['result_m2']}"]`).prop('checked', true);
            }            
        },
        error: function(data, xhr, status){
            alert('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

const getTechnicianForMaintenanceRepairHighlights = (cboElement) => {
    $.ajax({
        type: "get",
        url: "get_technician_repair_highlights",
        // data: "",
        dataType: "json",
        success: function (response) {
            console.log('clg', response);
            let result;

            result += `<option value="0" selected disabled>-- Select --</option>`;
            for(let x = 0; x< response.length; x++){
                result += `<option value="${response[x]['id']}">${response[x]['firstname']} ${response[x]['lastname']}</option>`;
            }

            cboElement.html(result);
        }
    });
}

