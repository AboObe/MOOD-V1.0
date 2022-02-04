@extends('admin.user.base')

@section('action-content')
<script  src='{{asset("/js/mood/jquery.js")}}'></script>
        <!-- BEGIN CSS for this page -->
        <style>
        .parsley-error {
            border-color: #ff5d48 !important;
        }
        .parsley-errors-list.filled {
            display: block;
        }
        .parsley-errors-list {
            display: none;
            margin: 0;
            padding: 0;
        }
        .parsley-errors-list > li {
            font-size: 12px;
            list-style: none;
            color: #ff5d48;
            margin-top: 5px;
        }
        .form-section {
            padding-left: 15px;
            border-left: 2px solid #FF851B;
            display: none;
        } 
        .form-section.current {
            display: inherit;
        }
    </style>
    <!-- END CSS for this page -->
    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-success" role="alert">
            </div>
            <form method="POST"  action="{{ route('profile.update', $user->id) }}" data-parsley-validate novalidate enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <input type="hidden" name="code" value="profile">

                <div class="row"> 
                <div class="col-lg-9 col-xl-9">
                

                    <div class="form-group">
                        <label >User Name <span class="text-danger">*</span></label>
                        <input type="text"  data-parsley-trigger="change"  placeholder="Enter user Name" class="form-control"  value="{{$user->name}}">
                    </div>
                    <div class="form-group">
                            <label class="col-md-4 control-label">Role</label>
                            <input type="text"  data-parsley-trigger="change" required class="form-control" readonly  value="{{$user->type}}">
                    </div>

                    <div class="form-group">
                        <label for="s_name">Email <span class="text-danger">*</span></label>
                        <input type="email"  data-parsley-trigger="change" required placeholder="Enter User Email" class="form-control" readonly  value="{{$user->email}}">
                        @if($errors->has('email'))
                            <span class="text-danger"><small>{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="form-group">
                            <label class="col-md-4 control-label">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" name="phone" data-parsley-trigger="change" placeholder="Enter User Phone Number" class="form-control" id="phone"  value="{{$user->phone}}">
                    </div>
                    <div class="form-group">
                        <label>Password (leave empty not to change)</label>
                        <input class="form-control" name="password" type="password" value="">
                    </div>

                    <div class="form-group">
                        <label for="s_name">User Address </label>
                        <input type="text" name="address" data-parsley-trigger="change"  placeholder="Enter user Name" class="form-control" id="address"  value="{{$user->address}}">
                    </div>             

                

                
                    <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
                    </button>
                </div>
                </div>
                
                <div class="col-lg-3 col-xl-3 border-left">
                    <!--<b>Latest activity</b>: {{$user->last_login}}  -->
                    <br>
                    <b>Register date: </b>: {{$user->created_at}}  
                    
                    <div class="m-b-10"></div>
                    
                    <div id="avatar_image">
                        <div class="col-md-6">
                                <a data-fancybox="gallery"  class="col-sm-2">
                                            <img alt="image" 
                                            src="../../{{$user->photo}}" class="img-fluid" >
                                        </a>
                            </div>
                        <br>
                        <input type="file" id="photo" name="photo"  >
                        @if($errors->has('photo'))
                            <span class="text-danger"><small>{{$errors->first('photo')}}</small>
                        @endif           
                    </div>  
                   


                
            </div>

            </form>

        </div>                                                      
        <!-- end card-->                  
    </div>

    <!-- BEGIN Java Script for this page -->
    <script src='{{asset("assets/plugins/parsleyjs/parsley.min.js")}}'></script>
    <script>
      $('#form').parsley();
  </script>
  @endsection
