@extends('admin.tag.base')

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
                    <label for="s_name">Tag Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter tag Name" class="form-control" id="name" value="{{ $tag->name }}" readonly>
                </div>
                

                <div class="form-group text-right m-b-0">

                    <a class="btn btn-secondary m-l-5" href ="{{route('web_tag.index')}}">
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
