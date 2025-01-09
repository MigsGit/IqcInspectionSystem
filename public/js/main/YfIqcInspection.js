// $(document).ready(function () {
    

    
    const getYfWhsPackagingById = function () {
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
        let data = {
            "pkid_received"        : pkidReceived,
        }
        call_ajax(data, 'get_yf_whs_packaging_by_id', function(response){
            // console.log(response);
            // return;
            $('#modalSaveIqcInspection').modal('show')
            let whsReceiving = response.yfWhsReceivedPackaging;
            let partCode =whsReceiving['partcode'];
            let partName =whsReceiving['partname'];
            let supplier =whsReceiving['supplier'];
            let lotNo =whsReceiving['lot_no'];
            let lotQty =whsReceiving['total_lot_qty'];
            let iqcCocFile = whsReceiving['iqc_coc_file'];
            let pkidReceived = (whsReceiving['whs_transaction_id'] != undefined ||whsReceiving['whs_transaction_id'] != null) ?whsReceiving['whs_transaction_id'] : 0;
            let lotAccepted =whsReceiving['accepted'];
            let invoiceNo = whsReceiving['invoice_no'];
            let generateControlNumber = response['generateControlNumber'];

            getDieNo();
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#aql'),'aql');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#family'),'family');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection');
            getDropdownDetailsByOptValue('TS',$('#mode_of_defect'),'mode_of_defects');

            /* Visual Inspection */
            form.iqcInspection.find('#whs_transaction_id').val(pkidReceived);
            form.iqcInspection.find('#app_no').val(generateControlNumber.app_no);
            form.iqcInspection.find('#app_no_extension').val(generateControlNumber.app_no_extension);
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
            for (let i = 0; i < 1; i++) { //If array make an array LotNumber
                let optLotNo = `<option value="${lotNo}">${lotNo}</option>`;
                $('#mod_lot_no').append(optLotNo);
            }
            console.log('whsarrTableMod',arrTableMod);
        },form.iqcInspection)

    }
// });