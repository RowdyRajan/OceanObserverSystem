<html>
 <body>
 <?php
	include("PHPconnectionDB.php");
	if(isset($_POST['sensor_id']) && isset($_COOKIE['Person']) && $_COOKIE['Status'] == "LoggedIn"){
		$sensor_id=$_POST['sensor_id'];
		$person_id=$_COOKIE['Person'];
	$conn=connect();

	$sql = 'INSERT INTO subscriptions VALUES(\''.$sensor_id.'\', \''.$person_id.'\')';
	$stid = oci_parse($conn, $sql);
	$res=oci_execute($stid);
	if (!$res) {
		$err = oci_error($stid);
		echo htmlentities($err['message']);
	} else {echo $sensor_id;}
	
	//commit the change
	$res = oci_commit($conn);
	if (!$res) {
		$err = oci_error($conn);
    trigger_error(htmlentities($err['message']), E_USER_ERROR);
	
	}
	
	// Free the statement identifier when closing the connection
	oci_free_statement($stid);
	oci_close($conn);
		
	header("Location:scientist.php");
	exit();
	}
	else{
		header("Location:index.php");		 
	} 
	?>
 </body>
</html> 