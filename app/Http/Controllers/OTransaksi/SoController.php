<?php

namespace App\Http\Controllers\OTransaksi;

use App\Http\Controllers\Controller;
// ganti 1

use App\Models\OTransaksi\So;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;
use Carbon\Carbon;


include_once base_path() . "/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";

use PHPJasperXML;


// ganti 2
class SoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    var $judul = '';
    var $FLAGZ = '';
    var $GOLZ = '';
	
    function setFlag(Request $request)
    {
        if ( $request->golz == 'K' ) {
            $this->judul = "Sales Order ";
        } else if ( $request->golz == 'L' ) {
            $this->judul = "Sales Order Non";
        }
			
        //$this->FLAGZ = $request->flagz;
        $this->GOLZ = $request->golz;    
		

    }	 


    public function index(Request $request)
    {

        // ganti 3
        $this->setFlag($request);
        // ganti 3
        return view('otransaksi_so.index')->with(['judul' => $this->judul, 'golz' => $this->GOLZ  ]);
    }


    // ganti 4
    // public function browse()
    // {

		
	// 	$so = DB::SELECT("SELECT NO_SO,TGL,  KODEC, NAMAC, ALAMAT, KOTA, KD_BRG, NA_BRG, HARGA, KG, KIRIM, SISA from so
	// 	WHERE YEAR (TGL) >= 2023  AND  GOL='A2' AND SLS='0' ORDER BY KODEC ASC; ");
    //     return response()->json($so); 
    // }

    ///////////////////////////////////////////////////////

    public function browse(Request $request)
    {   

		$no_soz = $request->NO_SO;
        $golz = $request->GOL;

		$filter_no_so='';
		
         if (!empty($request->NO_SO)) {
			
			$filter_no_so = " and NO_SO='".$request->NO_SO."' ";
		} 
		
		    $so = DB::SELECT("SELECT NO_SO,DATE_FORMAT(so.TGL,'%d/%m/%Y') AS TGL,  KODEC, NAMAC, ALAMAT, KOTA, KD_BRG, NA_BRG, HARGA, KG, 
                                    KIRIM, SISA, NOTES from so
                        WHERE YEAR (TGL) >= 2023  AND  GOL='$golz' AND SLS='0' $filter_no_so
                        ORDER BY KODEC ASC ");
						
		if	( empty($so) ) {
			
			$so = DB::SELECT("SELECT NO_SO,DATE_FORMAT(so.TGL,'%d/%m/%Y') AS TGL,  KODEC, NAMAC, ALAMAT, KOTA, KD_BRG, NA_BRG, HARGA, KG, 
                                    KIRIM, SISA, NOTES from so
                        WHERE YEAR (TGL) >= 2023  AND  GOL='$golz' AND SLS='0'
                        ORDER BY KODEC ASC ");			
		}

		
        return response()->json($so);
    }

    ///////////////////////////////////////////////////////

    

    public function browseuang(Request $request)
    {
		// $so = DB::SELECT("SELECT NO_SO,TGL,  KODEC, NAMAC, NA_BRG, HARGA, KG, KIRIM, SISA AS XSISA, PERJ AS TOTAL, PERJB AS BAYAR, (PERJ-PERJB) AS SISA  from so
		// WHERE LNS <> 1 AND  GOL='A2' ORDER BY NO_SO; ");

        // return response()->json($so);

		$no_soz = $request->NO_SO;
        $golz = $request->GOL;

		$filter_no_so='';
		
         if (!empty($request->NO_SO)) {
			
			$filter_no_so = " and NO_SO='".$request->NO_SO."' ";
		} 
		
			$so = DB::SELECT("SELECT NO_SO,TGL,  KODEC, NAMAC, NA_BRG, HARGA, KG, KIRIM, SISA AS XSISA  from so
                        WHERE LNS <> 1 AND  GOL='$golz' $filter_no_so
                        ORDER BY NO_SO ASC ");
						
		if	( empty($so) ) {
			
			$so = DB::SELECT("SELECT NO_SO,TGL,  KODEC, NAMAC, NA_BRG, HARGA, KG, KIRIM, SISA AS XSISA from so
                        WHERE LNS <> 1 AND  GOL='$golz'
                        ORDER BY NO_SO ASC ");			
		}

		
        return response()->json($so);
    }

    public function browseisi(Request $request)
    {
		$so = DB::SELECT("SELECT NO_SO AS NO_SO, NO_BUKTI AS NO_FAKTUR, TGL AS TGL_FAKTUR, TOTAL, 0 AS BAYAR 
                        FROM jual 
                        WHERE NO_SO ='$request->NO_SO'
                        
                        UNION ALL
						
                        SELECT NO_SO AS NO_SO, NO_BUKTI AS NO_FAKTUR, TGL AS TGL_FAKTUR, TOTAL, BAYAR AS BAYAR 
                        FROM piu  
                        WHERE NO_SO ='$request->NO_SO' ORDER BY TGL_FAKTUR  ");

        return response()->json($so);
    }


    public function getSo(Request $request)
    {
        // ganti 5

        if ($request->session()->has('periode')) {
            $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];
        } else {
            $periode = '';
        }


        //$so = DB::table('so')->select('*')->where('PER', $periode)->where('GOL', 'Y')->orderBy('NO_SO', 'ASC')->get();
        $this->setFlag($request);
		
        $so = DB::SELECT("SELECT * from so  where  PER ='$periode' and GOL ='$this->GOLZ'  ORDER BY NO_SO ");
	

        return Datatables::of($so)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                if (Auth::user()->divisi=="programmer" || Auth::user()->divisi=="owner" || Auth::user()->divisi=="assistant" || Auth::user()->divisi=="penjualan") 
                {

                    $btnEdit =   ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_SO . ' sudah diposting!\')" href="#" ' : ' href="so/edit/?idx
					=' . $row->NO_ID . '&tipx=edit&golz=' . $row->GOL . '&judul=' . $this->judul . '"';
					
                    $btnDelete = ($row->POSTED == 1) ? ' onclick= "alert(\'Transaksi ' . $row->NO_SO . ' sudah diposting!\')" href="#" ' : ' onclick="return confirm(&quot; Apakah anda yakin ingin hapus? &quot;)" href="so/delete/' . $row->NO_ID . '/?golz=' . $row->GOL . '" ';


                    $btnPrivilege =
                        '
                                <a class="dropdown-item" ' . $btnEdit . '>
                                <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <a class="dropdown-item btn btn-danger" href="jssoc/' . $row->NO_ID . '">
                                    <i class="fa fa-print" aria-hidden="true"></i>
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {


        $this->validate(
            $request,
            // GANTI 9

            [

                'TGL'      => 'required',
                'KODEC'       => 'required',
                'KD_BRG'       => 'required'

            ]
        );

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
        //////     nomer otomatis
  
        $periode = $request->session()->get('periode')['bulan'] . '/' . $request->session()->get('periode')['tahun'];

        $bulan    = session()->get('periode')['bulan'];
        $tahun    = substr(session()->get('periode')['tahun'], -2);
        // $query = DB::table('so')->select('NO_SO')->where('PER', $periode)->orderByDesc('NO_SO')->limit(1)->get();
        

        $query = DB::table('so')->select(DB::raw("TRIM(NO_SO) AS NO_SO"))->where('PER', $periode)->orderByDesc('NO_SO')->limit(1)->get();

		$no_bukti='';
		
        if ($query != '[]') {
            $query = substr($query[0]->NO_SO, -4);
            $query = str_pad($query + 1, 4, 0, STR_PAD_LEFT);
            $no_bukti = 'SK' . $tahun . $bulan . '-' . $query;
        } else {
            $no_bukti = 'SK' . $tahun . $bulan . '-0001';
        }



        // Insert Header

        // set format tgl otomatis TGL jadi 00-00-0000
        $tgl_rubah = $request['TGL'];
        $input_tgl = '';
        if (str_contains($tgl_rubah, '-')) {
            $input_tgl = date('Y-m-d', strtotime($tgl_rubah));
        }else{
            $input_tgl = substr($tgl_rubah, 4, 4)."-".substr($tgl_rubah, 2, 2)."-".substr($tgl_rubah, 0, 2);
        }
        ///

        // ganti 10

        $so = So::create(
            [
                'NO_SO'         => $no_bukti,

                // 'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                // set format tgl otomatis
                'TGL'              => $input_tgl,
                //

                'JTEMPO'              => date('Y-m-d', strtotime($request['JTEMPO'])),
                'PER'              => $periode,
                'RPRATE'           => '1',
                'GOL'              => $GOLZ,
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'ALAMAT'            => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'            => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
				
                'NO_ORDER'           => ($request['NO_ORDER'] == null) ? "" : $request['NO_ORDER'],
                'PO'           => ($request['PO'] == null) ? "" : $request['PO'],
				
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'RPHARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'SISA'             => (float) str_replace(',', '', $request['KG']),
                'TOTAL'               => (float) str_replace(',', '', $request['TOTAL']),
                'RPTOTAL'               => (float) str_replace(',', '', $request['TOTAL']),
                'USRNM'            => Auth::user()->username,
                'created_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );



  	    $no_buktix = $no_bukti;

        DB::SELECT("UPDATE so SET  SISA = KG - KIRIM  WHERE  so.NO_SO='$no_buktix';");
		
		$so = So::where('NO_SO', $no_buktix )->first();
					 
        // return redirect('/so?golz='.$GOLZ)
		//        ->with(['judul' => $judul, 'golz' => $GOLZ ]);

        return redirect('/so/edit/?idx=' . $so->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 12

    public function edit( Request $request , So $so)
    {


		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
		
				
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {
            return redirect('/so')
			       ->with('status', 'Maaf Periode sudah ditutup!')
                   ->with(['judul' => $judul, 'golz' => $GOLZ ]);
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
		   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_SO from so
		                 where PER ='$per' and  
						 and GOL ='$this->GOLZ' and NO_SO = '$buktix'						 
		                 ORDER BY NO_SO ASC  LIMIT 1" );
						 
			
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
			

		   $bingco = DB::SELECT("SELECT NO_ID, NO_SO from so
		                 where PER ='$per' and GOL ='$this->GOLZ' 
		                 ORDER BY NO_SO ASC  LIMIT 1" );
						 
		
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
			
		   $bingco = DB::SELECT("SELECT NO_ID, NO_SO from so     
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and NO_SO < '$buktix' ORDER BY NO_SO DESC LIMIT 1" );
			

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
	   
		   $bingco = DB::SELECT("SELECT NO_ID, NO_SO from so    
		             where PER ='$per' and GOL ='$this->GOLZ' 
					 and NO_SO > '$buktix' ORDER BY NO_SO ASC LIMIT 1" );
					 
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
		  
    		$bingco = DB::SELECT("SELECT NO_ID, NO_SO from so where PER ='$per'
            			and GOL ='$this->GOLZ'     
		              ORDER BY NO_SO DESC  LIMIT 1" );
					 
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
			$so = So::where('NO_ID', $idx )->first();	
	     }
		 else
		 {
				$so = new So;
                $so->TGL = Carbon::now();
      
				
		 }

        $no_bukti = $so->NO_SO;
				
		$data = [
            'header'        => $so,

        ];
 
         
         return view('otransaksi_so.edit', $data)
		 ->with(['tipx' => $tipx, 'idx' => $idx, 'golz' =>$this->GOLZ, 'judul'=> $this->judul ]);
			 
    
      
    }



    // ganti 18

    public function update(Request $request, So $so)
    {

        $this->validate(
            $request,
            [

                // ganti 19

                'TGL'      => 'required',
                'KODEC'       => 'required',
                'KD_BRG'       => 'required'
            ]
        );

        // ganti 20
		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		// set format tgl otomatis TGL jadi 00-00-0000
        $tgl_rubah = $request['TGL'];
        $input_tgl = '';
        if (str_contains($tgl_rubah, '-')) {
            $input_tgl = date('Y-m-d', strtotime($tgl_rubah));
        }else{
            $input_tgl = substr($tgl_rubah, 4, 4)."-".substr($tgl_rubah, 2, 2)."-".substr($tgl_rubah, 0, 2);
        }
        ///

        $so->update(
            [
                // 'TGL'              => date('Y-m-d', strtotime($request['TGL'])),
                // set format tgl otomatis
                'TGL'              => $input_tgl,
                //
                
                'JTEMPO'              => date('Y-m-d', strtotime($request['JTEMPO'])),
                'KODEC'            => ($request['KODEC'] == null) ? "" : $request['KODEC'],
                'NAMAC'            => ($request['NAMAC'] == null) ? "" : $request['NAMAC'],
                'ALAMAT'           => ($request['ALAMAT'] == null) ? "" : $request['ALAMAT'],
                'KOTA'             => ($request['KOTA'] == null) ? "" : $request['KOTA'],
                'KD_BRG'           => ($request['KD_BRG'] == null) ? "" : $request['KD_BRG'],
                'NA_BRG'           => ($request['NA_BRG'] == null) ? "" : $request['NA_BRG'],
				
                'NO_ORDER'         => ($request['NO_ORDER'] == null) ? "" : $request['NO_ORDER'],
                'PO'            => ($request['PO'] == null) ? "" : $request['PO'],
				
                'NOTES'            => ($request['NOTES'] == null) ? "" : $request['NOTES'],
                'KG'               => (float) str_replace(',', '', $request['KG']),
                'SISA'             => (float) str_replace(',', '', $request['KG']),
                'HARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'TOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'RPHARGA'            => (float) str_replace(',', '', $request['HARGA']),
                'RPTOTAL'            => (float) str_replace(',', '', $request['TOTAL']),
                'USRNM'            => Auth::user()->username,
                'updated_by'       => Auth::user()->username,
                'TG_SMP'           => Carbon::now()
            ]
        );

        //  ganti 21

		$no_buktix = $so->NO_SO;

        DB::SELECT("UPDATE so SET  SISA = KG - KIRIM  WHERE  so.NO_SO='$no_buktix';");
				
		$so = So::where('NO_SO', $no_buktix )->first();
					 
        // return redirect('/so?golz='.$GOLZ)
		//        ->with(['judul' => $judul, 'golz' => $GOLZ ]);

        return redirect('/so/edit/?idx=' . $so->NO_ID . '&tipx=edit&golz=' . $this->GOLZ . '&judul=' . $this->judul . '');


   }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Master\Rute  $rute
     * @return \Illuminate\Http\Response
     */

    // ganti 22

    public function destroy( Request $request, So $so )
    {

		$this->setFlag($request);
        $FLAGZ = $this->FLAGZ;
        $GOLZ = $this->GOLZ;
        $judul = $this->judul;
		
		
		$per = session()->get('periode')['bulan'] . '/' . session()->get('periode')['tahun'];
        $cekperid = DB::SELECT("SELECT POSTED from perid WHERE PERIO='$per'");
        if ($cekperid[0]->POSTED==1)
        {

            return redirect()->route('so')
                ->with('status', 'Maaf Periode sudah ditutup!')
                ->with(['judul' => $judul, 'golz' => $GOLZ ]);
				
        }
		
        // ganti 23
        $deleteSo = So::find($so->NO_ID);

        // ganti 24

        $deleteSo->delete();

        // ganti 

		return redirect('/so?golz='.$GOLZ)
		       ->with(['judul' => $judul, 'golz' => $GOLZ ])
			   ->with('statusHapus', 'Data '.$so->NO_SO.' berhasil dihapus');
			   
    }

    /////////////////////////////////////////////////////////////////	

    public function jssoc(So $so)
    {
        $no_so = $so->NO_SO;

        $file     = 'soc';
        $PHPJasperXML = new PHPJasperXML();
        $PHPJasperXML->load_xml_file(base_path() . ('/app/reportc01/phpjasperxml/' . $file . '.jrxml'));

        $query = DB::SELECT("
			SELECT '' AS NO_BUKTI, NO_SO, TGL, KODEC, NAMAC, KD_BRG, NA_BRG, KG, HARGA, TOTAL, NOTES
			FROM so 
			WHERE so.NO_SO='$no_so' 
			ORDER BY NO_SO;
		");


        //$xno_so1   = $query->fields["NO_SO"];
        $xno_so1   = $query[0]->NO_SO;
        $xtgl1     = $query[0]->TGL;
        $xkodec1   = $query[0]->KODEC;
        $xnamac1   = $query[0]->NAMAC;
        $xnotes1   = $query[0]->NOTES;
        $xkd_brg1  = $query[0]->KD_BRG;
        $xna_brg1  = $query[0]->NA_BRG;
        $xkg1      = $query[0]->KG;
        $xharga1   = $query[0]->HARGA;
        $xtotal1   = $query[0]->TOTAL;

        $PHPJasperXML->arrayParameter = array("HARGA1" => (double) $xharga1, "TOTAL1" => (double) $xtotal1, "KG1" => (double) $xkg1, "HARGA1" => (double) $xharga1, "NO_SO1" => (string) $xno_so1, "TGL1" => (string) $xtgl1,  "KODEC1" => (string) $xkodec1,  "NAMAC1" => (string) $xnamac1,  "KD_BRG1" => (string) $xkd_brg1,  "NA_BRG1" => (string) $xna_brg1,  "NOTES1" => (string) $xnotes1);
        $PHPJasperXML->arraysqltable = array();



        $query2 = DB::SELECT("
			SELECT NO_BUKTI, NO_SO, TGL, KODEC, NAMAC, IF ( FLAG='JL' , 'A','B' ) AS FLAG, IF ( FLAG ='UJ', 'U.M', TRUCK ) AS TRUCK, KD_BRG, NA_BRG, KG, 
			HARGA, RPTOTAL AS TOTAL, 0 AS BAYAR,  NOTES
			FROM jual 
			WHERE jual.NO_SO='$no_so'  UNION ALL 
			SELECT NO_BUKTI, NO_SO, TGL, KODEC, NAMAC, 'C' AS FLAG, 'BAYAR' AS TRUCK, '' AS KD_BRG, '' AS NA_BRG, 0 AS KG, 
			0 AS HARGA, TOTAL AS TOTAL, BAYAR, NOTES
			FROM piu 
			WHERE piu.NO_SO='$no_so' 
			ORDER BY TGL, NO_BUKTI;
		");


        $data = [];

        foreach ($query2 as $key => $value) {
            array_push($data, array(
                'NO_BUKTI' => $query2[$key]->NO_BUKTI,
                'TGL'      => $query2[$key]->TGL,
                'KODEC'    => $query2[$key]->KODEC,
                'NAMAC'    => $query2[$key]->NAMAC,
                'TRUCK'    => $query2[$key]->TRUCK,				
                'KG'       => $query2[$key]->KG,
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

    public function getSelectSO(Request $request)
    {
        $search = $request->search;
        $page = $request->page;
        if ($page == 0) {
            $xa = 0;
        } else {
            $xa = ($page - 1) * 10;
        }
        $perPage = 10;
        
        $hasil = DB::SELECT("SELECT NO_SO from so WHERE NO_SO LIKE '%$search%' ORDER BY NO_SO LIMIT $xa,$perPage ");
        $selectajax = array();
        foreach ($hasil as $row => $value) {
            $selectajax[] = array(
                'id' => $hasil[$row]->NO_SO,
                'text' => $hasil[$row]->NO_SO,
            );
        }
        $select['total_count'] =  count($selectajax);
        $select['items'] = $selectajax;
        return response()->json($select);
    }
	
}
