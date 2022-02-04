@extends('admin.service.base')
@section('action-content')

<div class="card mb-6" style="background-color:white"> 

  <div class="card-header">
    <i class="fa fa-tint bigfonts" aria-hidden="true"></i> All service ({{$count}})  
    <span class="pull-right">
      <a  class="btn btn-outline-info btn-sm" href="{{ route('web_service.index') }}"><i class="fa fa-refresh bigfonts" aria-hidden="true"></i> </a>
    <a class="btn btn-primary btn-sm" href="{{ route('web_service.create') }}" ><i class="fa fa-plus bigfonts" aria-hidden="true"></i> Add New service</a>
    </span>              
  </div>   

  <div class="card-body">
    <div class="table-responsive">
      <table id="example" class="table table-bordered table-hover display" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>Restaurant</th>
            <th>Category</th>
            <th>Status</th>
            <th>Price</th>
            <td>Action</td>
          </tr>
        </thead>

        <tbody>
          @foreach ($services as $service)
          <tr role="row" class="odd">
            <td>{{ $service->name }}</td>
            <td>{{ $service->restaurant_name }}</td>
            <td>{{ $service->category_name }}</td>
            <td>{{ $service->status_name }}</td>
            <td>{{ $service->price }}</td>
            <td>
             <form  method="POST" action="{{ route('web_service.destroy', $service->id) }}" onsubmit = "return confirm('Are you sure?')">
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
              <a href="{{ route('web_service.edit', $service->id) }}" class="btn btn-primary btn-sm" ><i class="fa fa-pencil" aria-hidden="true" ></i></a>
              <a href="{{ route('web_service.show', $service->id) }}" class="btn btn-primary btn-sm" ><i class="fa fa-eye bigfonts" aria-hidden="true"></i></a>
           
              <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i>
              </button>
            </form>
          </td>
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



 