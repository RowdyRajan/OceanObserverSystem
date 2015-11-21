<?php
	include("PHPconnectionDB.php");
	
	function validUserName ($username) {
		$sql = 'SELECT *
					FROM users
					WHERE user_name = \''.$username.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		if($row == NULL){
			header("location:admin.php?dError=invalidUsername");
			exit();		
		}
	}
	
	//Delete a users
	if(isset($_POST["submitDeleteUser"])){
		$username = $_POST["deleteUsername"];
		validUserName($username);
		
		$conn = connect();
		$sql = 'DELETE FROM users WHERE (user_name = \''.$username.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		$res = oci_commit($conn);
		header("location:admin.php?dSuccess=user");
		exit();
			
	} elseif(isset($_POST["submitDeletePerson"])){
		$email = $_POST["deleteEmail"];
		
		$sql = 'SELECT *
					FROM persons
					WHERE email = \''.$email.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		if($row == NULL){
			header("location:admin.php?error=invalidEmail");
			exit();		
		}
		
		$pid = $row["PERSON_ID"];
		$sql = 'DELETE FROM users WHERE (person_id = \''.$pid.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$res = oci_commit($conn);
		
		$sql = 'DELETE FROM persons WHERE (person_id = \''.$pid.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$res = oci_commit($conn);
		
		header("location:admin.php?dSuccess=person");
		exit();	
		}
?>