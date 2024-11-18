// $(document).ready(function() {

    const dataTableMachine = {
        'MachineParameter': '',
        'InjectionTabListOne' :'',
    }

    const tableMachine = {
        tableMachineParameter_form1 : "#tableMachineParameter_form1",
        tableMachineParameter_form2 : "#tableMachineParameter_form2",
        // tableInjectionTabList : "#tableInjectionTabList",
    }
    const formMachine = {
        formAddMachine1 : $('#formAddMachine1'),
        formAddMachine2 : $('#formAddMachine2'),
        formInjectionTabList : $('#formInjectionTabList'),
    }

    const saveMachineOne = () => {
        $.ajax({
            type: 'POST',
            url: 'save_machine_one',
            data: $('#formAddMachine1').serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#modal-loading').modal('show');
            },
            success: function (response) {
                // console.log(response);
                // return;
                if(response['is_success'] == 'true'){
                    $('#modal-loading').modal('hide');
                    $('#modalAddMachine1').modal('hide');
                    $('#formAddMachine1')[0].reset();
                    toastr.success('Save Sucessfully');
                    dataTableMachine.MachineParameter.draw();
                }else{
                    toastr.error('Saving Failed');
                }
            },error: function (data, xhr, status){
                $('#modal-loading').modal('hide');
                if(data.status === 422){
                    let errors = data.responseJSON.errors ;
                    toastr.error(`Saving Failed, Please fill up all required fields`);
                    //Machine Parameter
                    errorHandler( errors.machine_id,formMachine.formAddMachine1.find('[name="machine_id"]') );
                    errorHandler( errors.device_name,formMachine.formAddMachine1.find('[name="device_name"]') );
                    errorHandler( errors.no_of_cavity,formMachine.formAddMachine1.find('[name="no_of_cavity"]') );
                    errorHandler( errors.material_mixing_ratio_v,formMachine.formAddMachine1.find('[name="material_mixing_ratio_v"]') );
                    errorHandler( errors.material_mixing_ratio_r,formMachine.formAddMachine1.find('[name="material_mixing_ratio_r"]') );
                    errorHandler( errors.material_name,formMachine.formAddMachine1.find('[name="material_name"]') );
                    errorHandler( errors.color,formMachine.formAddMachine1.find('[name="color"]') );
                    errorHandler( errors.machine_no,formMachine.formAddMachine1.find('[name="machine_no"]') );
                    errorHandler( errors.shot_weight,formMachine.formAddMachine1.find('[name="shot_weight"]') );
                    errorHandler( errors.unit_weight,formMachine.formAddMachine1.find('[name="unit_weight"]') );
                    //Mold Close
                    errorHandler( errors.hi_v,formMachine.formAddMachine1.find('[name="hi_v"]') );
                    errorHandler( errors.mid_slow,formMachine.formAddMachine1.find('[name="mid_slow"]') );
                    errorHandler( errors.low_v,formMachine.formAddMachine1.find('[name="low_v"]') ); //
                    errorHandler( errors.close_monitor_tm,formMachine.formAddMachine1.find('[name="close_monitor_tm"]') ); //
                    errorHandler( errors.slow_start,formMachine.formAddMachine1.find('[name="slow_start"]') );
                    errorHandler( errors.slow_end,formMachine.formAddMachine1.find('[name="slow_end"]') );
                    errorHandler( errors.lvlp,formMachine.formAddMachine1.find('[name="lvlp"]') );
                    errorHandler( errors.hpcl,formMachine.formAddMachine1.find('[name="hpcl"]') );
                    errorHandler( errors.mid_sl_p,formMachine.formAddMachine1.find('[name="mid_sl_p"]') );
                    errorHandler( errors.low_p,formMachine.formAddMachine1.find('[name="low_p"]') );
                    errorHandler( errors.hi_p,formMachine.formAddMachine1.find('[name="hi_p"]') );
                    //Ejector
                    errorHandler( errors.ej_pres,formMachine.formAddMachine1.find('[name="ej_pres"]') );
                    errorHandler( errors.fwd_ev1,formMachine.formAddMachine1.find('[name="fwd_ev1"]') );
                    errorHandler( errors.fwd_ev2,formMachine.formAddMachine1.find('[name="fwd_ev2"]') );
                    errorHandler( errors.fwd_stop,formMachine.formAddMachine1.find('[name="fwd_stop"]') );
                    errorHandler( errors.bwd_stop,formMachine.formAddMachine1.find('[name="bwd_stop"]') );
                    errorHandler( errors.stop_tm,formMachine.formAddMachine1.find('[name="stop_tm"]') );
                    errorHandler( errors.count,formMachine.formAddMachine1.find('[name="count"]') );
                    errorHandler( errors.pattern,formMachine.formAddMachine1.find('[name="pattern"]') );
                    errorHandler( errors.ejt_tmg,formMachine.formAddMachine1.find('[name="ejt_tmg"]') );
                    errorHandler( errors.ev2_chg,formMachine.formAddMachine1.find('[name="ev2_chg"]') );
                    errorHandler( errors.bwd_ev4,formMachine.formAddMachine1.find('[name="bwd_ev4"]') );
                    errorHandler( errors.bwd_prs,formMachine.formAddMachine1.find('[name="bwd_prs"]') );
                    errorHandler( errors.repeat_bwd_stop,formMachine.formAddMachine1.find('[name="repeat_bwd_stop"]') );
                    errorHandler( errors.repeat_ejt_ev3,formMachine.formAddMachine1.find('[name="repeat_ejt_ev3"]') );
                    errorHandler( errors.repeat_fwd_stop,formMachine.formAddMachine1.find('[name="repeat_fwd_stop"]') );
                    //Mold Open
                    errorHandler( errors.open_end_v,formMachine.formAddMachine1.find('[name="open_end_v"]') );
                    errorHandler( errors.hi_velocity_1_percent,formMachine.formAddMachine1.find('[name="hi_velocity_1_percent"]') );
                    errorHandler( errors.open_v,formMachine.formAddMachine1.find('[name="open_v"]') );
                    errorHandler( errors.open_stop,formMachine.formAddMachine1.find('[name="open_stop"]') );
                    errorHandler( errors.low_distance,formMachine.formAddMachine1.find('[name="low_distance"]') );
                    errorHandler( errors.hi_velocity_1mm,formMachine.formAddMachine1.find('[name="hi_velocity_1mm"]') );
                    errorHandler( errors.hi_velocity_2_percent,formMachine.formAddMachine1.find('[name="hi_velocity_2_percent"]') );
                    errorHandler( errors.mold_rotation,formMachine.formAddMachine1.find('[name="mold_rotation"]') );
                    errorHandler( errors.hi_velocity_2mm,formMachine.formAddMachine1.find('[name="hi_velocity_2mm"]') );
                    //SETUP
                    errorHandler( errors.setup_close_v,formMachine.formAddMachine1.find('[name="setup_close_v"') );
                    errorHandler( errors.setup_close_p,formMachine.formAddMachine1.find('[name="setup_close_p"') );
                    errorHandler( errors.setup_open_v,formMachine.formAddMachine1.find('[name="setup_open_v"') );
                    errorHandler( errors.setup_rot_v,formMachine.formAddMachine1.find('[name="setup_rot_v"') );
                    errorHandler( errors.setup_ejt_v,formMachine.formAddMachine1.find('[name="setup_ejt_v"') );
                    errorHandler( errors.setup_ejt_p,formMachine.formAddMachine1.find('[name="setup_ejt_p"') );
                    //Heater
                    errorHandler( errors.nozzle_set,formMachine.formAddMachine1.find('[name="nozzle_set"]') );
                    errorHandler( errors.front_set,formMachine.formAddMachine1.find('[name="front_set"]') );
                    errorHandler( errors.mid_set,formMachine.formAddMachine1.find('[name="mid_set"]') );
                    errorHandler( errors.rear_set,formMachine.formAddMachine1.find('[name="rear_set"]') );
                    errorHandler( errors.nozzle_actual,formMachine.formAddMachine1.find('[name="nozzle_actual"]') );
                    errorHandler( errors.front_actual,formMachine.formAddMachine1.find('[name="front_actual"]') );
                    errorHandler( errors.mid_actual,formMachine.formAddMachine1.find('[name="mid_actual"]') );
                    errorHandler( errors.rear_actual,formMachine.formAddMachine1.find('[name="rear_actual"]') );
                    //Injection Velocity
                    errorHandler( errors.cooling_time,formMachine.formAddMachine1.find('[name="cooling_time"]') );
                    errorHandler( errors.cycle_start,formMachine.formAddMachine1.find('[name="cycle_start"]') );
                    errorHandler( errors.inj_pp2,formMachine.formAddMachine1.find('[name="inj_pp2"]') );
                    errorHandler( errors.inj_pp3,formMachine.formAddMachine1.find('[name="inj_pp3"]') );
                    errorHandler( errors.inj_pp1,formMachine.formAddMachine1.find('[name="inj_pp1"]') );
                    errorHandler( errors.inj_v1,formMachine.formAddMachine1.find('[name="inj_v1"]') );
                    errorHandler( errors.inj_v2,formMachine.formAddMachine1.find('[name="inj_v2"]') );
                    errorHandler( errors.inj_v3,formMachine.formAddMachine1.find('[name="inj_v3"]') );
                    errorHandler( errors.inj_v4,formMachine.formAddMachine1.find('[name="inj_v4"]') );
                    errorHandler( errors.inj_v6,formMachine.formAddMachine1.find('[name="inj_v6"]') );
                    errorHandler( errors.inj_v5,formMachine.formAddMachine1.find('[name="inj_v5"]') );
                    errorHandler( errors.inj_sv1,formMachine.formAddMachine1.find('[name="inj_sv1"]') );
                    errorHandler( errors.inj_sv2,formMachine.formAddMachine1.find('[name="inj_sv2"]') );
                    errorHandler( errors.inj_sv3,formMachine.formAddMachine1.find('[name="inj_sv3"]') );
                    errorHandler( errors.inj_sv4,formMachine.formAddMachine1.find('[name="inj_sv4"]') );
                    errorHandler( errors.inj_sv5,formMachine.formAddMachine1.find('[name="inj_sv5"]') );
                    errorHandler( errors.inj_sm,formMachine.formAddMachine1.find('[name="inj_sm"]') );
                    errorHandler( errors.inj_sd,formMachine.formAddMachine1.find('[name="inj_sd"]') );
                    errorHandler( errors.inj_tp1,formMachine.formAddMachine1.find('[name="inj_tp1"]') );
                    errorHandler( errors.inj_tp2,formMachine.formAddMachine1.find('[name="inj_tp2"]') );
                    errorHandler( errors.inj_pos_change_mode,formMachine.formAddMachine1.find('[name="inj_pos_change_mode"]') );
                    errorHandler( errors.inj_pos_vs,formMachine.formAddMachine1.find('[name="inj_pos_vs"]') );
                    errorHandler( errors.inj_pos_pb,formMachine.formAddMachine1.find('[name="inj_pos_pb"]') );
                    errorHandler( errors.inj_pv1,formMachine.formAddMachine1.find('[name="inj_pv1"]') );
                    errorHandler( errors.inj_pv2,formMachine.formAddMachine1.find('[name="inj_pv2"]') );
                    errorHandler( errors.inj_pv3,formMachine.formAddMachine1.find('[name="inj_pv3"]') );
                    errorHandler( errors.inj_sp1,formMachine.formAddMachine1.find('[name="inj_sp1"]') );
                    errorHandler( errors.inj_sp2,formMachine.formAddMachine1.find('[name="inj_sp2"]') );
                    errorHandler( errors.injection_time,formMachine.formAddMachine1.find('[name="injection_time"]') );
                    errorHandler( errors.inj_fill,formMachine.formAddMachine1.find('[name="inj_fill"]') );
                    errorHandler( errors.inj_hold,formMachine.formAddMachine1.find('[name="inj_hold"]') );
                    errorHandler( errors.inj_pos_bp,formMachine.formAddMachine1.find('[name="inj_pos_bp"]') );
                    //Support
                    errorHandler( errors.noz_bwd_tm_1,formMachine.formAddMachine1.find('[name="noz_bwd_tm_1"') );
                    errorHandler( errors.inj_st_tmg_1,formMachine.formAddMachine1.find('[name="inj_st_tmg_1"') );
                    errorHandler( errors.noz_bwd_tmg_2,formMachine.formAddMachine1.find('[name="noz_bwd_tmg_2"') );
                    errorHandler( errors.inj_st_tmg_2,formMachine.formAddMachine1.find('[name="inj_st_tmg_2"') );
                    //Injection Tab
                    errorHandler( errors.inj_tab_fill_time,formMachine.formAddMachine1.find('[name="inj_tab_fill_time"') );
                    errorHandler( errors.inj_tab_plastic_time,formMachine.formAddMachine1.find('[name="inj_tab_plastic_time"') );
                    errorHandler( errors.inj_tab_cycle_time,formMachine.formAddMachine1.find('[name="inj_tab_cycle_time"') );
                    errorHandler( errors.inj_tab_spray,formMachine.formAddMachine1.find('[name="inj_tab_spray"') );
                    errorHandler( errors.inj_tab_spray_tm,formMachine.formAddMachine1.find('[name="inj_tab_spray_tm"') );
                    errorHandler( errors.inj_tab_screw_most_fwd,formMachine.formAddMachine1.find('[name="inj_tab_screw_most_fwd"') );
                    errorHandler( errors.inj_tab_enj_end_pos,formMachine.formAddMachine1.find('[name="inj_tab_enj_end_pos"') );
                    errorHandler( errors.inj_tab_airblow_start_time,formMachine.formAddMachine1.find('[name="inj_tab_airblow_start_time"') );
                    errorHandler( errors.inj_tab_airblow_blow_time,formMachine.formAddMachine1.find('[name="inj_tab_airblow_blow_time"') );
                    errorHandler( errors.inj_tab_punch_applcn,formMachine.formAddMachine1.find('[name="inj_tab_punch_applcn"') );
                    errorHandler( errors.inj_tab_md_temp_requirement,formMachine.formAddMachine1.find('[name="inj_tab_md_temp_requirement"') );
                    errorHandler( errors.inj_tab_md_time_requirement,formMachine.formAddMachine1.find('[name="inj_tab_md_time_requirement"') );
                    errorHandler( errors.inj_tab_md_temp_actual,formMachine.formAddMachine1.find('[name="inj_tab_md_temp_actual"') );
                }else{
                    toastr.error(`Error: ${data.status}`);
                }
            }

        });
    }

    const getMachine1 =  (cboElement) => {
        let result = '<option value="0" disabled selected>Select One</option>';
        $.ajax({
            url: 'get_machine_name_form1',
            method: 'get',
            dataType: 'json',
            beforeSend: function(){
                result = '<option value="0" disabled>Loading</option>';
                cboElement.html(result);
            },
            success: function(response){
                let disabled = '';
                if(response['machine_details_1'].length > 0){
                    result = '<option value="0" disabled selected>Select One</option>';
                    for(let index = 0; index < response['machine_details_1'].length; index++){
                        result += '<option value="' + response['machine_details_1'][index].id + '">' + response['machine_details_1'][index].machine_name + '</option>';
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

    const getMachine2 =  (cboElement) => {
        let result = '<option value="0" disabled selected>Select One</option>';
        $.ajax({
            url: 'get_machine_name_form2',
            method: 'get',
            dataType: 'json',
            beforeSend: function(){
                result = '<option value="0" disabled>Loading</option>';
                cboElement.html(result);
            },
            success: function(response){
                let disabled = '';
                if(response['machine_details_2'].length > 0){
                    result = '<option value="0" disabled selected>Select One</option>';
                    for(let index = 0; index < response['machine_details_2'].length; index++){
                        result += '<option value="' + response['machine_details_2'][index].id + '">' + response['machine_details_2'][index].machine_name + '</option>';
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

    const editMachineParameter = (machineParameterId) => {
        $.ajax({
            type: 'GET',
            url: 'edit_machine_parameter',
            data: {'machine_parameter_id' : machineParameterId},
            dataType: 'json',
            beforeSend: function(){
                $('#modal-loading').modal('show');
            },success: function (response) {
                $('#modal-loading').modal('hide');
                let machineParameter = response.machine_parameter_detail;
                let moldClose = machineParameter.mold_close;
                let ejectorLub = machineParameter.ejector_lub;
                let moldOpen = machineParameter.mold_open;
                let setup = machineParameter.setup;
                let heater = machineParameter.heater;
                let injectionVelocity = machineParameter.injection_velocity;
                let support = machineParameter.support;
                let injectionTab = machineParameter.injection_tab;
                //Machine Parameter //date_created
                formMachine.formAddMachine1.find('[name="machine_parameter_id"]').val(machineParameter.id);
                formMachine.formAddMachine1.find('[name="machine_id"]').val(machineParameter.machine_id);
                formMachine.formAddMachine1.find('[name="device_name"]').val(machineParameter.device_name);
                formMachine.formAddMachine1.find('[name="no_of_cavity"]').val(machineParameter.no_of_cavity);
                formMachine.formAddMachine1.find('[name="material_mixing_ratio_v"]').val(machineParameter.material_mixing_ratio_v);
                formMachine.formAddMachine1.find('[name="material_mixing_ratio_r"]').val(machineParameter.material_mixing_ratio_r);
                formMachine.formAddMachine1.find('[name="material_name"]').val(machineParameter.material_name);
                formMachine.formAddMachine1.find('[name="color"]').val(machineParameter.color);
                formMachine.formAddMachine1.find('[name="machine_no"]').val(machineParameter.machine_no);
                formMachine.formAddMachine1.find('[name="shot_weight"]').val(machineParameter.shot_weight);
                formMachine.formAddMachine1.find('[name="unit_weight"]').val(machineParameter.unit_weight);
                formMachine.formAddMachine1.find('[name="date_created"]').val(response.created_at);
                if(machineParameter.is_accumulator === 1){
                    formMachine.formAddMachine1.find('#with').prop('checked',true);
                    formMachine.formAddMachine1.find('#without').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#with').prop('checked',false);
                    formMachine.formAddMachine1.find('#without').prop('checked',true);
                }

                if(machineParameter.dryer_used === 1){
                    formMachine.formAddMachine1.find('#dryerOven').prop('checked',true);
                    formMachine.formAddMachine1.find('#dryerDHD').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#dryerOven').prop('checked',false);
                    formMachine.formAddMachine1.find('#dryerDHD').prop('checked',true);
                }
                //Mold Close
                formMachine.formAddMachine1.find('[name="hi_v"]').val(moldClose.hi_v);
                formMachine.formAddMachine1.find('[name="mid_slow"]').val(moldClose.mid_slow);
                formMachine.formAddMachine1.find('[name="low_v"]').val(moldClose.low_v);
                formMachine.formAddMachine1.find('[name="close_monitor_tm"]').val(moldClose.close_monitor_tm); //NOTE: For Machine 1 only
                formMachine.formAddMachine1.find('[name="slow_start"]').val(moldClose.slow_start);
                formMachine.formAddMachine1.find('[name="slow_end"]').val(moldClose.slow_end);
                formMachine.formAddMachine1.find('[name="lvlp"]').val(moldClose.lvlp);
                formMachine.formAddMachine1.find('[name="hpcl"]').val(moldClose.hpcl);
                formMachine.formAddMachine1.find('[name="mid_sl_p"]').val(moldClose.mid_sl_p);
                formMachine.formAddMachine1.find('[name="low_p"]').val(moldClose.low_p);
                formMachine.formAddMachine1.find('[name="hi_p"]').val(moldClose.hi_p);
                if(machineParameter.dryer_used === 1){
                    formMachine.formAddMachine1.find('#HiPton').prop('checked',true);
                    formMachine.formAddMachine1.find('#HiPPercent').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#HiPton').prop('checked',false);
                    formMachine.formAddMachine1.find('#HiPPercent').prop('checked',true);
                }
                //Ejector
                formMachine.formAddMachine1.find('[name="pattern"]').val(ejectorLub.pattern);
                formMachine.formAddMachine1.find('[name="ej_pres"]').val(ejectorLub.ej_pres);
                formMachine.formAddMachine1.find('[name="fwd_ev1"]').val(ejectorLub.fwd_ev1);
                formMachine.formAddMachine1.find('[name="fwd_ev2"]').val(ejectorLub.fwd_ev2);
                formMachine.formAddMachine1.find('[name="stop_tm"]').val(ejectorLub.stop_tm);
                formMachine.formAddMachine1.find('[name="count"]').val(ejectorLub.count);
                formMachine.formAddMachine1.find('[name="ejt_tmg"]').val(ejectorLub.ejt_tmg);
                formMachine.formAddMachine1.find('[name="ev2_chg"]').val(ejectorLub.ev2_chg);
                formMachine.formAddMachine1.find('[name="fwd_stop"]').val(ejectorLub.fwd_stop);
                formMachine.formAddMachine1.find('[name="bwd_ev4"]').val(ejectorLub.bwd_ev4);
                formMachine.formAddMachine1.find('[name="bwd_prs"]').val(ejectorLub.bwd_prs);
                formMachine.formAddMachine1.find('[name="repeat_bwd_stop"]').val(ejectorLub.repeat_bwd_stop);
                formMachine.formAddMachine1.find('[name="repeat_ejt_ev3"]').val(ejectorLub.repeat_ejt_ev3);
                formMachine.formAddMachine1.find('[name="repeat_fwd_stop"]').val(ejectorLub.repeat_fwd_stop);
                //Mold Open
                formMachine.formAddMachine1.find('[name="open_end_v"]').val(moldOpen.open_end_v);
                formMachine.formAddMachine1.find('[name="hi_velocity_2_percent"]').val(moldOpen.hi_velocity_2_percent);
                formMachine.formAddMachine1.find('[name="hi_velocity_1_percent"]').val(moldOpen.hi_velocity_1_percent);
                formMachine.formAddMachine1.find('[name="open_v"]').val(moldOpen.open_v);
                formMachine.formAddMachine1.find('[name="mold_rotation"]').val(moldOpen.mold_rotation);
                formMachine.formAddMachine1.find('[name="open_stop"]').val(moldOpen.open_stop);
                formMachine.formAddMachine1.find('[name="low_distance"]').val(moldOpen.low_distance);
                formMachine.formAddMachine1.find('[name="hi_velocity_1mm"]').val(moldOpen.hi_velocity_1mm);
                formMachine.formAddMachine1.find('[name="hi_velocity_2mm"]').val(moldOpen.hi_velocity_2mm);
                //Setup
                formMachine.formAddMachine1.find('[name="setup_close_v"').val(setup.setup_close_v);
                formMachine.formAddMachine1.find('[name="setup_close_p"').val(setup.setup_close_p);
                formMachine.formAddMachine1.find('[name="setup_open_v"').val(setup.setup_open_v);
                formMachine.formAddMachine1.find('[name="setup_rot_v"').val(setup.setup_rot_v);
                formMachine.formAddMachine1.find('[name="setup_ejt_v"').val(setup.setup_ejt_v);
                formMachine.formAddMachine1.find('[name="setup_ejt_p"').val(setup.setup_ejt_p);
                //Heater
                formMachine.formAddMachine1.find('[name="nozzle_set"]').val(heater.nozzle_set);
                formMachine.formAddMachine1.find('[name="front_set"]').val(heater.front_set);
                formMachine.formAddMachine1.find('[name="mid_set"]').val(heater.mid_set);
                formMachine.formAddMachine1.find('[name="rear_set"]').val(heater.rear_set);
                formMachine.formAddMachine1.find('[name="nozzle_actual"]').val(heater.nozzle_actual);
                formMachine.formAddMachine1.find('[name="front_actual"]').val(heater.front_actual);
                formMachine.formAddMachine1.find('[name="mid_actual"]').val(heater.mid_actual);
                formMachine.formAddMachine1.find('[name="rear_actual"]').val(heater.rear_actual);
                //Injection Velocity
                formMachine.formAddMachine1.find('[name="injection_time"]').val(injectionVelocity.injection_time);
                formMachine.formAddMachine1.find('[name="cooling_time"]').val(injectionVelocity.cooling_time);
                formMachine.formAddMachine1.find('[name="cycle_start"]').val(injectionVelocity.cycle_start);
                formMachine.formAddMachine1.find('[name="inj_pp2"]').val(injectionVelocity.inj_pp2);
                formMachine.formAddMachine1.find('[name="inj_pp3"]').val(injectionVelocity.inj_pp3);
                formMachine.formAddMachine1.find('[name="inj_pp1"]').val(injectionVelocity.inj_pp1);
                formMachine.formAddMachine1.find('[name="inj_v1"]').val(injectionVelocity.inj_v1);
                formMachine.formAddMachine1.find('[name="inj_v2"]').val(injectionVelocity.inj_v2);
                formMachine.formAddMachine1.find('[name="inj_v3"]').val(injectionVelocity.inj_v3);
                formMachine.formAddMachine1.find('[name="inj_v4"]').val(injectionVelocity.inj_v4);
                formMachine.formAddMachine1.find('[name="inj_v6"]').val(injectionVelocity.inj_v6);
                formMachine.formAddMachine1.find('[name="inj_v5"]').val(injectionVelocity.inj_v5);
                formMachine.formAddMachine1.find('[name="inj_sv1"]').val(injectionVelocity.inj_sv1);
                formMachine.formAddMachine1.find('[name="inj_sv2"]').val(injectionVelocity.inj_sv2);
                formMachine.formAddMachine1.find('[name="inj_sv3"]').val(injectionVelocity.inj_sv3);
                formMachine.formAddMachine1.find('[name="inj_sv4"]').val(injectionVelocity.inj_sv4);
                formMachine.formAddMachine1.find('[name="inj_sv5"]').val(injectionVelocity.inj_sv5);
                formMachine.formAddMachine1.find('[name="inj_sm"]').val(injectionVelocity.inj_sm);
                formMachine.formAddMachine1.find('[name="inj_sd"]').val(injectionVelocity.inj_sd);
                formMachine.formAddMachine1.find('[name="inj_tp1"]').val(injectionVelocity.inj_tp1);
                formMachine.formAddMachine1.find('[name="inj_tp2"]').val(injectionVelocity.inj_tp2);
                formMachine.formAddMachine1.find('[name="inj_pos_change_mode"]').val(injectionVelocity.inj_pos_change_mode);
                formMachine.formAddMachine1.find('[name="inj_pos_vs"]').val(injectionVelocity.inj_pos_vs);
                formMachine.formAddMachine1.find('[name="inj_pos_pb"]').val(injectionVelocity.inj_pos_pb);
                formMachine.formAddMachine1.find('[name="inj_pv1"]').val(injectionVelocity.inj_pv1);
                formMachine.formAddMachine1.find('[name="inj_pv2"]').val(injectionVelocity.inj_pv2);
                formMachine.formAddMachine1.find('[name="inj_pv3"]').val(injectionVelocity.inj_pv3);
                formMachine.formAddMachine1.find('[name="inj_sp1"]').val(injectionVelocity.inj_sp1);
                formMachine.formAddMachine1.find('[name="inj_sp2"]').val(injectionVelocity.inj_sp2);
                formMachine.formAddMachine1.find('[name="inj_fill"]').val(injectionVelocity.inj_fill);
                formMachine.formAddMachine1.find('[name="inj_hold"]').val(injectionVelocity.inj_hold);
                formMachine.formAddMachine1.find('[name="inj_pos_bp"]').val(injectionVelocity.inj_pos_bp);
                //Support
                formMachine.formAddMachine1.find('[name="noz_bwd_tm_1"').val(support.noz_bwd_tm_1);
                formMachine.formAddMachine1.find('[name="inj_st_tmg_1"').val(support.inj_st_tmg_1);
                formMachine.formAddMachine1.find('[name="noz_bwd_tmg_2"').val(support.noz_bwd_tmg_2);
                formMachine.formAddMachine1.find('[name="inj_st_tmg_2"').val(support.inj_st_tmg_2);
                // formMachine.formAddMachine1.find('[name="support_tempo"').val(support.support_tempo);
                console.log(support.support_tempo);
                if(support.support_tempo === "SLOW"){
                    formMachine.formAddMachine1.find('#supportTempoSlow').prop('checked',true);
                    formMachine.formAddMachine1.find('#supportTempoMedium').prop('checked',false);
                    formMachine.formAddMachine1.find('#supportTempoFast').prop('checked',false);
                }else if(support.support_tempo === "MEDIUM"){
                    formMachine.formAddMachine1.find('#supportTempoMedium').prop('checked',true);
                    formMachine.formAddMachine1.find('#supportTempoSlow').prop('checked',false);
                    formMachine.formAddMachine1.find('#supportTempoFast').prop('checked',false);
                }else if(support.support_tempo === "FAST"){
                    formMachine.formAddMachine1.find('#supportTempoFast').prop('checked',true);
                    formMachine.formAddMachine1.find('#supportTempoMedium').prop('checked',false);
                    formMachine.formAddMachine1.find('#supportTempoSlow').prop('checked',false);
                }
                //Injection Tab
                formMachine.formAddMachine1.find('[name="inj_tab_fill_time"').val(injectionTab.inj_tab_fill_time);
                formMachine.formAddMachine1.find('[name="inj_tab_plastic_time"').val(injectionTab.inj_tab_plastic_time);
                formMachine.formAddMachine1.find('[name="inj_tab_cycle_time"').val(injectionTab.inj_tab_cycle_time);
                formMachine.formAddMachine1.find('[name="inj_tab_spray"').val(injectionTab.inj_tab_spray);
                formMachine.formAddMachine1.find('[name="inj_tab_spray_tm"').val(injectionTab.inj_tab_spray_tm);
                formMachine.formAddMachine1.find('[name="inj_tab_screw_most_fwd"').val(injectionTab.inj_tab_screw_most_fwd);
                formMachine.formAddMachine1.find('[name="inj_tab_enj_end_pos"').val(injectionTab.inj_tab_enj_end_pos);
                formMachine.formAddMachine1.find('[name="inj_tab_airblow_start_time"').val(injectionTab.inj_tab_airblow_start_time);
                formMachine.formAddMachine1.find('[name="inj_tab_airblow_blow_time"').val(injectionTab.inj_tab_airblow_blow_time);
                formMachine.formAddMachine1.find('[name="inj_tab_punch_applcn"').val(injectionTab.inj_tab_punch_applcn);
                formMachine.formAddMachine1.find('[name="inj_tab_md_temp_requirement"').val(injectionTab.inj_tab_md_temp_requirement);
                formMachine.formAddMachine1.find('[name="inj_tab_md_time_requirement"').val(injectionTab.inj_tab_md_time_requirement);
                formMachine.formAddMachine1.find('[name="inj_tab_md_temp_actual"').val(injectionTab.inj_tab_md_temp_actual);
                if(injectionTab.inj_tab_spray_type === "POWER"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypePower').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeZ').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeRF').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeNO').prop('checked',false);

                }else if(injectionTab.inj_tab_spray_type === "Z"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeZ').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeRF').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeYP').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeNO').prop('checked',false);

                }else if(injectionTab.inj_tab_spray_type === "RF"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeRF').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeYP').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeZ').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeNO').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeNO').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeRF').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeYP').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayTypeZ').prop('checked',false);
                }

                if(injectionTab.inj_tab_spray_mode === "MANUAL"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayModeManual').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayModeAuto').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabSprayModeManual').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayModeAuto').prop('checked',true);
                }

                if(injectionTab.inj_tab_spray_side === "MOVE"){
                    formMachine.formAddMachine1.find('#radioInjTabSpraySideMove').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSpraySideFixed').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabSpraySideMove').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSpraySideFixed').prop('checked',true);
                }

                if(injectionTab.inj_tab_ccd === "YES"){
                    formMachine.formAddMachine1.find('#radioInjTabCcdYes').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabCcdNo').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabCcdYes').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabCcdNo').prop('checked',true);
                }

                if(injectionTab.inj_tab_esc === "YES"){
                    formMachine.formAddMachine1.find('#radioInjTabEscYes').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabEscNo').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabEscYes').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabEscNo').prop('checked',true);
                }

                if(injectionTab.inj_tab_spray_portion === "CENTER ONLY"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayPortionCenter').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayPortionWhole').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabSprayPortionCenter').prop('checked',false);
                    formMachine.formAddMachine1.find('#radioInjTabSprayPortionWhole').prop('checked',true);
                }
                if(injectionTab.inj_tab_punch === "HARD"){
                    formMachine.formAddMachine1.find('#radioInjTabSprayPunchHard').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayPunchSoft').prop('checked',false);
                }else{
                    formMachine.formAddMachine1.find('#radioInjTabSprayPunchSoft').prop('checked',true);
                    formMachine.formAddMachine1.find('#radioInjTabSprayPunchHard').prop('checked',false);
                }
                dataTableMachine.InjectionTabListOne.draw();
                $('#modal-loading').modal('hide');

            },error: function (data, xhr, status){
                toastr.error(`Error: ${data.status}`);
                $('#modal-loading').modal('hide');
            }
        });
    }

    const fnGetOperatorName = function (elementId,dataId = null){
        let result = `<option value="0" selected> N/A </option>`;
        $.ajax({
            type: 'GET',
            url: 'get_operator_name',
            data: {"data_id" : dataId},
            dataType: 'json',
            beforeSend: function(){
                result = `<option value="0" selected disabled> - Loading - </option>`;
                elementId.html(result);
            },
            success: function (response) {
                let columnId = response.id;
                result = '';
                if(columnId.length > 0){
                    for(let index = 0; index < columnId.length; index++){

                        result += `<option value="${columnId[index]}">${response.value[index]}</option>`;
                    }
                }
                else{
                    result = `<option value="0" selected disabled> - No data found - </option>`;
                }
                elementId.html(result);
                if(dataId != null){
                    setTimeout(() => {
                        elementId.val(dataId).trigger('change');
                    }, 100);
                }
            },error: function (data, xhr, status){
                result = `<option value="0" selected disabled> - Reload Again - </option>`;
                elementId.html(result);
                toastr.error(`Error: ${data.status}`);
            }
        });
    }

    const saveInjectionTabList = function() {
        $.ajax({
            type: 'POST',
            url: 'save_injection_tab_list',
            data: formMachine.formInjectionTabList.serialize(),
            dataType: 'json',
            beforeSend: function() {
                $('#modal-loading').modal('show');
            },
            success: function(response) {
                if(response['is_success'] === 'true'){
                    $('#modalAddInjectionTabList').modal('hide');
                    $('#formInjectionTabList')[0].reset();
                    dataTableMachine.InjectionTabListOne.draw();
                    toastr.success('Save Sucessfully');
                }else{
                    toastr.error('Saving Failed');
                }
                $('#modal-loading').modal('hide');

            },
            error: function(data, xhr, status) {
                $('#modal-loading').modal('hide');
                if(data.status === 422){
                    let errors = data.responseJSON.errors ;
                    toastr.error(`Saving Failed, Please fill up all required fields`);
                    errorHandler( errors.inj_tab_list_mo_day,formMachine.formInjectionTabList.find('[name="inj_tab_list_mo_day"]') );
                    errorHandler( errors.inj_tab_list_shot_count,formMachine.formInjectionTabList.find('[name="inj_tab_list_shot_count"]') );
                    errorHandler( errors.inj_tab_list_operator_name,formMachine.formInjectionTabList.find('[name="inj_tab_list_operator_name"]') );
                    errorHandler( errors.inj_tab_list_mat_time_in,formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_time_in"]') );
                    errorHandler( errors.inj_tab_list_prond_time_start,formMachine.formInjectionTabList.find('[name="inj_tab_list_prond_time_start"]') );
                    errorHandler( errors.inj_tab_list_prond_time_end,formMachine.formInjectionTabList.find('[name="inj_tab_list_prond_time_end"]') );
                    errorHandler( errors.inj_tab_list_total_mat_dring_time,formMachine.formInjectionTabList.find('[name="inj_tab_list_total_mat_dring_time"]') );
                    errorHandler( errors.inj_tab_list_mat_lot_num_virgin,formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_lot_num_virgin"]') );
                    errorHandler( errors.inj_tab_list_mat_lot_num_recycle,formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_lot_num_recycle"]') );
                    errorHandler( errors.inj_tab_list_remarks,formMachine.formInjectionTabList.find('[name="inj_tab_list_remarks"]') );
                }else{
                    toastr.error(`Error: ${data.status}`);
                }
            }
        });
    }

    const editInjectionTabList = function (injectionTabListId){
        $("#tblLotNumber tbody").empty();
        $.ajax({
            type: 'GET',
            url: 'edit_injection_tab_list',
            data: {'injection_tab_list_id' : injectionTabListId},
            dataType: 'json',
            success: function (response) {
                if(response.is_success === 'true'){
                    let injectionTabDetails = response.injection_tab_details[0];
                    let injTabListLotNumberDetails = response.inj_tab_list_lot_number_details;
                    for (let index = 0; index < injTabListLotNumberDetails.length; index++) {
                        // const element = array[index];
                        console.log(injTabListLotNumberDetails[index]);
                        let rowLotNumber = `
                            <tr>
                                <td>
                                    <div id="divInjTabLotNumber" class="input-group input-group-sm mb-3">
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-dark"
                                                id="btnScanQrLotNumber" name="btnInjTabLotNumber[]" btn-value="btnInjTabLotNumber"><i
                                                    class="fa fa-qrcode w-100"></i></button>
                                        </div>
                                        <input value = "${injTabListLotNumberDetails[index].lot_number}" type="text" class="form-control form-control-sm"
                                            id="textInjTabLotNumber" name="inj_tab_lot_number[]" required>
                                    </div>
                                </td>
                                <td>
                                    <center><button class="btn btn-danger buttonLotNumber" title="Remove" type="button"><i class="fa fa-times"></i></button></center>
                                </td>
                            </tr>
                            `;
                        $("#tblLotNumber tbody").append(rowLotNumber);
                    }
                    formMachine.formInjectionTabList.find('[name="machine_parameter_id"]').val(injectionTabDetails.machine_parameter_id);
                    formMachine.formInjectionTabList.find('[name="injection_tab_list_id"]').val(injectionTabDetails.id);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_mo_day"]').val(injectionTabDetails.inj_tab_list_mo_day);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_shot_count"]').val(injectionTabDetails.inj_tab_list_shot_count);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_time_in"]').val(injectionTabDetails.inj_tab_list_mat_time_in);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_prond_time_start"]').val(injectionTabDetails.inj_tab_list_prond_time_start);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_prond_time_end"]').val(injectionTabDetails.inj_tab_list_prond_time_end);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_total_mat_dring_time"]').val(injectionTabDetails.inj_tab_list_total_mat_dring_time);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_lot_num_virgin"]').val(injectionTabDetails.inj_tab_list_mat_lot_num_virgin);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_mat_lot_num_recycle"]').val(injectionTabDetails.inj_tab_list_mat_lot_num_recycle);
                    formMachine.formInjectionTabList.find('[name="inj_tab_list_remarks"]').val(injectionTabDetails.inj_tab_list_remarks);
                    fnGetOperatorName(formMachine.formInjectionTabList.find('[name="inj_tab_list_operator_name"]'),injectionTabDetails.inj_tab_list_operator_name);

                }
            },error: function (data, xhr, status){

                toastr.error(`Error: ${data.status}`);
            }
        });
    }

// });//endDocReady
