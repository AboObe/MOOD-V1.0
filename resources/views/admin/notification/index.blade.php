@extends('admin.notification.base')
@section('action-content')

<div class="card mb-6"> 

  <div class="card-header">
    <i class="fa fa-tint bigfonts" aria-hidden="true"></i> All Notifications ({{$count}})  
    <span class="pull-right">
      <a  class="btn btn-outline-info btn-sm" href="{{ route('web_notification.index') }}"><i class="fa fa-refresh bigfonts" aria-hidden="true"></i> </a>
    <a class="btn btn-primary btn-sm" href="{{ route('web_notification.create') }}" ><i class="fa fa-plus bigfonts" aria-hidden="true"></i> Send New Notification</a>
    </span>              
  </div>   

  <div class="card-body">
    <div class="table-responsive">
      <table id="example" class="table table-bordered table-hover display" style="width:100%">
        <thead>
          <tr>
            <th>Title</th>
            <th>Restaurant Name</th>
            <th>Body</th>
            
          </tr>
        </thead>

        <tbody>
          @foreach ($notifications as $notification)
          <tr role="row" class="odd">
            <td>{{ $notification->title }}</td>
            <td>{{ $notification->restaurant_name }}</td>
            <td>{{ $notification->body }}</td>
            
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
</div>




<!-- BEGIN Java Script for this page -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
 <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script> 
 <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" defer></script> 

<script>
  // START CODE FOR BASIC DATA TABLE 
  $(document).ready(function() {
    $('#example').DataTable();
  } );
  // END CODE FOR BASIC DATA TABLE 
  $(document).ready(function() {
    $('#example thead th').each( function () {
      var title = $('#example thead th').eq( $(this).index() ).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
    } );

    // DataTable
    var table = $('#example').DataTable();

    // Apply the search
    table.columns().eq( 0 ).each( function ( colIdx ) {
      $( 'input', table.column( colIdx ).header() ).on( 'keyup change', function () {
        table
        .column( colIdx )
        .search( this.value )
        .draw();
      } );
    } );
  } );


  function delete_record(id)
  {
    if (confirm('Confirm delete')) {

    }
  }
</script>
@endsection



 