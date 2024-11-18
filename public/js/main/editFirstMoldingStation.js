//first_molding_detail_mods
const editFirstMoldingStation = function () {
    let first_molding_station_id = $(this).attr('first-molding-station-id');
    // console.log(first_molding_station_id)
    // return;
    $.ajax({
        type: "GET",
        url: "get_first_molding_station_details",
        data: { "first_molding_station_id": first_molding_station_id },
        dataType: "json",
        success: function (response) {

            let data = response[ 'first_molding_detail_mod' ][ 0 ].belongs_to_first_molding_detail;
            let first_molding_detail_mod = response[ 'first_molding_detail_mod' ];

            formModal.firstMoldingStation.find('#first_molding_id').val(data.first_molding_id);
            formModal.firstMoldingStation.find('#first_molding_detail_id').val(data.id);
            formModal.firstMoldingStation.find('#date').val(data.date);
            formModal.firstMoldingStation.find('#operator_name').val(data.operator_name);
            formModal.firstMoldingStation.find('#input').val(data.input);
            formModal.firstMoldingStation.find('#ng_qty').val(data.ng_qty);
            formModal.firstMoldingStation.find('#output').val(data.output);
            formModal.firstMoldingStation.find('#station_yield').val(data.yield);
            formModal.firstMoldingStation.find('#remarks').val(data.remarks);

            setTimeout(() => {
                formModal.firstMoldingStation.find('#station').val(data.station);
            }, 300);
            console.log(formModal.firstMolding.find("#first_molding_id").val());
            $('#modalFirstMoldingStation').modal('show');

            getStation();
            // getModeOfDefectForSecondMolding($("#tableFirstMoldingStationMOD tr:last").find('.selectMOD'),modeOfDefectId);
            // for(let i = 0; i < first_molding_detail_mod.length; i++){
            //     // console.log(first_molding_detail_mod[i]);
            //     let defects = first_molding_detail_mod[i].defects_info.defects;
            //     let info_defects_id = first_molding_detail_mod[i].defects_info.id;
            //     let mod_quantity = first_molding_detail_mod[i].mod_quantity;
            //     let rowModeOfDefect = `
            //         <tr>
            //             <td>
            //                 <select class="form-control select2 select2bs4 selectMOD" name="mod_id[]">
            //                     <option value="${info_defects_id}" selected hidden>${defects}</option>
            //                 </select>
            //             </td>
            //             <td>
            //                 <input type="number" class="form-control textMODQuantity" name="mod_quantity[]" value="${mod_quantity}" min="1">
            //             </td>
            //             <td>
            //                 <center><button class="btn btn-xs btn-danger buttonRemoveMOD" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
            //             </td>
            //         </tr>
            //     `;
            //     $("#tableFirstMoldingStationMOD tbody").append(rowModeOfDefect);
            // }
            let totalNumberOfMOD = 0;
            let ngQty = formModal.firstMoldingStation.find('#ng_qty').val();
            $("#labelTotalNumberOfNG").empty();

            $('#tableFirstMoldingStationMOD .textMODQuantity').each(function () {
                if ($(this).val() === null || $(this).val() === "") {
                    $("#tableFirstMoldingStationMOD tbody").empty();
                    $("#labelTotalNumberOfNG").text(parseInt(0));
                }
                totalNumberOfMOD += parseInt($(this).val());
            });

            getValidateTotalNgQty(ngQty, totalNumberOfMOD);
        }, error: function (data, xhr, status) {
            toastr.error(`Error: ${data.status}`);
        }
    });
};
