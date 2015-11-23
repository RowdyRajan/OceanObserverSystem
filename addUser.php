<?php
include	("PHPconnectionDB.php");
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" && $_COOKIE["Role"] == 'a' ){	 }
else{
header("Location:index.php");		 
} 
?>

<?php
	include("PHPconnectionDB.php");
	//Checks to see if username is already in the database
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
			header("location:admin.php?error=takenUsername");
			exit();		
		}
	}
	
	//Checks if a person already has the added role 
	function validRole ($pid,$role) {
		$sql = 'SELECT *
					FROM users
					WHERE person_id = \''.$pid.'\' 
					AND role = \''.$role.'\'';
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			//header("location:admin.php?error=general");
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		if($row != NULL){
			header("location:admin.php?error=roleTaken");
			exit();		
		}
	}
	//New user button was clicked
	if(isset($_POST["submitNewUser"])){
		
		$pid  = $_POST["newPersonID"];
		$fname = $_POST['newFirstName'];
		$lname = $_POST['newLastName'];
		$address = $_POST['newAdress'];
		$email = $_POST['newEmail'];
		$phone = $_POST['newPhoneNumber'];
		$username = $_POST["newUsername"];
		$password = $_POST["newPassword"];
		$role = $_POST["role"];
		
		validUserName($username);
		
		$conn = connect();
		$sql = ' INSERT INTO persons (person_id,first_name, last_name, address, email, phone)
					VALUES (\''.$pid.'\',\''.$fname.'\',\''.$lname.'\',\''.$address.'\',\''.$email.'\',\''.$phone.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?error=general");
			exit();
		}
		$res = oci_commit($conn);
			
		$sql = 'INSERT INTO users(user_name, password, role, person_id, date_registered)
				VALUES(\''.$username.'\',\''.$password.'\',\''.$role.'\', \''.$pid.'\' , to_date(sysdate, \'dd/mm/yyyy\'))';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			//header("location:admin.php?error=general");
		}
		$res = oci_commit($conn);
		header("location:admin.php?success=generalAdd");
		exit();
	} elseif(isset($_POST["submitExistingNewUser"])) {
		$email = $_POST['newEmail'];
		$username = $_POST["newUsername"];
		$password = $_POST["newPassword"];
		$role = $_POST["role"];
		
		validUserName($username);
		
		$sql = 'SELECT *
					FROM persons
					WHERE email = \''.$email.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			//header("location:admin.php?error=general");
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		if($row == NULL){
			header("location:admin.php?error=invalidEmail");
			exit();		
		}
		validRole($row["PERSON_ID"],$role);
		
		$sql = 'INSERT INTO users(user_name, password, role, person_id, date_registered)
				VALUES(
				\''.$username.'\',
				\''.$password.'\',
				\''.$role.'\',
				(SELECT p.person_id
				FROM persons p
				WHERE p.email = \''.$email.'\'),
				to_date(sysdate, \'dd/mm/yyyy\'))';
		
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
			header("location:admin.php?error=general");
			exit();
		}
		
		$res = oci_commit($conn);
		header("location:admin.php?success=generalAdd");
		exit();
	}
?>