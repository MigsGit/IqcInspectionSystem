function GetPpsWarehouse(cboElement){
    let result = '<option value="">N/A</option>'

    $.ajax({
        url: "get_pps_warehouse",
        method: "get",
        data: {
            ppsWhseDb : $('#slctMimfStampingMatrixPartNumber').val(),
        },
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>'
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['getPartNumber'].length > 0){
                result = '<option selected disabled> --- Select --- </option>';
                for(let index = 0; index < response['getPartNumber'].length; index++){
                    // result += '<option value="' + response['getPartNumber'][index].PartNumber +'">'+ response['getPartNumber'][index].PartNumber +'</option>'
                    result += '<option value="' + response['getPartNumber'][index].id +'">'+ response['getPartNumber'][index].PartNumber +' / '+ response['getPartNumber'][index].MaterialType +'</option>'
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>'
            }
            cboElement.html(result)
        }
    })
}

function GetPpsPoReceivedItemName(cboElement){
    let result = '<option value="">N/A</option>'

    $.ajax({
        url: "get_pps_po_recveived_item_name",
        method: "get",
        data: {
            poReceivedDb : $('#slctMimfStampingMatrixItemName').val(),
        },
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>'
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['getItemName'].length > 0){
                result = '<option selected disabled> --- Select --- </option>';
                for(let index = 0; index < response['getItemName'].length; index++){
                    result += '<option value="' + response['getItemName'][index].ItemName +'">'+ response['getItemName'][index].ItemName +'</option>'
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>'
            }
            cboElement.html(result)
        }
    })
}

