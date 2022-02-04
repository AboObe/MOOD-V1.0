<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<meta name="mood" content="mood , resturant , cafe , offer ">
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 
		<title>MOOD</title>
		<meta name="description" content="Mood | offers ">
		<meta name="author" content="alaa batha - https://www.mood.ae">

		<!-- Favicon -->
		<link rel="shortcut icon" href="{{asset('assets/images/favicon.ico')}}">

		<!-- Switchery css -->
		<link href="{{asset('assets/plugins/switchery/switchery.min.css')}}" rel="stylesheet" />
		
		<!-- Bootstrap CSS -->
		<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
		
		<!-- Font Awesome CSS -->
		<link href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css" />
		
		<!-- Custom CSS -->
		<link href="{{asset('assets/css/style.css')}}" rel="stylesheet" type="text/css" />	
		
		<!-- BEGIN CSS for this page -->
		<!-- END CSS for this page -->
				
</head>

<body class="adminbody">

<div id="main">

	<!-- top bar navigation -->
	@include('layouts.manager_header')
	<!-- End Navigation -->
	
 
	<!-- Left Sidebar -->
	@include('layouts.manager_sidebar')
	<!-- End Sidebar -->


    
	
		<!-- Start content -->
       @yield('content')
	    <!-- END content-page -->
    @include('layouts.footer')
	

</div>
<!-- END main -->

<script src="{{asset('assets/js/modernizr.min.js')}}"></script>
<script src="{{asset('assets/js/jquery.min.js')}}"></script>
<script src="{{asset('assets/js/moment.min.js')}}"></script>

<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

<script src="{{asset('assets/js/detect.js')}}"></script>
<script src="{{asset('assets/js/fastclick.js')}}"></script>
<script src="{{asset('assets/js/jquery.blockUI.js')}}"></script>
<script src="{{asset('assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('assets/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/js/pikeadmin.js')}}"></script>

<!-- BEGIN Java Script for this page -->

<!-- END Java Script for this page -->

</body>
</html>