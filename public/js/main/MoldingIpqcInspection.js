function DataFromMoldingPmiPo(cboElement){
    let result = '<option value="">N/A</option>'

    $.ajax({
        url: "get_molding_pmi_po",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>'
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['collectPmiPo'].length > 0){
                result = '<option selected disabled> --- Select PMI PO --- </option>';
                for(let index = 0; index < response['collectPmiPo'].length; index++){
                    result += '<option value="' + response['collectPmiPo'][index].pmi_po_no+'">'+ response['collectPmiPo'][index].pmi_po_no+'</option>'
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>'
            }
            cboElement.html(result);
        }
    })
}

function GetMoldingIpqcInspection(firstMoldingId, firstMoldingIpqcInspectionId){
	$.ajax({
        url: "get_molding_ipqc_inspection_by_id",
        method: "get",
        data: {
            'firstMoldingId'                : firstMoldingId,
            'firstMoldingIpqcInspectionId'  : firstMoldingIpqcInspectionId,
        },
        dataType: "json",
        beforeSend: function(){
            // ---------------
        },

        success: function(response){
            let getMoldingIpqcInspectionByid    = response['getMoldingIpqcInspectionByid']
            console.log('getMoldingIpqcInspectionByid',getMoldingIpqcInspectionByid)
            $('#txtMoldingIpqcInspectionPoNumber').val(getMoldingIpqcInspectionByid[0].pmi_po_no)
            $('#txtMoldingIpqcInspectionPartCode').val(getMoldingIpqcInspectionByid[0].item_code)
            $('#txtMoldingIpqcInspectionDeviceName').val(getMoldingIpqcInspectionByid[0].first_molding_device.device_name)
            $('#txtMoldingIpqcInspectionProductionLot').val(getMoldingIpqcInspectionByid[0].production_lot)
            $('#txtMoldingIpqcInspectionInput').val(getMoldingIpqcInspectionByid[0].qc_samples)

            if(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info != null){
                $('#txtMoldingIpqcInspectionOutput').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionNGQty').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionJudgement').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionInspectorName').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionSelectDocNoBDrawing').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionSelectDocNoInspStandard').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionSelectDocNoInspStandard').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
                $('#txtMoldingIpqcInspectionAddFile').val(getMoldingIpqcInspectionByid[0].molding_ipqc_inspection_info)
            } 
        },
    })
}

function UpdateMoldingIpqcInspection(){
    $.ajax({
        url: "update_molding_ipqc_inspection",
        method: "post",
        data: $('#formMoldingIpqcInspection').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnOqcInspectionIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnOqcInspection").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalMoldingIpqcInspection").modal('hide');
            	$("#formMoldingIpqcInspection")[0].reset();

            	dataTableDevices.draw();
                toastr.success('Succesfully saved!');
            }
            else{
                toastr.error('Saving Failed!');
            }

            $("#iBtnOqcInspectionIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnOqcInspection").removeAttr('disabled');
            $("#iBtnOqcInspectionIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnOqcInspectionIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnOqcInspection").removeAttr('disabled');
            $("#iBtnOqcInspectionIcon").addClass('fa fa-check');
        }
    });
}

