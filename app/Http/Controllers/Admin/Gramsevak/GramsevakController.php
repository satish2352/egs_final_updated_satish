<?php

namespace App\Http\Controllers\Admin\Gramsevak;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Gramsevak\GramsevakServices;
use App\Models\ {
    Roles,
    Permissions,
    TblArea,
    User,
    Labour,
    LabourAttendanceMark,
    Registrationstatus,
    Reasons,
    HistoryModel,
    Project,
    HistoryDocumentModel,
    GramPanchayatDocuments,
    DocumentReasons
};
use Validator;
use session;
use Config;

class GramsevakController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new GramsevakServices();
    }

    public function index()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->index();
        return view('admin.pages.gramsevak.list-gramsevaks',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocuments(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = Reasons::where('is_active', 1)
					->where('tbl_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocuments($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }


    public function updateGramDocumentStatus(Request $request){
        // $rules = [
        //     'is_approved' => 'required',
        //  ];       

        // $messages = [   
        //                 'is_approved.required' => 'Please enter email.',
        //                 // 'email.email' => 'Please enter valid email.',
        //                 // 'u_uname.required' => 'Please enter user uname.',
        //                 // 'password.required' => 'Please enter password.',
        //             ];


        try {
            
                $register_user = $this->service->updateGramDocumentStatus($request);
                if($register_user)
                {

                    $sess_user_id=session()->get('user_id');
                    $sess_user_type=session()->get('user_type');
                    $sess_user_role=session()->get('role_id');

                if($request->is_approved=='3' && $request['other_remark']!='')
                {    
                $history = new HistoryDocumentModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->gram_document_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_doc_id = $request->reason_doc_id; 
                $history->other_remark = $request->other_remark; 
                $history->save();
                }else if($request->is_approved=='3' && $request['other_remark']=='')
                {    
                    // Create a history record
                $history = new HistoryDocumentModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->gram_document_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_doc_id = $request->reason_doc_id; 
                $history->save();
                }
    
                $showId=$request->show_id;
                // dd($showId);
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        // $user_doc_data = GramPanchayatDocuments::leftJoin('documenttype', 'documenttype.id', '=', 'tbl_gram_panchayat_documents.document_type_id')
                        // ->where('tbl_gram_panchayat_documents.user_id', $showId)
                        //     ->select('tbl_gram_panchayat_documents.id',
                        //     'tbl_gram_panchayat_documents.user_id',
                        //     'tbl_gram_panchayat_documents.document_type_id',
                        //     'tbl_gram_panchayat_documents.document_name',
                        //     'tbl_gram_panchayat_documents.document_pdf',
                        //     'tbl_gram_panchayat_documents.is_active',
                        //     'documenttype.document_type_name',
                        //     'tbl_gram_panchayat_documents.is_approved',
                        //     'tbl_gram_panchayat_documents.is_resubmitted',
                        //     'tbl_gram_panchayat_documents.reason_doc_id',
                        //     'tbl_gram_panchayat_documents.other_remark',
                        //     )
                        //     ->get();
                        //     return response()->json(['labour_attendance_ajax_data' => $user_doc_data]);

                        return true;
                    }
                    else {
                        return false;
                    }
                }
                
            // }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function ListGrampanchayatDocuments()
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();
            $data_gram_doc_details = $this->service->ListGrampanchayatDocuments();
            return view('admin.pages.gramsevak.list-grampanchayat-doc', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListGrampanchayatDocumentsNew()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->ListGrampanchayatDocumentsNew();
        // dd($gramsevaks);
        return view('admin.pages.gramsevak.list-grampanchayat-doc-new',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocumentsNew(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocumentsNew($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc-new', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListSendedForApproval()
    {

        $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
        ->where('id', '!=', 1)
        ->select('id','status_name')
        ->get()
        ->toArray();

        $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
        ->select('id','reason_name')
        ->get()
        ->toArray();

        $data_gram_doc_details = $this->service->ListSendedForApproval();
        // dd($data_gram_doc_details);
        return view('admin.pages.gramsevak.list-sended-for-approval', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
    }

    public function ListGrampanchayatDocumentsApproved()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->ListGrampanchayatDocumentsApproved();
        // dd($gramsevaks);
        return view('admin.pages.gramsevak.list-grampanchayat-doc-approved',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocumentsApproved(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocumentsApproved($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc-approved', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListApprovedDocuments()
    {

        $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
        ->where('id', '!=', 1)
        ->select('id','status_name')
        ->get()
        ->toArray();

        $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
        ->select('id','reason_name')
        ->get()
        ->toArray();

        $data_gram_doc_details = $this->service->ListApprovedDocuments();
        // dd($data_gram_doc_details);
        return view('admin.pages.gramsevak.list-approved-documents', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
    }

    public function ListGrampanchayatDocumentsNotApproved()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->ListGrampanchayatDocumentsNotApproved();
        // dd($gramsevaks);
        return view('admin.pages.gramsevak.list-grampanchayat-doc-not-approved',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocumentsNotApproved(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocumentsNotApproved($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc-not-approved', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListNotApprovedDocuments()
    {

        $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
        ->where('id', '!=', 1)
        ->select('id','status_name')
        ->get()
        ->toArray();

        $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
        ->select('id','reason_name')
        ->get()
        ->toArray();

        $data_gram_doc_details = $this->service->ListNotApprovedDocuments();
        // dd($data_gram_doc_details);
        return view('admin.pages.gramsevak.list-not-approved-documents', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
    }

    public function ListGrampanchayatDocumentsResubmitted()
    {

        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');
        
        $district_data = TblArea::where('parent_id', '2')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

        $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);

                    $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                    ->where('users.id', $sess_user_id)
                    ->first();
    
                $utype=$data_output->user_type;
                $user_working_dist=$data_output->user_district;
                $user_working_tal=$data_output->user_taluka;
                $user_working_vil=$data_output->user_village;
    
    
                    if($utype=='1')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.district',$user_working_dist)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='2')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.taluka',$user_working_tal)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else if($utype=='3')
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->where('projects.village',$user_working_vil)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                }else
                {
                    $dynamic_projects = Project::where('is_active', 1)
                            ->orderBy('project_name', 'asc')
                            ->get(['id', 'project_name']);
                } 
                
        $gramsevaks = $this->service->ListGrampanchayatDocumentsResubmitted();
        // dd($gramsevaks);
        return view('admin.pages.gramsevak.list-grampanchayat-doc-resubmitted',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocumentsResubmitted(Request $request)
    {
        try {

            $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
            ->where('id', '!=', 1)
            ->select('id','status_name')
            ->get()
            ->toArray();

            $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
            ->select('id','reason_name')
            ->get()
            ->toArray();

            $data_gram_doc_details = $this->service->showGramsevakDocumentsResubmitted($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc-resubmitted', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function ListResubmittedDocuments()
    {

        $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
        ->where('id', '!=', 1)
        ->select('id','status_name')
        ->get()
        ->toArray();

        $dynamic_reasons = DocumentReasons::where('is_active', 1)
					->where('tbl_doc_reason.is_deleted', 0)
        ->select('id','reason_name')
        ->get()
        ->toArray();

        $data_gram_doc_details = $this->service->ListResubmittedDocuments();
        // dd($data_gram_doc_details);
        return view('admin.pages.gramsevak.list-resubmitted-documents', compact('data_gram_doc_details','dynamic_registrationstatus','dynamic_reasons'));
    }

    public function ListGrampanchayatDocumentsNewFilter(Request $request)
	{
		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
        $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');

		$doc_output = GramPanchayatDocuments::where('tbl_gram_panchayat_documents.is_approved', '1')
		->where('tbl_gram_panchayat_documents.is_resubmitted', '0')
		->select('user_id')
		->get()
		->toArray();
		if($sess_user_role=='1')
		{

            
			
		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($query) use ($request) {
                    $query->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');

                $data_output = $data_users->get();
		}else if($sess_user_role=='2')
		{
			$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village; 


		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($data_users) use ($request) {
                    $data_users->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($data_users) use ($request) {
                    $data_users->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($data_users) use ($request) {
                    $data_users->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');
                $data_output = $data_users->get();

		}		
        return response()->json(['documents_ajax_data' => $data_output]);
		// return $data_users;/
	}
    public function ListGrampanchayatDocumentsApprovedFilter(Request $request)
	{
		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
        $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');

		$doc_output = GramPanchayatDocuments::where('tbl_gram_panchayat_documents.is_approved', '2')
				->select('user_id')
                ->get()
				->toArray();
		if($sess_user_role=='1')
		{

            
			
		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($query) use ($request) {
                    $query->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');

                $data_output = $data_users->get();
		}else if($sess_user_role=='2')
		{
			$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            // }         


		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($data_users) use ($request) {
                    $data_users->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($data_users) use ($request) {
                    $data_users->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($data_users) use ($request) {
                    $data_users->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');
                $data_output = $data_users->get();

		}		
        return response()->json(['documents_ajax_data' => $data_output]);
		// return $data_users;/
	}


    public function ListGrampanchayatDocumentsNotApprovedFilter(Request $request)
	{
		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
        $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');

		$doc_output = GramPanchayatDocuments::where('tbl_gram_panchayat_documents.is_approved', '3')
				->select('user_id')
                ->get()
				->toArray();
		if($sess_user_role=='1')
		{

            
			
		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($query) use ($request) {
                    $query->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');

                $data_output = $data_users->get();
		}else if($sess_user_role=='2')
		{
			$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            // }         


		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($data_users) use ($request) {
                    $data_users->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($data_users) use ($request) {
                    $data_users->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($data_users) use ($request) {
                    $data_users->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');
                $data_output = $data_users->get();

		}		
        return response()->json(['documents_ajax_data' => $data_output]);
		// return $data_users;/
	}
   
    public function ListGrampanchayatDocumentsResubmittedFilter(Request $request)
	{
		$sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
        $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');

		$doc_output = GramPanchayatDocuments::where('tbl_gram_panchayat_documents.is_approved', '1')
				->where('tbl_gram_panchayat_documents.is_resubmitted', '1')
				->select('user_id')
                ->get()
				->toArray();

		if($sess_user_role=='1')
		{

            
			
		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($query) use ($request) {
                    $query->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');

                $data_output = $data_users->get();
		}else if($sess_user_role=='2')
		{
			$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            // }         


		$data_users = User::leftJoin('roles', 'roles.id', '=', 'users.role_id')
				->leftJoin('tbl_area as district_user', 'users.user_district', '=', 'district_user.location_id')
				->leftJoin('tbl_area as taluka_user', 'users.user_taluka', '=', 'taluka_user.location_id')
				->leftJoin('tbl_area as village_user', 'users.user_village', '=', 'village_user.location_id')
				->where('users.role_id','3')
                // ->whereIn('users.id',$data_user_output)
                ->whereIn('users.id',$doc_output)
                ->when($request->get('districtId'), function($data_users) use ($request) {
                    $data_users->where('users.user_district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($data_users) use ($request) {
                    $data_users->where('users.user_taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($data_users) use ($request) {
                    $data_users->where('users.user_village', $request->villageId);
                })
				->select('users.id','users.f_name','users.m_name','users.l_name','users.email','users.number','users.aadhar_no',
				'users.address','users.pincode','users.user_profile','roles.role_name',
				'district_user.name as district','taluka_user.name as taluka','village_user.name as village');
                $data_output = $data_users->get();

		}		
        return response()->json(['documents_ajax_data' => $data_output]);
		// return $data_users;/
	}

   
}