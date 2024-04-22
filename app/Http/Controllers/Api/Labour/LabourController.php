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

class LabourController extends Controller
{
    public function add(Request $request){
        try {

            if ($request->has('family')) {
                $family = json_decode($request->family, true);
                $request->merge(['family' => $family]);
            }

            $request->validate([
                'mgnrega_card_id' => 'required',
            ]);

            $existingLabour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)
                                    ->where('is_approved', 2)
                                    ->first();

            if ($existingLabour) {
                $existingLabour->update(['sync_reason' => 'Mgnrega Card ID already exists']);
                return response()->json("{'status' : 'false', 'message' : 'Mgnrega Card ID already exists'}", 200);

            } else {
                 
                $all_data_validation = [
                    'full_name' => 'required',
                    'gender_id' => 'required',
                    'date_of_birth' => 'required|date_format:d/m/Y|before_or_equal:today|before:-18 years',
                    'district_id' => 'required',
                    'taluka_id' => 'required',
                    'village_id' => 'required',
                    'skill_id' => 'required',
                    'mobile_number' => ['required', 'digits:10', 'regex:/^[6789]\d{9}$/'],
                    'mgnrega_card_id' => 'required',
                    'latitude' => ['required', 'between:-90,90'],
                    'longitude' => ['required', 'between:-180,180'],
                    'aadhar_image' => 'required|image|mimes:jpeg,png,jpg|min:10|max:2048',
                    'mgnrega_image' => 'required|image|mimes:jpeg,png,jpg|min:10|max:2048',
                    'profile_image' => 'required|image|mimes:jpeg,png,jpg|min:10|max:2048',
                    'voter_image' => 'required|image|mimes:jpeg,png,jpg|min:10|max:2048',
                ];

                $customMessages = [
                    'full_name.required'=>'full name is required',
                    'gender_id.required'=>'Please select a Gender.',
                    'date_of_birth.required'=>'date of birth is required',
                    'date_of_birth.date_format'=>'date of birth must be in the format d/m/Y.',
                    'date_of_birth.before_or_equal'=>'date of birth must be before or equal to today and at least 18 years ago.',
                    'date_of_birth.before'=>'date of birth must be before today and at least 18 years ago.',
                    'district_id.required'=>'Please select a district.',
                    'taluka_id.required'=>'Please select a taluka.',
                    'village_id.required'=>'Please select a village.',
                    'skill_id.required'=>'Please select a skill.',
                    'mobile_number.required'=>'Mobile number is required.',
                    'mobile_number.digits'=>'Mobile number must be 10 digits.',
                    'mobile_number.regex' => 'Mobile number must start with 9, 8, 7 or 6.',
                    'mgnrega_card_id.required'=>'mgnrega card id is required.',
                    'latitude.required'=>'latitude is required.',
                    'latitude.between'=>'latitude must be between -90 and 90',
                    'longitude.required'=>'longitude is required.',
                    'longitude.between'=>'longitude must be between -180 and 180',

                    'aadhar_image.required' => 'Aadhar Image is required',
                    'aadhar_image.image' => 'Aadhar Image must be an image file',
                    'aadhar_image.mimes' => 'Aadhar Image must be a jpeg, png, or jpg file',
                    'aadhar_image.min' => 'Aadhar Image must be at least 10 KB in size',
                    'aadhar_image.max' => 'Aadhar Image must not exceed 2048 KB in size',
                    
                    'mgnrega_image.required' => 'MGNREGA Image is required',
                    'mgnrega_image.image' => 'MGNREGA Image must be an image file',
                    'mgnrega_image.mimes' => 'MGNREGA Image must be a jpeg, png, or jpg file',
                    'mgnrega_image.min' => 'MGNREGA Image must be at least 10 KB in size',
                    'mgnrega_image.max' => 'MGNREGA Image must not exceed 2048 KB in size',
                    
                    'profile_image.required' => 'Profile Image is required',
                    'profile_image.image' => 'Profile Image must be an image file',
                    'profile_image.mimes' => 'Profile Image must be a jpeg, png, or jpg file',
                    'profile_image.min' => 'Profile Image must be at least 10 KB in size',
                    'profile_image.max' => 'Profile Image must not exceed 2048 KB in size',
                    
                    'voter_image.required' => 'Voter Image is required',
                    'voter_image.image' => 'Voter Image must be an image file',
                    'voter_image.mimes' => 'Voter Image must be a jpeg, png, or jpg file',
                    'voter_image.min' => 'Voter Image must be at least 10 KB in size',
                    'voter_image.max' => 'Voter Image must not exceed 2048 KB in size',
                  
               ];

                
                    if ($request->has('family')) {
                        if (sizeof($request->family)>0) {    
                            $all_data_validation['family'] = 'required|array';
                            $all_data_validation['family.*.fullName'] = 'required|string';
                            $all_data_validation['family.*.genderId'] = 'required|integer'; 
                            $all_data_validation['family.*.relationId'] = 'required|integer'; 
                            $all_data_validation['family.*.maritalStatusId'] = 'required|integer'; 
                            $all_data_validation['family.*.dob'] = 'required|date_format:d/m/Y|before_or_equal:today'; 
                            
                            $customMessages['family.required'] = 'Family details are required.';
                            $customMessages['family.array'] = 'Family details must be an array.';
                            $customMessages['family.*.fullName.required'] = 'Full name of family member is required.';
                            $customMessages['family.*.genderId.required'] = 'Gender of family member is required.';
                            $customMessages['family.*.relationId.required'] = 'Relation of family member is required.';
                            $customMessages['family.*.maritalStatusId.required'] = 'Marital status of family member is required.';
                            $customMessages['family.*.dob.required'] = 'Date of birth of family member is required.';
                            $customMessages['family.*.dob.date_format'] = 'Date of birth of family member must be in the format d/m/Y.';
                            $customMessages['family.*.dob.before_or_equal'] = 'Date of birth of family member must be before or equal to today.';
                        }
                    }

                // if(isset($request->landline_number)) {
                    //     $all_data_validation['landline_number'] =  ['required', 'regex:/^[0-9]{8,}$/'];
                    // }                   
                    
                 
                    $validator = Validator::make($request->all(), $all_data_validation, $customMessages);
      

                // $validator = Validator::make($request->all(), $all_data_validation);

                if ($validator->fails()) {
                    return response()->json(['status' => 'false', 'message' => 'Validation Fail',  'error' => $validator->errors()->all()], 200);
                   
                    // return response()->json("{'status' : 'false', 'error' : $validator->errors()->all()}", 200);
                }

                $user = auth()->user();

                $labour_data = new Labour();
                $labour_data->user_id = $user->id;
                $labour_data->full_name = $request->full_name;
                $labour_data->gender_id = $request->gender_id;
                $labour_data->date_of_birth = $request->date_of_birth;
                $labour_data->district_id = $request->district_id;
                $labour_data->taluka_id = $request->taluka_id;
                $labour_data->village_id = $request->village_id;
                $labour_data->mobile_number = $request->mobile_number;
                $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
                $labour_data->skill_id = $request->skill_id;
                $labour_data->latitude = $request->latitude;
                $labour_data->longitude = $request->longitude;
                $labour_data->landline_number = $request->has('landline_number') ? $request->landline_number : '';
                $labour_data->save();

                $last_insert_id = $labour_data->id;
                $imageAadhar = $last_insert_id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
                $imageMgnrega = $last_insert_id . '_' . rand(100000, 999999) . '_mgnrega.' . $request->mgnrega_image->extension();
                $imageProfile = $last_insert_id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
                $imageVoter = $last_insert_id . '_' . rand(100000, 999999) . '_voter.' . $request->voter_image->extension();

                $path = Config::get('DocumentConstant.USER_LABOUR_ADD');

                uploadImage($request, 'aadhar_image', $path, $imageAadhar);
                uploadImage($request, 'mgnrega_image', $path, $imageMgnrega);
                uploadImage($request, 'profile_image', $path, $imageProfile);
                uploadImage($request, 'voter_image', $path, $imageVoter);

                $labour_data->aadhar_image =  $imageAadhar;
                $labour_data->mgnrega_image = $imageMgnrega;
                $labour_data->profile_image = $imageProfile;
                $labour_data->voter_image =  $imageVoter;
                $labour_data->save();

                $familyDetails = [];

                if ($request->has('family')) {
                    if (sizeof($request->family)>0) {    
                        $familyDetailNewInsert = $request->family;
                                
                        foreach ($familyDetailNewInsert as $key => $familyMember) {
                            $familyDetail = new LabourFamilyDetails();
                            $familyDetail->labour_id = $labour_data->id;
                            $familyDetail->full_name = $familyMember['fullName'];
                            $familyDetail->gender_id = $familyMember['genderId'];
                            $familyDetail->relationship_id = $familyMember['relationId'];
                            $familyDetail->married_status_id = $familyMember['maritalStatusId'];
                            $familyDetail->date_of_birth = $familyMember['dob'];
                            $familyDetail->save();
                            $familyDetails[] = $familyDetail; // Collect family details
                        }
                    }
                }

                return response()->json([
                    'status' => 'true',
                    'message' => 'Labor added successfully',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'An error occurred',
             'error' => $e->getMessage()], 500);
        }
    }
    public function getAllLabourList(Request $request){

        try {
            $data_output = [];
            $user = auth()->user()->id;
            
            $is_approved = '' ;
            $is_resubmitted = ''; 

            $page = isset($request["start"]) ? $request["start"] : Config::get('DocumentConstant.LABOUR_DEFAULT_START') ;
            $rowperpage = LABOUR_DEFAULT_LENGTH;
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
            $basic_query_object = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                ->leftJoin('skills as skills_labour', 'labour.skill_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_reason as reason_labour', 'labour.reason_id', '=', 'reason_labour.id')
                ->where('labour.user_id', $user)
                ->when($request->has('is_approved'), function($query) use ($is_approved) {
                    $query->where('labour.is_approved', $is_approved);
                })
                ->when($request->has('is_resubmitted'), function($query) use ($is_resubmitted) {
                    $query->where('labour.is_resubmitted', $is_resubmitted);
                })
                ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
                    $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
                })
                ->when($request->get('project_id'), function($query) use ($request) {
                    
                    $query->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id');
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                });

