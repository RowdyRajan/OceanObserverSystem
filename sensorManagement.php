<?php
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn"){	 }
else{
header("Location:index.php");		 
} 
?>

<?php
	include("PHPconnectionDB.php");
	
	//Checks if a sensor id is already in the database
	function SensorExists($sid){
		$sql = 'SELECT *
					FROM sensors
					WHERE sensor_id = \''.$sid.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		//If there is a row that sensor already is taken
		if($row != NULL){
			header("location:admin.php?sError=takenSensorname");
			exit();		
		}
	}
	
	//Checks if a sensor is in the database already and returns if it is 
	function SensorNotExists($sid){
		$sql = 'SELECT *
					FROM sensors
					WHERE sensor_id = \''.$sid.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		//If there is not a row the sensor does not exist
		if($row == NULL){
			header("location:admin.php?sError=invalidSensorname");
			exit();		
		}
	}
	
	//If add sensor was clicked
	if(isset($_POST["submitAddSensor"])) {
		
		//Getting the correct data 		
		$sid = $_POST["sensorID"];
		$location = $_POST['location'];
		$description = $_POST['sensorDescription'];
		$sensor_type = $_POST['type'];
		
		//Checking to make sure the sensor is not in the database 
		SensorExists($sid);
		
		//Executing the query 
		$conn = connect();
		$sql = ' INSERT INTO sensors (sensor_id, location, sensor_type, description)
					VALUES (\''.$sid.'\',\''.$location.'\',\''.$sensor_type.'\',\''.$description.'\')';	
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?sError=general");
			exit();
		}
		$res = oci_commit($conn);
		
		//Return with success get variable on success		
		header("location:admin.php?sSuccess=addedSensor");
		exit();	
	} elseif(isset($_POST["submitRemoveSensor"])){
		
		$sid = $_POST["sensorID"];
		
		//Check to make sure the sensor is already in the database so it can be deleted		
		SensorNotExists($sid);
		
		//Deleting the sensor
		$conn = connect();
		$sql = ' DELETE FROM sensors WHERE (sensor_id = \''.$sid.'\')';	
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?sError=deleteGeneral");
			exit();
		}
		$res = oci_commit($conn);

		//Return with success get variable on success
		header("location:admin.php?sSuccess=removedSensor");
		exit();	
	}
?>