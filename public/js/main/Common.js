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

