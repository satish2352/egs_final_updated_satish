<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;


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
        $this->middleware('auth:api', ['except' => ['login']]);
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

        if ($user->device_id != 'null' && $user->device_id != $device_id) {
            return response()->json(['status' => 'False', 'message' => 'This user is associated with another device please login with same'], 200);
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
   
    // public function resetPasswordEmailBased(Request $request) {
    //     try {
            
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => $validator->errors()], 200);
    //         }
    
    //         // Check if the email exists
    //         $user = User::where('email', $request->email)->first();
    //         if (!$user) {
    //             return response()->json(['status' => 'false', 'message' => 'Email does not exist'], 200);
    //         }
    
    //         // Update user's password
    //         $user->password = Hash::make($request->new_password);
    //         $user->save();
    
    //         return response()->json(['status' => 'true', 'message' => 'Password updated successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Failed to update password', 'error' => $e->getMessage()], 500);
    //     }
    // }
    
    // public function sendPasswordEMAIL($password, $request) {
	// 	try {
	// 		$email_data = [
	// 			'password' => $password,
	// 		];
	// 		$toEmail = $request->email;
	// 		$senderSubject = 'User Password ' . date('d-m-Y H:i:s');
	// 		$fromEmail = env('MAIL_USERNAME');
	// 		Mail::send('admin.email.emailotp', ['email_data' => $email_data], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
	// 			$message->to($toEmail)->subject($senderSubject);
	// 			$message->from($fromEmail, 'User Password');
	// 		});
	// 		return 'ok';
	// 	} catch (\Exception $e) {
	// 		info($e);
	// 	}
	// }
    
    // public function resetPasswordEmailBased(Request $request) {
    //     try {
    //         // Validate the email
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => $validator->errors()], 200);
    //         }
    
           
    //         $user = User::where('email', $request->email)->first();
    //         if (!$user) {
    //             return response()->json(['status' => 'false', 'message' => 'Email does not exist'], 200);
    //         }
    
           
    //         $newPassword = Str::random(8); // Change the password length as needed
    
           
    //         $response = Password::sendResetLink(['email' => $user->email]);
  
    //         if ($response != Password::sendPasswordEMAIL()) {
              
    //             // If sending email fails
    //             return response()->json(['status' => 'false', 'message' => 'Failed to send reset link'], 500);
    //         }
    
    //         // Update user's password
    //         $user->password = Hash::make($newPassword);
    //         $user->save();
   
    //         return response()->json(['status' => 'true', 'message' => 'Password updated successfully'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['status' => 'false', 'message' => 'Failed to update password', 'error' => $e->getMessage()], 500);
    //     }
    // }
    // public function sendPasswordEmail($password, $toEmail) {
    //     try {
    //         $email_data = [
    //             'password' => $password,
    //         ];
    //         $newPassword = Str::random(8);
    //         $senderSubject = 'User Password ' . $newPassword;
    //         $fromEmail = config('mail.from.address');


    //         // Mail::send(['email_data' => $email_data], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
    //         //     $message->to($toEmail)->subject($senderSubject);
    //         //     $message->from($fromEmail, 'User Password');

               
    //         // });
    //         //dd($senderSubject);die();
    //         Mail::send([], [], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
    //             $message->to($toEmail)->subject($senderSubject);
    //             $message->setBody('Your new password is: ' . $senderSubject);
    //             $message->from($fromEmail, 'Your Application Name');

              
    //         });
           
            
    //         return true;
    //     } catch (\Exception $e) {
    //         // Log the error
    //         \Log::error($e);
    //         return false;
    //     }
    // }
    
    // public function resetPasswordEmailBased(Request $request) {
    //     try {
    //         // Validate the email
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => $validator->errors()], 400);
    //         }
    
    //         $user = User::where('email', $request->email)->first();
    //         if (!$user) {
    //             return response()->json(['status' => 'false', 'message' => 'Email does not exist'], 404);
    //         }
    
    //         $newPassword = Str::random(8); // Change the password length as needed
    
    //         // Send password reset email
    //                 $this->sendPasswordEmail($password, $request);
    //         if (!$emailSent) {
    //             return response()->json(['status' => 'false', 'message' => 'Failed to send reset link'], 500);
    //         }
    
    //         // Update user's password
    //         $user->password = Hash::make($newPassword);
    //         $user->save();
    
    //         return response()->json(['status' => 'true', 'message' => 'Password updated successfully'], 200);
    //     } catch (\Exception $e) {
    //         // Log the error
    //         \Log::error($e);
    //         return response()->json(['status' => 'false', 'message' => 'Failed to update password'], 500);
    //     }
    // }

    // public function sendPasswordEmail($password, $toEmail) {
    //     try {
    //         $password = Str::random(8);
    //         $senderSubject = 'User Password: ' . $password;
    //         $fromEmail = config('mail.from.address');
    
    //         Mail::send([], [], function ($message) use ($toEmail, $fromEmail, $senderSubject) {
    //             $message->to($toEmail)->subject($senderSubject);
    //             $message->setBody('Your new password is: ' . $password, 'text/plain'); // Set the body directly as a string
    //             $message->from($fromEmail, 'Your Application Name');
    //         });
           
    //         return true; // Email sent successfully
            
    //     } catch (\Exception $e) {
    //         // Log the error
    //         \Log::error($e);
    //         return false;
    //     }
    // }
    // public function sendPasswordEmail($password, $request) {
    //     try {
    //         $password = Str::random(8);
    //         $toEmail = $request->email;
    //         $senderSubject = 'User Password: ' . $password;           
	// 		$fromEmail = env('MAIL_USERNAME');
           
    //         // Build the message
    //         $message = Mail::raw('Your new password is: ' . $password, function ($message) use ($toEmail, $fromEmail, $senderSubject) {
    //             $message->to($toEmail)->subject($senderSubject);
    //             $message->from($fromEmail, 'Your Application Name');
    //         });
    //        dd($message);
    //        die();
    //         // Dump the message
            
    //         // Send the message
    //         Mail::send([], [], $message);
    
    //         return true; // Email sent successfully
            
    //     } catch (\Exception $e) {
    //         // Log the error
    //         \Log::error($e);
    //         return false;
    //     }
    // }
    
    
    // public function resetPasswordEmailBased(Request $request) {
    //     try {
    //         // Validate the email
    //         $validator = Validator::make($request->all(), [
    //             'email' => 'required|email',
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json(['status' => 'false', 'message' => 'Invalid email format'], 400);
    //         }
    
    //         $user = User::where('email', $request->email)->first();
    //         if (!$user) {
    //             return response()->json(['status' => 'false', 'message' => 'Email not found'], 404);
    //         }
    
    //         $newPassword = Str::random(8); // Change the password length as needed
    
    //         // Send password reset email
    //         $emailSent = $this->sendPasswordEmail($newPassword, $request->email);
   
    //         if (!$emailSent) {
    //             return response()->json(['status' => 'false', 'message' => 'Failed to send reset link'], 500);
    //         }
    
    //         // Update user's password
    //         $user->password = Hash::make($newPassword);
    //         $user->save();
    
    //         return response()->json(['status' => 'true', 'message' => 'Password updated successfully'], 200);
    //     } catch (\Exception $e) {
    //         // Log the error
    //         \Log::error($e);
    //         return response()->json(['status' => 'false', 'message' => 'Failed to update password'], 500);
    //     }
    // }

   

}