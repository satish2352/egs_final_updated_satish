<?php
namespace App\Http\Repository\Admin\ProjectUser;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
use Session;
use App\Models\{
	User,
	Permissions,
	RolesPermissions,
	Roles,
	Project,
	ProjectUser,
	Usertype
};
use Illuminate\Support\Facades\Mail;

class ProjectUserRepository
{

	public function getProjectsList() {
		// dd('hhhhhh');
		 $data_project_users = ProjectUser::leftJoin('users', 'users.id', '=', 'project_users.user_id')
		 ->leftJoin('projects', 'projects.id', '=', 'project_users.project_id')
		 ->leftJoin('usertype', 'usertype.id', '=', 'project_users.user_type_id')
		 //   ->where('gender.is_active', true)
		   ->select(
			   'project_users.id',
			   'project_users.user_type_id', 
			   'project_users.project_id', 
			   'projects.project_name',
			   'usertype.usertype_name',
			   'users.f_name',
			   'users.m_name',
			   'users.l_name',
			   'users.is_active',
		   )->get();
			//  dd($data_project_users);
		 return $data_project_users;
	 }

	

	public function addProjectUser($request)
	{
		try {
		$data =array();
		$project_user_data = new ProjectUser();
		$project_user_data->project_id = $request['project_id'];
		$project_user_data->user_type_id = $request['user_type_id'];
		$project_user_data->user_id = $request['user_id'];
		$project_user_data->is_active = isset($request['is_active']) ? true : false;
		$project_user_data->save();
// dd($project_user_data);
		$last_insert_id = $project_user_data->id;
        return $project_user_data;
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
		$user_data = ProjectUser::where('id',$request['edit_id']) 
						->update([
							// 'u_uname' => $request['u_uname'],
							'project_id' => $request['project_id'],
							'user_type_id' => $request['user_type_id'],
							'user_id' => $request['user_id'],
						]);
		
		// $this->updateRolesPermissions($request, $request->edit_id);
		return $request->edit_id;
	}

	public function editProjectUsers($reuest)
	{

		$data_projects = [];
		$data_projects['permissions'] = Permissions::where('is_active', true)
			->select('id', 'route_name', 'permission_name', 'url')
			->get()
			->toArray();

		$data_projects['project'] = Project::where('is_active', true)
			->select('id', 'project_name')
			->get()
			->toArray();

		$data_projects['user_type'] = Usertype::where('is_active', true)
			->select('id', 'usertype_name')
			->get()
			->toArray();

		$data_project_users_data = ProjectUser::where('project_users.id', '=', base64_decode($reuest->edit_id))
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				'project_users.id',
				'project_users.project_id',
				'project_users.user_type_id',
				'project_users.user_id',
				'project_users.is_active',
			)->get()
			->toArray();
		$prev_pro_id=$data_project_users_data[0]['project_id'];
		$prev_user_type_id=$data_project_users_data[0]['user_type_id'];

		$old_pro_data = Project::where('is_active', true)
			->where('projects.id', $prev_pro_id)
			->select('id', 'project_name','District','taluka','village')
			->get()
			->toArray();

		if($prev_user_type_id=='1'){
			$data_projects['user_data'] = User::where('users.user_district', '=',$old_pro_data[0]['District'])
			// ->where('roles_permissions.is_active','=',true)
			// ->where('users.is_active','=',true)
			->select(
				'id',
				'f_name',
				'm_name',
				'l_name',
			)->get()
			->toArray();
		}	
						
		$data_projects['data_project_users'] = $data_project_users_data[0];
		// dd($data_projects);

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
		try {
			$data_users = Project::leftJoin('tbl_area as state_project', 'projects.state', '=', 'state_project.location_id')
		->leftJoin('tbl_area as district_project', 'projects.district', '=', 'district_project.location_id')
		->leftJoin('tbl_area as taluka_project', 'projects.taluka', '=', 'taluka_project.location_id')
		->leftJoin('tbl_area as village_project', 'projects.village', '=', 'village_project.location_id')
				->where('projects.id', $id)
				->select('projects.id',
				'projects.project_name',
				'projects.description',
				'state_project.name as state_name',
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
            $project_user_data = ProjectUser::find($request); // Assuming $request directly contains the ID
			// dd($project_data);
            // Assuming 'is_active' is a field in the userr model
            if ($project_user_data) {
                $is_active = $project_user_data->is_active === 1 ? 0 : 1;
				// dd($is_active);
                $project_user_data->is_active = $is_active;
                $project_user_data->save();

                return [
                    'msg' => 'Project Wise User updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Project Wise User not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update Project Wise User.',
                'status' => 'error'
            ];
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