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
			
			//If passed from the proper form
			if(isset($_POST['updatepassword'])){
				
				//Extract provided user name, and both passwords entered
				$username=$_POST['username'];	
				$password1=$_POST['password1'];
				$password2=$_POST['password2'];
				
				//If the two passwords do not match, abort
				if(strcmp((string)$password1, (string)$password2) != 0){
					header('Refresh: 3; url = index.php');
					echo '<h2>New passwords did not match, returning to user page...</h2>';
					exit;
				}
				
				$conn=connect();
				
				//Pull user based on user name
				$sql = '	SELECT *
							FROM users
							WHERE user_name = \''.$username.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch($stid);
				
				//If the user does not exist, abort
				if($row== NULL) {
					header('Refresh: 3; url = index.php');
					echo '<h2>'.$username.' does not exist, returning to user page...</h2>';
					exit;				
				}	
				
				else {
					//Update the username with the provided, matching password
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
					
					//Redirect back to index
		 			header('Refresh: 3; url = index.php');
		 			echo '<h2>'.$username.' password changed successfully, returning to user page...</h2>';
				}
			}
		
		?>
	</body>
</html>				