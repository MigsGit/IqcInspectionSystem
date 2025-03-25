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
        $model,
        $from_date,
        $to_date,
        $material_category
    );
    public function totalIqcInspectionByDateMaterialGroupBySupplier(
        $model,
        $from_date,
        $to_date,
        $material_category
    );
    public function iqcInspectionRawSheet(
        $model,
        $from_date,
        $to_date,
        $material_category
    );
    public function iqcInspectionByDateMaterialGroupBySheet(
        $model,
        $from_date,
        $to_date,
        $material_category,
        $arr_merge_group
    );


}
