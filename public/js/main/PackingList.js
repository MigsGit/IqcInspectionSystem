function getCustomer(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_customer_data',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['customerDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['customerDetails'].length; index++){
                    result += '<option value="' + response['customerDetails'][index].company + '">' + response['customerDetails'][index].company + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getCarrier(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_carrier_data',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['carrierDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['carrierDetails'].length; index++){
                    result += '<option value="' + response['carrierDetails'][index].carrier_name + '">' + response['carrierDetails'][index].carrier_name + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getPortOfLoading(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_loading_port_data',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['loadingPortDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['loadingPortDetails'].length; index++){
                    result += '<option value="' + response['loadingPortDetails'][index].loading_port + '">' + response['loadingPortDetails'][index].loading_port + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getPortOfDestination(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_destination_port_data',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['destinationPortDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['destinationPortDetails'].length; index++){
                    result += '<option value="' + response['destinationPortDetails'][index].destination_port + '">' + response['destinationPortDetails'][index].destination_port + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getPreparedByUser(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_ppc_clerk_details',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['userDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['userDetails'].length; index++){
                    result += '<option value="' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '">' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getCheckedByUser(cboElement){
	let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_ppc_sr_planner',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['userDetails'].length > 0){
				result = '<option value="0" disabled selected>Select One</option>';
				for(let index = 0; index < response['userDetails'].length; index++){
                    result += '<option value="' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '">' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function getCarbonCopyUser(cboElement){
	// let result = '<option value="0" disabled selected>Select One</option>';
	$.ajax({
		url: 'get_carbon_copy_user',
		method: 'get',
		dataType: 'json',
		beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			cboElement.html(result);
		},
		success: function(response){
			let disabled = '';
			if(response['userDetails'].length > 0){
				// result = '<option value="0" disabled selected></option>';
                result = '';
				for(let index = 0; index < response['userDetails'].length; index++){
                    result += '<option value="' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '">' + response['userDetails'][index].firstname + ' ' + response['userDetails'][index].lastname + '</option>';
				}
			}
			else{
				result = '<option value="0" disabled>No User Role found</option>';
			}
			cboElement.html(result);
		},
		error: function(data, xhr, status){
			result = '<option value="0" disabled>Reload Again</option>';
			cboElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
	});
}

function GetPOFromProductionData(SelectPOElement){
    // let result = '<option value="0" disabled selected>Select PO/s</option>';
    $.ajax({
        url: 'get_po_from_production',
        method: 'get',
        dataType: 'json',
        beforeSend: function(){
			result = '<option value="0" disabled>Loading</option>';
			SelectPOElement.html(result);
		},
        success: function(response) {
            if (response['productionData'].length > 0){
                result = "";
                    // result = '<option value="0" disabled selected>Select PO/s</option>';
                for (let index = 0; index < response['productionData'].length; index++) {
                    result += '<option value="' + response['productionData'][index].po_no + '">' + response['productionData'][index].po_no + '</option>';
                }
            } else {
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            SelectPOElement.html(result);
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            SelectPOElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetPackingListControlNo(SelectControlNoElement){
    let result = '<option value="" disabled selected>-- Select Control No. --</option>';
    $.ajax({
        url: 'get_packing_list_data',
        method: 'get',
        dataType: 'json',
        beforeSend: function() {
                result = '<option value="0" disabled selected>--Loading--</option>';
                SelectControlNoElement.html(result);
        },
        success: function(response) {
            console.log(response['packing_list_data']);
            // function unique(array) {
            let control_no = $.grep(response['packing_list_data'], function(el, index){
                                return index === $.inArray(el, response['packing_list_data']);
                            });
            // }
            // console.log(response['packing_list_data']);
            console.log(control_no);

            if (control_no.length > 0) {
                    result = '<option value="" disabled selected>--Select Control No.--</option>';
                for (let index = 0; index < control_no.length; index++) {
                        // let control_no = control_no[index].control_no;
                        // let sub_control_no = control_no.substring(0, 12);
                    result += '<option value="' + control_no[index] + '">' + control_no[index] + '</option>';
                }
            } else {
                result = '<option value="0" selected disabled> -- No record found -- </option>';
            }
            SelectControlNoElement.html(result);
            SelectControlNoElement.select2();
        },
        error: function(data, xhr, status) {
            result = '<option value="0" selected disabled> -- Reload Again -- </option>';
            SelectControlNoElement.html(result);
            console.log('Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}






