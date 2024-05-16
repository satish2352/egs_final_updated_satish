<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Usertype
};

class UsertypeRepository{
	public function getAll(){
        try {
            $usertypes = Usertype::orderBy('id', 'asc')->get();
            return $usertypes;
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $usertype_data = new Usertype();
            $usertype_data->usertype_name = $request['usertype_name'];
            $usertype_data->save();       
                
            return $usertype_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $usertype = usertype::find($id);
            if ($usertype) {
                return $usertype;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id usertype.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $usertype_data = Usertype::find($request->id);
            
            if (!$usertype_data) {
                return [
                    'msg' => 'User type data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $usertype_data->usertype_name = $request['usertype_name'];
            // $usertype_data->marathi_title = $request['marathi_title'];
            // $usertype_data->url = $request['url'];
            $usertype_data->save();        
        
            return [
                'msg' => 'User type data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update usertype data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $usertype = Usertype::find($id);
            if ($usertype) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/usertype-suggestion/'.$usertype->english_image,
                    'public/images/citizen-action/usertype-suggestion/'.$usertype->marathi_image
                ]);

                // Delete the record from the database
                
                $usertype->delete();
                
                return $usertype;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Usertype::find($request); // Assuming $request directly contains the ID

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