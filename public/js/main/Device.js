function AddDevice(){
    $.ajax({
        url: "add_device",
        method: "post",
        data: $('#formAddDevice').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnAddDeviceIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").prop('disabled', 'disabled');
        },
        success: function(JsonObject){
            if(JsonObject['result'] == 1){
            	$("#modalAddDevice").modal('hide');
            	$("#formAddDevice")[0].reset();

            	dataTableDevices.draw();
                toastr.success('Device was succesfully saved!');
            }
            else{
                toastr.error(`${JsonObject['msg']}`);

                if(JsonObject['error']['name'] === undefined){
                    $("#txtAddDeviceName").removeClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', '');
                }
                else{
                    $("#txtAddDeviceName").addClass('is-invalid');
                    $("#txtAddDeviceName").attr('title', JsonObject['error']['name']);
                }

                if(JsonObject['error']['code'] === undefined){
                    $("#txtAddDeviceCode").removeClass('is-invalid');
                    $("#txtAddDeviceCode").attr('title', '');
                }
                else{
                    $("#txtAddDeviceCode").addClass('is-invalid');
                    $("#txtAddDeviceCode").attr('title', JsonObject['error']['code']);
                }

                if(JsonObject['error']['stamp_step'] === undefined){
                    $("#selStampStep").removeClass('is-invalid');
                    $("#selStampStep").attr('title', '');
                }
                else{
                    $("#selStampStep").addClass('is-invalid');
                    $("#selStampStep").attr('title', JsonObject['error']['stamp_step']);
                }
            }

            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnAddDeviceIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnAddDevice").removeAttr('disabled');
            $("#iBtnAddDeviceIcon").addClass('fa fa-check');
        }
    });
}

function GetDeviceByIdToEdit(deviceId){
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
        url: "get_device_by_id",
        method: "get",
        data: {
            device_id: deviceId
        },
        dataType: "json",
        beforeSend: function(){
            $("#txtDeviceId").val("");
            $("#txtDeviceName").val("");
            $("#txtDeviceCode").val("");
        },
        success: function(JsonObject){

            let result = JsonObject['device'];
            // if(result.length > 0){


                $("#txtDeviceId").val(result.id);
                $("#txtAddDeviceCode").val(result.code);
                $("#txtAddDeviceName").val(result.name);
                $("#txtAddQty").val(result.qty_per_reel);
                $("#txtAddQtyBox").val(result.qty_per_box);

                $("#txtVirginPerc").val(result.virgin_percent);
                $("#txtRecycledPerc").val(result.recycle_percent);

                if(result.process == 0){ // * Stamping
                    $('#stamping').prop('checked', true);
                }
                else{ // * Molding
                    $('#molding').prop('checked', true);
                }
                $('#modalAddDevice').modal('show');
            // }
            // else{
            //     toastr.warning('No Device Record Found!');
            // }
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}
