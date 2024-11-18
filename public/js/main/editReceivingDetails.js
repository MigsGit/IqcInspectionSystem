const editReceivingDetails = function () {
    alert('dasdsad');
    let receivingDetailId = ($(this).attr('receiving-detail-id') != undefined) ? $(this).attr('receiving-detail-id') : 0;
    let whsTransactionId = ($(this).attr('whs-trasaction-id') != undefined) ? $(this).attr('whs-trasaction-id') : 0;

    getWhsDetailsById(receivingDetailId, whsTransactionId);
    getFamily();
    getAql();
    getInspectionLevel();
    getDieNo();
    getLarDppm();
    getModeOfDefect();

    form.iqcInspection.find('input').removeClass('is-invalid');
    form.iqcInspection.find('input').attr('title', '');
    form.iqcInspection.find('select').removeClass('is-invalid');
    form.iqcInspection.find('select').attr('title', '');

    /*Upload and Download file*/
    $('#isUploadCoc').prop('checked', false);
    form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none', true);
};
