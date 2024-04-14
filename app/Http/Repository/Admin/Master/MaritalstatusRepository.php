<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Maritalstatus
};

class MaritalstatusRepository{
	public function getAll(){
        try {
            return Maritalstatus::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $maritalstatus_data = new Maritalstatus();
            $maritalstatus_data->maritalstatus = $request['maritalstatus'];
            $maritalstatus_data->save();       
                
            return $maritalstatus_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $maritalstatus = Maritalstatus::find($id);
            if ($maritalstatus) {
                return $maritalstatus;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id Incident Type.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $maritalstatus_data = maritalstatus::find($request->id);
            
            if (!$maritalstatus_data) {
                return [
                    'msg' => 'Incident Type data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $maritalstatus_data->maritalstatus = $request['maritalstatus'];
            // $gender_data->marathi_title = $request['marathi_title'];
            // $gender_data->url = $request['url'];
            $maritalstatus_data->save();        
        
            return [
                'msg' => 'Incident Type data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update Incident Type data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $maritalstatus = Maritalstatus::find($id);
            if ($maritalstatus) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/maritalstatus-suggestion/'.$maritalstatus->english_image,
                    'public/images/citizen-action/maritalstatus-suggestion/'.$maritalstatus->marathi_image
                ]);

                // Delete the record from the database
                
                $maritalstatus->delete();
                
                return $maritalstatus;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Maritalstatus::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the Slider model
            if ($slide) {
                $is_active = $slide->is_active === 1 ? 0 : 1;
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