            $totalRecords = $basic_query_object->select('labour.id')->get()->count();
            $data_output = $basic_query_object->select(
                'labour.id',
                'labour.full_name',
                'labour.date_of_birth',
                'labour.gender_id',
                'gender_labour.gender_name as gender_name',
                'labour.skill_id',
                'skills_labour.skills as skills',
                'labour.reason_id',
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
                )->skip($start)
                ->take($rowperpage)
                ->distinct('labour.id')
                ->orderBy('id', 'desc')
                ->get();

                foreach ($data_output as $labour) {
                    // Append image paths to the output data
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                    $labour->aadhar_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->aadhar_image;
                    $labour->mgnrega_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->mgnrega_image;
                    $labour->voter_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->voter_image;

                }
            
                foreach ($data_output as $labour) {
                $labour->family_details = LabourFamilyDetails::leftJoin('gender as gender_labour', 'labour_family_details.gender_id', '=', 'gender_labour.id')
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
                    ->where('labour_family_details.labour_id', $labour->id)
                    ->get();
            }

            foreach ($data_output as &$labourhistory) {
                $labourhistory['history_details'] = HistoryModel::leftJoin('roles as roles_labour', 'tbl_history.roles_id', '=', 'roles_labour.id')
                    ->leftJoin('users as users_labour', 'tbl_history.user_id', '=', 'users_labour.id')
                    ->leftJoin('tbl_reason', 'tbl_history.reason_id', '=', 'tbl_reason.id')
                    ->leftJoin('labour', 'tbl_history.labour_id', '=', 'labour.id')
                    ->select(
                        'tbl_history.id',
                        'roles_labour.role_name as role_name',
                        'users_labour.f_name as f_name',
                        'tbl_reason.reason_name as reason_name',
                        'tbl_history.other_remark',
                        'tbl_history.updated_at',
                        HistoryModel::raw("CONVERT_TZ(tbl_history.updated_at, '+00:00', '+05:30') as updated_at"), 
                    )
                    ->where('tbl_history.labour_id', $labourhistory['id'])
                    ->get();
            }
            
