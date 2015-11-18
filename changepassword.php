<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['change'])){
				$username=$_POST['usr'];	
				$password1=$_POST['password1'];
				$password2=$_POST['password2'];
				if(strcmp((string)$password1, (string)$password2) != 0){
					header('Refresh: 3; url = index.php');
					echo '<h2>New Passwords did not match, returning to login page...</h2>';
				}
				else {
					$conn=connect();
					$sql = '	UPDATE users
								SET password = \''.$password1.'\'
								WHERE user_name = \''.$username.'\'';
					$stid = oci_parse($conn, $sql);
					$res = oci_execute($stid, OCI_DEFAULT);
					if (!$res) {
						$err = oci_error($stid);
						echo htmlentities($err['message']);
		 			}
					$res = oci_commit($conn);
					
		 			header('Refresh: 3; url = index.php');
		 			echo '<h2>Password changed successfully, returning to login page...</h2>';
				}
			}
		
		?>
	</body>
</html>				