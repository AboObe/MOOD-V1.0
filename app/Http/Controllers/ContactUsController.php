<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use Auth;
use App\Validation\RegisterRequest;
use App\Mail\ContactUsEmail;
use Carbon\Carbon;
use App\User;

class ContactUsController extends BaseController
{
    public function send_mail(Request $request)
    {

        \Mail::to('eyad.jawabra.it@gmail.com')->send(new ContactUsEmail($request));

        session()->flash('message', 'Please check your email to activate your account');
        return 5;
    }
}
