<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIqcDropdownDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iqc_dropdown_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iqc_dropdown_categories_id')->references('id')->on('iqc_dropdown_categories')->comment ='id from iqc_dropdown_categories';
            $table->tinyInteger('status')->nullable();
            $table->string('dropdown_details')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('iqc_dropdown_details');
    }
}
