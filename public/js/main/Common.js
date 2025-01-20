/* Call basic ajax for submit */
function call_ajax(data = null, handler, fn,elFormId =null) {

    data = $.param(data);
    $.ajax({
        type: "GET",
        dataType: "json",
        data: data,
        url: handler,
        beforeSend: function(){
            // console.log('call_ajax elFormId',elFormId);
            // return;
            $('#modal-loading').modal('show');
            if(elFormId !=null){
                elFormId[0].reset();
            }
        },
        success: function (result) {
            fn(result);
            $('#modal-loading').modal('hide');

        },
        error: function (result) {
            fn(result);
            $('#modal-loading').modal('hide');
        }
    });
}

function call_ajax_serialize(data = null, serialized_data, handler, fn,elFormId =null) {
    data = $.param(data) + '&' + serialized_data;
	$.ajax({
        type: "post",
        dataType: "json",
        data: data,
        url: handler,
        beforeSend: function(){
            $('#modal-loading').modal('show');
            if(elFormId !=null){
                elFormId[0].reset();
            }
        },
        success: function (result) {
            fn(result);
            $('#modal-loading').modal('hide');

        },
        error: function (result) {
            alert('error ajax');
            $('#modal-loading').modal('hide');

        }
    });
}

function call_ajax_async_false(data, handler, fn) {
    data = $.param(data);
    $.ajax({
        type: "post",
        dataType: "json",
        data: data,
        url: handler,
		async: false,
        success: function (result) {
            fn(result);
        },
        error: function (result) {
            alert('error ajax');
        }
    });
}

function create_table(table_id, table_class, array_theader, array_tbody) {
    var html = '<table id="' + table_id + '" class="' + table_class + '">';
    /* Generate table header */
	html += '	<thead>';
	for(x=0;x<array_theader.length;x++){
		html += '	<th>' + array_theader[x] + '</th>';
    }
	html += '	</thead>'
	/* Generate table body */
	html += '	<tbody>';
	for(x=0;x<array_theader.length;x++){
		html += '	<td>' + array_tbody[x] + '</td>';
    }
	html += '	</tbody>';
	html += '</table>';
	return html;
}

function call_ajax_attachment(elFormId = null, serialized_data, handler, fn) {
	$.ajax({
		url				: handler,
		type			: "POST",
		data			: serialized_data,
		contentType		: false,
		dataType		: 'json',
		cache			: false,
		processData		: false,
        beforeSend: function(){
            $('#modal-loading').modal('show');
            if(elFormId !=null){
                elFormId[0].reset();
            }
        },
		success			: function(result)
		{
			fn(result);
		}, error : function (result) {
			// alert('ERROR: '+result['upload_msg']);
			/**
			 * TODO: Debug the for disposition in SAR
			 */
			// alert('Error: Please contact ISS');
			alert('Email Sent');
		}
	});
}



function resetFormValues() {
    // Reset values
    $("#formAddUser")[0].reset();

    // Reset hidden input fields
    // $("select[name='user_level']", $('#formAddUser')).val(0).trigger('change');

    // Remove invalid & title validation
    $('div').find('input').removeClass('is-invalid');
    $("div").find('input').attr('title', '');
    $('div').find('select').removeClass('is-invalid');
    $("div").find('select').attr('title', '');
}


$("#modalAddUser").on('hidden.bs.modal', function () {
    console.log('hidden.bs.modal');
    resetFormValues();
});

var invalidChars = ["-","+","e"];
$('input[type="number"]').on('keydown', function(e){
    if (invalidChars.includes(e.key)) {
        e.preventDefault();
    }
});


function resetFormProcessValues() {
    // Reset values
    $("#formProcess")[0].reset();
}

$("#modalAddProcess").on('hidden.bs.modal', function () {
    console.log('hidden.bs.modal');
    resetFormProcessValues();
});

function resetFormSublot() {
    // Reset values
    $("#formSublot")[0].reset();
    $('#btnSaveSublot').show();
    $('#buttons').show();
    $('.subLotMultiple').remove();
    $('#txtSublotMultipleCounter').val(1)
}

