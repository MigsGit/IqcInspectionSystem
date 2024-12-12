<?php

namespace App\Jobs;
use Illuminate\Support\Facades\DB;
//Interface
use App\Interfaces\ResourceInterface;

class ResourceJob implements ResourceInterface
{
    /**
     * Execute the job.
     *
     * @return mixed $model,array $data
     */
    public function create($model,array $data){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $dataId = $model::insertGetId($data);
            DB::commit();
            return response()->json(['isSuccess' => 'true','dataId'=>$dataId]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function createOrUpdate( $model,$dataId,array $data){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            if( isset( $dataId ) ){
                // return $model;
                $model::where('id',$dataId)->update($data);
                $dataId = $dataId;
            }else{
                $insert_by_id = $model::insertGetId($data);
                $dataId = $insert_by_id;
            }
            DB::commit();
            return ['isSuccess' => 'true','dataId'=>$dataId];
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function readCustomEloquent($model){
        try {
            return $data = $model::query();
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }

    public function readByID($model,$id){
        try {
            return $data = $model::where('id',$id)->whereNull('deleted_at')->get();
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function readAllWithConditions($model,$conditions){
        try {
            $query = $model::query();
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }
            return $query;
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function readByForeignID($model,$readByForeignID,$id){
        try {
            return $data = $model::where($readByForeignID,$id)->whereNull('deleted_at')->get();
            // return response()->json(['isSuccess' => 'true','data'=> $data]);
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }


    public function readActiveDataWithConditions($model,array $conditions){
        try {
            $query = $model::query();
            $query->whereNull('deleted_at');
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }
            return $query->get();
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }

    public function readAllRelationsAndConditions($model,$relations,$conditions=null){
        try {
            // return $data = $model::with($relations)->get();
            $query = $model::query();
            $query->whereNull('deleted_at');
            $query->with($relations);
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }
            return $query->get();
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }
    public function readAllClosureRelationsAndConditions($model,$relations,$conditions=null){
        try {
            // return $data = $model::with($relations)->get();
            // $query = $model::query();
            // $query->whereNull('deleted_at');
            // $query->with($relations);
            // foreach ($conditions as $key => $value) {
            //     $query->where($key, $value);
            // }
            $query = $model::query();
            $query->whereNull('deleted_at');
            $query->with($relations);
            foreach ($conditions as $key => $value) {
                $query->where($key, $value);
            }
            // $oqc_inspections = AssemblyOqcLotAppSummary::with([
            //     'oqc_lot_app_summ.user',
            //     'oqc_lot_app_summ' => function ($query) use ($request){
            //         return $query ->where('assy_fvi_id', $request->assy_fvi_id);
            //     }
            // ])->orderBy('submission','asc')->get();
            return $query->get();
        } catch (Exception $e) {
            return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
        }
    }

    public function update($model,$dataId,array $data){
        date_default_timezone_set('Asia/Manila');
        DB::beginTransaction();
        try {
            $model::where('id',$dataId)->update($data);
            DB::commit();
            return response()->json(['isSuccess' => 'true','dataId'=>$dataId]);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // public function inactive(Request $request){
    //     return 'true' ;
    //     try {
    //         return response()->json(['isSuccess' => 'true']);
    //     } catch (Exception $e) {
    //         return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
    //     }
    // }
    // public function delete(Request $request){
    //     return 'true' ;
    //     try {
    //         return response()->json(['isSuccess' => 'true']);
    //     } catch (Exception $e) {
    //         return response()->json(['isSuccess' => 'false', 'exceptionError' => $e->getMessage()]);
    //     }
    // }
}
