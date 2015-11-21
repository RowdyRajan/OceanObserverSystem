<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['updaterole'])){
				$username=$_POST['username'];	
				$role = $_POST['role'];
				
				$conn=connect();
				$sql = '	SELECT *
							FROM users
							WHERE user_name = \''.$username.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch($stid);
				if($row== NULL) {
					header('Refresh: 3; url = index.php');
					echo '<h2>'.$username.' does not exist, returning to user page...</h2>';
					exit;				
				}	
				else {
					$conn=connect();
					$sql = '	UPDATE users
								SET role = \''.$role.'\'
								WHERE user_name = \''.$username.'\'';
					$stid = oci_parse($conn, $sql);
					$res = oci_execute($stid, OCI_DEFAULT);
					if (!$res) {
						$err = oci_error($stid);
						echo htmlentities($err['message']);
		 			}
					$res = oci_commit($conn);
					
		 			header('Refresh: 3; url = index.php');
		 			echo '<h2>'.$username.' role succesfully changed, returning to login page...</h2>';
				}
			}
		
		?>
	</body>
</html>	