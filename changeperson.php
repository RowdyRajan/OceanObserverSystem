<?php
//Redirects login if not signed in
if(isset($_COOKIE['Status']) && $_COOKIE['Status'] == "LoggedIn" ){	 }
else{
header("Location:index.php");		 
} 
?>
<html>
	<body>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['changeperson'])){
				if($_POST['fromDash'] == '1'){$person = $_POST['id'];}				
				else{$person=$_COOKIE['Person'];}
				//extract information person has passed
				$fname=$_POST['fname'];
				$lname=$_POST['lname'];
				$addr=$_POST['addr'];
				$email=$_POST['email'];
				$phone=$_POST['phone'];

				//Update personal information
				$conn=connect();
				$sql = '	UPDATE persons
							SET 	first_name = \''.$fname.'\',
									last_name = \''.$lname.'\',
									address = \''.$addr.'\',
									phone = \''.$phone.'\', 
									email = \''.$email.'\'
							WHERE person_id = \''.$person.'\'';
				$stid = oci_parse($conn, $sql);
				$res = oci_execute($stid, OCI_DEFAULT);
				
				//If information is incorect or invalid, abort
				if (!$res) {
					$err = oci_error($stid);
					header('Refresh: 3; url = index.php');
		 			echo '<h2>Error: invalid entry change, returning to user page...</h2>';
		 			exit;
		 		}
		 		$res = oci_commit($conn);
				//Send user back to index
		 		header('Refresh: 3; url = index.php');
		 		echo '<h2>Information successfully changed, returning to user page...</h2>';
				}
		
		?>
	</body>
</html>