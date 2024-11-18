<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMpInjTabListLotNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mp_inj_tab_list_lot_numbers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mp_injection_tab_list_id')->unsigned();
            $table->string('lot_number')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('mp_injection_tab_list_id')->references('id')->on('mp_injection_tab_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mp_inj_tab_list_lot_numbers');
    }
}
