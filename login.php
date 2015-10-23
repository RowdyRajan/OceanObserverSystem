<?php
	include("PHPconnectionDB.php");
	if(isset($POST['confirm'])){
		$username=$_POST['username'];
		$password=$_POST['password'];	
		$conn=connect();
		$sql = '	SELECT u.user_name 
					WHERE u.user_name = .$username.
					AND u.password = .$password.';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid);
		$num_rows = mysqli_num_rows($res);
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
 		}
 		else if ($num_rows = 0){echo 'Incorrect username or password';}
 		else {echo 'Login successful';}
 		 
	}
?>