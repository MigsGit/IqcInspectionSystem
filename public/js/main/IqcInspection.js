
// $(document).ready(function () {
    var tbl = {
        iqcInspection:'#tblIqcInspection',
        iqcWhsDetails :'#tblWhsDetails',
        iqcInspected:'#tblIqcInspected',
        iqcYeuDetails:'#tblIqcYeuDetails',
        iqcYeuInspected:'#tblIqcYeuInspected',

    };

    var dataTable = {
        iqcInspection:'', //iqcInspection
        iqcWshDetails: '',
        iqcInspected: '',
        iqcYeuDetails: '',
        iqcYeuInspected: '',
    };
    var form = {
        iqcInspection : $('#formSaveIqcInspection')
    };

    var strDatTime = {
        dateToday : new Date(), // By default Date empty constructor give you Date.now
        currentDate : new Date().toJSON().slice(0, 10),
        currentTime : new Date().toLocaleTimeString('en-GB', { hour: "numeric",minute: "numeric"}),
        currentHours : new Date().getHours(),
        currentMinutes : new Date().getMinutes(),
    }

    var arrCounter= {
        ctr : 0
    }

    var btn = {
        removeModLotNumber : $('#btnRemoveModLotNumber'),
        saveComputation : $('#btnSaveComputation')
    }

    arrTableMod = {
        lotNo : [],
        modeOfDefects : [],
        lotQty : []
    };

    const editIqcInspection = function () {
        let iqcInpectionId = $(this).attr('iqc-inspection-id')
        getIqcInspectionById(iqcInpectionId);
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
        form.iqcInspection.find('#iqc_coc_file').prop('required',false);

    }
    const editReceivingDetails = function () {
        // alert('dasdsad')
        let receivingDetailId = ($(this).attr('receiving-detail-id') != undefined) ?  $(this).attr('receiving-detail-id') : 0;
        let whsTransactionId = ($(this).attr('whs-trasaction-id') != undefined) ?  $(this).attr('whs-trasaction-id') : 0;

        getWhsDetailsById(receivingDetailId,whsTransactionId);
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
    }
    const getWhsDetailsById = function (receivingDetailId,whsTransactionId) {
        $.ajax({
            type: "GET",
            url: "get_whs_receiving_by_id",
            data: {
                "receiving_detail_id" : receivingDetailId,
                "whs_transaction_id" : whsTransactionId,
            },
            dataType: "json",
            beforeSend: function(){
                $(".form-control-sm").val('');
                $("select").val('');
            },
            success: function (response) {
                $('#modalSaveIqcInspection').modal('show');
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
                let whsReceivingDetailId = ( response[0]['receiving_detail_id'] != undefined || response[0]['receiving_detail_id'] != null ) ? response[0]['receiving_detail_id'] : 0;
                let lotAccepted = response[0]['accepted'];
                /* Visual Inspection */
                form.iqcInspection.find('#app_no').val(`PPD-${twoDigitYear}-${twoDigitMonth}${twoDigitDay}-`);
                form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
                form.iqcInspection.find('#receiving_detail_id').val(whsReceivingDetailId);
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
                getDropdownDetailsById(form.iqcInspection.find('#aql'),'aql');
                getDropdownDetailsById(form.iqcInspection.find('#family'),'family');
                getDropdownDetailsById(form.iqcInspection.find('#inspection_lvl'),'inspection_lvl');
                getDropdownDetailsById(form.iqcInspection.find('#target_dppm'),'target_dppm');
                getDropdownDetailsById(form.iqcInspection.find('#target_lar'),'target_lar');
                getDropdownDetailsById($('#mode_of_defect'),'mode_of_defects');


                if( iqcCocFile === undefined || iqcCocFile === null ){
                    form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
                }else{
                    form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
                }
                /* Display the Mode of Defects Button */
                divDisplayNoneClass(lotAccepted);


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
            }
        });
    }
    const getIqcInspectionById = function (iqcInpectionId) {
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
                let whsReceivingDetailId = ( tblWhsTrasanction['receiving_detail_id'] != undefined || tblWhsTrasanction['receiving_detail_id'] != null ) ? tblWhsTrasanction['receiving_detail_id'] : 0;
                let iqcInspectionId = tblWhsTrasanction['iqc_inspection_id'];
                let iqcInspectionsMods = tblWhsTrasanction.iqc_inspections_mods;
                let lotAccepted = tblWhsTrasanction['accepted'];
                let iqcCocFile = tblWhsTrasanction['iqc_coc_file'];


                form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
                form.iqcInspection.find('#receiving_detail_id').val(whsReceivingDetailId);
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

                getDropdownDetailsById(form.iqcInspection.find('#aql'),'aql',tblWhsTrasanction['aql'])
                getDropdownDetailsById(form.iqcInspection.find('#family'),'family',tblWhsTrasanction['family'])
                getDropdownDetailsById(form.iqcInspection.find('#inspection_lvl'),'inspection_lvl',tblWhsTrasanction['inspection_lvl'])
                getDropdownDetailsById(form.iqcInspection.find('#target_dppm'),'target_dppm');
                getDropdownDetailsById(form.iqcInspection.find('#target_lar'),'target_lar');
                getDropdownDetailsById($('#mode_of_defect'),'mode_of_defects');

                if( iqcCocFile === undefined || iqcCocFile === null ){
                    form.iqcInspection.find('#fileIqcCocDownload').addClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').addClass('disabled',true);
                }else{
                    form.iqcInspection.find('#fileIqcCocDownload').removeClass('d-none',true);
                    form.iqcInspection.find('#iqc_coc_file_download').removeClass('disabled',true);
                }
                /* Display the Mode of Defects Button */
                divDisplayNoneClass(lotAccepted);

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
                        let selectedMod = iqcInspectionsMods[i].mode_of_defects
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
                for (let i = 0; i < response.length; i++) {
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

    const getDropdownDetailsById = function (cmb_element,iqc_inspection_column_ref,opt_value = null) {
        let opt = `<option value="N/A" selected disabled>N/A</option>`;
            opt += `<option value="N/A" selected disabled>N/A</option>`;
        cmb_element.empty().append(opt)
        $.ajax({
            type: "GET",
            url: "get_dropdown_details_by_opt_value",
            data: {"iqc_inspection_column_ref" : iqc_inspection_column_ref},
            dataType: "json",
            success: function (response) {
                let id = response['id'];
                let value = response['value'];
                if(iqc_inspection_column_ref =='target_lar' || iqc_inspection_column_ref == 'target_dppm'){
                    cmb_element.val(value[0]);
                    return;
                }
                console.log('value',value);
                for (let i = 0; i < id.length; i++) {
                    let opt = `<option value="${id[i]}">${value[i]}</option>`;
                    cmb_element.append(opt);
                }
                console.log(opt_value);

                if(opt_value != null){
                    cmb_element.val(opt_value).trigger("change");
                }

            }
        });
    }
    const getDieNo = function () {
        let opt = ``;
            opt += `<option value="" selected disabled>-Select-</option>`;
            opt += `<option value="">N/A</option>`;
        form.iqcInspection.find('#die_no').empty().prepend(opt)
        for (let i = 0; i < 15; i++) {
            let opt = `<option value="${i+1}">${i+1}</option>`;
            form.iqcInspection.find('#die_no').append(opt);
        }
    }
    const disabledEnabledButton = function(arrCounter){
        if(arrCounter === 0 ){
            btn.removeModLotNumber.prop('disabled',true);
        }else{
            btn.removeModLotNumber.prop('disabled',false);
        }
    }
    const getSum = function (total, num) {
        return total + Math.round(num);
    }
    const divDisplayNoneClass =  function (value){
        if(value == 0){ //nmodify
            form.iqcInspection.find('.divMod').removeClass('d-none',true);
            form.iqcInspection.find('#judgement').val(2);
        }else{
            form.iqcInspection.find('.divMod').addClass('d-none',true);
            form.iqcInspection.find('#judgement').val(1);
        }
    }
    const saveIqcInspection = function (){ //amodify
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
                    dataTable.iqcInspection.draw();
                    dataTable.iqcInspected.draw();
                    dataTable.iqcWshDetails.draw();
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Your work has been saved",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    $('#modalScanQRSave').modal('hide');
                }
            },error: function (data, xhr, status){
                let errors = data.responseJSON.errors ;
                toastr.error(`Saving Failed, Please fill up all required fields`);
                $('#modal-loading').modal('hide');
                if(data.status === 422){
                    errorHandler(errors.whs_transaction_id,form.iqcInspection.find('#receiving_detail_id'));
                    errorHandler(errors.whs_transaction_id,form.iqcInspection.find('#receiving_detail_id'));
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

// }) //end Doc Ready
