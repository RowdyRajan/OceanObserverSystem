<html>
 <body>
 <?php
	include("PHPconnectionDB.php");
	if(isset($_POST['sensor_id'])){
		$sensor_id=$_POST['sensor_id'];
		$person_id=2; //NEED TO GET THE ID OF THE PERSON LOGGED ON!
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
	?>
 </body>
</html> 