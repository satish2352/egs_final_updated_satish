<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Support\Facades\Mail;

use Validator;
use Illuminate\Http\Request;
use App\Models\ {
	User
};

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','resetPasswordEmailBased']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_id'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->all()], 200);
        }


        $email = $request->email;
        $password = $request->password;
        $device_id = $request->device_id;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json(['status' => 'False','message' => 'User not found'], 200);
        }
        if ($user->is_active != 1) {
            return response()->json(['status' => 'False', 'message' => 'User is not active'], 200);
        }

        if(!($email =='testgs@gmail.com' || $email =='testofc@gmail.com')){
            if ($user->device_id != 'null' && $user->device_id != $device_id) {
                return response()->json(['status' => 'False', 'message' => 'This user is associated with another device please login with same'], 200);
            }
        }
        
        if ($user->device_id== 'null') {
            User::where('email', $email)->update(['device_id' => $device_id]);
        }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['status' => 'False', 'message' => 'Invalid email or password. Please correct it'], 200);
        }
        

        if ($user->device_id== 'null') {
            User::where('email', $email)->update(['device_id' => $device_id]);
        }

        $user->update(['remember_token' => $token]);


        return response()->json([
            'status' => 'True',
            'message' => 'Login successfully',
            'data' => $user,
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
            // 'expires_in' => auth()->factory()->getTTL() * 60 * 24 * 365 * 10, // 10 years
        ]);

    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
   
    public function sendPasswordEmail($password, $email)
    {
        try {
           $msg= Mail::raw('Your new password is: ' . $password, function ($message) use ($email) {
                $message->to($email)->subject('Password Reset');
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

                // dd($message);
            });           
            return true;

        } catch (\Exception $e) {
            // Log the error
            \Log::error($e);
            return false;
        }
    }

 


    public function resetPasswordEmailBased(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'false', 'message' => 'Invalid email format'], 200);
            }

            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['status' => 'false', 'message' => 'Email not found'], 200);
            }
            

            // $newPassword = Str::random(8); // Change the password length as needed
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@$!%*?&';
            $newPassword = '';
            $length = 8;
            for ($i = 0; $i < $length; $i++) {
                $newPassword .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $emailSent = $this->sendPasswordEmail($newPassword, $request->email);

            if (!$emailSent) {
                return response()->json(['status' => 'false', 'message' => 'Failed to send reset link'], 200);
            }

            
            $user->password = Hash::make($newPassword);
            $user->save();
           
            return response()->json(['status' => 'true', 'message' => 'Password updated successfully'], 200);
        } catch (\Exception $e) {
            
            \Log::error($e);
            return response()->json(['status' => 'false', 'message' => 'Failed to update password'], 500);
        }
    }
}