@extends('layouts.main')

<style>
    .card {

    }

    .form-control:focus {
        background-color: #E0FFFF !important;
    }

	/* set capslock otomatis */
	.TRUCK{
		text-transform: uppercase;
	}
	/*  */
</style>

@section('content')



<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
        <div class="row">
            <div class="col-12">
            <div class="card">
                <div class="card-body">
																
                    <form action="{{($tipx=='new')? url('/jual/store?flagz='.$flagz.'&golz='.$golz.'') : url('/jual/update/'.$header->NO_ID.'&flagz='.$flagz.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
   
                        @csrf

                        <div class="tab-content mt-3">
        
                            <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="NO_BUKTI" class="form-label">NO BUKTI</label>
                                </div>
								
                                <input type="text" class="form-control NO_ID" id="NO_ID" name="NO_ID"
                                    value="{{$header->NO_ID ?? ''}}" hidden readonly>
								<input name="tipx" class="form-control tipx" id="tipx" value="{{$tipx}}" hidden >
								<input name="flagz" class="form-control flagz" id="flagz" value="{{$flagz}}" hidden >
								<input name="golz" class="form-control golz" id="golz" value="{{$golz}}" hidden >
								<input name="searchx" class="form-control searchx" id="searchx" value="{{$searchx ?? ''}}" hidden >

								
                                <div class="col-md-1">
                                    <input type="text" class="form-control NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" readonly style="width:150px">
                                </div>
								
								<div class="col-md-1" align="right">								
                                    <label for="GDG" class="form-label">GDG</label>
                                </div>
                                <div class="col-md-1 input-group" >
                                  <input type="text" class="form-control GDG" id="GDG" name="GDG" placeholder=""value="{{$header->GDG}}" style="text-align: left" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="KODEC" class="form-label">CUSTOMER</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODEC" id="KODEC" name="KODEC" placeholder="Masukkan Customer#" value="{{$header->KODEC}}" readonly>
                                </div>
								
								
								<div class="col-md-2"></div>
					
								<div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>

								</div> 								
								
                            </div>
        
			                <div class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="TGL" class="form-label">TGL</label>
                                </div>

                                <div class="col-md-2">
                                   <input class="form-control date" onclick="select()"  id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}" style="width:140px">
                                </div>
								
                                <div class="col-md-2">
								</div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control NAMAC" id="NAMAC" name="NAMAC" placeholder="Nama" value="{{$header->NAMAC}}" readonly>
                                </div>
                            </div>
        
                            <div class="form-group row">
								<div class="col-md-1" align="right">
									<label style="color:red">*</label>									
                                    <label for="NO_SO" class="form-label">NO SO#</label>
                                </div>
                                <div class="col-md-1" >
                                  <input type="text" class="form-control NO_SO" id="NO_SO" onblur="browseSo()" name="NO_SO" placeholder="Masukkan SO"value="{{$header->NO_SO}}" style="text-align: left; width:180px" >
        						</div>
								
								<div class="col-md-3" align="right">
                                </div>
								
                                <div class="col-md-3">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Masukkan Alamat" value="{{$header->ALAMAT}}" readonly>
                                </div>
                            </div>

			                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KD_BRG" class="form-label">BARANG</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KD_BRG" onclick="select()"  id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang" value="{{$header->KD_BRG}}" readonly style="width:140px">
                                </div>

								<div class="col-md-2">
								</div>
								
                                <div class="col-md-3">
                                    <input type="text" class="form-control KOTA" onclick="select()"  id="KOTA" name="KOTA" placeholder="Kota" value="{{$header->KOTA}}" readonly>
                                </div>
                            </div>

							<div {{($flagz == 'JL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label class="form-label"></label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NA_BRG" onclick="select()"  id="NA_BRG" name="NA_BRG" placeholder="-" value="{{$header->NA_BRG}}" readonly>
                                </div>
                            </div>
							
							<div {{($flagz == 'JL') ? '' : 'hidden' }} class="form-group row">
								
								<div class="col-md-1" align="right">
									<label class="form-label">HARGA</label>
								</div>
								<div class="col-md-1">
									<input type="text" onkeyup="hitung()"  onclick="select()"  class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga" value="{{ number_format($header->HARGA, 2, '.', ',') }}" style="text-align: right; width:140px">
								</div>
								
                                <div class="col-md-1"align="right">
                                    <label for="SISA" class="form-label">SISA</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control SISA" id="SISA" name="SISA" placeholder="Masukkan SISA" value="{{ number_format( $header->SISA, 0, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>

								<div class="col-md-3">
                                </div>
								
                                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <input type="text"  onclick="select()"  class="form-control RPRATE" id="RPRATE" name="RPRATE" placeholder="RPRATE" value="{{ number_format($header->RPRATE, 0, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
                            </div>

							<div class="form-group row">

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<label for="TRUCK" class="form-label">TRUCK</label>
								</div>
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1">
									<input type="text"  onclick="select()"  class="form-control TRUCK" id="TRUCK" name="TRUCK" placeholder="-" value="{{$header->TRUCK}}" style="text-align: right; width:140px">
								</div>

								<div class="col-md-5">
								</div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<input type="text"  onclick="select()"  class="form-control RPHARGA" id="RPHARGA" name="RPHARGA" placeholder="RPHARGA" value="{{ number_format($header->RPHARGA, 0, '.', ',') }}" style="text-align: right; width:140px" >
								</div>

							</div>

							<div class="form-group row">
								
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="QTY" class="form-label">BAG</label>
                                </div>
                                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1">
                                    <input type="text" onclick="select()"  class="form-control QTY" id="QTY" name="QTY" placeholder="Masukkan Bag" value="{{ number_format($header->QTY, 2, '.', ',') }}" style="text-align: right; width:100px" >
                                </div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="ATR" class="form-label">ATR</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" onclick="select()" class="form-control ATR" id="ATR" name="ATR" placeholder=""  value="{{$header->ATR}}" style="text-align: right; width:100px" >
                                </div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<label for="KA" class="form-label">KA</label>
								</div>
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1">
									<input type="text"  class="form-control KA" onclick="select()"  id="KA" name="KA" placeholder="Masukkan Bag" value="{{ number_format($header->KA, 2, '.', ',') }}" style="text-align: right; width:100px" >
								</div>

								<div class="col-md-1">
								</div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<input type="text" class="form-control RPTOTAL" onclick="select()" id="RPTOTAL" name="RPTOTAL" placeholder="RPTOTAL" value="{{ number_format($header->RPTOTAL, 0, '.', ',') }}" style="text-align: right; width:140px" >
								</div>

                                <div class="col-md-1"align="right">
                                    <label for="RP" class="form-label">RP</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control RP" id="RP" name="RP" placeholder="Masukkan RP" value="{{ number_format( $header->RP, 0, '.', ',') }}" style="text-align: right" >
                                </div>
							</div>

							<div class="form-group row">
								
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="KG" class="form-label">KG</label>
                                </div>
                                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-2">
                                    <input type="text" onclick="select()" class="form-control KG" id="KG" name="KG" placeholder="" value="{{ number_format($header->KG, 2, '.', ',') }}" style="text-align: right; width:180px" >
                                </div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="TOTAL" class="form-label">TOTAL</label>
                                </div>
                                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-2">
                                    <input type="text"  class="form-control TOTAL" onclick="select()"  id="TOTAL" name="TOTAL" placeholder="" value="{{ number_format($header->TOTAL, 0, '.', ',') }}" style="text-align: right; width:180px" >
                                </div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<label for="REF" class="form-label">REF</label>
								</div>
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1">
									<input type="text"  class="form-control REF" id="REF" onclick="select()" name="REF" placeholder="" value="{{ number_format($header->REF, 2, '.', ',') }}" style="text-align: right; width:100px" >
								</div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<label for="POT" class="form-label">POT</label>
								</div>
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1">
									<input type="text"  class="form-control POT" id="POT" onclick="select()"  name="POT" placeholder="" value="{{ number_format($header->POT, 2, '.', ',') }}" style="text-align: right; width:180px" >
								</div>
							</div>

							<div class="form-group row">
								
								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
                                    <label for="KGBAG" class="form-label">KG/BAG</label>
                                </div>
                                <div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-2">
                                    <input type="text"  class="form-control KGBAG" onclick="select()"  id="KGBAG" name="KGBAG" placeholder="Masukkan Kg/Bag" value="{{ number_format($header->KGBAG, 2, '.', ',') }}" style="text-align: right; width:180px" >
                                </div>

                                <div class="col-md-1"align="right">
                                    <label for="JUMREF" class="form-label">JUM REF</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control JUMREF" id="JUMREF" name="JUMREF" placeholder="Masukkan JUMREF" value="{{ number_format( $header->JUMREF, 0, '.', ',') }}" style="text-align: right; width:180px" >
                                </div>

								<div {{($flagz == 'JL') ? '' : 'hidden' }} class="col-md-1" align="right">
									<label for="GOL2" class="form-label">GOL</label>
								</div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control GOL2" id="GOL2" name="GOL2" placeholder=""  value="{{$header->GOL2}}" style="text-align: right; width:100px">
                                </div>

                                <div class="col-md-1"align="right">
                                    <label for="TOTALX" class="form-label">NETT</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onkeyup="hitung()" class="form-control TOTALX" id="TOTALX" name="TOTALX" placeholder="Masukkan TOTALX" value="{{ number_format( $header->TOTALX, 0, '.', ',') }}" style="text-align: right; width:180px" >
                                </div>
							</div>
							
							<div {{($flagz == 'JL') ? '' : 'hidden' }} class="form-group row">
								<div class="col-md-1" align="right">
                                    <label for="NO_DO" class="form-label">DO#</label>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control NO_DO" id="NO_DO" name="NO_DO" placeholder="Masukkan Notes"  value="{{$header->NO_DO}}">
                                </div>
                            </div>
							

							<div {{($flagz == 'JL') ? '' : 'hidden' }} class="form-group row">
								<div class="col-md-1" align="right">
                                    <label for="NOTES" class="form-label">NOTES</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control NOTES" onclick="select()"  id="NOTES" name="NOTES" placeholder="Masukkan Notes"  value="{{$header->NOTES}}">
                                </div>
                            </div>					

                            <div {{($flagz == 'TP') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="ACNOB" class="form-label">Acc#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control ACNOB" id="ACNOB" name="ACNOB" placeholder="Masukkan Acc#" value="{{$header->ACNOB}}" style="text-align: left" readonly >
        							
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NACNOB" id="NACNOB" name="NACNOB" placeholder="-" value="{{$header->NACNOB}}" readonly >
                                </div>
							</div>

                            <div {{($flagz == 'UM') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1">
                                    <label for="BACNO" class="form-label">Bank#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control BACNO" id="BACNO" name="BACNO" placeholder="Bank#" value="{{$header->BACNO}}" style="text-align: left" readonly >
        						
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control BNAMA" id="BNAMA" name="BNAMA" placeholder="-" value="{{ $header->BNAMA }}" readonly>
                                </div>
							</div>
							
                    </div>
						    
						<div class="mt-3 col-md-12 form-group row">
							<div class="col-md-4">
								<button type="button" id='TOPX'  onclick="location.href='{{url('/jual/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button" id='PREVX' onclick="location.href='{{url('/jual/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button" id='NEXTX' onclick="location.href='{{url('/jual/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button" id='BOTTOMX' onclick="location.href='{{url('/jual/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button" id='NEWX' onclick="location.href='{{url('/jual/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button" id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button" id='UNDOX' onclick="location.href='{{url('/jual/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success" class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button" id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/jual?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
							
                    </form>

			</div>
            </div>
            </div>
        </div>
        </div>
    </div>
	
	<div class="modal fade" id="browseSoModal" tabindex="-1" role="dialog" aria-labelledby="browseSoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSoModalLabel">Cari So#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bso">
				<thead>
					<tr>
						<th>So#</th>
						<th>Tgl</th>
						<th>Customer</th>
						<th>Barang</th>
						<th>Kg</th>
						<th>Kirim</th>
						<th>Sisa</th>
						<th>Harga</th>	
						<th>Notes</th>				

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

	<div class="modal fade" id="browseAccountModal" tabindex="-1" role="dialog" aria-labelledby="browseAccountModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseAccountModalLabel">Cari Account</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-baccount">
				<thead>
					<tr>
						<th>Acc#</th>
						<th>Nama</th>
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


	<div class="modal fade" id="browseGdgModal" tabindex="-1" role="dialog" aria-labelledby="browseGdgModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseMklModalLabel">Cari Gudang</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bgdg">
				<thead>
					<tr>
						<th>Kode</th>
						<th>Nama</th>
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
	


	<div class="modal fade" id="browseSoxModal" tabindex="-1" role="dialog" aria-labelledby="browseSoxModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseSoxModalLabel">Cari Sox#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bsox">
				<thead>
					<tr>
						<th>So#</th>
						<th>Customer#</th>
						<th>-</th>
						<th>Barang</th>
						<th>Harga</th>
						<th>Kg</th>
						<th>Kirim</th>						
						<th>Sisa</th>	
						<th>Total</th>
						<th>Bayar</th>
						<th>Sisa</th>
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

@section('footer-scripts')
<script src="{{ asset('js/autoNumerics/autoNumeric.min.js') }}"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="{{asset('foxie_js_css/bootstrap.bundle.min.js')}}"></script>


<script>
	var idrow = 1;
    function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(document).ready(function() {

		$tipx = $('#tipx').val();
		$searchx = $('#CARI').val();
		
		$('body').on('keydown', 'input, select', function(e) {
			if (e.key === "Enter") {
				var self = $(this), form = self.parents('form:eq(0)'), focusable, next;
				focusable = form.find('input,select,textarea').filter(':visible');
				next = focusable.eq(focusable.index(this)+1);
				console.log(next);
				if (next.length) {
					next.focus().select();
				} else {
					// tambah();
					// var nomer = idrow-1;
					console.log("NO_BUKTI");
					document.getElementById("NO_BUKTI").focus();
					// form.submit();
				}
				return false;
			}
		});

		
        if ( $tipx == 'new' )
		{
			 baru();			
		}

        if ( $tipx != 'new' )
		{
			 ganti();			
		}    
		
	
	
		$("#KG").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#QTY").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#SISA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#DPP").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#PPN").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#REF").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#JUMREF").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPRATE").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#RPHARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#RPTOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
        

		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		
		
		hitung=function() {	
		//getKA();
		
			var KGX = parseFloat($('#KG').val().replace(/,/g, ''));
			var HARGAX = parseFloat($('#HARGA').val().replace(/,/g, ''));
			var RATEX = parseFloat($('#RPRATE').val().replace(/,/g, ''));
			var REFX = parseFloat($('#REF').val().replace(/,/g, ''));

			var RPHARGAX  = ( RATEX * HARGAX );

			var TOTALX  = ( HARGAX * KGX ) - REFX;

			var RPTOTALX  = ( RATEX * TOTALX );

			var JUMREFX  = ( KGX * HARGAX * REFX );

			$('#TOTAL').val(numberWithCommas(TOTALX));
		    $("#TOTAL").autoNumeric('update');

			$('#RPHARGA').val(numberWithCommas(RPHARGAX));	
		    $("#RPHARGA").autoNumeric('update');

			$('#TOTAL').val(numberWithCommas(TOTALX));	
		    $("#TOTAL").autoNumeric('update');	

			$('#RPTOTAL').val(numberWithCommas(RPTOTALX));	
		    $("#RPTOTAL").autoNumeric('update');	

			$('#JUMREF').val(numberWithCommas(JUMREFX));	
		    $("#JUMREF").autoNumeric('update');	
			
		}				

		/////////////////////////////////////////////////////////////


		var dTableBSo;
		var rowidSo;
		loadDataBSo = function(){
		
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('so/browse')}}",
				async : false,
				data: {
						'NO_SO': $("#NO_SO").val(),
						'GOL': "{{$golz}}",
					
				},
				success: function( response )

				{
					resp = response;
					
					
					if ( resp.length > 1 )
					{	
							if(dTableBSo){
								dTableBSo.clear();
							}
							for(i=0; i<resp.length; i++){
								
								dTableBSo.row.add([
									'<a href="javascript:void(0);" onclick="chooseSo(\''+resp[i].NO_SO+'\', \''+resp[i].KODEC+'\', \''+resp[i].NAMAC+'\', \''+resp[i].TGL+'\', \''+resp[i].KOTA+'\' , \''+resp[i].KD_BRG+'\' , \''+resp[i].NA_BRG+'\' , \''+resp[i].HARGA+'\', \''+resp[i].KG+'\', \''+resp[i].KIRIM+'\', \''+resp[i].SISA+'\'  )">'+resp[i].NO_SO+'</a>',
									resp[i].TGL,
									resp[i].NAMAC,
									resp[i].NA_BRG,
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].KG)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].KIRIM)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].SISA)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].HARGA)+ '</div>',
									resp[i].NOTES,

								]);
							}
							dTableBSo.draw();
					
					}
					else
					{
						$("#NO_SO").val(resp[0].NO_SO);
						$("#KODEC").val(resp[0].KODEC);
						$("#NAMAC").val(resp[0].NAMAC);
						$("#ALAMAT").val(resp[0].ALAMAT);
						$("#KOTA").val(resp[0].KOTA);
						$("#KD_BRG").val(resp[0].KD_BRG);
						$("#NA_BRG").val(resp[0].NA_BRG);
						$("#SISA").val(resp[0].SISA);
						$("#HARGA").val(resp[0].HARGA);
					}
				}
			});
		}
		
		dTableBSo = $("#table-bso").DataTable({
			
		});

		browseSo = function(rid){
			rowidSo = rid;
			$("#NAMAC").val("");			
			loadDataBSo();
	
			
			if ( $("#NAMAC").val() == '' ) {				
					$("#browseSoModal").modal("show");
			}	
		}
		
		chooseSo = function(NO_SO,KODEC,NAMAC,ALAMAT, KOTA, KD_BRG, NA_BRG, SISA, HARGA){
			$("#NO_SO").val(NO_SO);
			$("#KODEC").val(KODEC);
			$("#NAMAC").val(NAMAC);
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);			
			$("#SISA").val(SISA);	
			$("#HARGA").val(HARGA);		
			$("#browseSoModal").modal("hide");
		}
		
		
		/* $("#NO_SO0").onblur(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseRak(0);
			}
		});  */



		/////////////////////////////////////////////////////////////////
		
		var dTableBSox;
		loadDataBSox = function(){
			
			$.ajax(
			{
				type: 'GET', 		
				url: "{{url('so/browseuang')}}",
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBSox){
						dTableBSox.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBSox.row.add([
							'<a href="javascript:void(0);" onclick="chooseSox(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODEC+'\', \''+resp[i].NAMAC+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].KODEC,
							resp[i].NAMAC,
							resp[i].NA_BRG,							
							Intl.NumberFormat('en-US').format(resp[i].HARGA),
							Intl.NumberFormat('en-US').format(resp[i].KG),
							Intl.NumberFormat('en-US').format(resp[i].KIRIM),
							Intl.NumberFormat('en-US').format(resp[i].XSISA),
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							Intl.NumberFormat('en-US').format(resp[i].SISA),
							
						]);
					}
					dTableBSox.draw();
				}
			});
		}
		
		dTableBSox = $("#table-bsox").DataTable({
			columnDefs: [
				{
                    className: "dt-right", 
					targets:  [],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browseSox = function(){			
			loadDataBSox();
			$("#browseSoxModal").modal("show");
		}
		
		chooseSox = function(NO_BUKTI,KODEC,NAMAC){
			$("#NO_SO").val(NO_BUKTI);
			$("#KODEC").val(KODEC);
			$("#NAMAC").val(NAMAC);		
			$("#browseSoxModal").modal("hide");
		}
		
		
		///////////////////////////////////////////////////////////////////////////////

 	
		var dTableBGdg;
		var rowidGdg;
		loadDataBGdg = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('gdg/browse')}}",

				success: function( response )
				{
					resp = response;
					if(dTableBGdg){
						dTableBGdg.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBGdg.row.add([
							'<a href="javascript:void(0);" onclick="chooseGdg(\''+resp[i].KODE+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].KODE+'</a>',
							resp[i].NAMA,
						
						]);
					}
					dTableBGdg.draw();
				}
			});
		}
		
		dTableBGdg = $("#table-bgdg").DataTable({
			
		});
		
		browseGdg = function(){
			loadDataBGdg();
			$("#browseGdgModal").modal("show");
		}
		
		chooseGdg = function(KODE,NAMA){
			$("#GDG").val(NAMA);			
			$("#browseGdgModal").modal("hide");
		}
		
		
		$("#GDG").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseGdg();
			}
		}); 
		
		
