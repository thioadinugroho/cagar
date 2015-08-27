<?php

include "../config.php";
header('Content-type: text/json');
// membaca refno dari GET request
$hp 		= $_GET['hp'];			
// membaca session dari GET request
$callback		= $_GET['cb'];

goLog('points',$_GET,$apicon);

if(empty($hp))
{
	$log=array('status_code'=>'101', 'status'=>'All data is required','data'=>$_GET);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';
}
else
{
	$sql = "
		SELECT 
			SUM(points) AS sum
		FROM
			".$thedatabase.".komoditi_raw
		WHERE
			handphone LIKE '".$hp."';
	";

	$query = mysql_query($sql,$apicon);
	$dataquery  = mysql_fetch_array($query);
	$point = is_null($dataquery['sum'])?0:$dataquery['sum'];
	
	$log=array('status_code'=>'000', 'status'=>'Success','point'=>$point,'hp'=>$hp);
	if(empty($callback) || is_null($callback)) $data = json_encode($log);
	else $data = $callback.'('.json_encode($log).')';

}
echo $data;

?>
