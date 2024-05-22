<?php
namespace App\Http\Repository\Admin\Area;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles,
    TblArea
};
use Illuminate\Support\Facades\Mail;

class AreaRepository
{

    public function getUsersList() {
        $data_district = TblArea::where('tbl_area.location_type','=','2')
                        ->where('tbl_area.parent_id','=','2')
						->select('tbl_area.location_id',
								'tbl_area.name',
                                'tbl_area.is_active',
							)
							->orderBy('tbl_area.name', 'asc')
							->get();
							// ->toArray();
        // dd($data_district);
		return $data_district;
	}

    public function addDistrictInsert($request)
	{
		$data =array();
		$area_data = new TblArea();
		$area_data->name = $request['name'];
		$area_data->location_type = '2';
		$area_data->parent_id = '2';
		$area_data->is_new = '1';
		$area_data->is_active = isset($request['is_active']) ? true : false;
		$area_data->save();
		$last_insert_id = $area_data->location_id;

        return $last_insert_id;

	}

    public function editDistrict($reuest)
	{

		// $data_district = [];

		$data_users_data = TblArea::where('tbl_area.location_id', '=', base64_decode($reuest->edit_id))
			->select(
				'tbl_area.location_id',
				'tbl_area.name',
				'tbl_area.is_active',
			)->get()
			->toArray();

	    
						
		$data_district = $data_users_data[0];
// dd($data_district);
		return $data_district;
	}

