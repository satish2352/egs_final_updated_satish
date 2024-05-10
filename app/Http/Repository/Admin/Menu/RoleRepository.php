<?php
namespace App\Http\Repository\Admin\Menu;

use Illuminate\Database\QueryException;
use DB;
use Illuminate\Support\Carbon;
// use Session;
use App\Models\ {
	Roles,
    Permissions,
    RolesPermissions

};

class RoleRepository  {
	public function getAllRole()
    {
        try {
            return Roles::all();
        } catch (\Exception $e) {
            return $e;
        }
    }

	public function addRole($request) {
        try {
            $role_data = new Roles();
            $role_data->role_name = $request['role_name'];
            $role_data->save();  
            $last_insert_id = $role_data->id;    
           
            $this->insertRolesPermissions($request, $last_insert_id); 
        
            return $role_data;
        } catch (\Exception $e) {
            return [
                'msg' => $e,
                'status' => 'error'
            ];
        }
    }

    private function insertRolesPermissions($request, $last_insert_id) {
        $permissions_data_from_table  = $this->permissionsData();
        

        foreach ($permissions_data_from_table as $key => $data) {
            $permission_id  = 'permission_id_'.$data['id'];
            $per_list  = 'per_list_'.$data['id'];
            $per_add  = 'per_add_'.$data['id'];
            $per_update  = 'per_update_'.$data['id'];
            $per_delete  = 'per_delete_'.$data['id'];
            
            if($request->has($permission_id) && ($request->has($per_list) || $request->has($per_add) || $request->has($per_update) || $request->has($per_delete))) {
                $permissions_data = new RolesPermissions();
                $permissions_data->permission_id = $data['id'];
                $permissions_data->role_id = $last_insert_id;
                
                if($request->has($per_list)) {
                    $permissions_data->per_list  = true;
                }

                if($request->has($per_add)) {
                    $permissions_data->per_add  = true;
                }
                
                if($request->has($per_update)) {
                    $permissions_data->per_update  = true;
                }
                
                if($request->has($per_delete)) {
                    $permissions_data->per_delete  = true;
                }
                $permissions_data->save();
            }
        }
        return "ok";
    }

    public function permissionsData() {
		$permissions = Permissions::where('is_active', true)
						->select('id','route_name','permission_name','url')
						->get()
						->toArray();

		return $permissions;
	}

    public function getById($id) {
        try {
            return Roles::find($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function edit($id) {
        try {
            $data_users = [];
            $data_users['roles'] = Roles::where('id',$id)->first();
            $data_users['permissions'] = Permissions::where('is_active', true)
                                ->select('id','route_name','permission_name','url')
                                ->get()
                                ->toArray();


            $data_users['permissions_user'] = RolesPermissions::join('permissions', function($join) {
							$join->on('roles_permissions.permission_id', '=', 'permissions.id');
						})
						->where('roles_permissions.role_id','=',$id)
						->where('roles_permissions.is_active','=',true)
						// ->where('users.is_active','=',true)
						->select(
							'roles_permissions.per_list',
							'roles_permissions.per_add',
							'roles_permissions.per_update',
							'roles_permissions.per_delete',
							'permissions.id as permissions_id'
							)->get()
							->toArray();

            if ($data_users) {
                return $data_users;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to get by id budget.',
                'status' => 'error'
            ];
        }
    }
    public function updateRole($request) {
        try {
            //  dd($request['edit_id']);
            $role_data = Roles::find($request['edit_id']);
            $role_data->role_name = $request['role_name'];       
            $role_data->is_active = isset($request['is_active']) ? true :false;    
            $role_data->update();  

            $this->updateRolesPermissions($request, $request['edit_id']);

        
            return [
                'msg' => 'Marquee updated successfully.',
                'status' => 'success'
            ];
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to update marquee.',
                'status' => 'error'
            ];
        }
    }

    private function updateRolesPermissions($request, $role_id) {

		$permissions_data_from_table  = $this->permissionsData();
        
		$update_data = array();
		foreach ($permissions_data_from_table as $key => $data) {
			$permission_id  = 'permission_id_'.$data['id'];
			$per_list  = 'per_list_'.$data['id'];
			$per_add  = 'per_add_'.$data['id'];
			$per_update  = 'per_update_'.$data['id'];
			$per_delete  = 'per_delete_'.$data['id'];

			$update_data['role_id'] = $role_id;
            if($request->has($per_list)) {
				$update_data['per_list']  = true;
			} else {
				$update_data['per_list']  = false;
			}

			if($request->has($per_add)) {
				$update_data['per_add']  = true;
			} else {
				$update_data['per_add']  = false;
			}
			
			if($request->has($per_update)) {
				$update_data['per_update']  = true;
			} else {
				$update_data['per_update']  = false;
			}
			
			if($request->has($per_delete)) {
				$update_data['per_delete']  = true;
			} else {
				$update_data['per_delete']  = false;
			}

            $permissions_data_all = RolesPermissions::where([
					'role_id' => $role_id,
					'permission_id' =>$data['id']
				])->get()->toArray();
                info($permissions_data_all);
				if(sizeof($permissions_data_all)>0) {

					$permissions_data = RolesPermissions::where([
						'role_id' => $role_id,
						'permission_id' =>$data['id']
					])->update($update_data);
				} else {
                    // dd($request->has($per_add));
                    if($request->has($per_list) || $request->has($per_add) || $request->has($per_update) || $request->has($per_delete)) {
                        $update_data['role_id']  = $role_id;
                        $update_data['permission_id']  = $data['id'];
                        $permissions_data = RolesPermissions::insert($update_data);
                    }
				}
		}
		return "ok";
	}


    public function updateOneRole($request) {
        try {
            $role = Roles::find($request); // Assuming $request directly contains the ID        
            if ($role) {
                $is_active = $role->is_active == 1 ? 0 : 1;
                $role->is_active = $is_active;
                // dd($marquee);
                $role->save();

                return [
                    'msg' => 'Marquee updated successfully.',
                    'status' => 'success'
                ];
            }

            return [
                'msg' => 'Marquee not found.',
                'status' => 'error'
            ];
        } catch (\Exception $e) {
            return [
                'msg' => 'Failed to update slide.',
                'status' => 'error'
            ];
        }
    }


    public function deleteById($id) {
        try {
            $role = Roles::destroy($id);
            if ($role) {
                return $role;
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return $e;
            return [
                'msg' => 'Failed to delete marquee.',
                'status' => 'error'
            ];
        }
    }

    public function listRoleWisePermission($id)
    {
        try {
            return RolesPermissions::leftjoin('permissions', function($join) {
                $join->on('roles_permissions.permission_id', '=', 'permissions.id');
                })->where('role_id',$id)->get()->toArray();
        } catch (\Exception $e) {
            return $e;
        }
    }

}