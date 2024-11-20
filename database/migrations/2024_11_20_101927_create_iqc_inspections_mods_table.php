<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIqcInspectionsModsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iqc_inspections_mods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iqc_inspection_id')->references('id')->on('ts_iqc_inspections')->comment ='id from ts_iqc_inspections';
            $table->string('lot_no')->nullable();
            $table->string('mode_of_defects')->nullable();
            $table->integer('quantity')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iqc_inspections_mods');
    }
}
