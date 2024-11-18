<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpMoldClosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_mold_closes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float('hi_v')->nullable()->default(0);
            $table->float('mid_slow')->nullable()->default(0);
            $table->float('low_v')->nullable()->default(0);
            $table->float('close_monitor_tm')->nullable()->default(0);
            $table->float('slow_start')->nullable()->default(0);
            $table->float('slow_end')->nullable()->default(0);
            $table->float('lvlp')->nullable()->default(0);
            $table->float('hpcl')->nullable()->default(0);
            $table->float('mid_sl_p')->nullable()->default(0);
            $table->float('low_p')->nullable()->default(0);
            $table->float('hi_p')->nullable()->default(0);
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
        Schema::dropIfExists('mp_mold_closes');
    }
}