////////////////////////////////////////////////		
	

		var dTableBAccount;
		var tipex ;
		
		loadDataBAccount = function(){
			
		  if ( tipex == '0' )
		  {
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('account/browse')}}',
				success: function( response )
				{
					resp = response;
					if(dTableBAccount){
						dTableBAccount.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBAccount.row.add([
							'<a href="javascript:void(0);" onclick="chooseAccount(\''+resp[i].ACNO+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].ACNO+'</a>',
							resp[i].NAMA,
						]);
					}
					dTableBAccount.draw();
				}
			});
			
		  }
		  	
		  if ( tipex == '1' )
		  {
			
			  
			$.ajax(
			{
				type: 'GET',    
				url: '{{url('account/browsebank')}}',
				success: function( response )
				{
					resp = response;
					if(dTableBAccount){
						dTableBAccount.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBAccount.row.add([
							'<a href="javascript:void(0);" onclick="chooseAccount(\''+resp[i].ACNO+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].ACNO+'</a>',
							resp[i].NAMA,
						]);
					}
					dTableBAccount.draw();
				}
			});
			
		  }
		  
			
		}
		
		dTableBAccount = $("#table-baccount").DataTable({
			
		});
		
		browseAccount = function(rid){
			tipex = rid;
			loadDataBAccount();
			$("#browseAccountModal").modal("show");
		}
		
		chooseAccount = function(ACNO, NAMA){
			
			if ( tipex =='0' )
			{
			  $("#ACNOB").val(ACNO);
			  $("#NACNOB").val(NAMA);
			}
			
			if ( tipex =='1' )
			{
			  $("#BACNO").val(ACNO);
			  $("#BNAMA").val(NAMA);
			}
			
			$("#browseAccountModal").modal("hide");
		}
		
		$("#ACNOB").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseAccount(0);
			}
		}); 

		$("#BACNO").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseAccount(1);
			}
		}); 





