<?php

namespace App\Http\Controllers\Admin\Area;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\Area\AreaServices;
use App\Models\ {
    Roles,
    Permissions,
    TblArea,
    User,
    Usertype
};
use Validator;
use session;
use Config;

class AreaController extends Controller {
    /**
     * Topic constructor.
     */
    public function __construct()
    {
        $this->service = new AreaServices();
    }

    public function index()
    {
        $data_district = $this->service->index();
        return view('admin.pages.area.list-district',compact('data_district'));
    }

    public function addDistrict(){
        
    	return view('admin.pages.area.add-district');
    }

    public function addDistrictInsert(Request $request){

        $rules = [
                    'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                 ];       

        $messages = [   
                        'name.required' => 'Please enter first name.',
                        'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                      ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('add-district')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->addDistrictInsert($request);
          
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('list-district')->with(compact('msg','status'));
                }
                else {
                    return redirect('add-district')->withInput()->with(compact('msg','status'));
                }
            }
            
        }
    }

    public function editDistrict(Request $request){
        $district_data = $this->service->editDistrict($request);
        return view('admin.pages.area.edit-district',compact('district_data'));
    }

    public function updateDistrict(Request $request){
        $rules = [
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
         ];       

        $messages = [
                        'name.required' => 'Please enter first name.',
                         'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                    ];
        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateDistrict($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-district')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-district')->withInput()->with(compact('msg','status'));
                    }
                }  
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function deleteDistrict(Request $request){
        try {
            $delete = $this->service->deleteDistrict($request->delete_id);
            if ($delete) {
                $msg = $delete['msg'];
                $status = $delete['status'];
                if ($status == 'success') {
                    return redirect('list-district')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateOneDistrict(Request $request){
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOneDistrict($active_id);
            return redirect('list-district')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getTalukaList()
    {
        $data_taluka = $this->service->getTalukaList();
        return view('admin.pages.area.list-taluka',compact('data_taluka'));
    }

    public function addTaluka(){
        $dynamic_district = TblArea::where('parent_id', 2)
                            ->where('is_active', '1')
                            ->select('location_id','name')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->toArray();
    	return view('admin.pages.area.add-taluka',compact('dynamic_district'));
    }

    public function addTalukaInsert(Request $request){

        $rules = [
                    'district' => 'required',
                    'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                 ];       

        $messages = [   
                        'district.required' => 'Please enter first name.',

                        'name.required' => 'Please enter first name.',
                        'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                      ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('add-taluka')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->addTalukaInsert($request);
          
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('list-taluka')->with(compact('msg','status'));
                }
                else {
                    return redirect('add-taluka')->withInput()->with(compact('msg','status'));
                }
            }
            
        }
    }

    public function editTaluka(Request $request){
        $dynamic_district = TblArea::where('parent_id', 2)
                            ->where('is_active', '1')
                            ->select('location_id','name')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->toArray();
        $taluka_data = $this->service->editTaluka($request);
        return view('admin.pages.area.edit-taluka',compact('taluka_data','dynamic_district'));
    }
    

    public function updateTaluka(Request $request){
        $rules = [
            'district' => 'required',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
         ];       

        $messages = [
                        'name.required' => 'Please Select District name.',

                        'name.required' => 'Please enter first name.',
                         'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                    ];
        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateTaluka($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-taluka')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-taluka')->withInput()->with(compact('msg','status'));
                    }
                }  
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function deleteTaluka(Request $request){
        try {
            $delete = $this->service->deleteTaluka($request->delete_id);
            if ($delete) {
                $msg = $delete['msg'];
                $status = $delete['status'];
                if ($status == 'success') {
                    return redirect('list-taluka')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function updateOneTaluka(Request $request){
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOneTaluka($active_id);
            return redirect('list-taluka')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getVillageList()
    {
        $data_village = $this->service->getVillageList();
        return view('admin.pages.area.list-village',compact('data_village'));
    }

    public function addVillage(){
        $dynamic_district = TblArea::where('parent_id', 2)
                            ->where('is_active', '1')
                            ->select('location_id','name')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->toArray();
    	return view('admin.pages.area.add-village',compact('dynamic_district'));
    }

    public function addVillageInsert(Request $request){
// dd($request);
        $rules = [
                    'district' => 'required',
                    'taluka' => 'required',
                    'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
                 ];       

        $messages = [   
                        'district.required' => 'Please enter first name.',
                        'taluka.required' => 'Please enter first name.',

                        'name.required' => 'Please enter first name.',
                        'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                      ];


        $validation = Validator::make($request->all(),$rules,$messages);
        if($validation->fails() )
        {
            return redirect('add-village')
            ->withInput()
            ->withErrors($validation);
        }
        else
        {
            $register_user = $this->service->addVillageInsert($request);
          
            if($register_user)
            {
              
                $msg = $register_user['msg'];
                $status = $register_user['status'];
                if($status=='success') {
                    return redirect('list-village')->with(compact('msg','status'));
                }
                else {
                    return redirect('add-village')->withInput()->with(compact('msg','status'));
                }
            }
            
        }
    }

    public function updateOneVillage(Request $request){
        try {
            $active_id = $request->active_id;
        $result = $this->service->updateOneVillage($active_id);
            return redirect('list-taluka')->with('flash_message', 'Updated!');  
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function editVillage(Request $request){
        $dynamic_district = TblArea::where('parent_id', 2)
                            ->where('is_active', '1')
                            ->select('location_id','name')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->toArray();
        $village_data = $this->service->editVillage($request);
        return view('admin.pages.area.edit-village',compact('village_data','dynamic_district'));
    }

    public function updateVillage(Request $request){
        $rules = [
            'district' => 'required',
            'taluka' => 'required',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255',
         ];       

        $messages = [
                        'district.required' => 'Please Select District name.',
                        'taluka.required' => 'Please Select Taluka name.',

                        'name.required' => 'Please enter first name.',
                         'name.regex' => 'Please  enter text only.',
                        'name.max'   => 'Please  enter first name length upto 255 character only.',
                    ];
        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateVillage($request);

                if($register_user)
                {
                
                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('list-village')->with(compact('msg','status'));
                    }
                    else {
                        return redirect('list-village')->withInput()->with(compact('msg','status'));
                    }
                }  
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }

    public function deleteVillage(Request $request){
        try {
            $delete = $this->service->deleteVillage($request->delete_id);
            if ($delete) {
                $msg = $delete['msg'];
                $status = $delete['status'];
                if ($status == 'success') {
                    return redirect('list-village')->with(compact('msg', 'status'));
                } else {
                    return redirect()->back()
                        ->withInput()
                        ->with(compact('msg', 'status'));
                }
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    

    public function getDistrict(Request $request)
    {
        $stateId = $request->input('stateId');

        $district = TblArea::where('parent_id', $stateId)
                    ->where('is_active', '1')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['district' => $district]);

    }

    public function getTaluka(Request $request)
    {
        $districtId = $request->input('districtId');

        $taluka = TblArea::where('parent_id', $districtId)
                    ->where('is_active', '1')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['taluka' => $taluka]);

    }

    public function getVillage(Request $request)
    {
        $talukaId = $request->input('talukaId');

        $village = TblArea::where('parent_id', $talukaId)
                    ->where('is_active', '1')
                    ->orderBy('name', 'asc')
                    ->get(['location_id', 'name']);
              return response()->json(['village' => $village]);

    }

    public function getState(Request $request)
    {
        $stateId = $request->input('stateId');
        $state =  TblArea::select('location_id','name')
                            ->where('is_active', '1')
                            ->orderBy('name', 'asc')
                            ->get()
                            ->toArray();
        return response()->json(['state' => $state]);

    }

       
    public function editUsersProfile(Request $request){
        $user_data = $this->service->getProfile($request);
        // $user_detail= session()->get('user_id');
        // $id = $user_data->id;
        // return view('admin.layout.master',compact('user_data'));
        return view('admin.pages.users.edit-user-profile',compact('user_data'));
    }

    public function updateProfile(Request $request){
        $rules = [
            // 'email' => 'required',
            // 'password' => 'required',
            // 'number' => 'regex:/^\d{10}$/',
         ];       

        $messages = [   
                        // 'email.required' => 'Please enter email.',
                        // 'email.email' => 'Please enter valid email.',
                        // 'password.required' => 'Please enter password.',
                        // 'number.regex' => 'Please enter 10 digit number.',
                    ];


        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                $register_user = $this->service->updateProfile($request);
                // dd($register_user);
                if($register_user)
                {
                    if((isset($register_user['password_change']) && ($register_user['password_change'] =='yes')) || (isset($register_user['mobile_change']) && $register_user['mobile_change'] =='yes')) {
                        return view('admin.pages.users.otp-verify')->with(compact('register_user'));
                    }
                    elseif((isset($request->password) && $request->password !== '') && ($request->number == $request->old_number)) {
                        
                        return redirect('log-out');

                    }
                

                    $msg = $register_user['msg'];
                    $status = $register_user['status'];
                    if($status=='success') {
                        return redirect('/dashboard')->with('msg','status');
                    }
                    else {
                        return redirect('/dashboard')->withInput()->with(compact('msg','status'))->with('success', 'Data updated successfully');
                    }
                }
                
            }

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }

    }
   

    public function updateEmailOtp(Request $request){
        $rules = [
            'otp_number' => 'required|numeric', // Add validation rules for otp_number field
        ];
    
        $messages = [
            'otp_number.required' => 'Please enter the OTP.',
            'otp_number.numeric' => 'The OTP must be a numeric value.',
        ];
    
        try {
            $validation = Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {
                // $verification_result = $this->service->verifyOtp($request->otp_number);
                $update_data = array();
                $return_data = array();
                $otp = User::where('id', $request->user_id)->first();
                if($otp->otp == $request->otp_number) {
                    
                    if($request->password_change =='yes') {
                        $update_data['password'] = $request->password_new;
                    }
                    if($request->mobile_change =='yes') {
                        $update_data['number'] = $request->new_mobile_number;
                    }
            
                    User::where('id', $request->user_id)->update($update_data);
                    $return_data['msg'] = 'Please login again to use services';
                    $return_data['msg_alert'] = 'green';
                                
                    $request->session()->flush();
                    $request->session()->regenerate();
                    return view('admin.login',compact('return_data'));
                    // return redirect('/login')->with('return_data', $return_data);

                } else {
                    $register_user = array();
                    $register_user['user_id'] = $request->user_id;
                    $register_user['password_new'] = $request->password_new;
                    $register_user['password_change'] = $request->password_change;
                    $register_user['new_mobile_number'] = $request->new_mobile_number;
                    $register_user['mobile_change'] = $request->mobile_change;
                    $register_user['msg'] = 'Please Enter Valid OTP';
                    $register_user['msg_alert'] = "red";


                    // return redirect()->back()
                    //     ->withInput()
                    //     ->with(['msg' => 'Invalid OTP.', 'status' => 'error']);
                    return view('admin.pages.users.otp-verify')->with(compact('register_user'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }

    public function checkEmailExists(Request $request) {
        $userEmail = $request->input('email');
        $user = User::where('email', $userEmail)->first();
      
        if ($user) {
          return response()->json([
            'success' => false,
            'message' => 'This Email already exists.',
          ]);
        } else {
          return response()->json([
            'success' => true,
            'message' => 'This Email does not exist.',
          ]);
        }
    }

    public function checkAadharExists(Request $request) {
        $userEmail = $request->input('aadhar_no');
        $user = User::where('aadhar_no', $userEmail)->first();
      
        if ($user) {
          return response()->json([
            'success' => false,
            'message' => 'This Aadhar already exists.',
          ]);
        } else {
          return response()->json([
            'success' => true,
            'message' => 'This Aadhar does not exist.',
          ]);
        }
    }
  
}