function UpdateMimf(){
	$.ajax({
        url: "update_mimf",
        method: "post",
        data: $('#formMimf').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnMimfIcon").addClass('spinner-border spinner-border-sm')
            $("#btnMimf").addClass('disabled')
            $("#iBtnMimfIcon").removeClass('fa fa-check')
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving failed!')

                if(response['error']['mimf_control_no'] === undefined){
                    $("#txtMimfControlNo").removeClass('is-invalid')
                    $("#txtMimfControlNo").attr('title', '')
                }
                else{
                    $("#txtMimfControlNo").addClass('is-invalid');
                    $("#txtMimfControlNo").attr('title', response['error']['mimf_control_no'])
                }

                if(response['error']['mimf_pmi_po_no'] === undefined){
                    $("#txtMimfPmiPoNo").removeClass('is-invalid')
                    $("#txtMimfPmiPoNo").attr('title', '')
                }
                else{
                    $("#txtMimfPmiPoNo").addClass('is-invalid')
                    $("#txtMimfPmiPoNo").attr('title', response['error']['mimf_pmi_po_no'])
                }

                if(response['error']['mimf_date_issuance'] === undefined){
                    $("#dateMimfDateOfInssuance").removeClass('is-invalid')
                    $("#dateMimfDateOfInssuance").attr('title', '')
                }
                else{
                    $("#dateMimfDateOfInssuance").addClass('is-invalid')
                    $("#dateMimfDateOfInssuance").attr('title', response['error']['mimf_date_issuance'])
                }

                if(response['error']['mimf_prodn_quantity'] === undefined){
                    $("#txtMimfProdnQuantity").removeClass('is-invalid')
                    $("#txtMimfProdnQuantity").attr('title', '')
                }
                else{
                    $("#txtMimfProdnQuantity").addClass('is-invalid')
                    $("#txtMimfProdnQuantity").attr('title', response['error']['mimf_prodn_quantity'])
                }

                if(response['error']['mimf_device_code'] === undefined){
                    $("#txtMimfDeviceCode").removeClass('is-invalid')
                    $("#txtMimfDeviceCode").attr('title', '')
                }
                else{
                    $("#txtMimfDeviceCode").addClass('is-invalid')
                    $("#txtMimfDeviceCode").attr('title', response['error']['mimf_device_code'])
                }

                if(response['error']['mimf_device_name'] === undefined){
                    $("#txtMimfDeviceName").removeClass('is-invalid')
                    $("#txtMimfDeviceName").attr('title', '')
                }
                else{
                    $("#txtMimfDeviceName").addClass('is-invalid')
                    $("#txtMimfDeviceName").attr('title', response['error']['mimf_device_name'])
                }

                if(response['error']['mimf_material_code'] === undefined){
                    $("#txtMimfMaterialCode").removeClass('is-invalid')
                    $("#txtMimfMaterialCode").attr('title', '')
                }
                else{
                    $("#txtMimfMaterialCode").addClass('is-invalid')
                    $("#txtMimfMaterialCode").attr('title', response['error']['mimf_material_code'])
                }

                if(response['error']['mimf_material_type'] === undefined){
                    $("#txtMimfMaterialType").removeClass('is-invalid')
                    $("#txtMimfMaterialType").attr('title', '')
                }
                else{
                    $("#txtMimfMaterialType").addClass('is-invalid')
                    $("#txtMimfMaterialType").attr('title', response['error']['mimf_material_type'])
                }

                if(response['error']['mimf_quantity_from_inventory'] === undefined){
                    $("#txtMimfQuantityFromInventory").removeClass('is-invalid')
                    $("#txtMimfQuantityFromInventory").attr('title', '')
                }
                else{
                    $("#txtMimfQuantityFromInventory").addClass('is-invalid')
                    $("#txtMimfQuantityFromInventory").attr('title', response['error']['mimf_quantity_from_inventory'])
                }

                if(response['error']['mimf_needed_kgs'] === undefined){
                    $("#txtMimfNeededKgs").removeClass('is-invalid')
                    $("#txtMimfNeededKgs").attr('title', '')
                }
                else{
                    $("#txtMimfNeededKgs").addClass('is-invalid')
                    $("#txtMimfNeededKgs").attr('title', response['error']['mimf_needed_kgs'])
                }

                if(response['error']['mimf_virgin_material'] === undefined){
                    $("#txtMimfVirginMaterial").removeClass('is-invalid')
                    $("#txtMimfVirginMaterial").attr('title', '')
                }
                else{
                    $("#txtMimfVirginMaterial").addClass('is-invalid')
                    $("#txtMimfVirginMaterial").attr('title', response['error']['mimf_virgin_material'])
                }

                if(response['error']['mimf_recycled'] === undefined){
                    $("#txtMimfRecycled").removeClass('is-invalid')
                    $("#txtMimfRecycled").attr('title', '')
                }
                else{
                    $("#txtMimfRecycled").addClass('is-invalid')
                    $("#txtMimfRecycled").attr('title', response['error']['mimf_recycled'])
                }

                if(response['error']['date_mimf_prodn'] === undefined){
                    $("#dateMimfProdn").removeClass('is-invalid')
                    $("#dateMimfProdn").attr('title', '')
                }
                else{
                    $("#dateMimfProdn").addClass('is-invalid')
                    $("#dateMimfProdn").attr('title', response['error']['date_mimf_prodn'])
                }

                if(response['error']['mimf_delivery'] === undefined){
                    $("#dateMimfDelivery").removeClass('is-invalid')
                    $("#dateMimfDelivery").attr('title', '')
                }
                else{
                    $("#dateMimfDelivery").addClass('is-invalid')
                    $("#dateMimfDelivery").attr('title', response['error']['mimf_delivery'])
                }

                if(response['error']['mimf_remark'] === undefined){
                    $("#txtMimfRemark").removeClass('is-invalid')
                    $("#txtMimfRemark").attr('title', '')
                }
                else{
                    $("#txtMimfRemark").addClass('is-invalid')
                    $("#txtMimfRemark").attr('title', response['error']['mimf_remark'])
                }

                if(response['error']['created_by'] === undefined){
                    $("#txtCreatedBy").removeClass('is-invalid')
                    $("#txtCreatedBy").attr('title', '')
                }
                else{
                    $("#txtCreatedBy").addClass('is-invalid')
                    $("#txtCreatedBy").attr('title', response['error']['created_by'])
                }
            }else if(response['result'] == 1){
                alert('Control No. "'+$("#txtMimfControlNo").val()+'" is already exist! '+"\n\n"+' Please refresh the browser to process the request once again.')
            }else if(response['result'] == 2){
                alert('PMI Po No. "'+$("#txtMimfPmiPoNo").val()+'" is already exist!')
            }else{
                $('.mimfClass').removeClass('is-invalid')
                $("#formMimf")[0].reset()
                $('#modalMimf').modal('hide')
                dataTableMimf.draw()
                toastr.success('Succesfully saved!')
            }

            $("#iBtnMimfIcon").removeClass('spinner-border spinner-border-sm')
            $("#btnMimf").removeClass('disabled')
            $("#iBtnMimfIcon").addClass('fa fa-check')
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status)
        }
    })
}

