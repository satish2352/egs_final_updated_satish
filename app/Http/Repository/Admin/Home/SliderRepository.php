<?php
namespace App\Http\Repository\Admin\Home;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Slider
};
use Config;

class SliderRepository  {
	public function getAll(){
        try {
            return Slider::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addAll($request){
        try {
            $data =array();
            $slides = new Slider();
            $slides->english_title = $request['english_title'];
            $slides->marathi_title = $request['marathi_title'];
            $slides->english_description = $request['english_description'];
            $slides->marathi_description = $request['marathi_description'];
            $slides->url = $request['url'];
            // $slides->english_scrolltime = $request['english_scrolltime'];
        
            $slides->save(); 
            $last_insert_id = $slides->id;

            $englishImageName = $last_insert_id .'_' . rand(100000, 999999) . '_english.' . $request->english_image->extension();
            $marathiImageName = $last_insert_id .'_' . rand(100000, 999999) . '_marathi.' . $request->marathi_image->extension();
            
            $slide = Slider::find($last_insert_id); // Assuming $request directly contains the ID
            $slide->english_image = $englishImageName; // Save the image filename to the database
            $slide->marathi_image = $marathiImageName; // Save the image filename to the database
            $slide->save();
            
            $data['englishImageName'] =$englishImageName;
            $data['marathiImageName'] =$marathiImageName;
            return $data;

        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    public function getById($id){
        try {
            $slider = Slider::find($id);
            if ($slider) {
                return $slider;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by id slide.',
                'status' => 'error'
            ];
        }
    }
    
    public function updateAll($request){
        try {
            $return_data = array();
            $slide_data = Slider::find($request->id);

            if (!$slide_data) {
                return [
                    'msg' => 'Report Incident Crowdsourcing not found.',
                    'status' => 'error'
                ];
            }

            // Store the previous image names
            $previousEnglishImage = $slide_data->english_image;
            $previousMarathiImage = $slide_data->marathi_image;

            // Update the fields from the request
            $slide_data->english_title = $request['english_title'];
            $slide_data->marathi_title = $request['marathi_title'];
            $slide_data->english_description = $request['english_description'];
            $slide_data->marathi_description = $request['marathi_description'];
            $slide_data->url = $request['url'];
            
            $slide_data->save();
            $last_insert_id = $slide_data->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['english_image'] = $previousEnglishImage;
            $return_data['marathi_image'] = $previousMarathiImage;
            return  $return_data;
        
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Report Incident Crowdsourcing.',
                'status' => 'error',
                'error' => $e->getMessage() // Return the error message for debugging purposes
            ];
        }
    }

    public function updateOne($request){
        try {
            $slide = Slider::find($request); // Assuming $request directly contains the ID

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

    public function deleteById($id){
            try {
                $slider = Slider::find($id);
                if ($slider) {
                    if (file_exists_s3(Config::get('DocumentConstant.SLIDER_DELETE') . $slider->english_image)){
                        removeImage(Config::get('DocumentConstant.SLIDER_DELETE') . $slider->english_image);
                    }
                    if (file_exists_s3(Config::get('DocumentConstant.SLIDER_DELETE') . $slider->marathi_image)){
                        removeImage(Config::get('DocumentConstant.SLIDER_DELETE') . $slider->marathi_image);
                    }
                    $slider->delete();
                    
                    return $slider;
                } else {
                    return null;
                }
            } catch (\Exception $e) {
                return $e;
            }
    }


}