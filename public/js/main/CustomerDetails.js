function getCustomerDetailsId(customerDetailsId){
    $.ajax({
        type: "get",
        url: "get_customer_details",
        data: {
            "customer_details_id" : customerDetailsId,
        },
        dataType: "json",
        success: function (response) {

			if(response['customerDetails'].length > 0){
				$('#txtCompanyName').val(response['customerDetails'][0]['company'])
				$('#txtCompanyContactPerson').val(response['customerDetails'][0]['contact_person'])
				$('#txtCompanyContactNo').val(response['customerDetails'][0]['company_contact_no'])
				$('#txtCompanyAddress').val(response['customerDetails'][0]['company_address']);
			}
			
        }
    });
}