<?php

namespace App\Http\Controllers\Admin\Reports;
use Illuminate\Support\Facades\DB;
// use Maatwebsite\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
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
    Skills
};
use Illuminate\Validation\Rule;
use App\Http\Services\Admin\Reports\ReportsServices;
use Validator;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LabourExport;
use Carbon\Carbon;
// use Maatwebsite\Excel\Concerns\FromCollection;
class ReportsController extends Controller
{

    public function __construct()
    {
        $this->service = new ReportsServices();
    }
    public function getAllLabourLocation()
    {
        try {

            $sess_user_id=session()->get('user_id');
            $sess_user_type=session()->get('user_type');
            $sess_user_role=session()->get('role_id');
            $sess_user_working_dist=session()->get('working_dist');

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            $district_data = TblArea::where('parent_id', '2')
                        ->orderBy('name', 'asc')
                        ->get(['location_id', 'name']);

            $taluka_data=TblArea::where('parent_id', $sess_user_working_dist)
                        ->orderBy('name', 'asc')
                        ->get(['location_id', 'name']);

            $village_data=TblArea::where('location_id', $user_working_vil)
                        ->orderBy('name', 'asc')
                        ->get(['location_id', 'name']);       
            // dd($village_data);                 

            $skills_data = Skills::where('is_active', 1) // 4 represents cities
                        ->whereNot('id', '1')
                        ->orderBy('skills', 'asc')
                        ->get(['id', 'skills as skill_name']);

            $registration_status_data = Registrationstatus::where('is_active', 1) // 4 represents cities
                        ->orderBy('status_name', 'asc')
                        ->get(['id', 'status_name']);
            // dd($skills_data);            

            $labours = $this->service->getAllLabourLocation();
            // dd($labours);
            return view('admin.pages.reports.list-location-report', compact('labours','district_data','taluka_data','skills_data','registration_status_data','village_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
 

    public function getAllLabourDuration()
    {
        $skills_data = Skills::where('is_active', 1) // 4 represents cities
                        ->whereNot('id', '1')
                        ->orderBy('skills', 'asc')
                        ->get(['id', 'skills as skill_name']);

        $district_data = TblArea::where('parent_id', '2')
                        ->orderBy('name', 'asc')
                        ->get(['location_id', 'name']);
        try {
            $labours = $this->service->getAllLabourLocation();
            return view('admin.pages.reports.list-labour-attendance-report',compact('labours','skills_data','district_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
    public function getAllProjects()
    {
        try {
            $sess_user_id=session()->get('user_id');
            $sess_user_type=session()->get('user_type');
            $sess_user_role=session()->get('role_id');

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

			$data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
                ->where('users.id', $sess_user_id)
                ->first();

                if($utype=='1')
            {
                $projects_data = Project::where('is_active', 1)
                        ->where('projects.district',$user_working_dist)
                        ->orderBy('project_name', 'asc')
                        ->get(['id', 'project_name']);
            }else if($utype=='2')
            {
                $projects_data = Project::where('is_active', 1)
                        ->where('projects.taluka',$user_working_tal)
                        ->orderBy('project_name', 'asc')
                        ->get(['id', 'project_name']);
            }else if($utype=='3')
            {
                $projects_data = Project::where('is_active', 1)
                        ->where('projects.village',$user_working_vil)
                        ->orderBy('project_name', 'asc')
                        ->get(['id', 'project_name']);
            }else
            {
                $projects_data = Project::where('is_active', 1)
                        ->orderBy('project_name', 'asc')
                        ->get(['id', 'project_name']);
            } 
            $skills_data = Skills::where('is_active', 1) // 4 represents cities
                        ->whereNot('id', '1')
                        ->orderBy('skills', 'asc')
                        ->get(['id', 'skills as skill_name']);

            $registration_status_data = Registrationstatus::where('is_active', 1) // 4 represents cities
                        ->orderBy('status_name', 'asc')
                        ->get(['id', 'status_name']);

            $district_data = TblArea::where('parent_id', '2')
                        ->orderBy('name', 'asc')
                        ->get(['location_id', 'name']);
           
            return view('admin.pages.reports.list-project-report', compact('projects_data','skills_data','registration_status_data','district_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }
   

    public function getAllProjectLocation()
    {
        try {

            $projects_data = Project::where('is_active', 1) // 4 represents cities
                        ->orderBy('project_name', 'asc')
                        ->get(['id', 'project_name']);

            $skills_data = Skills::where('is_active', 1) // 4 represents cities
                        ->whereNot('id', '1')
                        ->orderBy('skills', 'asc')
                        ->get(['id', 'skills as skill_name']);

            $registration_status_data = Registrationstatus::where('is_active', 1) // 4 represents cities
                        ->orderBy('status_name', 'asc')
                        ->get(['id', 'status_name']);

            // $getOutput = $this->service->getAllLabourLocation();
            // dd($getOutput);
            return view('admin.pages.reports.list-project-and-location-report', compact('projects_data','skills_data','registration_status_data'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getFilterLaboursReport(Request $request)
    {
        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');

       $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');
        $SkillId = $request->input('SkillId');
        $RegistrationStatusId = $request->input('RegistrationStatusId');

            if($sess_user_role=='1')
		{
            $query_user = User::where('users.role_id','3')
                ->select('id');
                if ($request->filled('districtId')) {
                    $query_user->where('users.user_district', $districtId);
                }
                if ($request->filled('talukaId')) {
                    $query_user->where('users.user_taluka', $talukaId);
                }
                if ($request->filled('villageId')) {
                    $query_user->where('users.user_village', $villageId);
                }

               $data_user_output=$query_user->get();


     	$query = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
		->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
		->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
		->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
		->leftJoin('users', 'labour.user_id', '=', 'users.id')
		// ->where('labour.is_approved', $RegistrationStatusId)
        
        ->when($request->get('districtId') || $request->get('talukaId') || $request->get('villageId'), function($query) use ($request, $data_user_output) {
            $query->whereIn('labour.user_id',$data_user_output);
        })
        ->when($request->get('SkillId'), function($query) use ($request) {
            $query->where('labour.skill_id', $request->SkillId);
        })  

        ->when($request->get('RegistrationStatusId'), function($query) use ($request) {
            $query->where('labour.is_approved', $request->RegistrationStatusId);
        }) 
          ->select(
			'labour.id',
			'labour.full_name',
			'labour.date_of_birth',
			'gender_labour.gender_name as gender_name',
			'district_labour.name as district_id',
			'taluka_labour.name as taluka_id',
			'village_labour.name as village_id',
			'labour.mobile_number',
			'labour.landline_number',
			'labour.mgnrega_card_id',
			'labour.aadhar_image',
			'labour.mgnrega_image', 
			'labour.profile_image', 
			'labour.voter_image', 
			'labour.is_active',
			'labour.is_approved',
			'labour.skill_id',
			'users.f_name',
			'users.m_name',
			'users.l_name',
          );

          $data_output = $query->get();

		  }else if($sess_user_role=='2')
		  {
            

            $query_user= User::where('users.role_id','3');
            if ($request->filled('talukaId')) {
                $query_user->where('users.user_taluka',$talukaId);
            }
            if ($request->filled('villageId')) {
                $query_user->where('users.user_village',$villageId);
            }
                
                $data_user_output=$query_user->select('id')->get();

                $query = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
				->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->where('registrationstatus.is_active', true)
                ->when($request->get('RegistrationStatusId'), function($query) use ($request) {
                    $query->where('labour.is_approved', $request->RegistrationStatusId);
                })
        
                ->when($request->get('districtId') || $request->get('talukaId') || $request->get('villageId'), function($query) use ($request, $data_user_output) {
                    $query->whereIn('labour.user_id',$data_user_output);
                })
                ->when($request->get('SkillId'), function($query) use ($request) {
                    $query->where('labour.skill_id', $request->SkillId);
                })  
                ->select(
                    'labour.id',
                    'labour.full_name',
                    'labour.date_of_birth',
                    'gender_labour.gender_name as gender_name',
                    'skills_labour.skills as skills',
                    'district_labour.name as district_id',
                    'taluka_labour.name as taluka_id',
                    'village_labour.name as village_id',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'registrationstatus.status_name',
					'labour.is_approved',
					'users.f_name',
					'users.m_name',
					'users.l_name',
                );
    
               
               $data_output = $query->get();

            }else if($sess_user_role=='3')
            {
              
  
              $query_user= User::where('users.role_id','3');
             
              if ($request->filled('villageId')) {
                  $query_user->where('users.user_village',$villageId);
              }
                  
                  $data_user_output=$query_user->select('id')->get();
  
                  $query = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                  ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                  ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                  ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                  ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                  ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
                  ->leftJoin('users', 'labour.user_id', '=', 'users.id')
                  ->where('registrationstatus.is_active', true)
                  ->when($request->get('RegistrationStatusId'), function($query) use ($request) {
                      $query->where('labour.is_approved', $request->RegistrationStatusId);
                  })
          
                  ->when($request->get('villageId'), function($query) use ($request, $data_user_output) {
                      $query->where('labour.user_id',$sess_user_id);
                  })
                  ->when($request->get('SkillId'), function($query) use ($request) {
                      $query->where('labour.skill_id', $request->SkillId);
                  })  
                  ->select(
                      'labour.id',
                      'labour.full_name',
                      'labour.date_of_birth',
                      'gender_labour.gender_name as gender_name',
                      'skills_labour.skills as skills',
                      'district_labour.name as district_id',
                      'taluka_labour.name as taluka_id',
                      'village_labour.name as village_id',
                      'labour.mobile_number',
                      'labour.landline_number',
                      'labour.mgnrega_card_id',
                      'labour.latitude',
                      'labour.longitude',
                      'labour.profile_image',
                      'registrationstatus.status_name',
                      'labour.is_approved',
                      'users.f_name',
                      'users.m_name',
                      'users.l_name',
                  );
      
                 
                 $data_output = $query->get();
  
              }
                return response()->json(['labour_ajax_data' => $data_output]);

            // } catch (\Exception $e) {
            //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            // }

    }

    public function getFilterProjectsReport(Request $request)
    {
        // dd($request);
        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');

        $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');
        $ProjectId = $request->input('ProjectId');
        $SkillId = $request->input('SkillId');
        // $RegistrationStatusId = $request->input('RegistrationStatusId');

            if($sess_user_role=='1')
		{
            $query_user = User::where('users.role_id','3')
                ->select('id');
                if ($request->filled('districtId')) {
                    $query_user->where('users.user_district', $districtId);
                }
                if ($request->filled('talukaId')) {
                    $query_user->where('users.user_taluka', $talukaId);
                }
                if ($request->filled('villageId')) {
                    $query_user->where('users.user_village', $villageId);
                }

               $data_user_output=$query_user->get();


            $query = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
            ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
            ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
            ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
            ->leftJoin('users', 'labour.user_id', '=', 'users.id')
            ->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id')
            ->join('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
            ->when($request->get('ProjectId'), function($query) use ($request) {
                $query->where('tbl_mark_attendance.project_id', $request->ProjectId);
            })
            ->when($request->get('districtId'), function($query) use ($request) {
                $query->where('projects.district', $request->districtId);
            })
            ->when($request->get('talukaId'), function($query) use ($request) {
                $query->where('projects.taluka', $request->talukaId);
            })
            ->when($request->get('villageId'), function($query) use ($request) {
                $query->where('projects.village', $request->villageId);
            })
            ->when($request->get('SkillId'), function($query) use ($request) {
                // dd($request->SkillId);
                $query->where('labour.skill_id', $request->SkillId);
            })  
            ->select(
                'labour.id',
                'labour.full_name',
                'labour.date_of_birth',
                'gender_labour.gender_name as gender_name',
                'district_labour.name as district_id',
                'taluka_labour.name as taluka_id',
                'village_labour.name as village_id',
                'labour.mobile_number',
                'labour.landline_number',
                'labour.mgnrega_card_id',
                'labour.aadhar_image',
                'labour.mgnrega_image', 
                'labour.profile_image', 
                'labour.voter_image', 
                'labour.is_active',
                'labour.is_approved',
                'labour.skill_id',
                'users.f_name',
                'users.m_name',
                'users.l_name',
                'projects.project_name as pro_name',
                'tbl_mark_attendance.created_at',
            );

                    //           $sql = $query->toSql();
                    // return $sql;
            $data_output = $query->get();

		  }else if($sess_user_role=='2')
		  {
            

            $query_user= User::where('users.role_id','3');
            if ($request->filled('talukaId')) {
                $query_user->where('users.user_taluka',$talukaId);
            }
            if ($request->filled('villageId')) {
                $query_user->where('users.user_village',$villageId);
            }
                
                $data_user_output=$query_user->select('id')->get();

                $query = Labour::leftJoin('registrationstatus', 'labour.is_approved', '=', 'registrationstatus.id')
                ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
                ->leftJoin('skills as skills_labour', 'labour.gender_id', '=', 'skills_labour.id')
                ->leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
                ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
				->leftJoin('users', 'labour.user_id', '=', 'users.id')
                ->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id')
                ->join('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                // ->where('tbl_mark_attendance.project_id', $ProjectId)
                ->where('registrationstatus.is_active', true)
                ->whereIn('tbl_mark_attendance.user_id',$data_user_output)
                ->when($request->get('ProjectId'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id', $request->ProjectId);
                })
                ->when($request->get('districtId'), function($query) use ($request) {
                    $query->where('projects.district', $request->districtId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('projects.taluka', $request->talukaId);
                })
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('projects.taluka', $request->villageId);
                })
                ->when($request->get('SkillId'), function($query) use ($request) {
                    $query->where('labour.skill_id', $request->SkillId);
                })  
                ->select(
                    'labour.id',
                    'labour.full_name',
                    'labour.date_of_birth',
                    'gender_labour.gender_name as gender_name',
                    'skills_labour.skills as skills',
                    'district_labour.name as district_id',
                    'taluka_labour.name as taluka_id',
                    'village_labour.name as village_id',
                    'labour.mobile_number',
                    'labour.landline_number',
                    'labour.mgnrega_card_id',
                    'labour.latitude',
                    'labour.longitude',
                    'labour.profile_image',
                    'registrationstatus.status_name',
					'labour.is_approved',
					'users.f_name',
					'users.m_name',
					'users.l_name',
                    'projects.project_name as pro_name',
                    // 'tbl_mark_attendance.created_at',
                    
                );
               
               $data_output = $query->get();

            }
                return response()->json(['labour_ajax_data' => $data_output]);

            // } catch (\Exception $e) {
            //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            // }

    }

    public function getExportLabourLocation(Request $request)
{
    $districtId = $request->input('districtId');
    $talukaId = $request->input('talukaId');
    $villageId = $request->input('villageId');
    $SkillId = $request->input('SkillId');
    $RegistrationStatusId = $request->input('RegistrationStatusId');

    $query = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
        ->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
        ->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
        ->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
        ->leftJoin('users', 'labour.user_id', '=', 'users.id')
        ->leftJoin('tbl_mark_attendance', 'labour.mgnrega_card_id', '=', 'tbl_mark_attendance.mgnrega_card_id')
        ->join('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
        ->when($SkillId, function($query) use ($SkillId) {
            $query->where('labour.skill_id', $SkillId);
        })  
        ->select(
            'labour.id',
            'labour.full_name',
            'labour.date_of_birth',
            'gender_labour.gender_name as gender_name',
            'district_labour.name as district_id',
            'taluka_labour.name as taluka_id',
            'village_labour.name as village_id',
            'labour.mobile_number',
            'labour.landline_number',
            'labour.mgnrega_card_id',
            'labour.aadhar_image',
            'labour.mgnrega_image', 
            'labour.profile_image', 
            'labour.voter_image', 
            'labour.is_active',
            'labour.is_approved',
            'labour.skill_id',
            'users.f_name',
            'users.m_name',
            'users.l_name',
        );

    if ($districtId) {
        $query->where('users.user_district', $districtId);
    }
    if ($talukaId) {
        $query->where('users.user_taluka', $talukaId);
    }
    if ($villageId) {
        $query->where('users.user_village', $villageId);
    }

    $data_output = $query->get();

    return Excel::download(new LabourExport($data_output), 'labour_data.xlsx');
}

public function getFilterLaboursdurationReport(Request $request)
{
    $sess_user_id=session()->get('user_id');
    $sess_user_type=session()->get('user_type');
    $sess_user_role=session()->get('role_id');
    $sess_user_working_dist=session()->get('working_dist');

   $districtId = $request->input('districtId');
    $talukaId = $request->input('talukaId');
    $villageId = $request->input('villageId');
    $SkillId = $request->input('SkillId');
    $monthId = $request->input('monthId');
    $yearId = $request->input('yearId');
    $weekNumber = $request->input('weekNumber');

    // $startDate = Carbon::createFromFormat('Y-m', $yearId . '-' . $monthId)->startOfMonth();
    // $endDate = $startDate->copy()->endOfMonth();

    if ($weekNumber) {
        // If week number is provided, calculate dates based on the week number
        $startDate = Carbon::createFromFormat('Y-m-d', $yearId . '-' . $monthId . '-01')->startOfWeek()->addWeeks($weekNumber - 1);
        $endDate = $startDate->copy()->endOfWeek();
    } else {
        // If week number is not provided, calculate dates based on month and year
        $startDate = Carbon::createFromFormat('Y-m-d', $yearId . '-' . $monthId . '-01')->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
    }

        if($sess_user_role=='1')
    {
        $query_user = User::where('users.role_id','3')
                ->select('id');
                if ($request->filled('districtId')) {
                    $query_user->where('users.user_district', $districtId);
                }
                if ($request->filled('talukaId')) {
                    $query_user->where('users.user_taluka', $talukaId);
                }
                if ($request->filled('villageId')) {
                    $query_user->where('users.user_village', $villageId);
                }

               $data_user_output=$query_user->get();

        
        $query = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
        ->leftJoin('users', 'labour.user_id', '=', 'users.id')
        ->leftJoin('skills', 'labour.skill_id', '=', 'skills.id')
        ->whereBetween('tbl_mark_attendance.created_at', [$startDate, $endDate])
        ->when($request->get('districtId') || $request->get('talukaId') || $request->get('villageId'), function($query) use ($request, $data_user_output) {
            $query->whereIn('tbl_mark_attendance.user_id',$data_user_output);
        })
        ->when($request->get('SkillId'), function($query) use ($request) {
            $query->where('labour.skill_id', $request->SkillId);
        })  
        ->select(
            'skills.id as skill_id',
            'skills.skills as skill_name',
            DB::raw('COUNT(tbl_mark_attendance.id) as attendance_count') // Count attendance records
        )
        ->groupBy('skills.id','skills.skills'); // Group by skill id

            $data_output = $query->get();

            // Calculate total working days in the specified month
            $totalWorkingDays = $startDate->daysInMonth;

            $labour_ajax_data = [];
            // Calculate average attendance percentage for each skill
            foreach ($data_output as $skill) {
                $attendancePercentage = ($skill->attendance_count / $totalWorkingDays) * 100;
                $averageAttendancePercentage = round($attendancePercentage, 2);

                $labour_ajax_data[] = [
                    'skill_id' => $skill->skill_id,
                    'skill_name' => $skill->skill_name,
                    'average_attendance_percentage' => $averageAttendancePercentage
                ];
            }
            // dd($labour_ajax_data);
 
      }else if($sess_user_role=='2')
      {
        

        $query_user = User::where('users.role_id','3')
                ->select('id');
                if ($request->filled('districtId')) {
                    $query_user->where('users.user_district', $districtId);
                }
                if ($request->filled('talukaId')) {
                    $query_user->where('users.user_taluka', $talukaId);
                }
                if ($request->filled('villageId')) {
                    $query_user->where('users.user_village', $villageId);
                }

               $data_user_output=$query_user->get();
            
            // $data_user_output=$query_user->select('id')->get();

            $query = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
        ->leftJoin('users', 'labour.user_id', '=', 'users.id')
        ->leftJoin('skills', 'labour.skill_id', '=', 'skills.id')
        ->whereBetween('tbl_mark_attendance.created_at', [$startDate, $endDate])
        ->when($request->get('districtId') || $request->get('talukaId') || $request->get('villageId'), function($query) use ($request, $data_user_output) {
            $query->whereIn('tbl_mark_attendance.user_id',$data_user_output);
        })
        ->when($request->get('SkillId'), function($query) use ($request) {
            $query->where('labour.skill_id', $request->SkillId);
        })  
        ->select(
            'skills.id as skill_id',
            'skills.skills as skill_name',
            DB::raw('COUNT(tbl_mark_attendance.id) as attendance_count') // Count attendance records
        )
        ->groupBy('skills.id','skills.skills'); // Group by skill id

            $data_output = $query->get();

            // Calculate total working days in the specified month
            $totalWorkingDays = $startDate->daysInMonth;

            $labour_ajax_data = [];
            // Calculate average attendance percentage for each skill
            foreach ($data_output as $skill) {
                $attendancePercentage = ($skill->attendance_count / $totalWorkingDays) * 100;
                $averageAttendancePercentage = round($attendancePercentage, 2);

                $labour_ajax_data[] = [
                    'skill_id' => $skill->skill_id,
                    'skill_name' => $skill->skill_name,
                    'average_attendance_percentage' => $averageAttendancePercentage
                ];
            }

           
        //    $data_output = $query->get();

        }

        // dd($labour_ajax_data);
            return response()->json(['labour_ajax_data' => $labour_ajax_data]);

        // } catch (\Exception $e) {
        //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        // }

}
}