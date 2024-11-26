<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\IqcDropdownCategory;
use App\Interfaces\ResourceInterface;

class SettingController extends Controller
{
    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface) {
        $this->resourceInterface = $resourceInterface;
    }
    public function readDropdownDetailsByCategory(){
        // return $this->resourceInterface->readAllRelationsAndConditions(IqcDropdownCategory::class,'iqc_dropdown_details');
        $iqcDropdownCategory = $this->resourceInterface->read(IqcDropdownCategory::class);


        return DataTables::of($iqcDropdownCategory)
        ->addColumn('raw_action', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1' style='display: none;' dropdownCategoryId='".$row->id."'id='btnDropdownCategory'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            $result .= '<input type="hidden" value="' . $row->dropdown_category . '" class="form-control" id="tdDropdownCategory">';

            return $result;
        })
        ->addColumn('raw_status', function($row){
            $result = '';
            $result .= '<center>';
            $result .= '<span class="badge rounded-pill bg-primary"> Active </span>';
            $result .= '</center>';
            return $result;
        })
        ->rawColumns(['raw_action','raw_status'])
        ->make(true);
    }
}
