@extends('admin.category.base')

@section('action-content')
<script  src='{{asset("/js/mood/jquery.js")}}'></script>

    <!-- END CSS for this page --> 
    <div class="card mb-3">
        <div class="card-body">
            <div class="alert alert-success" role="alert">
            </div>
            <form >
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row">   
            
                <div class="col-lg-9 col-xl-9">
                

                    <div class="form-group">
                        <label >User Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter user Name" class="form-control" id="name" readonly value="{{$user->name}}">
                    </div>
                    <div class="row form-group">
                        <div class="col-md-6">
                            <label class="col-md-4 control-label">Role</label>
                            <input type="text" name="role" data-parsley-trigger="change" required class="form-control" readonly value="{{$user->type}}">
                        </div>
                        <div class="col-md-6">
                            <label class="col-md-4 control-label">Status</label>
                            <input type="text"  data-parsley-trigger="change" placeholder="Enter End Time" class="form-control"  value="{{$user->status_name}}" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="s_name">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" data-parsley-trigger="change" required placeholder="Enter User Email" class="form-control" id="email" readonly value="{{$user->email}}">
                    </div>
                    <div class="form-group">
                            <label class="col-md-4 control-label">Phone Number<span class="text-danger">*</span></label>
                            <input type="text" name="phone" data-parsley-trigger="change" required placeholder="Enter User Phone Number" class="form-control" id="phone" readonly value="{{$user->phone}}">
                    </div>

                    <div class="form-group">
                        <label for="s_name">User Address </label>
                        <input type="text" name="address" data-parsley-trigger="change"  placeholder="Enter user Name" class="form-control" id="address" readonly value="{{$user->address}}">
                    </div>             

                

                
                    <div class="form-group text-right m-b-0">

                        <a class="btn btn-secondary m-l-5" href ="{{route('web_user.index')}}">
                            Cancel
                        </a>
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
                                            src="../{{$user->photo}}" class="img-fluid" >
                                        </a>
                            </div>
                        <br>
                    <!--    <i class="fa fa-trash-o fa-fw"></i> <a class="delete_image" href="#">Remove avatar</a>
                      -->              
                    </div>  
                   
                    
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
