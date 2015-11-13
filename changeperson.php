<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['change'])){
				$person=$_POST['pid'];
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$addr=$_POST['addr'];
				$email=$_POST['email'];
				$phone=$_POST['phone'];

				$conn=connect();
				$sql = '	UPDATE persons
							SET 	first_name = \''.$fname.'\' ,
									last_name = \''.$lname.'\' ,
									address = \''.$addr.'\' ,
									email = \''.$email.'\' ,
									phone = \''.$phone.'\' ,
							WHERE person_id = \''.$person.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
		 		}
		 		$res = oci_commit($conn);
					
		 		header('Refresh: 99; url = Login.html');
		 		echo '<h2>Information successfully changed, returning to login page...</h2>';
				}
		
		?>
	</body>
</html>