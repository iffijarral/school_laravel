<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Http\Traits\CreateState;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;


class AuthController extends Controller
{    
    use CreateState;
    // authenticate              
    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        // Validate the user's input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'            
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($credentials)) {
            // Authentication success
            /** @var \App\Models\User $user */
            $user = Auth::user();            

            // // Load packages info in $user object
            // $userPackages = $user->packages;
            
            // $numOfTests = 0;

            // if (isset($userPackages) && count($userPackages) > 0) {
                
            //     $length = count($userPackages);

            //     // Fetch last purchased package info
            //     $numOfTests = $userPackages[$length-1]->numberoftests;             
            // } 
            
            // $state = [
            //     'name'       => $user->name,
            //     'email'      => $user->email,
            //     'noOfTests'  => $numOfTests,
            //     'status'     => $user->status,
            //     'isLoggedIn' => true    
            // ];
            
            // Get state from CreateState trait
            $state = $this->createUserState($user);
            
            // Generate an API token for the user
            $token = $user->createToken('API Token')->plainTextToken;

            // Set HTTP-only cookie                        
            $cookie = Cookie::make('token', $token, 60, null, null, false, true); // The last parameter sets the cookie as HTTP-only
            
            // Return the token as the API response
            
            return response(compact('state', 'token'))->withCookie($cookie);
        } else {
            // Authentication failed
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        
    }        
   
    public function store(SignupRequest $request) {     

        // Retrieve validated data from request object
        $data = $request->validated();   
                 
        // Create user object
        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],   
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],         
            'type' => $data['type'],
            'status' => 0
            
        ]);
        if($user) {
            return response()->json(['message' => 'User Created'], 201);
        }

        return response()->json(['error' => 'User not created'], 422);
    }

    public function logout(Request $request) {   
       
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);

    }    

    // Change password, when user requests to change it after logging into the system
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                ->letters()
                ->symbols()
                ->numbers()                
            ]
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();                
        
        // check if current password is correct
        if (Hash::check($request->current_password, $user->password)) {
            
            // change or update password with new password
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
    
            return response()->json(['message' => 'Password changed successfully']);
        } 

        // return following message, when user entered wrong current password
        return response()->json(['error' => 'Invalid current password.'], 422);
                
    }

    // Send password reset email having a link
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Given email address is not found']);
        }        
 
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT ? response()->json(['message' => __($status)]) : response()->json()->withErrors(['email' => __($status)]);
    }

    // Validate token and redirect to front-end reset page, where user gives new password to reset
    public function reset(Request $request)
    {
        $token = $request->route('token');
        $email = $request->query('email');

        $user = User::where('email', $email)->first();

        $status = Password::tokenExists($user, $token);
    
        if ($status === Password::INVALID_TOKEN || $status === Password::INVALID_USER) {
            // Token is invalid
            return response()->json()->withErrors(['email' => __($status)]);            

        } 

        return redirect(env('APP_FRONTEND').'/reset-password/'.$token.'?email='.$email);

    }

    // reset password
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)
                ->letters()
                ->symbols()
                ->numbers()
            ]
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $status = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        } else {
            return response()->json(['error' => trans($status)], 422);
        }

    }

    protected function resetPassword($user, $password)
    {
        $user->password = bcrypt($password);

        $user->save();        
    }
    
}