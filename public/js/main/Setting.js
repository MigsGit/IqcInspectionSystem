
/*
            "id": 1,
            "status": "<center><span class=\"badge rounded-pill bg-primary\"> Active <\/span><\/center>",
            "dropdown_category": "AQL",
            "section": "TS",
            "created_by": 1,
            "updated_by": 1,
            "deleted_at": null,
            "created_at": "2024-11-25T00:00:00.000000Z",
            "updated_at": "2024-11-18T00:00:00.000000Z",
            "action": "<center><button class='btn btn-info btn-sm mr-1' style='display: none;' receiving-detail-id='1'id='btnEditIqcInspection'><i class='fa-solid fa-pen-to-square'><\/i><\/button><\/center>"
*/
// settingDataTable.dropdownCategory = $(settingTbl.dropdownCategory).DataTable({
//     "processing" : true,
//     "serverSide" : true,
//     "ajax" : {
//         url: "load_whs_transaction",
//         data: function (param){
//             param.firstStamping = "true" //DT for 1st Stamping
//             param.lotNum = $('#txtSearchLotNum').val()
//         },
//     },
//     fixedHeader: true,
//     "columns":[
//         { "data" : "action", orderable:false, searchable:false },
//         { "data" : "status", orderable:false, searchable:false },
//         { "data" : "InvoiceNo" },
//         { "data" : "Supplier" },
//         { "data" : "PartNumber" },
//         { "data" : "MaterialType" },
//         { "data" : "Lot_number" },
//     ],
// });

const readDropdownCategoryById = function (DropdownCategoryId){
    let data = {
        'DropdownCategoryId' : DropdownCategoryId
    }
    call_ajax(data, 'read_dropdown_category_by_id', function(response){
        console.log(response.readDropdownCategoryById[0]);
        let data = response.readDropdownCategoryById[0];
        if(response.isSuccess === 'true'){
            form.saveDropdownCategory.find('#dropdown_category_id').val(data.id);
            form.saveDropdownCategory.find('#dropdown_category').val(data.dropdown_category);
            setTimeout(() => {
                form.saveDropdownCategory.find('#section').val(data.section);
            }, 300);
        }
    })
}
const readDropdownDetailsById = function (DropdownDetailsId){
    let data = {
        'DropdownDetailsId' : DropdownDetailsId
    }
    call_ajax(data, 'read_dropdown_details_by_id', function(response){
        console.log(response.readDropdownDetailsById[0]);
        // return;
        let data = response.readDropdownDetailsById[0];
        if(response.isSuccess === 'true'){
            form.saveDropdownDetails.find('#dropdown_details_id').val(data.id);
            form.saveDropdownDetails.find('#dropdown_details').val(data.dropdown_details);
        }
    })
}