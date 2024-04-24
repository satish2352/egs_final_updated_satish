<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Validator;
use App\Models\ {
	Labour,
    User,
    LabourFamilyDetails,
    HistoryModel,
    GramPanchayatDocuments
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;



class OfficerController extends Controller
{
    public function getParticularLabourOfficer(Request $request){
        try {
            $user = auth()->user()->id;
            $mgnrega_card_id = $request->input('mgnrega_card_id');
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
           
            $data_labour = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.skill_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_reason as reason_labour', 'labour.reason_id', '=', 'reason_labour.id')
                ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->whereIn('labour.user_id',$data_user_output)
                ->where('registrationstatus.is_active', true)
                ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
                    $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
                })
                ->select(
                    'labour.id',
                    'labour.full_name',
                    User::raw("CONCAT(users.f_name, IFNULL(CONCAT(' ', users.m_name), ''),' ', users.l_name) AS gramsevak_full_name"),
                    'labour.date_of_birth',
                    'gender_labour.gender_name as gender_name',
                    'skills_labour.skills as skills',
                    'reason_labour.reason_name as reason_name',
                    'labour.district_id',
                    'district_labour.name as district_name',
                    'labour.taluka_id',
                    'taluka_labour.name as taluka_name',
                    'labour.village_id',
                    'village_labour.name as village_name',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'labour.aadhar_image',
                    'labour.mgnrega_image',
                    'labour.voter_image',
                    'labour.other_remark',
                    'registrationstatus.status_name'
                )
                    ->get()
					->toArray();
                    // dd($data_labour);
                    foreach ($data_labour as &$labour) { 
                        $labour['profile_image'] = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour['profile_image'];
                        $labour['aadhar_image'] = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour['aadhar_image'];
                        $labour['mgnrega_image'] = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour['mgnrega_image'];
                        $labour['voter_image'] = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour['voter_image'];
                    }
    
                foreach ($data_labour as &$labour) {
                    $labour['family_details'] = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
                        ->leftJoin('relation as relation_labour', 'labour_family_details.relationship_id', '=', 'relation_labour.id')
                        ->leftJoin('maritalstatus as maritalstatus_labour', 'labour_family_details.married_status_id', '=', 'maritalstatus_labour.id')
                        ->select(
                            'labour_family_details.id',
                        'gender_labour.gender_name as gender',
                        'labour_family_details.gender_id',
                        'relation_labour.relation_title as relation',
                        'labour_family_details.relationship_id',
                        'maritalstatus_labour.maritalstatus as maritalStatus',
                        'labour_family_details.married_status_id',
                        'labour_family_details.full_name',
                        'labour_family_details.date_of_birth'
                        )
                        ->where('labour_family_details.labour_id', $labour['id'])
                        ->get();
                }

                // if (isset($data_labour['is_resubmitted']) && $data_labour['is_resubmitted']) {
                foreach ($data_labour as &$labourhistory) {
                    $labourhistory['history_details'] = HistoryModel::leftJoin('roles as roles_labour', 'tbl_history.roles_id', '=', 'roles_labour.id')
                        ->leftJoin('users as users_labour', 'tbl_history.user_id', '=', 'users_labour.id')
                        ->leftJoin('tbl_reason', 'tbl_history.reason_id', '=', 'tbl_reason.id')
                        ->leftJoin('labour', 'tbl_history.labour_id', '=', 'labour.id')
                        // ->where('tbl_history.labour_id', $labourhistory['labour_id'])
                        ->select(
                            'tbl_history.id',
                            'roles_labour.role_name as role_name',
                            'users_labour.f_name as f_name',
                            'tbl_reason.reason_name as reason_name',
                            'tbl_history.other_remark',
                            HistoryModel::raw("CONVERT_TZ(tbl_history.updated_at, '+00:00', '+05:30') as updated_at"), 
                           
                        )
                        ->where('tbl_history.labour_id', $labourhistory['id'])
                        ->get();
                }

                    return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_labour], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Failed to retrieve labour list','error' => $e->getMessage()], 500);
        }
    }    
    public function getLabourStatusListReceived(Request $request){
        try {
            $user = auth()->user()->id;
            $is_approved = '' ;
            $is_resubmitted = ''; 

            $page = isset($request["start"]) ? $request["start"] : Config::get('DocumentConstant.LABOUR_DEFAULT_START') ;
            // $rowperpage = isset($request["length"])? $request["length"] : Config::get('DocumentConstant.LABOUR_DEFAULT_LENGTH') ; // Rows display per page
            $rowperpage = LABOUR_DEFAULT_LENGTH;
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
                $basic_query_object = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->whereIn('labour.user_id', $data_user_output)
                ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
                    $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
                })
                ->when($request->has('is_approved'), function($query) use ($is_approved) {
                    $query->where('labour.is_approved', $is_approved);
                })
                ->when($request->has('is_resubmitted'), function($query) use ($is_resubmitted) {
                    $query->where('labour.is_resubmitted', $is_resubmitted);
                });

                $totalRecords = $basic_query_object->select('labour.id')->get()->count();

                $data_output = $basic_query_object->select(
                    'labour.id',
                    'labour.full_name',
                    User::raw("CONCAT(users.f_name, IFNULL(CONCAT(' ', users.m_name), ''),' ', users.l_name) AS gramsevak_full_name"),
                    'labour.date_of_birth',
                    'labour.district_id',
                    'district_labour.name as district_name',
                    'labour.taluka_id',
                    'taluka_labour.name as taluka_name',
                    'labour.village_id',
                    'village_labour.name as village_name',
                    'labour.mobile_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'registrationstatus.status_name',
                )->skip($start)
                ->take($rowperpage)
                ->orderBy('id', 'desc')
                ->get();

                foreach ($data_output as &$labour) { 
                    $labour['profile_image'] = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour['profile_image'];
                }
                if(sizeof($data_output)>=0) {
                    $totalPages = ceil($totalRecords/$rowperpage);
                } else {
                    $totalPages = 0;
                }
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Document List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
    // public function updateLabourStatusApproved(Request $request){
    //     try {
    //         $user = auth()->user()->id;
    //             // Validate the incoming request
    //         $validator = Validator::make($request->all(), [
    //             // 'mgnrega_card_id' => 'required',
    //             'labour_id'=> 'required',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
    //         }
            
    //         $existingRecord = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)
    //         ->where('id', $request->labour_id)
    //         ->where('is_approved', 2)
    //         ->first();

    //         if ($existingRecord) {
    //             return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID already exists with approved. Please change the MGNREGA card ID.'], 200);
    //         }

    //         $updated = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)
    //             ->where('is_approved', 1)
    //             ->update(['is_approved' => 2,'is_resubmitted'=> 0]); 
                
    
    //         if ($updated) {
    //             return response()->json(['status' => 'true', 'message' => 'Labour status updated successfully'], 200);
    //         } else {
    //             return response()->json(['status' => 'false', 'message' => 'No labour found with the provided MGNREGA card Id'], 200);
    //         }
    
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Update failed','error' => $e->getMessage()], 500);
    //     }
    // }
    public function updateLabourStatusApproved(Request $request){
        try {
            $user = auth()->user();
            $labour_id = $request->input('labour_id'); 
            $mgnrega_card_id = $request->input('mgnrega_card_id'); 
    
            $validator = Validator::make($request->all(), [
                'labour_id' => 'required',
                'mgnrega_card_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
            }
            
            $existingRecord = Labour::where('mgnrega_card_id', $mgnrega_card_id)
                ->where('is_approved', 2)
                ->first();
    
            if ($existingRecord) {
                return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID already exists with approved status. Please change the MGNREGA card ID.'], 200);
            }
    
            $labour = Labour::find($labour_id);
    
            if (!$labour) {
                return response()->json(['status' => 'false', 'message' => 'No labour found with the provided ID'], 200);
            }
    
            if ($labour->mgnrega_card_id !== $mgnrega_card_id) {
                return response()->json(['status' => 'false', 'message' => 'Mismatch in provided labour ID and MGNREGA card ID'], 200);
            }
    
            if ($labour->is_approved === 2) {
                return response()->json(['status' => 'false', 'message' => 'Labour record is already approved'], 200);
            }
    
            $labour->is_approved = 2;
            $labour->is_resubmitted = 0;
            $labour->save();
    
            return response()->json(['status' => 'true', 'message' => 'Labour status updated successfully'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function updateLabourStatusNotApproved(Request $request) {
        try {
            $user = auth()->user();
            
            $labour_id = $request->input('labour_id'); 
           
            $validator = Validator::make($request->all(), [
                'labour_id' => 'required',
                'reason_id' => 'required',
                'is_approved' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
    
            // Update labor entry
            $updateData = [
                'is_approved' => 3,
                'is_resubmitted' => 0,
                'reason_id' => $request->reason_id, 
            ];
    
            // Include 'other_remark' in the update data if it's provided
            if ($request->has('other_remark')) {
                $updateData['other_remark'] = $request->other_remark ?: ''; // Set to empty string if not provided
            }
    
            $updated = Labour::where('id', $request->labour_id)
                ->where('is_approved', 1)
                ->update($updateData);
            if ($updated) {
                // Create a history record
                $history = new HistoryModel();
                $history->user_id = $user->id; 
                $history->roles_id = $user->role_id; 
                $history->labour_id = $request->labour_id;
                $history->is_approved = $request->is_approved;
                $history->reason_id = $request->reason_id; 
                
                if ($request->has('other_remark')) {
                    $history->other_remark = $request->other_remark ?: 'null'; // Set to empty string if not provided
                }
    
                $history->save();
    
                return response()->json(['status' => 'true', 'message' => 'Labour status updated successfully'], 200);
            } else {
                return response()->json(['status' => 'false', 'message' => 'No labor found with the provided Labour Id or status is not approved'], 200);
            }
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function officerReportsCount(Request $request) {
        try {
            $user = auth()->user()->id;
    
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $user)
                ->first();
    
            $utype = $data_output->user_type;
            $user_working_dist = $data_output->user_district;
            $user_working_tal = $data_output->user_taluka;
            $user_working_vil = $data_output->user_village;
    
            $data_user_output = User::select('id');
            if ($utype == '1') {
                $data_user_output->where('users.user_district', $user_working_dist);
            } else if ($utype == '2') {
                $data_user_output->where('users.user_taluka', $user_working_tal);
            } else if ($utype == '3') {
                $data_user_output->where('users.user_village', $user_working_vil);
            }
    
            $data_user_output = $data_user_output->get()->pluck('id');  
    
            $counts = Labour::leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->whereIn('users.id', $data_user_output)
                ->selectRaw('is_approved, COUNT(*) as count')
                ->where('is_resubmitted', '0')
                ->groupBy('is_approved')              
                ->get();
               

            $countsDocument = GramPanchayatDocuments::whereIn('user_id', $data_user_output)
                ->selectRaw('is_approved, COUNT(*) as count')
                ->where('is_resubmitted', '0')
                ->groupBy('is_approved')
                ->get();
    
                $resubmittedCountLabour = Labour::whereIn('user_id', $data_user_output)
                ->where('is_resubmitted', 1)
                ->where('is_approved', 1)
                ->count();

            $resubmittedCountDocument = GramPanchayatDocuments::whereIn('user_id', $data_user_output)
                ->where('is_resubmitted', 1)
                ->where('is_approved', 1)
                ->count();
    
            // Initialize counters
            $sentForApprovalCount = 0;
            $approvedCount = 0;
            $notApprovedCount = 0;
            

            $sentForApprovalCountDocument = 0;
            $approvedCountDocument = 0;
            $notApprovedCountDocument = 0;
    
            // Counting each status
            foreach ($counts as $count) {
                if ($count->is_approved == 1 && $count->is_resubmitted == 0) {
                    $sentForApprovalCount = $count->count;
                } elseif ($count->is_approved == 2) {
                    $approvedCount = $count->count;
                } elseif ($count->is_approved == 3) {
                    $notApprovedCount = $count->count;
                }
            }
    
            foreach ($countsDocument as $countdoc) {
                if ($countdoc->is_resubmitted == 0 && $countdoc->is_approved == 1) {
                    $sentForApprovalCountDocument = $countdoc->count;
                } elseif ($countdoc->is_approved == 2) {
                    $approvedCountDocument = $countdoc->count;
                } elseif ($countdoc->is_approved == 3) {
                    $notApprovedCountDocument = $countdoc->count;
                }
            }
    
            // Return the counts in the response
            return response()->json([
                'status' => 'true',
                'message' => 'Counts retrieved successfully',
                'sent_for_approval_count' => $sentForApprovalCount,
                'approved_count' => $approvedCount,
                'not_approved_count' => $notApprovedCount,
                'resubmitted_labour_count' => $resubmittedCountLabour,
                'sent_for_approval_document_count' => $sentForApprovalCountDocument,
                'approved_document_count' => $approvedCountDocument,
                'not_approved_document_count' => $notApprovedCountDocument,
                'resubmitted_document_count' => $resubmittedCountDocument
            ], 200);
    
        } catch (\Exception $e) {
            // Return error if any exception occurs
            return response()->json(['status' => 'false', 'message' => 'Error occurred', 'error' => $e->getMessage()], 500);
        }
    }

}
