<?php

//koneksi ke database di sistem A
$apicon = mysql_connect("localhost", "root", "r!c3K1cker");
mysql_select_db("alpahorion",$apicon);

$thedatabase = 'alphaorion';

if (!$apicon) {
    die("Connection failed: " . mysql_connect_error());
}

/*
$con=mysqli_connect("localhost", "baznasgo_corner", "tW_d6BSFIBvR","baznasgo_alpahorion");
// Check connection
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
*/

function goLog($meta='',$get=array(),$apicon=true) {
	$sql = "
		INSERT INTO ".$thedatabase.".komoditi_logs (`meta`,`message`)
		VALUES ('".$meta."','".json_encode($get)."');
	";
	mysql_query($sql,$apicon);
}

/**
 * convert_date
 *
 * to convert a date format automatically from human readable to system /
 * sql readable
 *
 * @access	public
 * @param	string
 * @return string
 */
if ( ! function_exists('convert_date'))
{
	function convert_date($date = '')
	{
    if (strpos($date, '-') === false) {
      $datearr = explode('/',$date);
      if($date != '') $newdate = ((strlen($datearr[2]) == '2')?'20'.$datearr[2]:$datearr[2]).'-'.$datearr[1].'-'.$datearr[0];
      else $newdate = '0000-00-00';
    } else {
      $datearr = explode('-',$date);
      if($date != '') $newdate = $datearr[2].'/'.$datearr[1].'/'.((strlen($datearr[0]) == '2')?'20'.$datearr[0]:$datearr[0]);
      else $newdate = '00/00/0000';
    }
    return $newdate;
	}
}

// ------------------------------------------------------------------------

/**
 * Calculate Confidence Level
 *
 * This function is to calculate the confidence level
 *
 * @access	public
 * @param	string
 * @return string
 */
function calculateConfidenceLevel($avg,$min,$max,$std,$n) {
	//echo $avg.';'.$min.';'.$max.';'.$std.';'.$n.'<br />';
	$z1 = abs($avg - $min)*sqrt($n)/$std;
	$z2 = abs($max - $avg)*sqrt($n)/$std;
	if($z1 < $z2) {
		$z = $z1;
	} else {
		$z = $z2;
	}
	$y = 0.055*$z*$z*$z - 0.442*$z*$z + 1.212*$z - 0.144;
	return number_format($y,2)*100;
}

// ------------------------------------------------------------------------


/**
 * isKomoditiMatch
 *
 * This function is to check and autocorrect word(s)
 *
 * @access	public
 * @param	string
 * @return string
 */
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

// ------------------------------------------------------------------------

?>
