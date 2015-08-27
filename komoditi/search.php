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

function isKomoditiMatch($input='',$tipe='nama',$apicon='') {
global $thedatabase;	
	// array of words to check against
	if($tipe == 'nama') {
		$sql = "
			SELECT nama AS komoditi
			FROM 
				".$thedatabase.".komoditi_jenis
			GROUP BY nama
		";
	} else {
		$sql = "
			SELECT jenis AS komoditi
			FROM 
				".$thedatabase.".komoditi_jenis
			GROUP BY jenis
		";
	}
	$query = mysql_query($sql,$apicon);
	$words = array();
	while($row = mysql_fetch_assoc($query)) {
	    $words[] = $row["komoditi"];
	}
	
	//$words  = array('apple','pineapple','banana','orange','radish','carrot','pea','bean','potato');

	// no shortest distance found, yet
	$shortest = -1;

	// loop through words to find the closest
	foreach ($words as $word) {

	    // calculate the distance between the input word,
	    // and the current word
	    $lev = levenshtein($input, $word);

	    // check for an exact match
	    if ($lev == 0) {

	        // closest word is this one (exact match)
	        $closest = $word;
	        $shortest = 0;

	        // break out of the loop; we've found an exact match
	        break;
	    }

	    // if this distance is less than the next found shortest
	    // distance, OR if a next shortest word has not yet been found
	    if ($lev <= $shortest || $shortest < 0) {
	        // set the closest match, and shortest distance
	        $closest  = $word;
	        $shortest = $lev;
	    }
	}

	/*
	echo "Input word: $input\n";
	if ($shortest == 0) {
	    echo "Exact match found: $closest\n";
	} else {
	    echo "Did you mean: $closest?\n";
	}
	*/
	return $closest;
}
?>
