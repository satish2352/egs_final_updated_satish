<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Gender
};

class GenderRepository{
	public function getAll(){
        try {
            return Gender::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $gender_data = new Gender();
            $gender_data->gender_name = $request['gender_name'];
            $gender_data->is_active = 1;
            $gender_data->is_deleted = 0;
            $gender_data->save();       
                
            return $gender_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $gender = Gender::find($id);
            if ($gender) {
                return $gender;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id Gender.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $gender_data = Gender::find($request->id);
            
            if (!$gender_data) {
                return [
                    'msg' => 'Gender data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $gender_data->gender_name = $request['gender_name'];
            // $gender_data->marathi_title = $request['marathi_title'];
            // $gender_data->url = $request['url'];
            $gender_data->save();        
        
            return [
                'msg' => 'Gender data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update Gender data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $gender = Gender::find($id);
            if ($gender) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/gender-suggestion/'.$gender->english_image,
                    'public/images/citizen-action/gender-suggestion/'.$gender->marathi_image
                ]);

                // Delete the record from the database
                
                $gender->delete();
                
                return $gender;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Gender::find($request); // Assuming $request directly contains the ID

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