function GetMimfById(mimfID,whseID,matrixID,poReceivedID,ppdMimfStampingMatrixID){
	$.ajax({
        url: "get_mimf_by_id",
        method: "get",
        data: {
            'mimfID'        :   mimfID,
            'whseID'        :   whseID,
            'matrixID'      :   matrixID,
            'dieSetID'      :   dieSetID,
            'poReceivedID'  :   poReceivedID,
            'ppdMimfStampingMatrixID'  :   ppdMimfStampingMatrixID
        },
        dataType: "json",
        beforeSend: function(){
            $('.mimfClass').removeClass('is-invalid')
        },

        success: function(response){
            let getMimfToEdit   = response['getMimfToEdit']
            console.log(getMimfToEdit)
            if(getMimfToEdit.length > 0){
                if(getMimfToEdit[0].category == 1){
                    $('.first').prop('checked', true)
                    $('.second').prop('disabled', true)
                }else{
                    $('.first').prop('disabled', true)
                    $('.second').prop('checked', true)
                }         

                if( $('#radioBtnSecondCategory').is(':checked') && $('#txtMimfStatus').val() == 1){
                    $('.second-stamping-pins-pcs').removeClass('d-none')
                    $('.first-stamping-needed-kgs').addClass('d-none')
                }else{
                    $('.second-stamping-pins-pcs').addClass('d-none')
                    $('.first-stamping-needed-kgs').removeClass('d-none')
                }

                if($('.update-mimf-pps_request').is(':checked')){
                    $('.update-mimf-pps_request').attr('required',false);
                    $('.second-stamping-pins-pcs').removeClass('d-none')
                    $('.first-stamping-needed-kgs').addClass('d-none')
                }else{
                    $('.update-mimf-pps_request').attr('required',true);
                }

                $('#txtMimfControlNo').val(getMimfToEdit[0].control_no)
                $('#txtMimfPmiPoNo').val(getMimfToEdit[0].pmi_po_no)
                $('#dateMimfDateOfInssuance').val(getMimfToEdit[0].date_issuance)
                $('#txtMimfProdnQuantity').val(getMimfToEdit[0].prodn_qty)
                $('#txtMimfDeviceCode').val(getMimfToEdit[0].device_code)
                $('#txtMimfDeviceName').val(getMimfToEdit[0].device_name)
                $('#txtMimfMaterialCode').val(getMimfToEdit[0].material_code)
                $('#txtMimfMaterialType').val(getMimfToEdit[0].material_type)
                $('#txtMimfQuantityFromInventory').val(getMimfToEdit[0].qty_invt)
                $('#txtMimfRequestPinsPcs').val(getMimfToEdit[0].request_pins_pcs)
                $('#txtMimfNeededKgs').val(getMimfToEdit[0].needed_kgs)
                $('#txtMimfVirginMaterial').val(getMimfToEdit[0].virgin_material)
                $('#txtMimfRecycled').val(getMimfToEdit[0].recycled)
                $('#dateMimfProdn').val(getMimfToEdit[0].prodn)
                $('#dateMimfDelivery').val(getMimfToEdit[0].delivery)
                $('#txtMimfRemark').val(getMimfToEdit[0].remarks)
            }
        },
    })
}

