<?php

namespace App\Interfaces;

interface CommonInterface
{
    /**
     * Create a interface
     *
     * @return void
     */
    public function generateControlNumber($model,$categoryMaterial);
    public function readIqcInspectionByMaterialCategory($model,$categoryMaterial);
    public function getIqcInspectionShift();
    public function iqcInspectionByDateMaterialGroupBySupplierChart(
        $from_date,
        $to_date,
        $material_category
    );
    public function totalIqcInspectionByDateMaterialGroupBySupplier(
        $from_date,
        $to_date,
        $material_category
    );


}
