<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function rules($data){
        $messages = [
            'email.required'        => 'Please enter email ',
            'email.exists'          => 'Email not registered',
            'email.email'           => 'Please enter valid email ',
            'password.required' => 'Enter your password.',
        ]; 

        $validator = Validator::make($data, [
            'email'             =>'required|email|exists:users',
            'password'      =>'required'
        ], $messages);

        return $validator;
    }

    public function login(Request $request)
    {  
        $validator = $this->rules($request->all());
        if($validator->fails()){
           return redirect()->back()->withErrors($validator)->withInput();   
        }
        else{
           if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
             switch(Auth::user()->type){
                        case 'admin':
                            return redirect()->route('home');
                            break;
                        case 'restaurant_manager':
                            return redirect()->route('home');
                            break;
                        default:
                            Auth::logout();
                            return redirect()->route('/')->with('error','you can not login');
                    }
           }
           else{
             return \Redirect::back()
                    ->withInput()
                    ->withErrors([
                        'password' => 'Incorrect password!'
                    ]);
           }
        }
    }
}
