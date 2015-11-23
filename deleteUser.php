<?php
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn"){	 }
else{
header("Location:index.php");		 
} 
?>

<?php
	include("PHPconnectionDB.php");
	
	//Checks to see if a username exists 
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
		
		//If no username is found		
		if($row == NULL){
			header("location:admin.php?dError=invalidUsername");
			exit();		
		}
	}
	
	//Delete a user is clicked
	if(isset($_POST["submitDeleteUser"])){
		$username = $_POST["deleteUsername"];
		
		//Checks if the username exists	
		validUserName($username);
		
		//Statement to delete the user		
		$conn = connect();
		$sql = 'DELETE FROM users WHERE (user_name = \''.$username.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		//Return with error on failure
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$res = oci_commit($conn);
		
		//Return on success
		header("location:admin.php?dSuccess=user");
		exit();
			
		//If deleting a person is clicked 
	} elseif(isset($_POST["submitDeletePerson"])){
		 		
		$email = $_POST["deleteEmail"];
		
		//Get the person with the email
		$sql = 'SELECT *
					FROM persons
					WHERE email = \''.$email.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		//Return with error on failure
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		//If no rows were found it is an invalid email
		if($row == NULL){
			header("location:admin.php?error=invalidEmail");
			exit();		
		}
		
		//Deleting the user associated with the email
		$pid = $row["PERSON_ID"];
		$sql = 'DELETE FROM users WHERE (person_id = \''.$pid.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		
		//Return with error on failure
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?dError=general");
			exit();
		}
		
		$res = oci_commit($conn);
		
		//Deleting the person associated with the email
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
		
		//Return with success
		header("location:admin.php?dSuccess=person");
		exit();	
		}
?>