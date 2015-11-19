
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

				$row = oci_fetch_array($stid, OCI_ASSOC);
				$selectedRow = $row;

		 		if($row == NULL){echo '<h2>Incorrect username or password!</h2>';}

		 		elseif($_POST['confirm'] == "Log In") {
		 			

		 			//Creating a cookie that lasts a day
		 			
		 			$role = $selectedRow["ROLE"];
		 			setcookie("Person", $selectedRow["PERSON_ID"], time()+60*60*24);
		 			setcookie("Role", $role, time()+60*60*24);
		 			setcookie("Status", "LoggedIn", time()+60*60*24);
		 			setcookie("Username", $selectedRow["USER_NAME"],time()+60*60*24);
		 			
		 			if($role == 'a'){
		 				header("Location:admin.php");
		 			} elseif($role == 's'){
				 		header("Location:scientist.php");	
		 			} elseif($role == 'd'){
						header("Location:dataCurator.php");		 			
		 			}else{
		 				
		 				//header("Location:index.php");
		 			}

		 		} 
		 		elseif($_POST['confirm'] == "Change Password") {
					echo '<h1>Password Change for '.$username.'</h1>';
					echo '<form name = "changepass" method = "post" action = "changepassword.php">';
					echo '<input type = "hidden" name="usr" value = "'.$username.'" />';
					echo 'New Password: <input type="password" name="password1"/><br/>';
					echo 'Repeat New Password: <input type="password" name="password2"/><br/>';
					echo '<input type = "submit" name = "change" value = "Change Password"/><form/>';
				}
				elseif($_POST['confirm'] == "Modify Personal Info") {
					$sqlp = '  	SELECT *
									FROM persons p
									WHERE p.person_id = \''.$row[3].'\'';
					$stidp = oci_parse($conn, $sqlp);
					$res = oci_execute($stidp, OCI_DEFAULT);
					if (!$res) {
						$err = oci_error($stidp);
						echo htmlentities($err['message']);
		 			}
		 			$persons = oci_fetch_row($stidp);
					echo '<h1>Personal Information change for '.$persons[1].' '.$persons[2].' </h1>';
					echo '<form name = "changeperson" method = "post" action = "changeperson.php">';
					echo '<input type = "hidden" name="pid" value = "'.$persons[0].'" />';
					echo 'First Name: <input type = "text" name="fname" value = '.$persons[1].' /> <br/>';
					echo 'Last Name:	<input type = "text" name="lname" value = '.$persons[2].' /> <br/>';
					echo 'Address:		<input type = "text" name="addr" value = '.$persons[3].' /> <br/>';
					echo 'Email:		<input type = "text" name="email" value = '.$persons[4].' /> <br/>';
					echo 'Phone:		<input type = "text" name="phone" value = '.$persons[5].' /> <br/>';
					echo '<input type = "submit" name = "change" value = "Change Personal Info" /></form>';
					
				}
		 	}
		?>
	</body>
</html>

