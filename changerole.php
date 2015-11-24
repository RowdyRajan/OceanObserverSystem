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
			if(isset($_POST['updaterole'])){
				//Exctract username and role passed by admin
				$username=$_POST['username'];	
				$role = $_POST['role'];
				
				//Check if username exists
				$conn=connect();
				$sql = '	SELECT *
							FROM users
							WHERE user_name = \''.$username.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				$row = oci_fetch($stid);
				
				//If user does not exist, abort and return to index
				if($row== NULL) {
					header('Refresh: 3; url = index.php');
					echo '<h2>'.$username.' does not exist, returning to user page...</h2>';
					exit;				
				}	
				
				else {
					//Chnage the user's role
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
					
					//Return to user page
		 			header('Refresh: 3; url = index.php');
		 			echo '<h2>'.$username.' role successfully changed, returning to User page...</h2>';
				}
			}
		
		?>
	</body>
</html>	