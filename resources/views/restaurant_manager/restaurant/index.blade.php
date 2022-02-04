@extends('restaurant_manager.restaurant.base')
@section('action-content')

<div class="card mb-6"> 

  <div class="card-header">
    <i class="fa fa-tint bigfonts" aria-hidden="true"></i> All Restaurant ({{$count}})                
  </div>   

  <div class="card-body">
    <div class="table-responsive">
      <table id="example" class="table table-bordered table-hover display" style="width:100%">
        <thead>
          <tr>
            <th>Name</th>
            <th>City</th>
            <th>Address</th>
            <th>Phone</th>
            <th>Opening Hours</th>
            <td>Action</td>
          </tr>
        </thead>

        <tbody>
          @foreach ($restaurantes as $restaurant)
          <tr role="row" class="odd">
            <td>{{ $restaurant->name }}</td>
            <td>{{ $restaurant->city }}</td>
            <td>{{ $restaurant->address }}</td>
            <td>{{ $restaurant->phone }}</td>
            <td>{{ $restaurant->opening_hours }}</td>
            <td>
              <a href="{{ route('my_restaurant.edit', $restaurant->id) }}" class="btn btn-primary btn-sm" ><i class="fa fa-pencil" aria-hidden="true" ></i></a>
              <a href="{{ route('my_restaurant.show', $restaurant->id) }}" class="btn btn-primary btn-sm" ><i class="fa fa-eye bigfonts" aria-hidden="true"></i></a>
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



 