function UpdateMimfStampignMatrix(){
	$.ajax({
        url: "update_mimf_stamping_matrix",
        method: "post",
        data: $('#formMimfStampingMatrix').serialize(),
        dataType: "json",
        beforeSend: function(){
            $('.mimfClass').removeClass('is-invalid')
            $("#iBtnMimfStampingMatrixIcon").addClass('spinner-border spinner-border-sm')
            $("#btnMimfStampingMatrix").addClass('disabled')
            $("#iBtnMimfStampingMatrixIcon").removeClass('fa fa-check')
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving failed!')

                if(response['error']['mimf_stamping_matrix_item_code'] === undefined){
                    $("#txtMimfStampingMatrixItemCode").removeClass('is-invalid')
                    $("#txtMimfStampingMatrixItemCode").attr('title', '')
                }
                else{
                    $("#txtMimfStampingMatrixItemCode").addClass('is-invalid')
                    $("#txtMimfStampingMatrixItemCode").attr('title', response['error']['mimf_stamping_matrix_item_code'])
                }

                if(response['error']['mimf_stamping_matrix_item_name'] === undefined){
                    $("#slctMimfStampingMatrixItemName").removeClass('is-invalid')
                    $("#slctMimfStampingMatrixItemName").attr('title', '')
                }
                else{
                    $("#slctMimfStampingMatrixItemName").addClass('is-invalid')
                    $("#slctMimfStampingMatrixItemName").attr('title', response['error']['mimf_stamping_matrix_item_name'])
                }

                if(response['error']['mimf_stamping_matrix_pin_kg'] === undefined){
                    $("#txtMimfStampingMatrixPinkg").removeClass('is-invalid')
                    $("#txtMimfStampingMatrixPinkg").attr('title', '')
                }
                else{
                    $("#txtMimfStampingMatrixPinkg").addClass('is-invalid')
                    $("#txtMimfStampingMatrixPinkg").attr('title', response['error']['mimf_stamping_matrix_pin_kg'])
                }

                if(response['error']['mimf_stamping_matrix_part_number'] === undefined){
                    $("#slctMimfStampingMatrixPartNumber").removeClass('is-invalid')
                    $("#slctMimfStampingMatrixPartNumber").attr('title', '')
                }
                else{
                    $("#slctMimfStampingMatrixPartNumber").addClass('is-invalid')
                    $("#slctMimfStampingMatrixPartNumber").attr('title', response['error']['mimf_stamping_matrix_part_number'])
                }

                if(response['error']['mimf_stamping_matrix_material_type'] === undefined){
                    $("#txtMimfStampingMatrixMaterialType").removeClass('is-invalid')
                    $("#txtMimfStampingMatrixMaterialType").attr('title', '')
                }
                else{
                    $("#txtMimfStampingMatrixMaterialType").addClass('is-invalid')
                    $("#txtMimfStampingMatrixMaterialType").attr('title', response['error']['mimf_stamping_matrix_material_type'])
                }

                if(response['error']['mimf_stamping_matrix_created_by'] === undefined){
                    $("#txtMimfStampingMatrixCreatedBy").removeClass('is-invalid')
                    $("#txtMimfStampingMatrixCreatedBy").attr('title', '')
                }
                else{
                    $("#txtMimfStampingMatrixCreatedBy").addClass('is-invalid')
                    $("#txtMimfStampingMatrixCreatedBy").attr('title', response['error']['mimf_stamping_matrix_created_by'])
                }
            }else if(response['hasError'] == 0){
                $("#formMimfStampingMatrix")[0].reset()
                $('#modalMimfStampingMatrix').modal('hide')
                dataTableMimfStampingMatrix.draw()
                toastr.success('Succesfully saved!')
            }else{
                alert('')
            }

            $("#iBtnMimfStampingMatrixIcon").removeClass('spinner-border spinner-border-sm')
            $("#btnMimfStampingMatrix").removeClass('disabled')
            $("#iBtnMimfStampingMatrixIcon").addClass('fa fa-check')
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status)
        }
    })
}

function GetMimfStampingMatrixById(mimfStampingMatrixID){
	$.ajax({
        url: "get_mimf_stamping_matrix_by_id",
        method: "get",
        data: {
            'mimfStampingMatrixID'  :   mimfStampingMatrixID,
        },
        dataType: "json",
        beforeSend: function(){
            
        },

        success: function(response){
            let getMimfStampingMatrixToEdit   = response['getMimfStampingMatrixToEdit']
            console.log('EDIT: ', getMimfStampingMatrixToEdit[0])
            console.log('item_name: ', getMimfStampingMatrixToEdit[0].item_code)
            console.log('item_code: ', getMimfStampingMatrixToEdit[0].item_name)
            console.log('PartNumber: ', getMimfStampingMatrixToEdit[0].pps_whse_info.PartNumber)
            console.log('MaterialType: ', getMimfStampingMatrixToEdit[0].pps_whse_info.MaterialType)
            if(getMimfStampingMatrixToEdit.length > 0){
                setTimeout(() => {     
                    $('#txtMimfStampingMatrixItemCode').val(getMimfStampingMatrixToEdit[0].item_code)
                    $('#slctMimfStampingMatrixItemName').val(getMimfStampingMatrixToEdit[0].item_name).trigger('change')
                    $('#txtMimfStampingMatrixPinkg').val(getMimfStampingMatrixToEdit[0].pin_kg)
                    $('#slctMimfStampingMatrixPartNumber').val(getMimfStampingMatrixToEdit[0].pps_whse_info.id).trigger('change')
                    $('#txtMimfStampingMatrixMaterialType').val(getMimfStampingMatrixToEdit[0].pps_whse_info.MaterialType )
                }, 300);
            }
        },
    })
}