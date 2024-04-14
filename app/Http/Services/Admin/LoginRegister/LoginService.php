<?php
namespace App\Http\Services\Admin\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Repository\Admin\LoginRegister\LoginRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Session;

class LoginService 
{
	protected $repo;
	public function __construct()
    {        
        $this->repo = new LoginRepository();
    }

    public function checkLogin($request) {
        $response = $this->repo->checkLogin($request);
  
        if($response['user_details']) {
            // use bcrypt for login
            $password = $request['password'];
            if (Hash::check($password, $response['user_details']['password'])) {
                $request->session()->put('user_id',$response['user_details']['id']);
                $request->session()->put('role_id',$response['user_details']['role_id']);
                $request->session()->put('email',$response['user_details']['email']);
                $request->session()->put('user_type',$response['user_details']['user_type']);
                $request->session()->put('working_dist',$response['user_details']['user_district']);
                $request->session()->put('f_name',$response['user_details']['f_name']);
                $request->session()->put('m_name',$response['user_details']['m_name']);
                $request->session()->put('l_name',$response['user_details']['l_name']);
                $request->session()->put('role_name',$response['user_details']['role_name']);
                $request->session()->put('permissions',$response['user_permission']);
                // $request->session()->put('user_agent',$request->userAgent());
                // $request->session()->put('ip_of_user',$request->ip());
                getRouteDetailsPresentOrNot(session('permissions'));

                $update = User::where([
                                'email' => $request['email'],
                                'is_active' =>true
                                ])
                                ->update([
                                    'ip_address'=>$request->ip(),
                                    'user_agent'=>$request->userAgent()
                                ]);

                $json = ['status'=>'success','msg'=>$response['user_details']];
            } else {
                $json = ['status'=>'failed','msg'=>'These credentials do not match our records.'];
            }
            
        } else {
            $json = ['status'=>'failed','msg'=>'These credentials do not match our records.'];
        }
        return $json;
    }
}