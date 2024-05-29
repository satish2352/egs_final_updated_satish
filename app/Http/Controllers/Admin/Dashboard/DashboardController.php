<?php

namespace App\Http\Controllers\Admin\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use App\Http\Services\DashboardServices;
use App\Models\ {
    Permissions,
    User,
    RTI,
    VacanciesHeader,
    DepartmentInformation,
    DisasterForcast,
    Gallery,
    Video,
    Event,
    Project,
    TblArea,
    Labour,
    GramPanchayatDocuments,
    DistanceKM
};
use Validator;

class DashboardController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        // $this->service = new DashboardServices();
    }

public function index(Request $request)
{
   
    $fromDate = date('Y-m-d').' 00:00:01';
    $toDate =  date('Y-m-d').' 23:59:59';

        $sess_user_id = session()->get('user_id');
       
        $sess_user_type = session()->get('user_type');
      
        $sess_user_role = session()->get('role_id');
      
        $sess_user_working_dist = session()->get('working_dist');

        $district_data = TblArea::where('parent_id', '2')
            ->orderBy('name', 'asc')
            ->get(['location_id', 'name']);
        
        $taluka_data = TblArea::where('parent_id', $sess_user_working_dist)
            ->orderBy('name', 'asc')
            ->get(['location_id', 'name']);
        
        $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $sess_user_id)
            ->first();
        
        $utype = $data_output->user_type;
        $roleId = $data_output->role_id;
        // dd($roleId);
        $user_working_dist = $data_output->user_district;
        // dd($user_working_dist);
        $user_working_tal = $data_output->user_taluka;
        $user_working_vil = $data_output->user_village;

        $sess_user_working_dist = session()->get('working_dist');
        $sess_user_working_vil = session()->get('working_vil');

        if (!$sess_user_id) {
            throw new \Exception('User not authenticated.');
        }

        $labourRequestCounts = [
            'Sent For Approval Labours' => 0,
            'Approved Labours' => 0,
            'Not Approved Labours' => 0,
            'Resubmitted Labours' => 0,
        ];
        
        $documentRequestCounts = [
            'Sent For Approval Documents' => 0,
            'Approved Documents' => 0,
            'Not Approved Documents' => 0,
            'Resubmitted Documents' => 0,
        ];

        if ($roleId== 1) {
            $userCount = User::where('role_id', 2)
                                ->orWhere('role_id', 3)
                                ->count();

              $projectCount= Project::where('projects.is_active', true)
              ->count();  
                        
              $projectCountCompleted= Project::where('projects.end_date', '=',date('Y-m-d'))
              //   ->whereIn('projects.District', $data_user_output)
                ->where('projects.is_active', true)
                ->count();
                
            $todayCount = Labour::where('updated_at', '>=', $fromDate)
            ->where('updated_at', '<=', $toDate)
            // ->where('is_approved', 2)
            ->get()
            ->count();

            $currentYearCount = Labour::whereYear('updated_at', date('Y'))
                // ->where('is_approved', 2)
                ->get()
                ->count();

            
            $labourCounts = Labour::selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                // ->where('is_resubmitted', 0)
                ->groupBy('is_approved','is_resubmitted')
                ->get();
            
            foreach ($labourCounts as $count) {
                if ($count->is_approved == 1 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Sent For Approval Labours'] += $count->count;
                } elseif ($count->is_approved == 2 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Approved Labours'] += $count->count;
                } elseif ($count->is_approved == 3 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Not Approved Labours'] += $count->count;
                } elseif ($count->is_approved == 1 && $count->is_resubmitted == 1) {
                    $labourRequestCounts['Resubmitted Labours'] += $count->count;
                }
            }
            $documentCounts = GramPanchayatDocuments::selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                // ->where('is_resubmitted', 0)
                ->groupBy('is_approved','is_resubmitted')
                ->get();
            foreach ($documentCounts as $countdoc) {
                if ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Sent For Approval Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 2  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Approved Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 3  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Not Approved Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 1) {
                    $documentRequestCounts['Resubmitted Documents'] += $countdoc->count;
                }
            }

            // $documentRequestCounts['resubmitted_document_count'] = GramPanchayatDocuments::where('user_id', $sess_user_id)
            //     ->where('is_resubmitted', 1)
            //     ->where('is_approved', 1)
            //     ->count();

           
        }

        elseif ($roleId== 2 && $utype == 1) {

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $sess_user_id)
            ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            if($utype=='1')
            {
            $data_user_output = User::where('users.user_district', $user_working_dist)
            ->select('id')
                ->get()
				->toArray();
            }else if($utype=='2')
            {
                $data_user_output = User::where('users.user_taluka', $user_working_tal)
                ->select('id')
                ->get()
				->toArray();
            }else if($utype=='3')
            {
                $data_user_output = User::where('users.user_village', $user_working_vil)
                ->select('id')
                ->get()
				->toArray();
            } 
            
            $userCount = User::where('role_id', 2)
            ->orWhere('role_id', 3)
            ->where('id', $user_working_dist)
            ->count();


            $projectCount= Project::leftJoin('users', 'projects.district', '=', 'users.user_district')  
            // ->where('projects.end_date', '>=',date('Y-m-d'))
            // ->whereIn('projects.District', $user_working_dist)
            ->where('projects.is_active', true)
            ->where('projects.district', $user_working_dist)
            ->groupBy('users.id')
            ->count();      
           
            $projectCountCompleted= Project::leftJoin('users', 'projects.district', '=', 'users.user_district')  
            ->where('projects.end_date', '<=',date('Y-m-d'))
            // ->whereIn('projects.District', $user_working_dist)
            ->where('projects.is_active', true)
            ->where('users.user_district', $user_working_dist)
            ->count();  
           

            

            

             $todayCount = Labour::where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->whereIn('labour.user_id',$data_user_output)
            ->get()
            ->count();

            $currentYearCount = Labour::whereYear('updated_at', date('Y'))
                // ->where('user_id', $sess_user_id)
                ->whereIn('labour.user_id',$data_user_output)

                // ->where('is_approved', 2)
                ->get()
                ->count();

            $labourCounts = Labour::whereIn('labour.user_id',$data_user_output)
                ->selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                // ->where('is_resubmitted', 0)
                ->groupBy('is_approved','is_resubmitted')
                ->get();
 

            foreach ($labourCounts as $count) {
                if ($count->is_approved == 1 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Sent For Approval Labours'] += $count->count;
                } elseif ($count->is_approved == 2 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Approved Labours'] += $count->count;
                } elseif ($count->is_approved == 3 && $count->is_resubmitted == 0) {
                    $labourRequestCounts['Not Approved Labours'] += $count->count;
                } elseif ($count->is_approved == 1 && $count->is_resubmitted == 1) {
                    $labourRequestCounts['Resubmitted Labours'] += $count->count;
                }
            }    

            $documentCounts = GramPanchayatDocuments::whereIn('tbl_gram_panchayat_documents.user_id',$data_user_output)
                ->selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                // ->where('is_resubmitted', 0)
                ->groupBy('is_approved','is_resubmitted')
                ->get();


            foreach ($documentCounts as $countdoc) {
                if ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Sent For Approval Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 2  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Approved Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 3  && $countdoc->is_resubmitted == 0) {
                    $documentRequestCounts['Not Approved Documents'] += $countdoc->count;
                } elseif ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 1) {
                    $documentRequestCounts['Resubmitted Documents'] += $countdoc->count;
                }
            }    
            // $documentRequestCounts['resubmitted_document_count'] = GramPanchayatDocuments::where('user_id', $user_working_dist)
            //     ->where('is_resubmitted', 1)
            //     ->where('is_approved', 1)
            //     ->count();

           
        } elseif ($roleId== 3 && $utype == 3) {

            $data_output = User::leftJoin('usertype', 'users.user_type', '=', 'usertype.id')
            ->where('users.id', $sess_user_id)
            ->first();

            $utype=$data_output->user_type;
            $user_working_dist=$data_output->user_district;
            $user_working_tal=$data_output->user_taluka;
            $user_working_vil=$data_output->user_village;

            $userLatitude = $request->latitude; 
            $userLongitude = $request->longitude; 
            $distanceInKm = DistanceKM::first()->distance_km;
            // $distanceInKm = 5; 

            $latLongArr= $this->getLatitudeLongitude($userLatitude,$userLongitude, $distanceInKm);
            $latN = $latLongArr['latN'];
            $latS = $latLongArr['latS'];
            $lonE = $latLongArr['lonE'];
            $lonW = $latLongArr['lonW'];


            // $projectCount = Project:: leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
            //     ->where('projects.is_active', true)
            //     ->where('projects.end_date', '>=', now())
            //     ->when($request->has('latitude'), function($query) use ($latN, $latS, $lonE, $lonW) {
            //         $query->where('projects.latitude', '<=', $latN)
            //             ->where('projects.latitude', '>=', $latS)
            //             ->where('projects.longitude', '<=', $lonE)
            //             ->where('projects.longitude', '>=', $lonW);
            //     })
            //     ->count();

            $projectCount= Project::leftJoin('users', 'projects.district', '=', 'users.user_district')  
            ->where('projects.is_active', true)
            ->where('projects.village', $user_working_vil)
            ->groupBy('users.id')
            ->count();   

            $projectCountCompleted = Project:: leftJoin('tbl_area as district_projects', 'projects.district', '=', 'district_projects.location_id')  
                ->where('projects.is_active', true)
                ->where('projects.end_date', '<=', now())
                ->when($request->has('latitude'), function($query) use ($latN, $latS, $lonE, $lonW) {
                    $query->where('projects.latitude', '<=', $latN)
                        ->where('projects.latitude', '>=', $latS)
                        ->where('projects.longitude', '<=', $lonE)
                        ->where('projects.longitude', '>=', $lonW);
                })
                ->count();  

            
            $todayCount = Labour::where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->where('user_id', $sess_user_id)
            // ->where('is_approved', 2)
            ->get()
            ->count();

            $currentYearCount = Labour::whereYear('updated_at', date('Y'))
                // ->where('is_approved', 2)
                ->where('user_id', $sess_user_id)
                ->get()
                ->count();

                         $labourCounts = Labour::where('user_id', $sess_user_id)
                            ->selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                            // ->where('is_resubmitted', 0)
                            ->groupBy('is_approved','is_resubmitted')
                            ->get();
                        //   dd( $labourCounts);
                        // foreach ($labourCounts as $count) {
                        //     if ($count->is_approved == 1) {
                        //         $labourRequestCounts['Sent For Approval Labours'] += $count->count;
                        //     } elseif ($count->is_approved == 2) {
                        //         $labourRequestCounts['Approved Labours'] += $count->count;
                        //     } elseif ($count->is_approved == 3) {
                        //         $labourRequestCounts['Not Approved Labours'] += $count->count;
                        //     }
                        // }

                        foreach ($labourCounts as $count) {
                            if ($count->is_approved == 1 && $count->is_resubmitted == 0) {
                                $labourRequestCounts['Sent For Approval Labours'] += $count->count;
                            } elseif ($count->is_approved == 2 && $count->is_resubmitted == 0) {
                                $labourRequestCounts['Approved Labours'] += $count->count;
                            } elseif ($count->is_approved == 3 && $count->is_resubmitted == 0) {
                                $labourRequestCounts['Not Approved Labours'] += $count->count;
                            } elseif ($count->is_approved == 1 && $count->is_resubmitted == 1) {
                                $labourRequestCounts['Resubmitted Labours'] += $count->count;
                            }
                        }
            
                        $documentCounts = GramPanchayatDocuments::where('user_id', $sess_user_id)
                            ->selectRaw('is_approved,is_resubmitted, COUNT(*) as count')
                            // ->where('is_resubmitted', 0)
                            ->groupBy('is_approved','is_resubmitted')
                            ->get();
            
                        // foreach ($documentCounts as $countdoc) {
                        //     if ($countdoc->is_approved == 1) {
                        //         $documentRequestCounts['Sent For Approval Documents'] += $countdoc->count;
                        //     } elseif ($countdoc->is_approved == 2) {
                        //         $documentRequestCounts['Approved Documents'] += $countdoc->count;
                        //     } elseif ($countdoc->is_approved == 3) {
                        //         $documentRequestCounts['Not Approved Documents'] += $countdoc->count;
                        //     }
                        // }

                        foreach ($documentCounts as $countdoc) {
                            if ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 0) {
                                $documentRequestCounts['Sent For Approval Documents'] += $countdoc->count;
                            } elseif ($countdoc->is_approved == 2  && $countdoc->is_resubmitted == 0) {
                                $documentRequestCounts['Approved Documents'] += $countdoc->count;
                            } elseif ($countdoc->is_approved == 3  && $countdoc->is_resubmitted == 0) {
                                $documentRequestCounts['Not Approved Documents'] += $countdoc->count;
                            } elseif ($countdoc->is_approved == 1  && $countdoc->is_resubmitted == 1) {
                                $documentRequestCounts['Resubmitted Documents'] += $countdoc->count;
                            }
                        }  
            
        }


        $return_data = [
            'status' => 'true',
            'message' => 'Counts retrieved successfully',
            // 'user_count' => $userCount,
            'today_count' => $todayCount,
            'current_year_count' => $currentYearCount,
            'project_count' => $projectCount,
            'project_count_completed' =>$projectCountCompleted,
            'labourRequestCounts' => $labourRequestCounts,
            'documentRequestCounts' => $documentRequestCounts,
        ];
        // dd( $return_data);
        if ($sess_user_role == '1' || $sess_user_role == '2') {
            $return_data['user_count'] = $userCount;
        }
        // dd($sess_user_role);
        // return $return_data;

        // dd( $counts);
        return view('admin.pages.dashboard', compact('return_data', 'sess_user_role'));
    
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