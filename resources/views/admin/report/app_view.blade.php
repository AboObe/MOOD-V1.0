@extends('admin.report.base')
@section('action-content')

<div class="card mb-6"> 

  <div class="card-header">
                 
  </div>   

  <div class="card-body">
    <div class="table-responsive">
      <table id="example" class="table table-bordered table-hover display" style="width:100%">
        <thead>
          <tr>
            <th>Year</th>
            <th>Month</th>
            <td>count</td>
          </tr>
        </thead>

        <tbody>
          @foreach ($views as $view)
          <tr role="row" class="odd">
            <td>{{ $view->year }}</td>
            <td>{{ $view->month }}</td>
            <td>{{ $view->count_v }}</td>
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
 <script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> 

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



 