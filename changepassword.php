<?php
include	("PHPconnectionDB.php");
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
			if(isset($_POST['changepassword'])){
				//Extract the username, and both passwords typed in
				$username=$_COOKIE['Username'];	
				$password1=$_POST['password1'];
				$password2=$_POST['password2'];
				
				//If the user has entered two different passwords, abort and redirect to index
				if(strcmp((string)$password1, (string)$password2) != 0){
					header('Refresh: 3; url = index.php');
					echo '<h2>New Passwords did not match, returning to user page...</h2>';
				}
				else {
					//Update the user's password
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
					
					//Delete cookies, send them back to login:
		 			header('Refresh: 3; url = index.php');
		 			setcookie("Status", "", time()-3600);
					setcookie("Person", "", time()-3600);
					setcookie("Role", "", time()-3600);
					setcookie("Username", time()-3600);
		 			echo '<h2>Password changed successfully, returning to login page...</h2>';
				}
			}
		
		?>
	</body>
</html>				