<?php
	//Will log the user out and redirect to login.html given correct GET conditions
	if(isset($_GET['status']) && $_GET['status'] == 'logout'){
		setcookie("Status", "", time()-3600);
		setcookie("Person", "", time()-3600);
		setcookie("Role", "", time()-3600);
		header("Location:Login.html");
	}
?>
<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			
			$selectedRow; 
			if(isset($_POST['confirm'])){
				$username=$_POST['username'];
				$password=$_POST['password'];	
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
				while(($row = oci_fetch_array($stid, OCI_ASSOC))){
					$selectedRow = $row;
					$count = $count +1;
					}		 		
		 		if($count == 0){echo '<h2>Incorrect username or password!</h2>';}
		 		else {
		 			//Creating a cookie that lasts a day
		 			
		 			$role = $selectedRow["ROLE"];
		 			echo $role;
		 			echo 
		 			setcookie("Person", $selectedRow["PERSON_ID"], time()+60*60*24);
		 			setcookie("Role", $role, time()+60*60*24);
		 			setcookie("Status", "LoggedIn", time()+60*60*24);
		 			
		 			if($role == 'a'){
		 				header("Location:admin.php");
		 			} elseif($role == 's'){
				 		header("Location:scientist.php");	
		 			} elseif($role == 'd'){
						header("Location:dataCurator.php");		 			
		 			}else{
		 				header("Location:login.php");
		 			}
		 			
		 			
		 		}
		
			}
		?>
	</body>
</html>
