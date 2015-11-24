
<?php
	//Will log the user out and redirect to index.php given correct GET conditions
	if(isset($_GET['status']) && $_GET['status'] == 'logout'){
		setcookie("Status", "", time()-3600);
		setcookie("Person", "", time()-3600);
		setcookie("Role", "", time()-3600);
		setcookie("Username", time()-3600);
		header("Location:index.php");
	}
?>

<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			//If logged in from index.php
			if(isset($_POST['confirm'])){
								
				$username=$_POST['username'];
				$password=$_POST['password'];	

				//Getting the user from the given data				
				$conn=connect();
				$sql = '	SELECT *
							FROM users u
							WHERE u.user_name = \''.$username.'\'
							AND u.password = \''.$password.'\'';
				$stid = oci_parse($conn, $sql);
				$res=oci_execute($stid, OCI_DEFAULT);
				if (!$res) {
					$err = oci_error($stid);
					echo htmlentities($err['message']);
		 		}

				$row = oci_fetch_array($stid, OCI_ASSOC);
				$selectedRow = $row;

				//If no user that matches the given information then display a message and return to index.php
		 		if($row == NULL){
					header('Refresh: 3; url = index.php');		 			
		 			echo '<h2>Incorrect username or password!</h2>';
		 			exit;}
				
				//IF the row is not empty create correct cookies for the user 
		 		elseif($_POST['confirm'] == "Log In") {
		 			

		 			//Creating a cookie that lasts a day
		 			
		 			$role = $selectedRow["ROLE"];
		 			setcookie("Person", $selectedRow["PERSON_ID"], time()+60*60*24);
		 			setcookie("Role", $role, time()+60*60*24);
		 			setcookie("Status", "LoggedIn", time()+60*60*24);
		 			setcookie("Username", $selectedRow["USER_NAME"],time()+60*60*24);
		 			
		 			//Redirect to correct page based on user
		 			if($role == 'a'){
		 				header("Location:admin.php");
		 				exit();
		 			} elseif($role == 's'){
				 		header("Location:scientist.php");
				 		exit();	
		 			} elseif($role == 'd'){
						header("Location:dataCurator.php");
						exit();		 			
		 			}
		 		} 
		 	}
		?>
	</body>
</html>

