
// $(document).ready(function () {

    const tbl = {
        iqcInspection:'#tblIqcInspection',
        iqcWhsDetails :'#tblWhsDetails',
        iqcInspected:'#tblIqcInspected'
    };

    const dataTable = {
        iqcInspection:'', //iqcInspection
        iqcWshDetails: '',
        iqcInspected: ''
    };

    const form = {
        iqcInspection : $('#formSaveIqcInspection')
    };
    const strDatTime = {
        dateToday : new Date(), // By default Date empty constructor give you Date.now
        currentDate : new Date().toJSON().slice(0, 10),
        currentTime : new Date().toLocaleTimeString('en-GB', { hour: "numeric",minute: "numeric"}),
        currentHours : new Date().getHours(),
        currentMinutes : new Date().getMinutes(),
    }
    const arrCounter= {
        ctr : 0
    }
    const btn = {
        removeModLotNumber : $('#btnRemoveModLotNumber'),
        saveComputation : $('#btnSaveComputation')
    }
    const arrTableMod = {
        lotNo : [],
        modeOfDefects : [],
        lotQty : []
    };

    dataTable.iqcInspection = $(tbl.iqcInspection).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "load_whs_transaction",
            data: function (param){
                param.firstStamping = "true" //DT for 1st Stamping
                param.lotNum = $('#txtSearchLotNum').val()
            },
        },
        fixedHeader: true,
        "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "InvoiceNo" },
            { "data" : "Supplier" },
            { "data" : "PartNumber" },
            { "data" : "MaterialType" },
            { "data" : "Lot_number" },
        ],
    });

    dataTable.iqcWshDetails = $(tbl.iqcWhsDetails).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "load_whs_details",
            data: function (param){
                param.lotNum = $('#txtSearchLotNum').val()
            },
        },
        fixedHeader: true,
        "columns":[

            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "po_no" },
            { "data" : "Supplier" },
            { "data" : "PartNumber" },
            { "data" : "MaterialType" },
            { "data" : "Lot_number" },

        ],
    });

    dataTable.iqcInspected = $(tbl.iqcInspected).DataTable({
        "processing" : true,
        "serverSide" : true,
        "ajax" : {
            url: "load_iqc_inspection",
            data: function (param){
                param.lotNum = $('#txtSearchLotNum').val()
            },
        },
        fixedHeader: true,
        "columns":[
            { "data" : "action", orderable:false, searchable:false },
            { "data" : "status", orderable:false, searchable:false },
            { "data" : "date_inspected" },
            { "data" : "time_inspected" }, //
            { "data" : "app_ctrl_no" }, //
            { "data": "supplier" },
            // { "data" : "classification" },//
            // { "data" : "family" },//
            // { "data" : "category" },//
            { "data" : "partcode" },
            { "data" : "partname" },
            { "data" : "lot_no" },
            { "data" : "total_lot_qty" },
            // { "data" : "aql" }, //
            { "data" : "qc_inspector" }, //
            { "data" : "created_at" },
            { "data" : "updated_at" },
        ],
    });

    const getFamily = function () {
        $.ajax({
            url: "get_family",
            method: "get",
            dataType: "json",
            beforeSend: function(){
                result = '<option value="" selected disabled> -- Loading -- </option>';
                form.iqcInspection.find('select[name=family]').html(result);
            },
            success: function(response){
                result = '';
                let families_id = response['id'];
                let families_name = response['value'];

                if(response['id'].length > 0){
                    result = '<option selected disabled> --- Select --- </option>';
                    for(let index = 0; index < response['id'].length; index++){
                        result += '<option value="' + response['id'][index]+'">'+ response['value'][index]+'</option>';
                    }
                }
                else{
                    result = '<option value="0" selected disabled> No record found </option>';
                }
                form.iqcInspection.find('select[name="family"]').html(result);
            }
        });
    }
    const editIqcInspection = function () {
        let iqcInpectionId = $(this).attr('iqc-inspection-id')
        getIqcInspectionById(iqcInpectionId);
        getFamily();
        getAql();
        getInspectionLevel();
        getDieNo();
        getLarDppm();
        getModeOfDefect();

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
    const editReceivingDetails = function () {
        // alert('dasdsad')
        let receivingDetailId = ($(this).attr('receiving-detail-id') != undefined) ?  $(this).attr('receiving-detail-id') : 0;
        let whsTransactionId = ($(this).attr('whs-trasaction-id') != undefined) ?  $(this).attr('whs-trasaction-id') : 0;

        getWhsDetailsById(receivingDetailId,whsTransactionId);
        getFamily();
        getAql();
        getInspectionLevel();
        getDieNo();
        getLarDppm();
        getModeOfDefect();

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
                let supplier = response[0]['supplier'];
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
                let partCode = response[0]['partcode'];
                let partName = response[0]['partname'];
                let supplier = response[0]['supplier'];
                let lotNo = response[0]['lot_no'];
                let lotQty = response[0]['total_lot_qty'];

                let whsTransactionId = ( response[0]['whs_transaction_id'] != undefined || response[0]['whs_transaction_id'] != null) ? response[0]['whs_transaction_id'] : 0;
                let whsReceivingDetailId = ( response[0]['receiving_detail_id'] != undefined || response[0]['receiving_detail_id'] != null ) ? response[0]['receiving_detail_id'] : 0;
                let iqcInspectionId = response[0]['iqc_inspection_id'];
                let iqcInspectionsMods = response[0].iqc_inspections_mods;
                let lotAccepted = response[0]['accepted'];
                let iqcCocFile = response[0]['iqc_coc_file'];


                form.iqcInspection.find('#whs_transaction_id').val(whsTransactionId);
                form.iqcInspection.find('#receiving_detail_id').val(whsReceivingDetailId);
                form.iqcInspection.find('#invoice_no').val(response[0]['invoice_no']);
                form.iqcInspection.find('#partcode').val(partCode);
                form.iqcInspection.find('#partname').val(partName);
                form.iqcInspection.find('#supplier').val(supplier);
                form.iqcInspection.find('#total_lot_qty').val(lotQty);
                form.iqcInspection.find('#lot_no').val(lotNo);
                form.iqcInspection.find('#iqc_inspection_id').val(iqcInspectionId);
                form.iqcInspection.find('#app_no').val(response[0]['app_no']);
                form.iqcInspection.find('#app_no_extension').val(response[0]['app_no_extension']);
                form.iqcInspection.find('#die_no').val(response[0]['die_no']);
                form.iqcInspection.find('#classification').val(response[0]['classification']);
                form.iqcInspection.find('#type_of_inspection').val(response[0]['type_of_inspection']);
                form.iqcInspection.find('#severity_of_inspection').val(response[0]['severity_of_inspection']);
                form.iqcInspection.find('#accept').val(response[0]['accept']);
                form.iqcInspection.find('#reject').val(response[0]['reject']);
                form.iqcInspection.find('#shift').val(response[0]['shift']);
                form.iqcInspection.find('#target_lar').val(response[0]['target_lar']);
                form.iqcInspection.find('#target_dppm').val(response[0]['target_dppm']);
                form.iqcInspection.find('#date_inspected').val(response[0]['date_inspected']);
                form.iqcInspection.find('#time_ins_from').val(response[0]['time_ins_from']);
                form.iqcInspection.find('#time_ins_to').val(response[0]['time_ins_to']);
                form.iqcInspection.find('#inspector').val(response[0]['inspector']).trigger('change');
                form.iqcInspection.find('#submission').val(response[0]['submission']);
                form.iqcInspection.find('#category').val(response[0]['category']);
                form.iqcInspection.find('#sampling_size').val(response[0]['sampling_size']);
                form.iqcInspection.find('#no_of_defects').val(response[0]['no_of_defects']);
                form.iqcInspection.find('#lot_inspected').val(response[0]['lot_inspected']);
                form.iqcInspection.find('#accepted').val(lotAccepted);
                form.iqcInspection.find('#judgement').val(response[0]['judgement']);
                form.iqcInspection.find('#remarks').val(response[0]['remarks']);
                form.iqcInspection.find('#iqc_coc_file').val('');
                form.iqcInspection.find('#isUploadCoc').prop('required',false);

                setTimeout(() => {
                    form.iqcInspection.find('#family').val(response[0]['family']).trigger("change");
                    form.iqcInspection.find('#inspection_lvl').val(response[0]['inspection_lvl']).trigger("change");
                    form.iqcInspection.find('#aql').val(response[0]['aql']).trigger("change");
                }, 300);

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
    const getInspectionLevel = function () {
        $.ajax({
            type: "GET",
            url: "get_inspection_level",
            data: "data",
            dataType: "json",
            success: function (response) {
                let dropdown_inspection_level_id = response['id'];
                let dropdown_inspection_level_name = response['value'];
                form.iqcInspection.find('#inspection_lvl').empty();
                form.iqcInspection.find('#inspection_lvl').empty().prepend(`<option value="0" selected disabled>-Select-</option>`)
                for (let i = 0; i < dropdown_inspection_level_id.length; i++) {
                    let opt = `<option value="${dropdown_inspection_level_id[i]}">${dropdown_inspection_level_name[i]}</option>`;
                    form.iqcInspection.find('#inspection_lvl').append(opt);
                }
            }
        });
    }
    const getAql = function () {
        form.iqcInspection.find('#aql').empty().prepend(`<option value="0" selected disabled>-Select-</option>`)
        $.ajax({
            type: "GET",
            url: "get_aql",
            data: "data",
            dataType: "json",
            success: function (response) {
                let dropdown_aql_id = response['id'];
                let dropdown_aql_name = response['value'];
                for (let i = 0; i < dropdown_aql_id.length; i++) {
                    let opt = `<option value="${dropdown_aql_name[i]}">${dropdown_aql_name[i]}</option>`;
                    form.iqcInspection.find('#aql').append(opt);
                }
            }
        });
    }
    const getDieNo = function () {
        form.iqcInspection.find('#die_no').empty().prepend(`<option value="0" selected disabled>-Select-</option>`)
        for (let i = 0; i < 15; i++) {
            let opt = `<option value="${i+1}">${i+1}</option>`;
            form.iqcInspection.find('#die_no').append(opt);
        }
    }
    const getLarDppm = function (){
        $.ajax({
            type: "GET",
            url: "get_lar_dppm",
            data: "data",
            dataType: "json",
            success: function (response) {
                // console.log(response['lar_value'][0]);
                // console.log(response['dppm_value'][0]);
                form.iqcInspection.find('#target_dppm').val(response['lar_value'][0]);
                form.iqcInspection.find('#target_lar').val(response['dppm_value'][0]);
            }
        });
    }
    const getModeOfDefect = function (){
        $.ajax({
            type: "GET",
            url: "get_mode_of_defect",
            data: "data",
            dataType: "json",
            success: function (response) {
                let dropdown_iqc_mode_of_defect_id = response['id'];
                let dropdown_iqc_mode_of_defect = response['value'];
                $('#mode_of_defect').empty().prepend(`<option value="0" selected disabled>-Select-</option>`)
                for (let i = 0; i < dropdown_iqc_mode_of_defect_id.length; i++) {
                    let opt = `<option value="${dropdown_iqc_mode_of_defect[i]}">${dropdown_iqc_mode_of_defect[i]}</option>`;
                    $('#mode_of_defect').append(opt);
                }
            }
        });
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
