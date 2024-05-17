<?php
namespace App\Http\Controllers\Api\Master;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ {
    User,
	Labour,
    Project,
    GramPanchayatDocuments,
    DistanceKM,
    LabourAttendanceMark
};
use Illuminate\Support\Facades\Config;
class ProjectController extends Controller
{
    public function getAllProjectForOfficer(Request $request){
        try {
            $user = auth()->user()->id;
            
            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $user)
            ->first();

        $utype=$data_output->user_type;
        $user_working_dist=$data_output->user_district;
        $user_working_tal=$data_output->user_taluka;
        $user_working_vil=$data_output->user_village;

        $data_user_output = User::select('user_district');

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
        // dd($data_user_output); 
            $project = Project::leftJoin('users', 'projects.district', '=', 'users.user_district')  
              ->where('projects.end_date', '>=',date('Y-m-d'))
              ->whereIn('projects.district', $data_user_output)
              ->where('projects.is_active', true)
              ->select(
                  'projects.id',
                  'projects.project_name',
				  'projects.latitude',
				  'projects.longitude',
                  'projects.start_date',
                  'projects.end_date',
              )->distinct('projects.id')
              ->orderBy('id', 'desc')
              ->get();
           
            return response()->json(['status' => 'true', 'message' => 'All data retrieved successfully', 'data' => $project], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => 'Projects List Get Fail', 'error' => $e->getMessage()], 500);
        }
    }
    public function filterDataProjectsLaboursMap(Request $request){
        try {
            $date = date('Y-m-d'); 

            $user = auth()->user()->id;
            $userLatitude = $request->latitude; 
            $userLongitude = $request->longitude; 
            $distanceInKm = DistanceKM::first()->distance_km;
            // $distanceInKm = 5; 

            $latLongArr= $this->getLatitudeLongitude($userLatitude,$userLongitude, $distanceInKm);
            $latN = $latLongArr['latN'];
            $latS = $latLongArr['latS'];
            $lonE = $latLongArr['lonE'];
            $lonW = $latLongArr['lonW'];

            $labourQuery =  LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
            ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')    
            ->where('labour.user_id', $user)
                ->where('labour.is_approved', 2)
                 ->whereDate('tbl_mark_attendance.updated_at', $date)
                ->where('tbl_mark_attendance.is_deleted', 0)
                ->when($request->has('mgnrega_card_id'), function($query) use ($request) {
                    $query->where('labour.mgnrega_card_id', 'like', '%' . $request->mgnrega_card_id . '%');
                })
                ->select(
                    'tbl_mark_attendance.id',
                    'labour.full_name',
                    User::raw("CONCAT(users.f_name, COALESCE(CONCAT(' ', users.m_name), ''),' ', users.l_name) AS gramsevak_full_name"),
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'tbl_mark_attendance.attendance_day',
                    // LabourAttendanceMark::raw("CONVERT_TZ(tbl_mark_attendance.updated_at, '+00:00', '+05:30') as updated_at"), 
                    LabourAttendanceMark::raw("tbl_mark_attendance.updated_at AT TIME ZONE 'UTC' AT TIME ZONE 'Asia/Kolkata' as updated_at"), 
                    )
                    // ->distinct('labour.id')
                ->orderBy('tbl_mark_attendance.id', 'desc');
    
                $projectQuery = Project:: leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->leftJoin('tbl_area as taluka_projects', 'projects.taluka', '=', 'taluka_projects.location_id')
               ->leftJoin('tbl_area as village_projects', 'projects.village', '=', 'village_projects.location_id')
                ->where('projects.is_active', true)
                ->where('projects.end_date', '>=', now())
                ->when($request->has('latitude'), function($query) use ($latN, $latS, $lonE, $lonW) {
                    $query->where('projects.latitude', '<=', $latN)
                        ->where('projects.latitude', '>=', $latS)
                        ->where('projects.longitude', '<=', $lonE)
                        ->where('projects.longitude', '>=', $lonW);
                })
                
                ->when($request->has('project_name'), function($query) use ($request) {
                    $query->where('projects.project_name', 'like', '%' . $request->project_name . '%');
                })
                ->select(
                    'projects.id',
                    'projects.project_name',
                    'projects.start_date',
                    'projects.end_date',
                    'projects.latitude',
                    'projects.longitude',
                    'projects.district',
                    'district_projects.name as district_name',
                    'projects.taluka',
                    'taluka_projects.name as taluka_name',
                    'projects.village',
                    'village_projects.name as village_name',
                )->orderBy('projects.id', 'desc');
                // ->distinct('projects.id')
                // ->orderBy('id', 'desc');

                $gramsevakdocumentQuery = GramPanchayatDocuments::where('tbl_gram_panchayat_documents.user_id', $user)
                ->where('tbl_gram_panchayat_documents.is_approved', 2)
                ->select(
                    'tbl_gram_panchayat_documents.id',
                    'tbl_gram_panchayat_documents.document_name',
                    'tbl_gram_panchayat_documents.latitude',
                    'tbl_gram_panchayat_documents.longitude',
                    'tbl_gram_panchayat_documents.document_pdf',
                )
                ->orderBy('id', 'desc');
            
            // Fetch data
            $labourData = $labourQuery->get();
            $projectData = $projectQuery->get();
            $documentData = $gramsevakdocumentQuery->get();

            $labourData_array_final = [];
            foreach ($labourData as $key => $value) {
                $labourData_array = [];
                $labourData_array['id'] = $value->mgnrega_card_id;
                $labourData_array['name'] = $value->full_name;
                $labourData_array['mgnrega_card_id'] = $value->mgnrega_card_id;
                $labourData_array['latitude'] = $value->latitude;
                $labourData_array['longitude'] = $value->longitude;              
                $labourData_array['type'] = 'labour';
                array_push($labourData_array_final, $labourData_array);
            }

            $projectData_array_final = [];
            foreach ($projectData as $key => $value) {
                $projectData_array = [];
                $projectData_array['id'] = $value->id;
                $projectData_array['name'] = $value->project_name;
                $projectData_array['latitude'] = $value->latitude;
                $projectData_array['longitude'] = $value->longitude;
                $projectData_array['district'] = $value->district;
                $projectData_array['district_name'] = $value->district_name;
                $projectData_array['taluka'] = $value->taluka;
                $projectData_array['taluka_name'] = $value->taluka_name;
                $projectData_array['village'] = $value->village;
                $projectData_array['village_name'] = $value->village_name;
                $projectData_array['type'] = 'project';
                array_push($labourData_array_final, $projectData_array);
            }

            $documentData_array_final = [];
            foreach ($documentData as $key => $value) {
                $documentData_array = [];
                $documentData_array['id'] = $value->id;
                $documentData_array['document_name'] = $value->document_name;
                $documentData_array['latitude'] = $value->latitude;
                $documentData_array['longitude'] = $value->longitude;
                $documentData_array['document_pdf'] = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $value->document_pdf;
                $documentData_array['type'] = 'document';
                array_push($labourData_array_final, $documentData_array);
            }
            foreach ($documentData as $document_data) {
                $document_data->document_pdf = Config::get('DocumentConstant.GRAM_PANCHAYAT_DOC_VIEW') . $document_data->document_pdf;
            }
            $finalData = $labourData_array_final + $projectData_array_final + $documentData_array_final;
            // Check if mgnrega_card_id filter applied and adjust response accordingly
            if ($request->has('mgnrega_card_id')) {
                return response()->json([
                    'status' => 'true', 
                    'message' => 'Filtered labour data retrieved successfully', 
                    'labour_data' => $labourData
                ], 200);
            }
            elseif ($request->has('project_name')) {
                return response()->json([
                    'status' => 'true', 
                    'message' => 'Filtered project data retrieved successfully', 
                    'project_data' => $projectData
                ], 200);
            }  elseif ($request->has('want_project_data')) {
                return response()->json([
                    'status' => 'true', 
                    'message' => 'Filtered project data retrieved successfully', 
                    'project_data' => $projectData
                ], 200);
            }
            else {
                return response()->json([
                    'status' => 'true', 
                    'message' => 'Filtered data retrieved successfully', 
                    'map_data' => $finalData,
                    'project_data' => $projectData,
                    'labour_data' => $labourData
                ], 200);
            }
        } catch (\Exception $e) {
            
            return response()->json(['status' => 'false', 'message' => 'Data get failed '.$e->getMessage()], 500);
        }
    }
    public function getLatitudeLongitude($latitude,$longitude, $distanceInKm){
        $d = 0.621371*$distanceInKm; // 15 km in miles
        $r = 3959; //earth's radius in miles
        $latLongArr = array();
        
        $latN = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
                + cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(0))));

        $latS = rad2deg(asin(sin(deg2rad($latitude)) * cos($d / $r)
                + cos(deg2rad($latitude)) * sin($d / $r) * cos(deg2rad(180))));

        $lonE = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(90))
                * sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
                - sin(deg2rad($latitude)) * sin(deg2rad($latN))));

        $lonW = rad2deg(deg2rad($longitude) + atan2(sin(deg2rad(270))
                * sin($d / $r) * cos(deg2rad($latitude)), cos($d / $r)
                - sin(deg2rad($latitude)) * sin(deg2rad($latN))));

        $latLongArr = 
        [
            'pincodeLatitude' => $latitude,
            'pincodeLongitude' => $longitude,
            'latN' => $latN,
            'latS' => $latS,
            'lonE' => $lonE,
            'lonW' => $lonW
        ];
        return $latLongArr;
    }
            
}


