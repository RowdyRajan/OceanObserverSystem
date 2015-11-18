<?php
	include("PHPconnectionDB.php");
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
		
		$conn = connect();
		$sql = ' INSERT INTO persons (person_id,first_name, last_name, address, email, phone)
					VALUES (\''.$pid.'\',\''.$fname.'\',\''.$lname.'\',\''.$address.'\',\''.$email.'\',\''.$phone.'\')';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		$res = oci_commit($conn);
			
		$sql = 'INSERT INTO users(user_name, password, role, person_id, date_registered)
				VALUES(\''.$username.'\',\''.$password.'\',\''.$role.'\', \''.$pid.'\' , to_date(sysdate, \'dd/mm/yyyy\'))';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		$res = oci_commit($conn);
	} elseif(isset($_POST["submitExistingNewUser"])) {
		$email = $_POST['newEmail'];
		$username = $_POST["newUsername"];
		$password = $_POST["newPassword"];
		$role = $_POST["role"];
		
		$sql = 'SELECT *
					FROM persons
					WHERE email = \''.$email.'\'';
		
		$conn = connect();
		$stid = oci_parse($conn, $sql);
		
		$res=oci_execute($stid, OCI_DEFAULT);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
		}
		
		$row = oci_fetch_array($stid, OCI_ASSOC);
		
		if($row == NULL){
					
		}else{
			echo $row['PERSON_ID'];		
		}
	}
?>