@extends('admin.campaign.base')

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
            <form method="POST"  action="{{ route('web_campaign.update', $campaign->id) }}" data-parsley-validate novalidate enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="s_name">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Campaign Name" class="form-control" id="name" value="{{$campaign->name}}">
                </div>
                <div class="form-group">
                    <label>Start Date </label>
                    <input type="text" name="start_date" data-parsley-trigger="change" placeholder="Enter Start Date" class="form-control" id="start_date" value="{{$campaign->start_date}}">
                </div>
                <div class="form-group">
                    <label >End Date</label>
                    <input type="text" name="end_date" data-parsley-trigger="change" placeholder="Enter End Date" class="form-control" id="end_date" value="{{$campaign->end_date}}">
                </div>


                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-clone"></i> Restaurant</h3>
                            </div>

                            <div class="card-body">
                            <!-- restaurants -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".restaurant">Restaurants</a>

                                <div class="modal fade bd-example-modal-lg restaurant" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Restaurants</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="modal-body" id="dynamic_restaurant">
                                            @foreach($campaign_restaurants as $restaurant)
                                                <input type="hidden" id="old_restaurants[]" name="old_restaurants[]" value="{{$restaurant->id}}">
                                                <div  class="row control-group-restaurant" style="padding-left:10px">
                                                    <div class="col-md-10">
                                                        <div  class="row">
                                                            <input type="hidden"  name="new_restaurants[]" id="new_restaurants[]" value="{{$restaurant->id}}">
                                                            <input type="text" data-parsley-trigger="change"  class="form-control" value="{{$restaurant->name}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-0">
                                                        <button type="button"  class="btn btn-danger btn_remove_restaurant">X</button>

                                                    </div>
                                                </div>
                                            @endforeach
                                            <div class="row" >
                                                <div class="col-md-10">
                                                        <select class="form-control" name="new_restaurants[]" id="new_restaurants[]">
                                                            <option ></option>
                                                            @foreach ($restaurants as $restaurant)
                                                                <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                                                            @endforeach
                                                        </select> 
                                                </div>
                                                <div class="col-md-0">
                                                    <button type="button" name="add_restaurant" id="add_restaurant" class="btn btn-success">Add More
                                                    </button>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <!--End Body-->

                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <!-- END restaurant -->
                            </div>
                        </div>
                    </div>

                                <div class="col-md-6">
                                    <label class="col-md-4 control-label">Status</label>
                                    <select class="form-control select2" name="status_id" id="status_id"  >
                                        @foreach ($statuses as $status)
                                            <option value="{{$status->id}}"  {{$status->id == $campaign->status_id ? 'selected' : ''}}>{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                </div>










                <div class="form-group">
                        <label class="col-md-4 control-label">Details</label>
                        <textarea class="form-control"  id="details" name="details">{{$campaign->details}}</textarea>
                </div>

                


                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
                    </button>
                    <a class="btn btn-secondary m-l-5" href ="{{route('web_campaign.index')}}">
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
    <script type="text/javascript">
       $(document).ready(function(){ 
            var i=1;
            //restaurants
            $('#add_restaurant').click(function(){
                i++;
                $('#dynamic_restaurant').append('\
                    <div id="'+i+'_remove_restaurant" class="row" >\
                        <div class="col-md-10">\
                            <select class="form-control" name="new_restaurants[]" id="new_restaurants['+i+']">\
                                @foreach ($restaurants as $restaurant)\
                                    <option value="{{$restaurant->id}}">{{$restaurant->name}}</option>\
                                @endforeach\
                            </select>\
                        </div>\
                        <div class="col-md-0" >\
                            <button name="'+i+'_remove_restaurant" id="'+i+'_remove_restaurant" class="btn btn-danger btn_remove_restaurant">X</button>\
                        </div>\
                    </div>');  
            });

            $(document).on('click', '.btn_remove_restaurant', function(){  
                var button_id = $(this).attr("id");   
                $('#'+button_id+'').remove();  
            });


            $("body").on("click",".btn_remove_restaurant",function(){ 
                $(this).parents(".control-group-restaurant").remove();
            });
        });
    </script>
  @endsection
