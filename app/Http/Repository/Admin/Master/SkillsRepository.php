<?php
namespace App\Http\Repository\Admin\Master;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Skills
};

class SkillsRepository{
	public function getAll(){
        try {
            $skills = Skills::orderBy('id', 'asc')->get();
            return $skills;
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $skills_data = new Skills();
            $skills_data->skills = $request['skills'];
            $skills_data->save();       
                
            return $skills_data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }
    public function getById($id){
        try {
            $skills = Skills::find($id);
            if ($skills) {
                return $skills;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by Id Skills.',
                'status' => 'error'
            ];
        }
    }
    public function updateAll($request){
        try {
            $skills_data = Skills::find($request->id);
            
            if (!$skills_data) {
                return [
                    'msg' => 'Skills data not found.',
                    'status' => 'error'
                ];
            }
        // Store the previous image names
            $skills_data->skills = $request['skills'];
            // $skills_data->marathi_title = $request['marathi_title'];
            // $skills_data->url = $request['url'];
            $skills_data->save();        
        
            return [
                'msg' => 'Skills data updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update Skills data.',
                'status' => 'error'
            ];
        }
    }

    public function deleteById($id) {
        try {
            $skills = Skills::find($id);
            if ($skills) {
                // Delete the images from the storage folder
                Storage::delete([
                    'public/images/citizen-action/skills-suggestion/'.$skills->english_image,
                    'public/images/citizen-action/skills-suggestion/'.$skills->marathi_image
                ]);

                // Delete the record from the database
                
                $skills->delete();
                
                return $skills;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function updateOne($request){
        try {
            $slide = Skills::find($request); // Assuming $request directly contains the ID

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