@extends('layouts.main')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Kartu Purchase Order</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Kartu Purchase Order</li>
            </ol>
        </div>
        </div>
    </div>
    </div>
    
    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{url('jasper-po-kartu')}}">
                    @csrf
						<div class="form-group row">
							<div class="col-md-1">
								<label><strong>Gol :</strong></label>
								
								<select name="gol" id="gol" class="form-control gol">
									<option value="K" {{ session()->get('filter_gol')=='K' ? 'selected': ''}}>K</option>
									<option value="L" {{ session()->get('filter_gol')=='L' ? 'selected': ''}}>L</option>
								</select>
							</div>
							<div class="col-md-2">						
								<label class="form-label">Suplier</label>
								<input type="text" class="form-control kodes" id="kodes" name="kodes" placeholder="Pilih Suplier" value="{{ session()->get('filter_kodes1') }}" readonly>
							</div>  
							<div class="col-md-3">
								<label class="form-label">Nama</label>
								<input type="text" class="form-control NAMAS" id="NAMAS" name="NAMAS" placeholder="Nama" value="{{ session()->get('filter_namas1') }}" readonly>
							</div>
						</div>

                        <div class="form-group row">
							<div class="col-md-2">						
								<label class="form-label">PEMBELIAN / RETUR : </label>
								<button type="button" class="btn btn-primary" onclick="browseBeli()"><i class="fa fa-search"></i></button>
                            </div> 
                            
                            <div class="col-md-2">						
								<label class="form-label">PEMBAYARAN : </label>
								<button type="button" class="btn btn-primary" onclick="browseHut()"><i class="fa fa-search"></i></button>
                            </div> 
                            
                            <div class="col-md-2">						
								<label class="form-label">LAIN : </label>
								<button type="button" class="btn btn-primary" onclick="browseHut()"><i class="fa fa-search"></i></button>
                            </div> 
                            
                            <div class="col-md-2">						
								<label class="form-label">KARTU : </label>
								<button type="button" class="btn btn-primary" onclick="browseKartu()"><i class="fa fa-search"></i></button>
                            </div> 
						</div>
						
						<!-- Filter Tanggal -->
						<!--
						<div class="form-group row">
							<div class="col-md-3">
								<input class="form-control date tglDr" id="tglDr" name="tglDr"
								type="text" autocomplete="off" value="{{ session()->get('filter_tglDari') }}"> 
							</div>
							<div>s.d.</div> 
							<div class="col-md-3">
								<input class="form-control date tglSmp" id="tglSmp" name="tglSmp"
								type="text" autocomplete="off" value="{{ session()->get('filter_tglSampai') }}">
							</div>
						</div> -->
						
						<button class="btn btn-primary" type="submit" id="filter" class="filter" name="filter">Filter</button>
						<button class="btn btn-danger" type="button" id="resetfilter" class="resetfilter" onclick="window.location='{{url("rkartupo")}}'">Reset</button>
						<button class="btn btn-warning" type="submit" id="cetak" class="cetak" formtarget="_blank">Cetak</button>
                    </form>
                    <div style="margin-bottom: 15px;"></div>
                    <!--
                    <table class="table table-fixed table-striped table-border table-hover nowrap datatable">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" style="text-align: center">#</th>
                                <th scope="col" style="text-align: center">Bukti</th>
                                <th scope="col" style="text-align: center">Tgl</th>
                                <th scope="col" style="text-align: center">Suplier#</th>
                                <th scope="col" style="text-align: center">-</th>
                                <th scope="col" style="text-align: center">Total</th>
                                <th scope="col" style="text-align: center">Bayar</th>
                                <th scope="col" style="text-align: center">Saldo</th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody> 
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table> -->
                    
                    <!-- PASTE DIBAWAH INI -->
                    <!-- DISINI BATAS AWAL KOOLREPORT-->
					 <!-- Include jQuery and Bootstrap CSS/JS -->

                    <div class="report-content" col-md-12>
                        <?php
                        use \koolreport\datagrid\DataTables;

                        if($hasil)
                        {
                            DataTables::create(array(
                                "dataSource" => $hasil,
                                "name" => "example",
                                "fastRender" => true,
                                "fixedHeader" => true,
                                'scrollX' => true,
                                "showFooter" => true,
                                "showFooter" => "bottom",
                                "columns" => array(
                                    "NO_BUKTI" => array(
                                        "label" => "Bukti#",
                                    ),
                                    "TGL" => array(
                                        "label" => "Tanggal",
                                    ),
                                    "NAMAS" => array(
                                        "label" => "Suplier#",
                                    ),
                                    "NA_BRG" => array(
                                        "label" => "Barang",
                                        "footerText" => "<b>Grand Total :</b>",
                                    ),
                                    "KG" => array(
                                        "label" => "Kg",
                                        "type" => "number",
                                        "decimals" => 2,
                                        "decimalPoint" => ".",
                                        "thousandSeparator" => ",",
                                        "footer" => "sum",
                                        "footerText" => "<b>@value</b>",
                                    ),
                                    "KIRIM" => array(
                                        "label" => "Kirim",
                                        "type" => "number",
                                        "decimals" => 2,
                                        "decimalPoint" => ".",
                                        "thousandSeparator" => ",",
                                        "footer" => "sum",
                                        "footerText" => "<b>@value</b>",
                                    ),
                                    "SISA" => array(
                                        "label" => "Sisa",
                                        "type" => "number",
                                        "decimals" => 2,
                                        "decimalPoint" => ".",
                                        "thousandSeparator" => ",",
                                        "footer" => "sum",
                                        "footerText" => "<b>@value</b>",
                                    ),
                                    "HARGA" => array(
                                        "label" => "Harga",
                                        "type" => "number",
                                        "decimals" => 2,
                                        "decimalPoint" => ".",
                                        "thousandSeparator" => ",",
                                        "footerText" => "<b>@value</b>",
                                    ),
                                    "TOTAL" => array(
                                        "label" => "Total",
                                        "type" => "number",
                                        "decimals" => 2,
                                        "decimalPoint" => ".",
                                        "thousandSeparator" => ",",
                                        "footer" => "sum",
                                        "footerText" => "<b>@value</b>",
                                    ),
                                    "NOTES" => array(
                                        "label" => "Notes",
                                        "footerText" => "<b>Grand Total :</b>",
                                    ),
                                ),
                                "cssClass" => array(
                                    "table" => "table table-hover table-striped table-bordered compact",
                                    "th" => "label-title",
                                    "td" => "detail",
                                    "tf" => "footerCss"
                                ),
                                "options" => array(
                                    "columnDefs"=>array(
                                        array(
                                            "className" => "dt-right", 
                                            "targets" => [4,5,6,7,8],
                                        ),

                                        array(
                                            "className" => "dt-center", 
                                            "targets" => [0,1,2,3],
                                        ),
                                    ),
                                    "order" => [],
                                    "paging" => true,
                                    // "pageLength" => 12,
                                    "searching" => true,
                                    "colReorder" => true,
                                    "select" => true,
                                    "dom" => 'Blfrtip', // B e dilangi
                                    // "dom" => '<"row"<col-md-6"B><"col-md-6"f>> <"row"<"col-md-12"t>><"row"<"col-md-12">>',
                                    "buttons" => array(
                                        array(
                                            "extend" => 'collection',
                                            "text" => 'Export',
                                            "buttons" => [
                                                'copy',
                                                'excel',
                                                'csv',
                                                'pdf',
                                                'print'
                                            ],
                                        ),
                                    ),
                                ),
                            ));
                        }
                        ?>
                    </div>
                    <!-- DISINI BATAS AKHIR KOOLREPORT-->

                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