	public function updateDistrict($request)
	{
		$user_data = TblArea::where('location_id',$request['edit_id']) 
						->update([
							'name' => $request['name'],
							'parent_id' => '2',
							'is_new' => '1',
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		return $request->edit_id;
	}

    public function deleteDistrict($id)
    {
        try {
            $user = TblArea::find($id);
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

    public function updateOneDistrict($request){
        try {
            $area_data = TblArea::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($area_data) {
                $is_active = $area_data->is_active === 1 ? false : true;
                $area_data->is_active = $is_active;
                $area_data->is_new = 1;
                // $area_data->save();
				dd($area_data->save());
                return [
                    'msg' => 'District updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'District not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update District.',
                'status' => 'error'
            ];
        }
    }

    public function getTalukaList() {
        $data_taluka = TblArea::leftJoin('tbl_area as district_data', 'tbl_area.parent_id', '=', 'district_data.location_id')
                        ->where('tbl_area.location_type','=','3')
						->select('tbl_area.location_id',
								'tbl_area.name',
                                'tbl_area.is_active',
                                'district_data.name as dist_name',
							)
							->orderBy('district_data.name', 'asc')
							->get();
							
		return $data_taluka;
	}

    public function addTalukaInsert($request)
	{
		$data =array();
		$area_data = new TblArea();
		$area_data->name = $request['name'];
		$area_data->location_type = '3';
		$area_data->parent_id = $request['district'];
		$area_data->is_new = '1';
		$area_data->is_active = isset($request['is_active']) ? true : false;
		$area_data->save();

		$last_insert_id = $area_data->location_id;
        return $last_insert_id;
	}

    public function editTaluka($reuest)
	{

		$data_users = [];

		$data_users_data = TblArea::where('tbl_area.location_id', '=', base64_decode($reuest->edit_id))
			->select(
				'tbl_area.name',
				'tbl_area.location_id',
				'tbl_area.parent_id',
				'tbl_area.is_active',
			)->get()
			->toArray();

		$data_taluka = $data_users_data[0];
	// dd($data_taluka);

		return $data_taluka;
	}

    public function updateTaluka($request)
	{
		$user_data = TblArea::where('location_id',$request['edit_id']) 
						->update([
							'name' => $request['name'],
							'parent_id' => $request['district'],
							'is_new' => '1',
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		return $request->edit_id;
	}

    public function deleteTaluka($id)
    {
        try {
            $user = TblArea::find($id);
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

    public function updateOneTaluka($request){
        try {
            
            $area_data = TblArea::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($area_data) {
                $is_active = $area_data->is_active === 1 ? 0 : 1;
                $area_data->is_active = $is_active;
                $area_data->is_new = 1;
                $area_data->save();

                return [
                    'msg' => 'District updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'District not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update District.',
                'status' => 'error'
            ];
        }
    }

	public function getVillageList() {
		$data_village = TblArea::leftJoin('tbl_area as taluka_data', 'tbl_area.parent_id', '=', 'taluka_data.location_id')
						->leftJoin('tbl_area as district_data', 'taluka_data.parent_id', '=', 'district_data.location_id')
						->where('tbl_area.location_type','=','4')
						->select('tbl_area.location_id',
								'tbl_area.name',
								'tbl_area.is_active',
								'taluka_data.name as taluka_name',
								'district_data.name as district_name',
							)
							->orderBy('taluka_data.name', 'asc')
							->paginate(50);
	// dd($data_village);
		return $data_village;
	}
	
    // public function getVillageList() {
    //     $data_village = TblArea::leftJoin('tbl_area as taluka_data', 'tbl_area.parent_id', '=', 'taluka_data.location_id')
    //                     ->leftJoin('tbl_area as district_data', 'taluka_data.parent_id', '=', 'district_data.location_id')
    //                     ->where('tbl_area.location_type','=','4')
	// 					->select('tbl_area.location_id',
	// 							'tbl_area.name',
    //                             'tbl_area.is_active',
    //                             'taluka_data.name as taluka_name',
    //                             'district_data.name as district_name',
	// 						)
	// 						->orderBy('taluka_data.name', 'asc')
	// 						// ->get()
	// 						->paginate(20);
	// 	dd($data_village);					
							
	// 	return $data_village;
	// }

    public function addVillageInsert($request)
	{
        // dd($request);
		$data =array();
		$area_data = new TblArea();
		$area_data->name = $request['name'];
		$area_data->location_type = '4';
		$area_data->parent_id = $request['taluka'];
		$area_data->is_new = '1';
		$area_data->is_active = isset($request['is_active']) ? true : false;
		$area_data->save();

		$last_insert_id = $area_data->location_id;
        return $last_insert_id;

	}

    public function updateOneVillage($request){
        try {
            
            $area_data = TblArea::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($area_data) {
                $is_active = $area_data->is_active === 1 ? 0 : 1;
                $area_data->is_active = $is_active;
                $area_data->is_new = 1;
                $area_data->save();

                return [
                    'msg' => 'Village updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Village not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Village.',
                'status' => 'error'
            ];
        }
    }

    public function editVillage($reuest)
	{

		$data_users = [];

		$data_users_data = TblArea::leftJoin('tbl_area as taluka_data', 'tbl_area.parent_id', '=', 'taluka_data.location_id')
            ->leftJoin('tbl_area as district_data', 'taluka_data.parent_id', '=', 'district_data.location_id')
            ->where('tbl_area.location_id', '=', base64_decode($reuest->edit_id))
			->select(
				'tbl_area.name',
				'tbl_area.location_id',
				'tbl_area.parent_id',
				'tbl_area.is_active',
				'district_data.location_id as district_id',
			)->get()
			->toArray();

		$data_village = $data_users_data[0];
	// dd($data_village);

		return $data_village;
	}

    public function updateVillage($request)
	{
		$user_data = TblArea::where('location_id',$request['edit_id']) 
						->update([
							'name' => $request['name'],
							'parent_id' => $request['taluka'],
							'is_new' => '1',
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		return $request->edit_id;
	}

    public function deleteVillage($id)
    {
        try {
            $user = TblArea::find($id);
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


	public function register($request)
	{
		$data =array();
		// $ipAddress = getIPAddress($request);
		$user_data = new User();
		$user_data->email = $request['email'];
		// $user_data->u_uname = $request['u_uname'];
		$user_data->password = bcrypt($request['password']);
		$user_data->role_id = $request['role_id'];
		$user_data->f_name = $request['f_name'];
		$user_data->m_name = $request['m_name'];
		$user_data->l_name = $request['l_name'];
		$user_data->number = $request['number'];
		$user_data->aadhar_no = $request['aadhar_no'];
		$user_data->address = $request['address'];
		$user_data->state = '';
		$user_data->district = $request['district'];
		$user_data->taluka	 = $request['taluka'];
		$user_data->village = $request['village'];
		$user_data->pincode = $request['pincode'];
		$user_data->user_type = $request['user_type'];
		$user_data->user_district = $request['user_district'];
		$user_data->user_taluka = $request['user_taluka'];
		$user_data->user_village = $request['user_village'];
		$user_data->ip_address = 'null';
		$user_data->is_active = isset($request['is_active']) ? true : false;
		$user_data->save();

		$last_insert_id = $user_data->id;
		// $this->insertRolesPermissions($request, $last_insert_id);

		$imageProfile = $last_insert_id .'_' . rand(100000, 999999) . '_english.' . $request->user_profile->extension();
        
        $user_detail = User::find($last_insert_id); // Assuming $request directly contains the ID
        $user_detail->user_profile = $imageProfile; // Save the image filename to the database
        $user_detail->save();
        // echo  $user_detail;
		// die();
        $data['imageProfile'] =$imageProfile;
        return $data;

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
							'aadhar_no' => $request['aadhar_no'],
							'address' => $request['address'],
							'district' => $request['district'],
							'taluka' => $request['taluka'],
							'village' => $request['village'],
							'pincode' => $request['pincode'],
							'user_type' => $request['user_type'],
							'user_district' => $request['user_district'],
							'user_taluka' => $request['user_taluka'],
							'user_village' => $request['user_village'],
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

	public function editUsers($reuest)
	{

		$data_users = [];

		$data_users['roles'] = Roles::where('is_active', true)
			->select('id', 'role_name')
			->get()
			->toArray();
		$data_users['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_users_data = User::join('roles', function ($join) {
			$join->on('users.role_id', '=', 'roles.id');
		})
			
			->where('users.id', '=', base64_decode($reuest->edit_id))
			->select(
				'roles.id o    le_id',
				// 'users.u_uname',
				'users.password',
				'users.email',
				'users.f_name',
				'users.m_name',
				'users.l_name',
				'users.number',
				'users.aadhar_no',
				'users.address',
				'users.district',
				'users.taluka',
				'users.village',
				'users.pincode',
				'users.user_type',
				'users.state',
				'users.user_district',
				'users.user_taluka',
				'users.user_village',
				'users.id',
				'users.is_active',
			)->get()
			->toArray();

	    $data_users_data = User::join('roles', function($join) {
						$join->on('users.role_id', '=', 'roles.id');
					})
					// ->join('roles_permissions', function($join) {
					// 	$join->on('users.id', '=', 'roles_permissions.user_id');
					// })
					->where('users.id','=',base64_decode($reuest->edit_id))
					// ->where('roles_permissions.is_active','=',true)
					// ->where('users.is_active','=',true)
					->select('roles.id as role_id',
							// 'users.u_uname',
							'users.password',
							'users.email',
							'users.f_name',
							'users.m_name',
							'users.l_name',
							'users.number',
							'users.aadhar_no',
							'users.address',
							'users.district',
							'users.taluka',
							'users.village',
							'users.pincode',
							'users.user_type',
							'users.state',
							'users.user_district',
							'users.user_taluka',
							'users.user_village',
							'users.id',
							'users.is_active',
						)->get()
						->toArray();
						
		$data_users['data_users'] = $data_users_data[0];
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

		return $data_users;
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
		try {
			$user = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.village', '=', 'village_user.location_id')
				->where('users.id', $id)
				->select('users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village')
				->first();
	
			if ($user) {
				return $user;
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
            $user = User::find($request); // Assuming $request directly contains the ID

            // Assuming 'is_active' is a field in the userr model
            if ($user) {
                $is_active = $user->is_active === 1 ? 0 : 1;
                $user->is_active = $is_active;
                $user->save();

                return [
                    'msg' => 'User updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'User not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update User.',
                'status' => 'error'
            ];
        }
    }

	public function getProfile()
	{
		$user_detail = User::where('is_active', true)
			->where('id', session()->get('user_id'))
			->select('id', 'f_name', 'm_name', 'l_name', 'email', 'password', 'number', 'designation','user_profile')
			->first();
		return $user_detail;
	}


	public function updateProfile($request)
	{
		try {
			
			$return_data = array();
			$otp = rand(6, 999999);

			
			$update_data = [
				'f_name' => $request->f_name,
				'm_name' => $request->m_name,
				'l_name' => $request->l_name,
				'designation' => $request->designation,
			];
			
			if (isset($return_data['user_profile'])) {
				$previousUserProfile = $update_data->user_profile;
			}
			// if ($request->hasFile('user_profile')) {
			// 	$profileImage = $request->file('user_profile');
			// 	$newImagePathOrFilename = $profileImage->store('profile_images');
			// 	$update_data['user_profile'] = $newImagePathOrFilename;
			// }

			if (($request->number != $request->old_number) && !isset($request->password)) {
				$this->sendOTPEMAIL($otp, $request);
				info("only mobile change");
				$return_data['password_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['password_new'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

			}

			if ((isset($request->password) && $request->password !== '') && ($request->number == $request->old_number)) {
				info("only password change");
				// $update_data['password'] = bcrypt($request->password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'no';
				$update_data['otp'] = $otp;
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['password_new'] = bcrypt($request->password);
				$return_data['new_mobile_number'] = '';
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}

			if ((isset($request->password) && $request->password !== '') && ($request->number != $request->old_number)) {
				info("only password and mobile number changed");
				$update_data['otp'] = $otp;
				$return_data['password_new'] = bcrypt($request->password);
				$return_data['password_change'] = 'yes';
				$return_data['mobile_change'] = 'yes';
				$return_data['user_id'] = $request->edit_user_id;
				$return_data['new_mobile_number'] = $request->number;
				$return_data['msg'] = "OTP sent on registered on email";
				$return_data['msg_alert'] = "green";

				$this->sendOTPEMAIL($otp, $request);
			}
			
			User::where('id', $request->edit_user_id)->update($update_data);

			$user_data = User::find($request->edit_user_id);
			$previousUserProfile = $user_data->english_image;
			$last_insert_id = $user_data->id;

            $return_data['last_insert_id'] = $last_insert_id;
            $return_data['user_profile'] = $previousUserProfile;
			return $return_data;


		} catch (\Exception $e) {
			info($e);
		}

		// return $update_data;
	}

	public function sendOTPEMAIL($otp, $request) {
		try {
			$email_data = [
				'otp' => $otp,
			];
			$toEmail = $request->email;
			$senderSubject = 'Disaster Management OTP ' . date('d-m-Y H:i:s');
			$fromEmail = env('MAIL_USERNAME');
			Mail::send('admin.email.emailotp', ['email_data' => $email_data], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
				$message->to($toEmail)->subject($senderSubject);
				$message->from($fromEmail, 'Disaster Management OTP');
			});
			return 'ok';
		} catch (\Exception $e) {
			info($e);
		}
	}
	
	// public function checkEmailExists(Request $request) {
    

    


	public function permissionsData()
	{
		$permissions = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		return $permissions;
	}


}