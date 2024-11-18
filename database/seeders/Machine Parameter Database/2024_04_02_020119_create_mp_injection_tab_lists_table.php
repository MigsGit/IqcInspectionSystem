<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpInjectionTabListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_injection_tab_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('machine_parameter_id')->unsigned();
            $table->string('inj_tab_list_mo_day')->nullable();
            $table->bigInteger('inj_tab_list_operator_name')->unsigned();
            $table->float('inj_tab_list_shot_count')->nullable()->default(0);
            $table->float('inj_tab_list_mat_time_in')->nullable()->default(0);
            $table->string('inj_tab_list_prond_time_start')->nullable();
            $table->string('inj_tab_list_prond_time_end')->nullable();
            $table->float('inj_tab_list_mat_lot_num_virgin')->nullable()->default(0);
            $table->float('inj_tab_list_mat_lot_num_recycle')->nullable()->default(0);
            $table->float('inj_tab_list_total_mat_dring_time')->nullable()->default(0);
            $table->string('inj_tab_list_remarks' )->nullable();
            // Defaults
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            // Foreign key
            $table->foreign('machine_parameter_id')->references('id')->on('machine_parameters');
            $table->foreign('inj_tab_list_operator_name')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_injection_tab_lists');
    }
}
