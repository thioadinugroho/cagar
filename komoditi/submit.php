<?php

include "../config.php";
header('Content-type: text/json');
// membaca refno dari GET request
$jenisSentra 	= strtolower($_GET['jenisSentra']); 	// pasar
$namaSentra 	= $_GET['namaSentra'];		// ps. baru
$kodePos 		= $_GET['kodePos'];			// 12345
$namaKomoditi 	= strtolower($_GET['namaKomoditas']);	// beras
$jenisKomoditi 	= $_GET['jenisKomoditas'];	// IR.9
$kuantitas 		= $_GET['kuantitas'];		// 50
$harga 			= $_GET['harga'];			// 50000
$satuan 		= strtolower($_GET['satuan']);			// KG
$handphone 		= $_GET['hp'];		// Handphone
// membaca session dari GET request
$callback		= $_GET['cb'];

goLog('submit',$_GET,$apicon);

if(empty($jenisSentra) || empty($namaSentra) || empty($kodePos) ||  empty($namaKomoditi) || empty($jenisKomoditi) || empty($kuantitas) || empty($harga) || empty($satuan))
{
	$log=array('session'=>$sess,'status_code'=>'101', 'status'=>'All data is required', 'dataset'=>$_GET);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';
}
else
{

	$unitPrice = 0;
	if(strtolower($satuan) == 'kg') {
		$unitPrice = $harga/$kuantitas;
	} else if(strtolower($satuan) == 'gr') {
		$unitPrice = $harga*1000/$kuantitas;
	}
	
	// CHECK NATURAL LANGUAGE PROCESSING
	$rawNamaKomoditi = $namaKomoditi;
	$rawJenisKomoditi = $jenisKomoditi;
	$namaKomoditi = isKomoditiMatch($namaKomoditi,$tipe='nama',$apicon);
	$jenisKomoditi = isKomoditiMatch($jenisKomoditi,$tipe='jenis',$apicon);

	// CHECK IF BELOW AVG +- STDDEV
	//$cont = true;
	
	$sql = "
		SELECT AVG(unitprice) AS average, STD(unitprice) AS stddev, COUNT(unitprice) AS count
		FROM
			".$thedatabase.".komoditi_raw
		WHERE
			timestamp LIKE '".date('Y-m-d')."%' AND
			nama_komoditi LIKE '".$namaKomoditi."' AND
			jenis_komoditi LIKE '".$jenisKomoditi."' AND
			jenis_sentra LIKE 'pasar' AND
			kode_pos LIKE '".$kodePos."'
	";

	$query = mysql_query($sql,$apicon);
	$queryhp  = mysql_fetch_array($query);
	$rataan = $queryhp['average'];
	$stddev = $queryhp['stddev'];
	$lowerrange = $rataan - 2*$stddev;
	$highrange = $rataan + 2*$stddev;
	if($queryhp['count'] < 30) {
		$cont = true;
	} else {

		if($unitPrice > $lowerrange && $unitPrice < $highrange) $cont = true;
		else $cont = false;
	}
	
	if(!$cont) {
		$log=array('status_code'=>'102', 'status'=>'Submit Gagal data tidak masuk dalam range sebaran data yang dibolehkan.', 'dataset'=>$_GET);
		if(empty($callback) || is_null($callback)) $data = json_encode($log);
		else $data = $callback.'('.json_encode($log).')';
	} else {

		// CALCULATE POINTS
		$points = 0;
		if(empty($handphone) || is_null($handphone)) {

		} else {
			$sql = "
				SELECT COUNT(*) AS total 
				FROM ".$thedatabase.".user_login
				WHERE handphone LIKE '".$handphone."';
			";
			$query12 = mysql_query($sql,$apicon);
			$queryhp  = mysql_fetch_array($query12);
			if($queryhp['total'] > 0) { 	// IF HP IS EXISTS
				// GET DATA EACH KOMODITI TO CALCULATE HOW MANY POINTS SHOULD BE GIVEN
				$sql = "
					SELECT nama, jenis
					FROM
						".$thedatabase.".komoditi_jenis 
					WHERE 
						nama LIKE '".$namaKomoditi."'
				";
				$komquery = mysql_query($sql);
				$totalKomoditi = array();
				$i=0;
				while($row = mysql_fetch_assoc($komquery)) {
				    $totalKomoditi[$row['jenis']] = 0;
				    $sql = "
						SELECT COUNT(*) AS total
						FROM
							".$thedatabase.".komoditi_raw
						WHERE
							nama_komoditi LIKE '".$namaKomoditi."' AND
							jenis_komoditi LIKE '".$row['jenis']."' AND
							timestamp LIKE '".date('Y-m-d')."%';
				    ";
				    $querykomoditiperday = mysql_query($sql);
				    $totalkomoditiperday  = mysql_fetch_array($querykomoditiperday);
				    $totalKomoditi[$row['jenis']] = $totalkomoditiperday['total'];
					$i++;
				}
				arsort($totalKomoditi);
				$i=0;
				foreach($totalKomoditi as $k => $v) {
					if($k == $jenisKomoditi) $points = $i+1;
					$i++;
				}
			} else {	// IF HP NOT EXIST
				$sql = "
					SELECT COUNT(handphone) AS total 
					FROM ".$thedatabase.".komoditi_raw
					WHERE 
						handphone LIKE '".$handphone."' AND
						nama_sentra LIKE '".$namaSentra."' AND
						jenis_sentra LIKE '".$jenisSentra."' AND
						timestamp LIKE '".date('Y-m-d')."%'
				";
				$query13 = mysql_query($sql,$apicon);
				$querypoint  = mysql_fetch_array($query13);
				if($querypoint['total'] > 0) {		// IF NO DATA HAS BEEN SUBMITTED FROM HP OWNER THIS DAY
					$points = 0;
				} else {	// IF NO DATA OF HP AND KOMODITI HAS BEEN SUBMITTED THIS DAY
					$points = 1;
				}
			}
		}
		
		// INSERT RAW DATA
		$sql = "
			INSERT INTO ".$thedatabase.".komoditi_raw (`jenis_sentra`,`nama_sentra`,`kode_pos`,`nama_komoditi`,`jenis_komoditi`,`raw_nama`,`raw_jenis`,`kuantitas`,`harga`,`satuan`,`unitprice`,`handphone`,`points`)
			VALUES ('".$jenisSentra."','".$namaSentra."','".$kodePos."','".$namaKomoditi."','".$jenisKomoditi."','".$rawNamaKomoditi."','".$rawJenisKomoditi."','".$kuantitas."','".$harga."','".$satuan."','".$unitPrice."','".$handphone."','".$points."');
		";
		$query1 = mysql_query($sql,$apicon);

		// FIND JENIS KOMODITI
		$sql = "
			SELECT id FROM ".$thedatabase.".komoditi_jenis WHERE nama LIKE '".$namaKomoditi."' AND jenis LIKE '".$jenisKomoditi."';
		";
		$query2 = mysql_query($sql);	
		if($query2) {
			$dataquery  = mysql_fetch_array($query2);
			$komoditi_id = $dataquery['id'];
		} else {
			$komoditi_id = '';
		}
		
		// CHECK DISTRIBUSI IS EXISTS?
		$avgPrice = $harga;
		$lat = '';
		$lon = '';
		$sql = "
			SELECT COUNT(kode_pos) AS total 
			FROM ".$thedatabse.".komoditi_distribusi 
			WHERE 
				kode_pos = '".$kodePos."' AND
				timestamp LIKE '".date('Y-m-d')."%'
			;
		";
		$query = mysql_query($sql);
		$dataquery2  = mysql_fetch_array($query);
		$isKodeposExist = $dataquery2['total'];

		$max = $unitPrice;
		$min = $unitPrice;
		$responden = 0;
			
		if($isKodeposExist > 0) {
			// STATISTICS COME IN PLACE 
			$sql = "
				SELECT AVG(unitprice) AS avg, STD(unitprice) AS stddev, MAX(unitprice) AS max, MIN(unitprice) AS min, COUNT(unitprice) AS responden
				FROM 
					".$thedatabase.".komoditi_raw
				WHERE 
					kode_pos = '".$kodePos."' AND
					jenis_sentra LIKE '".$jenisSentra."' AND
					timestamp LIKE '".date('Y-m-d')."%';
			";
			$avquery = mysql_query($sql);
			$avgPrice = 0;
			while($row = mysql_fetch_assoc($avquery)) {
			    $avgPrice += $row["avg"];
			    $stdPrice += $row["stddev"];
			    $max += $row["max"];
			    $min += $row["min"];
			    $responden += $row["responden"];
			}
			//mysql_free_result($result);

			// UPDATE IF EXISTS
			$sql = "
				UPDATE ".$thedatabase.".komoditi_distribusi 
				SET 
					longitude = '".$lon."',
					latitude = '".$lat."',
					harga = '".$avgPrice."',
					max = '".$max."',
					min = '".$min."',
					responden = '".$responden."',
					stddev = '".$stdPrice."',
					satuan = '".$satuan."',
					komoditi = '".$komoditi_id."'
				WHERE 
					kode_pos = '".$kodePos."' AND
					jenis_sentra = '".$jenisSentra."'
			";
			mysql_query($sql,$apicon);
		} else {
			// INSERT AVERAGE IF NOT EXISTS
			$sql = "
				INSERT INTO ".$thedatabase.".komoditi_distribusi (`kode_pos`,`jenis_sentra`,`longitude`,`latitude`,`harga`,`max`,`min`,`satuan`,`komoditi`,`responden`)
				VALUES ('".$kodePos."','".$jenisSentra."','".$lon."','".$lat."','".$unitPrice."','".$max."','".$min."','".$satuan."','".$komoditi_id."','".$responden."')
			";
			$query3 = mysql_query($sql,$apicon);
		
		}
		
		/*
		$jmlsession=mysql_num_rows($hasilsession);
		$datasession  = mysql_fetch_array($hasilsession);
		$npwz = $datasession['npwz'];
		*/
		$log=array('status_code'=>'000', 'status'=>'Sukses');
		if(empty($callback) || is_null($callback)) $data = json_encode($log);
		else $data = $callback.'('.json_encode($log).')';
	}

}
echo $data;

?>
