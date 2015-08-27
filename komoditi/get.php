<?php

include "../config.php";
header('Content-type: text/json');
// membaca refno dari GET request
$periodeDari 	= $_GET['periodeDari']; 	// pasar
$periodeHingga 	= $_GET['periodeHingga'];		// ps. baru
$query 			= $_GET['query'];			// 12345
// membaca session dari GET request
$callback		= $_GET['cb'];

goLog('get',$_GET,$apicon);

if(empty($periodeDari) || empty($periodeHingga) || empty($query))
{
	$log=array('status_code'=>'101', 'status'=>'All data is required');
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';
}
else
{
	if($query == 'dashboard') {
		$sql = "
			SELECT AVG(unitprice) AS harga, MAX(unitprice) AS max, MIN(unitprice) AS min, STD(unitprice) AS stddev, COUNT(unitprice) AS responden
			FROM
				".$thedatabase.".komoditi_raw
			WHERE
				timestamp BETWEEN '".convert_date($periodeDari)."%' AND '".convert_date($periodeHingga)."'
				jenis_sentra LIKE 'pasar'
		";
		/*
		$sql = "
			SELECT AVG(unitprice) AS harga, MAX(unitprice) AS max, MIN(unitprice) AS min, STD(unitprice) AS stddev, COUNT(unitprice) AS responden
			FROM
				baznasgo_alphaorion.komoditi_raw
			WHERE
				timestamp LIKE '2015-08-22%' AND
				jenis_sentra LIKE 'pasar'
		";
		*/
		$query = mysql_query($sql,$apicon);
		$dataquery  = mysql_fetch_array($query);
		//$hargaProdusen = $dataquery3['harga'];
		$komoditi['pasar']['average'] = is_null($dataquery['harga'])?0:$dataquery['harga'];
		$komoditi['pasar']['max'] = is_null($dataquery['max'])?0:$dataquery['max'];
		$komoditi['pasar']['min'] = is_null($dataquery['min'])?0:$dataquery['min'];
		$komoditi['pasar']['stddev'] = is_null($dataquery['stddev'])?0:$dataquery['stddev'];
		if($komoditi['pasar']['stddev'] == 0) {
			$komoditi['pasar']['confidence_level'] = 99;
		} else $komoditi['pasar']['confidence_level'] = calculateConfidenceLevel($komoditi['pasar']['average'],$komoditi['pasar']['min'],$komoditi['pasar']['max'],$komoditi['pasar']['stddev'],$dataquery['responden']);

		
		$sql = "
			SELECT AVG(unitprice) AS harga, MAX(unitprice) AS max, MIN(unitprice) AS min, STD(unitprice) AS stddev, COUNT(unitprice) AS responden
			FROM
				".$thedatabase.".komoditi_raw
			WHERE
				timestamp BETWEEN '".convert_date($periodeDari)."%' AND '".convert_date($periodeHingga)."'
				jenis_sentra LIKE 'produsen'
		";
		/*
		$sql = "
			SELECT AVG(unitprice) AS harga, MAX(unitprice) AS max, MIN(unitprice) AS min, STD(unitprice) AS stddev, COUNT(unitprice) AS responden
			FROM
				baznasgo_alphaorion.komoditi_raw
			WHERE
				timestamp LIKE '2015-08-22%' AND
				jenis_sentra LIKE 'produsen'
		";
		*/
		$query = mysql_query($sql,$apicon);
		$dataquery  = mysql_fetch_array($query);
		//$hargaProdusen = $dataquery3['harga'];
		$komoditi['produsen']['average'] = is_null($dataquery['harga'])?0:$dataquery['harga'];
		$komoditi['produsen']['max'] = is_null($dataquery['max'])?0:$dataquery['max'];
		$komoditi['produsen']['min'] = is_null($dataquery['min'])?0:$dataquery['min'];
		$komoditi['produsen']['stddev'] = is_null($dataquery['stddev'])?0:$dataquery['stddev'];
		if($komoditi['produsen']['stddev'] == 0) {
			$komoditi['produsen']['confidence_level'] = 99;
		} else $komoditi['produsen']['confidence_level'] = calculateConfidenceLevel($komoditi['produsen']['average'],$komoditi['produsen']['min'],$komoditi['produsen']['max'],$komoditi['produsen']['stddev'],$dataquery['responden']);

		$log=array('status_code'=>'000', 'status'=>'Success','komoditi'=>$komoditi);
		if(empty($callback) || is_null($callback)) $data = json_encode($log);
		else $data = $callback.'('.json_encode($log).')';		
	} else {

		// GET PROVINSI
		$hargaProdusen = 0;
		$hargaKonsumen = 0;
		$komoditi = array();
		$sql = "
			SELECT propinsi 
			FROM 
				".$thedatabase.".kodepos
			GROUP BY propinsi
		";
		$query = mysql_query($sql,$apicon);
		$i=0;
		while($row = mysql_fetch_assoc($query)) {
			$komoditi[$i]['provinsi'] = $row['propinsi'];	// PROVINSI NAME
			$komoditi[$i]['average_produsen'] = 0;
			$komoditi[$i]['average_pasar'] = 0;
			$komoditi[$i]['unit_price'] = '';
			$sql = "
				SELECT kodepos
				FROM
					".$thedatabase.".kodepos
				WHERE
					propinsi LIKE '".$row['propinsi']."'
				GROUP BY kodepos
			";
			$query2 = mysql_query($sql,$apicon);
			while($row2 = mysql_fetch_assoc($query2)) {
				// GET AVERAGE FOR PRODUSEN
				$sql = "
					SELECT harga, satuan
					FROM
						".$thedatabase.".komoditi_distribusi
					WHERE 
						kode_pos = '".$row2['kodepos']."' AND
						jenis_sentra LIKE 'produsen';
				";
				$query3 = mysql_query($sql,$apicon);
				$dataquery3  = mysql_fetch_array($query3);
				//$hargaProdusen = $dataquery3['harga'];
				$komoditi[$i]['average_produsen'] += is_null($dataquery3['harga'])?0:$dataquery3['harga'];

				// GET AVERAGE FOR PASAR
				$sql = "
					SELECT harga, satuan
					FROM
						".$thedatabase.".komoditi_distribusi
					WHERE 
						kode_pos = '".$row2['kodepos']."' AND
						jenis_sentra LIKE 'pasar';
				";
				$query4 = mysql_query($sql,$apicon);
				$dataquery4  = mysql_fetch_array($query4);
				//$hargaKonsumen = $dataquery3['harga'];
				$komoditi[$i]['average_pasar'] += is_null($dataquery4['harga'])?0:$dataquery4['harga'];
				$komoditi[$i]['unit_price'] = is_null($dataquery4['satuan'])?'kg':$dataquery4['satuan'];
			}
			//if($i == 1) break;
		    $i++;
		}

		$log=array('status_code'=>'000', 'status'=>'Sukses', 'komoditi'=>$komoditi);
		if(empty($callback) || is_null($callback)) $data = json_encode($log);
		else $data = $callback.'('.json_encode($log).')';
	}

}
echo $data;

?>
