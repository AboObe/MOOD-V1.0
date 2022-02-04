@extends('admin.report.base')
@section('action-content')

<div class="card mb-6"> 

  <div class="card-header row">
    <div class="col-md-8">
      
    </div>
    <input class="col-md-2" type="month" name="month" id="month">
    <button class="col-md-2 btn btn-primary btn-sm"  onclick="search()"> Search</button>
    </span>              
  </div>   

  <div class="card-body">
    <div class="table-responsive">
      <table id="example" class="table table-bordered table-hover display" style="width:100%">
        <thead>
          <tr>
            <th>Restaurants Name</th>
            <td>Count</td>
          </tr>
        </thead>

        <tbody id="body" name="body">
          @foreach ($views as $view)
          <tr role="row" class="odd">
            <td>{{ $view->name }}</td>
            <td>{{ $view->count_r }}</td>
            
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


  function search(){
    date = document.getElementById('month').value;
    year = date.substring(0, 4);
    month = date.substring(5,7);
    console.log(year);
    console.log(month);
    $.ajax({
        type :"GET",
        url:"{{route('RESTAURANT.VIEWS.WITH_SEARCH')}}",
        data:{
          year : year,
          month : month
        },
        success:function(res){
          $("#body tr").remove(); 
          result_search =  JSON.parse(res);
          console.log(result_search);
          result_search.forEach(function(item){
            $("#body").append("<tr><td>"+item.name+"</td><td>"+item.count_r+"</td></tr>");
          });
        }
    });
  }
</script>
@endsection



 