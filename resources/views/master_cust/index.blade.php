@extends('layouts.main')
@section('styles')
<link rel="stylesheet" href="{{url('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
<link rel="stylesheet" href="{{url('http://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css') }}">
@endsection

<style>  

    th { font-size: 13px; }
    td { font-size: 13px; }
</style>

@section('content')
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
		        <h1 class="m-0">Master Customer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Master Customer</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Status -->
    @if (session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
    @endif

    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-fixed table-striped table-border table-hover nowrap datatable" id="datatable">
                    <thead class="table-dark">
                        <tr>										
                            <th scope="col" style="text-align: center">No</th>
				     		<th scope="col" style="text-align: center">-</th>							
                            <th scope="col" style="text-align: center">Kode</th>
                            <th scope="col" style="text-align: center">Nama</th>
                            <th scope="col" style="text-align: center">Alamat</th>
                            <th scope="col" style="text-align: center">Kota</th>
							<th scope="col" style="text-align: center">Telpon</th>
							<th scope="col" style="text-align: center">HP</th>
							<th scope="col" style="text-align: center">NPWP</th>
							<th scope="col" style="text-align: center">Ket</th>

                        </tr>
                    </thead>
    
                     <tbody>
                         
                    </tbody> 
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('javascripts')
<script src="{{url('AdminLTE/plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{url('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{url('http://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js') }}"></script>

<script>
  $(document).ready(function() {
        var dataTable = $('.datatable').DataTable({
            processing: true,
            serverSide: true,
            'scrollY': '400px',	
            "order": [[ 0, "asc" ]],
            ajax: 
            {
				
 <!--// ganti 7a -->
 
                url: '{{ route('get-cust') }}'
            },
            columns: 
            [
                {  data: 'DT_RowIndex', orderable: false, searchable: false },

 <!--// ganti 8 -->
			    {
				data: 'action',
				name: 'action'
			    },
				
				{data: 'KODEC', name: 'KODEC'},
                {data: 'NAMAC', name: 'NAMAC'},
                {data: 'ALAMAT', name: 'ALAMAT'},				
                {data: 'KOTA', name: 'KOTA'},
				{data: 'TELPON1', name: 'TELPON1'},
				{data: 'HP', name: 'HP'},
				{data: 'NPWP', name: 'NPWP'},
				{data: 'KET', name: 'KET'}

				
            ],

            columnDefs: [
                {
                    "className": "dt-center", 
                    "targets": 0
                }
            ],
			
			dom: "<'row'<'col-md-6'><'col-md-6'>>" +
                "<'row'<'col-md-2'l><'col-md-6 test_btn m-auto'><'col-md-4'f>>" +
                "<'row'<'col-md-12't>><'row'<'col-md-12'ip>>",
		
			stateSave:true,
        
        });
        
        $("div.test_btn").html('<a class="btn btn-lg btn-md btn-success" href="{{url('cust/edit?idx=0&tipx=new')}}"> <i class="fas fa-plus fa-sm md-3" ></i></a');
    });
	
</script>
@endsection