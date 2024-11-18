function getCarrierDetailsId(carrierDetailsId){
    $.ajax({
        type: "get",
        url: "get_carrier_details",
        data: {
            "carrier_details_id" : carrierDetailsId,
        },
        dataType: "json",
        success: function (response) {

			if(response['carrierDetails'].length > 0){
				$('#txtCarrierName').val(response['carrierDetails'][0]['carrier_name'])
			}
			
        }
    });
}