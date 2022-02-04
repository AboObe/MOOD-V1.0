<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class testController extends Controller
{
    public function about_us(){
		return view('about_us');
	}
	
	public function privacy_policy(){
		return view('privacy_policy');
	}
	
	public function disclaimer(){
		return view('disclaimer');
	}
}
