@extends('restaurant_manager.offer.base') 

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
                        <label for="s_name">offer Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter offer Name" class="form-control" id="name" value="{{$offer->name}}" readonly>
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Restaurant</label>
                            <input type="text" name="end_time" data-parsley-trigger="change" placeholder="Enter End Time" class="form-control" id="end_time" value="{{$offer->restaurant_name}}" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Price </label>
                        <input type="number" name="price" data-parsley-trigger="change" placeholder="Enter Price" class="form-control" id="price" value="{{$offer->price}}" readonly>
                    </div>
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">Status</label>
                            <input type="text" name="end_time" data-parsley-trigger="change" placeholder="Enter End Time" class="form-control" id="end_time" value="{{$offer->status_name}}" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Start Date </label>
                        <input type="date" name="start_date" data-parsley-trigger="change" placeholder="Enter Start Date" class="form-control" id="start_date" value="{{$offer->start_date}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label >End Date </label>
                        <input type="date" name="end_date" data-parsley-trigger="change" placeholder="Enter End Date" class="form-control" id="end_date" value="{{$offer->end_date}}" readonly>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Start Time </label>
                        <input type="time" name="start_time" data-parsley-trigger="change" placeholder="Enter Start Time" class="form-control" id="start_time" value="{{$offer->start_time}}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label >End Time </label>
                        <input type="time" name="end_time" data-parsley-trigger="change" placeholder="Enter End Time" class="form-control" id="end_time" value="{{$offer->end_time}}" readonly>
                    </div>
                </div>

                 <div class="row form-group">
                    <div class="col-md-6">
                        <label for="avatar" class="col-md-4 control-label" >Image</label>
                        <div class="col-md-6">
                            <a data-fancybox="gallery"  class="col-sm-2">
                                        <img alt="image" 
                                        src="../{{$offer->image}}" class="img-fluid" height="150px" width="150px">
                                    </a>
                        </div>
                    </div>  
                    <div class="col-md-6">
                        <div>
                            <label >Detals</label>
                        </div>
                        <div>
                            <textarea id="details" name="details" cols="60"  readonly>{{$offer->details}}</textarea>
                        </div>
                    </div>
                </div>



                 









                <div class="form-group text-right m-b-0">

                    <a class="btn btn-secondary m-l-5" href ="{{route('web_my_offer.index')}}">
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
