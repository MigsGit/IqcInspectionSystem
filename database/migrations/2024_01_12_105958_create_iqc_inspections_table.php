<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIqcInspectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_iqc_inspections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('whs_transaction_id')->comment = "Reference to rapid/db_pps/tbl_WarehouseTransaction pkid";
            $table->bigInteger('receiving_detail_id');
            $table->string('invoice_no')->nullable();
            $table->string('partcode')->nullable();
            $table->string('partname')->nullable();
            $table->string('supplier')->nullable();
            $table->tinyInteger('family')->nullable();
            $table->string('app_no')->nullable();
            $table->string('app_no_extension')->nullable();
            $table->tinyInteger('die_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->integer('total_lot_qty')->nullable();
            $table->tinyInteger('classification')->nullable();
            $table->tinyInteger('type_of_inspection')->nullable();
            $table->tinyInteger('severity_of_inspection')->nullable();
            $table->tinyInteger('inspection_lvl')->nullable();
            $table->float('aql')->nullable();
            $table->tinyInteger('accept')->nullable();
            $table->tinyInteger('reject')->nullable();
            $table->tinyInteger('shift')->nullable();
            $table->date('date_inspected')->nullable();
            $table->time('time_ins_from')->nullable();
            $table->time('time_ins_to')->nullable();
            $table->string('inspector')->nullable();
            $table->tinyInteger('submission')->nullable();
            $table->tinyInteger('category')->nullable();
            $table->float('target_lar')->nullable();
            $table->float('target_dppm')->nullable();
            $table->integer('sampling_size')->nullable();
            $table->tinyInteger('lot_inspected')->nullable();
            $table->tinyInteger('accepted')->nullable();
            $table->integer('no_of_defects')->nullable();
            $table->tinyInteger('judgement')->nullable()->comment = "1-Accept | 2-Reject";
            $table->longText('remarks')->nullable();
            $table->string('iqc_coc_file')->nullable();
            $table->longText('iqc_coc_file_name')->nullable();
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
        Schema::dropIfExists('ts_iqc_inspections');
    }
}
