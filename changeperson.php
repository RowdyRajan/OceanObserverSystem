<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['changeperson'])){
				$person=$_COOKIE['Person'];
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$addr=$_POST['addr'];
				$email=$_POST['email'];
				$phone=$_POST['phone'];

				$conn=connect();
				$sql = '	UPDATE persons
							SET 	first_name = \''.$fname.'\',
									last_name = \''.$lname.'\',
									address = \''.$addr.'\',
									phone = \''.$phone.'\', 
									email = \''.$email.'\'
							WHERE person_id = \''.$person.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					header('Refresh: 3; url = index.php');
		 			echo '<h2>Error: invalid entry change, returning to user page...</h2>';
		 			exit;
		 		}
		 		$res = oci_commit($conn);
					
		 		header('Refresh: 3; url = index.php');
		 		echo '<h2>Information successfully changed, returning to user page...</h2>';
				}
		
		?>
	</body>
</html>