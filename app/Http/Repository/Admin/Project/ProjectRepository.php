<?php
namespace App\Http\Repository\Admin\Project;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles,
	Project
};
use Illuminate\Support\Facades\Mail;

class ProjectRepository
{

	public function getProjectsList() {
        // $data_users = Projects::join('tbl_area', function($join) {
		// 					$join->on('projects.role_id', '=', 'roles.id');
		// 				})
		// 				// ->where('users.is_active','=',true)
		// 				->select('roles.role_name',
		// 						'users.email',
		// 						'users.f_name',
		// 						'users.m_name',
		// 						'users.l_name',
		// 						'users.number',
		// 						'users.imei_no',
		// 						'users.aadhar_no',
		// 						'users.address',
		// 						'users.state',
		// 						'users.district',
		// 						'users.taluka',
		// 						'users.village',
		// 						'users.pincode',
		// 						'users.id',
		// 						'users.is_active'
		// 					)->get();
							// ->toArray();

		$data_users = Project::leftJoin('tbl_area as district_project', 'projects.district', '=', 'district_project.location_id')
		->leftJoin('tbl_area as taluka_project', 'projects.taluka', '=', 'taluka_project.location_id')
		->leftJoin('tbl_area as village_project', 'projects.village', '=', 'village_project.location_id')
        //   ->where('gender.is_active', true)
          ->select(
              'projects.id',
              'projects.project_name',
              'projects.description',
            //   'labour.gender_name',
              'district_project.name as district_name',
              'taluka_project.name as taluka_name',
              'village_project.name as village_name',
              'projects.start_date',
              'projects.end_date',
              'projects.latitude',
              'projects.longitude',
              'projects.is_active',
          )->get();
			// dd($data_users);
		return $data_users;
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
		$project_data = new Project();
		$project_data->project_name = $request['project_name'];
		$project_data->state = '';
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
		$user_data = Project::where('id',$request['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'project_name' => $request['project_name'],
							'state' => '',
							'district' => $request['district'],
							'taluka' => $request['taluka'],
							'village' => $request['village'],
							'latitude' => $request['latitude'],
							'longitude' => $request['longitude'],
							'start_date' => $request['start_date'],
							'end_date' => $request['end_date'],
							'description' => $request['description'],
							'is_active' => isset($request['is_active']) ? true :false,
						]);
		
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}


	public function checkDupCredentials($request)
	{
		return User::where('email', '=', $request['email'])
			// ->orWhere('u_uname','=',$request['u_uname'])
			->select('id')->get();
	}

	public function editProjects($reuest)
	{

		$data_projects = [];

		$data_projects['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_projects_data = Project::where('projects.id', '=', base64_decode($reuest->edit_id))
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				// 'roles.id as role_id',
				'projects.project_name',
				'projects.description',
				'projects.state',
				'projects.district',
				'projects.taluka',
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
            $project = Project::find($id);
			// dd($project);
            if ($project) {
              
                $project->delete();
               
                return $project;
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
			$data_users = Project::leftJoin('tbl_area as district_project', 'projects.district', '=', 'district_project.location_id')
		->leftJoin('tbl_area as taluka_project', 'projects.taluka', '=', 'taluka_project.location_id')
		->leftJoin('tbl_area as village_project', 'projects.village', '=', 'village_project.location_id')
				->where('projects.id', $id)
				->select('projects.id',
				'projects.project_name',
				'projects.description',
				'district_project.name as district_name',
				'taluka_project.name as taluka_name',
				'village_project.name as village_name',
				'projects.start_date',
				'projects.end_date',
				'projects.latitude',
				'projects.longitude',)
				->first();
	
			if ($data_users) {
				return $data_users;
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
            $project_data = Project::find($request); // Assuming $request directly contains the ID
			// dd($project_data);
            // Assuming 'is_active' is a field in the userr model
            if ($project_data) {
                $is_active = $project_data->is_active === 1 ? 0 : 1;
				// dd($is_active);
                $project_data->is_active = $is_active;
                $project_data->save();

                return [
                    'msg' => 'Project updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Project not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Project.',
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
    //     $userEmail = $request->input('email');
      
    //     $user = User::where('email', $userEmail)->first();
      
    //     if ($user) {
    //       return response()->json([
    //         'success' => false,
    //         'message' => 'This Email already exists.',
    //       ]);
    //     } else {
    //       return response()->json([
    //         'success' => true,
    //         'message' => 'This Email does not exist.',
    //       ]);
    //     }
    // }
}