<?php
	include("PHPconnectionDB.php");
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
		
		if($row != NULL){
			header("location:admin.php?sError=takenSensorname");
			exit();		
		}
	}
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
		
		if($row == NULL){
			header("location:admin.php?sError=invalidSensorname");
			exit();		
		}
	}
	
	if(isset($_POST["submitAddSensor"])) {
		$sid = $_POST["sensorID"];
		$location = $_POST['location'];
		$description = $_POST['sensorDescription'];
		$sensor_type = $_POST['type'];
		
		SensorExists($sid);
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
		header("location:admin.php?sSuccess=addedSensor");
		exit();	
	} elseif(isset($_POST["submitRemoveSensor"])){
		$sid = $_POST["sensorID"];
		SensorNotExists($sid);
		
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
		header("location:admin.php?sSuccess=removedSensor");
		exit();	
	}
?>