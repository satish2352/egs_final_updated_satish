<?php
namespace App\Http\Services\Admin\Home;

use App\Http\Repository\Admin\Home\SliderRepository;

use App\Models\
{ Slider };
use Carbon\Carbon;
use Config;
use Storage;

class SliderServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new SliderRepository();
    }
    
    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request){
        try {
            $last_id = $this->repo->addAll($request);
            $path = Config::get('DocumentConstant.SLIDER_ADD');
            $englishImageName = $last_id['englishImageName'];
            $marathiImageName = $last_id['marathiImageName'];
            uploadImage($request, 'english_image', $path, $englishImageName);
            uploadImage($request, 'marathi_image', $path, $marathiImageName);

            if ($last_id) {
                return ['status' => 'success', 'msg' => 'Slide Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Slide get Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    
    public function getById($id){
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateAll($request){
        try {
            $return_data = $this->repo->updateAll($request);
            
            $path = Config::get('DocumentConstant.SLIDER_ADD');
            if ($request->hasFile('english_image')) {
                if ($return_data['english_image']) {
                    if (file_exists_s3(Config::get('DocumentConstant.SLIDER_DELETE') . $return_data['english_image'])) {
                        removeImage(Config::get('DocumentConstant.SLIDER_DELETE') . $return_data['english_image']);
                    }

                }
                $englishImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_english.' . $request->english_image->extension();
                uploadImage($request, 'english_image', $path, $englishImageName);
                $slide_data = Slider::find($return_data['last_insert_id']);
                $slide_data->english_image = $englishImageName;
                $slide_data->save();
            }
    
            if ($request->hasFile('marathi_image')) {
                if ($return_data['marathi_image']) {
                    if (file_exists_s3(Config::get('DocumentConstant.SLIDER_DELETE') . $return_data['marathi_image'])) {
                        removeImage(Config::get('DocumentConstant.SLIDER_DELETE') . $return_data['marathi_image']);
                    }
                }
                $marathiImageName = $return_data['last_insert_id'] . '_' . rand(100000, 999999) . '_marathi.' . $request->marathi_image->extension();
                uploadImage($request, 'marathi_image', $path, $marathiImageName);
                $slide_data = Slider::find($return_data['last_insert_id']);
                $slide_data->marathi_image = $marathiImageName;
                $slide_data->save();
            }
            
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'Slide Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Slide  Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }

    public function updateOne($id){
        return $this->repo->updateOne($id);
    }

   
    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }



}