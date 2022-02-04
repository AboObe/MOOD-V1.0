<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Str;
use DB;
use App\Mail\VerificationEmail;
use Auth;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message 
     */
    public function create(Request $request)
    {
    	$new_token = Str::random(60);
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);

        $passwordReset = DB::table('password_resets')->where('email','=',$user->email)->first();
        if($passwordReset == null)
        	$passwordReset = PasswordReset::create([
                'email' => $user->email,
                'token' => $new_token
             ]);
        else
        	PasswordReset::where('id', $passwordReset->id)->update([
                'token' => $new_token
             ]);

        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($new_token,'web/password/find')
            );
        return redirect()->intended();
    }
    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if ($passwordReset == null)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);

        if (Carbon::parse($passwordReset->created_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        }
        return redirect()->intended('password/reset/'.$token);
    }
     /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid.'
            ], 404);
        
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);
        $user->password = bcrypt($request->password);
        $user->save();

        $passwordReset->delete();
        //$user->notify(new PasswordResetSuccess($passwordReset));
        return redirect()->route('login');
    }

    /**
    *   Resend email to active account
    */
    public function resend(){
        $user = auth()->user();
            
        \Mail::to($user->email)->send(new VerificationEmail($user));

        session()->flash('message', 'Please check your email to activate your account');
       
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
        
        session()->flash('message', 'Please check your email to activate your account');
        return redirect()->intended('home');
    }
}