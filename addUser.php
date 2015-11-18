<?php
	include("PHPconnectionDB.php");
	if(isset($_POST["submitNewUser"])){
		$pid = $_POST["newPersonID"]
		$fname=$_POST['newFirstName'];
		$lname=$_POST['newLastName'];
		$address=$_POST['newAdress'];
		$email=$_POST['newEmail'];
		$phone=$_POST['newPhoneNumber'];
		INSERT INTO persons (first_name, last_name, address, email, phone)
VALUES (\''.$fname.'\',\''.$lname.'\',\''.$address.'\',\''.$email.'\',\''.$phone.'\');
	}
?>