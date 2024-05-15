<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Reasons
};

class RejectionReasonsRepository{
	public function getAll(){
        try {
            return Reasons::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $rejectionreasons_data = new Reasons();
            $rejectionreasons_data->reason_name = $request['reason_name'];
            $rejectionreasons_data->save();       
                
            return $rejectionreasons_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $rejectionreasons = Reasons::find($id);
            if ($rejectionreasons) {
                return $rejectionreasons;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id Rejection Reasons.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $rejectionreasons_data = Reasons::find($request->id);
            
            if (!$rejectionreasons_data) {
                return [
                    'msg' => 'Rejection Reasons data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $rejectionreasons_data->reason_name = $request['reason_name'];
            // $rejectionreasons_data->marathi_title = $request['marathi_title'];
            // $rejectionreasons_data->url = $request['url'];
            $rejectionreasons_data->save();        
        
            return [
                'msg' => 'Rejection Reasons data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update Rejection Reasons.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $registrationstatus = Registrationstatus::find($id);
            if ($registrationstatus) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/registrationstatus-suggestion/'.$registrationstatus->english_image,
                    'public/images/citizen-action/registrationstatus-suggestion/'.$registrationstatus->marathi_image
                ]);

                // Delete the record from the database
                
                $registrationstatus->delete();
                
                return $registrationstatus;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Registrationstatus::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the Slider model
            if ($slide) {
                $is_active = $slide->is_active == 1 ? 0 : 1;
                $slide->is_active = $is_active;
                $slide->save();

                return [
                    'msg' => 'Slide updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Slide not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update slide.',
                'status' => 'error'
            ];
        }
    }
    
       
}