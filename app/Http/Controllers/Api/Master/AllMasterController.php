<?php
namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
	Gender,
    Maritalstatus,
    Skills,
    RelationModel,
    Documenttype,
    Registrationstatus,
    Reasons,
    DocumentReasons,
    TblArea
};

class AllMasterController extends Controller
{
    public function getAllMasters(){
        try {
            $data = [];
            $data['gender'] = Gender::where('is_active', true)->orderBy('id', 'asc')->get();
            $data['maritalstatus'] = Maritalstatus::where('is_active', true)->orderBy('id', 'asc')->get();
            $data['skills'] = Skills::where('is_active', true)->orderBy('id', 'asc')->get();
            $data['relation'] = RelationModel::where('is_active', true)->orderBy('id', 'asc')->get();
            $data['documenttype'] = Documenttype::where('is_active', true)->orderBy('id', 'asc')->get();
            $data['registrationstatus'] = Registrationstatus::where('is_active', true)
            ->whereNotIn('id', [1])
            ->orderBy('id', 'asc')->get();
            $data['reasons'] = Reasons::where('is_active', true)->where('is_deleted', false)->orderBy('id', 'asc')->get();
            $data['documentreasons'] = DocumentReasons::where('is_active', true)->where('is_deleted', false)->orderBy('id', 'asc')->get();
            // dd($data);
            return response()->json(['status' => 'success', 'message' => 'All data retrieved successfully', 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Data not found', 'error' => $e->getMessage()], 500);
        }
    }
    public function getAllMastersUpdated(Request $request){
        try {
            // $user = auth()->user()->id;
            // $data_output = TblArea::where(['is_new'=> 1])->orderBy('id', 'asc')->get();
            $areas =  TblArea::where(['is_new'=> '1'])->get();

            $areas = $areas->map(function ($data) {
                return [
                    'id' => (string) $data->id,
                    'is_visible' => (string) $data->is_visible,
                    'location_id' => (string) $data->location_id,
                    'location_type' => (string) $data->location_type,
                    'name' => $data->name,
                    'parent_id' => (string) $data->parent_id,
                    'is_active' => $data->is_active ? "1" : "0",
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'is_new' => (string) $data->is_new,
                ];
            })->toArray();
    
            return response()->json(['status' => 'true', 'message' => 'All new data retrieved successfully', 'data' => $areas], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Data not found', 'error' => $e->getMessage()], 500);
        }
    }
}


