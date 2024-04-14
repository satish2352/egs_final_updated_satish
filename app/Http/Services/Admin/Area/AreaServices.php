<?php
namespace App\Http\Services\Admin\Area;

use App\Http\Repository\Admin\Area\AreaRepository;


use App\Models\
{ User };
use Carbon\Carbon;
use Config;
use Storage;

class AreaServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct() {
        $this->repo = new AreaRepository();
    }

    public function index() {
        $data_district = $this->repo->getUsersList();
        // dd($data_users);
        return $data_district;
    }

    public function addDistrictInsert($request){
        try {

            $chk_dup = $this->repo->checkDupCredentials($request);
            if(sizeof($chk_dup)>0)
            {
                return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
            }
            else
            {
                $last_id = $this->repo->addDistrictInsert($request);
                if ($last_id) {
                    return ['status' => 'success', 'msg' => 'User Added Successfully.'];
                } else {
                    return ['status' => 'error', 'msg' => 'User get Not Added.'];
                }  
            }

        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
            }      
    }

    public function editDistrict($request) {
        $data_district = $this->repo->editDistrict($request);
        return $data_district;
    }

    public function updateDistrict($request) {
        $user_register_id = $this->repo->updateDistrict($request);
        return ['status'=>'success','msg'=>'Data Updated Successful.'];
}  

public function deleteDistrict($id){
    try {
        $delete = $this->repo->deleteDistrict($id);
        if ($delete) {
            return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
        } else {
            return ['status' => 'error', 'msg' => ' Not Deleted.'];
        }  
    } catch (Exception $e) {
        return ['status' => 'error', 'msg' => $e->getMessage()];
    } 
}

    public function updateOneDistrict($id){
        return $this->repo->updateOneDistrict($id);
    }

    public function getTalukaList() {
        $data_taluka = $this->repo->getTalukaList();
        // dd($data_users);
        return $data_taluka;
    }

    public function addTalukaInsert($request){
        try {

            $chk_dup = $this->repo->checkDupCredentials($request);
            if(sizeof($chk_dup)>0)
            {
                return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
            }
            else
            {
                $last_id = $this->repo->addTalukaInsert($request);
                if ($last_id) {
                    return ['status' => 'success', 'msg' => 'User Added Successfully.'];
                } else {
                    return ['status' => 'error', 'msg' => 'User get Not Added.'];
                }  
            }

        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
            }      
    }

    public function editTaluka($request) {
        $data_users = $this->repo->editTaluka($request);
        return $data_users;
    }

    public function updateTaluka($request) {
        $user_register_id = $this->repo->updateTaluka($request);
        return ['status'=>'success','msg'=>'Data Updated Successful.'];
    }

    public function deleteTaluka($id){
        try {
            $delete = $this->repo->deleteTaluka($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }

    public function updateOneTaluka($id){
        return $this->repo->updateOneTaluka($id);
    }

    public function getVillageList() {
        $data_vilage = $this->repo->getVillageList();
        // dd($data_users);
        return $data_vilage;
    }

    public function addVillageInsert($request){
        try {

            $chk_dup = $this->repo->checkDupCredentials($request);
            if(sizeof($chk_dup)>0)
            {
                return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
            }
            else
            {
                $last_id = $this->repo->addVillageInsert($request);
                if ($last_id) {
                    return ['status' => 'success', 'msg' => 'User Added Successfully.'];
                } else {
                    return ['status' => 'error', 'msg' => 'User get Not Added.'];
                }  
            }

        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
            }      
    }

    public function updateOneVillage($id){
        return $this->repo->updateOneVillage($id);
    }

    public function editVillage($request) {
        $data_users = $this->repo->editVillage($request);
        return $data_users;
    }

    public function updateVillage($request) {
        $user_register_id = $this->repo->updateVillage($request);
        return ['status'=>'success','msg'=>'Data Updated Successful.'];
    }

    public function deleteVillage($id){
        try {
            $delete = $this->repo->deleteVillage($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }

    public function update($request) {
            $user_register_id = $this->repo->update($request);
            return ['status'=>'success','msg'=>'Data Updated Successful.'];
    }    

    public function editUsers($request) {
        $data_users = $this->repo->editUsers($request);
        return $data_users;
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
    //  public function verifyOtp($otp){
    //     $user = User::where('otp_number', $otp)->first();
    //     if ($user) {
    //         // Clear OTP after successful verification
    //         $user->otp_number = null;
    //         $user->save();

    //         return true; // Valid OTP
    //     }

    //     return false; // Invalid OTP
    // }

}