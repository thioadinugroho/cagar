<?php

include "../config.php";
header('Content-type: text/json');
// membaca refno dari GET request
$namaKomoditas 	= isKomoditiMatch(strtolower($_GET['namaKomoditas']),'nama',$apicon); 	// pasar
$jenisKomoditas = isKomoditiMatch(strtolower($_GET['jenisKomoditas']),'jenis',$apicon);	
$kodepos 		= $_GET['kodePos'];			
$jenisSentra 	= strtolower($_GET['jenisSentra']);	
// membaca session dari GET request
$callback		= $_GET['cb'];

goLog('search',$_GET,$apicon);

if(empty($namaKomoditas) || empty($jenisKomoditas) || empty($kodepos) || empty($jenisSentra))
{
	$log=array('status_code'=>'101', 'status'=>'All data is required','data'=>$_GET);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';
}
else
{
	$sql = "
		SELECT 
			ROUND(AVG(harga)) AS harga, nama_sentra
		FROM
			".$thedatabase.".komoditi_raw
		WHERE
			timestamp LIKE '".date('Y-m-d')."%' AND
			jenis_sentra LIKE '".$jenisSentra."' AND
			jenis_komoditi LIKE '".$jenisKomoditas."' AND
			nama_komoditi LIKE '".$namaKomoditas."'
		GROUP BY
			nama_sentra
		ORDER BY 
			harga ASC
		LIMIT 0,5
	";

	$query = mysql_query($sql,$apicon);
	//$dataquery  = mysql_fetch_array($query);
	//$hargaProdusen = $dataquery3['harga'];
	$komoditi = array();
	$i=0;
	while($row = mysql_fetch_assoc($query)) {
		$komoditi[$i]['harga'] = is_null($row['harga'])?0:$row['harga'];
		$komoditi[$i]['nama_sentra'] = $row['nama_sentra'];
		$i++;
	}
	
	$log=array('status_code'=>'000', 'status'=>'Success','komoditas'=>$komoditi,'nama_komoditas'=>$namaKomoditas,'jenis_komoditas'=>$jenisKomoditas,'jenis_sentra'=>$jenisSentra);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';

}
echo $data;

?>
