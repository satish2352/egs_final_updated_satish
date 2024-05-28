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
    HistoryDocumentModel
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;

class GramPanchayatDocumentController extends Controller
{
    public function add(Request $request){
        $all_data_validation = [
            'document_type_id' => 'required',  
            'document_name' => 'required', 
            'document_pdf' => 'required|mimes:pdf|min:1|max:25600', 
            'latitude' => ['required', 'between:-90,90'], 
            'longitude' => ['required', 'between:-180,180'], 
        ];       
        $validator = Validator::make($request->all(), $all_data_validation);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'false', 'message' => $validator->errors()->all()], 200);
        }
        try {
            $user = auth()->user();
            $document_data = new GramPanchayatDocuments();
            $document_data->user_id = $user->id; 
            $document_data->document_name = $request->document_name;
            $document_data->document_type_id = $request->document_type_id;
            $document_data->latitude = $request->latitude;
            $document_data->longitude = $request->longitude;
            $document_data->save();
            $last_insert_id = $document_data->document_name;
            $documentPdf = $last_insert_id;
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
    
            uploadImage($request, 'document_pdf', $path, $documentPdf);
    
            $document_data->document_pdf = $documentPdf;
            $document_data->save();
    
            $document_data->document_pdf = $document_data->document_pdf;
    
            return response()->json(['status' => 'true', 'message' => 'Document uploaded successfully',  'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document uploaded failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function getAllDocuments(Request $request){
    
        try {
            $data_output = [];

            $user = auth()->user()->id;
            $is_approved = '' ;
            $is_resubmitted = ''; 

            $page = isset($request["start"]) ? $request["start"] : Config::get('DocumentConstant.GRAM_DOCUMENT_DEFAULT_START') ;
            // $rowperpage = isset($request["length"])? $request["length"] : Config::get('DocumentConstant.GRAM_DOCUMENT_DEFAULT_LENGTH') ; // Rows display per page
            $rowperpage = GRAM_DOCUMENT_DEFAULT_LENGTH;
            $start = ($page - 1) * $rowperpage;

            if($request->has('is_approved') && $request->is_approved == 'added' && $request->has('is_resubmitted') && $request->is_resubmitted == 'resubmitted') {  //1
                $is_approved = 1 ;
                $is_resubmitted = 0 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'not_approved') { //3
                $is_approved = 3 ;
            } elseif($request->has('is_approved') && $request->is_approved == 'approved') { //2
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
                ->where('tbl_gram_panchayat_documents.user_id', $user)
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
                    'tbl_documenttype.doc_color',
                    'tbl_gram_panchayat_documents.document_pdf',
                    'users.user_district',
                    'district_u.name as district_name',
                    'users.user_taluka',
                    'taluka_u.name as taluka_name',
                    'users.user_village',
                    'village_u.name as village_name',
                    'registrationstatus.status_name',
                    GramPanchayatDocuments::raw("tbl_gram_panchayat_documents.updated_at AT TIME ZONE 'UTC' AT TIME ZONE 'Asia/Kolkata' as updated_at"),                
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
                            HistoryDocumentModel::raw("tbl_doc_history.updated_at AT TIME ZONE 'UTC' AT TIME ZONE 'Asia/Kolkata' as updated_at"), 

                        )
                        ->where('tbl_doc_history.gram_document_id', $documenthistory['id'])
                        ->where('tbl_doc_reason.is_deleted', 0)
                        ->get();
                }

                if(sizeof($data_output)>=0) {
                    $totalPages = ceil($totalRecords/$rowperpage);
                } else {
                    $totalPages = 0;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document list get fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateDocuments(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required', 
                // 'document_type_id' => 'required',
                // 'document_name' => 'required',             
                'document_pdf' => 'required|mimes:pdf|min:1|max:25600', 
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => $validator->errors()], 200);
            }          
            
            
            $document_data = GramPanchayatDocuments::findOrFail($request->id);
    
            
            if (!empty($document_data->document_pdf)) {
                $old_pdf_path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_DELETE') . $document_data->document_pdf;
                if (file_exists_view($old_pdf_path)) {
                    removeImage($old_pdf_path);
                }
            }
           
            $path = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_ADD');
            $documentPdf = $request->document_name; 
            uploadImage($request, 'document_pdf', $path, $documentPdf);
    
            // Update document information in the database
            // $document_data->document_type_id = $request->document_type_id;
            $document_data->document_name = $request->document_name;
            $document_data->latitude = $request->latitude;
            $document_data->longitude = $request->longitude;
            $document_data->document_pdf =  $documentPdf;
            $document_data->is_approved = 1;
            $document_data->is_resubmitted = true;
            $document_data->reason_doc_id = null;
            $document_data->other_remark = null;
            $document_data->save();
    
            return response()->json(['status' => 'true', 'message' => 'Document updated successfully', 'data' => $document_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document updated fail', 'error' => $e->getMessage()], 500);
        }
    }
   
    public function getDownloadDocument(Request $request){
        try {
            $user = auth()->user()->id;
            $document_pdffile = $request->input('document_name');
        
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $user)
                ->first();
        
            $utype = $data_output->user_type;
            $user_working_dist = $data_output->user_district;
            $user_working_tal = $data_output->user_taluka;
            $user_working_vil = $data_output->user_village;
        
            if ($utype == '1') {
                $data_user_output = User::where('users.user_district', $user_working_dist)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            } else if ($utype == '2') {
                $data_user_output = User::where('users.user_taluka', $user_working_tal)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            } else if ($utype == '3') {
                $data_user_output = User::where('users.user_village', $user_working_vil)
                    ->select('id')
                    ->pluck('id')
                    ->toArray();
            }
        
            $document = GramPanchayatDocuments::leftJoin('documenttype as tbl_documenttype', 'tbl_gram_panchayat_documents.document_type_id', '=', 'tbl_documenttype.id')
                ->whereIn('tbl_gram_panchayat_documents.user_id', $data_user_output)
                ->where('tbl_gram_panchayat_documents.document_name', $document_pdffile)
                ->when($request->has('document_name'), function($query) use ($request) {
                    $query->where('tbl_gram_panchayat_documents.document_name', 'like', '%' . $request->document_name . '%');
                })
                ->first();
           
        // dd( $document);
            if (!$document) {
                return response()->json(['status' => 'false', 'message' => 'Document not found'], 200);
            }
           
                $document->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document->document_pdf;
            
            return response()->json(['status' => 'true', 'message' => 'Document retrieved successfully', 'data' => $document], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document retrieval failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function countGramsevakDocument(Request $request) {
        try {
            $user = auth()->user();
    
            $documentCount = GramPanchayatDocuments::where('user_id', $user->id)
            ->where('is_approved', 2)
            ->get()
            ->count();
           
            return response()->json([
                'status' => 'true',
                'message' => 'Counts retrieved successfully',
                'document_count' => $documentCount,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'message' => 'Counts get failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
