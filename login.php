<html>
	<body>
		<h1>Confirm Test</h1>
		<?php
			include("PHPconnectionDB.php");
			if(isset($_POST['confirm'])){
				$username=$_POST['username'];
				$password=$_POST['password'];	
				$conn=connect();
				$sql = '	SELECT u.user_name
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
					foreach($row as $item){
					$count = $count +1;}
					}		 		
		 		if($count == 0){echo '<h2>Incorrect username or password!</h2>';}
		 		else {echo '<p>Login successful</p>';}
		
			}
		?>
	</body>
</html>