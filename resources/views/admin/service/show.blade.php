@extends('admin.service.base')

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
                
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Service Name <span class="text-danger">*</span></label>
                        <input type="text" data-parsley-trigger="change"  placeholder="Enter Service Name" class="form-control" value="{{$service->name}}" readonly>
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Restaurant</label>
                            <input type="text"  data-parsley-trigger="change"  class="form-control" value="{{$service->restaurant_name}}" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Category </label>
                        <input type="text"  data-parsley-trigger="change"  class="form-control" readonly value="{{$service->category_name}}">
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Status</label>
                            <input type="text"  data-parsley-trigger="change"  class="form-control" value="{{$service->status_name}}" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Price</label>
                        <input type="text" name="price" data-parsley-trigger="change"  placeholder="Enter Price" class="form-control" id="price" value="{{$service->price}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label for="avatar" class="col-md-4 control-label" >Image</label>
                        <div class="col-md-6">
                            <a data-fancybox="gallery"  class="col-sm-2">
                                        <img alt="image" 
                                        src="../{{$service->image}}" class="img-fluid" height="150px" width="150px">
                                    </a>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <label for="s_name">Content</label>
                    <textarea name="content" data-parsley-trigger="change"  placeholder="Enter Price" class="form-control" id="content" readonly>{{$service->content}}</textarea>
                    
                </div>

                

                <div class="form-group text-right m-b-0">

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
