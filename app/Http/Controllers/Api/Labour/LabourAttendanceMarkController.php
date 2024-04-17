<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
    Labour,
    LabourFamilyDetails,
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;


class LabourAttendanceMarkController extends Controller
{
    public function addAttendanceMark(Request $request){
       
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
            'mgnrega_card_id' => 'required',
            'attendance_day' => 'required', 
        ]);
    
        if ($validator->fails()) {
            return response()->json(['status' => 'false', 'message' => $validator->errors()->all()], 200);
        }
    
        try {
            // Check if the user exists
            $user = auth()->user();
            if (!$user) {
                return response()->json(['status' => 'false', 'message' => 'User not found'], 200);
            }

            // Check if labour status is approved
            $labour = Labour::where('mgnrega_card_id', $request->mgnrega_card_id)->first();
            if (!$labour || $labour->is_approved != 2) {
                return response()->json(['status' => 'false', 'message' => 'Labour status not approved'], 200);
            }
            

            $fromDate = date('Y-m-d').' 00:00:01';
            $toDate =  date('Y-m-d').' 23:59:59';

            $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                            ->whereBetween('updated_at', [$fromDate, $toDate])
                                            ->first();

            if ($existingEntry  && $existingEntry->attendance_day =='full_day') {
                return response()->json(['status' => 'false', 'message' => 'Attendance for this card ID full day already marked for today'], 200);
            } 
            
            else {
            
                $fromDate = date('Y-m-d').' 05:00:01';
                $toDate =  date('Y-m-d').' 13:00:00';

                $firstHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                                ->whereBetween('updated_at', [$fromDate, $toDate])
                                                ->first();

                
                $secondHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                                ->where('updated_at', '>',  date('Y-m-d').' 13:00:00')
                                                ->get()->toArray();

                if($firstHalfWorkAttendance) {
                    
                    if(date('Y-m-d H:i:s') >  date('Y-m-d').' 13:00:00') {

                        
                        if($request->attendance_day =='half_day' && (count($secondHalfWorkAttendance)<=0) ) {

                            if($firstHalfWorkAttendance->project_id == $request->project_id) {
                                $labour_data = LabourAttendanceMark::where(['id'=> $firstHalfWorkAttendance->id,
                                'mgnrega_card_id'=> $firstHalfWorkAttendance->mgnrega_card_id])->update(['attendance_day'=>'full_day']);
                             
                            } else  {

                                $labour_data = new LabourAttendanceMark();
                                $labour_data->user_id = $user->id; // Assign the user ID
                                $labour_data->project_id = $request->project_id;
                                $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
                                $labour_data->attendance_day = $request->attendance_day;
                            
                                $labour_data->save();
                       
                            }
                    
                        }  else {
                            return response()->json(['status' => 'false', 'message' => 'Attendance cant be mark as half/full day because halday alreay present for today'], 200);
                        }
                    }
                    elseif ($existingEntry->attendance_day == 'half_day' && now()->format('H:i:s') < '13:00:00') {
                        return response()->json(['status' => 'false', 'message' => 'Attendance for this card ID half day already marked for today 1st half'], 200);
                    }    
                } elseif((count($secondHalfWorkAttendance)>=1) && ($secondHalfWorkAttendance[0]['project_id'] == $request->project_id) ) {
                    return response()->json(['status' => 'false', 'message' => 'Attendance cant be mark as half/full day because halday alreay present for today'], 200);
                
                }
                elseif ($existingEntry  && $existingEntry->attendance_day =='half_day') {
                        return response()->json(['status' => 'false', 'message' => 'Attendance for this card ID half day already marked for today'], 200);
                    } 
                
                else {
                    $labour_data = new LabourAttendanceMark();
                    $labour_data->user_id = $user->id; // Assign the user ID
                    $labour_data->project_id = $request->project_id;
                    $labour_data->mgnrega_card_id = $request->mgnrega_card_id;
                    $labour_data->attendance_day = $request->attendance_day;
                    $labour_data->save();
                    
                }

            }

            return response()->json(['status' => 'true', 'message' => 'Attendance Mark successfully added', 'data' => $labour_data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance Mark Fail','error' => $e->getMessage()], 500);
        }
    }
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = auth()->user()->id;
        
            $page = isset($request["start"]) ? $request["start"] : Config::get('DocumentConstant.LABOUR_DEFAULT_START') ;
            $rowperpage = LABOUR_DEFAULT_LENGTH;
            $start = ($page - 1) * $rowperpage;

            $date = date('Y-m-d');     
            $basic_query_object = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
                ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->where('tbl_mark_attendance.user_id', $user)
                ->where('tbl_mark_attendance.is_deleted', 0)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                  ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                });  
                $totalRecords = $basic_query_object->select('tbl_mark_attendance.id')->get()->count();
                $data_output  = $basic_query_object
                ->select(
                    'tbl_mark_attendance.id',
                    'tbl_mark_attendance.project_id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'tbl_mark_attendance.attendance_day',
                    LabourAttendanceMark::raw("CONVERT_TZ(tbl_mark_attendance.updated_at, '+00:00', '+05:30') as updated_at"), 
                )->skip($start)
                ->take($rowperpage)
                ->orderBy('id', 'desc')
                ->get();
    
                foreach ($data_output as $labour) {
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                }

                // if(sizeof($data_output)>=1) {
                    if (sizeof($data_output) > 0) {
                    $totalPages = ceil($totalRecords/$rowperpage);
                } else {
                    $totalPages = 1;
                }
    
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }
    public function updateAttendanceMark(Request $request) {
        try {
            $user = Auth::user()->id;
    
            
            $validator = Validator::make($request->all(), [
                'project_id' => 'required',
                'mgnrega_card_id' => 'required',
                'attendance_day' => 'required',
            ]);
    
            
            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => $validator->errors()], 200);
            }
    
            
            $fromDate = date('Y-m-d').' 00:00:01';
            $toDate =  date('Y-m-d').' 23:59:59';

            $existingEntry = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                            ->whereBetween('updated_at', [$fromDate, $toDate])
                                            ->first();

            $fromDateHalf = date('Y-m-d').' 05:00:01';
            $toDateHalf =  date('Y-m-d').' 13:00:00';

            $firstHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                            ->whereBetween('updated_at', [$fromDateHalf, $toDateHalf])
                                            ->first();

      
            $secondHalfWorkAttendance = LabourAttendanceMark::where('mgnrega_card_id', $request->mgnrega_card_id)
                                            ->where('updated_at', '>',  date('Y-m-d').' 13:00:00')
                                            ->get()->toArray();                                

  
            if ($existingEntry) {

                if(date('Y-m-d H:i:s') <  date('Y-m-d').' 13:00:00'){
                        if ($existingEntry->project_id == $request->project_id) {
                                    $existingEntry->attendance_day = $request->attendance_day;
                                    $existingEntry->save();
                        } else {
                                    $existingEntry->project_id = $request->project_id;
                                    $existingEntry->attendance_day = $request->attendance_day;
                                    $existingEntry->save();
                        }
                }
                else{
                    $mgnregaCardCount = LabourAttendanceMark::where('user_id', $user)
                    ->where('mgnrega_card_id', $request->mgnrega_card_id)
                    ->count();
                    // dd($mgnregaCardCount);

                    if(date('Y-m-d H:i:s') >  date('Y-m-d').' 13:00:00'){
                     
                         if ($existingEntry->attendance_day == 'full_day' && $mgnregaCardCount <= 2) {
                                        if ($existingEntry->project_id != $request->project_id) {
                                            $existingEntry->attendance_day = 'half_day';
                                            $existingEntry->save();

                                            $newEntry = new LabourAttendanceMark();
                                            $newEntry->user_id = $user;
                                            $newEntry->project_id = $request->project_id;
                                            $newEntry->mgnrega_card_id = $request->mgnrega_card_id;
                                            $newEntry->attendance_day = 'half_day';
                                            $newEntry->save();

                                    return response()->json(['status' => 'true', 'message' => 'Attendance updated successfully'], 200);
                                    }

                                   elseif($existingEntry->attendance_day == 'full_day' && $mgnregaCardCount <= 2 && $existingEntry->project_id == $request->project_id){
                                            $existingEntry->attendance_day = $request->attendance_day;
                                            $existingEntry->save();
                                   }

                                else{
                                    return response()->json(['status' => 'false', 'message' => 'please select other project for 2nd half day'], 200);
                                }
                        
                        }                        
                         elseif($existingEntry->attendance_day == 'half_day'){

                            if($mgnregaCardCount <= 3 && $existingEntry->created_at <  date('Y-m-d').' 13:00:00' && $existingEntry->project_id == $request->project_id){
                                        $existingEntry->attendance_day = 'full_day';
                                        $existingEntry->save();

                                        LabourAttendanceMark::where('mgnrega_card_id', $existingEntry->mgnrega_card_id)
                                        ->where('id', '!=', $existingEntry->id)
                                        ->update(['is_deleted' => 1]);

                                }

                                else{
                                        return response()->json(['status' => 'false', 'message' => 'Please slecte 1st half project for update attedance full day'], 200);
                                    }
                                // elseif($mgnregaCardCount <= 2){
                                //     if($existingEntry->created_at > date('Y-m-d').' 13:00:00' &&  $existingEntry->project_id != $request->project_id){
                                //         $existingEntry->project_id = $request->project_id;
                                //         $existingEntry->attendance_day = 'half_day';
                                //         $existingEntry->save();
                                       
                                //     }
                                //     else{
                                //         return response()->json(['status' => 'false', 'message' => 'Attendance for this card ID Half day already marked for today'], 200);
                                //     }

                                // }
                         } 
                      
                        else{
                            return response()->json(['status' => 'false', 'message' => 'Attendance count mismatch'], 200); 
                        }                  
                    }
                }
                     
           } else {
               
                return response()->json(['status' => 'false', 'message' => 'Attendance not found'], 200);
            }
    
            return response()->json(['status' => 'true', 'message' => 'Attendance updated successfully'], 200);
    
        } catch (\Exception $e) {
           
            return response()->json(['status' => 'false', 'message' => 'Attendance mark update failed', 'error' => $e->getMessage()], 500);
        }
        }
}