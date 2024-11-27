<?php
namespace App\Solid\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Import Interfaces
 */
use App\Solid\Interfaces\YieldDataRepositoryInterface;

/**
 * Import Models
 */
use App\Models\YieldData;

class YieldDataRepository implements YieldDataRepositoryInterface
{
    public function getAll(){
        return YieldData::whereNull('deleted_at')
        ->get();
    }

    public function getAllWithConditions(array $conditions){
        $query = YieldData::query();
        $query->whereNull('deleted_at');
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        return $query->get();
    }

    public function getAllWithRelationsAndConditions(array $relations, array $conditions){
        $query = YieldData::query();
        $query->whereNull('deleted_at');
        $query->with($relations);
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        return $query->get();
    }

    public function getById($id){
        return YieldData::where('id', $id)
        ->whereNull('deleted_at')
        ->get();
    }

    public function insert(array $data){
        date_default_timezone_set('Asia/Manila');

        DB::beginTransaction();
        try {
            $data['created_by'] = Auth::user()->id;
            $data['created_at'] = date('Y-m-d H:i:s');
            $id = YieldData::insertGetId($data);

            DB::commit();
            return response()->json(['hasError' => false, 'id' => $id]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['hasError' => true, 'exceptionError' => $e->getMessage()]);
        }
    }

    public function update($id, array $data){
        date_default_timezone_set('Asia/Manila');

        DB::beginTransaction();
        try {
            $data['last_updated_by'] = Auth::user()->id;
            $data['updated_at']      = date('Y-m-d H:i:s');
            YieldData::where('id', $id)->update($data);

            DB::commit();
            return response()->json(['hasError' => false]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['hasError' => true, 'exceptionError' => $e->getMessage()]);
        }
    }

    public function getYear(){
        // Fetch distinct years based on the created_at date column
        return $years = YieldData::selectRaw('YEAR(production_date) as year')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');
    }
}
