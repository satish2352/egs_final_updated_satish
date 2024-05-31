<?php

namespace App\Http\Controllers\Admin\Menu;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Labours\LabourServices;
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
    Project
};
use Validator;
use session;
use Config;

class LaboursController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new LabourServices();
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

        $labour_type='1';            
        $resubmitted_type='0';        
        $labour_data_type="New Labours";
        $labours = $this->service->index();
        return view('admin.pages.labours.list-labour',compact('labours','district_data','taluka_data','labour_type','resubmitted_type','labour_data_type'));
    }

    public function listApprovedLabours()
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
                    
        $labour_type='2';            
        $resubmitted_type='0';            
        $labour_data_type="Approved Labours";
        $labours = $this->service->listApprovedLabours();
        return view('admin.pages.labours.list-labour',compact('labours','district_data','taluka_data','labour_type','resubmitted_type','labour_data_type'));
    }

    public function listTodayApprovedLabours()
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
                    
        $labour_type='2';            
        $resubmitted_type='0';            
        $labours = $this->service->listTodayApprovedLabours();
        return view('admin.pages.labours.list-labour',compact('labours','district_data','taluka_data','labour_type','resubmitted_type'));
    }

    public function listDisapprovedLabours()
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

        $labour_type='3'; 
        $resubmitted_type='0';           
        $labour_data_type="Not Approved Labours";
        $labours = $this->service->listDisapprovedLabours();
        return view('admin.pages.labours.list-labour',compact('labours','district_data','taluka_data','labour_type','resubmitted_type','labour_data_type'));
    }

    public function listResubmitedLabours()
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

        $labour_type='1'; 
        $resubmitted_type='1';       
        $labour_data_type="Resubmitted Labours";
        $labours = $this->service->listResubmitedLabours();
        return view('admin.pages.labours.list-labour',compact('labours','district_data','taluka_data','labour_type','resubmitted_type','labour_data_type'));
    }

    public function getLabourAttendanceList()
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

        $labours = $this->service->getLabourAttendanceList();
        // dd($labours);
        return view('admin.pages.labours.list-labour-attendence',compact('labours','district_data','taluka_data','dynamic_projects'));
    }

    // public function getProf()
    // {
    //     $register_user = $this->service->index();
    //     return view('admin.layout.master',compact('register_user'));
    // }



    public function addProjects(){
        // $roles = Roles::where('is_active', true)
        //                 ->select('id','role_name')
        //                 ->get()
        //                 ->toArray();
        $permissions = Permissions::where('is_active', true)
                            ->select('id','route_name','permission_name','url')
                            ->get()
                            ->toArray();
        $dynamic_state = TblArea::where('location_type', 1)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
    	// return view('admin.pages.users.add-users',compact('roles','permissions','dynamic_state'));
    	return view('admin.pages.projects.add-projects',compact('permissions','dynamic_state'));
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('stateId');

        $city = TblArea::where('location_type', 2) // 4 represents cities
                    ->where('parent_id', $stateId)
                    ->get(['location_id', 'name']);
              return response()->json(['city' => $city]);

    }

    public function getDistrict(Request $request)
    {
        $stateId = $request->input('stateId');

        $district = TblArea::where('location_type', 2) // 4 represents cities
                    ->where('parent_id', $stateId)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['district' => $district]);

    }

    public function getTaluka(Request $request)
    {
        $districtId = $request->input('districtId');

        $taluka = TblArea::where('location_type', 3) // 4 represents cities
                    ->where('parent_id', $districtId)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['taluka' => $taluka]);

    }

    public function getVillage(Request $request)
    {
        $talukaId = $request->input('talukaId');

        $village = TblArea::where('location_type', 4) // 4 represents cities
                    ->where('parent_id', $talukaId)
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['village' => $village]);

    }

    public function getState(Request $request)
    {
        $stateId = $request->input('stateId');
        $state =  TblArea::where('location_type', 1)
                            // ->where('parent_id', $stateId)
                            ->select('location_id','name')
                            ->get()
                            ->toArray();
        return response()->json(['state' => $state]);

    }

    public function editProjects(Request $request){
        
        $project_data = $this->service->editProjects($request);
        return view('admin.pages.projects.edit-projects',compact('project_data'));
    }

    public function update(Request $request){
        // $user_data = $this->service->editUsers($request);
        // return view('admin.pages.users.users-list',compact('user_data'));

        $rules = [
            // 'email' => 'required',
            // 'u_uname' => 'required',
            // 'password' => 'required',
            'role_id' => 'required',
            'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
            'number' =>  'required|regex:/^[0-9]{10}$/',
            'imei_no' => 'required',
            'aadhar_no' => 'required',
            'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'village' => 'required',
            'pincode' => 'required|regex:/^[0-9]{6}$/',
         ];       

        $messages = [   
                        // 'email.required' => 'Please enter email.',
                        // 'email.email' => 'Please enter valid email.',
                        // 'u_uname.required' => 'Please enter user uname.',
                        // 'password.required' => 'Please enter password.',
                        'role_id.required' => 'Please select role type.',
                        'f_name.required' => 'Please enter first name.',
                         'f_name.regex' => 'Please  enter text only.',
                        'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                        'm_name.required' =>'Please enter middle name.',
                        'm_name.regex' => 'Please  enter text only.',
                        'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                        'l_name.required' => 'Please enter last name.',
                        'l_name.regex' => 'Please  enter text only.',
                        'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                        'number.required' => 'Please enter number.',
                        'number.regex' => 'Please enter only numbers with 10-digit.',

                        'imei_no.required' =>'Please enter IMEI Number.',
                        'aadhar_no.required' =>'Please enter Aadhar Number.',
                        // 'designation.regex' => 'Please  enter text only.',
                        // 'designation.max'   => 'Please  enter designation length upto 255 character only.',

                        'address.required' => 'Please enter address.',
                        'address.regex' => 'Please enter right address.',
                        'address.max'   => 'Please  enter address length upto 255 character only.',


                        'state.required' => 'Please select state.',
                        'district.required' =>'Please select District.',
                        'taluka.required' =>'Please select Taluka.',
                        'village.required' =>'Please select Village.',
                        'pincode.required' => 'Please enter pincode.',
                        'pincode.regex' => 'Please enter a 6-digit pincode.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->update($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-users')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-users')->withInput()->with(compact('msg','status'));
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function store(Request $request)
    {

        try {

            $rules = [
                'project_name' => 'required|unique:projects|regex:/^[a-zA-Z\s]+$/u|max:255',
                'state' => 'required',
                'district' => 'required',
                'taluka' => 'required',
                'village' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required',
            ];
            $messages = [
                'project_name.required' => 'Please  enter title.',
                // 'role_name.unique' => 'Your role type is already exist.',
                'project_name.regex' => 'Please  enter text only.',
                'project_name.max' => 'Please  enter text length upto 255 character only.',
                'project_name.unique' => 'Title already exist.',

                'state.required' => 'Please  enter state.',
                'district.required' => 'Please  enter district.',
                'taluka.required' => 'Please  enter taluka.',
                'village.required' => 'Please  enter village.',
                'latitude.required' => 'Please  enter latitude.',
                'longitude.required' => 'Please  enter longitude.',
                'start_date.required' => 'Please  enter start_date.',
                'end_date.required' => 'Please  enter end_date.',
                'description.required' => 'Please  enter description.',

            ];

            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect('add-projects')
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $add_role = $this->service->addProject($request);
                if ($add_role) {
                    $msg = $add_role['msg'];
                    $status = $add_role['status'];
                    if ($status == 'success') {
                        return redirect('list-role')->with(compact('msg', 'status'));
                    } else {
                        return redirect('add-role')->withInput()->with(compact('msg', 'status'));
                    }
                }

            }
        } catch (Exception $e) {
            return redirect('add-role')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function add(Request $request){
        $rules = [
                //    'email' => 'required|unique:users,email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.)+([a-zA-Z0-9]{2,4})+$/',
                //     'password'=>'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[a-zA-Z\d]).{8,}$/',
                //     'password_confirmation' => 'required|same:password',
                //     'role_id' => 'required',
                //     'f_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'm_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'l_name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'number' =>  'required|regex:/^[0-9]{10}$/',
                //     'designation' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                //     'address' => ['required','regex:/^(?![0-9\s]+$)[A-Za-z0-9\s\.,#\-\(\)\[\]\{\}]+$/','max:255'],
                //     'state' => 'required',
                //     'city' => 'required',
                //     'pincode' => 'required|regex:/^[0-9]{6}$/',
                //     'user_profile' => 'required|image|mimes:jpeg,png,jpg|max:'.Config::get("AllFileValidation.USER_PROFILE_MAX_SIZE").'|dimensions:min_width=150,min_height=150,max_width=400,max_height=400|min:'.Config::get("AllFileValidation.USER_PROFILE_MIN_SIZE").'',

                 ];       

        $messages = [   
                    //     'email.required' => 'Please enter email.',
                    //     'email.unique' => 'Your email is already exist.',
                    //     'email.regex' => 'Enter valid email.',
                    //     // 'u_uname.required'=>'Please enter firstname.',
                    //     // 'u_uname.regex' => 'Please  enter text only.',
                    //     // 'u_uname.max'   => 'Please  enter firstname length upto 255 character only.',       
                    //     'password.required' => 'Please enter password.',
                    //     'password.regex' => 'Password should be more than 8 numbers with atleast 1 capital letter,1 small letter, 1 number and 1 special character.',
                    //     'password_confirmation.same' => 'The password confirmation does not match.',
                    //     // 'password.min' => 'Please combination of number character of 8 char.',
                    //     'role_id.required' => 'Please select role type.',
                    //     'f_name.required' => 'Please enter first name.',
                    //      'f_name.regex' => 'Please  enter text only.',
                    //     'f_name.max'   => 'Please  enter first name length upto 255 character only.',

                    //     'm_name.required' =>'Please enter middle name.',
                    //     'm_name.regex' => 'Please  enter text only.',
                    //     'm_name.max'   => 'Please  enter middle name length upto 255 character only.',

                    //     'l_name.required' => 'Please enter last name.',
                    //     'l_name.regex' => 'Please  enter text only.',
                    //     'l_name.max'   => 'Please  enter last name length upto 255 character only.',

                    //     'number.required' => 'Please enter number.',
                    //     'number.regex' => 'Please enter only numbers with 10-digit.',

                    //     'designation.required' =>'Please enter designation.',
                    //     'designation.regex' => 'Please  enter text only.',
                    //     'designation.max'   => 'Please  enter designation length upto 255 character only.',

                    //     'address.required' => 'Please enter address.',
                    //     'address.regex' => 'Please enter right address.',
                    //     'address.max'   => 'Please  enter address length upto 255 character only.',


                    //     'state.required' => 'Please select state.',
                    //     'city.required' =>'Please select city.',
                    //    'pincode.required' => 'Please enter pincode.',
                    //     'pincode.regex' => 'Please enter a 6-digit pincode.',
                    //     'user_profile.required' => 'The user profile is required.',
                    //     'user_profile.image' => 'The user profile must be a valid image file.',
                    //     'user_profile.mimes' => 'The user profile must be in JPEG, PNG, JPG, GIF, or SVG format.',
                    //     'user_profile.max' => 'The user profile size must not exceed '.Config::get("AllFileValidation.USER_PROFILE_MAX_SIZE").'KB .',
                    //     'user_profile.min' => 'The user profile size must not be less than '.Config::get("AllFileValidation.USER_PROFILE_MIN_SIZE").'KB .',
                    //     'user_profile.dimensions' => 'The user profile dimensions must be between 150x150 and 400x400 pixels.',    

                      ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('add-users')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->register($request);
          
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('list-users')->with(compact('msg','status'));
                }
                else {
                    return redirect('add-users')->withInput()->with(compact('msg','status'));
                }
            }
            
        }


    }

    public function show(Request $request)
    {
        $dynamic_registrationstatus = Registrationstatus::where('is_active', 1)
                            ->where('id', '!=', 1)
                            ->select('id','status_name')
                            ->get()
                            ->toArray();
        $dynamic_reasons = Reasons::where('is_active', 1)
					        ->where('tbl_reason.is_deleted', 0)
                            ->where('id', '!=', 1001)
                            ->select('id','reason_name')
                            ->get()
                            ->toArray();

        try {
            $labour_detail = $this->service->getById($request->show_id);
            // dd($labour_detail);
            return view('admin.pages.labours.show-labour', compact('labour_detail','dynamic_registrationstatus','dynamic_reasons'));
        } catch (\Exception $e) {
            return $e;
        }
    }
  
   
    // public function delete(Request $request){
    //     try {
    //         $delete = $this->service->delete($request->delete_id);
    //         if ($delete) {
    //             $msg = $delete['msg'];
    //             $status = $delete['status'];
    //             if ($status == 'success') {
    //                 return redirect('list-users')->with(compact('msg', 'status'));
    //             } else {
    //                 return redirect()->back()
    //                     ->withInput()
    //                     ->with(compact('msg', 'status'));
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // } 

    // public function updateOne(Request $request){
    //     try {
    //         $active_id = $request->active_id;
    //     $result = $this->service->updateOne($active_id);
    //         return redirect('list-labours')->with('flash_message', 'Updated!');  
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    
    public function updateLabourStatus(Request $request){
        $rules = [
            'is_approved' => 'required',
         ];       

        $messages = [   
                        'is_approved.required' => 'Please enter email.',
                        // 'email.email' => 'Please enter valid email.',
                        // 'u_uname.required' => 'Please enter user uname.',
                        // 'password.required' => 'Please enter password.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateLabourStatus($request);

                if($register_user)
                {

                    $sess_user_id=session()->get('user_id');
                    $sess_user_type=session()->get('user_type');
                    $sess_user_role=session()->get('role_id');

                if($request->is_approved=='3' && $request['other_remark']!='')
                {    
                $history = new HistoryModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->labour_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_id = $request->reason_id; 
                $history->other_remark = $request->other_remark; 
                $history->save();
                }else if($request->is_approved=='3' && $request['other_remark']=='')
                {    
                    // Create a history record
                $history = new HistoryModel();
                $history->user_id = $sess_user_id; 
                $history->roles_id = $sess_user_role; 
                $history->labour_id = $request->edit_id;
                $history->is_approved = $request->is_approved;
                $history->reason_id = $request->reason_id; 
                $history->save();
                }
    
                
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-labours')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-labours')->withInput()->with(compact('msg','status'));
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function getGramsevakList()
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
                
        $gramsevaks = $this->service->getGramsevakList();
        return view('admin.pages.gramsevak.list-gramsevaks',compact('gramsevaks','district_data','taluka_data','dynamic_projects'));
    }

    public function showGramsevakDocuments(Request $request)
    {
        try {
            $data_gram_doc_details = $this->service->showGramsevakDocuments($request->show_id);
            return view('admin.pages.gramsevak.show-gramsevak-doc', compact('data_gram_doc_details'));
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getFilterLabours(Request $request)
    {
        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');

       $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');
        $IsApprovedId = $request->input('IsApprovedId');
        $IsResubmittedId = $request->input('IsResubmittedId');

        if($IsApprovedId=='1')
        {
            $IsApprovedIdNew='1';
        }elseif($IsApprovedId=='2')
        {
            $IsApprovedIdNew='2';
        }elseif($IsApprovedId=='3')
        {
            $IsApprovedIdNew='3';
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


     	$query = Labour::leftJoin('tbl_area as district_labour', 'labour.district_id', '=', 'district_labour.location_id')
		->leftJoin('tbl_area as taluka_labour', 'labour.taluka_id', '=', 'taluka_labour.location_id')
		->leftJoin('tbl_area as village_labour', 'labour.village_id', '=', 'village_labour.location_id')
		->leftJoin('gender as gender_labour', 'labour.gender_id', '=', 'gender_labour.id')
		->leftJoin('users', 'labour.user_id', '=', 'users.id')
		->where('labour.is_approved', $IsApprovedIdNew)
		->where('labour.is_resubmitted', $IsResubmittedId)
        ->whereIn('labour.user_id',$data_user_output)
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
				->where('labour.is_approved', $IsApprovedIdNew)
		        ->where('labour.is_resubmitted', $IsResubmittedId)
                ->whereIn('labour.user_id',$data_user_output)
                ->where('registrationstatus.is_active', true)
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

    public function getFilterLaboursAttendance(Request $request)
    {
        $sess_user_id=session()->get('user_id');
		$sess_user_type=session()->get('user_type');
		$sess_user_role=session()->get('role_id');
		$sess_user_working_dist=session()->get('working_dist');

       $districtId = $request->input('districtId');
        $talukaId = $request->input('talukaId');
        $villageId = $request->input('villageId');

        $fromDate = date('Y-m-d', strtotime($request->input('FromDate')));
        $fromDateNew =  $fromDate.' 00:00:01';
        $toDate = date('Y-m-d', strtotime($request->input('ToDate')));
        $toDateNew =  $toDate.' 23:59:59';

            if($sess_user_role=='1')
		{

                $query = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
                ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
                ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
                ->when($request->get('ProjectId'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id', $request->ProjectId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })  
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
                ->when($request->filled('FromDate'), function($query) use ($fromDateNew, $toDateNew) {
                    $query->whereBetween('tbl_mark_attendance.created_at', [$fromDateNew, $toDateNew]);
                })
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
                    'tbl_mark_attendance.created_at'
    
                );
    
               
               $data_output = $query->get();

		}
        else if($sess_user_role=='2')
		  {

                $query = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
                ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
                ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
                ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
                ->where('projects.district', $sess_user_working_dist)
                ->when($request->get('ProjectId'), function($query) use ($request) {
                    $query->where('tbl_mark_attendance.project_id', $request->ProjectId);
                })
                ->when($request->get('talukaId'), function($query) use ($request) {
                    $query->where('users.user_taluka', $request->talukaId);
                })  
                ->when($request->get('villageId'), function($query) use ($request) {
                    $query->where('users.user_village', $request->villageId);
                })
                ->when($request->filled('FromDate'), function($query) use ($fromDateNew, $toDateNew) {
                    $query->whereBetween('tbl_mark_attendance.created_at', [$fromDateNew, $toDateNew]);
                })
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
                    'tbl_mark_attendance.created_at'
    
                );
    
               
               $data_output = $query->get();

            } else if($sess_user_role=='3')
            {
  
                  $query = LabourAttendanceMark::leftJoin('labour', 'tbl_mark_attendance.mgnrega_card_id', '=', 'labour.mgnrega_card_id')
                  ->leftJoin('users', 'tbl_mark_attendance.user_id', '=', 'users.id')
                  ->leftJoin('projects', 'tbl_mark_attendance.project_id', '=', 'projects.id')
                  ->leftJoin('tbl_area as taluka_labour', 'users.user_taluka', '=', 'taluka_labour.location_id')
                  ->leftJoin('tbl_area as village_labour', 'users.user_village', '=', 'village_labour.location_id')
                  ->where('tbl_mark_attendance.user_id', $sess_user_id)
                  ->when($request->get('ProjectId'), function($query) use ($request) {
                      $query->where('tbl_mark_attendance.project_id', $request->ProjectId);
                  })
                  ->when($request->get('talukaId'), function($query) use ($request) {
                      $query->where('users.user_taluka', $request->talukaId);
                  })  
                  ->when($request->get('villageId'), function($query) use ($request) {
                      $query->where('users.user_village', $request->villageId);
                  })
                  ->when($request->filled('FromDate'), function($query) use ($fromDateNew, $toDateNew) {
                      $query->whereBetween('tbl_mark_attendance.created_at', [$fromDateNew, $toDateNew]);
                  })
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
                      'tbl_mark_attendance.created_at'
      
                  );
      
                 
                 $data_output = $query->get();
  
              }
                return response()->json(['labour_attendance_ajax_data' => $data_output]);

            // } catch (\Exception $e) {
            //     return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            // }

    }

  
   

   
}