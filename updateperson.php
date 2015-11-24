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
		if(isset($_POST['updateperson'])){
			
			//Extract email sent from form
			$email = $_POST['email'];
			$conn=connect();
			
			//Pull the person that has identical email
			$sqlp = '  	SELECT *
							FROM persons p
							WHERE p.email = \''.$email.'\'';
			$stidp = oci_parse($conn, $sqlp);
			$res = oci_execute($stidp, OCI_DEFAULT);
			if (!$res) {
				$err = oci_error($stidp);
				echo htmlentities($err['message']);
		 		}		
			$persons = oci_fetch_row($stidp);
			
			//Populate all fields with the person's current information
			//And provide a button to change their information
			echo '<h3>Personal Information change for '.$persons[1].' '.$persons[2].' </h3>';
			echo '<form name = "changeperson" method = "post" action = "changeperson.php">';
			echo '<input type="hidden" name = "fromDash" value="1" />';
			echo '<input type="hidden" name = "id" value='.$persons[0].' />';
			echo 'First Name: <input type = "text" name="fname" value = '.$persons[1].' /> <br/>';
			echo 'Last Name:	<input type = "text" name="lname" value = '.$persons[2].' /> <br/>';
			echo 'Address:		<input type = "text" name="addr" value = '.$persons[3].' /> <br/>';
			echo 'Email:		<input type = "text" name="email" value = '.$persons[4].' /> <br/>';
			echo 'Phone:		<input type = "text" name="phone" value = '.$persons[5].' /> <br/>';
			echo '<input type = "submit" name = "changeperson" value = "Change Personal Info" /></form>';
		}
		?>
	</body>
</html>