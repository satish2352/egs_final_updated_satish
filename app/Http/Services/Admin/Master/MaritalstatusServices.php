<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\MaritalstatusRepository;

use App\Maritalstatus;
use Carbon\Carbon;


class MaritalstatusServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new MaritalstatusRepository();
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
            $add_Maritalstatus = $this->repo->addAll($request);
            if ($add_Maritalstatus) {
                return ['status' => 'success', 'msg' => 'Marital Status Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Marital Status Not Added.'];
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
            $update_Maritalstatus = $this->repo->updateAll($request);
            if ($update_Maritalstatus) {
                return ['status' => 'success', 'msg' => 'Marital Status Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Marital Status Not Updated.'];
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