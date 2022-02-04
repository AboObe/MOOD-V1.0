@extends('admin.restaurant.base')
@section('action-content')

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

        #myMap {
           height: 350px;
           width: 680px;
        }
    </style>
    <!-- END CSS for this page -->
    <div class="card mb-3">
        <div class="card-body">
            <!--Header-->
            <div class="alert alert-success" role="alert">
            </div>
            <!-- Body -->
            <form  method="POST"  action="{{ route('web_restaurant.store') }}" data-parsley-validate novalidate enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Restaurant Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Restaurant Name" class="form-control" id="name">
                        @if($errors->has('name'))
                            <span class="text-danger"><small>{{$errors->first('name')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="s_name">Manager</label>
                        <select class="form-control" name="manager_id" id="manager_id"  >
                            <option></option>
                            @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">City</label>
                            <input type="text" name="city" data-parsley-trigger="change" placeholder="Enter City" class="form-control" id="city" required>
                            @if($errors->has('city'))
                                <span class="text-danger"><small>{{$errors->first('city')}}</small>
                            @endif
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label >Status</label>
                                <select class="form-control" name="status_id" id="status_id"  >
                                        @foreach ($statuses as $status)
                                            <option value="{{$status->id}}">{{$status->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label >Is Featured</label>
                                <select class="form-control" name="is_featured" id="is_featured"  >
                                    <option value="0">NO</option>
                                    <option value="1">YES</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Email </label>
                        <input type="email" name="email" data-parsley-trigger="change"  placeholder="Enter Email" class="form-control" id="email">
                        @if($errors->has('email'))
                            <span class="text-danger"><small>{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label >phone </label>
                        <input type="number" name="phone" data-parsley-trigger="change"  placeholder="Enter Phone" class="form-control" id="phone">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >QR </label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="qr" data-parsley-trigger="change"  placeholder="Generate QR" class="form-control" id="qr" readonly>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="makeQR(20)">Generate</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="showQR()">Show QR</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label >PIN </label>
                        <input type="string" name="pin" data-parsley-trigger="change"  placeholder="Enter PIN" class="form-control" id="pin">
                    </div>
                </div>


                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Profile Image </label>
                        <div class="col-md-12">
                            <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon01" id="image" name="image">
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                        </div>
                        @if($errors->has('image'))
                            <span class="text-danger"><small>{{$errors->first('image')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label >Openning Hours </label>
                        <input type="text" name="opening_hours" data-parsley-trigger="change"  placeholder="Enter Openning Hours" class="form-control" id="opening_hours">
                    </div>
                </div>

                <div class="row">
                    <!-- -->
                    <!-- -->
                    <!-- -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-clone"></i> Tags & Categories & Media & Location</h3>
                            </div>
                            <div class="card-body">
                            <!-- Tags -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".tag">Tags</a>

                                <div class="modal fade bd-example-modal-lg tag" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Tags</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="form-group card-body" id="dynamic_tag">
                                            <div class="row" >
                                                <div class="col-md-10">
                                                        <select class="form-control" name="new_tags[]" id="new_tags[]">
                                                            <option ></option>
                                                            @foreach ($tags as $tag)
                                                                <option value="{{$tag->id}}">{{$tag->name}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-md-0">
                                                    <button type="button" name="add_tag" id="add_tag" class="btn btn-success">
                                                            Add More
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Body-->

                                        <div class="modal-footer">
                                        <button id='tags_save' type="button" class="btn btn-primary">Save</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <!-- end tags -->
                            <!-- Categories -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".category">Categories</a>

                                <div class="modal fade bd-example-modal-lg category" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Categories</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="form-group card-body" id="dynamic_category">
                                            <div class="row" >
                                                <div class="col-md-10">
                                                        <select class="form-control" name="new_categories[]" id="new_categories[]">
                                                            <option ></option>
                                                            @foreach ($categories as $category)
                                                                <option value="{{$category->id}}">{{$category->name}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <div class="col-md-0">
                                                    <button type="button" name="add_category" id="add_category" class="btn btn-success">
                                                            Add More
                                                        </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!--End Body-->
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <!-- end categories -->
                            <!-- Media -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".media">Media</a>

                                <div class="modal fade bd-example-modal-lg media" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h3><i class="fa fa-file"></i> Images</h3>
                                                Images upload with drag & drop
                                            </div>
                                            <div class="card-body">
                                                <input type="file" name="files[]" id="filer_example1" multiple="multiple">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" >Save</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <!-- end Media -->
                            <!-- Location -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".location">Locations</a>

                                <div class="modal fade bd-example-modal-lg location" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Locations</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="myMap"></div>
                                            <div class="row form-group">
                                                <div class="col-md-6">
                                                    <label >Address </label>
                                                    <input type="text" name="address" data-parsley-trigger="change" required placeholder="Enter Address" class="form-control" id="address">
                                                </div>
                                            </div>
                                            <input type="text"  name="latitude" id="latitude"  placeholder="Latitude"/>
                                            <input type="text"  name="longitude" id="longitude" placeholder="Longitude"/>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            <!-- end location -->
                            <!-- Social Media -->
                            <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".socialmedia ">Social Media</a>

                                <div class="modal fade bd-example-modal-lg socialmedia" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Social Media</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="form-group card-body" >
                                            <div class="row" >
                                                <div class="col-md-10">
                                                    <label >Facebook </label>
                                                    <input type="string" name="facebook" data-parsley-trigger="change"  placeholder="Enter Facebook" class="form-control" id="facebook">
                                                    <label >Instagram </label>
                                                    <input type="string" name="instagram" data-parsley-trigger="change"  placeholder="Enter Instagram" class="form-control" id="instagram">
                                                    <label >Snapchat </label>
                                                    <input type="string" name="snapchat" data-parsley-trigger="change"  placeholder="Enter Snapchat" class="form-control" id="snapchat">
                                                    <label >Whatsapp <small>(begin with '+971' without space)</small></label>
                                                    <input type="string" name="whatsapp" data-parsley-trigger="change"  placeholder="Enter Whatsapp" class="form-control" id="whatsapp"  pattern="[+][9][7][1][0-9]*">
                                                    <label >Youtube </label>
                                                    <input type="string" name="youtube" data-parsley-trigger="change"  placeholder="Enter Youtube" class="form-control" id="youtube">
                                                    <label >Website </label>
                                                    <input type="string" name="website" data-parsley-trigger="change"  placeholder="Enter Website" class="form-control" id="website">

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
                                <!--Social Media -->
                            </div>
                        </div><!-- end card-->
                    </div>
                    <!-- -->
                    <!-- -->
                    <!-- -->

                    <div class="form-group col-md-6">
                        <label >Description </label>
                        <textarea class="form-control"  id="description" name="description" rows="3"></textarea>


                    </div>
                </div>



                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Create
                    </button>
                    <a class="btn btn-secondary m-l-5" href ="{{route('web_category.index')}}">
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
        $(document).ready(function(){
            var i=1;
            //tags
            $('#add_tag').click(function(){
                i++;
                $('#dynamic_tag').append('\
                    <div id="'+i+'_remove_tag" class="row" >\
                        <div class="col-md-10">\
                            <select class="form-control" name="new_tags['+i+']" id="new_tags['+i+']">\
                                @foreach ($tags as $tag)\
                                    <option value="{{$tag->id}}">{{$tag->name}}</option>\
                                @endforeach\
                            </select>\
                        </div>\
                        <div class="col-md-0" >\
                            <button name="'+i+'_remove_tag" id="'+i+'_remove_tag" class="btn btn-danger btn_remove_tag">X</button>\
                        </div>\
                    </div>');
            });

            $(document).on('click', '.btn_remove_tag', function(){
                var button_id = $(this).attr("id");
                $('#'+button_id+'').remove();
            });
            //category
            $('#add_category').click(function(){
                i++;
                $('#dynamic_category').append('\
                    <div id="'+i+'_remove_category" class="row" >\
                        <div class="col-md-10">\
                            <select class="form-control" name="new_categories['+i+']" id="new_categories['+i+']">\
                                @foreach ($categories as $category)\
                                    <option value="{{$category->id}}">{{$category->name}}\
                                    </option>\
                                @endforeach\
                            </select>\
                        </div>\
                    <div class="col-md-0" >\
                        <button name="'+i+'_remove_category" id="'+i+'_remove_category" class="btn btn-danger btn_remove_category">X\
                        </button>\
                    </div>\
                    ');
            });

            $(document).on('click', '.btn_remove_category', function(){
                var button_id = $(this).attr("id");
                $('#'+button_id+'').remove();
            });

            /********
            //media**
            /********/
            'use-strict';
            $('#filer_example1').filter({
                limit: 100,
                maxSize: 1000,
                extensions: ['jpg', 'jpeg', 'png', 'gif', 'psd'],
                changeInput: true,
                showThumbs: true,
                addMore: true
            });
    });
  </script>
<script type="text/javascript">
        var map;
        var marker;
        var myLatlng = new google.maps.LatLng(25.09375,55.176106);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow();
        function initialize(){
        var mapOptions = {
        zoom: 18,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("myMap"), mapOptions);

        marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        draggable: true
        });

        geocoder.geocode({'latLng': myLatlng }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
        $('#latitude,#longitude').show();
        $('#address').val(results[0].formatted_address);
        $('#latitude').val(marker.getPosition().lat());
        $('#longitude').val(marker.getPosition().lng());
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
        }
        }
        });

        google.maps.event.addListener(marker, 'dragend', function() {

        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
        $('#address').val(results[0].formatted_address);
        $('#latitude').val(marker.getPosition().lat());
        $('#longitude').val(marker.getPosition().lng());
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
        }
        }
        });
        });

        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script type="text/javascript">
        function makeQR(length) {
           var result           = '';
           var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
           var charactersLength = characters.length;
           for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * charactersLength));
           }
           document.getElementById("qr").value = result;
        }

        function showQR(){
            if (document.getElementById("qr").value.length == 20)
                window.open('https://api.qrserver.com/v1/create-qr-code/?size=500x500&data='+document.getElementById("qr").value, '_blank');
        }
    </script>
  @endsection
