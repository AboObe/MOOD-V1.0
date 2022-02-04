<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <meta name="mood" content="mood , resturant , cafe , offer ">

        <title>MOOD</title>
        <meta name="description" content="Mood | offers ">
        <meta name="author" content="alaa batha - https://www.mood.ae">


    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">


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

    <!-- Modernizr -->
    <script src="{{asset('assets/js/modernizr.min.js')}}"></script>

    <!-- jQuery -->
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>

    <!-- Moment -->
    <script src="{{asset('assets/js/moment.min.js')}}"></script>

    <!-- BEGIN CSS for this page -->
    <link href="{{asset('assets/plugins/jquery.filer/css/jquery.filer.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/jquery.filer/css/themes/jquery.filer-dragdropbox-theme.css')}}" rel="stylesheet" />
    <!-- END CSS for this page -->
    <!-- Location Google Map-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAs5np-6fXYxviPtwGLRGcpvMi8WzPDYDI&v=3.exp&sensor=false">
        </script>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
        </script>

        <!--End Location-->

</head>
    <body class="adminbody">
    <div id="main">
        <!-- top bar navigation -->
            <div class="headerbar">

                <!-- LOGO -->
                <div class="headerbar-left">
                    <a class="logo"><img alt="logo" src="{{asset('assets/images/logo.png')}}" /> </a>
                </div>

                <nav class="navbar-custom">

                            <ul class="list-inline float-right mb-0">

                                <li class="list-inline-item dropdown notif">
                                    <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                        <img src="{{asset('assets/images/avatars/admin.png')}}" alt="Profile image" class="avatar-rounded">
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right profile-dropdown">
                                        <!-- item-->
                                        <div class="dropdown-item noti-title">
                                            <h7 class="text-overflow">Hello, Rest Manager </h7>
                                        </div>

                                        <!-- item-->
                                        <a href="{{ route('profile.index') }}" class="dropdown-item notify-item">
                                            <i class="fa fa-user"></i> <span>Profile</span>
                                        </a>

                                        <!-- item-->
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
                                            <i class="fa fa-power-off"></i> <span>Logout</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>


                                    </div>
                                </li>

                            </ul>

                            <ul class="list-inline menu-left mb-0">
                                <li class="float-left">
                                    <button class="button-menu-mobile open-left">
                                        <i class="fa fa-fw fa-bars"></i>
                                    </button>
                                </li>
                            </ul>

                </nav>

            </div>

        <!-- End Navigation -->
        <!-- Left Sidebar -->
<div class="left main-sidebar">

        <div class="sidebar-inner leftscroll">

            <div id="sidebar-menu">

            <ul>
                    <li class="submenu">
                        <a href="{{ route('my_restaurant.index')}}"><i class="fa fa-fw fa-th"></i> <span> RESTAURANTS </span> </a>
                    </li>
                    <li class="submenu">
                        <a href="{{ route('web_my_offer.index') }}"><i class="fa fa-fire bigfonts"></i> <span> OFFERS </span> </a>
                    </li>
                    <li>
                        <a href="{{route('REDEMPTIONS')}}">REDEMPTIONS VIEWS</a>
                    </li>
            </ul>

            <div class="clearfix"></div>

            </div>

            <div class="clearfix"></div>

        </div>

    </div>

        <!-- End Sidebar -->

        <!-- Start content -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content" style="width :100%">
                    <div class="container-fluid">
                            <div class="row col-xl-12">
                                        <div class="col-xl-12">
                                                <div class="breadcrumb-holder">
                                                        <h1 class="main-title float-left">MOOD</h1>
                                                        <ol class="breadcrumb float-right">
                                                            <li class="breadcrumb-item">Rest Manager</li>
                                                            <li class="breadcrumb-item active">Restaurant</li>
                                                        </ol>
                                                        <div class="clearfix"></div>
                                                </div>
                                        </div>
                            </div>
                            <!-- end row -->
                            <div class="row">
                                    <div class="col-xl-12">
                                        @yield('action-content')
                                    </div>
                            </div>
                    </div>
                </div>
            </div>
        <!-- END content-page -->

        <!-- Footer -->
            <footer class="footer">
                <span class="text-right">
                Copyright <a target="_blank" href="https://www.mood.ae">Mood.ae 2020</a>
                </span>
                <span class="float-right">
                Powered by <a target="mard.ae" href=""><b>MARD</b></a>
                </span>
            </footer>
        <!-- END Footer -->

    </div>
    <!-- END main -->
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
<script src="{{asset('assets/plugins/jquery.filer/js/jquery.filer.min.js')}}"></script>
<script>
$(document).ready(function(){

  'use-strict';

    //Example 2
    $('#filer_example2').filer({
        limit: 3,
        maxSize: 3,
        extensions: ['jpg', 'jpeg', 'png', 'gif', 'psd'],
        changeInput: true,
        showThumbs: true,
        addMore: true
    });

});
</script>
<!-- END Java Script for this page -->

</body>
</html>
