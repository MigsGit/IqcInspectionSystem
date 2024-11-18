function getDestinationPortDetailsId(destinationPortDetailsId){
    $.ajax({
        type: "get",
        url: "get_destination_port_details",
        data: {
            "destination_port_details_id" : destinationPortDetailsId,
        },
        dataType: "json",
        success: function (response) {

			if(response['destinationPortDetails'].length > 0){
				$('#txtDestinationPort').val(response['destinationPortDetails'][0]['destination_port'])
			}
			
        }
    });
}