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
           height: 300px;
           width: 680px;
        }
    /* Style the Image Used to Trigger the Modal */
            #myImg {
              border-radius: 5px;
              cursor: pointer;
              transition: 0.3s;
            }

            #myImg:hover {opacity: 0.7;}

            /* The Modal (background) */
            .modal-image {
              display: none; /* Hidden by default */
              position: fixed; /* Stay in place */
              z-index: 1; /* Sit on top */
              padding-top: 100px; /* Location of the box */
              left: 0;
              top: 0;
              width: 100%; /* Full width */
              height: 100%; /* Full height */
              overflow: auto; /* Enable scroll if needed */
              background-color: rgb(0,0,0); /* Fallback color */
              background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
            }

            /* Modal Content (Image) */
            .modal-content-image {
              margin: auto;
              display: block;
              width: 80%;
              max-width: 700px;
            }

            /* Caption of Modal Image (Image Text) - Same Width as the Image */
            #caption {
              margin: auto;
              display: block;
              width: 80%;
              max-width: 700px;
              text-align: center;
              color: #ccc;
              padding: 10px 0;
              height: 150px;
            }

            /* Add Animation - Zoom in the Modal */
            .modal-content-image, #caption {
              animation-name: zoom;
              animation-duration: 0.6s;
            }

            @keyframes zoom {
              from {transform:scale(0)}
              to {transform:scale(1)}
            }

            /* The Close Button */
            .close-image {
              position: absolute;
              top: 50px;
              right: 35px;
              color: #f1f1f1;
              font-size: 40px;
              font-weight: bold;
              transition: 0.3s;
            }

            .close-image:hover,
            .close-image:focus {
              color: #bbb;
              text-decoration: none;
              cursor: pointer;
            }

            /* 100% Image Width on Smaller Screens */
            @media only screen and (max-width: 700px){
              .modal-content-image {
                width: 100%;
              }

    </style>
    <!-- END CSS for this page -->
    <div class="card mb-3">
        <div class="card-body">
            <!--Header-->
            <div class="alert alert-success" role="alert">
            </div>
            <!-- Body -->
            <form method="POST"  action="{{ route('web_restaurant.update', $restaurant->id) }}" data-parsley-validate novalidate enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                {{ csrf_field() }}


                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="s_name">Restaurant Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Restaurant Name" class="form-control" id="name"  value="{{$restaurant->name}}">
                        @if($errors->has('name'))
                            <span class="text-danger"><small>{{$errors->first('name')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label for="s_name">Manager</label>
                        <select class="form-control select2" name="manager_id" id="manager_id">
                            @foreach ($users as $user)
                                <option value="{{$user->id}}"  {{$user->id == $restaurant->manager_id ? 'selected' : ''}}>{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">City</label>
                            <input type="text" name="city" data-parsley-trigger="change" placeholder="Enter City" class="form-control" id="city"  value="{{$restaurant->city}}" required>
                            @if($errors->has('city'))
                                <span class="text-danger"><small>{{$errors->first('city')}}</small>
                            @endif
                    </div>
                    <div class="col-md-6">
                      <div class=row>
                        <div class="col-md-6">
                              <label >Status</label>
                              <select class="form-control" name="status_id" id="status_id">
                                  @foreach ($statuses as $status)
                                      <option value="{{$status->id}}"  {{$status->id == $restaurant->status_id ? 'selected' : ''}}>{{$status->name}}</option>
                                  @endforeach
                              </select>
                        </div>
                        <div class="col-md-6">
                              <label >Is Featured</label>
                              <select class="form-control" name="is_featured" id="is_featured">
                                  <option value="0" {{$restaurant->is_featured  == 0 ? 'selected' : '' }}>NO</option>
                                  <option value="1" {{$restaurant->is_featured  == 1 ? 'selected' : '' }}>YES</option>
                              </select>
                        </div>
                      </div>
                    </div>


                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Email </label>
                        <input type="email" name="email" data-parsley-trigger="change"  placeholder="Enter Email" class="form-control" id="email"   value="{{$restaurant->email}}">
                        @if($errors->has('email'))
                            <span class="text-danger"><small>{{$errors->first('email')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label >phone </label>
                        <input type="number" name="phone" data-parsley-trigger="change"  placeholder="Enter Phone" class="form-control" id="phone"  value="{{$restaurant->phone}}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >QR </label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" name="qr" data-parsley-trigger="change"  placeholder="Generate QR" class="form-control" id="qr" readonly value="{{$restaurant->qr}}">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="makeQR(20)">Generate</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="showQR()">Show QR</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label >PIN </label>
                        <input type="string" name="pin" data-parsley-trigger="change"  placeholder="Enter PIN" class="form-control" id="pin" value="{{$restaurant->pin}}">
                    </div>
                </div>


                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Profile Image </label>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <a data-fancybox="gallery"  class="col-sm-2">

                                    <!-- Trigger the Modal -->
                                    <img id="myImg" alt="image"
                                    src="../../{{$restaurant->image}}" class="img-fluid"  height="42" width="42" alt="Profile Restaurant" >

                                    <!-- The Modal -->
                                    <div id="myModal" class="modal-image">

                                      <!-- The Close Button -->
                                      <span class="close-image">&times;</span>

                                      <!-- Modal Content (The Image) -->
                                      <img class="modal-content-image" id="img01">

                                      <!-- Modal Caption (Image Text) -->
                                      <div id="caption"></div>
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal-image">Close</button>
                                    </div>
                                </a>
                            </div>
                            <div  class="form-group col-md-9">
                                <input type="file" class="custom-file-input" aria-describedby="inputGroupFileAddon01" id="image" name="image">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                            </div>
                        </div>
                        @if($errors->has('image'))
                            <span class="text-danger"><small>{{$errors->first('image')}}</small>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label >Openning Hours </label>
                        <input type="text" name="opening_hours" data-parsley-trigger="change"  placeholder="Enter Openning Hours" class="form-control" id="opening_hours"  value="{{$restaurant->opening_hours}}">
                    </div>
                </div>

                 <div class="row">
                    <!-- -->
                    <!-- -->
                    <!-- -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-clone"></i> Tags & Compaigns & Offers & Media & Location</h3>
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
                                        <div class="modal-body" id="dynamic_tag">
                                            @foreach($restaurant_tags as $tag)
                                                <input type="hidden" id="old_tags[]" name="old_tags[]" value="{{$tag->id}}">
                                                <div  class="row control-group-tag" style="padding-left:10px">
                                                    <div class="col-md-10">
                                                        <div  class="row">
                                                            <input type="hidden"  name="new_tags[]" id="new_tags[]" value="{{$tag->id}}">
                                                            <input type="text" data-parsley-trigger="change"  class="form-control" value="{{$tag->name}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-0">
                                                        <button type="button"  class="btn btn-danger btn_remove_tag">X</button>

                                                    </div>
                                                </div>
                                            @endforeach
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
                                                    <button type="button" name="add_tag" id="add_tag" class="btn btn-success">Add More
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
                            <!-- END Tag -->
                            <!-- Category -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".category">Category</a>

                                <div class="modal fade bd-example-modal-lg category" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="modal-body" id="dynamic_category">
                                            @foreach($restaurant_categories as $category)
                                                <input type="hidden" id="old_categories[]" name="old_categories[]" value="{{$category->id}}">
                                                <div  class="row control-group-category" style="padding-left:10px">
                                                    <div class="col-md-10">
                                                        <div  class="row">
                                                            <input type="hidden"  name="new_categories[]" id="new_categories[]" value="{{$category->id}}">
                                                            <input type="text" data-parsley-trigger="change"  class="form-control" value="{{$category->name}}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-0">
                                                        <button type="button"  class="btn btn-danger btn_remove_category">X</button>

                                                    </div>
                                                </div>
                                            @endforeach
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
                                                    <button type="button" name="add_category" id="add_category" class="btn btn-success">Add More
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
                            <!-- END Category -->
                            <!-- -->

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

                                            <div class="modal-body">
                                                @if($photos != null)
                                                @foreach($photos as $photo)
                                                    <input type="hidden"  name="old_images[]" value="{{$photo}}">
                                                    <div  class="row control-group-image">
                                                        <div class="col-md-10">
                                                            <div  class="row">
                                                                <input type="hidden"  name="new_images[]" value="{{$photo}}">
                                                                <img alt="image" src="../../{{$photo}}" class="img-fluid">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-0">
                                                            <button type="button"  class="btn btn-danger btn_remove_image" onclick="deleteImage('{{$restaurant->id}}','{{$photo}}')">X</button>

                                                        </div>
                                                    </div>
                                                @endforeach
                                                @endif
                                                <input type="file" name="files[]" id="filer_example1" multiple="multiple">
                                            </div>


                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                      </div>
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
                                                    <input type="text" name="address" data-parsley-trigger="change" required placeholder="Enter Address" class="form-control" id="address" value="{{$restaurant->address}}">
                                                </div>
                                            </div>
                                            <input type="text"  name="latitude" id="latitude"  placeholder="Latitude" value="{{$restaurant->latitude}}"/>
                                            <input type="text"  name="longitude" id="longitude" placeholder="Longitude" value="{{$restaurant->longitude}}"/>
                                        </div>
                                        <div class="modal-footer">
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
                                                    <input type="string" name="facebook" data-parsley-trigger="change"  placeholder="Enter Facebook" class="form-control" id="facebook" value="{{$restaurant->facebook}}">
                                                    <label >Instagram </label>
                                                    <input type="string" name="instagram" data-parsley-trigger="change"  placeholder="Enter Instagram" class="form-control" id="instagram" value="{{$restaurant->instagram}}">
                                                    <label >Snapchat </label>
                                                    <input type="string" name="snapchat" data-parsley-trigger="change"  placeholder="Enter Snapchat" class="form-control" id="snapchat" value="{{$restaurant->snapchat}}">
                                                    <label >Whatsapp <small>(begin with '+971' without space)</small></label>
                                                    <input type="string" name="whatsapp" data-parsley-trigger="change"  placeholder="Enter Whatsapp" class="form-control" id="whatsapp" value="{{$restaurant->whatsapp}}" pattern="[+][9][7][1][0-9]*">
                                                    <label >Youtube </label>
                                                    <input type="string" name="youtube" data-parsley-trigger="change"  placeholder="Enter Youtube" class="form-control" id="youtube" value="{{$restaurant->youtube}}">
                                                    <label >Website </label>
                                                    <input type="string" name="website" data-parsley-trigger="change"  placeholder="Enter Website" class="form-control" id="website" value="{{$restaurant->website}}">

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
                            <!-- -->
                            </div>
                        </div><!-- end card-->
                    </div>
                    <!-- -->
                    <!-- -->
                    <!-- -->
                    <div class="form-group col-md-6">
                        <label >Description </label>
                        <textarea class="form-control"  id="description" name="description" rows="3">{{$restaurant->description}}</textarea>


                    </div>
                </div>



                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Save
                    </button>
                    <a class="btn btn-secondary m-l-5" href ="{{route('web_restaurant.index')}}">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
        <!-- end card-->
    </div>

    <!-- BEGIN Java Script for this page -->
   <script src='{{asset("assets/plugins/parsleyjs/parsley.min.js")}}'></script>
   <script type="text/javascript">
       $(document).ready(function(){
            var i=1;
            //tags
            $('#add_tag').click(function(){
                i++;
                $('#dynamic_tag').append('\
                    <div id="'+i+'_remove_tag" class="row" >\
                        <div class="col-md-10">\
                            <select class="form-control" name="new_tags[]" id="new_tags['+i+']">\
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


            $("body").on("click",".btn_remove_tag",function(){
                $(this).parents(".control-group-tag").remove();
            });
            //Category
            $('#add_category').click(function(){
                i++;
                $('#dynamic_category').append('\
                    <div id="'+i+'_remove_category" class="row" >\
                        <div class="col-md-10">\
                            <select class="form-control" name="new_categories[]" id="new_categories['+i+']">\
                                @foreach ($categories as $category)\
                                    <option value="{{$category->id}}">{{$category->name}}</option>\
                                @endforeach\
                            </select>\
                        </div>\
                        <div class="col-md-0" >\
                            <button name="'+i+'_remove_category" id="'+i+'_remove_category" class="btn btn-danger btn_remove_category">X</button>\
                        </div>\
                    </div>');
            });

            $(document).on('click', '.btn_remove_category', function(){
                var button_id = $(this).attr("id");
                $('#'+button_id+'').remove();
            });


            $("body").on("click",".btn_remove_category",function(){
                $(this).parents(".control-group-category").remove();
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

            $("body").on("click",".btn_remove_image",function(){
                $(this).parents(".control-group-image").remove();
                $("#1").remove();
            });
        });

       function deleteImage(restaurant_id , photo){
                console.log(restaurant_id);
                console.log(photo);
            $.ajax({
                type :"GET",
                url:"{{route('restaurant.delete_photo')}}",
                data:{
                  restaurant_id : restaurant_id,
                  photo : photo
                },
                success:function(res){
                    console.log(res);
                }
            });
        }
   </script>
<!-- PROFILE IMAGE -->
    <script type="text/javascript">
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var img = document.getElementById("myImg");
            var modalImg = document.getElementById("img01");
            var captionText = document.getElementById("caption");
            img.onclick = function(){
              modal.style.display = "block";
              modalImg.src = this.src;
              captionText.innerHTML = this.alt;
            }

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close-image")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
              modal.style.display = "none";
            }
    </script>
<!-- LOCATION -->
    <script type="text/javascript">
    $(document).ready(function(){
            var map;
            var marker;
            var myLatlng = new google.maps.LatLng(document.getElementById('latitude').value,document.getElementById('longitude').value);
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
        });

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
