
// $(document).ready(function () {
    const editIqcInspected = function ()
    {
        let iqcInpectionId = $(this).attr('iqc-inspection-id')
        form.iqcInspection.find('input').removeClass('is-valid');
        form.iqcInspection.find('input').removeClass('is-invalid');
        form.iqcInspection.find('input').attr('title', '');
        form.iqcInspection.find('select').removeClass('is-valid');
        form.iqcInspection.find('select').removeClass('is-invalid');
        form.iqcInspection.find('select').attr('title', '');

        /*Upload and Download file*/
        $('#isUploadCoc').prop('checked',false);
        form.iqcInspection.find('#fileIqcCocUpload').addClass('d-none',true);
        form.iqcInspection.find('#iqc_coc_file').prop('required',false);
        getDieNo();
        getIqcInspectionById(iqcInpectionId);
    }
    const editYeuIqcDetails = function ()
    {
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

        let yeuReceivesId = ($(this).attr('yeu-receives-id') != undefined) ?  $(this).attr('yeu-receives-id') : 0;
        let data = {
            yeuReceivesId : yeuReceivesId
        }
        let elFormId = form.iqcInspection;
        call_ajax(data, 'get_yeu_receiving_by_id', function(response){
            $('#modalSaveIqcInspection').modal('show');

            let iqcInspection = response['iqcInspection'][0];
            let generateControlNumber = response['generateControlNumber'];
            // return;
            let partCode = iqcInspection['item_code'];
            let partName = iqcInspection['item_name'];
            let supplier = iqcInspection['supplier']; // aql
            let lotNo = iqcInspection['lot_no'];
            let lotQty = iqcInspection['qty'];
            let invoiceNo = iqcInspection['invoice_no'];
            let iqcCocFile = iqcInspection['iqc_coc_file'];
            let whsTransactionId = ( iqcInspection['id'] != undefined || iqcInspection['id'] != null) ? iqcInspection['id'] : 0;
            let lotAccepted = iqcInspection['accepted'];
            /* Visual Inspection */
            form.iqcInspection.find('#app_no').val(generateControlNumber.app_no);
            form.iqcInspection.find('#app_no_extension').val(generateControlNumber.app_no_extension);
            form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
            form.iqcInspection.find('#iqc_category_material_id').val($('#txtCategoryMaterial').val());
            form.iqcInspection.find('#invoice_no').val(invoiceNo);
            form.iqcInspection.find('#partcode').val(partCode);
            form.iqcInspection.find('#partname').val(partName);
            form.iqcInspection.find('#supplier').val(supplier);
            form.iqcInspection.find('#total_lot_qty').val(lotQty);
            form.iqcInspection.find('#lot_no').val(lotNo);
            form.iqcInspection.find('#iqc_coc_file').val('');
            form.iqcInspection.find('#sample_size').val(response.sample_size)
            /* Sampling Plan */
            form.iqcInspection.find('#accept').val(0);
            form.iqcInspection.find('#reject').val(1);
            /* Visual Inspection Result */
            form.iqcInspection.find('#lot_inspected').val(1);
            form.iqcInspection.find('#date_inspected').val(strDatTime.currentDate);
            form.iqcInspection.find('#time_ins_from').val(strDatTime.currentTime);
            form.iqcInspection.find('#isUploadCoc').prop('required',true);
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#aql'),'aql');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#family'),'family');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection');
            getDropdownDetailsByOptValue('TS',$('#mode_of_defect'),'mode_of_defects');

            console.log('iqcCocFile',iqcCocFile);


            if( iqcCocFile === undefined || iqcCocFile === null){
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
        },elFormId)
    }
    const getTsWhsPackagingById = function ()
    {
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

        call_ajax(data, 'get_ts_whs_packaging_by_id', function(response){

            $('#modalSaveIqcInspection').modal('show')

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
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#aql'),'aql');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#family'),'family');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_dppm'),'target_dppm');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_lar'),'target_lar');
            getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection');
            getDropdownDetailsByOptValue('TS',$('#mode_of_defect'),'mode_of_defects');

            // return;
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
        },elFormId)

    }
    const getIqcInspectionById = function (iqcInpectionId)
    {
        $.ajax({
            type: "GET",
            url: "get_iqc_inspection_by_id",
            data: {
                "iqc_inspection_id" : iqcInpectionId,
            },
            dataType: "json",
            beforeSend: function(){
                $('#modal-loading').modal('show');
            },
            success: function (response) {
                console.log(response);
                $('#modal-loading').modal('hide');
                $('#modalSaveIqcInspection').modal('show');
                let tblWhsTrasanction = response.tbl_whs_trasanction[0];

                let partCode = tblWhsTrasanction['partcode'];
                let partName = tblWhsTrasanction['partname'];
                let supplier = tblWhsTrasanction['supplier'];
                let lotNo = tblWhsTrasanction['lot_no'];
                let lotQty = tblWhsTrasanction['total_lot_qty'];

                let whsTransactionId = ( tblWhsTrasanction['whs_transaction_id'] != undefined || tblWhsTrasanction['whs_transaction_id'] != null) ? tblWhsTrasanction['whs_transaction_id'] : 0;
                let iqcCategoryMaterialId = ( tblWhsTrasanction['iqc_category_material_id'] != undefined || tblWhsTrasanction['iqc_category_material_id'] != null ) ? tblWhsTrasanction['iqc_category_material_id'] : 0;
                let iqcInspectionId = tblWhsTrasanction['iqc_inspection_id'];
                let iqcInspectionsMods = tblWhsTrasanction.iqc_inspections_mods;
                let lotAccepted = tblWhsTrasanction['accepted'];
                let iqcCocFile = tblWhsTrasanction['iqc_coc_file'];


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

                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#aql'),'aql',tblWhsTrasanction['aql'])
                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#family'),'family',tblWhsTrasanction['family'])
                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#inspection_lvl'),'inspection_lvl',tblWhsTrasanction['inspection_lvl'])
                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_dppm'),'target_dppm');
                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#target_lar'),'target_lar');
                getDropdownDetailsByOptValue('TS',form.iqcInspection.find('#severity_of_inspection'),'severity_of_inspection',tblWhsTrasanction['severity_of_inspection']);
                getDropdownDetailsByOptValue('TS',$('#mode_of_defect'),'mode_of_defects')


                if( iqcCocFile === undefined || iqcCocFile === null ){
                    form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
                }else{
                    form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').removeClass('disabled',true);
                }
                /* Display the Mode of Defects Button */
                divDisplayNoneClass(form.iqcInspection,lotAccepted);



                $('#tblModeOfDefect tbody').empty();
                arrTableMod.lotNo = [];
                arrTableMod.modeOfDefects = [];
                arrTableMod.lotQty = [];
                if(iqcInspectionsMods === undefined){
                    arrCounter.ctr = 0;
                }else{
                    btn.removeModLotNumber.prop('disabled',false);
                    for (let i = 0; i < iqcInspectionsMods.length; i++) {
                        let selectedLotNo = iqcInspectionsMods[i].lot_no;
                        let selectedMod = iqcInspectionsMods[i].mode_of_defects;
                        let selectedLotQty = iqcInspectionsMods[i].quantity;
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
            },error: function (data, xhr, status){
                toastr.error(`Error: ${data.status}`);
                $('#modal-loading').modal('hide');

            }
        });
    }
    const getDieNo = function ()
    {
        let opt = ``;
            opt += `<option value="" selected disabled>-Select-</option>`;
            opt += `<option value="">N/A</option>`;
        form.iqcInspection.find('#die_no').empty().prepend(opt)
        for (let i = 0; i < 15; i++) {
            let opt = `<option value="${i+1}">${i+1}</option>`;
            form.iqcInspection.find('#die_no').append(opt);
        }
    }
    const disabledEnabledButton = function(arrCounter)
    {
        if(arrCounter === 0 ){
            btn.removeModLotNumber.prop('disabled',true);
        }else{
            btn.removeModLotNumber.prop('disabled',false);
        }
    }
    const getSum = function (total, num)
    {
        return total + Math.round(num);
    }
    const divDisplayNoneClass =  function (elFormId = null,value)
    {
        if(value == 0){ //nmodify
            elFormId.find('.divMod').removeClass('d-none',true);
            elFormId.find('#judgement').val(2);
        }else{
            elFormId.find('.divMod').addClass('d-none',true);
            elFormId.find('#judgement').val(1);
        }
    }
    const saveIqcInspection = function (categoryMaterialId)
    { //amodify
        let serialized_data = new FormData(form.iqcInspection[0]);
            serialized_data.append('lotNo',arrTableMod.lotNo);
            serialized_data.append('modeOfDefects',arrTableMod.modeOfDefects);
            serialized_data.append('lotQty',arrTableMod.lotQty);
        $.ajax({
            type: "POST",
            url: "save_iqc_inspection",
            data: serialized_data,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $('#modal-loading').modal('show');
            },
            success: function (response) {
                $('#modal-loading').modal('hide');
                if (response['result'] === 1){
                    $('#modalSaveIqcInspection').modal('hide');
                    if(categoryMaterialId =="37"){
                        dataTable.iqcTsWhsPackaging.draw();
                        dataTable.iqcTsWhsPackagingInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterialId).draw();
                    }else{
                        dataTable.iqcYeuDetails.draw();
                        dataTable.iqcYeuInspected.ajax.url("load_iqc_inspection?category_material="+categoryMaterialId).draw();
                    }
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Your work has been saved",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#modalScanQRSave').modal('hide');
                    form.iqcInspection[0].reset();
                }
            },error: function (data, xhr, status){
                let errors = data.responseJSON.errors ;
                toastr.error(`Saving Failed, Please fill up all required fields`);
                $('#modal-loading').modal('hide');
                if(data.status === 422){
                    errorHandler(errors.whs_transaction_id,form.iqcInspection.find('#whs_transaction_id'));
                    errorHandler(errors.iqc_category_material_id,form.iqcInspection.find('#iqc_category_material_id'));
                    errorHandler(errors.app_no,form.iqcInspection.find('#app_no'));
                    errorHandler(errors.partcode,form.iqcInspection.find('#partcode'));
                    errorHandler(errors.partname,form.iqcInspection.find('#partname'));
                    errorHandler(errors.supplier,form.iqcInspection.find('#supplier'));
                    errorHandler(errors.total_lot_qty,form.iqcInspection.find('#total_lot_qty'));
                    errorHandler(errors.accept,form.iqcInspection.find('#accept'));
                    errorHandler(errors.family,form.iqcInspection.find('#family'));
                    errorHandler(errors.app_no_extension,form.iqcInspection.find('#app_no_extension'));
                    errorHandler(errors.die_no,form.iqcInspection.find('#die_no'));
                    errorHandler(errors.lot_no,form.iqcInspection.find('#lot_no'));
                    errorHandler(errors.classification,form.iqcInspection.find('#classification'));
                    errorHandler(errors.type_of_inspection,form.iqcInspection.find('#type_of_inspection'));
                    errorHandler(errors.severity_of_inspection,form.iqcInspection.find('#severity_of_inspection'));
                    errorHandler(errors.inspection_lvl,form.iqcInspection.find('#inspection_lvl'));
                    errorHandler(errors.aql,form.iqcInspection.find('#aql'));
                    errorHandler(errors.accept,form.iqcInspection.find('#accept'));
                    errorHandler(errors.reject,form.iqcInspection.find('#reject'));
                    errorHandler(errors.shift,form.iqcInspection.find('#shift'));
                    errorHandler(errors.date_inspected,form.iqcInspection.find('#date_inspected'));
                    errorHandler(errors.time_ins_from,form.iqcInspection.find('#time_ins_from'));
                    errorHandler(errors.time_ins_to,form.iqcInspection.find('#time_ins_to'));
                    errorHandler(errors.inspector,form.iqcInspection.find('#inspector'));
                    errorHandler(errors.submission,form.iqcInspection.find('#submission'));
                    errorHandler(errors.category,form.iqcInspection.find('#category'));
                    errorHandler(errors.sampling_size,form.iqcInspection.find('#sampling_size'));
                    errorHandler(errors.lot_inspected,form.iqcInspection.find('#lot_inspected'));
                    errorHandler(errors.accepted,form.iqcInspection.find('#accepted'));
                    errorHandler(errors.judgement,form.iqcInspection.find('#judgement'));
                }else{
                    toastr.error(`Error: ${data.status}`);
                }
            }
        });
    }
    const getSamplingSizeBySamplingPlan = function (severityOfInspection,inspectionLvl,aql,totalLotQty){
        let data = {
            'severity_of_inspection' : severityOfInspection,
            'inspection_lvl' : inspectionLvl,
            'aql' : aql,
            'total_lot_qty' : totalLotQty,
        }
        call_ajax(data,'get_sampling_size_by_sampling_plan',function(response){
            console.log(response.sample_size);

            form.iqcInspection.find('#sampling_size').val(response.sample_size)
            // form.iqcInspection.find('#sampling_size').val(tblWhsTrasanction['sampling_size']);
        })
    }

// }) //end Doc Ready
