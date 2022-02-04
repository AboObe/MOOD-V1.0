@extends('admin.notification.base') 
@section('action-content')

<script  src="{{asset('/js/mood/jquery.js')}}"></script>
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
            <!--Header-->
            <div class="alert alert-success" role="alert">
            </div>
            <!-- Body -->
            <form  method="Post"  action="{{route('web_notification.store') }}" data-parsley-validate>
                {{ csrf_field() }}
                <div class="row form-group">
                        <label >Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" data-parsley-trigger="change" required placeholder="Enter Title Message" class="form-control" id="title">
                    
                </div>
                <div class="row form-group">
                    <label class="col-md-4 control-label">Restaurant</label>
                    <select class="form-control select2" name="restaurant_id" id="restaurant_id"  >
                            <option></option>
                            @foreach ($restaurants as $restaurant)
                                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                            @endforeach
                    </select>
                </div>

                <div class="row form-group">
                        <label >Body <span class="text-danger">*</span></label>
                        <textarea class="form-control"  id="body" name="body" rows="3" required></textarea>

                    
                </div>

                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Send
                    </button>
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