function getOqcDetailsbyId(oqcDetailsId){
    $.ajax({
        type: "get",
        url: "get_oqc_details",
        data: {
            "oqc_details_id" : oqcDetailsId,
        },
        dataType: "json",
        success: function (response) {

			if(response['oqcData'] != null){
                    $('#txtPONumber').val(response['oqcData']['stamping_production_info']['po_num'])
                    $('#txtPOQuantity').val(response['oqcData']['stamping_production_info']['ship_output'])
                    $('#txtPartsName').val(response['oqcData']['stamping_production_info']['material_name'])
                    $('#txtProdLotNumber').val(response['oqcData']['stamping_production_info']['prod_lot_no'])
                    $('#txtDrawingNumber').val(response['oqcData']['stamping_production_info']['drawing_no'])
                    $('#txtNumberOfCuts').val(response['oqcData']['stamping_production_info']['no_of_cuts'])
			}else{
                toastr.warning('warning messages');
            }
			
        }
    });
}

function getPackingListDetails(packingDetailsCtrlNo){
    $.ajax({
        type: "get",
        url: "get_packing_list_details",
        data: {
            "packing_list_ctrl_no" : packingDetailsCtrlNo,
        },
        dataType: "json",
        success: function (response) {

			if(response['packingListDetails'] != null){
                    $('#getTextCtrlNumber').val(response['packingListDetails'][0]['control_no'])
                    $('#getTextPickUpDateAndTime').val(response['packingListDetails'][0]['pick_up_date'] + ' ' + response['packingListDetails'][0]['pick_up_time'])
                    $('#getTextCarrier').val(response['packingListDetails'][0]['carrier'])
                    $('#getTextShipFrom').val(response['packingListDetails'][0]['product_from'])
                    $('#getTextShipTo').val(response['packingListDetails'][0]['product_to'])
                    $('#getTextPortOfLoading').val(response['packingListDetails'][0]['port_of_loading'])
                    $('#getTextPortOfDestination').val(response['packingListDetails'][0]['port_of_destination'])
                    $('#getPreparedBy').val(response['packingListDetails'][0]['prepared_by'])
                    $('#getCheckedBy').val(response['packingListDetails'][0]['checked_by'])
                    $('#getCarbonCopy').val(response['packingListDetails'][0]['cc_personnel'])
			}else{
                toastr.warning('warning messages');
            }
			
        }
    });
}

const generatePackingQr = async (id) => {
    await $.ajax({
        type: "get",
        url: "generate_packing_qr",
        data: {
            "id" : id
        },
        dataType: "json",
        success: function (response) {
            response['label_hidden'][0]['id'] = id;
            $("#img_barcode_PO").attr('src', response['qrCode']);
            $("#img_barcode_PO_text").html(response['label']);
            img_barcode_PO_text_hidden = response['label_hidden'];
            $('#modalGeneratePackingDetailsQr').modal('show');
            console.log(response);
        }
    });
}

const changePrintingStatus = (printedId) => {
    // alert(printedId);
    $.ajax({
        type: "get",
        url: "change_printing_status",
        data: {
            id: printedId
        },
        dataType: "dataType",
        success: function (response) {
            
        }
    });
}


