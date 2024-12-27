    // const editIqcInspection = function () {
    //     let iqcInpectionId = $(this).attr('iqc-inspection-id')
    //     form.iqcInspection.find('input').removeClass('is-valid');
    //     form.iqcInspection.find('input').removeClass('is-invalid');
    //     form.iqcInspection.find('input').attr('title', '');
    //     form.iqcInspection.find('select').removeClass('is-valid');
    //     form.iqcInspection.find('select').removeClass('is-invalid');
    //     form.iqcInspection.find('select').attr('title', '');

    //     /*Upload and Download file*/
    //     $('#isUploadCoc').prop('checked',false);
    //     form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none',true);
    //     form.iqcInspection.find('#iqc_coc_file').prop('required',false);
    //     getDieNo();
    //     getIqcInspectionById(iqcInpectionId);
    // }

    const editReceivingDetails = function () {
        getDieNo();
        form.iqcInspection.find('input').removeClass('is-valid');
        form.iqcInspection.find('input').removeClass('is-invalid');
        form.iqcInspection.find('input').attr('title', '');
        form.iqcInspection.find('select').removeClass('is-valid');
        form.iqcInspection.find('select').removeClass('is-invalid');
        form.iqcInspection.find('select').attr('title', '');
        /*Upload and Download file*/
        $('#isUploadCoc').prop('checked',false);
        form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none',true);

        let whsTransactionId = ($(this).attr('whs-trasaction-id') != undefined) ?  $(this).attr('whs-trasaction-id') : 0;
        let iqcCategoryMaterialId = $('#txtCategoryMaterial').val();
        let data = {
            "whs_transaction_id"        : whsTransactionId,

        }
        let elFormId = form.iqcInspection;
        call_ajax(data, 'get_whs_receiving_by_id', function(response){
            $('#modalSaveIqcInspection').modal('show');
            form.iqcInspection.find('select').val(2);
            let twoDigitYear = strDatTime.dateToday.getFullYear().toString().substr(-2);
            let twoDigitMonth = (strDatTime.dateToday.getMonth() + 1).toString().padStart(2, "0");
            let twoDigitDay = String(strDatTime.dateToday.getDate()).padStart(2, '0');

            let partCode = response[0]['partcode'];
            let partName = response[0]['partname'];
            let supplier = response[0]['supplier']; // aql
            let lotNo = response[0]['lot_no'];
            let lotQty = response[0]['total_lot_qty'];
            let iqcCocFile = response[0]['iqc_coc_file'];
            let whsTransactionId = ( response[0]['whs_transaction_id'] != undefined || response[0]['whs_transaction_id'] != null) ? response[0]['whs_transaction_id'] : 0;
            let lotAccepted = response[0]['accepted'];

            /* Visual Inspection */
            form.iqcInspection.find('#app_no').val(`PPD-${twoDigitYear}${twoDigitMonth}-`);
            form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
            form.iqcInspection.find('#iqc_category_material_id').val(iqcCategoryMaterialId);
            form.iqcInspection.find('#invoice_no').val(response[0]['invoice_no']);
            form.iqcInspection.find('#partcode').val(partCode);
            form.iqcInspection.find('#partname').val(partName);
            form.iqcInspection.find('#supplier').val(supplier);
            form.iqcInspection.find('#total_lot_qty').val(lotQty);
            form.iqcInspection.find('#lot_no').val(lotNo);
            form.iqcInspection.find('#iqc_coc_file').val('');
            /* Sampling Plan */
            form.iqcInspection.find('#accept').val(0);
            form.iqcInspection.find('#reject').val(1);
            /* Visual Inspection Result */
            form.iqcInspection.find('#lot_inspected').val(1);
            form.iqcInspection.find('#date_inspected').val(strDatTime.currentDate);
            form.iqcInspection.find('#time_ins_from').val(strDatTime.currentTime);
            form.iqcInspection.find('#isUploadCoc').prop('required',true);
            getDropdownDetailsByOptValue(form.iqcInspection.find('#aql'),'aql');
            getDropdownDetailsByOptValue(form.iqcInspection.find('#family'),'family');
            getDropdownDetailsByOptValue(form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
            getDropdownDetailsByOptValue(form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsByOptValue(form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsByOptValue(form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection');
            getDropdownDetailsByOptValue($('#mode_of_defect'),'mode_of_defects');


            if( iqcCocFile === undefined || iqcCocFile === null ){
                form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
            }else{
                form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
            }
            /* Display the Mode of Defects Button */
            divDisplayNoneClass(form.iqcInspection,lotAccepted);



            $('#tblModeOfDefect tbody').empty();
            arrTableMod.lotNo = [];
            arrTableMod.modeOfDefects = [];
            arrTableMod.lotQty = [];
            arrCounter.ctr = 0;
            /*Mode of Defects Modal*/
            $('#mod_lot_no').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            $('#mod_quantity').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            for (let i = 0; i < response.length; i++) {
                let optLotNo = `<option value="${lotNo}">${lotNo}</option>`;
                $('#mod_lot_no').append(optLotNo);
            }
            console.log('whsarrTableMod',arrTableMod);
        },elFormId)

    }

    const getPpdWhsPackagingById = function () {
        getDieNo();
        form.iqcInspection.find('input').removeClass('is-valid');
        form.iqcInspection.find('input').removeClass('is-invalid');
        form.iqcInspection.find('input').attr('title', '');
        form.iqcInspection.find('select').removeClass('is-valid');
        form.iqcInspection.find('select').removeClass('is-invalid');
        form.iqcInspection.find('select').attr('title', '');

        /*Upload and Download file*/
        $('#isUploadCoc').prop('checked',false);
        form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none',true)

        let pkidReceived = ($(this).attr('pkid-received') != undefined) ?  $(this).attr('pkid-received') : 0;
        let iqcCategoryMaterialId = $('#txtCategoryMaterial').val();
        let elFormId = form.iqcInspection;
        let data = {
            "pkid_received"        : pkidReceived,
        }

        // call_ajax(data, 'get_ts_whs_receving_packaging_by_id', function(response){
        call_ajax(data, 'get_ppd_whs_packaging_by_id', function(response){

            $('#modalSaveIqcInspection').modal('show')
            console.log(response);

            return;
            form.iqcInspection.find('select').val(2);

            let tsWhsReceivedPackaging = response.tsWhsReceivedPackaging;
            let partCode =tsWhsReceivedPackaging['partcode'];
            let partName =tsWhsReceivedPackaging['partname'];
            let supplier =tsWhsReceivedPackaging['supplier']; // aql
            let lotNo =tsWhsReceivedPackaging['date'];
            let lotQty =tsWhsReceivedPackaging['rcvqty'];
            let iqcCocFile = tsWhsReceivedPackaging['iqc_coc_file'];
            let pkidReceived = (tsWhsReceivedPackaging['pkid_received'] != undefined ||tsWhsReceivedPackaging['pkid_received'] != null) ?tsWhsReceivedPackaging['pkid_received'] : 0;
            let lotAccepted =tsWhsReceivedPackaging['accepted'];
            let invoiceNo = tsWhsReceivedPackaging['invoiceno'];
            let generateControlNumber = response['generateControlNumber'];

            /* Visual Inspection */
            form.iqcInspection.find('#app_no').val(generateControlNumber.app_no);
            form.iqcInspection.find('#app_no_extension').val(generateControlNumber.app_no_extension);
            form.iqcInspection.find('#whs_transaction_id').val(pkidReceived);
            form.iqcInspection.find('#iqc_category_material_id').val(iqcCategoryMaterialId);
            form.iqcInspection.find('#invoice_no').val(invoiceNo);
            form.iqcInspection.find('#partcode').val(partCode);
            form.iqcInspection.find('#partname').val(partName);
            form.iqcInspection.find('#supplier').val(supplier);
            form.iqcInspection.find('#total_lot_qty').val(lotQty);
            form.iqcInspection.find('#lot_no').val(lotNo);
            form.iqcInspection.find('#iqc_coc_file').val('');

            /* Sampling Plan */
            form.iqcInspection.find('#accept').val(0);
            form.iqcInspection.find('#reject').val(1);
            /* Visual Inspection Result */
            form.iqcInspection.find('#lot_inspected').val(1);
            form.iqcInspection.find('#date_inspected').val(strDatTime.currentDate);
            form.iqcInspection.find('#time_ins_from').val(strDatTime.currentTime);
            form.iqcInspection.find('#isUploadCoc').prop('required',true);
            getDropdownDetailsById(form.iqcInspection.find('#aql'),'aql');
            getDropdownDetailsById(form.iqcInspection.find('#family'),'family');
            getDropdownDetailsById(form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
            getDropdownDetailsById(form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsById(form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsById(form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection');
            getDropdownDetailsById($('#mode_of_defect'),'mode_of_defects');

            return;
            if( iqcCocFile === undefined || iqcCocFile === null ){
                form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
            }else{
                form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
            }
            /* Display the Mode of Defects Button */
            divDisplayNoneClass(form.iqcInspection,lotAccepted);



            $('#tblModeOfDefect tbody').empty();
            arrTableMod.lotNo = [];
            arrTableMod.modeOfDefects = [];
            arrTableMod.lotQty = [];
            arrCounter.ctr = 0;
            /*Mode of Defects Modal*/
            $('#mod_lot_no').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            $('#mod_quantity').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            for (let i = 0; i < response.length; i++) {
                let optLotNo = `<option value="${lotNo}">${lotNo}</option>`;
                $('#mod_lot_no').append(optLotNo);
            }
            console.log('whsarrTableMod',arrTableMod);
        },elFormId)

    }
