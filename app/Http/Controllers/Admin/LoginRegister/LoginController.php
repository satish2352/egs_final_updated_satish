<?php

namespace App\Http\Controllers\Admin\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\LoginRegister\LoginService;
use Session;
use Validator;
use PDO;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;


class LoginController extends Controller
{
    public static $loginServe,$masterApi;
    public function __construct()
    {
        self::$loginServe = new LoginService();
    }

    public function index(){
        
        return view('admin.login');
    }

    public function submitLogin(Request $request) {

        $get_user = User::where([
            'email' => $request['email'],
            ])->first();
            if(!empty($get_user)){
           
                if($get_user->ip_address!=$request->ip() || $get_user->user_agent!=$request->userAgent()){
                    $update = User::where([
                        'email' => $request['email'],
                        ])
                        ->update([
                            'ip_address'=>'null',
                            'user_agent'=>'null',
                        ]);        
                }
            }
            
        $rules = [
            'email' => 'required | email', 
            'password' => 'required',
            // 'g-recaptcha-response' => 'required|captcha',
            ];
        $messages = [   
            'email.required' => 'Please Enter Email.',
            'email.email' => 'Please Enter a Valid Email Address.',
            'password.required' => 'Please Enter Password.',
            // 'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
            // 'g-recaptcha-response.required' =>'Please verify that you are not a robot.',
        ];
    
        try {
            $validation = Validator::make($request->all(),$rules,$messages);
            if($validation->fails() )
            {
                return redirect('login')
                    ->withInput()
                    ->withErrors($validation);
            } else {

                $update_values = User::where([
                    'email' => $request['email']
                    ])->get()->toArray();

                    if(!empty($update_values))
                    {
                        if(!($update_values[0]['ip_address'] == $request->ip() && $update_values[0]['user_agent'] == $request->userAgent()) &&
                        !($update_values[0]['ip_address'] == 'null' && $update_values[0]['user_agent'] == 'null')) {
                            return redirect('/login')->with('error','Please logout from another browser');
                        }
                    }        
                    $resp  = self::$loginServe->checkLogin($request);
                    if($resp['status']=='success') {
                        return redirect('/dashboard');
                    } elseif($resp['status']=='failed') {
                        return redirect('/login')->with('error', $resp['msg']);
                    }else {
                        return redirect('/login')->with('error', $resp['msg']);
                    }

            }

        } catch (Exception $e) {
            return redirect('feedback-suggestions')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
        
    }

    public function logout(Request $request)
    {

        $update = User::where([
            'email' => session()->get('email'),
            'is_active' =>true
            ])
            ->update([
                'ip_address'=>'null',
                'user_agent'=>'null'
            ]);


        $cookies = Cookie::getQueuedCookies();
    
        foreach ($cookies as $name => $value) {
            Cookie::queue(Cookie::forget($name));
        }
 
        $request->session()->forget('user_id');
        $request->session()->forget('role_id');
        $request->session()->forget('email');
        $request->session()->forget('permissions');

        $request->session()->flush();

       
        //$request->session()->regenerate();

        return redirect('/login');
    }
}
