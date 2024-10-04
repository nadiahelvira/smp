<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;

use App\Models\OTransaksi\Beli;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;

include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;

class BeliController extends Controller
{

    var $judul = '';
    var $FLAGZ = '';
    var $GOLZ = '';


    function setFlag(Request $request)
    {
		
        if ( $request->flagz == 'BL' && $request->golz == 'K' ) {
            $this->judul = "Pembelian Barang";
        } else if ( $request->flagz == 'BL' && $request->golz == 'L' ) {
            $this->judul = "Pembelian Non";
        } else if ( $request->flagz == 'TH' && $request->golz == 'K' ) {
            $this->judul = "Transaksi Hutang";
        } else if ( $request->flagz == 'TH' && $request->golz == 'L' ) {
            $this->judul = "Transaksi Hutang Non";
        } else if ( $request->flagz == 'UM' && $request->golz == 'K' ) {
            $this->judul = "Uang Muka Pembelian ";
        } else if ( $request->flagz == 'UM' && $request->golz == 'L' ) {
            $this->judul = "Uang Muka Pembelian Non";
        }
		
		
        $this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_beli.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ , 'flagz' => $this->FLAGZ ]);
    }

    public function browse(Request $request)
    {
        //$beli = DB::table('beli')->select('NO_BUKTI', 'TGL', 'KODES','NAMAS', 'ALAMAT','KOTA', 'TOTAL','BAYAR','SISA')->where('KODES', $request['KODES'] )->where('SISA', '<>', 0 )->where('GOL', 'Y')->orderBy('KODES', 'ASC')->get();



        $beli = DB::SELECT("SELECT NO_BUKTI,TGL, NO_PO, KODES, NAMAS, ALAMAT, KOTA,KD_BRG, NA_BRG, KG, HARGA, ( JCONT- SCONT ) AS KIRIM, SCONT AS SISA, NOTES, RPRATE, EMKL, BL, AJU  from beli
		WHERE  SCONT > 0 and GOL='$request->GOL' ORDER BY KODES; ");

        return response()->json($beli);
    }

    public function browseuang(Request $request)
    {

		$no_pox = $request->NO_PO;
		$golx = $request->GOL;
		
        $beli = DB::SELECT("SELECT NO_BUKTI,TGL, NO_PO, KODES, NAMAS, RPTOTAL AS TOTAL, RPBAYAR AS BAYAR, RPSISA AS SISA  from beli
		WHERE  NO_PO='$no_pox' AND RPSISA<>0 and GOL='$golx' ORDER BY NO_BUKTI; ");
        
        return response()->json($beli);
    }

    public function browsekartu(Request $request)
    {
		$periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $beli = DB::SELECT(" SELECT NO_BUKTI, NO_PO, DATE_FORMAT(beli.TGL,'%d/%m/%Y') AS TGL, NAMAS, NA_BRG, TRUCK, KG, HARGA, TOTAL 
        FROM BELI
		WHERE 
        -- PER='$periode' AND 
        NO_PO=(SELECT max(NO_PO) from po WHERE NO_ID='$request->IDPO')
        ORDER BY NO_BUKTI; ");

        return response()->json($beli);
    }

    public function browsekartu2(Request $request)
    {
		$periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        $tahun = substr($periode,3,4);

        $kartu = DB::SELECT(" SELECT *,@akum:=@akum+TOTAL-BAYAR AS SALDO from
                    (

                    SELECT NO_BUKTI, DATE_FORMAT(belix.TGL,'%d/%m/%Y') AS TGL, NAMAS, 0 AS KG, PER01 AS TOTAL, 0 AS BAYAR
                    from belix where belix.YER='$tahun' and PER01<>0  union all

                    SELECT NO_PO AS NO_BUKTI, DATE_FORMAT(po.TGL,'%d/%m/%Y') AS TGL , NAMAS, KG, 0 AS TOTAL, 0 AS BAYAR
                    from po 
                    WHERE 
                    -- PER='$periode' AND 
                    NO_PO=(SELECT max(NO_PO) from po WHERE NO_ID='$request->IDPO') union all

                    SELECT NO_BUKTI, DATE_FORMAT(hut.TGL,'%d/%m/%Y') AS TGL, NAMAS, 0 AS KG, TOTAL, BAYAR 
                    from hut 
                    WHERE 
                    -- PER='$periode' AND 
                    NO_PO=(SELECT max(NO_PO) from po WHERE NO_ID='$request->IDPO')
                                    
                    ) as  kartu ");

        return response()->json($kartu);
    }

    public function getBeli(Request $request)
    {
        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }

        $this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
        $beli = DB::SELECT("SELECT * from beli  where  PER ='$periode' and FLAG ='$this->FLAGZ' AND GOL ='$this->GOLZ'  ORDER BY NO_BUKTI ");
		    
         return Datatables::of($beli)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant") 
                {

                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' href="beli/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&flagz=' . $row->FLAG . '&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_BUKTI . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)"  href="beli/delete/' . $row->NO_ID . '/?flagz=' . $row->FLAG . '&golz=' . $row->GOL . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="jsbelic/' . $row->NO_ID . '">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Print
                                </a> 									
                                <hr></hr>
                                <a class="dropdown-item btn btn-danger" ' . $btnDelete . '>
   
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                    Delete
                                </a> 
                        ';
                } else {
                    $btnPrivilege = '';
                }

                $actionBtn =
                    '
                    <div class="dropdown show" style="text-align: center">
                        <a class="btn btn-secondary dropdown-toggle btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                           

                            ' . $btnPrivilege . '
                        </div>
                    </div>
                    ';

                return $actionBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }



    public function store(Request $request)
    {

        $this->validate(
            $request,
            [
           //     'NO_PO'       => 'required',
                'TGL'      => 'required',
            ]
        );


		
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		
		
        // Generate Nomor Bukti
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];


        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);

        $no_bukti ='';
        $no_bukti2 ='';
		
		if ( $request->flagz == 'BL' && $request->golz == 'K' ) {

            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'BL')->where('GOL', 'K')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'BK' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'BK' . $tahun . $bulan . '-0001';
				}
		
        } else if ( $request->flagz == 'BL' && $request->golz == 'L' ) {

            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'BL')->where('GOL', 'L')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'BL' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'BL' . $tahun . $bulan . '-0001';
				}


        } else if ( $request->flagz == 'TH' && $request->golz == 'K' ) {

            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'TH')->where('GOL', 'K')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'AK' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'AK' . $tahun . $bulan . '-0001';
				}
				
        } else if ( $request->flagz == 'TH' && $request->golz == 'L' ) {

            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'TH')->where('GOL', 'L')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'AL' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'AL' . $tahun . $bulan . '-0001';
				}

        } else if ( $request->flagz == 'UM' && $request->golz == 'K' ) {
 
            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'UM')->where('GOL', 'K')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'UK' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'UK' . $tahun . $bulan . '-0001';
				}
				
			$bulan    = session()->get('periode')['bulan'];
            $tahun    = substr(session()->get('periode')['tahun'], -2);
            $query2 = DB::table('bank')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BBK')->orderByDesc('NO_BUKTI')->limit(1)->get();

            if ($query2 != '[]') {
                $query2 = substr($query2[0]->NO_BUKTI, -4);
                $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                $no_bukti2 = 'BBK' . $tahun . $bulan . '-' . $query2;
            } else {
                $no_bukti2 = 'BBK' . $tahun . $bulan . '-0001';
            }
			
				
 
        } else if ( $request->flagz == 'UM' && $request->golz == 'L' ) {

            $query = DB::table('beli')->select(DB::raw("TRIM(NO_BUKTI) AS NO_BUKTI"))->where('PER', $periode)
			         ->where('FLAG', 'UM')->where('GOL', 'L')->orderByDesc('NO_BUKTI')->limit(1)->get();
			
			if ($query != '[]') {
            
				$query = substr($query[0]->NO_BUKTI, -4);
				$query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
				$no_bukti = 'UL' . $tahun . $bulan . '-' . $query;
			
			} else {
				$no_bukti = 'UL' . $tahun . $bulan . '-0001';
				}


			$bulan    = session()->get('periode')['bulan'];
            $tahun    = substr(session()->get('periode')['tahun'], -2);
            $query2 = DB::table('bank')->select('NO_BUKTI')->where('PER', $periode)->where('TYPE', 'BBK')->orderByDesc('NO_BUKTI')->limit(1)->get();

            if ($query2 != '[]') {
                $query2 = substr($query2[0]->NO_BUKTI, -4);
                $query2 = str_pad($query2 + 1, 4, 0, STR_PAD_LEFT);
                $no_bukti2 = 'BBK' . $tahun . $bulan . '-' . $query2;
            } else {
                $no_bukti2 = 'BBK' . $tahun . $bulan . '-0001';
            }
			
			
        }
        


        // Insert Header

        // set format tgl otomatis TGL jadi 00-00-0000
        $tgl_rubah = $request['TGL'];
        ///

        $beli = Beli::create(
            [
                'NO_BUKTI'         => $no_bukti,
                // 'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                // set format tgl otomatis
                'TGL'              => substr($tgl_rubah, 4, 4)."-".substr($tgl_rubah, 2, 2)."-".substr($tgl_rubah, 0, 2),
                // 
                'PER'              => $periode,
                'NO_BL'            => ($request['NO_PO'] == null) ? "" : $request['NO_BL'],
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'KODES'            => ($request['KODES'] == null) ? "" : $request['KODES'],
                'NAMAS'            => ($request['NAMAS'] == null) ? "" : $request['NAMAS'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'FLAG'             =>  $FLAGZ,
                'GOL'              =>  $GOLZ,
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'             => (float) str_replace(',', '', $request['SISA']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'LAIN'             => (float) str_replace(',', '', $request['LAIN']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'RPRATE'          => (float) str_replace(',', '', $request['RPRATE']),
                'RPHARGA'          => (float) str_replace(',', '', $request['HARGA']),
                'RPLAIN'           => (float) str_replace(',', '', $request['RPLAIN']),				
                'RPTOTAL'          => (float) str_replace(',', '', $request['TOTAL']),
				'RPSISA'           => (float) str_replace(',', '', $request['RPSISA']),
                'ACNOA'            => ($request['ACNOA'] == null) ? "" : $request['ACNOA'],
                'NACNOA'           => ($request['NACNOA'] == null) ? "" : $request['NACNOA'],
               // 'ACNOB'            => '211101',
               // 'NACNOB'           => 'HUTANG DAGANG',
                'BACNO'              => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'               => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],
                // 'NO_BANK'               => $no_bukti2,				
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
				
                //set huruf kapital
                'TRUCK'            => ($request['TRUCK'] == null) ? "" : strtoupper($request['TRUCK']),
                //

                'BA'               => (float) str_replace(',', '', $request['BA']),
                'BP'               => (float) str_replace(',', '', $request['BP']),
                'BAG'              => (float) str_replace(',', '', $request['BAG']),
                'KA'               => (float) str_replace(',', '', $request['KA']),
                'REF'              => (float) str_replace(',', '', $request['REF']),
                'RP'               => (float) str_replace(',', '', $request['RP']),
                'JUMREF'           => (float) str_replace(',', '', $request['JUMREF']),
                'KG1'             => (float) str_replace(',', '', $request['KG1']),
                'POT2'            => (float) str_replace(',', '', $request['POT2']),
                'KGBAG'             => (float) str_replace(',', '', $request['KGBAG']),
                'POT'            => (float) str_replace(',', '', $request['POT']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'GUDANG'            => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
               'KAPAL'            => ($request['KAPAL'] == null) ? "" : $request['KAPAL'],
				
                'TG_SMP'           => Carbon::now()
            ]
        );


		if ( $FLAGZ == 'BL' ) {	
		
			// $variablell = DB::select('call beliins(?)', array($no_bukti));
			
		} else if ( $FLAGZ == 'UM' ) {
			//$variablell = DB::select('call umins(?,?)', array($no_bukti, $no_bukti2));

        } else if ( $FLAGZ == 'TH' ) {
            //$variablell = DB::select('call thutins(?)', array($no_bukti));
        }
		
	    
		$no_buktix = $no_bukti;

        DB::SELECT("UPDATE beli, po SET  beli.SISA = po.SISA WHERE beli.NO_PO=po.NO_PO;");
		
		$beli = Beli::where('NO_BUKTI', $no_buktix )->first();
					 
	// 	return redirect('/beli?flagz='.$FLAGZ.'&golz='.$GOLZ)->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    return redirect('/beli/edit/?idx=' . $beli->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&flagz=' . $this->FLAGZ . '&judul=' . $this->judul . '');


    }


   public function edit( Request $request , Beli $beli)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/kas')
			       ->with('status', 'Maaf Periode sudah ditutup!')
                   ->with(['judul' => $judul, 'flagz' => $FLAGZ]);
        }
		
		$this->setFlag($request);
		
        $tipx = $request->tipx;

		$idx = $request->idx;
			

		
		if ( $idx =='0' && $tipx=='undo'  )
	    {
			$tipx ='top';
			
		   }
		   
		 
		   
		if ($tipx=='search') {
			
		   	
    	   $buktix = $request->buktix;
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from beli
		                 where PER ='$per' and FLAG ='$this->FLAGZ' 
						 and GOL ='$this->GOLZ' and NO_BUKTI = '$buktix'						 
		                 ORDER BY NO_BUKTI ASC  LIMIT 1" );
						 
			
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
		
					
		}
		
		if ($tipx=='top') {
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from beli 
		                 where PER ='$per' and GOL ='$this->GOLZ'
						 and FLAG ='$this->FLAGZ'    
		                 ORDER BY NO_BUKTI ASC  LIMIT 1" );
						 
		
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
		
					
		}
		
		
		if ($tipx=='prev' ) {
			
    	   $buktix = $request->buktix;
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from beli     
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and FLAG ='$this->FLAGZ'  and NO_BUKTI < 
					 '$buktix' ORDER BY NO_BUKTI DESC LIMIT 1" );
			

			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = $idx; 
			  }
			  
		}
		
		
		if ($tipx=='next' ) {
			
				
      	   $buktix = $request->buktix;
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from beli    
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and FLAG ='$this->FLAGZ' and NO_BUKTI > 
					 '$buktix' ORDER BY NO_BUKTI ASC LIMIT 1" );
					 
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = $idx; 
			  }
			  
			
		}

		if ($tipx=='bottom') {
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_BUKTI from beli
						where PER ='$per' and GOL ='$this->GOLZ' 
						and FLAG ='$this->FLAGZ'   
		              ORDER BY NO_BUKTI DESC  LIMIT 1" );
					 
			if(!empty($bingco)) 
			{
				$idx = $bingco[0]->NO_ID;
			  }
			else
			{
				$idx = 0; 
			  }
			  
			
		}

        
		if ( $tipx=='undo' || $tipx=='search' )
	    {
        
			$tipx ='edit';
			
		   }
		
		

       	if ( $idx != 0 ) 
		{
			$beli = Beli::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$beli = new Beli;
                $beli->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $beli->NO_BUKTI;
				
		$data = [
            'header'        => $beli,

        ];
 
         
         return view('otransaksi_beli.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'flagz' => $this->FLAGZ, 'judul'=> $this->judul ]);
			 
    
      
    }




    public function update(Request $request, Beli $beli)
    {
        $this->validate(
            $request,
            [
                'TGL'      => 'required',

            ]
        );



		

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;	
		

		if ( $FLAGZ == 'BL' ) {	
		
			// $variablell = DB::select('call belidel(?)', array($beli['NO_BUKTI']));

			
		} else if ( $FLAGZ == 'UM' ) {

            //$variablell = DB::select('call umdel(?,?)', array($beli['NO_BUKTI'], '0'));


        } else if ( $FLAGZ == 'TH' ) {
            //$variablell = DB::select('call thutdel(?)', array($beli['NO_BUKTI']));

        }
		
		

        $beli->update(
            [
                'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
				'NO_BL'            => ($request['NO_PO'] == null) ? "" : $request['NO_BL'],
                'NO_PO'            => ($request['NO_PO'] == null) ? "" : $request['NO_PO'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'             => (float) str_replace(',', '', $request['SISA']),			
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'LAIN'             => (float) str_replace(',', '', $request['LAIN']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'RPRATE'           => (float) str_replace(',', '', $request['RPRATE']),
                'RPHARGA'          => (float) str_replace(',', '', $request['HARGA']),
                'RPLAIN'           => (float) str_replace(',', '', $request['RPLAIN']),				
                'RPTOTAL'          => (float) str_replace(',', '', $request['TOTAL']),
				'RPSISA'           => (float) str_replace(',', '', $request['RPSISA']),			
                'ACNOA'             => ($request['ACNOA'] == null) ? "" : $request['ACNOA'],				
				'NACNOA'            => ($request['NACNOA'] == null) ? "" : $request['NACNOA'],
                'BACNO'              => ($request['BACNO'] == null) ? "" : $request['BACNO'],
                'BNAMA'               => ($request['BNAMA'] == null) ? "" : $request['BNAMA'],				
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
				
				'TRUCK'            => ($request['TRUCK'] == null) ? "" : $request['TRUCK'],
                'BA'               => (float) str_replace(',', '', $request['BA']),
                'BP'               => (float) str_replace(',', '', $request['BP']),
                'BAG'              => (float) str_replace(',', '', $request['BAG']),
                'KA'               => (float) str_replace(',', '', $request['KA']),
                'REF'              => (float) str_replace(',', '', $request['REF']),
                'RP'               => (float) str_replace(',', '', $request['RP']),
                'JUMREF'           => (float) str_replace(',', '', $request['JUMREF']),
                'KG1'             => (float) str_replace(',', '', $request['KG1']),
                'POT2'            => (float) str_replace(',', '', $request['POT2']),
                'KGBAG'             => (float) str_replace(',', '', $request['KGBAG']),
                'POT'            => (float) str_replace(',', '', $request['POT']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'GUDANG'            => ($request['GUDANG'] == null) ? "" : $request['GUDANG'],
               'KAPAL'            => ($request['KAPAL'] == null) ? "" : $request['KAPAL'],
				
                'TG_SMP'           => Carbon::now()
            ]
        );

		$no_buktix = $beli->NO_BUKTI;
		
        DB::SELECT("UPDATE beli, po SET  beli.SISA = po.SISA WHERE beli.NO_PO=po.NO_PO;");

		if ( $FLAGZ == 'BL' ) {	
		
			// $variablell = DB::select('call beliins(?)', array($beli['NO_BUKTI']));
			
		} else if ( $FLAGZ == 'UM' ) {
          
    	    //$variablell = DB::select('call umins(?,?)', array($beli['NO_BUKTI'], 'X'));

        } else if ( $FLAGZ == 'TH' ) {
            //$variablell = DB::select('call thutins(?)', array($beli['NO_BUKTI']));
        }
		
		

		$beli = Beli::where('NO_BUKTI', $no_buktix )->first();
					 
	// 	return redirect('/beli?flagz='.$FLAGZ.'&golz='.$GOLZ)
	//    ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ]);

    return redirect('/beli/edit/?idx=' . $beli->NO_ID . '&tipx=edit&flagz=' . $this->FLAGZ . '&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');

    }

    public function destroy( Request $request, Beli $beli)
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect()->route('beli')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ]);
				
        }
				
				
				
				
				
		if ( $FLAGZ == 'BL' ) {	
		
			// $variablell = DB::select('call belidel(?)', array($beli['NO_BUKTI']));
			
		} else if ( $FLAGZ == 'UM' ) {
           
		   //$variablell = DB::select('call umdel(?,?)', array($beli['NO_BUKTI'], '1'));

        } else if ( $FLAGZ == 'TH' ) {
            //$variablell = DB::select('call thutdel(?)', array($beli['NO_BUKTI']));
        }
		
        $deleteBeli = Beli::find($beli->NO_ID);
        $deleteBeli->delete();

		return redirect('/beli?flagz='.$FLAGZ.'&golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ, 'flagz' => $FLAGZ ])
			   ->with('statusHapus', 'Data '.$beli->NO_BUKTI.' berhasil dihapus');



    }

    public function repost(Beli $beli)
    {
        DB::SELECT("UPDATE beli SET POSTED=0 WHERE NO_ID=".$beli->NO_ID." AND FLAG in ('BD','BN')");
        return redirect('/belin')->with('status', 'Data '.$beli->NO_BUKTI.' berhasil dibuka posting');
    }
	
	public function jsbelic(Beli $beli)
    {
        $no_po = $beli->NO_PO;

        $file     = 'belic';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $query = DB::SELECT("
			SELECT NO_PO,  TGL, KODES, NAMAS, KD_BRG, NA_BRG, KG, HARGA, TOTAL, NOTES
			FROM po 
			WHERE po.NO_PO='$no_po' 
			ORDER BY NO_PO;
		");
		
		$xno_po1   = $query[0]->NO_PO;
        $xtgl1     = $query[0]->TGL;
        $xkodes1   = $query[0]->KODES;
        $xnamas1   = $query[0]->NAMAS;
        $xnotes1   = $query[0]->NOTES;
        $xkd_brg1  = $query[0]->KD_BRG;
        $xna_brg1  = $query[0]->NA_BRG;
        $xkg1      = $query[0]->KG;
        $xharga1   = $query[0]->HARGA;
        $xtotal1   = $query[0]->TOTAL;
        
        $PHPJasperXML->arrayParameter = array("HARGA1" => (float) $xharga1, "TOTAL1" => (float) $xtotal1, "KG1" => (float) $xkg1, 
										"NO_PO1" => (string) $xno_po1, "TGL1" => (string) $xtgl1,  
										"KODES1" => (string) $xkodes1,  "NAMAS1" => (string) $xnamas1,  "KD_BRG1" => (string) $xkd_brg1,  
										"NA_BRG1" => (string) $xna_brg1,  "NOTES1" => (string) $xnotes1 );
        $PHPJasperXML->arraysqltable = array();


        $query2 = DB::SELECT("
			SELECT NO_BUKTI, NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT,  IF ( FLAG='BL' , 'A','B' ) AS FLAG, 
					AJU, BL, EMKL, KD_BRG, NA_BRG, KG, RPHARGA AS HARGA, RPTOTAL AS TOTAL, 0 AS BAYAR,  NOTES, TRUCK, BAG, KA, REF, KG1, BP
			FROM beli 
			WHERE beli.NO_PO='$no_po'  UNION ALL 
			SELECT NO_BUKTI, NO_PO, TGL, KODES, NAMAS, if(ALAMAT='','NOT-FOUND.png',ALAMAT) as ALAMAT,  'C' AS FLAG, '' AS AJU, '' AS BL, 
					'' AS EMKL,  '' AS KD_BRG, '' AS NA_BRG, 0 AS KG, 
			0 AS HARGA, 0 AS TOTAL, BAYAR, NOTES, '' AS TRUCK, '' AS BAG, '' AS KA, '' AS REF, '' AS KG1, '' AS BP
			FROM hut 
			WHERE hut.NO_PO='$no_po' 
			ORDER BY TGL, FLAG, NO_PO;
		");

        $data = [];

        foreach ($query2 as $key => $value) {
            array_push($data, array(
                'NO_PO' => $query2[$key]->NO_PO,
                'NO_BUKTI' => $query2[$key]->NO_BUKTI,
                'TGL'      => $query2[$key]->TGL,
                'TRUCK'    => $query2[$key]->TRUCK,
                'NAMAS'    => $query2[$key]->NAMAS,
                'ALAMAT'    => $query2[$key]->ALAMAT,
                'BAG'    => $query2[$key]->BAG,
                'KA'       => $query2[$key]->KA,
                'REF'    => $query2[$key]->REF,
                'KG'       => $query2[$key]->KG,
                'KG1'       => $query2[$key]->KG1,
                'BP'       => $query2[$key]->BP,
                'HARGA'    => $query2[$key]->HARGA,
                'TOTAL'    => $query2[$key]->TOTAL,
                'BAYAR'    => $query2[$key]->BAYAR,
                'NOTES'    => $query2[$key]->NOTES
            ));
        }
		
        $PHPJasperXML->setData($data);
        ob_end_clean();
        $PHPJasperXML->outpage("I");
    }
}