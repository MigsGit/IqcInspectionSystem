<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpInjectionVelocitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_injection_velocities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float('injection_time')->nullable()->default(0);
            $table->float('cooling_time')->nullable()->default(0);
            $table->float('cycle_start')->nullable()->default(0);
            $table->float('inj_v6')->nullable()->default(0);
            $table->float('inj_v5')->nullable()->default(0);
            $table->float('inj_v4')->nullable()->default(0);
            $table->float('inj_v3')->nullable()->default(0);
            $table->float('inj_v2')->nullable()->default(0);
            $table->float('inj_v1')->nullable()->default(0);
            $table->float('inj_fill')->nullable()->default(0);
            $table->float('inj_sv5')->nullable()->default(0);
            $table->float('inj_sv4')->nullable()->default(0);
            $table->float('inj_sv3')->nullable()->default(0);
            $table->float('inj_sv2')->nullable()->default(0);
            $table->float('inj_sv1')->nullable()->default(0);
            $table->float('inj_sm')->nullable()->default(0);
            $table->float('inj_sd')->nullable()->default(0);
            $table->float('inj_pp3')->nullable()->default(0);
            $table->float('inj_pp2')->nullable()->default(0);
            $table->float('inj_pp1')->nullable()->default(0);
            $table->float('inj_hold')->nullable()->default(0);
            $table->float('inj_tp2')->nullable()->default(0);
            $table->float('inj_tp1')->nullable()->default(0);
            $table->float('inj_pos_change_mode')->nullable()->default(0);
            $table->float('inj_pos_vs')->nullable()->default(0);
            $table->float('inj_pos_bp')->nullable()->default(0);
            $table->float('inj_pv3')->nullable()->default(0);
            $table->float('inj_pv2')->nullable()->default(0);
            $table->float('inj_pv1')->nullable()->default(0);
            $table->float('inj_sp2')->nullable()->default(0);
            $table->float('inj_sp1')->nullable()->default(0);
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
        Schema::dropIfExists('mp_injection_velocities');
    }
}
