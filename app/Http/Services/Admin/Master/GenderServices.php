<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\GenderRepository;

use App\Gender;
use Carbon\Carbon;


class GenderServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new GenderRepository();
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
            $add_Gender = $this->repo->addAll($request);
            if ($add_Gender) {
                return ['status' => 'success', 'msg' => 'Gender Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Gender Not Added.'];
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
            $update_Gender = $this->repo->updateAll($request);
            if ($update_Gender) {
                return ['status' => 'success', 'msg' => 'Gender Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Gender Not Updated.'];
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