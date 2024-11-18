<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpHeatersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_heaters', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->float('nozzle_set')->nullable()->default(0);
            $table->float('front_set')->nullable()->default(0);
            $table->float('mid_set')->nullable()->default(0);
            $table->float('rear_set')->nullable()->default(0);
            $table->float('nozzle_actual')->nullable()->default(0);
            $table->float('front_actual')->nullable()->default(0);
            $table->float('mid_actual')->nullable()->default(0);
            $table->float('rear_actual')->nullable()->default(0);
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
        Schema::dropIfExists('mp_heaters');
    }
}