/////////////////////////////////////////////////

        
	});		

	

    function simpan() {
	//	hitung();

    	var flagz = $('#flagz').val();

			if ( flagz =='JL'  ){
                 hitung();			
			}
		
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
        //var cekDropship = '0';
        //var noDropship = '';
		

			if ( $('#NO_SO').val()=='' ) 
            {			
			    check = '1';
				alert("SO# Harus diisi.");
			}
			
			// cek save format tgl otomatis

			if (tgl.includes("-")) {
				if ( tgl.substring(3,5) != bulanPer ) 
				{
					check = '1';
					alert("Bulan tidak sama dengan Periode");
				}	
				
				if ( tgl.substring(tgl.length-4) != tahunPer )
				{
					check = '1';
					alert("Tahun tidak sama dengan Periode");
				}	
			}else{
				if ( tgl.substring(2,4) != bulanPer ) 
				{
					check = '1';
					alert("Bulan tidak sama dengan Periode");
				}	
				
				if ( tgl.substring(tgl.length-4) != tahunPer )
				{
					check = '1';
					alert("Tahun tidak sama dengan Periode");
				}
			}

			//
			
			// if ( tgl.substring(3,5) != bulanPer ) 
			// {
			// 	check = '1';
			// 	alert("Bulan tidak sama dengan Periode");
			// }	
			
			// if ( tgl.substring(tgl.length-4) != tahunPer )
			// {
			// 	check = '1';
			// 	alert("Tahun tidak sama dengan Periode");
		    // }	 



			
		(check==0) ? document.getElementById("entri").submit() : alert('Masih ada kesalahan');

			
	}
	
 

	function baru() {
		
		 kosong();
		 hidup();
	
	}
	
	function ganti() {
		
		mati();
		// hidup();
	}
	
	function batal() {
		
		// alert($header[0]->NO_BUKTI);
		
		 //$('#NO_BUKTI').val($header[0]->NO_BUKTI);	
		 mati();
	
	}
	
 

	
	
	function hidup() {

		
		$("#TOPX").attr("disabled", true);
	    $("#PREVX").attr("disabled", true);
	    $("#NEXTX").attr("disabled", true);
	    $("#BOTTOMX").attr("disabled", true);

	    $("#NEWX").attr("disabled", true);
	    $("#EDITX").attr("disabled", true);
	    $("#UNDOX").attr("disabled", false);
	    $("#SAVEX").attr("disabled", false);
		
	    $("#HAPUSX").attr("disabled", true);
	    //$("#CLOSEX").attr("disabled", true);

		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_BUKTI").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			

			$("#KODEC").attr("readonly", true);
			$("#NAMAC").attr("readonly", true);
			$("#ALAMAT").attr("readonly", true);
			$("#KOTA").attr("readonly", true);
			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#KG").attr("readonly", false);
			$("#HARGA").attr("readonly", false);
			$("#QTY").attr("readonly", false);
			$("#TOTAL").attr("readonly", true);
			$("#DPP").attr("readonly", true);
			$("#PPN").attr("readonly", false);

			$("#TRUCK").attr("readonly", false);
			$("#GDG").attr("readonly", true);
			$("#GUDANG").attr("readonly", false);
			$("#NOTES").attr("readonly", false);

			$("#SISA").attr("readonly", true);
			$("#RPRATE").attr("readonly", true);
			$("#RPHARGA").attr("readonly", true);
			$("#RPTOTAL").attr("readonly", true);
			$("#TOTALX").attr("readonly", false);
			$("#REF").attr("readonly", false);
			$("#JUMREF").attr("readonly", false);
			$("#GOL2").attr("readonly", false);
			$("#NO_DO").attr("readonly", false);
			$("#ATR").attr("readonly", false);
			$("#KA").attr("readonly", false);
			$("#POT").attr("readonly", false);
			$("#RP").attr("readonly", false);
			$("#KGBAG").attr("readonly", false);


	        var flagz = $('#flagz').val();
		 
		    
			if ( flagz !='JL' ){
			    $("#TOTAL").attr("readonly", false);
			}
			
			$tipx = $('#tipx').val();
		
			
			if ( $tipx != 'new' )
			{
				$("#NO_SO").attr("readonly", true);		
				$("#NO_SO").removeAttr('onblur');		
			}
		
		
	}


	function mati() {

		
	    $("#TOPX").attr("disabled", false);
	    $("#PREVX").attr("disabled", false);
	    $("#NEXTX").attr("disabled", false);
	    $("#BOTTOMX").attr("disabled", false);


	    $("#NEWX").attr("disabled", false);
	    $("#EDITX").attr("disabled", false);
	    $("#UNDOX").attr("disabled", true);
	    $("#SAVEX").attr("disabled", true);
	    $("#HAPUSX").attr("disabled", false);
	    $("#CLOSEX").attr("disabled", false);

		$("#CARI").attr("readonly", false);	
	    $("#SEARCHX").attr("disabled", false);
		
	    $("#PLUSX").attr("hidden", true)
		
	    $(".NO_BUKTI").attr("readonly", true);	
		
		$("#TGL").attr("readonly", true);
		$("#NO_SO").attr("readonly", true);
		$("#KODEC").attr("readonly", true);
		$("#NAMAC").attr("readonly", true);
		$("#ALAMAT").attr("readonly", true);
		$("#KOTA").attr("readonly", true);
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);
		$("#KG").attr("readonly", true);
		$("#HARGA").attr("readonly", true);
		$("#QTY").attr("readonly", true);
		$("#TOTAL").attr("readonly", true)
		$("#DPP").attr("readonly", true);
		$("#PPN").attr("readonly", true);

		$("#TRUCK").attr("readonly", true);
		$("#GDG").attr("readonly", true);
		$("#GUDANG").attr("readonly", true);
		$("#NOTES").attr("readonly", true);
		$("#SISA").attr("readonly", true);

		$("#RPRATE").attr("readonly", true);
		$("#RPHARGA").attr("readonly", true);
		$("#RPTOTAL").attr("readonly", true);
		$("#TOTALX").attr("readonly", true);
		$("#REF").attr("readonly", true);
		$("#JUMREF").attr("readonly", true);
		$("#GOL2").attr("readonly", true);
		$("#NO_DO").attr("readonly", true);
		$("#ATR").attr("readonly", true);
		$("#KA").attr("readonly", true);
		$("#POT").attr("readonly", true);
		$("#RP").attr("readonly", true);
		$("#KGBAG").attr("readonly", true);


		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#NO_SO').val("");	
		 $('#KODEC').val("");	
		 $('#NAMAC').val("");
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");
		 
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");	
		 $('#KG').val("0");
		 $('#HARGA').val("0");		 
		 $('#QTY').val("0");
		 $('#TOTAL').val("0.00");		 
		 $('#DPP').val("0.00");		 
		 $('#PPN').val("0.00");
		 

		 $('#TRUCK').val("");
		 $('#NOTES').val("");	
		 $('#GDG').val("");		
		 $('#GUDANG').val("");	
		 $('#ACNOB').val("");	
		 $('#NACNOB').val("");
		 $('#BACNO').val("");	
		 $('#BNAMA').val("");		 
		 $('#RPRATE').val("1");	
		 

		//var flagz = $('#flagz').val();
		    
		//	if ( flagz =='JL'  ){

		//	    $('#ACNOB').val('411101');					
		//	    $('#NACNOB').val('PENJUALAN');					
				
		//	}

		 
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/jual/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/jual/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}

   
    
</script>
@endsection