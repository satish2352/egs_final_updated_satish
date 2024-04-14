<?php
namespace App\Http\Services\Admin\Master;

use App\Http\Repository\Admin\Master\SkillsRepository;

use App\Skills;
use Carbon\Carbon;


class SkillsServices{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct(){
        $this->repo = new SkillsRepository();
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
            $add_Skills = $this->repo->addAll($request);
            if ($add_Skills) {
                return ['status' => 'success', 'msg' => 'Skills Added Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Skills Not Added.'];
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
            $update_Skills = $this->repo->updateAll($request);
            if ($update_Skills) {
                return ['status' => 'success', 'msg' => 'Skills Updated Successfully.'];
            } else {
                return ['status' => 'error', 'msg' => 'Skills Not Updated.'];
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