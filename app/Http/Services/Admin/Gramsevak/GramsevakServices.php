<?php
namespace App\Http\Services\Admin\Gramsevak;

use App\Http\Repository\Admin\Gramsevak\GramsevakRepository;


use App\Models\
{ 
    User,
    Labour 
};
use Carbon\Carbon;
use Config;
use Storage;

class GramsevakServices
{

	protected $repo;

    /**
     * TopicService constructor.
     */
    public function __construct() {
        $this->repo = new GramsevakRepository();
    }

    public function index() {
        $data_gramsevaks = $this->repo->getGramsevakList();
        return $data_gramsevaks;
    }

    public function showGramsevakDocuments($id){
        try {
            return $this->repo->showGramsevakDocuments($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateGramDocumentStatus($request) {
        $user_register_id = $this->repo->updateGramDocumentStatus($request);
        return ['status'=>'success','msg'=>'Data Updated Successful.'];
    }

    public function ListGrampanchayatDocuments(){
        try {
            return $this->repo->ListGrampanchayatDocuments();
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function ListGrampanchayatDocumentsNew(){
        try {
            return $this->repo->ListGrampanchayatDocumentsNew();
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function showGramsevakDocumentsNew($id){
        try {
            return $this->repo->showGramsevakDocumentsNew($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListSendedForApproval(){
        try {
            return $this->repo->ListSendedForApproval();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListGrampanchayatDocumentsApproved(){
        try {
            return $this->repo->ListGrampanchayatDocumentsApproved();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showGramsevakDocumentsApproved($id){
        try {
            return $this->repo->showGramsevakDocumentsApproved($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListApprovedDocuments(){
        try {
            return $this->repo->ListApprovedDocuments();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListGrampanchayatDocumentsNotApproved(){
        try {
            return $this->repo->ListGrampanchayatDocumentsNotApproved();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showGramsevakDocumentsNotApproved($id){
        try {
            return $this->repo->showGramsevakDocumentsNotApproved($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListNotApprovedDocuments(){
        try {
            return $this->repo->ListNotApprovedDocuments();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListGrampanchayatDocumentsResubmitted(){
        try {
            return $this->repo->ListGrampanchayatDocumentsResubmitted();
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function showGramsevakDocumentsResubmitted($id){
        try {
            return $this->repo->showGramsevakDocumentsResubmitted($id);
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListResubmittedDocuments(){
        try {
            return $this->repo->ListResubmittedDocuments();
        } catch (\Exception $e) {
            return $e;
        }
    }


}