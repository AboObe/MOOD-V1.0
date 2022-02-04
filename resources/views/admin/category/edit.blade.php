@extends('admin.category.base')

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
            <form method="POST"  action="{{ route('web_category.update', $category->id) }}" data-parsley-validate novalidate enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label for="s_name">Category Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" data-parsley-trigger="change" required placeholder="Enter Category Name" class="form-control" id="name" 
                    value="{{$category->name}}" >
                </div>

                <div class="form-group">
                    <label for="s_name">Category Status<span class="text-danger">*</span></label>
                    <select class="form-control select2" name="status_id" id="status_id">
                            @foreach ($statuses as $status)
                                <option value="{{$status->id}}"  {{$status->id == $category->status_id ? 'selected' : ''}}>{{$status->name}}</option>
                            @endforeach
                        </select>
                </div>

                <div class="form-group">
                        <label for="avatar" class="col-md-4 control-label" >Image</label>
                        <div class="col-md-6">
                            @if(!empty($category->image))
                                <img src="../../{{$category->image}}" width="150px" height="150px"/>
                                @endif
                            <input type="file" id="image" name="image"  >
                        </div>
                    </div>



                <div class="form-group text-right m-b-0">
                    <button class="btn btn-primary" type="submit">
                        Submit
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
      $('#form').parsley();
  </script>
  @endsection
