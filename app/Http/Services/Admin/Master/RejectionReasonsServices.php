<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\RejectionReasonsRepository;

use App\Reasons;
use Carbon\Carbon;


class RejectionReasonsServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new RejectionReasonsRepository();
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
            $add_Registrationstatus = $this->repo->addAll($request);
            if ($add_Registrationstatus) {
                return ['status' => 'success', 'msg' => 'Rejection Reason Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Rejection Reason Not Added.'];
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
            $update_Registrationstatus = $this->repo->updateAll($request);
            if ($update_Registrationstatus) {
                return ['status' => 'success', 'msg' => 'Registration Status Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Registration Status Not Updated.'];
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