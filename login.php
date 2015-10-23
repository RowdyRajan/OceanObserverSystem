<?php
	include("PHPconnectionDB.php");
	if(isset($POST['confirm'])){
		$username=$_POST['username'];
		$password=$_POST['password'];	
		$conn=connect();
		$sql = '	SELECT u.user_name 
					CASE
						WHEN u.username IS NULL THEN 'False'
						ELSE 'True'
					FROM users u
					WHERE u.user_name = .$username.
					AND u.password = .$password.';
		$stid = oci_parse($conn, $sql);
		$res=oci_execute($stid);
		
		if (!$res) {
			$err = oci_error($stid);
			echo htmlentities($err['message']);
 		}
 		else if ($res = 'False'){echo 'Incorrect username or password';}
 		else if ($res = 'True'){echo 'Login successful';}
 		 
	}
?>