<?php
//Connects to the SQL database.
function connect(){
	//Insert your own oracle username and password here:
	$conn = oci_connect('cpchmila', 'AlbertaCanada1');
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	return $conn;
}
?>