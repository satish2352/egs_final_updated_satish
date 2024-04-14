<?php
namespace App\Http\Services\Admin\Menu;

use App\Http\Repository\Admin\Menu\RoleRepository;

use App\Roles;
use Carbon\Carbon;


class RoleServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct()
    {
        $this->repo = new RoleRepository();
    }
    public function getAllRole()
    {
        try {
            return $this->repo->getAllRole();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addRole($request)
    {
        try {
            $add_role = $this->repo->addRole($request);
            if ($add_role) {
                return ['status' => 'success', 'msg' => 'Role Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Role Not Added.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    
    public function getById($id)
    {
        try {
            return $this->repo->getById($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function edit($id) {

        try {
            $user_data = $this->repo->edit($id);
            return $user_data;
        } catch (\Exception $e) {
            return $e;
        }
    }
   
   
    public function updateRole($request)
    {
        $update_role = $this->repo->updateRole($request);
        if ($update_role) {
            return ['status' => 'success', 'msg' => 'Role Updated Successfully.'];
        } else {
            return ['status' => 'error', 'msg' => 'Role Not Updated.'];
        }  
       
    }
    public function updateOneRole($id)
    {
        return $this->repo->updateOneRole($id);
    }
    public function deleteById($id)
    {
        try {
            $delete = $this->repo->deleteById($id);
            if ($delete) {
                return ['status' => 'success', 'msg' => 'Deleted Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => ' Not Deleted.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        } 
    }

    
    public function listRoleWisePermission($id)
    {
        try {
            return $this->repo->listRoleWisePermission($id);
        } catch (\Exception $e) {
            return $e;
        }
    }
   



}