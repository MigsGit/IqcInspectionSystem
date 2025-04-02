<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\IqcDropdownDetail;
use Illuminate\Support\Facades\DB;
use App\Models\IqcDropdownCategory;
use App\Interfaces\ResourceInterface;
use App\Http\Requests\IqcDropdownDetailRequest;
use App\Http\Requests\IqcDropdownCategoryRequest;

class SettingController extends Controller
{
    protected $resourceInterface;
    public function __construct(ResourceInterface $resourceInterface) {
        $this->resourceInterface = $resourceInterface;
    }
    public function readDropdownCategory(){

        $query = $this->resourceInterface->readCustomEloquent(Department::class);
        $get_department_by_id = $query->where('department_id',session('rapidx_department_id'))->get(['department_group']);
        $department = ($get_department_by_id[0]['department_group'] == "PPS" )? "PPD": $get_department_by_id[0]['department_group'];

        $query = $this->resourceInterface->readCustomEloquent(IqcDropdownCategory::class);
        $iqcDropdownCategory = $query->whereNull('deleted_at')->orderBy('dropdown_category')
        ->get();
        if($department != "ISS"){
            $iqcDropdownCategory = $query->whereNull('deleted_at')
            ->where("dropdown_category", "!=","Material Category")
            ->where('section',$department)
            ->get();
        }
        return DataTables::of($iqcDropdownCategory)
        ->addColumn('raw_action', function($row){
            $result = '';
            if(session('rapidx_department_id') === 1){
                $result .= '<center>';
                $result .= "<button class='btn btn-info btn-sm mr-1' dropdown-category-id='".$row->id."' id='btnDropdownCategory'><i class='fa-solid fa-pen-to-square'></i></button>";
                $result .= '</center>';
            }
            $result .= '<input type="hidden" value="' . $row->id . '" class="form-control" id="tdDropdownCategoryId">';
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
        ->addColumn('updated_by', function($row){
            $result = '';
            $result .= ( $row->updated_by != NULL ) ? $row->updated_by : 'N/A';
            return $result;
        })
        ->rawColumns(['raw_action','raw_status'])
        ->make(true);
    }
    public function readDropdownDetailsByCategory(Request $request){
        $iqc_dropdown_category_id = isset($request->iqc_dropdown_category_id) ? $request->iqc_dropdown_category_id : 0;
        $dropdownDetailsByCategory = $this->resourceInterface->readByForeignID(IqcDropdownDetail::class,'iqc_dropdown_categories_id',$iqc_dropdown_category_id);

        return DataTables::of($dropdownDetailsByCategory)
        ->addColumn('raw_action', function($row){
            $result = '';
            $result .= '<center>';
            $result .= "<button class='btn btn-info btn-sm mr-1' dropdown-details-id='".$row->id."' id='btnDropdownCategory'><i class='fa-solid fa-pen-to-square'></i></button>";
            $result .= '</center>';
            return $result;
        })
        ->addColumn('raw_status', function($row){
            $result = '';
            $result .= '<center>';
            $result .= '<span class="badge rounded-pill bg-primary"> Active </span>';
            $result .= '</center>';
            return $result;
        })
        ->addColumn('updated_by', function($row){
            $result = '';
            $result .= ($row->updated_by != NULL) ? $row->updated_by : 'N/A';
            return $result;
        })
        ->rawColumns(['raw_action','raw_status'])
        ->make(true);
    }

    public function readDropdownCategoryById(Request $request){
        try {
            $readDropdownCategoryById = $this->resourceInterface->readByID(IqcDropdownCategory::class,$request->DropdownCategoryId);
            return response()->json(['isSuccess' => 'true','readDropdownCategoryById' => $readDropdownCategoryById]);
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function readDropdownDetailsById(Request $request){
        try {
            $readDropdownDetailsById = $this->resourceInterface->readByID(IqcDropdownDetail::class,$request->DropdownDetailsId);
            return response()->json(['isSuccess' => 'true','readDropdownDetailsById' => $readDropdownDetailsById]);
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function saveDropdownCategoryById(IqcDropdownCategoryRequest $IqcDropdownCategoryRequest){
        $readDropdownCategoryById = $this->resourceInterface->createOrUpdate(IqcDropdownCategory::class,$IqcDropdownCategoryRequest->dropdown_category_id,$IqcDropdownCategoryRequest->validated());
        $arrData = [
            'updated_by' => session('rapidx_user_id'),
            'iqc_inspection_column_ref' => $IqcDropdownCategoryRequest->iqc_inspection_column_ref
        ];
        return $readDropdownCategoryById = $this->resourceInterface->update(IqcDropdownCategory::class,$readDropdownCategoryById['dataId'],$arrData);
    }
    public function saveDropdownDetailsById(IqcDropdownDetailRequest $IqcDropdownDetailRequest)
    {
        // return $IqcDropdownDetailRequest->validated();
        return $readDropdownDetailById = $this->resourceInterface->createOrUpdate(IqcDropdownDetail::class,$IqcDropdownDetailRequest->dropdown_details_id,$IqcDropdownDetailRequest->validated());
    }
}
