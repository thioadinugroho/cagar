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

?>