$("#modalMultipleSublot").on('hidden.bs.modal', function () {
    console.log('hidden.bs.modal');
    resetFormSublot();
});

function resetFormProdValues() {
    // Reset values
    $("#formProdData")[0].reset();
    $('#formProdData').find('input').removeClass('is-invalid'); // remove all invalid
    $('#saveProdData').show();
    // $('.appendDiv').remove();
    $('#btnRemoveMatNo').addClass('d-none');
    $('#divProdLotInput').removeClass('d-none');
    $('#divProdLotView').addClass('d-none');
    $('select[name="opt_name[]"]').val(0).trigger('change');
    $('#txtProdDataId').val('');
    // $('input',)
    $('#txtProdSamp').prop('readonly', false);
    $('#txtTtlMachOutput').prop('readonly', false);
    $('#txtProdDate').prop('readonly', false);
    $('#txtNGCount').prop('readonly', true);

    $('#selOperator').prop('disabled', true);
    $('#txtOptShift').prop('readonly', true);
    $('#txtInptCoilWeight').prop('readonly', false);
    $('#txtSetupPin').prop('readonly', false);
    $('#txtAdjPin').prop('readonly', false);
    $('#txtQcSamp').prop('readonly', false);
    $('#txtTargetOutput').prop('readonly', false);
    $('#prodLotNoExt1').prop('readonly', false);
    $('#prodLotNoExt2').prop('readonly', false);
    // $('.matNo').prop('readonly', false);
    $('input[name="cut_point"]').prop('disabled', false);
    $('#radioCutPointWithout').prop('checked', true);

    $('#button-addon2').prop('disabled', false);
    $('#btnScanOperator').prop('disabled', false);

    // $('#radioIQC').attr('checked', false);
    // $('#radioMassProd').attr('checked', false);
}

$("#modalProdData").on('hidden.bs.modal', function () {
    console.log('hidden.bs.modal');
    resetFormProdValues();
});

function resetFormProdSecondValues(){
    $('#saveProdData').show();
    $('#formProdDataSecondStamp')[0].reset();
    $('#divProdLotInput').removeClass('d-none');
    $('#divProdLotView').addClass('d-none');
    $('select[name="opt_name[]"]').val(0).trigger('change');
    $('#txtProdDataId').val('');
    $('#txtProdSamp').prop('readonly', false);
    $('#txtTtlMachOutput').prop('readonly', false);
    $('#txtProdDate').prop('readonly', false);
    $('#txtNGCount').prop('readonly', true);
    $('#selOperator').prop('disabled', true);
    $('#txtOptShift').prop('readonly', true);
    $('#txtSetupPin').prop('readonly', false);
    $('#txtAdjPin').prop('readonly', false);
    $('#txtQcSamp').prop('readonly', false);
    $('#selOperator').prop('readonly', false);
    $('#txtTargetOutput').prop('readonly', false);
    $('#txtInptPins').prop('readonly', false);
    $('#txtActQty').prop('readonly', false);
    $('#button-addon2').prop('disabled', false);
    $('#btnScanOperator').prop('disabled', false);

}
$("#modalProdSecondStamp").on('hidden.bs.modal', function () {
    console.log('hidden.bs.modal');
    resetFormProdSecondValues();
});

function fnSelect2EmployeeName(comboId){
    comboId.select2({
            placeholder: "",
            minimumInputLength: 1,
            allowClear: true,
            placeholder: {
            id: "",
            placeholder: "Leave blank to ..."
            },
            ajax:{
                type: "GET",
                url: "get_family",
                data: "data",
                dataType: "json",
                data: function (params) {
                    // console.log(params);
                    return {
                        search: params.term, // search term
                    };
                },
                processResults: function (response){
                    return{
                        results: response
                    };
                },
                cache: true
            }
    });
}

function fnGetSelect2Value(comboId,dataValue){
    // $('#formEditSa select[name="select_checked_by_qc[]"]').select2({
        console.log(dataValue);


    comboId.select2({
        // data : response['iqc_qc_checkedby']
        data : dataValue
    });
    var arrValue = [];
    $.each(dataValue, function(key, value){
        arrValue.push(value['id'])
    });

    comboId.val(arrValue).trigger('change');
}
/* Select 2 Attr */
$('.select2bs4').each(function () {
    $(this).select2({
        theme: 'bootstrap-5',
        dropdownParent: $(this).parent(),
    });
});

