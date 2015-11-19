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
		
		if($row != NULL){
			header("location:admin.php?dError=invalidUsername");		
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
		}
		$res = oci_commit($conn);
		header("location:admin.php?dSuccess=user");
			
	}
?>