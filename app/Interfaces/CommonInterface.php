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

}
