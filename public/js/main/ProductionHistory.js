// const getOperatorList = (cboElement) => {
//     $.ajax({
//         type: "get",
//         url: "get_optr_list",
//         data: "",
//         dataType: "json",
//         success: function (response) {
//             console.log(response);
//             let result = "";
//             for(let x = 0; x<response.length; x++){
//                 result += `<option value="${response[x]['id']}">${response[x]['firstname']} ${response[x]['lastname']}</option>`;
//             }

//             cboElement.html(result);
//         }
//     });
// }

// const getMaterialList = (cboElement) => {
//     $.ajax({
//         type: "get",
//         url: "get_material_list",
//         data: "",
//         dataType: "json",
//         success: function (response) {
//             console.log(response);
//             let result = "";
//             for (let x = 0; x < response.length; x++) {
//                 result += `<option value="${response[x]['id']}">${response[x]['firstname']} ${response[x]['lastname']}</option>`;
//             }

//             cboElement.html(result);
//         }
//     });
// }

// const getMachineDropdown = (cboElement, materialName) => {
//     $.ajax({
//         type: "get",
//         url: "get_machine",
//         data: {
//             "material_name" : materialName
//         },
//         dataType: "json",
//         success: function (response) {
//             let result = '';
//             console.log(response['machine']);
//             if(response['machine'].length > 0){
//                 for(let index = 0; index < response['machine'].length; index++){
//                     result += `<option value="${response['machine'][index].machine_name}">${response['machine'][index].machine_name}</option>`;
//                 }
//             }

//             cboElement.html(result);

//         }
//     });
// }

