<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpInjectionTabsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_injection_tabs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float('inj_tab_plastic_time')->nullable()->default(0);
            $table->float('inj_tab_fill_time')->nullable()->default(0);
            $table->float('inj_tab_cycle_time')->nullable()->default(0);
            $table->string('inj_tab_spray_type')->nullable();
            $table->float('inj_tab_spray')->nullable()->default(0);
            $table->string('inj_tab_spray_mode')->nullable();
            $table->string('inj_tab_spray_side')->nullable();
            $table->float('inj_tab_spray_tm')->nullable()->default(0);
            $table->float('inj_tab_screw_most_fwd')->nullable()->default(0);
            $table->float('inj_tab_enj_end_pos')->nullable()->default(0);
            $table->float('inj_tab_airblow_start_time')->nullable()->default(0);
            $table->float('inj_tab_airblow_blow_time')->nullable()->default(0);
            $table->string('inj_tab_ccd')->nullable();
            $table->string('inj_tab_esc')->nullable();
            $table->string('inj_tab_punch')->nullable();
            $table->string('inj_tab_spray_portion')->nullable();
            $table->float('inj_tab_punch_applcn')->nullable()->default(0);
            $table->float('inj_tab_md_temp_requirement')->nullable()->default(0);
            $table->float('inj_tab_md_time_requirement')->nullable()->default(0);
            $table->float('inj_tab_md_temp_actual')->nullable()->default(0);
            // Defaults
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            // Foreign key
            $table->foreign('machine_parameter_id')->references('id')->on('machine_parameters'); // foreign id sa table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_injection_tabs');
    }
}
