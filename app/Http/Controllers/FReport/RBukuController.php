<?php

namespace App\Http\Controllers\FReport;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\FMaster\Account;

use Illuminate\Http\Request;
use DataTables;
use Auth;
use DB;

include_once base_path()."/vendor/simitgroup/phpjasperxml/version/1.1/PHPJasperXML.inc.php";
use PHPJasperXML;

use \koolreport\laravel\Friendship;
use \koolreport\bootstrap4\Theme;

class RBukuController extends Controller
{
    public function report()
    {
		$acno = Account::orderBy('acno')->get();
		session()->put('filter_tglDari', date("d-m-Y"));
		session()->put('filter_tglSampai', date("d-m-Y"));
		session()->put('filter_acno1', '');
		session()->put('filter_acno2', '');
		session()->put('filter_nacc1', '');
		session()->put('filter_nacc2', '');

        return view('freport_buku.report')->with(['acno' => $acno])->with(['hasil' => []]);
		
    }
	
	public function getBukuReport(Request $request)
    {
			
		$periode = $request->session()->get('periode')['bulan']. '/' . $request->session()->get('periode')['tahun'];
		$bulan = substr($periode,0,2);
		$tahun = substr($periode,3,4);
		$acno = '';
		$tgawal = $tahun.'-'.$bulan.'-01';
			$tglDrD = date("Y-m-d", strtotime($request->tglDr));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
		
		if ($request->ajax())
		{
			// Ganti format tanggal input agar sama dengan database
			$tglDrD = date("Y-m-d", strtotime($request['tglDr']));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
			
			// Convert tanggal agar ambil start of day/end of day
			$tglDr = Carbon::parse($request->tglDr)->startOfDay();
            $tglSmp = Carbon::parse($request->tglSmp)->endOfDay();
			
			// Check Filter
			/*
			if (!empty($request->acno))
			{
				$query = $query->where('BACNO', $request->acno);
			}
			
			if (!empty($request->tglDr) && !empty($request->tglSmp))
			{
				$query = $query->whereBetween('TGL', [$tglDrD, $tglSmp]);
			}
			*/
			
			$periode = date("m/Y", strtotime($request['tglDr']));
			$bulan = date("m", strtotime($request['tglDr']));
			$tahun = date("Y", strtotime($request['tglDr']));
			$acno = $request->acno;
			$tgawal = $tahun.'-'.$bulan.'-01';
		}
		
		$queryakum = DB::SELECT("SET @akum:=0 ;");
		$query = DB::SELECT("
		SELECT *,if(urutan = 1,@akum:=AWAL,@akum:=@akum+AWAL+DEBET-KREDIT) as SALDO, URUTAN from
		(
			SELECT '' AS NO_BUKTI, DATE_FORMAT($tglDrD,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, BACNO, BNAMA, '' AS ACNO, '' AS NACNO, '' AS KODE, '' AS NAMA,
			'SALDO AWAL' URAIAN,
			SUM(AWAL) AS AWAL, 0 AS DEBET, 0 AS KREDIT , 1 as URUTAN, time('00:00:00') as WAKTU
			from
			(

				SELECT ACNO AS BACNO, NAMA AS BNAMA , AW$bulan AS AWAL 
				from accountd WHERE YER='$tahun' and ACNO between '$acno' and '$acno2' union all
				
               				
				SELECT kas.BACNO, kas.BNAMA, SUM(kasd.DEBET-kasd.KREDIT) AS AWAL 
				from kas, kasd where kas.NO_BUKTI = kasd.NO_BUKTI and kas.TGL<'$tglDrD' 
				and  kas.PER='$periode' and kas.BACNO between '$acno' and '$acno2' GROUP BY KAS.BACNO union all
				
				SELECT kasd.ACNO AS BACNO, kasd.NACNO AS BNAMA, SUM(kasd.DEBET-kasd.KREDIT) AS AWAL 
				from kas, kasd where kas.NO_BUKTI = kasd.NO_BUKTI and kas.TGL<'$tglDrD' 
				and  kas.PER='$periode' and kasd.ACNO between '$acno' and '$acno2' GROUP BY KASD.ACNO union all
				
				
				SELECT bank.BACNO,  bank.BNAMA, SUM(bankd.DEBET-bankd.KREDIT) AS AWAL 
				from bank, bankd where bank.NO_BUKTI = bankd.NO_BUKTI and bank.TGL<'$tglDrD' 
				and  bank.PER='$periode' and bank.BACNO between '$acno' and '$acno2' GROUP BY BANK.BACNO union all

				SELECT bankd.ACNO AS BACNO, bankd.NACNO as BNAMA, SUM(bankd.DEBET-bankd.KREDIT) AS AWAL 
				from bank, bankd where bank.NO_BUKTI = bankd.NO_BUKTI and bank.TGL<'$tglDrD' 
				and  bank.PER='$periode' and bankd.ACNO between '$acno' and '$acno2' GROUP BY BANKD.ACNO union all
				
				SELECT memod.ACNO AS BACNO, memod.NACNO as BNAMA, SUM(memod.DEBET-memod.KREDIT) AS AWAL 
				from memo, memod where memo.NO_BUKTI = memod.NO_BUKTI aNd memo.TGL<'$tglDrD' 
				and memo.PER='$periode' and memod.ACNO between '$acno' and '$acno2' GROUP BY MEMOD.ACNO 
				
			) as AWAL00 GROUP BY BACNO, BNAMA
	
				
			UNION ALL
			
			SELECT kas.NO_BUKTI, DATE_FORMAT(kas.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, kas.BACNO, kas.BNAMA, kasd.ACNO, kasd.NACNO,  KAS.KODE, KAS.NAMA,  KASD.URAIAN , 0 AWAL, kasd.DEBET, kasd.KREDIT , IF (kasd.DEBET <> 0, 2, 7) as URUTAN, time(kas.TG_SMP) as WAKTU
			from kas, kasd where kas.NO_BUKTI=kasd.NO_BUKTI and kas.PER='$periode' and kas.TGL BETWEEN '$tglDrD' and '$tglSmpD' and kas.BACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT kas.NO_BUKTI, DATE_FORMAT(kas.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, kasd.ACNO AS BACNO, kasd.NACNO AS BNAMA, kas.BACNO AS ACNO, kas.BNAMA AS NACNO,KAS.KODE, KAS.NAMA,  KASD.URAIAN , 0 AWAL, kasd.KREDIT AS DEBET, kasd.DEBET AS KREDIT , IF (kasd.DEBET <> 0, 3, 8) as URUTAN, time(kas.TG_SMP) as WAKTU
			from kas, kasd where kas.NO_BUKTI=kasd.NO_BUKTI and kas.PER='$periode'  and kas.TGL BETWEEN '$tglDrD' and '$tglSmpD' and kasd.ACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT bank.NO_BUKTI, DATE_FORMAT(bank.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, bank.BACNO, bank.BNAMA, bankd.ACNO, bankd.NACNO, BANK.KODE, BANK.NAMA,  BANKD.URAIAN , 0 AWAL, bankd.DEBET, bankd.KREDIT , IF (bankd.DEBET <> 0, 4, 9) as URUTAN, time(bank.TG_SMP) as WAKTU
			from bank, bankd where bank.NO_BUKTI=bankd.NO_BUKTI and bank.PER='$periode' and bank.TGL BETWEEN '$tglDrD' and '$tglSmpD' and bank.BACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT bank.NO_BUKTI, DATE_FORMAT(bank.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, bankd.ACNO AS BACNO, bankd.NACNO AS BNAMA, bank.BACNO AS ACNO, bank.BNAMA AS NACNO, BANK.KODE, BANK.NAMA,  BANKD.URAIAN , 0 AWAL, bankd.KREDIT AS DEBET, bankd.DEBET AS KREDIT , IF (bankd.DEBET <> 0, 5, 10)  as URUTAN, time(bank.TG_SMP) as WAKTU
			from bank, bankd where bank.NO_BUKTI=bankd.NO_BUKTI and bank.PER='$periode' and bank.TGL BETWEEN '$tglDrD' and '$tglSmpD' and bankd.ACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT memo.NO_BUKTI, DATE_FORMAT(memo.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, 
			memod.ACNO as BACNO, memod.NACNO as BNAMA, ' ' AS ACNO, ' ' AS NACNO,  MEMO.KODE, MEMO.NAMA, 
			MEMOD.URAIAN , 0 AWAL, memod.DEBET, 0 as KREDIT, 6  as URUTAN, time(memo.TG_SMP) as WAKTU
			from memo, memod where memo.NO_BUKTI=memod.NO_BUKTI and memo.PER='$periode' and memo.TGL BETWEEN '$tglDrD' 
			and '$tglSmpD' and memod.ACNO between '$acno' and '$acno2' and memod.debet <> 0
			
			UNION ALL
			
			SELECT memo.NO_BUKTI, DATE_FORMAT(memo.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK,
			memod.ACNO AS BACNO, memod.NACNO AS BNAMA, ' ' AS ACNO, ' ' AS NACNO,  MEMO.KODE, MEMO.NAMA,
			MEMOD.URAIAN , 0 AWAL, 0 as DEBET, memod.KREDIT  , 11   as URUTAN, time(memo.TG_SMP) as WAKTU
			from memo, memod where memo.NO_BUKTI=memod.NO_BUKTI and memo.PER='$periode' and memo.TGL BETWEEN '$tglDrD'
			and '$tglSmpD' and memod.ACNO between '$acno' and '$acno2'  and memod.kredit <>0			
			ORDER BY BACNO,TGL,WAKTU, URUTAN, NO_BUKTI
		)
		as rbuku ;"
		);

		
		return Datatables::of($query)->addIndexColumn()->make(true);
		
    }	 
	 


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


	public function jasperBukuReport(Request $request) 
	{
		$file 	= 'buku';
		$PHPJasperXML = new PHPJasperXML();
		$PHPJasperXML->load_xml_file(base_path().('/app/reportc01/phpjasperxml/'.$file.'.jrxml'));

			$tglDrD = date("Y-m-d", strtotime($request['tglDr']));
            $tglSmpD = date("Y-m-d", strtotime($request['tglSmp']));
			
			// Convert tanggal agar ambil start of day/end of day
			$tglDr = Carbon::parse($request->tglDr)->startOfDay();
            $tglSmp = Carbon::parse($request->tglSmp)->endOfDay();

			
        $periode = date("m/Y", strtotime($request['tglDr']));
		$bulan = date("m", strtotime($request['tglDr']));
		$tahun = date("Y", strtotime($request['tglDr']));
		$acno = $request->acno1;
		$acno2 = $request->acno2;

		session()->put('filter_tglDari', $request->tglDr);
		session()->put('filter_tglSampai', $request->tglSmp);
		session()->put('filter_acno1', $request->acno1);
		session()->put('filter_acno2', $request->acno2);
		session()->put('filter_nacc1', $request->nacc1);
		session()->put('filter_nacc2', $request->nacc2);
		
		$queryakum = DB::SELECT("SET @akum:=0 ;");
		$query = DB::SELECT("
		SELECT *,if(urutan = 1,@akum:=AWAL,@akum:=@akum+AWAL+DEBET-KREDIT) as SALDO, URUTAN from
		(
			SELECT '' AS NO_BUKTI, DATE_FORMAT($tglDrD,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, BACNO, BNAMA, '' AS ACNO, '' AS NACNO, '' AS KODE, '' AS NAMA,
			'SALDO AWAL' URAIAN,
			SUM(AWAL) AS AWAL, 0 AS DEBET, 0 AS KREDIT , 1 as URUTAN, time('00:00:00') as WAKTU
			from
			(

				SELECT ACNO AS BACNO, NAMA AS BNAMA , AW$bulan AS AWAL 
				from accountd WHERE YER='$tahun' and ACNO between '$acno' and '$acno2' union all
				
               				
				SELECT kas.BACNO, kas.BNAMA, SUM(kasd.DEBET-kasd.KREDIT) AS AWAL 
				from kas, kasd where kas.NO_BUKTI = kasd.NO_BUKTI and kas.TGL<'$tglDrD' 
				and  kas.PER='$periode' and kas.BACNO between '$acno' and '$acno2' GROUP BY KAS.BACNO union all
				
				SELECT kasd.ACNO AS BACNO, kasd.NACNO AS BNAMA, SUM(kasd.DEBET-kasd.KREDIT) AS AWAL 
				from kas, kasd where kas.NO_BUKTI = kasd.NO_BUKTI and kas.TGL<'$tglDrD' 
				and  kas.PER='$periode' and kasd.ACNO between '$acno' and '$acno2' GROUP BY KASD.ACNO union all
				
				
				SELECT bank.BACNO,  bank.BNAMA, SUM(bankd.DEBET-bankd.KREDIT) AS AWAL 
				from bank, bankd where bank.NO_BUKTI = bankd.NO_BUKTI and bank.TGL<'$tglDrD' 
				and  bank.PER='$periode' and bank.BACNO between '$acno' and '$acno2' GROUP BY BANK.BACNO union all

				SELECT bankd.ACNO AS BACNO, bankd.NACNO as BNAMA, SUM(bankd.DEBET-bankd.KREDIT) AS AWAL 
				from bank, bankd where bank.NO_BUKTI = bankd.NO_BUKTI and bank.TGL<'$tglDrD' 
				and  bank.PER='$periode' and bankd.ACNO between '$acno' and '$acno2' GROUP BY BANKD.ACNO union all
				
				SELECT memod.ACNO AS BACNO, memod.NACNO as BNAMA, SUM(memod.DEBET-memod.KREDIT) AS AWAL 
				from memo, memod where memo.NO_BUKTI = memod.NO_BUKTI aNd memo.TGL<'$tglDrD' 
				and memo.PER='$periode' and memod.ACNO between '$acno' and '$acno2' GROUP BY MEMOD.ACNO 
				
			) as AWAL00 GROUP BY BACNO, BNAMA
	
				
			UNION ALL
			
			SELECT kas.NO_BUKTI, DATE_FORMAT(kas.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, kas.BACNO, kas.BNAMA, kasd.ACNO, kasd.NACNO,  KAS.KODE, KAS.NAMA,  KASD.URAIAN , 0 AWAL, kasd.DEBET, kasd.KREDIT , IF (kasd.DEBET <> 0, 2, 7) as URUTAN, time(kas.TG_SMP) as WAKTU
			from kas, kasd where kas.NO_BUKTI=kasd.NO_BUKTI and kas.PER='$periode' and kas.TGL BETWEEN '$tglDrD' and '$tglSmpD' and kas.BACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT kas.NO_BUKTI, DATE_FORMAT(kas.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, kasd.ACNO AS BACNO, kasd.NACNO AS BNAMA, kas.BACNO AS ACNO, kas.BNAMA AS NACNO,KAS.KODE, KAS.NAMA,  KASD.URAIAN , 0 AWAL, kasd.KREDIT AS DEBET, kasd.DEBET AS KREDIT , IF (kasd.DEBET <> 0, 3, 8) as URUTAN, time(kas.TG_SMP) as WAKTU
			from kas, kasd where kas.NO_BUKTI=kasd.NO_BUKTI and kas.PER='$periode'  and kas.TGL BETWEEN '$tglDrD' and '$tglSmpD' and kasd.ACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT bank.NO_BUKTI, DATE_FORMAT(bank.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, bank.BACNO, bank.BNAMA, bankd.ACNO, bankd.NACNO, BANK.KODE, BANK.NAMA,  BANKD.URAIAN , 0 AWAL, bankd.DEBET, bankd.KREDIT , IF (bankd.DEBET <> 0, 4, 9) as URUTAN, time(bank.TG_SMP) as WAKTU
			from bank, bankd where bank.NO_BUKTI=bankd.NO_BUKTI and bank.PER='$periode' and bank.TGL BETWEEN '$tglDrD' and '$tglSmpD' and bank.BACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT bank.NO_BUKTI, DATE_FORMAT(bank.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, bankd.ACNO AS BACNO, bankd.NACNO AS BNAMA, bank.BACNO AS ACNO, bank.BNAMA AS NACNO, BANK.KODE, BANK.NAMA,  BANKD.URAIAN , 0 AWAL, bankd.KREDIT AS DEBET, bankd.DEBET AS KREDIT , IF (bankd.DEBET <> 0, 5, 10)  as URUTAN, time(bank.TG_SMP) as WAKTU
			from bank, bankd where bank.NO_BUKTI=bankd.NO_BUKTI and bank.PER='$periode' and bank.TGL BETWEEN '$tglDrD' and '$tglSmpD' and bankd.ACNO between '$acno' and '$acno2'
			
			UNION ALL
			
			SELECT memo.NO_BUKTI, DATE_FORMAT(memo.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK, 
			memod.ACNO as BACNO, memod.NACNO as BNAMA, ' ' AS ACNO, ' ' AS NACNO,  MEMO.KODE, MEMO.NAMA, 
			MEMOD.URAIAN , 0 AWAL, memod.DEBET, 0 as KREDIT, 6  as URUTAN, time(memo.TG_SMP) as WAKTU
			from memo, memod where memo.NO_BUKTI=memod.NO_BUKTI and memo.PER='$periode' and memo.TGL BETWEEN '$tglDrD' 
			and '$tglSmpD' and memod.ACNO between '$acno' and '$acno2' and memod.debet <> 0
			
			UNION ALL
			
			SELECT memo.NO_BUKTI, DATE_FORMAT(memo.TGL,'%d/%m/%Y') AS TGL, DATE_FORMAT(NOW(),'%d/%m/%Y') AS TGL_CETAK,
			memod.ACNO AS BACNO, memod.NACNO AS BNAMA, ' ' AS ACNO, ' ' AS NACNO,  MEMO.KODE, MEMO.NAMA,
			MEMOD.URAIAN , 0 AWAL, 0 as DEBET, memod.KREDIT  , 11   as URUTAN, time(memo.TG_SMP) as WAKTU
			from memo, memod where memo.NO_BUKTI=memod.NO_BUKTI and memo.PER='$periode' and memo.TGL BETWEEN '$tglDrD'
			and '$tglSmpD' and memod.ACNO between '$acno' and '$acno2'  and memod.kredit <>0			
			ORDER BY BACNO,TGL,WAKTU, URUTAN, NO_BUKTI
		)
		as rbuku ;"
		);

		if($request->has('filter'))
		{
		$acno = Account::orderBy('acno')->get();

        return view('freport_buku.report')->with(['acno' => $acno])->with(['hasil' => $query]);
		}

		$data=[];
		foreach ($query as $key => $value)
		{
			array_push($data, array(
				'NO_BUKTI' => $query[$key]->NO_BUKTI,
				'TGL_CETAK' => $query[$key]->TGL_CETAK,
				'TGL' => $query[$key]->TGL,
				'BACNO' => $query[$key]->BACNO,
				'BNAMA' => $query[$key]->BNAMA,
				'ACNO' => $query[$key]->ACNO,
				'NACNO' => $query[$key]->NACNO,
				'URAIAN' => $query[$key]->URAIAN,
				'AWAL' => $query[$key]->AWAL,
				'DEBET' => $query[$key]->DEBET,
				'KREDIT' => $query[$key]->KREDIT,
				'SALDO' => $query[$key]->SALDO,
			));
		}
		$PHPJasperXML->setData($data);
		ob_end_clean();
		$PHPJasperXML->outpage("I");
	}

	
}