<?php

namespace App\Http\Controllers\Admin\LoginRegister;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Facades\Mail;
use Validator;
use PDO;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;


class ChangePassController extends Controller
{
    public static $loginServe,$masterApi;
    public function __construct()
    {
        // self::$loginServe = new LoginService();
    }

    public function index(Request $request)
    {
        return view('admin.login');
    }

    public function resetPasswordEmailBased(Request $request)
    {
        $rules = [
            'email' => 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z])+\.)+([a-zA-Z0-9]{2,4})+$/',
         ];       

        $messages = [   
                        'email.required' => 'Please enter email.',
                    ];

        try {
            $validation = Validator::make($request->all(),$rules, $messages);
            if ($validation->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($validation);
            } else {            
                $user = User::where('email', $request->email)->first();

                if(!$user)
                {
                    $msg = 'These credentials do not match our records.';
                    $status = 'failed';
                    return redirect('/login')->with('error', $msg);
                }
                $newPassword = Str::random(8);

                $user->password = Hash::make($newPassword);
                $user->save();

                $emailSent = $this->sendPasswordEmail($newPassword, $request->email);

                if (!$emailSent) {
                    return redirect('/login')->with('error', 'Failed to send reset link');
                }
                return redirect('/login')->with('success', 'Password updated successfully');
            }

        } catch (Exception $e) {
            return redirect('feedback-suggestions')->withInput()->with(['msg' => $e->getMessage(), 'status' => 'error']);
        }
    }  
    
    public function sendPasswordEmail($password, $email)
    {
        try {
           $msg= Mail::raw('Your new password is: ' . $password, function ($message) use ($email) {
                $message->to($email)->subject('Password Reset');
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            return true;

        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);
            return false;
        }
    }
}