function AddProdnHistory() {
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "add_prodn_history",
        method: "post",
        data: $('#formProductionHistory').serialize(),
        dataType: "json",
        beforeSend: function () {
            $("#iBtnAddUserIcon").addClass('fa fa-spinner fa-pulse');
            // $("#btnSubmit").prop('disabled', 'disabled');
        },
        success: function (JsonObject) {
            if (JsonObject['result'] == 1) {
                // $("#modalProductionHistory").modal('hide');
                // $("#formProductionHistory")[0].reset();
                $("#prodn_stime").val('');
                $("#machine_no").val('');
                $("#standard_para_date").val('');
                $("#standard_para_attach").val('');
                $("#act_cycle_time").val('');
                $("#shot_weight").val('');
                $("#product_weight").val('');
                $("#screw_most_fwd").val('');
                $("#ccd_setting_s1").val('');
                $("#ccd_setting_s2").val('');
                $("#ccd_setting_ng").val('');
                $("#changes_para").val('');
                $("#remarks").val('');
                $("#opt_id").val('');
                $("#opt_name").val('');
                $("#opt_name").val('');

                // $("#ibtnSubmitIcon").removeClass('fa fa-spinner fa-pulse');
                // $("#btnSubmit").removeAttr('disabled');
                // $("#ibtnSubmitIcon").addClass('fa fa-check');

                // ProductionHistory.draw();
                toastr.success('Production History was succesfully saved!');
            }else {
                toastr.error('Saving Productionsss History Failed!');
                if (JsonObject['error']['prodn_stime'] === undefined) {
                    $("#prodn_stime").removeClass('is-invalid');
                    $("#prodn_stime").attr('title', '');
                } else {
                    $("#prodn_stime").addClass('is-invalid');
                    $("#prodn_stime").attr('title', JsonObject['error']['prodn_stime']);
                }
                // if (JsonObject['error']['machine_no'] === undefined) {
                //     $("#machine_no").removeClass('is-invalid');
                //     $("#machine_no").attr('title', '');
                // }else {
                //     $("#machine_no").addClass('is-invalid');
                //     $("#machine_no").attr('title', JsonObject['error']['machine_no']);
                // }
                if (JsonObject['error']['standard_para_date'] === undefined) {
                    $("#standard_para_date").removeClass('is-invalid');
                    $("#standard_para_date").attr('title', '');
                }else {
                    $("#standard_para_date").addClass('is-invalid');
                    $("#standard_para_date").attr('title', JsonObject['error']['standard_para_date']);
                }
                if (JsonObject['error']['act_cycle_time'] === undefined) {
                    $("#act_cycle_time").removeClass('is-invalid');
                    $("#act_cycle_time").attr('title', '');
                } else {
                    $("#act_cycle_time").addClass('is-invalid');
                    $("#act_cycle_time").attr('title', JsonObject['error']['act_cycle_time']);
                }
                if (JsonObject['error']['shot_weight'] === undefined) {
                    $("#shot_weight").removeClass('is-invalid');
                    $("#shot_weight").attr('title', '');
                } else {
                    $("#shot_weight").addClass('is-invalid');
                    $("#shot_weight").attr('title', JsonObject['error']['shot_weight']);
                }
                if (JsonObject['error']['product_weight'] === undefined) {
                    $("#product_weight").removeClass('is-invalid');
                    $("#product_weight").attr('title', '');
                } else {
                    $("#product_weight").addClass('is-invalid');
                    $("#product_weight").attr('title', JsonObject['error']['product_weight']);
                }
                if (JsonObject['error']['screw_most_fwd'] === undefined) {
                    $("#screw_most_fwd").removeClass('is-invalid');
                    $("#screw_most_fwd").attr('title', '');
                } else {
                    $("#screw_most_fwd").addClass('is-invalid');
                    $("#screw_most_fwd").attr('title', JsonObject['error']['screw_most_fwd']);
                }
                if (JsonObject['error']['ccd_setting_s1'] === undefined) {
                    $("#ccd_setting_s1").removeClass('is-invalid');
                    $("#ccd_setting_s1").attr('title', '');
                } else {
                    $("#ccd_setting_s1").addClass('is-invalid');
                    $("#ccd_setting_s1").attr('title', JsonObject['error']['ccd_setting_s1']);
                }
                if (JsonObject['error']['ccd_setting_s2'] === undefined) {
                    $("#ccd_setting_s2").removeClass('is-invalid');
                    $("#ccd_setting_s2").attr('title', '');
                } else {
                    $("#ccd_setting_s2").addClass('is-invalid');
                    $("#ccd_setting_s2").attr('title', JsonObject['error']['ccd_setting_s2']);
                }
                if (JsonObject['error']['ccd_setting_ng'] === undefined) {
                    $("#ccd_setting_ng").removeClass('is-invalid');
                    $("#ccd_setting_ng").attr('title', '');
                } else {
                    $("#ccd_setting_ng").addClass('is-invalid');
                    $("#ccd_setting_ng").attr('title', JsonObject['error']['ccd_setting_ng']);
                }
                if (JsonObject['error']['remarks'] === undefined) {
                    $("#remarks").removeClass('is-invalid');
                    $("#remarks").attr('title', '');
                } else {
                    $("#remarks").addClass('is-invalid');
                    $("#remarks").attr('title', JsonObject['error']['remarks']);
                }
                if (JsonObject['error']['opt_id'] === undefined) {
                    $("#opt_name").removeClass('is-invalid');
                    $("#opt_name").attr('title', '');
                } else {
                    $("#opt_name").addClass('is-invalid');
                    $("#opt_name").attr('title', JsonObject['error']['opt_id']);
                }
                if (JsonObject['error']['shots'] === undefined) {
                    $("#shots").removeClass('is-invalid');
                    $("#shots").attr('title', '');
                } else {
                    $("#shots").addClass('is-invalid');
                    $("#shots").attr('title', JsonObject['error']['shots']);
                }
                if (JsonObject['error']['prodn_etime'] === undefined) {
                    $("#prodn_etime").removeClass('is-invalid');
                    $("#prodn_etime").attr('title', '');
                } else {
                    $("#prodn_etime").addClass('is-invalid');
                    $("#prodn_etime").attr('title', JsonObject['error']['prodn_etime']);
                }
                if (JsonObject['error']['qc_id'] === undefined) {
                    $("#qc_name").removeClass('is-invalid');
                    $("#qc_name").attr('title', '');
                } else {
                    $("#qc_name").addClass('is-invalid');
                    $("#qc_name").attr('title', JsonObject['error']['qc_id']);
                }
            }
            // $("#ibtnSubmitIcon").removeClass('fa fa-spinner fa-pulse');
            // $("#btnSubmit").removeAttr('disabled');
            // $("#ibtnSubmitIcon").addClass('fa fa-check');
        },
        error: function (data, xhr, status) {
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#ibtnSubmitIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnSubmit").removeAttr('disabled');
            $("#ibtnSubmitIcon").addClass('fa fa-check');
        }
    });
}

