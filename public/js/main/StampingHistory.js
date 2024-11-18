function GetStampingProdnMaterialName(cboElement){
    let result = '<option value="">N/A</option>'

    $.ajax({
        url: "get_stamping_prodn_material_name",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>'
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['getPrdnMaterialName'].length > 0){
                result = '<option selected disabled> --- Select --- </option>';
                for(let index = 0; index < response['getPrdnMaterialName'].length; index++){
                    result += '<option value="' + response['getPrdnMaterialName'][index].material_name +'">'+ response['getPrdnMaterialName'][index].material_name +'</option>'
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>'
            }
            cboElement.html(result)
        }
    })
}

function GetPatsPpdUser(cboElement){
    let result = '<option value="">N/A</option>'

    $.ajax({
        url: "get_user",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Scan Operator ID -- </option>'
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['users'].length > 0){
                for(let index = 0; index < response['users'].length; index++){
                    result += '<option value="' + response['users'][index].employee_id + '">'+ response['users'][index].firstname +' '+ response['users'][index].lastname + '</option>'
                }
            }
            cboElement.html(result)
        }
    })
}

function UpdateTotalShotAccumulatedOnTime(){
    $.ajax({
        url: "get_previous_shot_accumulated_by_partname",
        type: "get",
        data: {
            materialName : materialName,
        },
        dataType: "json",
        success: function (response) {
            let newTotalShotAccum = response['newTotalShotAccum']
            if(newTotalShotAccum != null){
                $('#txtStampingHistoryPrevTotalShotAccum').val(newTotalShotAccum)
            }else{
                $('#txtStampingHistoryPrevTotalShotAccum').val('0')   
            }
        }
    })
}

function UpdateStampingHistory(){
	$.ajax({
        url: "update_stamping_history",
        method: "post",
        data: $('#formStampingHistory').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnStampingHistoryIcon").addClass('spinner-border spinner-border-sm')
            $("#btnStampingHistory").addClass('disabled')
            $("#iBtnStampingHistoryIcon").removeClass('fa fa-check')
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving failed!')

                if(response['error']['stamping_history_part_name'] === undefined){
                    $("#txtStampingHistoryPartName").removeClass('is-invalid')
                    $("#txtStampingHistoryPartName").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryPartName").addClass('is-invalid');
                    $("#txtStampingHistoryPartName").attr('title', response['error']['stamping_history_part_name'])
                }

                if(response['error']['stamping_history_diecode_no'] === undefined){
                    $("#txtStampingHistoryDieCodeNo").removeClass('is-invalid')
                    $("#txtStampingHistoryDieCodeNo").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryDieCodeNo").addClass('is-invalid')
                    $("#txtStampingHistoryDieCodeNo").attr('title', response['error']['stamping_history_diecode_no'])
                }

                if(response['error']['date_stamping_history'] === undefined){
                    $("#dateStampingHistory").removeClass('is-invalid')
                    $("#dateStampingHistory").attr('title', '')
                }
                else{
                    $("#dateStampingHistory").addClass('is-invalid')
                    $("#dateStampingHistory").attr('title', response['error']['date_stamping_history'])
                }

                if(response['error']['stamping_history_total_shot'] === undefined){
                    $("#txtStampingHistoryTotalShot").removeClass('is-invalid')
                    $("#txtStampingHistoryTotalShot").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryTotalShot").addClass('is-invalid')
                    $("#txtStampingHistoryTotalShot").attr('title', response['error']['stamping_history_total_shot'])
                }

                if(response['error']['stamping_history_prev_total_shot_accum'] === undefined){
                    $("#txtStampingHistoryPrevTotalShotAccumulated").removeClass('is-invalid')
                    $("#txtStampingHistoryPrevTotalShotAccumulated").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryPrevTotalShotAccumulated").addClass('is-invalid')
                    $("#txtStampingHistoryPrevTotalShotAccumulated").attr('title', response['error']['stamping_history_prev_total_shot_accum'])
                }

                if(response['error']['stamping_history_new_total_shot_accum'] === undefined){
                    $("#txtStampingHistoryNewTotalShotAccumulated").removeClass('is-invalid')
                    $("#txtStampingHistoryNewTotalShotAccumulated").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryNewTotalShotAccumulated").addClass('is-invalid')
                    $("#txtStampingHistoryNewTotalShotAccumulated").attr('title', response['error']['stamping_history_new_total_shot_accum'])
                }

                if(response['error']['stamping_history_operator'] === undefined){
                    $("#slctStampingHistoryOperator").removeClass('is-invalid')
                    $("#slctStampingHistoryOperator").attr('title', '')
                }
                else{
                    $("#slctStampingHistoryOperator").addClass('is-invalid')
                    $("#slctStampingHistoryOperator").attr('title', response['error']['stamping_history_operator'])
                }

                if(response['error']['stamping_history_machine_no'] === undefined){
                    $("#txtStampingHistoryMachineNo").removeClass('is-invalid')
                    $("#txtStampingHistoryMachineNo").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryMachineNo").addClass('is-invalid')
                    $("#txtStampingHistoryMachineNo").attr('title', response['error']['stamping_history_machine_no'])
                }

                if(response['error']['stamping_history_die_height'] === undefined){
                    $("#txtStampingHistoryDieHeight").removeClass('is-invalid')
                    $("#txtStampingHistoryDieHeight").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryDieHeight").addClass('is-invalid')
                    $("#txtStampingHistoryDieHeight").attr('title', response['error']['stamping_history_die_height'])
                }

                if(response['error']['stamping_history_revolution_no'] === undefined){
                    $("#txtStampingHistoryRevolutionNo").removeClass('is-invalid')
                    $("#txtStampingHistoryRevolutionNo").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryRevolutionNo").addClass('is-invalid')
                    $("#txtStampingHistoryRevolutionNo").attr('title', response['error']['stamping_history_revolution_no'])
                }

                if(response['error']['stamping_history_revision_no'] === undefined){
                    $("#txtStampingHistoryRevisionNo").removeClass('is-invalid')
                    $("#txtStampingHistoryRevisionNo").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryRevisionNo").addClass('is-invalid')
                    $("#txtStampingHistoryRevisionNo").attr('title', response['error']['stamping_history_revision_no'])
                }

                if(response['error']['stamping_history_neraiti'] === undefined){
                    $("#txtStampingHistoryNeraiti").removeClass('is-invalid')
                    $("#txtStampingHistoryNeraiti").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryNeraiti").addClass('is-invalid')
                    $("#txtStampingHistoryNeraiti").attr('title', response['error']['stamping_history_neraiti'])
                }

                if(response['error']['stamping_history_remark'] === undefined){
                    $("#txtStampingHistoryRemark").removeClass('is-invalid')
                    $("#txtStampingHistoryRemark").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryRemark").addClass('is-invalid')
                    $("#txtStampingHistoryRemark").attr('title', response['error']['stamping_history_remark'])
                }

                if(response['error']['stamping_history_created_by'] === undefined){
                    $("#txtStampingHistoryCreatedBy").removeClass('is-invalid')
                    $("#txtStampingHistoryCreatedBy").attr('title', '')
                }
                else{
                    $("#txtStampingHistoryCreatedBy").addClass('is-invalid')
                    $("#txtStampingHistoryCreatedBy").attr('title', response['error']['stamping_history_created_by'])
                }
            }else if(response['hasError'] == 0){
                $("#formStampingHistory")[0].reset()
                $('#modalStampingHistory').modal('hide')
                dataTableStampingHistory.draw()
                toastr.success('Succesfully saved!')
            }else{
                alert('Control No. "'+$("#txtStampingHistoryPartName").val()+'" is already exist! '+"\n\n"+' Please refresh the browser to process the request once again.')
            }
            $("#iBtnStampingHistoryIcon").removeClass('spinner-border spinner-border-sm')
            $("#btnStampingHistory").removeClass('disabled')
            $("#iBtnStampingHistoryIcon").addClass('fa fa-check')
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status)
        }
    })
}