</div>

	<div class="modal fade" id="browseSuplierModal" tabindex="-1" role="dialog" aria-labelledby="browseSuplierModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="browseSuplierModalLabel">Cari Suplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-stripped table-bordered" id="table-bsuplier">
					<thead>
						<tr>
							<th>Suplier</th>
							<th>Nama</th>
							<th>Alamat</th>
							<th>Kota</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
	  	</div>
	</div>

    <div class="modal fade" id="browseBeliModal" tabindex="-1" role="dialog" aria-labelledby="browseBeliModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="browseBeliModalLabel">PEMBELIAN/RETUR</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-stripped table-bordered" id="table-bbeli">
					<thead>
						<tr>
							<th>NO BUKTI</th>
							<th>NO PO</th>
							<th>TGL</th>
							<th>SUPPLIER</th>
							<th>BARANG</th>
							<th>TRUCK</th>
							<th>KG</th>
							<th>HARGA</th>
							<th>TOTAL</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
	  	</div>
	</div>

    <div class="modal fade" id="browseHutModal" tabindex="-1" role="dialog" aria-labelledby="browseHutModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="browseHutModalLabel">PEMBAYARAN</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-stripped table-bordered" id="table-bhut">
					<thead>
						<tr>
							<th>NO BUKTI</th>
							<th>NO PO</th>
							<th>TGL</th>
							<th>KODE</th>
							<th>SUPPLIER</th>
							<th>URAIAN</th>
							<th>BAYAR</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
	  	</div>
	</div>

    <div class="modal fade" id="browseKartuModal" tabindex="-1" role="dialog" aria-labelledby="browseKartuModalLabel" aria-hidden="true">
	  	<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="browseKartuModalLabel">KARTU</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<table class="table table-stripped table-bordered" id="table-bkartu">
					<thead>
						<tr>
							<th>NO BUKTI</th>
							<th>TGL</th>
							<th>SUPPLIER</th>
							<th>KG</th>
							<!-- <th>KIRIM</th> -->
							<!-- <th>SALDO I</th> -->
							<th>TOTAL</th>
							<th>BAYAR</th>
							<!-- <th>SALDO</th> -->
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
			</div>
	  	</div>
	</div>


	@endsection

	@section('javascripts')
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
    $(document).ready(function() {
        $('.date').datepicker({  
            dateFormat: 'dd-mm-yy'
        }); 
        /*
        function fill_datatable( kodes = '', tglDr = '', tglSmp = '')
        {
            var dataTable = $('.datatable').DataTable({
                dom: '<"row"<"col-4"B>>fltip',
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                processing: true,
                serverSide: true,
                autoWidth: true,
                'scrollX': true,
                'scrollY': '400px',
                "order": [[ 0, "asc" ]],
                ajax: 
                {
                    url: "{{ url('get-po-kartu') }}",
                    data: {
                        kodes: kodes,
                        tglDr: tglDr,
                        tglSmp: tglSmp
                    }
                },
                columns: 
                [
                    {data: 'DT_RowIndex', orderable: false, searchable: false },
                    {data: 'NO_BUKTI', name: 'NO_BUKTI'},
                    {data: 'TGL', name: 'TGL'},
                    {data: 'KODES', name: 'KODES'},
                    {data: 'NAMAS', name: 'NAMAS'},
                    {
                     data: 'TOTAL', 
                     name: 'TOTAL',
                     render: $.fn.dataTable.render.number( ',', '.', 2, '' )
                    },	
                    {
                     data: 'BAYAR', 
                     name: 'BAYAR',
                     render: $.fn.dataTable.render.number( ',', '.', 2, '' )
                    },	
                    {
                     data: 'SALDO', 
                     name: 'SALDO',
                     render: $.fn.dataTable.render.number( ',', '.', 2, '' )
                    },	
                 ],
                 
                 columnDefs: [
                  {
                    "className": "dt-center", 
                    "targets": 0
                  },
                  {
                    targets: 2,
                    render: $.fn.dataTable.render.moment( 'DD-MM-YYYY' )
                  },
                  {
                    "className": "dt-right", 
                    "targets": [5,6,7]
                  }
               
                 ],
                
                ///////////////////////////////////////////////////
                
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
         
                    // Total over this page
                    pageDebetTotal = api
                        .column(5, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
                    pageKreditTotal = api
                        .column(6, { page: 'current' })
                        .data()
                        .reduce(function (a, b) {
                            return intVal(a) + intVal(b);
                        }, 0);
         
                    // Update footer
                    $(api.column(5).footer()).html(pageDebetTotal.toLocaleString('en-US'));
                    $(api.column(6).footer()).html(pageKreditTotal.toLocaleString('en-US'));
                },
                
                
            });
        }
        
        $('#filter').click(function() {
            var kodes = $('#kodes').val();
            var tglDr = $('#tglDr').val();
            var tglSmp = $('#tglSmp').val();
            
            if (kodes != '' || (tglDr != '' && tglSmp != ''))
            {
                $('.datatable').DataTable().destroy();
                fill_datatable(kodes, tglDr, tglSmp);
            }
        });

        $('#resetfilter').click(function() {
            var kodes = '';
            var tglDr = '';
            var tglSmp = '';

            $('.datatable').DataTable().destroy();
            fill_datatable(kodes, tglDr, tglSmp);
        }); */

    });
    
		var dTableBSuplier;
		loadDataBSuplier = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('sup/browse')}}",
				data: {
					'GOL': $('#gol').val(),
				},
				success: function( response )
				{
					resp = response;
					if(dTableBSuplier){
						dTableBSuplier.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBSuplier.row.add([
							'<a href="javascript:void(0);" onclick="chooseSuplier(\''+resp[i].KODES+'\',  \''+resp[i].NAMAS+'\', \''+resp[i].ALAMAT+'\',  \''+resp[i].KOTA+'\')">'+resp[i].KODES+'</a>',
							resp[i].NAMAS,
							resp[i].ALAMAT,
							resp[i].KOTA,
						]);
					}
					dTableBSuplier.draw();
				}
			});
		}
		
		dTableBSuplier = $("#table-bsuplier").DataTable({
			
		});
		
		browseSuplier = function(){
			loadDataBSuplier();
			$("#browseSuplierModal").modal("show");
		}
		
		chooseSuplier = function(KODES,NAMAS, ALAMAT, KOTA){
			$("#kodes").val(KODES);
			$("#NAMAS").val(NAMAS);	
			$("#browseSuplierModal").modal("hide");
		}
		
		$("#kodes").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseSuplier();
			}
		});

        /////////////////////////////////////////////////////////////////////////////

        var dTableBBeli;
		loadDataBBeli = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('beli/browsekartu')}}",
				data: {
					'GOL': $('#gol').val(),
				},
				success: function( response )
				{
					resp = response;
					if(dTableBBeli){
						dTableBBeli.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBBeli.row.add([
							'<a href="javascript:void(0);" onclick="chooseBeli(\''+resp[i].NO_BUKTI+'\', \''+resp[i].NO_PO+'\', \''+resp[i].TGL+'\', \''+resp[i].NAMAS+'\', \''+resp[i].NA_BRG+'\', \''+resp[i].TRUCK+'\', \''+resp[i].KG+'\', \''+resp[i].HARGA+'\', \''+resp[i].TOTAL+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].TGL,
							resp[i].NAMAS,
							resp[i].NA_BRG,
							resp[i].TRUCK,
							resp[i].KG,
							resp[i].HARGA,
							resp[i].TOTAL,
						]);
					}
					dTableBBeli.draw();
				}
			});
		}
		
		dTableBBeli = $("#table-bbeli").DataTable({
			
		});
		
		browseBeli = function(){
			loadDataBBeli();
			$("#browseBeliModal").modal("show");
		}
		
		// chooseBeli = function(KODES,NAMAS, ALAMAT, KOTA){
		// 	$("#kodes").val(KODES);
		// 	$("#NAMAS").val(NAMAS);	
		// 	$("#browseBeliModal").modal("hide");
		// }
		
		$("#NO_BUKTI").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseBeli();
			}
		});

        ///////////////////////////////////////////////////////////////////////////

        var dTableBHut;
		loadDataBHut = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('hut/browsekartu')}}",
				data: {
					'GOL': $('#gol').val(),
				},
				success: function( response )
				{
					resp = response;
					if(dTableBHut){
						dTableBHut.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBHut.row.add([
							'<a href="javascript:void(0);" onclick="chooseHut(\''+resp[i].NO_BUKTI+'\', \''+resp[i].NO_PO+'\', \''+resp[i].TGL+'\', \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\', \''+resp[i].URAIAN+'\', \''+resp[i].BAYAR+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].NO_PO,
							resp[i].TGL,
							resp[i].KODES,
							resp[i].NAMAS,
							resp[i].URAIAN,
							resp[i].BAYAR,
						]);
					}
					dTableBHut.draw();
				}
			});
		}
		
		dTableBHut = $("#table-bhut").DataTable({
			
		});
		
		browseHut = function(){
			loadDataBHut();
			$("#browseHutModal").modal("show");
		}
		
		// chooseHut = function(KODES,NAMAS, ALAMAT, KOTA){
		// 	$("#kodes").val(KODES);
		// 	$("#NAMAS").val(NAMAS);	
		// 	$("#browseHutModal").modal("hide");
		// }
		
		$("#NO_BUKTI").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseHut();
			}
		});

        //////////////////////////////////////////////////////////////////////

        var dTableBKartu;
		loadDataBKartu = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('beli/browsekartu2')}}",
				data: {
					'GOL': $('#gol').val(),
				},
				success: function( response )
				{
					resp = response;
					if(dTableBKartu){
						dTableBKartu.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBKartu.row.add([
							'<a href="javascript:void(0);" onclick="chooseKartu(\''+resp[i].NO_BUKTI+'\', \''+resp[i].TGL+'\', \''+resp[i].NAMAS+'\', \''+resp[i].KG+'\', \''+resp[i].TOTAL+'\', \''+resp[i].BAYAR+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].TGL,
							resp[i].NAMAS,
							resp[i].KG,
							resp[i].TOTAL,
							resp[i].BAYAR,
						]);
					}
					dTableBKartu.draw();
				}
			});
		}
		
		dTableBKartu = $("#table-bkartu").DataTable({
			
		});
		
		browseKartu = function(){
			loadDataBKartu();
			$("#browseKartuModal").modal("show");
		}
		
		// chooseKartu = function(KODES,NAMAS, ALAMAT, KOTA){
		// 	$("#kodes").val(KODES);
		// 	$("#NAMAS").val(NAMAS);	
		// 	$("#browseKartuModal").modal("hide");
		// }
		
		$("#NO_BUKTI").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseKartu();
			}
		});

        //////////////////////////////////////////////////////////////////////
</script>
@endsection