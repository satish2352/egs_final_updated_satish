<?php

namespace App\Http\Controllers\Api\Labour;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
    Labour,
    Project,
    LabourFamilyDetails,
	LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
use Storage;
use Carbon\Carbon;

class AttendanceMarkVisibleForOfficerController extends Controller
{
    public function getAllAttendanceMarkedLabour(Request $request) {
        try {
            $user = auth()->user()->id;            
            $date = date('Y-m-d'); 
           
            $fromDate = date('Y-m-d', strtotime($request->input('from_date')));
            $fromDate =  $fromDate.' 00:00:01';
            $toDate = date('Y-m-d', strtotime($request->input('to_date')));
            $toDate =  $toDate.' 23:59:59';

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
        if($utype=='1') {
            $data_user_output = $data_user_output->where('users.user_district', $user_working_dist);
        } else if($utype=='2') {
            $data_user_output = $data_user_output->where('users.user_taluka', $user_working_tal);
        } else if($utype=='3') {
            $data_user_output = $data_user_output->where('users.user_village', $user_working_vil);
        }

        $data_user_output = $data_user_output->get()->toArray();  

        // dd($data_user_output);
            $basic_query_object = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
            ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
            ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
            // ->where('tbl_mark_attendance.user_id', $data_user_output)
                ->where('users.user_district', $user_working_dist)
                ->whereDate('tbl_mark_attendance.updated_at', $date)
                ->when($request->get('project_id'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id',$request->project_id);
                })
                ->when($request->get('user_taluka'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->user_taluka);
                })  
                ->when($request->get('user_village'), function($query) use ($request) {
                    $query->where('users.user_village', $request->user_village);
                })

                ->when($request->get('from_date'), function($query) use ($fromDate, $toDate) {
                    $query->whereBetween('tbl_mark_attendance.updated_at', [$fromDate, $toDate]);
                });
                
                $totalRecords = $basic_query_object->select('tbl_mark_attendance.id')->get()->count();
                
                $data_output = $basic_query_object
                ->select(
                    'tbl_mark_attendance.id',
                    'users.f_name',
                    'tbl_mark_attendance.project_id',
                    'projects.project_name',
                    'labour.full_name as full_name',
                    'labour.date_of_birth',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'users.user_taluka',
                    'taluka_labour.name as taluka_name',
                    'users.user_village',
                    'village_labour.name as village_name',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'tbl_mark_attendance.attendance_day',
                    LabourAttendanceMark::raw("CONVERT_TZ(tbl_mark_attendance.updated_at, '+00:00', '+05:30') as updated_at"), 
                )->distinct('tbl_mark_attendance.id')
                ->skip($start)
                ->take($rowperpage)
                ->orderBy('id', 'desc')
                ->get();
    
                foreach ($data_output as $labour) {
                    $labour->profile_image = Config::get('DocumentConstant.USER_LABOUR_VIEW') . $labour->profile_image;
                }
                if(sizeof($data_output)>=1) {
                    $totalPages = ceil($totalRecords/$rowperpage);
                } else {
                    $totalPages = 1;
                }
                return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', "totalRecords" => $totalRecords, "totalPages"=>$totalPages, 'page_no_to_hilight'=>$page, 'data' => $data_output], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Attendance List Fail','error' => $e->getMessage()], 500);
        }
    }
    
}