function GetStampingHistoryById(stampingHistoryID, partName){
	$.ajax({
        url: "get_stamping_history_by_id",
        method: "get",
        data: {
            'stampingHistoryID' :   stampingHistoryID,
            'partName'          :   partName
        },
        dataType: "json",
        beforeSend: function(){
            GetPatsPpdUser($('.getUsers'));
        },

        success: function(response){
            let getStampingHistoryToEdit    = response['getStampingHistoryToEdit']
            let getOperatorName             = response['getOperatorName']
            let totalSum                    = response['totalSum']

            if(getStampingHistoryToEdit.length > 0){
                let splitted_operator = getStampingHistoryToEdit[0].operator.split(',');
                for(let index = 0; index < splitted_operator.length; index++){
                    for(let outdex = 0; outdex < getOperatorName.length; outdex++){
                        if(splitted_operator[index] == getOperatorName[outdex].employee_id){
                            setTimeout(() => {
                                let operator = '<option selected value="' + splitted_operator[index] + '">' + getOperatorName[outdex].firstname +' '+ getOperatorName[outdex].lastname + '</option>';
                                $(`select[name="stamping_history_operator[]"]`, $('#formStampingHistory')).append(operator);
                            }, 500)
                        }
                    }
                }

                $('#txtStampingHistoryPartName').val(getStampingHistoryToEdit[0].part_name)
                $('#txtStampingHistoryDieCodeNo').val(getStampingHistoryToEdit[0].die_code_no)
                $('#dateStampingHistory').val(getStampingHistoryToEdit[0].date)
                $('#txtStampingHistoryPrevTotalShotAccumulated').val(totalSum)
                $('#txtStampingHistoryTotalShot').val(getStampingHistoryToEdit[0].total_shot)
                $('#txtStampingHistoryMachineNo').val(getStampingHistoryToEdit[0].machine_no)
                $('#txtStampingHistoryDieHeight').val(getStampingHistoryToEdit[0].die_height)
                $('#txtStampingHistoryRevolutionNo').val(getStampingHistoryToEdit[0].revolution_no)
                $('#txtStampingHistoryRevisionNo').val(getStampingHistoryToEdit[0].rev_no)
                $('#txtStampingHistoryNeraiti').val(getStampingHistoryToEdit[0].neraiti)
                $('#txtStampingHistoryRemark').val(getStampingHistoryToEdit[0].remarks)
                $('#txtStampingHistoryNewTotalShotAccumulated').val(Number(totalSum) + Number(getStampingHistoryToEdit[0].total_shot))
            }
        },
    })
}