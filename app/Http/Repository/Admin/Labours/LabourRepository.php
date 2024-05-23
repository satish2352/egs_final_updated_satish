<?php
namespace App\Http\Repository\Admin\Labours;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles,
	Labour,
	LabourAttendanceMark,
	LabourFamilyDetails,
	GramPanchayatDocuments,
	HistoryModel,
	Skills
};
use Illuminate\Support\Facades\Mail;

class LabourRepository
{

	public function getLaboursList() {
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
		->where('labour.is_approved', '1')
		->where('labour.is_resubmitted', '0')
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
			'labour.is_resubmitted',
			'users.f_name',
			'users.m_name',
			'users.l_name'
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
				->where('labour.is_approved', '1')
				->where('labour.is_resubmitted', '0')
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
					'labour.is_resubmitted',
					'users.f_name',
					'users.m_name',
					'users.l_name'
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
				  ->where('labour.is_approved', '1')
				  ->where('labour.is_resubmitted', '0')
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
					  'labour.is_resubmitted',
					  'users.f_name',
					  'users.m_name',
					  'users.l_name'
				  )
					  ->get();
			  }		
		return $data_labours;
	}

	public function getApprovedLaboursList() {
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
		->where('labour.is_approved', '2')
		->where('labour.is_resubmitted', '0')
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
			'users.l_name'
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
				->where('labour.is_approved', '2')
				->where('labour.is_resubmitted', '0')
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
					'labour.is_resubmitted',
					'users.f_name',
					'users.m_name',
					'users.l_name'
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
				  ->where('labour.is_approved', '2')
				  ->where('labour.is_resubmitted', '0')
				  ->where('labour.user_id',$sess_user_id)
				  ->where('registrationstatus.is_active', true)
				  // ->where('labour.is_approved', $is_approved)
				  // ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
				  //     $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
				  // })
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
					  'labour.is_resubmitted',
					  'users.f_name',
					  'users.m_name',
					  'users.l_name'
				  )
					  ->get();
			  }		
		return $data_labours;
	}

	public function getTodaysApprovedLaboursList() {
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
		->where('labour.is_approved', '2')
		->where('labour.is_resubmitted', '0')
		->whereDate('labour.created_at', today())
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
			'users.l_name'
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
				->where('labour.is_approved', '2')
				->where('labour.is_resubmitted', '0')
                ->whereIn('labour.user_id',$data_user_output)
                ->where('registrationstatus.is_active', true)
                ->whereDate('labour.created_at', today())
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
					'labour.is_resubmitted',
					'users.f_name',
					'users.m_name',
					'users.l_name'
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
				  ->where('labour.is_approved', '2')
				  ->where('labour.is_resubmitted', '0')
				  ->where('labour.user_id',$sess_user_id)
				  ->where('registrationstatus.is_active', true)
				  ->whereDate('labour.created_at', today())
				  // ->where('labour.is_approved', $is_approved)
				  // ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
				  //     $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
				  // })
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
					  'labour.is_resubmitted',
					  'users.f_name',
					  'users.m_name',
					  'users.l_name'
				  )
					  ->get();
			  }		
		return $data_labours;
	}

	public function getDisapprovedLaboursList() {
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
		->where('labour.is_approved', '3')
		->where('labour.is_resubmitted', '0')
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
			'labour.is_resubmitted',
			'users.f_name',
			'users.m_name',
			'users.l_name'
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
				->where('labour.is_approved', '3')
				->where('labour.is_resubmitted', '0')
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
					'labour.is_resubmitted',
					'users.f_name',
					'users.m_name',
					'users.l_name'
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
				  ->where('labour.is_approved', '3')
				  ->where('labour.is_resubmitted', '0')
				  ->where('labour.user_id',$sess_user_id)
				  ->where('registrationstatus.is_active', true)
				  // ->where('labour.is_approved', $is_approved)
				  // ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
				  //     $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
				  // })
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
					  'labour.is_resubmitted',
					  'users.f_name',
					  'users.m_name',
					  'users.l_name'
				  )
					  ->get();
			  }		
		return $data_labours;
	}

	public function getResubmitedLaboursList() {
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
		->where('labour.is_resubmitted', '1')
		->where('labour.is_approved', '1')
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
			'labour.is_resubmitted',
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
				->where('labour.is_resubmitted', '1')
				->where('labour.is_approved', '1')
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
					'labour.is_resubmitted',
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
				  ->where('labour.is_resubmitted', '1')
				  ->where('labour.is_approved', '1')
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
					  'labour.is_resubmitted',
					  'users.f_name',
					  'users.m_name',
					  'users.l_name',
				  )
					  ->get();
			  }		
		return $data_labours;
	}


	public function getLabourAttendanceList() {
		$sess_user_id=session()->get('user_id');

		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$date = date('Y-m-d'); 

		$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

		$user_working_dist=$data_output->user_district;
		$user_type=$data_output->user_type;
		// dd($user_working_dist);
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

		if($sess_user_role=='1')
		{
     	
		  $data_labour_attendance = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
			->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
            ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
            ->whereDate('tbl_mark_attendance.created_at', $date)
			->select(
				'tbl_mark_attendance.id',
				'users.f_name',
				'tbl_mark_attendance.project_id',
				'projects.project_name',
				'labour.full_name as full_name',
				'labour.date_of_birth',
				'labour.mobile_number',
				'labour.landline_number',
				'labour.mgnrega_card_id',
				'users.user_taluka',
				'taluka_labour.name as taluka_name',
				'users.user_village',
				'village_labour.name as village_name',
				'labour.latitude',
				'labour.longitude',
				'labour.profile_image',
				'tbl_mark_attendance.attendance_day',
				'tbl_mark_attendance.created_at'

			)->get();
				// dd($data_labour_attendance);
		  }else if($sess_user_role=='2')
		  {
			$data_labour_attendance = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
			->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
            ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
			->where('projects.district', $user_working_dist)
            ->whereDate('tbl_mark_attendance.created_at', $date)
			->select(
				'tbl_mark_attendance.id',
				'users.f_name',
				'tbl_mark_attendance.project_id',
				'projects.project_name',
				'labour.full_name as full_name',
				'labour.date_of_birth',
				'labour.mobile_number',
				'labour.landline_number',
				'labour.mgnrega_card_id',
				'users.user_taluka',
				'taluka_labour.name as taluka_name',
				'users.user_village',
				'village_labour.name as village_name',
				'labour.latitude',
				'labour.longitude',
				'labour.profile_image',
				'tbl_mark_attendance.attendance_day',
				'tbl_mark_attendance.created_at'

			)->get();

			}else if($sess_user_role=='3')
			{
			  $data_labour_attendance = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
			  ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
			  ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
			  ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
			  ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
			  ->whereDate('tbl_mark_attendance.created_at', $date)
			  ->where('tbl_mark_attendance.user_id', $sess_user_id)
			  ->select(
				  'tbl_mark_attendance.id',
				  'users.f_name',
				  'tbl_mark_attendance.project_id',
				  'projects.project_name',
				  'labour.full_name as full_name',
				  'labour.date_of_birth',
				  'labour.mobile_number',
				  'labour.landline_number',
				  'labour.mgnrega_card_id',
				  'users.user_taluka',
				  'taluka_labour.name as taluka_name',
				  'users.user_village',
				  'village_labour.name as village_name',
				  'labour.latitude',
				  'labour.longitude',
				  'labour.profile_image',
				  'tbl_mark_attendance.attendance_day',
				  'tbl_mark_attendance.created_at'
  
			  )->get();
  
			  }		
			// dd();	
		return $data_labour_attendance;
	}

	


	public function permissionsData()
	{
		$permissions = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		return $permissions;
	}
	// public function register($request)
	// {
	// 	$ipAddress = getIPAddress($request);
	// 	$user_data = new User();
	// 	$user_data->email = $request['email'];
	// 	// $user_data->u_uname = $request['u_uname'];
	// 	$user_data->password = bcrypt($request['password']);
	// 	$user_data->role_id = $request['role_id'];
	// 	$user_data->f_name = $request['f_name'];
	// 	$user_data->m_name = $request['m_name'];
	// 	$user_data->l_name = $request['l_name'];
	// 	$user_data->number = $request['number'];
	// 	$user_data->designation = $request['designation'];
	// 	$user_data->address = $request['address'];
	// 	$user_data->state = $request['state'];
	// 	$user_data->city = $request['city'];
	// 	$user_data->pincode = $request['pincode'];
	// 	$user_data->ip_address = $ipAddress;
	// 	$user_data->is_active = isset($request['is_active']) ? true : false;
	// 	$user_data->save();

	// 	$last_insert_id = $user_data->id;
	// 	// $this->insertRolesPermissions($request, $last_insert_id);
	// 	return $last_insert_id;
	// }

	public function addProject($request)
	{
		try {
		$data =array();
		// $ipAddress = getIPAddress($request);
		$project_data = new Projects();
		// $project_data->email = $request['email'];
		// $project_data->u_uname = $request['u_uname'];
		// $project_data->password = bcrypt($request['password']);
		$project_data->project_name = $request['project_name'];
		$project_data->state = $request['state'];
		$project_data->district = $request['district'];
		$project_data->taluka	 = $request['taluka'];
		$project_data->village = $request['village'];

		$project_data->latitude = $request['latitude'];
		$project_data->longitude = $request['longitude'];
		$project_data->start_date = $request['start_date'];
		$project_data->end_date = $request['end_date'];
		$project_data->description = $request['description'];
		$project_data->is_active = isset($request['is_active']) ? true : false;
		$project_data->save();

		$last_insert_id = $project_data->id;
		// $this->insertRolesPermissions($request, $last_insert_id);

		// $imageProfile = $last_insert_id .'_' . rand(100000, 999999) . '_english.' . $request->user_profile->extension();
        
        // $user_detail = User::find($last_insert_id); // Assuming $request directly contains the ID
        // $user_detail->user_profile = $imageProfile; // Save the image filename to the database
        // $user_detail->save();
        // echo  $user_detail;
		// die();
        // $data['imageProfile'] =$imageProfile;
        return $project_data;
	} catch (\Exception $e) {
		return [
			'msg' => $e,
			'status' => 'error'
		];
	}

	}

	public function update($request)
	{
        $ipAddress = getIPAddress($request);
		$user_data = User::where('id',$request['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'role_id' => $request['role_id'],
							'f_name' => $request['f_name'],
							'm_name' => $request['m_name'],
							'l_name' => $request['l_name'],
							'number' => $request['number'],
							'imei_no' => $request['imei_no'],
							'aadhar_no' => $request['aadhar_no'],
							'address' => $request['address'],
							'state' => $request['state'],
							'district' => $request['district'],
							'taluka' => $request['taluka'],
							'village' => $request['village'],
							'pincode' => $request['pincode'],
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}


	// private function updateRolesPermissions($request, $last_insert_id) {

	// 	$permissions_data_from_table  = $this->permissionsData();
	// 	$update_data = array();
	// 	foreach ($permissions_data_from_table as $key => $data) {
	// 		$permission_id  = 'permission_id_'.$data['id'];
	// 		$per_add  = 'per_add_'.$data['id'];
	// 		$per_update  = 'per_update_'.$data['id'];
	// 		$per_delete  = 'per_delete_'.$data['id'];

	// 		$update_data['role_id'] = $request->role_id;
	// 		if($request->has($per_add)) {
	// 			$update_data['per_add']  = true;
	// 		} else {
	// 			$update_data['per_add']  = false;
	// 		}

	// 		if($request->has($per_update)) {
	// 			$update_data['per_update']  = true;
	// 		} else {
	// 			$update_data['per_update']  = false;
	// 		}

	// 		if($request->has($per_delete)) {
	// 			$update_data['per_delete']  = true;
	// 		} else {
	// 			$update_data['per_delete']  = false;
	// 		}

	// 		$permissions_data_all = RolesPermissions::where([
	// 			'user_id' =>$request['edit_id'],
	// 			'permission_id' =>$data['id']
	// 		])->get()->toArray();
	// 		if(count($permissions_data_all)>0) {

	// 			$permissions_data = RolesPermissions::where([
	// 				'user_id' =>$request['edit_id'],
	// 				'permission_id' =>$data['id']
	// 			])->update($update_data);
	// 		} else {
	// 			$update_data['user_id']  = $request['edit_id'];
	// 			$update_data['permission_id']  = $data['id'];
	// 			$permissions_data = RolesPermissions::insert($update_data);
	// 		}

	// 	}
	// 	return "ok";
	// }


	// private function insertRolesPermissions($request, $last_insert_id) {

	// 	$permissions_data_from_table  = $this->permissionsData();
	// 	foreach ($permissions_data_from_table as $key => $data) {
	// 		$permission_id  = 'permission_id_'.$data['id'];
	// 		$per_add  = 'per_add_'.$data['id'];
	// 		$per_update  = 'per_update_'.$data['id'];
	// 		$per_delete  = 'per_delete_'.$data['id'];
	// 		if($request->has($permission_id) && ($request->has($per_add) || $request->has($per_update) || $request->has($per_delete))) {
	// 			// dd("I am here for permission");
	// 			$permissions_data = new RolesPermissions();
	// 			$permissions_data->permission_id = $data['id'];
	// 			$permissions_data->role_id = $request->role_id;
	// 			$permissions_data->user_id = $last_insert_id;

	// 			if($request->has($per_add)) {
	// 				$permissions_data->per_add  = true;
	// 			}

	// 			if($request->has($per_update)) {
	// 				$permissions_data->per_update  = true;
	// 			}

	// 			if($request->has($per_delete)) {
	// 				$permissions_data->per_delete  = true;
	// 			}
	// 			$permissions_data->save();
	// 		}

	// 	}
	// 	return "ok";
	// }

	public function checkDupCredentials($request)
	{
		return User::where('email', '=', $request['email'])
			// ->orWhere('u_uname','=',$request['u_uname'])
			->select('id')->get();
	}

	public function editProjects($reuest)
	{

		$data_projects = [];

		// $data_users['roles'] = Roles::where('is_active', true)
		// 	->select('id', 'role_name')
		// 	->get()
		// 	->toArray();
		$data_projects['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_projects_data = Projects::where('projects.id', '=', base64_decode($reuest->edit_id))
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				// 'roles.id as role_id',
				'projects.project_name',
				'projects.description',
				'projects.state',
				'projects.district',
				'projects.village',
				'projects.start_date',
				'projects.end_date',
				'projects.latitude',
				'projects.longitude',
				'projects.id',
				'projects.is_active',
			)->get()
			->toArray();
						
		$data_projects['data_projects'] = $data_projects_data[0];
		// $data_users['permissions_user'] = User::join('roles_permissions', function($join) {
		// 					$join->on('users.id', '=', 'roles_permissions.user_id');
		// 				})
		// 				->join('permissions', function($join) {
		// 					$join->on('roles_permissions.permission_id', '=', 'permissions.id');
		// 				})
		// 				->where('roles_permissions.user_id','=',$reuest->edit_id)
		// 				->where('roles_permissions.is_active','=',true)
		// 				// ->where('users.is_active','=',true)
		// 				->select(
		// 					'roles_permissions.per_add',
		// 					'roles_permissions.per_update',
		// 					'roles_permissions.per_delete',
		// 					'permissions.id as permissions_id'
		// 					)->get()
		// 					->toArray();

		return $data_projects;
	}

	// public function delete($request)
	// {
	// 	$user = User::where(['id' => $request->delete_id])
	// 		->update(['is_active' => false]);
	// 	// $rolesPermissions = RolesPermissions::where(['user_id' => $request->delete_id])
	// 	// 	->update(['is_active' => false]);

	// 	return "ok";
	// }

	public function delete($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
              
                $user->delete();
               
                return $user;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function getById($id)
	{
		// dd($id);
		$data_labours = [];
		try {
			
			$data_labours['data_users_data'] = Labour::leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
			->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
			  ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
			  ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
			  ->leftJoin('skills as skills_labour', 'labour.skill_id', '=', 'skills_labour.id')
				->where('labour.id', $id)
				->select('labour.id',
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
				'labour.latitude',
				'labour.longitude',
				'labour.is_active',
				'labour.is_approved',
				'labour.reason_id',
				'labour.other_remark',
				'labour.is_resubmitted',
				'skills_labour.skills as skill_name',)
				->first();



				$data_labours['data_family_users'] = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
                ->leftJoin('relation as relationship_labour', 'labour_family_details.relationship_id', '=', 'relationship_labour.id')
                ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
                ->where('labour_family_details.labour_id', $id)
                ->select('labour_family_details.id',
                'labour_family_details.full_name',
                'labour_family_details.date_of_birth',
                'gender_labour.gender_name as gender_name',
				'relationship_labour.relation_title',
				'maritalstatus_labour.maritalstatus')
				->get()
				->toArray();

				$mgnrega_id=$data_labours['data_users_data']['mgnrega_card_id'];

				$data_labours['data_verification_history'] = HistoryModel::leftJoin('registrationstatus as registrationstatus_labour', 'tbl_history.is_approved', '=', 'registrationstatus_labour.id')
                ->leftJoin('tbl_reason as tbl_reason_labour', 'tbl_history.reason_id', '=', 'tbl_reason_labour.id')
                ->where('tbl_history.labour_id', $id)
                ->select('tbl_history.id',
                'tbl_history.is_approved',
                'tbl_history.reason_id',
                'tbl_history.other_remark',
                'tbl_history.created_at',
                'registrationstatus_labour.status_name',
				'tbl_reason_labour.reason_name')
				->get()
				->toArray();

				// dd($data_labours);
	
			if ($data_labours) {
				return $data_labours;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}

	public function updateOne($request){
        try {
            $project = Labour::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($project) {
                $is_active = $project->is_active === 1 ? 0 : 1;
                $project->is_active = $is_active;
                $project->save();

                return [
                    'msg' => 'Labour updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Labour not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Labour.',
                'status' => 'error'
            ];
        }
    }

	

	public function updateLabourStatus($request)
	{
		// dd($request);
		if($request['is_approved']=='2')
		{
			$user_data = Labour::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'is_resubmitted' => '0',
						]);
		}else if($request['is_approved']=='3' && $request['other_remark']!='')		
		{
			$user_data = Labour::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'reason_id' => $request['reason_id'],
							'other_remark' => $request['other_remark'],
							'is_resubmitted' => '0',
						]);
		}
		else if($request['is_approved']=='3' && $request['other_remark']=='')		
		{
			$user_data = Labour::where('id',$request['edit_id']) 
						->update([
							'is_approved' => $request['is_approved'],
							'reason_id' => $request['reason_id'],
							'is_resubmitted' => '0',
						]);
		}			
		
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}

	// public function updateLabourStatus($request)
	// {
	// 	// dd($request);
	// 	if($request['is_approved']=='2')
	// 	{
	// 		$user_data = Labour::where('id',$request['edit_id']) 
	// 					->update([
	// 						'is_approved' => $request['is_approved'],
	// 						'is_resubmitted' => '0',
	// 					]);
	// 	}else if($request['is_approved']=='3' && $request['other_remark']!='')		
	// 	{
	// 		$user_data = Labour::where('id',$request['edit_id']) 
	// 					->update([
	// 						'is_approved' => $request['is_approved'],
	// 						'reason_id' => $request['reason_id'],
	// 						'other_remark' => $request['other_remark']
	// 					]);
	// 	}
	// 	else if($request['is_approved']=='3' && $request['other_remark']=='')		
	// 	{
	// 		$user_data = Labour::where('id',$request['edit_id']) 
	// 					->update([
	// 						'is_approved' => $request['is_approved'],
	// 						'reason_id' => $request['reason_id']
	// 					]);
	// 	}			
		
	// 	// $this->updateRolesPermissions($request, $request->edit_id);
	// 	return $request->edit_id;
	// }

	public function getGramsevakList() {

		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->get();

		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		return $data_users;
	}

	public function showGramsevakDocuments($id)
	{
		$data_gram_doc=[];
		try {
			$data_gram_doc['user_data'] = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.id', $id)
				->select('users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->first();

			$data_gram_doc['user_doc_data'] = GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
			->where('tbl_gram_panchayat_documents.user_id', $id)
				->select('tbl_gram_panchayat_documents.user_id',
				'tbl_gram_panchayat_documents.document_type_id',
				'tbl_gram_panchayat_documents.document_name',
				'tbl_gram_panchayat_documents.document_pdf',
				'tbl_gram_panchayat_documents.is_active',
				'documenttype.document_type_name')
				->get();
					

	
			if ($data_gram_doc) {
				return $data_gram_doc;
			} else {
				return null;
			}
		} catch (\Exception $e) {
			return [
				'msg' => $e->getMessage(),
				'status' => 'error'
			];
		}
	}

}