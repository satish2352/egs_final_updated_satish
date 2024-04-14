<?php
namespace App\Http\Services\Admin\LoginRegister;

use App\Http\Repository\Admin\LoginRegister\RegisterRepository;


use App\Models\
{ User };
use Carbon\Carbon;
use Config;
use Storage;

class RegisterServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct() {
        $this->repo = new RegisterRepository();
    }

    public function index() {
        $data_users = $this->repo->getUsersList();
        // dd($data_users);
        return $data_users;
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

    public function register($request){
        try {

            $chk_dup = $this->repo->checkDupCredentials($request);
            if(sizeof($chk_dup)>0)
            {
                return ['status'=>'failed','msg'=>'Registration Failed. The name has already been taken.'];
            }
            else
            {
                $last_id = $this->repo->register($request);
                $path = Config::get('DocumentConstant.USER_PROFILE_ADD');
                
                //"\all_web_data\images\home\slides\\"."\\";
                $imageProfile = $last_id['imageProfile'];
              
                // $userProfile = $last_id . '_english.' . $request->user_profile->extension();
                uploadImage($request, 'user_profile', $path, $imageProfile);
                // dd($imageProfile);
                // die();
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