const getProdLotNoCtrl = async () => {
    await $.ajax({
        type: "get",
        url: "get_prod_lot_no_ctrl",
        data: "",
        dataType: "dataType",
        success: function (response) {
        }
    });
};
