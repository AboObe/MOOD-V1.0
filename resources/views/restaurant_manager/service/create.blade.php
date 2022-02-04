@extends('restaurant_manager.service.base')
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
            <form  method="POST"  action="{{ route('web_service.store') }}" data-parsley-validate novalidate enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Service Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change"  placeholder="Enter Service Name" class="form-control" id="name">
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Restaurant</label>
                            <select class="form-control select2" name="restaurant_id" id="restaurant_id"  >
                                    @foreach ($restaurants as $restaurant)
                                        <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                    @endforeach
                            </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Category </label>
                        <select class="form-control select2" name="category_id" id="category_id"  >
                                    @foreach ($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Status</label>
                            <select class="form-control select2" name="status_id" id="status_id"  >
                                    @foreach ($statuses as $status)
                                        <option value="{{$status->id}}">{{$status->name}}</option>
                                    @endforeach
                            </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Price</label>
                        <input type="text" name="price" data-parsley-trigger="change"  placeholder="Enter Price" class="form-control" id="price">
                    </div>
                    <div class="col-md-6">
                        <label for="avatar" class="col-md-4 control-label" >Image</label>
                        <div class="col-md-6">
                            <input type="file" id="image" name="image"  >
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="s_name">Content</label>
                    <textarea name="content" data-parsley-trigger="change"  placeholder="Enter Price" class="form-control" id="content"></textarea>
                    
                </div>

                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Create
                    </button>
                    <a class="btn btn-secondary m-l-5" href ="{{route('web_service.index')}}">
                        Cancel
                    </a>
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