            if (sizeof($data_output) > 0) {
                $totalPages = ceil($totalRecords/$rowperpage);
            } else {
                $totalPages = 0;
            }

            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
                } catch (\Exception $e) {
                    return response()->json(['status' => 'false', 'message' => 'Data get failed', 'error' => $e->getMessage()], 500);
                }
    }
    public function updateLabourFirstForm(Request $request){
        try {
            $user = auth()->user();

            $all_data_validation = [
                'full_name' => 'required',
                'date_of_birth' => 'required|date_format:d/m/Y|before_or_equal:today|before:-18 years',
                'gender_id' => 'required',
                'district_id' => 'required',
                'taluka_id' => 'required',
                'village_id' => 'required',
                'skill_id' => 'required',
                'mobile_number' => ['required', 'digits:10', 'regex:/^[6789]\d{9}$/'],
                'mgnrega_card_id' => ['required'],
            ];
    
            $customMessages = [
                'full_name.required'=>'full name is required',
                'gender_id.required'=>'Please select a Gender.',
                'date_of_birth.required'=>'date of birth is required',
                'date_of_birth.date_format'=>'date of birth must be in the format d/m/Y.',
                'date_of_birth.before_or_equal'=>'date of birth must be before or equal to today and at least 18 years ago.',
                'date_of_birth.before'=>'date of birth must be before today and at least 18 years ago.',
                'district_id.required'=>'Please select a district.',
                'taluka_id.required'=>'Please select a taluka.',
                'village_id.required'=>'Please select a village.',
                'skill_id.required'=>'Please select a skill.',
                'mobile_number.required'=>'Mobile number is required.',
                'mobile_number.digits'=>'Mobile number must be 10 digits.',
                'mobile_number.regex' => 'Mobile number must start with 9, 8,7, or 6.',
                'mgnrega_card_id.required'=>'mgnrega card id is required.',
              
            ];
            $validator = Validator::make($request->all(), $all_data_validation, $customMessages);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->json([
                    'status' => 'false',
                    'message' => 'Validation Fail',
                    'error' => $errors
                ], 200);
            }
         

            // Find the labour data to update
            $labour_data = Labour::where('id', $request->id)->first();

            if (!$labour_data) {
                return response()->json(['status' => 'false', 'message' => 'Labour data not found'], 200);
            }

            // Check if the mgnrega_card_id can be updated based on is_approved
            if ($labour_data->is_approved == 2) {
                return response()->json(['status' => 'false', 'message' => 'Cannot update mgnrega card id when labour is approved'], 200);
            }

            // Check if the provided mgnrega_card_id already exists in the database
            $existingLabour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();
           

            if ($existingLabour) {
                if ($existingLabour->is_approved == 2) {
                    // If is_approved is 2, do not update the MGNREGA card ID
                    return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID already exists and is not approved for update'], 200);
                } else {
                    // If is_approved is 1 or 3, update the MGNREGA card ID
                    if ($existingLabour->id !== $labour_data->id) {
                        return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID already exists'], 200);
                    }
                }
            }
            
            $labour_data->user_id = $user->id;
            $labour_data->full_name = $request->full_name;
            $labour_data->gender_id = $request->gender_id;
            $labour_data->date_of_birth = $request->date_of_birth;
            $labour_data->skill_id = $request->skill_id;  
            $labour_data->district_id = $request->district_id;
            $labour_data->taluka_id = $request->taluka_id;
            $labour_data->village_id = $request->village_id;
            $labour_data->mobile_number = $request->mobile_number;
            $labour_data->landline_number = $request->landline_number;
            $labour_data->is_approved = 1;
            $labour_data->is_resubmitted = true;
            $labour_data->reason_id = null;
            $labour_data->other_remark = 'null';
            if ($labour_data->is_approved != 2) {
                $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
            }
            $labour_data->save();

            return response()->json(['status' => 'true', 'message' => 'Labour updated successfully', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Labour update failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function updateLabourSecondForm(Request $request){
        try {
            $user = auth()->user();

            $validatorRules = [
                'latitude' => ['required', 'between:-90,90'], 
                'longitude' => ['required', 'between:-180,180'], 
            ];
    
            if ($request->hasFile('aadhar_image')) {
                $validatorRules['aadhar_image'] = 'required|image|mimes:jpeg,png,jpg|min:10|max:2048';
            }
    
            if ($request->hasFile('mgnrega_image')) {
                $validatorRules['mgnrega_image'] = 'required|image|mimes:jpeg,png,jpg|min:10|max:2048';
            }
    
            if ($request->hasFile('profile_image')) {
                $validatorRules['profile_image'] = 'required|image|mimes:jpeg,png,jpg|min:10|max:2048';
            }
    
            if ($request->hasFile('voter_image')) {
                $validatorRules['voter_image'] = 'required|image|mimes:jpeg,png,jpg|min:10|max:2048';
            }
    
            $validator = Validator::make($request->all(), $validatorRules);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => $validator->errors()], 200);
            }

            // Find the labour data to update
            $labour_data = Labour::where('id', $request->id)->first();

            if (!$labour_data) {
                return response()->json(['status' => 'false', 'message' => 'Labour data not found'], 200);
            }

            // Check if labour_id is greater than zero before deleting family details
            if ($labour_data->id > 0) {
                LabourFamilyDetails::where('labour_id', $request->id)->delete();
            }

            $labour_data->user_id = $user->id;
            $labour_data->latitude = $request->latitude;
            $labour_data->longitude = $request->longitude;
            $labour_data->is_approved = 1;
            $labour_data->reason_id = null;
            $labour_data->other_remark = 'null';

            $labour_data->save();

            $pathdelete = Config::get('DocumentConstant.USER_LABOUR_DELETE');
            $path = Config::get('DocumentConstant.USER_LABOUR_ADD');
           
            if ($request->hasFile('profile_image')) {
            if ($labour_data->profile_image) {
            removeImage($pathdelete . $labour_data->profile_image);
            }
            $profileImageName = $labour_data->id . '_' . rand(100000, 999999) . '_profile.' . $request->profile_image->extension();
            uploadImage($request, 'profile_image', $path, $profileImageName);
            $labour_data->profile_image = $profileImageName;
            }

            if ($request->hasFile('aadhar_image')) {
            if ($labour_data->aadhar_image) {
                removeImage($pathdelete . $labour_data->aadhar_image);
            }
            $aadharImageName = $labour_data->id . '_' . rand(100000, 999999) . '_aadhar.' . $request->aadhar_image->extension();
            uploadImage($request, 'aadhar_image', $path, $aadharImageName);
            $labour_data->aadhar_image = $aadharImageName;
            }

            if ($request->hasFile('mgnrega_image')) {
            if ($labour_data->mgnrega_image) {
                removeImage($pathdelete . $labour_data->mgnrega_image);
            }
            $mgnregaImageName = $labour_data->id . '_' . rand(100000, 999999) . '_mgnrega.' . $request->mgnrega_image->extension();
            uploadImage($request, 'mgnrega_image', $path, $mgnregaImageName);
            $labour_data->mgnrega_image = $mgnregaImageName;
            }

            if ($request->hasFile('voter_image')) {
            if ($labour_data->voter_image) {
                removeImage($pathdelete . $labour_data->voter_image);
            }
            $voterImageName = $labour_data->id . '_' . rand(100000, 999999) . '_voter.' . $request->voter_image->extension();
            uploadImage($request, 'voter_image', $path, $voterImageName);
            $labour_data->voter_image = $voterImageName;
            }
            $labour_data->is_resubmitted = true;
            // $labour_data->is_approved = 3;
            $labour_data->save();

            // $familyDetails = [];
            $familyDetailNew = json_decode($request->family,true);
                
            if ($labour_data->id > 0) {
            foreach ($familyDetailNew as $key => $familyMember) {
                $familyDetail = new LabourFamilyDetails();
                $familyDetail->labour_id = $labour_data->id;
                $familyDetail->full_name = $familyMember['full_name'];
                $familyDetail->gender_id = $familyMember['gender_id'];
                $familyDetail->relationship_id = $familyMember['relationship_id'];
                $familyDetail->married_status_id = $familyMember['married_status_id'];
                $familyDetail->date_of_birth = $familyMember['date_of_birth'];           
                $familyDetail->save();
                $familyDetails[] = $familyDetail; // Collect family details
            }
        }

            return response()->json(['status' => 'true', 'message' => 'Labour updated successfully', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Labour update failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function gramsevakReportscount(Request $request) {
        try {
            $user = auth()->user();
    
            $fromDate = date('Y-m-d').' 00:00:01';
            $toDate =  date('Y-m-d').' 23:59:59';
    
          
            $counts = Labour::where('user_id', $user->id)
                ->selectRaw('is_approved, COUNT(*) as count')
                ->where('is_resubmitted', 0)
                ->groupBy('is_approved')
                ->get();

            $todayCount = Labour::where('user_id', $user->id)
                ->where('updated_at', '>=', $fromDate)
                ->where('updated_at', '<=', $toDate)
                ->where('is_approved', 2)
                ->get()
                ->count();
   
            $currentYearCount = Labour::where('user_id', $user->id)
                ->whereYear('updated_at', date('Y'))
                ->where('is_approved', 2)
                ->get()
                ->count();
    
    
            $countsDocument = GramPanchayatDocuments::where('user_id', $user->id)
                ->selectRaw('is_approved, COUNT(*) as count')
                ->where('is_resubmitted', 0)
                ->groupBy('is_approved')
                ->get();
    
            $resubmittedCountLabour = Labour::where('user_id', $user->id)
                ->where('is_resubmitted', 1)
                ->where('is_approved', 1)
                ->get()
                ->count();
    
            $resubmittedCountDocument = GramPanchayatDocuments::where('user_id', $user->id)
                ->where('is_resubmitted', 1)
                ->where('is_approved', 1)
                ->get()
                ->count();
    
            $sentForApprovalCount = 0;
            $approvedCount = 0;
            $notApprovedCount = 0;
           
            $sentForApprovalCountDocument = 0;
            $approvedCountDocument = 0;
            $notApprovedCountDocument = 0;
            
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
               if ($countdoc->is_approved == 1 && $countdoc->is_resubmitted == 0) {
                    $sentForApprovalCountDocument = $countdoc->count;
                }
                elseif ($countdoc->is_approved == 2) {
                    $approvedCountDocument = $countdoc->count;
                }
                elseif ($countdoc->is_approved == 3) {
                    $notApprovedCountDocument = $countdoc->count;
                }
            }
    
            // Return the counts in the response
            return response()->json([
                'status' => 'true',
                'message' => 'Counts retrieved successfully',
                'today_count' => $todayCount,
                'current_year_count' => $currentYearCount,
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
            return response()->json(['status' => 'false', 'message' => 'Error occurred', 'error' => $e->getMessage()], 500);
        }
    }
    public function mgnregaCardIdAlreadyExist(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'mgnrega_card_id' => 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Validation failed', 'errors' => $validator->errors()], 200);
            }
    
            $existingLabour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)
                                    ->where('is_approved', 2)
                                    ->first();
    
            if ($existingLabour) {
                return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID already exists for another labour'], 200);
            }
    
            return response()->json(['status' => 'true', 'message' => 'MGNREGA card ID does not exist for any labour'], 200);
    
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Update failed', 'error' => $e->getMessage()], 500);
        }
    }
    public function autoSuggMgnregaCardId(Request $request)
    {
        try {
        $user = auth()->user()->id;
        $mgnrega_card_id = $request->input('mgnrega_card_id');

        if (!$mgnrega_card_id) {
            return response()->json(['status' => 'false', 'message' => 'MGNREGA card ID is required'], 200);
        }
            $data_output = Labour::where('labour.user_id', $user)
            ->where('labour.is_approved', 2)
            ->when($request->has('mgnrega_card_id'), function ($query) use ($request) {
                $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
            })
            ->pluck('mgnrega_card_id'); 
       
           $mgnrega_card_ids = $data_output->toArray();

            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Data not found', 'error' => $e->getMessage()], 500);
        }
    }
   
}
