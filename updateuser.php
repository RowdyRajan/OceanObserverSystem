<?php
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn"){	 }
else{
header("Location:index.php");		 
} 
?>
<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['updatepassword'])){
				$username=$_POST['username'];	
				$password1=$_POST['password1'];
				$password2=$_POST['password2'];
				if(strcmp((string)$password1, (string)$password2) != 0){
					header('Refresh: 3; url = index.php');
					echo '<h2>New passwords did not match, returning to user page...</h2>';
					exit;
				}
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
		 			echo '<h2>'.$username.' password changed successfully, returning to user page...</h2>';
				}
			}
		
		?>
	</body>
</html>				