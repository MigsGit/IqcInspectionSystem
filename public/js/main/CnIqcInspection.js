// $(document).ready(function () {

    const getCnWhsPackagingById = function () {
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
        call_ajax(data, 'get_cn_whs_packaging_by_id', function(response){
            // console.log(response);
            // return;
            $('#modalSaveIqcInspection').modal('show')
            let whsReceiving = response.cnWhsReceivedPackaging;
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
            for (let i = 0; i < 1; i++) {
                let optLotNo = `<option value="${lotNo}">${lotNo}</option>`;
                $('#mod_lot_no').append(optLotNo);
            }
            console.log('whsarrTableMod',arrTableMod);
        },form.iqcInspection)

    }

    const getCnIqcInspectionById = function (iqcInpectionId,iqcCategoryMaterialId) {
        let data = {
            "iqc_inspection_id"        : iqcInpectionId,
        }
        call_ajax(data, 'get_cn_iqc_inspection_by_id', function(response){
            $('#modal-loading').modal('hide');
            $('#modalSaveIqcInspection').modal('show');
            let tblWhsTrasanction = response.tbl_whs_trasanction[0];

            let partCode = tblWhsTrasanction['partcode'];
            let partName = tblWhsTrasanction['partname'];
            let supplier = tblWhsTrasanction['supplier'];
            let lotNo = tblWhsTrasanction['lot_no'];
            let lotQty = tblWhsTrasanction['total_lot_qty'];

            let whsTransactionId = ( tblWhsTrasanction['whs_transaction_id'] != undefined || tblWhsTrasanction['whs_transaction_id'] != null) ? tblWhsTrasanction['whs_transaction_id'] : 0;
            let iqcInspectionId = tblWhsTrasanction['iqc_inspection_id'];
            // let iqcInspectionsMods = tblWhsTrasanction.cn_iqc_inspections_mods[0].iqc_dropdown_detail.dropdown_details;
            let iqcInspectionsMods = tblWhsTrasanction.cn_iqc_inspections_mods;
            let lotAccepted = tblWhsTrasanction['accepted'];
            let iqcCocFile = tblWhsTrasanction['iqc_coc_file'];

            getDieNo();
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#aql'),'aql',tblWhsTrasanction['aql'])
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#family'),'family',tblWhsTrasanction['family'])
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#inspection_lvl'),'inspection_lvl',tblWhsTrasanction['inspection_lvl'])
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection',tblWhsTrasanction['severity_of_inspection']);
            getDropdownDetailsByOptValue('TS',$('#mode_of_defect'),'mode_of_defects');

            form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
            form.iqcInspection.find('#iqc_category_material_id').val(iqcCategoryMaterialId);
            form.iqcInspection.find('#invoice_no').val(tblWhsTrasanction['invoice_no']);
            form.iqcInspection.find('#partcode').val(partCode);
            form.iqcInspection.find('#partname').val(partName);
            form.iqcInspection.find('#supplier').val(supplier);
            form.iqcInspection.find('#total_lot_qty').val(lotQty);
            form.iqcInspection.find('#lot_no').val(lotNo);
            form.iqcInspection.find('#iqc_inspection_id').val(iqcInspectionId);
            form.iqcInspection.find('#app_no').val(tblWhsTrasanction['app_no']);
            form.iqcInspection.find('#app_no_extension').val(tblWhsTrasanction['app_no_extension']);
            form.iqcInspection.find('#die_no').val(tblWhsTrasanction['die_no']);
            form.iqcInspection.find('#classification').val(tblWhsTrasanction['classification']);
            form.iqcInspection.find('#type_of_inspection').val(tblWhsTrasanction['type_of_inspection']);
            form.iqcInspection.find('#severity_of_inspection').val(tblWhsTrasanction['severity_of_inspection']);
            console.log('severity_of_inspection',tblWhsTrasanction['severity_of_inspection'])
            //severity_of_inspection
            form.iqcInspection.find('#accept').val(tblWhsTrasanction['accept']);
            form.iqcInspection.find('#reject').val(tblWhsTrasanction['reject']);
            form.iqcInspection.find('#shift').val(tblWhsTrasanction['shift']);
            form.iqcInspection.find('#target_lar').val(tblWhsTrasanction['target_lar']);
            form.iqcInspection.find('#target_dppm').val(tblWhsTrasanction['target_dppm']);
            form.iqcInspection.find('#date_inspected').val(tblWhsTrasanction['date_inspected']);
            form.iqcInspection.find('#time_ins_from').val(tblWhsTrasanction['time_ins_from']);
            form.iqcInspection.find('#time_ins_to').val(tblWhsTrasanction['time_ins_to']);
            form.iqcInspection.find('#inspector').val(tblWhsTrasanction['inspector']);
            form.iqcInspection.find('#inspector_name').html(tblWhsTrasanction.user_iqc.name);
            form.iqcInspection.find('#submission').val(tblWhsTrasanction['submission']);
            form.iqcInspection.find('#category').val(tblWhsTrasanction['category']);
            form.iqcInspection.find('#sampling_size').val(tblWhsTrasanction['sampling_size']);
            form.iqcInspection.find('#no_of_defects').val(tblWhsTrasanction['no_of_defects']);
            form.iqcInspection.find('#lot_inspected').val(tblWhsTrasanction['lot_inspected']);
            form.iqcInspection.find('#accepted').val(lotAccepted);
            form.iqcInspection.find('#judgement').val(tblWhsTrasanction['judgement']);
            form.iqcInspection.find('#remarks').val(tblWhsTrasanction['remarks']);
            form.iqcInspection.find('#iqc_coc_file').val('');
            form.iqcInspection.find('#isUploadCoc').prop('required',false);

            if( iqcCocFile === undefined || iqcCocFile === null ){
                form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
            }else{
                form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                form.iqcInspection.find('#iqc_coc_file_download').removeClass('disabled',true);
            }
            /* Display the Mode of Defects Button */
            divDisplayNoneClass(form.iqcInspection,lotAccepted);

                        console.log('iqcCocFile',iqcCocFile);


            $('#tblModeOfDefect tbody').empty();
            arrTableMod.lotNo = [];
            arrTableMod.modeOfDefects = [];
            arrTableMod.lotQty = [];
            if(iqcInspectionsMods === undefined){
                arrCounter.ctr = 0;
            }else{
                btn.removeModLotNumber.prop('disabled',false);
                for (let i = 0; i < iqcInspectionsMods.length; i++) {
                    let selectedLotNo = iqcInspectionsMods[i].lot_no
                    let selectedMod = iqcInspectionsMods[i].iqc_dropdown_detail.dropdown_details;
                    let selectedLotQty = iqcInspectionsMods[i].quantity
                    arrCounter.ctr = i+1;
                    var html_body  = '<tr>';
                        html_body += '<td>'+arrCounter.ctr+'</td>';
                        html_body += '<td>'+selectedLotNo+'</td>';
                        html_body += '<td>'+selectedMod+'</td>';
                        html_body += '<td>'+selectedLotQty+'</td>';
                        html_body += '</tr>';
                    $('#tblModeOfDefect tbody').append(html_body);

                    arrTableMod.lotNo.push(selectedLotNo);
                    arrTableMod.modeOfDefects.push(selectedMod);
                    arrTableMod.lotQty.push(selectedLotQty);
                }
            }
            /*Mode of Defects Modal*/

            $('#mod_lot_no').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            $('#mod_quantity').empty().prepend(`<option value="" selected disabled>-Select-</option>`)
            for (let i = 0; i < 1; i++) {
                let optLotNo = `<option value="${lotNo}">${lotNo}</option>`;
                $('#mod_lot_no').append(optLotNo);
            }
            console.log('arrTableMod.lotNo',arrTableMod.lotNo);
            console.log('arrTableMod.lotQty',arrTableMod.lotQty);
        },form.iqcInspection);
    }
// });
