<?php

namespace App\Http\Controllers\Api\UserProfile;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;


class UserProfileController extends Controller
{
public function getParticularUserProfile(Request $request) {

        try {
            $user = auth()->user()->id;
        
            $data_output = User::leftJoin('tbl_area as district_users', 'users.user_district', '=', 'district_users.location_id')
            ->leftJoin('tbl_area as taluka_users', 'users.user_taluka', '=', 'taluka_users.location_id')
            ->leftJoin('tbl_area as village_users', 'users.user_village', '=', 'village_users.location_id')
            ->where('users.id', $user)
            ->select('users.id',
                User::raw("CONCAT(users.f_name, IFNULL(CONCAT(' ', users.m_name), ''),' ', users.l_name) AS gramsevak_full_name"),
                'users.number',
                'users.user_district',
                'district_users.name as district_name',
                'users.user_taluka',
                'taluka_users.name as taluka_name',
                'users.user_village',
                'village_users.name as village_name',
                'users.aadhar_no',
                'users.address',               
                )->distinct('users.id')
                ->get();
                
                foreach ($data_output as $userimage) {
                    $userimage->user_profile = Config::get('DocumentConstant.USER_PROFILE_VIEW') . $userimage->user_profile;
                }
    
            return response()->json(['status' => 'true', 'message' => 'User data retrieved successfully','data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'User Details Get Fail','error' => $e->getMessage()], 500);
        }
    }

}