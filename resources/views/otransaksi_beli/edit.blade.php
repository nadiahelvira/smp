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

																	
                    <form action="{{($tipx=='new')? url('/beli/store?flagz='.$flagz.'&golz='.$golz.'') : url('/beli/update/'.$header->NO_ID.'&flagz='.$flagz.'&golz='.$golz.'' ) }}" method="POST" name ="entri" id="entri" >
  
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
								
                                <div class="col-md-2">
                                    <input type="text" class="form-control NO_BUKTI" id="NO_BUKTI" name="NO_BUKTI"
                                    placeholder="Masukkan Bukti#" value="{{$header->NO_BUKTI}}" readonly>
                                </div>
								
								<div class="col-md-1" align="right">								
                                    <label for="GDG" class="form-label">GUDANG</label>
                                </div>
                                <div class="col-md-1 input-group" >
                                  <input type="text" class="form-control GDG" id="GDG" name="GDG" placeholder=""value="{{$header->GDG}}" style="text-align: left" readonly >
                                </div>

								<div class="col-md-1" align="right">
                                    <label for="KODES" class="form-label">SUPLIER</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KODES" id="KODES" onclick="select()" name="KODES" placeholder="Masukkan Suplier#" value="{{$header->KODES}}" readonly>
                                </div>
								
                                
                                <div class="col-md-3">
                                    <input type="text" class="form-control NAMAS" id="NAMAS" onclick="select()" name="NAMAS" placeholder="Nama" value="{{$header->NAMAS}}" readonly>
                                </div>
					
								<!-- <div class="col-md-3 input-group">

									<input type="text" hidden class="form-control CARI" id="CARI" name="CARI"
                                    placeholder="Cari Bukti#" value="" >
									<button type="button" hidden id='SEARCHX'  onclick="CariBukti()" class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
								</div>  -->
								
                            </div>
							
							<div class="form-group row">

                                <div class="col-md-1" align="right">
                                    <label for="TGL" class="form-label">TGL</label>
                                </div>
                                <div class="col-md-2">
									<input class="form-control date" onclick="select()" id="TGL" name="TGL" data-date-format="dd-mm-yyyy" type="text" autocomplete="off" value="{{date('d-m-Y',strtotime($header->TGL))}}">
                                </div>

								<div class="col-md-2"></div>
								
                                <div class="col-md-1" align="right">
                                </div>

                                <div class="col-md-3">
                                    <input type="text" class="form-control ALAMAT" id="ALAMAT" name="ALAMAT" placeholder="Masukkan Alamat" value="{{$header->ALAMAT}}" readonly>
                                </div>
								
                                <div class="col-md-2">
                                    <input type="text" class="form-control KOTA" id="KOTA" name="KOTA" placeholder="Kota" value="{{$header->KOTA}}" readonly>
                                </div>


                            </div>
        
                            <div class="form-group row">
								<div class="col-md-1" align="right">
									<label style="color:red">*</label>									
                                    <label for="NO_PO" class="form-label">NO PO</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_PO" id="NO_PO" name="NO_PO" onblur="browsePo()" placeholder="Masukkan PO"value="{{$header->NO_PO}}" style="text-align: left" >
        						</div>
								
								<div class="col-md-2" align="right">
                                </div>
                            </div>

							<div class="form-group row">

								<div class="col-md-1" align="right">								
                                    <label for="NO_SO" class="form-label">NO SO</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control NO_SO" id="NO_SO" name="NO_SO" placeholder="Masukkan SO"value="{{$header->NO_SO}}" style="text-align: left" >
        						</div>
								
								<div class="col-md-2" align="right">
                                </div>
                            </div>

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1" align="right">
                                    <label for="KD_BRG" class="form-label">BARANG</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KD_BRG" id="KD_BRG" name="KD_BRG" placeholder="Masukkan Barang" value="{{$header->KD_BRG}}" style="text-align: center" readonly>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control NA_BRG" id="NA_BRG" name="NA_BRG" placeholder="-" value="{{$header->NA_BRG}}" style="text-align: center" readonly>
                                </div>
                            </div>

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">

								<div class="col-md-1"align="right">
                                    <label for="HARGA" class="form-label">HARGA</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control HARGA" id="HARGA" name="HARGA" placeholder="Masukkan Harga"
									value="{{ number_format( $header->HARGA, 2, '.', ',') }}" style="text-align: right" >
                                </div>
								
                                <div class="col-md-1"align="right">
                                    <label for="SISA" class="form-label">SISA</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control SISA" id="SISA" name="SISA" placeholder="Masukkan SISA" value="{{ number_format( $header->SISA, 0, '.', ',') }}" style="text-align: right; width:120px" >
                                </div>

								<div class="col-md-1" align="right">
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <input type="text" class="form-control RPRATE" onclick="select()" id="RPRATE" name="RPRATE" placeholder="RPRATE" value="{{ number_format($header->RPRATE, 0, '.', ',') }}" style="text-align: center; width:150px" >
                                </div>
                            </div>

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">

								<div class="col-md-1" align="right">
									<label for="TRUCK" class="form-label">TRUCK</label>
								</div>
								<div class="col-md-2" align="left">
									<input type="text" class="form-control TRUCK" onclick="select()" id="TRUCK" name="TRUCK" placeholder="-" value="{{$header->TRUCK}}">
								</div>
								
                                <div class="col-md-1" align="right">
                                    <label for="BA" class="form-label">BA</label>
                                </div>
                                <div class="col-md-1" align="left">
                                    <input type="text" class="form-control BA" onclick="select()" id="BA" name="BA" placeholder="BA" value="{{ number_format($header->BA, 0, '.', ',') }}" style="text-align: right; width:100px" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="BM" class="form-label">BM</label>
                                </div>
                                <div class="col-md-1" align="left">
                                    <input type="text" class="form-control BM" onclick="select()" id="BM" name="BM" placeholder="BM" value="{{ number_format($header->BM, 0, '.', ',') }}" style="text-align: right; width:100px" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <input type="text" class="form-control RPHARGA" onclick="select()" id="RPHARGA" name="RPHARGA" placeholder="RPHARGA" value="{{ number_format($header->RPHARGA, 0, '.', ',') }}" style="text-align: right; width:150px" >
                                </div>

								<div class="col-md-1"align="right">
									<label for="BP" class="form-label">BP</label>
								</div>
								<div class="col-md-1">
									<input type="text" onclick="select()" onblur="hitung()" class="form-control BP" id="BP" name="BP" placeholder="Masukkan BP" value="{{ number_format( $header->BP, 0, '.', ',') }}" style="text-align: right" >
								</div>

								<div class="col-md-1"align="right">
									<label for="PERS" class="form-label">%</label>
								</div>
								<div class="col-md-1">
									<input type="text" onclick="select()" onblur="hitung()" class="form-control PERS" id="PERS" name="PERS" placeholder="Masukkan PERS" value="{{ number_format( $header->PERS, 0, '.', ',') }}" style="text-align: right" >
								</div>
                            </div>

							
							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
								
								<div class="col-md-1" align="right">
                                    <label for="BAG" class="form-label">BAG</label>
                                </div>
                                <div class="col-md-2" align="left">
                                    <input type="text" class="form-control BAG" onclick="select()" id="BAG" name="BAG" placeholder="BAG" value="{{ number_format($header->BAG, 0, '.', ',') }}" style="text-align: right; width:140px" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="KA" class="form-label">KA</label>
                                </div>
                                <div class="col-md-1" align="left">
                                    <input type="text" class="form-control KA" onclick="select()" id="KA" name="KA" placeholder="KA" value="{{ number_format($header->KA, 2, '.', ',') }}" style="text-align: right; width:100px" >
                                </div>
								
                                <div class="col-md-1" align="right">
                                    <label for="REF" class="form-label">REF</label>
                                </div>
                                <div class="col-md-1" align="left">
                                    <input type="text" class="form-control REF" onclick="select()" id="REF" name="REF" placeholder="REF" value="{{ number_format($header->REF, 2, '.', ',') }}" style="text-align: right; width:100px" >
                                </div>

                                <div class="col-md-1" align="right">
                                    <input type="text" class="form-control RPTOTAL" onclick="select()" id="RPTOTAL" name="RPTOTAL" placeholder="RPTOTAL" value="{{ number_format($header->RPTOTAL, 0, '.', ',') }}" style="text-align: right; width:150px" >
                                </div>
								
                                <div class="col-md-1"align="right">
                                    <label for="RP" class="form-label">RP</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control RP" id="RP" name="RP" placeholder="Masukkan RP" value="{{ number_format( $header->RP, 0, '.', ',') }}" style="text-align: right" >
                                </div>

                            </div>

							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
								
								<div class="col-md-1"align="right">
                                    <label for="KG" class="form-label">KG</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control KG" id="KG" name="KG" placeholder="Masukkan KG" value="{{ number_format( $header->KG, 0, '.', ',') }}" style="text-align: right" >
                                </div>

                                <div class="col-md-1"align="right">
                                    <label for="JUMREF" class="form-label">JUMREF</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control JUMREF" id="JUMREF" name="JUMREF" placeholder="Masukkan JUMREF" value="{{ number_format( $header->JUMREF, 0, '.', ',') }}" style="text-align: right; width:150px" >
                                </div>
								
                                <div class="col-md-1"align="right">
                                    <label for="KG1" class="form-label">KG II</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control KG1" id="KG1" name="KG1" placeholder="Masukkan KG1" value="{{ number_format( $header->KG1, 0, '.', ',') }}" style="text-align: right" >
                                </div>
								
                                <div class="col-md-1"align="right">
                                    <label for="POT2" class="form-label">POT II</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control POT2" id="POT2" name="POT2" placeholder="Masukkan POT2" value="{{ number_format( $header->POT2, 0, '.', ',') }}" style="text-align: right; width:150px" >
                                </div>
                            </div>
                            
							<div {{($flagz == 'BL') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1"align="right">
                                    <label for="KGBAG" class="form-label">KG/BAG</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control KGBAG" id="KGBAG" name="KGBAG" placeholder="Masukkan KGBAG" value="{{ number_format( $header->KGBAG, 0, '.', ',') }}" style="text-align: right" >
                                </div>
								
                                <div class="col-md-1"align="right">
                                    <label for="POT" class="form-label">POT</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" onclick="select()" onblur="hitung()" class="form-control POT" id="POT" name="POT" placeholder="Masukkan POT" value="{{ number_format( $header->POT, 0, '.', ',') }}" style="text-align: right" >
                                </div>

                                <div class="col-md-2" align="right">
                                </div>

                                <div class="col-md-1" align="right">
                                    <label for="TOTAL" class="form-label">TOTAL</label>
                                </div>
                                <div class="col-md-1">
                                    <input type="text" class="form-control TOTAL" onclick="select()" id="TOTAL" name="TOTAL" placeholder="TOTAL" value="{{ number_format($header->TOTAL, 2, '.', ',') }}" style="text-align: right; width:150px" readonly>
                                </div>
                            </div>

							
							<div class="form-group row">
								
								<div class="col-md-1" align="right">
                                    <label for="GUDANG" class="form-label">GUDANG</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control GUDANG" id="GUDANG" name="GUDANG" placeholder="" value="{{$header->GUDANG}}">
                                </div>
								
								<div class="col-md-1" align="right">
                                    <label for="KAPAL" class="form-label">KAPAL</label>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control KAPAL" onclick="select()" id="KAPAL" name="KAPAL" placeholder="Masukkan Kapal" value="{{$header->KAPAL}}">
                                </div>
								
                            </div>
							
							<div class="form-group row">
								<div class="col-md-1" align="right">
                                    <label for="NOTES" class="form-label">NOTES</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" class="form-control NOTES" onclick="select()" id="NOTES" name="NOTES" placeholder="Masukkan Notes" value="{{$header->NOTES}}">
                                </div>
                            </div>

                            <div {{($flagz == 'TH') ? '' : 'hidden' }} class="form-group row">
                                <div class="col-md-1">
                                    <label for="ACNOA" class="form-label">Acc#</label>
                                </div>
                                <div class="col-md-2 input-group" >
                                  <input type="text" class="form-control ACNOA" id="ACNOA" name="ACNOA" placeholder="Acc#" value="{{$header->ACNOA}}" style="text-align: left" readonly >
        						
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control NACNOA" id="NACNOA" name="NACNOA" placeholder="-" value="{{ $header->NACNOA }}" readonly>
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
								<button type="button"  id='TOPX'  onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=top&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Top</button>
								<button type="button"  id='PREVX' onclick="location.href='{{url('/beli/edit/?idx='.$header->NO_ID.'&tipx=prev&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Prev</button>
								<button type="button"  id='NEXTX' onclick="location.href='{{url('/beli/edit/?idx='.$header->NO_ID.'&tipx=next&flagz='.$flagz.'&golz='.$golz.'&buktix='.$header->NO_BUKTI )}}'" class="btn btn-outline-primary">Next</button>
								<button type="button"  id='BOTTOMX' onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=bottom&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-primary">Bottom</button>
							</div>
							<div class="col-md-5">
								<button type="button"  id='NEWX' onclick="location.href='{{url('/beli/edit/?idx=0&tipx=new&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-warning">New</button>
								<button type="button"  id='EDITX' onclick='hidup()' class="btn btn-secondary">Edit</button>                    
								<button type="button"  id='UNDOX' onclick="location.href='{{url('/beli/edit/?idx=' .$idx. '&tipx=undo&flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-info">Undo</button>  
								<button type="button" id='SAVEX' onclick='simpan()'   class="btn btn-success" class="fa fa-save"></i>Save</button>

							</div>
							<div class="col-md-3">
								<button type="button"  id='HAPUSX'  onclick="hapusTrans()" class="btn btn-outline-danger">Hapus</button>
								<button type="button" id='CLOSEX'  onclick="location.href='{{url('/beli?flagz='.$flagz.'&golz='.$golz.'' )}}'" class="btn btn-outline-secondary">Close</button>
							</div>
						</div>
						
						


                    </form>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
	

	<div class="modal fade" id="browsePoModal" tabindex="-1" role="dialog" aria-labelledby="browsePoModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browsePoModalLabel">Cari Po#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bpo">
				<thead>
					<tr>
						<th>Po#</th>
						<th>Tgl</th>
						<th>Suplier</th>
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
	  <div class="modal-dialog modal-xl" role="document">
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
	

	<div class="modal fade" id="browseMklModal" tabindex="-1" role="dialog" aria-labelledby="browseMklModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browseMklModalLabel">Cari EMKL</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bmkl">
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

	<div class="modal fade" id="browsePoxModal" tabindex="-1" role="dialog" aria-labelledby="browsePoxModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h5 class="modal-title" id="browsePoxModalLabel">Cari Po#</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table table-stripped table-bordered" id="table-bpox">
				<thead>
					<tr>
						<th>Po#</th>
						<th>Kode</th>
						<th>-</th>
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
		$("#KG1").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});		
		$("#HARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#TOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#SISA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.99'});
		$("#RPRATE").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#RPHARGA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#RPTOTAL").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#REF").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999.9999'});
		$("#JUMREF").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#POTII").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#POT").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#BA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#BM").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#BP").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#PERS").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});
		$("#KA").autoNumeric('init', {aSign: '<?php echo ''; ?>',vMin: '-999999999'});


		
		$(".date").datepicker({
			'dateFormat': 'dd-mm-yy',
		})
		

	
	
		hitung=function() {	

			var HARGAX = parseFloat($('#HARGA').val().replace(/,/g, ''));
			var KGX = parseFloat($('#KG').val().replace(/,/g, ''));
			var RATEX = parseFloat($('#RPRATE').val().replace(/,/g, ''));
			var REFX = parseFloat($('#REF').val().replace(/,/g, ''));
		
			var RPHARGAX  = ( RATEX * HARGAX );

			var TOTALX  = ( HARGAX * KGX );

			var RPTOTALX  = ( RATEX * TOTALX );

			var JUMREFX  = ( KGX * REFX );

			var KGIIX  = ( KGX - JUMREFX );
			
			$('#RPHARGA').val(numberWithCommas(RPHARGAX));	
		    $("#RPHARGA").autoNumeric('update');

			$('#TOTAL').val(numberWithCommas(TOTALX));	
		    $("#TOTAL").autoNumeric('update');	

			$('#RPTOTAL').val(numberWithCommas(RPTOTALX));	
		    $("#RPTOTAL").autoNumeric('update');	

			$('#JUMREF').val(numberWithCommas(JUMREFX));	
		    $("#JUMREF").autoNumeric('update');	
			
			$('#KG1').val(numberWithCommas(KGIIX));	
		    $("#KH1").autoNumeric('update');	
		
		}			
///////////////////////////////////////////////////////////////////////

		// var dTableBPo;
		// loadDataBPo = function(){
		// 	$.ajax(
		// 	{
		// 		type: 'GET',    
		// 		url: "{{url('po/browse')}}",
		// 		data: {
		// 			'GOL': "{{$golz}}",
		// 		},
		// 		success: function( response )
		// 		{
		// 			resp = response;
		// 			if(dTableBPo){
		// 				dTableBPo.clear();
		// 			}
		// 			for(i=0; i<resp.length; i++){
						
		// 				dTableBPo.row.add([
		// 					'<a href="javascript:void(0);" onclick="choosePo(\''+resp[i].NO_PO+'\', \''+resp[i].KODES+'\',  \''+resp[i].NAMAS+'\', \''+resp[i].ALAMAT+'\',  \''+resp[i].KOTA+'\',  \''+resp[i].KD_BRG+'\' ,  \''+resp[i].NA_BRG+'\' ,  \''+resp[i].SISA+'\',  \''+resp[i].HARGA+'\'   )">'+resp[i].NO_PO+'</a>',
		// 					resp[i].NAMAS,
		// 					resp[i].NA_BRG,
		// 					resp[i].HARGA,							
		// 					Intl.NumberFormat('en-US').format(resp[i].KG),	
		// 					Intl.NumberFormat('en-US').format(resp[i].KIRIM),	
		// 					Intl.NumberFormat('en-US').format(resp[i].SISA),	
							
		// 				]);
		// 			}
		// 			dTableBPo.draw();
		// 		}
		// 	});
		// }
		
		// dTableBPo = $("#table-bpo").DataTable({
			
		// });
		
		// browsePo = function(){
		// 	loadDataBPo();
		// 	$("#browsePoModal").modal("show");
		// }
		
		// choosePo = function(NO_PO, KODES, NAMAS, ALAMAT, KOTA, KD_BRG, NA_BRG, SISA, HARGA ){
		// 	$("#NO_PO").val(NO_PO);
		// 	$("#KODES").val(KODES);
		// 	$("#NAMAS").val(NAMAS);
		// 	$("#ALAMAT").val(ALAMAT);
		// 	$("#KOTA").val(KOTA);
		// 	$("#KD_BRG").val(KD_BRG);
		// 	$("#NA_BRG").val(NA_BRG);
		// 	$("#SISA").val(SISA);				
		// 	$("#HARGA").val(HARGA);
		// 	$("#browsePoModal").modal("hide");
			
		// 	hitung();
		// }
		
		// $("#NO_PO").keypress(function(e){

		// 	if(e.keyCode == 46){
		// 		e.preventDefault();
				
		// 		$flagz = $('#flagz').val();
				
		// 		if ( $flagz == 'BL' ) {
		// 			browsePo();
					
		// 		} else {
					
		// 			browsePox();

        //         }					
					
		// 	}
			
		// }); 
		
		
		////////////////////////////////////////

		/////////////////////////////////////////////////////////////


		var dTableBPo;
		var rowidPo;
		loadDataBPo = function(){
		
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('po/browse')}}",
				async : false,
				data: {
						'NO_PO': $("#NO_PO").val(),
						'GOL': "{{$golz}}",
					
				},
				success: function( response )

				{
					resp = response;
					
					
					if ( resp.length > 1 )
					{	
							if(dTableBPo){
								dTableBPo.clear();
							}
							for(i=0; i<resp.length; i++){
								
								dTableBPo.row.add([
									'<a href="javascript:void(0);" onclick="choosePo(\''+resp[i].NO_PO+'\', \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\', \''+resp[i].TGL+'\', \''+resp[i].KOTA+'\' , \''+resp[i].KD_BRG+'\' , \''+resp[i].NA_BRG+'\', \''+resp[i].KIRIM+'\', \''+resp[i].SISA+'\' , \''+resp[i].HARGA+'\'  )">'+resp[i].NO_PO+'</a>',
									resp[i].TGL,
									resp[i].NAMAS,
									resp[i].NA_BRG,
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].KG)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].KIRIM)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].SISA)+ '</div>',
									'<div style="text-align: right">' +Intl.NumberFormat('en-US').format(resp[i].HARGA)+ '</div>',
									resp[i].NOTES,
									
								]);
							}
							dTableBPo.draw();
					
					}
					else
					{
						$("#NO_PO").val(resp[0].NO_PO);
						$("#KODES").val(resp[0].KODES);
						$("#NAMAS").val(resp[0].NAMAS);
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
		
		dTableBPo = $("#table-bpo").DataTable({
			
		});

		browsePo = function(rid){
			rowidPo = rid;
			$("#NAMAS").val("");			
			loadDataBPo();
	
			
			if ( $("#NAMAS").val() == '' ) {				
					$("#browsePoModal").modal("show");
			}	
		}
		
		choosePo = function(NO_PO,KODES,NAMAS,ALAMAT, KOTA, KD_BRG, NA_BRG, KIRIM, SISA, HARGA ){
			$("#NO_PO").val(NO_PO);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);
			$("#ALAMAT").val(ALAMAT);
			$("#KOTA").val(KOTA);
			$("#KD_BRG").val(KD_BRG);
			$("#NA_BRG").val(NA_BRG);			
			$("#SISA").val(SISA);	
			$("#HARGA").val(HARGA);		
			$("#browsePoModal").modal("hide");

		}
		
		
		/* $("#NO_PO0").onblur(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browsePo(0);
			}
		});  */



		/////////////////////////////////////////////////////////////////
		

		var dTableBPox;
		loadDataBPox = function(){
		
			$.ajax(
			{
				type: 'GET', 		
				url: '{{url('po/browseuang')}}',
				data: {
					'GOL': "{{$golz}}",
				},
				success: function( response )
				{
					resp = response;
					if(dTableBPox){
						dTableBPox.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBPox.row.add([
							'<a href="javascript:void(0);" onclick="choosePox(\''+resp[i].NO_BUKTI+'\',  \''+resp[i].KODES+'\', \''+resp[i].NAMAS+'\')">'+resp[i].NO_BUKTI+'</a>',
							resp[i].KODES,
							resp[i].NAMAS,
							Intl.NumberFormat('en-US').format(resp[i].TOTAL),
							Intl.NumberFormat('en-US').format(resp[i].BAYAR),
							Intl.NumberFormat('en-US').format(resp[i].SISA),
							
						]);
					}
					dTableBPox.draw();
				}
			});
		}
		
		dTableBPox = $("#table-bpox").DataTable({
			columnDefs: [
				{
                    className: "dt-right", 
					targets:  [],
					render: $.fn.dataTable.render.number( ',', '.', 2, '' )
				}
			],
		});
		
		browsePox = function(){
			 loadDataBPox();
			$("#browsePoxModal").modal("show");
		}
		
		choosePox = function(NO_BUKTI,KODES,NAMAS){
			$("#NO_PO").val(NO_BUKTI);
			$("#KODES").val(KODES);
			$("#NAMAS").val(NAMAS);		
			$("#browsePoxModal").modal("hide");
		}
		
 
						
		
		
		
		
		//////////////////////////////////////

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
			  $("#ACNOA").val(ACNO);
			  $("#NACNOA").val(NAMA);
			}
			
			if ( tipex =='1' )
			{
			  $("#BACNO").val(ACNO);
			  $("#BNAMA").val(NAMA);
			}
			
			$("#browseAccountModal").modal("hide");
		}
		
		$("#ACNOA").keypress(function(e){
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



		
		///////////////////////////////////////////////////////////////////////////////////////////////		
		var dTableBMkl;
		var rowidMkl;
		loadDataBMkl = function(){
			$.ajax(
			{
				type: 'GET',    
				url: "{{url('mkl/browse')}}",

				success: function( response )
				{
					resp = response;
					if(dTableBMkl){
						dTableBMkl.clear();
					}
					for(i=0; i<resp.length; i++){
						
						dTableBMkl.row.add([
							'<a href="javascript:void(0);" onclick="chooseMkl(\''+resp[i].KODE+'\',  \''+resp[i].NAMA+'\' )">'+resp[i].KODE+'</a>',
							resp[i].NAMA,
						
						]);
					}
					dTableBMkl.draw();
				}
			});
		}
		
		dTableBMkl = $("#table-bmkl").DataTable({
			
		});
		
		browseMkl = function(){
			loadDataBMkl();
			$("#browseMklModal").modal("show");
		}
		
		chooseMkl = function(KODE,NAMA){
			$("#EMKL").val(NAMA);			
			$("#browseMklModal").modal("hide");
		}
		
		
		$("#EMKL").keypress(function(e){
			if(e.keyCode == 46){
				e.preventDefault();
				browseMkl();
			}
		}); 	
 		
		/*
		$("#KA").keypress(function(e){

			if(e.keyCode == 46){
				e.preventDefault();
				browseRefa();
			}
		}); */
		
		//////////////////////////////////////////////////////////////////////////////////////////////////
	});		



 	function simpan() {

    	var flagz = $('#flagz').val();

			if ( flagz =='BL'  ){
                 hitung();			
			}
		
		var tgl = $('#TGL').val();
		var bulanPer = {{session()->get('periode')['bulan']}};
		var tahunPer = {{session()->get('periode')['tahun']}};
		
        var check = '0';
		
		
			if ( $('#NO_PO').val()=='' ) 
            {			
			    check = '1';
				alert("PO# Harus diisi.");
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

	    
			if ( flagz =='TH'  ){
			    var RPTOTALXX = $("#TOTAL").val();
		
			    $('#RPTOTAL').val(numberWithCommas(RPTOTALXX));	
		        $("#RPTOTAL").autoNumeric('update');				
				
			}

			if ( flagz =='UM'  ){
			    var RPTOTALXX = $("#TOTAL").val() * -1;
		
			    $('#RPTOTAL').val(numberWithCommas(RPTOTALXX));	
		        $("#RPTOTAL").autoNumeric('update');				
				
			}
			
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
	  //  $("#CLOSEX").attr("disabled", true);

		$("#CARI").attr("readonly", true);	
	    $("#SEARCHX").attr("disabled", true);
		
	    $("#PLUSX").attr("hidden", false)
		   
			$("#NO_BUKTI").attr("readonly", true);		   
			$("#TGL").attr("readonly", false);
			// $("#NO_PO").attr("readonly", true);
			$("#KODES").attr("readonly", true);
			$("#NAMAS").attr("readonly", true);
			$("#ALAMAT").attr("readonly", true);
			$("#KOTA").attr("readonly", true);
			$("#KD_BRG").attr("readonly", true);
			$("#NA_BRG").attr("readonly", true);
			$("#KG").attr("readonly", false);
			$("#SISA").attr("readonly", true);
			$("#HARGA").attr("readonly", false);
			$("#LAIN").attr("readonly", false);
			$("#TOTAL").attr("readonly", true);
			$("#RPRATE").attr("readonly", true);
			$("#RPHARGA").attr("readonly", true);
			$("#RPLAIN").attr("readonly", false);
			$("#RPTOTAL").attr("readonly", true);
			$("#TGL_BL").attr("readonly", false );						
			$("#NOTES").attr("readonly", false);
			$("#BAG").attr("readonly", false);
			$("#RP").attr("readonly", false);
			$("#KGBAG").attr("readonly", false);
			$("#POT").attr("readonly", false);
			$("#GUDANG").attr("readonly", false);
			$("#GDG").attr("readonly", false);
			$("#TRUCK").attr("readonly", false);
			$("KAPAL").attr("readonly", false);
			$("#BA").attr("readonly", false);
			$("#BP").attr("readonly", false);
			$("#BM").attr("readonly", false);
			$("#JUMREF").attr("readonly", true);
			$("#REF").attr("readonly", false);
			$("#POT2").attr("readonly", false);
			$("#KG1").attr("readonly", true);
			$("#KA").attr("readonly", false);
			$("#PERS").attr("readonly", false);
			$("#NO_SO").attr("readonly", false);
			
		
    		var flagz = $('#flagz').val();
    		var golz = $('#golz').val();

		    
			if ( flagz !='BL' ){
			    $("#TOTAL").attr("readonly", false);
			}
			
			if ( flagz =='BL' && golz =='Z' ){
			    $("#HARGA").attr("readonly", false);
			}

			$tipx = $('#tipx').val();
		
			
			if ( $tipx != 'new' )
			{
				$("#NO_PO").attr("readonly", true);		
				$("#NO_PO").removeAttr('onblur');
				
				$("#NO_SO").attr("readonly", true);		
				
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
		$("#NO_PO").attr("readonly", true);
		$("#KODES").attr("readonly", true);
		$("#NAMAS").attr("readonly", true);
		$("#ALAMAT").attr("readonly", true);
		$("#KOTA").attr("readonly", true);
		$("#KD_BRG").attr("readonly", true);
		$("#NA_BRG").attr("readonly", true);
		$("#KG").attr("readonly", true);
		$("#HARGA").attr("readonly", true);
		$("#LAIN").attr("readonly", true);
		$("#TOTAL").attr("readonly", true)
		$("#RPRATE").attr("readonly", true);
		$("#RPHARGA").attr("readonly", true);
		$("#RPLAIN").attr("readonly", true);
		$("#RPTOTAL").attr("readonly", true);

		$("#AJU").attr("readonly", true);
		$("#BL").attr("readonly", true);
		$("#EMKL").attr("readonly", true);
		$("#JCONT").attr("readonly", true);
		$("#TGL_BL").attr("readonly", true);
		$("#NOTES").attr("readonly", true);
		$("#SISA").attr("readonly", true);
		$("#TRUCK").attr("readonly", true);
		$("#BAG").attr("readonly", true);
		$("#RP").attr("readonly", true);
		$("#KGBAG").attr("readonly", true);
		$("#POT").attr("readonly", true);
		$("#GUDANG").attr("readonly", true);
		$("#GDG").attr("readonly", true);
		$("#KAPAL").attr("readonly", true);
		$("#BA").attr("readonly", true);
		$("#BP").attr("readonly", true);
		$("#BM").attr("readonly", true);
		$("#JUMREF").attr("readonly", true);
		$("#REF").attr("readonly", true);
		$("#POT2").attr("readonly", true);
		$("#KG1").attr("readonly", true);
		$("#KA").attr("readonly", true);
		$("#PERS").attr("readonly", true);
		$("#NO_SO").attr("readonly", true);
		
	}


	function kosong() {
				
		 $('#NO_BUKTI').val("+");	
	//	 $('#TGL').val("");	
		 $('#KODES').val("");	
		 $('#NAMAS').val("");
		 $('#ALAMAT').val("");	
		 $('#KOTA').val("");
		 
		 $('#KD_BRG').val("");	
		 $('#NA_BRG').val("");	
		 $('#KG').val("0");
		 $('#HARGA').val("0.00");		 
		 $('#LAIN').val("0.00");
		 $('#TOTAL').val("0.00");		 
		 $('#RPRATE').val("1");		 
		 $('#RPHARGA').val("0.00");
		 $('#RPLAIN').val("0.00");
		 $('#RPTOTAL').val("0.00");		 

		 $('#AJU').val("");	
		 $('#BL').val("");	
		 $('#EMKL').val("");	
		 $('#JCONT').val("0");			 	 
		 $('#NOTES').val("");	
		 $('#ACNOA').val("");
		 $('#NACNOA').val("");	
		 $('#BACNO').val("");
		 $('#BNAMA').val("");
		 $('#RP').val("0");	
		 $('#BA').val("0");	
		 $('#BM').val("0");	
		 $('#JUMREF').val("0");	
		 $('#KG1').val("0");
		 $('#POT').val("0");
		 $('#KA').val("0");	
		 $('#REF').val("0");
		 $('#TRUCK').val("");	
		 $('#GUDANG').val("");
		 $('#GDG').val("");
		 
		//var flagz = $('#flagz').val();
		    
		//	if ( flagz =='BL'  ){

		//	    $('#ACNOA').val('115102');					
		//	    $('#NACNOA').val('PERSEDIAAN DALAM PERJALANAN');					
				
		//	}
			
		
	}
	
	function hapusTrans() {
		let text = "Hapus Transaksi "+$('#NO_BUKTI').val()+"?";
		if (confirm(text) == true) 
		{
			window.location ="{{url('/beli/delete/'.$header->NO_ID .'/?flagz='.$flagz.'&golz=' .$golz.'' )}}";
			//return true;
		} 
		return false;
	}
	

	function CariBukti() {
		
		var flagz = "{{ $flagz }}";
		var golz = "{{ $golz }}";
		var cari = $("#CARI").val();
		var loc = "{{ url('/beli/edit/') }}" + '?idx={{ $header->NO_ID}}&tipx=search&flagz=' + encodeURIComponent(flagz) +'&golz=' + encodeURIComponent(golz) + '&buktix=' +encodeURIComponent(cari);
		window.location = loc;
		
	}


		
	//////////////////////////////////////////////////////////////////////
</script>
@endsection