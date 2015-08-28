<?php

include "../config.php";
header('Content-type: text/json');
// membaca refno dari GET request
$namaKomoditas 	= strtolower($_GET['namaKomoditas']);
$jenisKomoditas = strtolower($_GET['jenisKomoditas']);
$kodepos 		= $_GET['kodePos'];
$jenisSentra 	= strtolower($_GET['jenisSentra']);
// membaca session dari GET request
$callback		= $_GET['cb'];

goLog('monthly report',$_GET,$apicon);

if(empty($namaKomoditas) || empty($jenisKomoditas) || empty($kodepos) || empty($jenisSentra))
{
	$log=array('status_code'=>'101', 'status'=>'All data is required','data'=>$_GET);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';
}
else
{
	$report = array();
	$delta = 0;
	for($i=1;$i<=30;$i++) {
		$report['pasar'][$i-1] = 0;
		$sql = "
			SELECT 
				ROUND(AVG(harga)) AS harga
			FROM
				".$thedatabase.".komoditi_raw
			WHERE
				timestamp LIKE '".date('Y-m-'.$i)."%' AND
				jenis_sentra LIKE 'pasar'
		";
		$query = mysql_query($sql,$apicon);
		$dataquery  = mysql_fetch_array($query);
		$report['pasar'][$i-1] = is_null($dataquery['harga'])?0:$dataquery['harga'];
		if($i > 1) {
			$delta += $report['pasar'][$i-1] - $report['pasar'][$i-2];
		}

		$report['produsen'][$i-1] = 0;
		$sql = "
			SELECT 
				ROUND(AVG(harga)) AS harga
			FROM
				".$thedatabase.".komoditi_raw
			WHERE
				timestamp LIKE '".date('Y-m-'.$i)."%' AND
				jenis_sentra LIKE 'produsen'
		";
		$query = mysql_query($sql,$apicon);
		$dataquery  = mysql_fetch_array($query);
		$report['produsen'][$i-1] = is_null($dataquery['harga'])?0:$dataquery['harga'];
	
	}
	$inflasi = $delta/($i-1);
	$log=array('status_code'=>'000', 'status'=>'Success','report'=>$report,'inflasi'=>$inflasi);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';

}
echo $data;

?>
