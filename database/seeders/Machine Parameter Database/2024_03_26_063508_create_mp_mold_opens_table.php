<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpMoldOpensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_mold_opens', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float('open_end_v')->nullable()->default(0);
            $table->float('hi_velocity_2_percent')->nullable()->default(0);
            $table->float('hi_velocity_1_percent')->nullable()->default(0);
            $table->float('open_v')->nullable()->default(0);
            $table->float('mold_rotation')->nullable()->default(0);
            $table->float('open_stop')->nullable()->default(0);
            $table->float('low_distance')->nullable()->default(0);
            $table->float('hi_velocity_1mm')->nullable()->default(0);
            $table->float('hi_velocity_2mm')->nullable()->default(0);
            // Defaults
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            // Foreign key
            $table->foreign('machine_parameter_id')->references('id')->on('machine_parameters'); // foreign id sa table, references id sa pagkukunan, on pagkukunan na table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_mold_opens');
    }
}
