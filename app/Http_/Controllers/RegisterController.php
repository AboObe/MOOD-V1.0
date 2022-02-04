<?php

namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Validation\RegisterRequest;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Validator;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class RegisterController extends BaseController
{
  
    use RegisterRequest;


    /*
    *   Register
    */

    public function ShowRegisterForm()
    {
        //return view('authentication.register');
    }

    public function HandleRegister(Request $request)
    {

        $this->inputDataSanitization($request->all());

        if (User::where('email', '=', $request->email)->count() > 0) {
        // user not found
          $response = [
            'success' => false,
            'message' => "Duplicate entry, email is found",
            'code'  => 404,
          ];
          return response()->json($response);
        }

        $user = User::create([ 
            'name' => trim($request->input('name')),
            'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
            'email_verification_token' => Str::random(32)
        ]);
            
        \Mail::to($user->email)->send(new VerificationEmail($user));

        session()->flash('message', 'Please check your email to activate your account');
       
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
		
		//return redirect()->back();
		return $this->sendResponse($success,'User register successfully, check your mail to activated account');

    }  
    /**
    *   LOGIN
    */

    public function ShowLoginForm()
    {
       // return view('authentication.login');
    }

    public function HandleLogin(Request $request)
    {
        
        $this->loginDataSanitization($request->except(['_token']));
        
        if (User::where('email', '=', $request->email)->count() == 0) {
        // user not found
          $response = [
            'success' => false,
            'message' => "email not found",
            'code'  => 404,
          ];
          return response()->json($response);
        }

        $credentials = $request->except(['_token']);


        $user = User::where('email',$request->email)->first();

        if($user->email_verified == 1){

			if (auth()->attempt($credentials)) {

                 $user = auth()->user();

                 $user->last_login = Carbon::now();

                 $user->save();

                $success['token'] =  $user->createToken('MyApp')->accessToken; 
				$success['name'] =  $user->name;
   
				return $this->sendResponse($success, 'User login successfully.');
                 //return redirect()->route('home');

            }  
			else{ 
				return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
			}
        }      
		
        session()->flash('message', 'Invalid Credentials');

        session()->flash('type', 'danger');

        //return redirect()->back();
        return $this->sendError('Email Not Verification.');
        
    }

    public function facebookLogin(Request $request){
        
        $user = User::where('email', '=', $request->email)->first();
        
        if (!$user) 
        {
            /**
             * store Image
             */
            $fileName = "" ;
            if ($request->hasFile('photo')) {
                $image      = $request->file('photo');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                $img = Image::make($image->getRealPath());
                $img->stream();
                Storage::disk('local')->put('public/User'.'/'.$fileName, $img, 'public');
                $fileName = 'storage/User'.'/'.$fileName;
            }

            $user = User::create([ 
                'name' => 'mood',
                'email' => strtolower($request->input('email')),
                'password' => bcrypt('567836770'),
                'type'=>'normal',
                'email_verified' => 1,
                'email_verified_at' => Carbon::now(),
                'email_verification_token' => '', 
                'photo' => $fileName,
            ]);
        }

        auth()->login($user);
        $user = auth()->user();
        $user->last_login = Carbon::now();
        $user->save();
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;
   
        return $this->sendResponse($success, 'User login successfully.');
    }
}