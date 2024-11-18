function onTimeChange(inputElement) {
    let timeSplit = $(inputElement).val().split(':');
    console.log(`timeSplit ${timeSplit}`);
    let hours;
    let minutes;
    let meridian;
    hours = parseInt(timeSplit[0]);
    minutes = parseInt(timeSplit[1]);

    if(hours > 12){
        meridian = 'PM';
        console.log(`meridian ${meridian}`);
        hours -= 12;
    }else if(hours < 12){
        meridian = 'AM';
        console.log(`meridian ${meridian}`);
        if(hours == 0){
            hours = 12;
        }
    }else{
        meridian = 'PM';
    }
    $('#hiddenTime').val(`${hours}:${minutes} ${meridian}`);
}

const resetFormValuesStampingWorkingReportOnModalClose = (modalId, formId) => {
    $(`#${modalId}`).on('hidden.bs.modal', function () {
        // Remove invalid & title validation
        $('div').find('input').removeClass('is-invalid');
        $('div').find('input').attr('title', '');

        $('div').find('select').removeClass('is-invalid');
        $('div').find('select').attr('title', '');
        
        // Reset form values
        $(`#${formId}`)[0].reset();
        console.log(`modalId ${modalId}`);
        console.log(`formId ${formId}`);
    });
}

const getStampingWorkingReport = (idParam) => {
    $.ajax({
        type: 'GET',
        url: 'get_stamping_working_report_by_id',
        data: {
            id: idParam,
        },
        dataType: 'json',
        success: function (response) {
            let responseData = response['data'];
            console.log(responseData);
            if(responseData.length > 0){
                $('#textStampingWorkingReportId', $('#formStampingWorkingReport')).val(responseData[0]['id']);
                $('#textControlNumber', $('#formStampingWorkingReport')).val(responseData[0]['control_number']);
                $('#textMachineNumber', $('#formStampingWorkingReport')).val(responseData[0]['machine_number']);
                $('#textYear', $('#formStampingWorkingReport')).val(responseData[0]['year']);
                $('#textMonth', $('#formStampingWorkingReport')).val(responseData[0]['month']);
                $('#textDay', $('#formStampingWorkingReport')).val(responseData[0]['day']);
                dataTablesStampingWorkingReportWorkDetails.draw();
            }
        },
        error: function(data, xhr, status){
            toastr.error(`An error occured! Data: ${data} XHR: ${xhr} Status: ${status}`);
        }
    });
}

const getStampingWorkingReportWorkDetails = (idParam) => {
    $.ajax({
        type: 'GET',
        url: 'get_stamping_working_report_work_details_by_id',
        data: {
            id: idParam,
        },
        dataType: 'json',
        success: function (response) {
            let responseData = response['data'];
            console.log(responseData);
            if(responseData.length > 0){
                $('#textStampingWorkingReportWorkDetailsId', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['id']);
                $('#textStampingWorkingReportId', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['stamping_working_report_id']);
                $('#textTimeStart', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['time_start']);
                $('#textTimeEnd', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['time_end']);
                $('#textTotalMinutes', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['total_minutes']);
                $('#selectWorkDetails', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['work_details']).trigger('change');
                $('#textSequenceNumber', $('#formStampingWorkingReportWorkDetails')).val(responseData[0]['sequence_number']);
            }
        },
        error: function(data, xhr, status){
            toastr.error(`An error occured! Data: ${data} XHR: ${xhr} Status: ${status}`);
        }
    });
}