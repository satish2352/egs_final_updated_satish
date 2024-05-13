<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Registrationstatus
};

class RegistrationstatusRepository{
	public function getAll(){
        try {
            return Registrationstatus::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $registrationstatus_data = new Registrationstatus();
            $registrationstatus_data->status_name = $request['status_name'];
            $registrationstatus_data->save();       
                
            return $registrationstatus_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $registrationstatus = Registrationstatus::find($id);
            if ($registrationstatus) {
                return $registrationstatus;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id registrationstatus.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $registrationstatus_data = Registrationstatus::find($request->id);
            
            if (!$registrationstatus_data) {
                return [
                    'msg' => 'registrationstatus data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $registrationstatus_data->registrationstatus = $request['registrationstatus'];
            // $registrationstatus_data->marathi_title = $request['marathi_title'];
            // $registrationstatus_data->url = $request['url'];
            $registrationstatus_data->save();        
        
            return [
                'msg' => 'registrationstatus data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update registrationstatus data.',
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