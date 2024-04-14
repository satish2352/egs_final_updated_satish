<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\UsertypeRepository;

use App\Usertype;
use Carbon\Carbon;


class UsertypeServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new UsertypeRepository();
    }
    public function getAll(){
        try {
            return $this->repo->getAll();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function addAll($request) {
        try {
            $add_Usertype = $this->repo->addAll($request);
            if ($add_Usertype) {
                return ['status' => 'success', 'msg' => 'User Type Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'User Type Not Added.'];
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
    public function updateAll($request){
        try {
            $update_Usertype = $this->repo->updateAll($request);
            if ($update_Usertype) {
                return ['status' => 'success', 'msg' => 'User Type Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'User Type Not Updated.'];
            }  
        } catch (Exception $e) {
            return ['status' => 'error', 'msg' => $e->getMessage()];
        }      
    }
    public function deleteById($id){
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
   
    public function updateOne($id)
    {
        return $this->repo->updateOne($id);
    }


}