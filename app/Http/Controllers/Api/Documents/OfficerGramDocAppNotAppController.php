<?php

namespace App\Http\Controllers\Api\Documents;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
	Documenttype,
    GramPanchayatDocuments,
    HistoryDocumentModel,
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class OfficerGramDocAppNotAppController extends Controller
{
    public function getReceivedDocumentListForAppNotApp(Request $request){
        try {
            $user = auth()->user()->id;
            $is_approved = '' ;
            $is_resubmitted = ''; 
            $fromDate = date('Y-m-d', strtotime($request->input('from_date')));
            $fromDate =  $fromDate.' 00:00:01';
            $toDate = date('Y-m-d', strtotime($request->input('to_date')));
            $toDate =  $toDate.' 23:59:59';

            $page = isset($request["start"]) ? $request["start"] : Config::get('DocumentConstant.GRAM_DOCUMENT_DEFAULT_START') ;
        // $rowperpage = isset($request["length"])? $request["length"] : Config::get('DocumentConstant.GRAM_DOCUMENT_DEFAULT_LENGTH') ; // Rows display per page
        $rowperpage = GRAM_DOCUMENT_DEFAULT_LENGTH;
            $start = ($page - 1) * $rowperpage;

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $user)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;
            
            $data_user_output = User::select('id');
            if($utype=='1')
            {
                $data_user_output = $data_user_output->where('users.user_district', $user_working_dist);
            } else if($utype=='2')
            {
                $data_user_output = $data_user_output->where('users.user_taluka', $user_working_tal);
            } else if($utype=='3')
            {
                $data_user_output = $data_user_output->where('users.user_village', $user_working_vil);
            }

            $data_user_output = $data_user_output->get()->toArray();    
            
            if($request->has('is_approved') && $request->is_approved == 'received' && $request->has('is_resubmitted') && $request->is_resubmitted == 'resubmitted') {  //1
                $is_approved = 1 ;
                $is_resubmitted = 0 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'not_approved') { //3
                $is_approved = 3 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'approved') { //3
                $is_approved = 2 ;
            } 
            elseif($request->has('is_resubmitted') && $request->is_resubmitted == 'resubmitted' && $request->has('is_approved') && $request->is_approved == 'resend') { 
                $is_resubmitted = 1 ;
                $is_approved = 1 ;
            } 
                $basic_query_object = GramPanchayatDocuments::leftJoin('registrationstatus', 'tbl_gram_panchayat_documents.is_approved', '=', 'registrationstatus.id')
            ->leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
            ->leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
            ->leftJoin('tbl_area as district_u', 'users.user_district', '=', 'district_u.location_id')
            ->leftJoin('tbl_area as taluka_u', 'users.user_taluka', '=', 'taluka_u.location_id')
            ->leftJoin('tbl_area as village_u', 'users.user_village', '=', 'village_u.location_id')
                ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
                ->when($request->has('document_type_name'), function($query) use ($request) {
                    $query->where('tbl_documenttype.document_type_name', 'like', '%' . $request->document_type_name . '%');
                })
                ->when($request->has('gram_document_id'), function($query) use ($request) {
                    $query->where('tbl_gram_panchayat_documents.id',$request->gram_document_id);
                })
                ->when($request->has('is_approved'), function($query) use ($is_approved) {
                    $query->where('tbl_gram_panchayat_documents.is_approved', $is_approved);
                })
                ->when($request->has('is_resubmitted'), function($query) use ($is_resubmitted) {
                    $query->where('tbl_gram_panchayat_documents.is_resubmitted', $is_resubmitted);
                })
                ->when($request->get('from_date'), function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('tbl_gram_panchayat_documents.updated_at', [$fromDate, $toDate]);
                });
                if ($request->has('district_id')) {
                    $basic_query_object->where('district_u.location_id', $request->input('district_id'));
                }
                if ($request->has('taluka_id')) {
                    $basic_query_object->where('taluka_u.location_id', $request->input('taluka_id'));
                }
                if ($request->has('village_id')) {
                    $basic_query_object->where('village_u.location_id', $request->input('village_id'));
                }

                $totalRecords = $basic_query_object->select('tbl_gram_panchayat_documents.id')->get()->count();

                $data_output = $basic_query_object->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_documenttype.document_type_name',
                    'tbl_gram_panchayat_documents.document_pdf',
                        User::raw("CONCAT(users.f_name, IFNULL(CONCAT(' ', users.m_name), ''),' ', users.l_name) AS gramsevak_full_name"),
                    'users.user_district',
                    'district_u.name as district_name',
                    'users.user_taluka',
                    'taluka_u.name as taluka_name',
                    'users.user_village',
                    'village_u.name as village_name',
                    'registrationstatus.status_name',
                    GramPanchayatDocuments::raw("CONVERT_TZ(tbl_gram_panchayat_documents.updated_at, '+00:00', '+05:30') as updated_at"), 
                )->skip($start)
                ->take($rowperpage)
                ->orderBy('id', 'desc')
                ->get();

                foreach ($data_output as $document_data) {
                    $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
                }
                foreach ($data_output as &$documenthistory) {
                    $documenthistory['history_details'] = HistoryDocumentModel::leftJoin('roles', 'tbl_doc_history.roles_id', '=', 'roles.id')
                        ->leftJoin('users', 'tbl_doc_history.user_id', '=', 'users.id')
                        ->leftJoin('tbl_doc_reason', 'tbl_doc_history.reason_doc_id', '=', 'tbl_doc_reason.id')
                        ->leftJoin('tbl_gram_panchayat_documents', 'tbl_doc_history.gram_document_id', '=', 'tbl_gram_panchayat_documents.id')
                        ->select(
                            'tbl_doc_history.id',
                            'roles.role_name as role_name',
                            'users.f_name as f_name',
                            'tbl_doc_reason.reason_name as reason_name',
                            'tbl_doc_history.other_remark',
                            'tbl_doc_history.updated_at',
                        )
                        ->where('tbl_doc_history.gram_document_id', $documenthistory['id'])
                        ->get();
                }
                if(sizeof($data_output)>=1) {
                    $totalPages = ceil($totalRecords/$rowperpage);
                } else {
                    $totalPages = 1;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateDocumentStatusApproved(Request $request){
        try {
            $user = auth()->user()->id;
                // Validate the incoming request
            $validator = Validator::make($request->all(), [
                'gram_document_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
            
        
            $updated = GramPanchayatDocuments::where('id', $request->gram_document_id)
                ->where('is_approved', 1)
                ->update(['is_approved' => 2,'is_resubmitted'=> 0]); 
                
    
            if ($updated) {
                return response()->json(['status' => 'true', 'message' => 'Document status updated successfully'], 200);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No document found with the provided Id'], 200);
            }
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed','error' => $e->getMessage()], 500);
        }
    }
    public function updateDocumentStatusNotApproved(Request $request) {
        try {
            $user = auth()->user();
            
            $gram_document_id = $request->input('gram_document_id'); 
        
        
            $validator = Validator::make($request->all(), [
                'gram_document_id' => 'required',
                'reason_doc_id' => 'required',
                'is_approved' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
    
            // Update labor entry
            $updateData = [
                'is_approved' => 3,
                'is_resubmitted' => 0,
                'reason_doc_id' => $request->reason_doc_id, 
            ];
    
            // Include 'other_remark' in the update data if it's provided
            if ($request->has('other_remark')) {
                $updateData['other_remark'] = $request->other_remark ?: ''; // Set to empty string if not provided
            }
    
            $updated = GramPanchayatDocuments::where('id', $request->gram_document_id)
                ->where('is_approved', 1)
                ->update($updateData);
            if ($updated) {
                // Create a history record
                $history = new HistoryDocumentModel();
                $history->user_id = $user->id; 
                $history->roles_id = $user->role_id; 
                $history->gram_document_id = $request->gram_document_id;
                $history->is_approved = $request->is_approved;
                $history->reason_doc_id = $request->reason_doc_id; 
                
                if ($request->has('other_remark')) {
                    $history->other_remark = $request->other_remark ?: 'null'; // Set to empty string if not provided
                }
    
                $history->save();
    
                return response()->json(['status' => 'true', 'message' => 'Document status updated successfully'], 200);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No document found with the provided Document Id or status is not approved'], 200);
            }
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function countOfficerDocument(Request $request) {
        try {
            $user = auth()->user()->id;

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $user)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            $data_user_output = User::select('id');
            if($utype=='1') {
                $data_user_output = $data_user_output->where('users.user_district', $user_working_dist);
            } else if($utype=='2') {
                $data_user_output = $data_user_output->where('users.user_taluka', $user_working_tal);
            } else if($utype=='3') {
                $data_user_output = $data_user_output->where('users.user_village', $user_working_vil);
            }

            $data_user_output = $data_user_output->get()->toArray();  

            $counts = GramPanchayatDocuments::leftJoin('users', 'tbl_gram_panchayat_documents.user_id', '=', 'users.id')
                ->whereIn('users.id', $data_user_output)
                ->selectRaw('is_approved, COUNT(*) as count')
                ->groupBy('is_approved')
                ->get();

    
            // Initialize counters
            $sentForApprovalCount = 0;
            $approvedCount = 0;
            $notApprovedCount = 0;
    
            // Counting each status
            foreach ($counts as $count) {
                if ($count->is_approved == 1) {
                    $sentForApprovalCount = $count->count;
                } elseif ($count->is_approved == 2) {
                    $approvedCount = $count->count;
                } elseif ($count->is_approved == 3) {
                    $notApprovedCount = $count->count;
                }
            }
    
            // Return the counts in the response
            return response()->json([
                'status' => 'true',
                'message' => 'Counts retrieved successfully',
                'sent_for_approval_count' => $sentForApprovalCount,
                'approved_count' => $approvedCount,
                'not_approved_count' => $notApprovedCount
            ], 200);
    
        } catch (\Exception $e) {
            // Return error if any exception occurs
            return response()->json(['status' => 'false', 'message' => 'Error occurred', 'error' => $e->getMessage()], 500);
        }
    }
}
