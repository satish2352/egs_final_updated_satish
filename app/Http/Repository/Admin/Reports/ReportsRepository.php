<?php
namespace App\Http\Repository\Admin\Reports;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
    Labour,
    User

};

class ReportsRepository  {

    public function getAllLabourLocation(){
        try {
            
          $sess_user_id=session()->get('user_id');
          $sess_user_type=session()->get('user_type');
          $sess_user_role=session()->get('role_id');
          if($sess_user_role=='1')
          {
             $data_labours = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
          ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
          ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
          ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
          ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->select(
            'labour.id',
            'labour.full_name',
            'labour.date_of_birth',
            'gender_labour.gender_name as gender_name',
            'district_labour.name as district_id',
            'taluka_labour.name as taluka_id',
            'village_labour.name as village_id',
            'labour.mobile_number',
            'labour.landline_number',
            'labour.mgnrega_card_id',
            'labour.aadhar_image',
            'labour.mgnrega_image', 
            'labour.profile_image', 
            'labour.voter_image', 
            'labour.is_active',
            'labour.is_approved',
            'users.f_name',
            'users.m_name',
            'users.l_name',
            )->get();
            }else if($sess_user_role=='2')
            {
      
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                      ->where('users.id', $sess_user_id)
                      ->first();
      
                  $utype=$data_output->user_type;
                  $user_working_dist=$data_output->user_district;
                  $user_working_tal=$data_output->user_taluka;
                  $user_working_vil=$data_output->user_village;
      
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                      ->where('users.id', $sess_user_id)
                      ->first();
      
                  if($utype=='1')
                  {
                  $data_user_output = User::where('users.user_district', $user_working_dist)
                  ->select('id')
                      ->get()
              ->toArray();
                  }else if($utype=='2')
                  {
                      $data_user_output = User::where('users.user_taluka', $user_working_tal)
                      ->select('id')
                      ->get()
              ->toArray();
                  }else if($utype=='3')
                  {
                      $data_user_output = User::where('users.user_village', $user_working_vil)
                      ->select('id')
                      ->get()
              ->toArray();
                  }         
                  
                      $data_labours = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                      ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                      ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                      ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                      ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                      ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
              ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                      ->whereIn('labour.user_id',$data_user_output)
                      ->where('registrationstatus.is_active', true)
                      ->select(
                          'labour.id',
                          'labour.full_name',
                          'labour.date_of_birth',
                          'gender_labour.gender_name as gender_name',
                          'skills_labour.skills as skills',
                          'district_labour.name as district_id',
                          'taluka_labour.name as taluka_id',
                          'village_labour.name as village_id',
                          'labour.mobile_number',
                          'labour.landline_number',
                          'labour.mgnrega_card_id',
                          'labour.latitude',
                          'labour.longitude',
                          'labour.profile_image',
                          'registrationstatus.status_name',
                'labour.is_approved',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                      )
                          ->get();
            }else if($sess_user_role=='3')
            {
      
                $data_labours = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->where('labour.user_id',$sess_user_id)
                ->where('registrationstatus.is_active', true)
                ->select(
                  'labour.id',
                  'labour.full_name',
                  'labour.date_of_birth',
                  'gender_labour.gender_name as gender_name',
                  'skills_labour.skills as skills',
                  'district_labour.name as district_id',
                  'taluka_labour.name as taluka_id',
                  'village_labour.name as village_id',
                  'labour.mobile_number',
                  'labour.landline_number',
                  'labour.mgnrega_card_id',
                  'labour.latitude',
                  'labour.longitude',
                  'labour.profile_image',
                  'registrationstatus.status_name',
                  'labour.is_approved',
                  'users.f_name',
                  'users.m_name',
                  'users.l_name',
                )
                  ->get();
              }	
            return $data_labours;
        } catch (\Exception $e) {
      
            return $e;
        }
    }
}