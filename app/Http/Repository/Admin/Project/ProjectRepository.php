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

		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');

		$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

			if($utype=='1')
            {
            $data_user_output = $user_working_dist;
            }else if($utype=='2')
            {
                $data_user_output = $user_working_tal;
            }else if($utype=='3')
            {
                $data_user_output = $user_working_vil;
            } 

		$data_users = Project::leftJoin('tbl_area as district_project', 'projects.district', '=', 'district_project.location_id')
		->leftJoin('tbl_area as taluka_project', 'projects.taluka', '=', 'taluka_project.location_id')
		->leftJoin('tbl_area as village_project', 'projects.village', '=', 'village_project.location_id')
        //   ->where('gender.is_active', true)
          ->select(
              'projects.id',
              'projects.project_name',
              'projects.description',
              'district_project.name as district_name',
              'taluka_project.name as taluka_name',
              'village_project.name as village_name',
              'projects.start_date',
              'projects.end_date',
              'projects.latitude',
              'projects.longitude',
              'projects.is_active',
		  )
		  ->orderBy('id', 'asc');

		  if($utype=='1')
            {
				$data_users->where('projects.district', $user_working_dist);
            }else if($utype=='2')
            {
				$data_users->where('projects.taluka', $user_working_tal);
            }else if($utype=='3')
            {
				$data_users->where('projects.village', $user_working_vil);
            }
			$data_output = $data_users->get();
			// dd($data_users);
		return $data_output;
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
		$project_data->district = intval($request['district']);
		$project_data->taluka	 = intval($request['taluka']);
		$project_data->village = intval($request['village']);

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

}