const getFirstModlingDevicesForHistory = () => {
    $.ajax({
        type: "GET",
        url: "get_first_molding_devices_for_history",
        data: "data",
        dataType: "json",
        success: function (response) {
            let first_molding_device_data = response['data'];
            // let device_name = response['value'];
            let result = '';

            if(first_molding_device_data.length > 0){
                result = '<option selected disabled> --- Select --- </option>';
                for(let index = 0; index < first_molding_device_data.length; index++){
                    result += '<option value="' +first_molding_device_data[index]['id']+'">'+first_molding_device_data[index]['device_name']+'</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>';
            }
            $('select[name="global_device_name"]').html(result);
        }
    });
}

const getProdHistoryById = (pId, btnFunction, firstMoldingDevId,pmCat) => {
    // 0= viewing, 1-edit
    /*
        * firstMoldingDevId => for viewing purposes only.
        * check getFirstMoldingDeviceById() on blade for reference
    */
    $.ajax({
        type: "get",
        url: "get_prodn_history_by_id",
        data: {
            "id" : pId,
            "pm_cat" : pmCat
        },
        dataType: "json",
        beforeSend: function(){
            if(btnFunction == 0){
                console.log(btnFunction);
                $('#prodn_stime', $('#formProductionHistory')).prop('readonly', true);
                $('#standard_para_date', $('#formProductionHistory')).prop('readonly', true);
                $('#act_cycle_time', $('#formProductionHistory')).prop('readonly', true);
                $('#shot_weight', $('#formProductionHistory')).prop('readonly', true);
                $('#product_weight', $('#formProductionHistory')).prop('readonly', true);
                $('#screw_most_fwd', $('#formProductionHistory')).prop('readonly', true);
                $('#ccd_setting_s1', $('#formProductionHistory')).prop('readonly', true);
                $('#ccd_setting_s2', $('#formProductionHistory')).prop('readonly', true);
                $('#ccd_setting_ng', $('#formProductionHistory')).prop('readonly', true);
                $('#changes_para', $('#formProductionHistory')).prop('readonly', true);
                $('#shots', $('#formProductionHistory')).prop('readonly', true);
                $('#remarks', $('#formProductionHistory')).prop('readonly', true);
                $('#prodn_etime', $('#formProductionHistory')).prop('readonly', true);
                $('#machine_no', $('#formProductionHistory')).prop('readonly', true);
                $('#btnScanQrMaterialLotNo', $('#formProductionHistory')).prop('disabled', true);
                $('#btnScanQrPMaterialLotNo', $('#formProductionHistory')).prop('disabled', true);
                $('#btnSubmit',  $('#formProductionHistory')).hide();
                $('.divBtnMultiples').attr('style', 'display: none !important');

            }
        },
        success: function (response ) {
            let prodPartsMat; //collection
            //get machine
            getMachineDropdown($('#machine_no'), $('#device_name').val());
            $('#prodn_history_id').val(pId);
            $('#prodn_date').val(response ['prodHistory']['prodn_date']);
            $('#prodn_stime').val(response ['prodHistory']['prodn_stime']);
            $('#shift').val(response ['prodHistory']['shift']);
            $('#machine_no').val(response ['prodHistory']['machine_no']);
            $('#standard_para_date').val(response ['prodHistory']['standard_para_date']);
            $('#standard_para_attach').val(response ['prodHistory']['standard_para_attach']);
            $('#act_cycle_time').val(response ['prodHistory']['act_cycle_time']);
            $('#shot_weight').val(response ['prodHistory']['shot_weight']);
            $('#product_weight').val(response ['prodHistory']['product_weight']);
            $('#screw_most_fwd').val(response ['prodHistory']['screw_most_fwd']);
            $('#ccd_setting_s1').val(response ['prodHistory']['ccd_setting_s1']);
            $('#ccd_setting_s2').val(response ['prodHistory']['ccd_setting_s2']);
            $('#ccd_setting_ng').val(response ['prodHistory']['ccd_setting_ng']);
            $('#changes_para').val(response ['prodHistory']['changes_para']);
            $("#remarks").val(response ['prodHistory']['remarks']).trigger('change');
            $('#opt_name').val(response ['prodHistory']['operator_info']['firstname']+' '+response ['prodHistory']['operator_info']['lastname']);
            $('#opt_id').val(response ['prodHistory']['opt_id']);

            if (response ['prodHistory']['qc_info'] != null){
                $('#qc_name').val(response ['prodHistory']['qc_info']['firstname']+' '+response ['prodHistory']['qc_info']['lastname']);
            }else{
                $('#qc_name').val('');
            }
            $('#qc_id').val(response ['prodHistory']['qc_id']);

            $('#shots').val(response ['prodHistory']['shots']);
            $('#prodn_etime').val(response ['prodHistory']['prodn_etime']);

            $('#material_lotno').val(response ['prodHistory']['material_lot']);

            $('#shots').prop('readonly',false);
            $('#prodn_etime').prop('readonly',false);

            $('#btnScanQrQCID').prop('disabled',false);
            $('#btnScanQrMaterialLotNo').prop('disabled',false);
            $('#btnScanQrPMaterialLotNo').prop('disabled',false);

            $(`#divMultiplePartsLot1`).empty();
            $(`#divMultipleMaterialLot`).empty();
            $(`#divMultiplePartsLot2_0`).empty();
            $(`#divMultiplePartsLot2_1`).empty();
            $(`#divMultiplePartsLot2_2`).empty();

            if(pmCat == 1){
                $.each(response.first_molding, function(index, data ) {
                    let  contact_lot_number = data['contact_lot_number'];
                    if(contact_lot_number =! null ){
                        // divMultipleMaterialLot
                        let result_material_lot =`
                            <div class="input-group input-group-sm mb-3">
                                <div class="input-group-prepend w-25">
                                    <span class="input-group-text w-100" id="basic-addon1">Material Lot No.${index}</span>
                                </div>
                                <input class="form-control form-control-sm" type="text" id="material_lotno_${index}" name="material_lotno[]" readonly>
                            </div>
                        `;
                        $(`#divMultipleMaterialLot`).append(result_material_lot);
                        let result_part_material_lot =
                            `<div class="input-group input-group-sm mb-3 multiplePMLotDiv" id="multiplePmLot1_${index}">
                                <div class="input-group-prepend w-25">
                                    <span class="input-group-text w-100" id="basic-addon1"> Parts Material Lot No.${index}</span>
                                </div>
                                <input class="form-control form-control-sm pmLotNum2" type="hidden" id="firt_moldings_id_${index}" name="firt_moldings_id[]" readonly>
                                <input class="form-control form-control-sm pmLotNum2" type="text" id="pmat_lot_no_${index}" name="pmat_lot_no[]" readonly>
                                <input class="form-control form-control-sm pmLotNum2" type="text" id="prodn_runcard_${index}" name="prodn_runcard[]" readonly>
                            </div>`;
                        $(`#divMultiplePartsLot1`).append(result_part_material_lot);
                        $(`#firt_moldings_id_${index}`).val(data['id']);
                        $(`#pmat_lot_no_${index}`).val(data['contact_lot_number']);
                        $(`#prodn_runcard_${index}`).val(data['production_lot']+data['production_lot_extension']);
                        $(`#material_lotno_${index}`).val(data.first_molding_material_list.virgin_material);

                    }
                });
            }
            if(pmCat == 2){
                $.each(response.sec_molding_runcard, function(index, data) {
                    let  contact_name_lot_number_one = data['contact_name_lot_number_one'];
                    let  contact_name_lot_number_second = data['contact_name_lot_number_second'];
                    let  me_name_lot_number_one = data['me_name_lot_number_one'];
                    let result_material_lot =`
                        <div class="input-group input-group-sm mb-3">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="basic-addon1">Material Lot No.${index}</span>
                            </div>
                            <input class="form-control form-control-sm" type="text" id="material_lotno_${index}" name="material_lotno[]" readonly>
                        </div>
                    `;
                    $(`#divMultipleMaterialLot`).append(result_material_lot);
                    $(`#material_lotno_${index}`).val(data['material_lot_number']);
                    if(contact_name_lot_number_one =! null ){
                        let result = `<div class="input-group input-group-sm mb-3 multiplePMLotDiv" id="multiplePmLot2_${index}">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="basic-addon1"> Parts Material Lot No.${index}</span>
                            </div>
                            <input class="form-control form-control-sm pmLotNum2" type="hidden" id="sec_molding_runcards_id-2_0_${index}" name="sec_molding_runcards_id2_0[]"  data-ref="2_0" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="pmat_lot_no-2_0_${index}" name="pmat_lot_no2_0[]"  data-ref="2_0" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="prodn_runcard-2_0_${index}" name="prodn_runcard2_0[]"  data-ref="2_0" readonly>

                        </div>`;
                        $(`#divMultiplePartsLot2_0`).append(result);
                        $(`#sec_molding_runcards_id-2_0_${index}`).val(data['id']);
                        $(`#pmat_lot_no-2_0_${index}`).val(data['contact_name_lot_number_one']);
                        $(`#prodn_runcard-2_0_${index}`).val(data['production_lot']);
                    }
                    if(contact_name_lot_number_second =! null ){
                        let result = `<div class="input-group input-group-sm mb-3 multiplePMLotDiv" id="multiplePmLot2_${index}">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="basic-addon1"> Parts Material Lot No.${index}</span>
                            </div>
                            <input class="form-control form-control-sm pmLotNum2" type="hidden" id="sec_molding_runcards_id-2_1_${index}" name="sec_molding_runcards_id2_1[]"  data-ref="2_1" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="pmat_lot_no-2_1_${index}" name="pmat_lot_no2_1[]"  data-ref="2_1" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="prodn_runcard-2_1_${index}" name="prodn_runcard2_1[]"  data-ref="2_1" readonly>
                        </div>`;
                        $(`#divMultiplePartsLot2_1`).append(result);
                        $(`#sec_molding_runcards_id-2_1_${index}`).val(data['id']);
                        $(`#pmat_lot_no-2_1_${index}`).val(data['contact_name_lot_number_second']);
                        $(`#prodn_runcard-2_1_${index}`).val(data['production_lot']);
                    }
                    if(me_name_lot_number_one =! null ){
                        let result = `<div class="input-group input-group-sm mb-3 multiplePMLotDiv" id="multiplePmLot2_${index}">
                            <div class="input-group-prepend w-25">
                                <span class="input-group-text w-100" id="basic-addon1"> Parts Material Lot No.${index}</span>
                            </div>
                            <input class="form-control form-control-sm pmLotNum2" type="hidden" id="sec_molding_runcards_id-2_2_${index}" name="sec_molding_runcards_id2_2[]"  data-ref="2_2" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="pmat_lot_no-2_2_${index}" name="pmat_lot_no2_2[]"  data-ref="2_2" readonly>
                            <input class="form-control form-control-sm pmLotNum2" type="text" id="prodn_runcard-2_2_${index}" name="prodn_runcard2_2[]"  data-ref="2_2" readonly>
                        </div>`;
                        $(`#divMultiplePartsLot2_2`).append(result);
                        $(`#sec_molding_runcards_id-2_2_${index}`).val(data['id']);
                        $(`#pmat_lot_no-2_2_${index}`).val(data['me_name_lot_number_one']);
                        $(`#prodn_runcard-2_2_${index}`).val(data['production_lot']);
                    }
                });
            }
            if(pmCat == 3){
                alert('C171S Part Material Not Found!') //TODO: Ongoing Development
            }
            $('#modalProductionHistory').modal('show');
        }
    });
}

