@extends('layouts.admin_mood_app')
@section('content')
  <div class="content-page">
        <!-- Start content -->
        <div class="content" style="width :100%">
            <div class="container-fluid">
                    
                        <div class="row col-xl-12">
                                    <div class="col-xl-12">
                                            <div class="breadcrumb-holder">
                                                    <h1 class="main-title float-left">MOOD</h1>
                                                    <ol class="breadcrumb float-right">
                                                        <li class="breadcrumb-item">Admin</li>
                                                        <li class="breadcrumb-item active">Offer</li>
                                                    </ol>
                                                    <div class="clearfix"></div>
                                            </div>
                                    </div>
                        </div>
                        <!-- end row -->
                        <div class="row">
                                <div class="col-xl-12">									
                                    @yield('action-content')
                                </div>
                        </div>
                  

            </div>
            <!-- END container-fluid -->
        </div>
        <!-- END content -->

</div>
<!-- END content-page -->

    
    

@endsection