// $(this).on('select2:open', function(e) {
//     setTimeout(function () {
//         document.querySelector('input.select2-search__field').focus();
//     }, 0);
// });

function validateUser(userId, validPosition, callback){ // this function will accept scanned id and validPosition based on user table (number only)
    console.log('validPosition', validPosition);
    $.ajax({
        type: "get",
        url: "validate_user",
        data: {
            'id'    : userId,
            'pos'   : validPosition
        },
        dataType: "json",
        success: function (response) {
            let value1
            if(response['result'] == 1){
                value1 = true;
            }
            else{
                value1 = false;
            }

            callback(value1);
        }
    });
}

const errorHandler = function (errors,formInput){
    if(errors === undefined){
        formInput.removeClass('is-invalid')
        formInput.addClass('is-valid')
        formInput.attr('title', '')
    }else {
        formInput.removeClass('is-valid')
        formInput.addClass('is-invalid');
        formInput.attr('title', errors[0])
    }
}
//IQC FUNCTION
const getDropdownDetailsByOptValue = function (section=null,cmb_element,iqc_inspection_column_ref,opt_value = null) {
    let opt = `<option value="" selected disabled>-Select-</option>`;
        // opt += `<option value="N/A">N/A</option>`;
        console.log('cmb_element',cmb_element);
        cmb_element.empty().append(opt)
    $.ajax({
        type: "GET",
        url: "get_dropdown_details_by_opt_value",
        data: {
            "iqc_inspection_column_ref" : iqc_inspection_column_ref,
            "section" : section
        },
        dataType: "json",
        success: function (response) {
            let id = response['id'];
            let value = response['value'];
            if(iqc_inspection_column_ref =='target_lar' || iqc_inspection_column_ref == 'target_dppm'){
                cmb_element.val(value[0]);
                return;
            }
            if(iqc_inspection_column_ref =='mode_of_defects'){
                for (let i = 0; i < id.length; i++) {
                    let opt = `<option value="${value[i]}">${value[i]}</option>`;
                    cmb_element.append(opt);
                }
                return;
            }
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
const getSamplingSizeBySamplingPlanCn = function (severityOfInspection,inspectionLvl,aql,totalLotQty){
    let data = {
        'severity_of_inspection' : severityOfInspection,
        'inspection_lvl' : inspectionLvl,
        'aql' : aql,
        'total_lot_qty' : totalLotQty,
    }
    call_ajax(data,'get_sampling_size_by_sampling_plan_cn',function(response){
        console.log(response.sample_size);
        console.log('CN');

        form.iqcInspection.find('#sampling_size').val(response.sample_size)
        // form.iqcInspection.find('#sampling_size').val(tblWhsTrasanction['sampling_size']);
    })
}
const getSamplingSizeBySamplingPlanYf = function (severityOfInspection,inspectionLvl,aql,totalLotQty){
    let data = {
        'severity_of_inspection' : severityOfInspection,
        'inspection_lvl' : inspectionLvl,
        'aql' : aql,
        'total_lot_qty' : totalLotQty,
    }
    call_ajax(data,'get_sampling_size_by_sampling_plan_yf',function(response){
        console.log(response.sample_size);
        console.log('YF');

        form.iqcInspection.find('#sampling_size').val(response.sample_size)
        // form.iqcInspection.find('#sampling_size').val(tblWhsTrasanction['sampling_size']);
    })
}




// validateUser1 = function(userId, validPosition){ // this function will accept scanned id and validPosition based on user table (number only)
//     console.log('validPosition', validPosition);
//     $.ajax({
//         type: "get",
//         url: "validate_user",
//         data: {
//             'id'    : userId,
//             'pos'   : validPosition
//         },
//         dataType: "json",
//         success: function (response) {
//             let value1
//             if(response['result'] == 1){
//                 value1 = true;
//             }
//             else{
//                 value1 = false;
//             }

//             return value1;
//         }
//     });
// }

