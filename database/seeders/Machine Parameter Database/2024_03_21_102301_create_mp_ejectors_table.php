<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpEjectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_ejectors', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float("pattern")->nullable()->default(0);
            $table->float("ej_pres")->nullable()->default(0);
            $table->float("fwd_ev1")->nullable()->default(0);
            $table->float("fwd_ev2")->nullable()->default(0);
            $table->float("stop_tm")->nullable()->default(0);
            $table->float("count")->nullable()->default(0);
            $table->float("ejt_tmg")->nullable()->default(0);
            $table->float("ev2_chg")->nullable()->default(0);
            $table->float("fwd_stop")->nullable()->default(0);
            $table->float("bwd_ev4")->nullable()->default(0);
            $table->float("bwd_prs")->nullable()->default(0);
            $table->float("repeat_bwd_stop")->nullable()->default(0);
            $table->float("repeat_ejt_ev3")->nullable()->default(0);
            $table->float("repeat_fwd_stop")->nullable()->default(0);
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
        Schema::dropIfExists('mp_ejectors');
    }
}
