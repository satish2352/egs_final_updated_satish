<?php
namespace App\Http\Services\Admin\Labours;

use App\Http\Repository\Admin\Labours\LabourRepository;


use App\Models\
{ 
    User,
    Labour 
};
use Carbon\Carbon;
use Config;
use Storage;

class LabourServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct() {
        $this->repo = new LabourRepository();
    }

    public function index() {
        $data_labours = $this->repo->getLaboursList();
        return $data_labours;
    }

    public function listApprovedLabours() {
        $data_labours = $this->repo->getApprovedLaboursList();
        return $data_labours;
    }

    public function listTodayApprovedLabours() {
        $data_labours = $this->repo->getTodaysApprovedLaboursList();
        return $data_labours;
    }

    public function listDisapprovedLabours() {
        $data_labours = $this->repo->getDisapprovedLaboursList();
        return $data_labours;
    }

    public function listResubmitedLabours() {
        $data_labours = $this->repo->getResubmitedLaboursList();
        return $data_labours;
    }

    public function getLabourAttendanceList() {
        $data_labours = $this->repo->getLabourAttendanceList();
        // dd($data_users);
        return $data_labours;
    }

    public function addProject($request)
    {
        try {
            $add_role = $this->repo->addProject($request);
            if ($add_role) {
                return ['status' => 'success', 'msg' => 'Role Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Role Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }

    // public function register($request) {
    //    // $academicYear = 1;
    //     $chk_dup = $this->repo->checkDupCredentials($request);
    //     if(sizeof($chk_dup)>0)
    //     {
    //         return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
    //     }
    //     else
    //     {
    //         $user_register_id = $this->repo->register($request);
    //         return ['status'=>'success','msg'=>'User Data Added Successful.'];
    //     }
    // }

    // public function register($request){
    //     try {

    //         $chk_dup = $this->repo->checkDupCredentials($request);
    //         if(sizeof($chk_dup)>0)
    //         {
    //             return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
    //         }
    //         else
    //         {
    //             $last_id = $this->repo->register($request);
    //             $path = Config::get('DocumentConstant.USER_PROFILE_ADD');
                
    //             //"\all_web_data\images\home\slides\\"."\\";
    //             $imageProfile = $last_id['imageProfile'];
              
    //             // $userProfile = $last_id . '_english.' . $request->user_profile->extension();
    //             uploadImage($request, 'user_profile', $path, $imageProfile);
    //             // dd($imageProfile);
    //             // die();
    //             if ($last_id) {
    //                 return ['status' => 'success', 'msg' => 'User Added Successfully.'];
    //             } else {
    //                 return ['status' => 'error', 'msg' => 'User get Not Added.'];
    //             }  
    //         }

    //     } catch (Exception $e) {
    //         return ['status' => 'error', 'msg' => $e->getMessage()];
    //         }      
    // }

    public function update($request) {
            $user_register_id = $this->repo->update($request);
            return ['status'=>'success','msg'=>'Data Updated Successful.'];
    }    

    public function updateLabourStatus($request) {
        $user_register_id = $this->repo->updateLabourStatus($request);
        return ['status'=>'success','msg'=>'Data Updated Successful.'];
} 

    public function editProjects($request) {
        $data_projects = $this->repo->editProjects($request);
        return $data_projects;
    }
    

    public function delete($id){
        try {
            $delete = $this->repo->delete($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
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
    public function getProfile($request) {
        $data_users = $this->repo->getProfile($request);
        return $data_users;
    }



    public function updateAll($request){
        try {
            
            $return_data = $this->repo->updateProfile($request);
            
            $path = Config::get('DocumentConstant.USER_PROFILE_ADD');
            if ($request->hasFile('user_profile')) {
                if ($return_data['user_profile']) {
                    if (file_exists_s3(Config::get('DocumentConstant.USER_PROFILE_DELETE') . $return_data['user_profile'])) {
                        removeImage(Config::get('DocumentConstant.USER_PROFILE_DELETE') . $return_data['user_profile']);
                    }

                }
    
                $englishImageName = $return_data['last_insert_id'] .'_' . rand(100000, 999999) . '_english.' . $request->user_profile->extension();
                uploadImage($request, 'user_profile', $path, $englishImageName);
                $slide_data = User::find($return_data['last_insert_id']);
                $slide_data->user_profile = $englishImageName;
                $slide_data->save();
            }
    
            
            if ($return_data) {
                return ['status' => 'success', 'msg' => 'User Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'User  Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }

    public function updateProfile($request) {
        
        try {
        $return_data = $this->repo->updateProfile($request);
        $path = Config::get('DocumentConstant.USER_PROFILE_ADD');
        if ($request->hasFile('user_profile')) {
            if ($return_data['user_profile']) {
                if (file_exists_s3(Config::get('DocumentConstant.USER_PROFILE_DELETE') . $return_data['user_profile'])) {
                    removeImage(Config::get('DocumentConstant.USER_PROFILE_DELETE') . $return_data['user_profile']);
                }

            }

            $englishImageName = $return_data['last_insert_id'] . '_english.' . $request->user_profile->extension();
            uploadImage($request, 'user_profile', $path, $englishImageName);
            $profile = User::find($return_data['last_insert_id']);
            $profile->user_profile = $englishImageName;
            $profile->save();
        }

        if((isset($return_data['password_change']) && ($return_data['password_change'] =='yes')) || (isset($return_data['mobile_change']) && $return_data['mobile_change'] =='yes')) {
            return $return_data;
        } else {
            return ['status' => 'success', 'msg' => 'Profile data saved successfully'];
        }
       

    } catch (\Exception $e) {
        return ['status' => 'error', 'msg' => $e->getMessage()];
    }  
    }
    
    public function updateOne($id){
        return $this->repo->updateOne($id);
    }

    public function getGramsevakList() {
        $data_gramsevaks = $this->repo->getGramsevakList();
        return $data_gramsevaks;
    }

    public function showGramsevakDocuments($id){
        try {
            return $this->repo->showGramsevakDocuments($id);
        } catch (\Exception $e) {
            return $e;
        }
    }


}