@extends('admin.campaign.base')
 
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
                <div class="form-group">
                    <label for="s_name">Campaign Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Campaign Name" class="form-control" id="name" value="{{$campaign->name}}" readonly>
                </div>
                <div class="form-group">
                    <label>Start Date </label>
                    <input type="text" name="start_date" data-parsley-trigger="change" placeholder="Enter Start Date" class="form-control" id="start_date" value="{{$campaign->start_date}}" readonly>
                </div>
                <div class="form-group">
                    <label >End Date</label>
                    <input type="text" name="end_date" data-parsley-trigger="change" placeholder="Enter End Date" class="form-control" id="end_date" value="{{$campaign->end_date}}" readonly>
                </div>
                <div class="row form-group">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h3><i class="fa fa-clone"></i> Restaurants</h3>
                            </div>
                            <div class="card-body">
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
                                        <div class="form-group card-body" id="dynamic_restaurant">
                                            <div class="row" >
                                                <div class="col-md-12">
                                                    @foreach ($restaurants as $restaurant)
                                                        <input type="text" class="form-control" readonly value="{{$restaurant->name}}">
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-4 control-label">Status</label>
                        <input type="text" name="status_id" data-parsley-trigger="change" required  class="form-control" id="status_id" value="{{$campaign->status_name}}" readonly>
                    </div>
                </div>
                <div class="form-group">
                        <label class="col-md-4 control-label">Details</label>
                        <textarea class="form-control"  id="details" name="details" readonly>{{$campaign->details}}</textarea>
                </div>

                
                <div class="form-group text-right m-b-0">

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
  @endsection
