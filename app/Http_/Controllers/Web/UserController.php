<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Validator;
use DB;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerificationEmail;
use Illuminate\Support\Str;
use Carbon\Carbon;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('send_notification');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $count = $users->count();

        return view('admin/user/index',compact('users','count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/user/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $input = $request->all();  
        $validator = Validator::make($input, [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'photo' => 'file|image|max:5000',
        ]);
   
        if($validator->fails()){
            return $validator->errors();       
        }
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
            $input['photo'] = $fileName;
        }

        $input['name'] = trim($request->input('name'));
        $input['email'] = strtolower($request->input('email'));
        $input['password'] = bcrypt($request->input('password'));
        $input['email_verification_token'] = Str::random(32);
      

        $user = User::create($input);

        \Mail::to($user->email)->send(new VerificationEmail($user));

        session()->flash('message', 'Please check your email to activate your account');
        
        
        return redirect()->intended('web_user');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('admin/user/show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin/user/edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null ) {
            return 'User not found.';
        }
        
        $input = [
            'name' => $request->name == null ? $user->name : trim($request->name),
            'type' => $request->type == null ? $user->type : $request->type ,
            'email' => $request->email == null ? $user->email : strtolower($request->email),
            'phone' => $request->phone,
            'address' => $request->address,
        ];
        /**
        * Password
        */
        if(!($request->password == null || $request->password == ""))
            $input['password'] = bcrypt($request->password);



        $validator = Validator::make($input, [
            'name' => 'required | unique:users,name,'.$id,
            'email' => 'required | unique:users,email,'.$id,
            'phone' => 'required | unique:users,phone,'.$id,
            'type' => 'required',
            //'status_id' => 'numeric',
        ]);
        //return $input;
        if($validator->fails()){
            return $validator->errors();       
        }

        $user = user::find($id);
        if ($user == null ) {
            return 'user not found.';
        }
        /**
         * store Image
         */
        $fileName = "" ;
        if ($request->hasFile('photo')) {
            $validator = Validator::make($input, [
                'photo' => 'file|image|max:5000',
            ]);
            if($validator->fails()){
                return $validator->errors();       
            }
            //delete old image
            Storage::delete($user->photo);
            //add new image
            $image      = $request->file('photo');
            $fileName   = time() . '.' . $image->getClientOriginalExtension();
            $img = Image::make($image->getRealPath());
            $img->stream();
            Storage::disk('local')->put('public/user'.'/'.$fileName, $img, 'public');
            $fileName = 'storage/user'.'/'.$fileName;
            $input["photo" ] = $fileName;
        }


        user::where('id', $id)->update($input);
        
        $user->save();

        if($request->code == "profile")
            return back();
        else
            return redirect()->intended('web_user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /*$user = User::find($id);
        if ($user == null ) {
            return 'User not found.';
        }
        $input['status_id'] = 1; 
        User::where('id', $id)->update($input);
*/
        return redirect()->intended('web_user');
    }

    /**
     * Show the Profile for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile($id)
    {
        $user = User::find($id);
        return view('admin/user/profile',compact('user'));
    }
    /**
    *   Resend email 
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
    /*
    * Notifiction
    */
    public function send_notification_form(){
        return view('admin/notification/create');
    }
    public function send_notification(Request $request){

        $curl = curl_init();

        $devices_collection = DB::table('mobile_devices')->select('device_code')->get();
        $to= [];
        foreach ($devices_collection as $device) {
           array_push($to,$device->device_code);
        }       
        $title = $request->title;
        $body = $request->body;
        
        $data = array("to" => $to, "title" => $title, "body" => $body);
        

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://exp.host/--/api/v2/push/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "Accept: application/json",
            "Accept-Encoding: application/gzip",
            "Content-Type: application/json"
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return redirect()->route('web_user.form_send_notification');
    }
}
