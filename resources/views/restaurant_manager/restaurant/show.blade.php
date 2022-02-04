@extends('restaurant_manager.restaurant.base')

@section('action-content')
<style type="text/css">
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

/**


***     GALLERY


***

*/
            * {
              box-sizing: border-box;
            }

            /* Position the image container-gallery (needed to position the left and right arrow-gallerys) */
            .container-gallery {
              position: relative;
            }

            /* Hide the images by default */
            .mySlides-gallery {
              display: none;
            }

            /* Add a pointer when hovering over the thumbnail images */
            .cursor-gallery {
              cursor: pointer;
            }

            /* Next & prev-galleryious buttons */
            .prev-gallery,
            .next-gallery {
              cursor: pointer;
              position: absolute;
              top: 40%;
              width: auto;
              padding: 16px;
              margin-top: -50px;
              color: white;
              font-weight: bold;
              font-size: 20px;
              border-radius: 0 3px 3px 0;
              user-select: none;
              -webkit-user-select: none;
            }

            /* Position the "next-gallery button" to the right */
            .next-gallery {
              right: 0;
              border-radius: 3px 0 0 3px;
            }

            /* On hover, add a black background color with a little bit see-through */
            .prev-gallery:hover,
            .next-gallery:hover {
              background-color: rgba(0, 0, 0, 0.8);
            }

            /* Number text (1/3 etc) */
            .numbertext-gallery {
              color: #f2f2f2;
              font-size: 12px;
              padding: 8px 12px;
              position: absolute;
              top: 0;
            }

            /* container-gallery for image text */
            .caption-container-gallery {
              text-align: center;
              background-color: #222;
              padding: 2px 16px;
              color: white;
            }

            .row:after {
              content: "";
              display: table;
              clear: both;
            }

            /* Six columns side by side */
            .column {
              float: left;
              width: 16.66%;
            }

            /* Add a transparency effect for thumnbail images */
            .demo {
              opacity: 0.6;
            }

            .active,
            .demo:hover {
              opacity: 1;
            }


}
</style>
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
                        <label for="s_name">Restaurant Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Restaurant Name" class="form-control" id="name" readonly value="{{$restaurant->name}}">
                    </div>
                    <div class="col-md-6">
                        <label for="s_name">Manager</label>
                        <input type="text" class="form-control" readonly value="{{$restaurant->user_name}}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                            <label class="col-md-4 control-label">City</label>
                            <input type="text" name="city" data-parsley-trigger="change" placeholder="Enter City" class="form-control" id="city" readonly value="{{$restaurant->city}}">
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-6">
                            <label >Status</label>
                            <input type="text" class="form-control" readonly value="{{$restaurant->status_name}}">
                        </div>
                        <div class="col-md-6">
                            <label >Is Featured</label>
                            <input type="text" class="form-control" readonly value="{{$restaurant->is_featured  == 0 ? 'NO': 'YES' }}">
                        </div>
                      </div>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Email </label>
                        <input type="email" name="email" data-parsley-trigger="change"  placeholder="Enter Email" class="form-control" id="email"  readonly value="{{$restaurant->email}}">
                    </div>
                    <div class="col-md-6">
                        <label >phone </label>
                        <input type="number" name="phone" data-parsley-trigger="change"  placeholder="Enter Phone" class="form-control" id="phone" readonly value="{{$restaurant->phone}}">
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label >QR </label>
                        <input type="text" name="qr" data-parsley-trigger="change"  placeholder="Enter QR" class="form-control" id="qr" readonly value="{{$restaurant->qr}}">
                    </div>
                    <div class="col-md-6">
                        <label >PIN </label>
                        <input type="string" name="pin" data-parsley-trigger="change"  placeholder="Enter PIN" class="form-control" id="pin" readonly value="{{$restaurant->pin}}">
                    </div>
                </div>


                <div class="row form-group">
                    <div class="col-md-6">
                        <label >Profile Image </label>
                        <div class="col-md-12">
                            <a data-fancybox="gallery"  class="col-sm-2">
                                
                                <!-- Trigger the Modal -->
                                <img id="myImg" alt="image" 
                                src="../{{$restaurant->image}}" class="img-fluid"  height="42" width="42" alt="Profile Restaurant" >

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
                    </div>
                    <div class="col-md-6">
                        <label >Openning Hours </label>
                        <input type="text" name="opening_hours" data-parsley-trigger="change"  placeholder="Enter Openning Hours" class="form-control" id="opening_hours" readonly value="{{$restaurant->opening_hours}}">
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
                                                <div class="col-md-12">
                                                    @foreach ($tags as $tag)
                                                        <input type="text" class="form-control" readonly value="{{$tag->name}}">
                                                    @endforeach
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
                                                <div class="col-md-12">
                                                    @foreach ($categories as $category)
                                                        <input type="text" class="form-control" readonly value="{{$category->name}}">
                                                    @endforeach
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
                            <!-- end categories -->
                            <!-- Campaigns -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".campaign">Campaigns</a>

                                <div class="modal fade bd-example-modal-lg campaign" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title">Campaigns</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        </div>
                                        <!--body-->
                                        <div class="form-group card-body" id="dynamic_campaign">
                                            <div class="row" >
                                                <div class="col-md-12">
                                                    @foreach ($campaigns as $campaign)
                                                        <input type="text" class="form-control" readonly value="{{$campaign->name}}">
                                                    @endforeach
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
                            <!-- end campaigns -->
                            <!-- Media -->
                                <a role="button" href="#" class="btn btn-warning" data-toggle="modal" data-target=".media">Media</a>

                                <div class="modal fade bd-example-modal-lg media" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="card mb-3">
                                            <div class="card-header">
                                                <h3><i class="fa fa-file"></i> Images</h3>
                                            </div>  
                                            <div class="card-body">        
                                                
                                                <div class="container-gallery">        
                                                  @if($photos != null) 
                                                    @foreach($photos as $photo)
                                                        <!-- Full-width images with number text -->
                                                        <div class="mySlides-gallery">
                                                            <img src="../{{$photo}}" style="width:100%">
                                                        </div>
                                                    @endforeach
                                                    <a class="prev-gallery" onclick="plusSlides(-1)">&#10094;</a>
                                                    <a class="next-gallery" onclick="plusSlides(1)">&#10095;</a>
                                                    <!-- Image text -->
                                                    <div class="caption-container-gallery">
                                                        <p id="caption"></p>
                                                    </div>
                                                    <div class="row">
                                                      @php ($i = 1)
                                                      @foreach($photos as $photo)

                                                          <div class="column">
                                                              <img class="demo cursor-gallery" src="../{{$photo}}" style="width:42px;height: 42px" onclick="currentSlide($i)" alt="The Woods">
                                                          </div>
                                                          @php ($i = $i + 1)
                                                      @endforeach
                                                    </div>
                                                  @endif
                                                </div> 
                                                            
                                                

                                            </div>
                                        </div>                                                   

                                        <div class="modal-footer">
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
                                                    <input type="text" name="address" data-parsley-trigger="change" required placeholder="Enter Address" class="form-control" id="address" readonly value="{{$restaurant->address}}">
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
                                                    <input type="string" name="facebook" data-parsley-trigger="change"  placeholder="Enter Facebook" class="form-control" id="facebook" value="{{$restaurant->facebook}}"  readonly>
                                                    <label >Instagram </label>
                                                    <input type="string" name="instagram" data-parsley-trigger="change"  placeholder="Enter Instagram" class="form-control" id="instagram" value="{{$restaurant->instagram}}" readonly>
                                                    <label >Snapchat </label>
                                                    <input type="string" name="snapchat" data-parsley-trigger="change"  placeholder="Enter Snapchat" class="form-control" id="snapchat" value="{{$restaurant->snapchat}}" readonly>
                                                    <label >Whatsapp </label>
                                                    <input type="string" name="whatsapp" data-parsley-trigger="change"  placeholder="Enter Whatsapp" class="form-control" id="whatsapp" value="{{$restaurant->whatsapp}}" readonly>
                                                    <label >Youtube </label>
                                                    <input type="string" name="youtube" data-parsley-trigger="change"  placeholder="Enter Youtube" class="form-control" id="youtube" value="{{$restaurant->youtube}}" readonly>
                                                    <label >Website </label>
                                                    <input type="string" name="website" data-parsley-trigger="change"  placeholder="Enter Website" class="form-control" id="website" value="{{$restaurant->website}}" readonly>
                                                    
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
                        <textarea class="form-control"  id="description" name="description" rows="3" readonly >{{$restaurant->description}}</textarea> 
                    </div>
                </div> 

                <div class="form-group text-right m-b-0">
                    <a class="btn btn-secondary m-l-5" href ="{{ route('my_restaurant.index')}}">
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
        <!-- Gallary -->
        <script type="text/javascript">
            var slideIndex = 1;
            showSlides(slideIndex);

            // next-gallery/prev-galleryious controls
            function plusSlides(n) {
              showSlides(slideIndex += n);
            }

            // Thumbnail image controls
            function currentSlide(n) {
              showSlides(slideIndex = n);
            }

            function showSlides(n) {
              var i;
              var slides = document.getElementsByClassName("mySlides-gallery");
              var dots = document.getElementsByClassName("demo");
              var captionText = document.getElementById("caption");
              if (n > slides.length) {slideIndex = 1}
              if (n < 1) {slideIndex = slides.length}
              for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
              }
              for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
              }
              slides[slideIndex-1].style.display = "block";
              dots[slideIndex-1].className += " active";
              captionText.innerHTML = dots[slideIndex-1].alt;
            }
        </script>
  @endsection