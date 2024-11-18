function getLoadingPortDetailsId(loadingPortDetailsId){
    $.ajax({
        type: "get",
        url: "get_loading_port_details",
        data: {
            "loading_port_details_id" : loadingPortDetailsId,
        },
        dataType: "json",
        success: function (response) {

			if(response['loadingPortDetails'].length > 0){
				$('#txtLoadingPort').val(response['loadingPortDetails'][0]['loading_port'])
			}
			
        }
    });
}